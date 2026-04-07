<?php
// controllers/SmsController.php — Admin SMS actions for BuligDiretso

require_once ROOT_PATH . 'services/SmsService.php';

class SmsController
{
    /**
     * POST /index.php?action=sms-assign-responder
     * Called from the Assign Responder modal.
     * Sends user assignment update, responder alert, and optional follow-up SMS.
     */
    public function assignResponder(): void
    {
        $this->requireAdmin();

        $emergencyId    = trim($_POST['emergency_id'] ?? '');
        $userPhone      = trim($_POST['user_phone'] ?? '');
        $userName       = trim($_POST['user_name'] ?? '');
        $location       = trim($_POST['location'] ?? '');
        $emergencyType  = trim($_POST['emergency_type'] ?? 'Emergency');
        $responderName  = trim($_POST['responder_name'] ?? '');
        $responderPhone = trim($_POST['responder_phone'] ?? '');
        $etaRaw         = trim($_POST['eta_minutes'] ?? '10');
        $etaCustom      = trim($_POST['eta_custom'] ?? '');
        $sendFollowup   = !empty($_POST['send_followup']);
        $followupMsg    = trim($_POST['followup_message'] ?? '');

        if ($emergencyId === '' || $userPhone === '' || $userName === '' || $location === '' || $responderName === '') {
            $_SESSION['sms_error'] = 'Missing required assignment details.';
            $this->back();
        }

        $eta = ($etaRaw === 'custom' && $etaCustom !== '')
            ? ($etaCustom . ' minutes')
            : ((is_numeric($etaRaw) && (int)$etaRaw >= 60) ? '1 hour' : ($etaRaw . ' minutes'));

        // SMS 1: User gets assignment + ETA update
        $userMsg = "BuligDiretso: Hi {$userName}, your {$emergencyType} report (#{$emergencyId}) has been received. "
                 . "Responder {$responderName} is now ON THE WAY to your location at {$location}. "
                 . "Estimated arrival: {$eta}. Please stay calm and keep your phone on. Stay safe!";
        $userResult = SmsService::send(SmsService::formatPhone($userPhone), $userMsg);

        // SMS 2: Responder gets assignment details
        if ($responderPhone !== '') {
            $respMsg = "BuligDiretso ALERT: You have been assigned to {$emergencyType} #{$emergencyId} at {$location}. "
                     . "User: {$userName} ({$userPhone}). Please respond immediately.";
            $responderResult = SmsService::send(SmsService::formatPhone($responderPhone), $respMsg);
        } else {
            $responderResult = ['success' => false, 'message' => 'Responder phone is blank.'];
        }

        // SMS 3: Optional follow-up clinic reminder (pregnancy/children/medical)
        if ($sendFollowup && $followupMsg !== '') {
            SmsService::send(SmsService::formatPhone($userPhone), $followupMsg);
        }

        // Assignment is accepted regardless of SMS outcome.
        $_SESSION['sms_success'] = "Responder {$responderName} assigned successfully for case #{$emergencyId}.";
        if (!$userResult['success']) {
            $_SESSION['sms_success'] .= " User SMS failed: {$userResult['message']}.";
        } else {
            $_SESSION['sms_success'] .= " User SMS sent.";
        }
        if (!$responderResult['success']) {
            $_SESSION['sms_success'] .= " Responder SMS failed: {$responderResult['message']}.";
        } else {
            $_SESSION['sms_success'] .= " Responder SMS sent.";
        }

        $this->back();
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            exit('Forbidden');
        }
    }

    private function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'index.php?action=users');
        header('Location: ' . $ref);
        exit();
    }
}
