<?php
// controllers/SmsController.php — Admin SMS actions for BuligDiretso

// SmsService is already loaded by config.php, but require_once is safe to repeat
require_once ROOT_PATH . 'services/SmsService.php';

class SmsController
{
    // NOTE: No session_start() needed here.
    // config/config.php (loaded first by index.php) already calls session_start().
    // Adding a second session_start() would cause a headers-already-sent warning.

    /**
     * POST /index.php?action=sms-assign-responder
     * Called from the Assign Responder modal.
     * Sends user assignment update, responder alert, and optional follow-up SMS.
     */
    public function assignResponder(): void
    {
        $this->requireAdmin();

        $emergencyId    = trim($_POST['emergency_id']     ?? '');
        $userPhone      = trim($_POST['user_phone']       ?? '');
        $userName       = trim($_POST['user_name']        ?? '');
        $location       = trim($_POST['location']         ?? '');
        $emergencyType  = trim($_POST['emergency_type']   ?? 'Emergency');
        $responderName  = trim($_POST['responder_name']   ?? '');
        $responderPhone = trim($_POST['responder_phone']  ?? '');
        $etaRaw         = trim($_POST['eta_minutes']      ?? '10');
        $etaCustom      = trim($_POST['eta_custom']       ?? '');
        $sendFollowup   = !empty($_POST['send_followup']);
        $followupMsg    = trim($_POST['followup_message'] ?? '');

        if ($emergencyId === '' || $userPhone === '' || $userName === '' || $location === '' || $responderName === '') {
            $_SESSION['sms_error'] = 'Missing required assignment details.';
            $this->back();
            return;
        }

        // Build ETA string
        if ($etaRaw === 'custom' && $etaCustom !== '') {
            $eta = $etaCustom . ' minutes';
        } elseif (is_numeric($etaRaw) && (int)$etaRaw >= 60) {
            $eta = '1 hour';
        } else {
            $eta = $etaRaw . ' minutes';
        }

        // SMS 1: Notify the user their responder is on the way
        $userMsg = "BuligDiretso: Hi {$userName}, your {$emergencyType} report (#{$emergencyId}) has been received. "
                 . "Responder {$responderName} is now ON THE WAY to your location at {$location}. "
                 . "Estimated arrival: {$eta}. Please stay calm and keep your phone on. Stay safe!";

        $userResult = SmsService::send(SmsService::formatPhone($userPhone), $userMsg);

        // SMS 2: Alert the responder with assignment details
        if ($responderPhone !== '') {
            $respMsg = "BuligDiretso ALERT: You have been assigned to {$emergencyType} #{$emergencyId} at {$location}. "
                     . "User: {$userName} ({$userPhone}). Please respond immediately.";
            $responderResult = SmsService::send(SmsService::formatPhone($responderPhone), $respMsg);
        } else {
            $responderResult = ['success' => false, 'message' => 'Responder phone is blank.'];
        }

        // SMS 3: Optional follow-up (clinic reminder for pregnancy/medical cases)
        if ($sendFollowup && $followupMsg !== '') {
            SmsService::send(SmsService::formatPhone($userPhone), $followupMsg);
        }

        // Build feedback message — assignment succeeds regardless of SMS outcome
        $msg = "Responder {$responderName} assigned successfully for case #{$emergencyId}.";
        $msg .= $userResult['success']
            ? ' User SMS sent.'
            : " User SMS failed: {$userResult['message']}.";
        $msg .= $responderResult['success']
            ? ' Responder SMS sent.'
            : " Responder SMS failed: {$responderResult['message']}.";

        $_SESSION['sms_success'] = $msg;
        $this->back();
    }

    /**
     * Verify the logged-in user is an admin.
     * Redirects to login page instead of showing a raw 403 — keeps UX clean
     * and prevents the "Forbidden" dead-end the old version showed.
     *
     * Session key is 'user_role' — set by AuthController::setUserSession().
     */
    private function requireAdmin(): void
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit();
        }
    }

    /**
     * Redirect back to the referring page, or fall back to the users list.
     */
    private function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'index.php?action=users');
        header('Location: ' . $ref);
        exit();
    }
}