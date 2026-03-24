<?php
// controllers/SmsController.php — Admin SMS actions for BuligDiretso

class SmsController
{
    /**
     * POST /index.php?action=sms-emergency-received
     * Called when an emergency report is submitted.
     * Expected POST: user_phone, emergency_id, emergency_type
     */
    public function emergencyReceived(): void
    {
        $this->requireAdmin();

        $phone = trim($_POST['user_phone']     ?? '');
        $id    = trim($_POST['emergency_id']   ?? '');
        $type  = trim($_POST['emergency_type'] ?? 'Emergency');

        if (empty($phone) || empty($id)) {
            $_SESSION['sms_error'] = 'Missing phone or emergency ID.';
            $this->back();
        }

        $result = SmsService::notifyEmergencyReceived($phone, $id, $type);
        $this->setFlash($result);
        $this->back();
    }

    /**
     * POST /index.php?action=sms-responder-assigned
     * Called when a responder is assigned to an emergency.
     * Expected POST: responder_phone, emergency_id, location
     */
    public function responderAssigned(): void
    {
        $this->requireAdmin();

        $phone    = trim($_POST['responder_phone'] ?? '');
        $id       = trim($_POST['emergency_id']    ?? '');
        $location = trim($_POST['location']        ?? 'Unknown location');

        if (empty($phone) || empty($id)) {
            $_SESSION['sms_error'] = 'Missing phone or emergency ID.';
            $this->back();
        }

        $result = SmsService::notifyResponderAssigned($phone, $id, $location);
        $this->setFlash($result);
        $this->back();
    }

    /**
     * POST /index.php?action=sms-resolved
     * Called when an emergency is resolved.
     * Expected POST: user_phone, emergency_id
     */
    public function emergencyResolved(): void
    {
        $this->requireAdmin();

        $phone = trim($_POST['user_phone']   ?? '');
        $id    = trim($_POST['emergency_id'] ?? '');

        if (empty($phone) || empty($id)) {
            $_SESSION['sms_error'] = 'Missing phone or emergency ID.';
            $this->back();
        }

        $result = SmsService::notifyEmergencyResolved($phone, $id);
        $this->setFlash($result);
        $this->back();
    }

    /**
     * POST /index.php?action=sms-broadcast
     * Admin broadcast to multiple numbers.
     * Expected POST: phones (comma-separated or array), message
     */
    public function broadcast(): void
    {
        $this->requireAdmin();

        $rawPhones = $_POST['phones']  ?? '';
        $message   = trim($_POST['message'] ?? '');

        if (empty($rawPhones) || empty($message)) {
            $_SESSION['sms_error'] = 'Recipients and message are required.';
            $this->back();
        }

        // Accept comma-separated string or array
        $phones = is_array($rawPhones)
            ? $rawPhones
            : array_filter(array_map('trim', explode(',', $rawPhones)));

        $formatted = array_map([SmsService::class, 'formatPhone'], $phones);

        $result = SmsService::sendCustom($formatted, $message);
        $this->setFlash($result);
        $this->back();
    }

    // ── Private helpers ───────────────────────────────────────────────

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            exit('Forbidden');
        }
    }

    private function setFlash(array $result): void
    {
        if ($result['success']) {
            $_SESSION['sms_success'] = $result['message'];
        } else {
            $_SESSION['sms_error'] = $result['message'];
        }
    }

    private function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'index.php?action=admin-dashboard');
        header('Location: ' . $ref);
        exit();
    }

    /**
     * POST /index.php?action=sms-assign-responder
     * Called from the Assign Responder modal.
     * Sends: (1) on-the-way notification to user, (2) optional follow-up SMS.
     */
    public function assignResponder(): void
    {
        $this->requireAdmin();

        $emergencyId   = trim($_POST['emergency_id']    ?? '');
        $userPhone     = trim($_POST['user_phone']       ?? '');
        $userName      = trim($_POST['user_name']        ?? '');
        $location      = trim($_POST['location']         ?? '');
        $emergencyType = trim($_POST['emergency_type']   ?? 'Emergency');
        $responderName = trim($_POST['responder_name']   ?? '');
        $responderPhone= trim($_POST['responder_phone']  ?? '');
        $etaRaw        = trim($_POST['eta_minutes']      ?? '10');
        $etaCustom     = trim($_POST['eta_custom']       ?? '');
        $sendFollowup  = !empty($_POST['send_followup']);
        $followupMsg   = trim($_POST['followup_message'] ?? '');

        // Resolve ETA
        $eta = ($etaRaw === 'custom' && $etaCustom)
            ? $etaCustom . ' minutes'
            : ($etaRaw >= 60 ? '1 hour' : $etaRaw . ' minutes');

        // --- SMS 1: Notify user that responder is on the way ---
        $userMsg = "BuligDiretso: Hi {$userName}, your {$emergencyType} report (#{$emergencyId}) has been received. "
                 . "Responder {$responderName} is now ON THE WAY to your location at {$location}. "
                 . "Estimated arrival: {$eta}. Please stay calm and keep your phone on. Stay safe!";

        $result1 = SmsService::send(SmsService::formatPhone($userPhone), $userMsg);

        // --- SMS 2: Notify responder of assignment ---
        if (!empty($responderPhone)) {
            $respMsg = "BuligDiretso ALERT: You have been assigned to {$emergencyType} #{$emergencyId} "
                     . "at {$location}. User: {$userName} ({$userPhone}). Please respond immediately.";
            SmsService::send(SmsService::formatPhone($responderPhone), $respMsg);
        }

        // --- SMS 3: Optional follow-up clinic reminder ---
        if ($sendFollowup && !empty($followupMsg) && !empty($userPhone)) {
            SmsService::send(SmsService::formatPhone($userPhone), $followupMsg);
        }

        if ($result1['success']) {
            $_SESSION['sms_success'] = "✅ Responder assigned! SMS sent to {$userName} — {$responderName} is on the way (ETA: {$eta}).";
        } else {
            $_SESSION['sms_error'] = "Responder assigned but SMS failed: " . $result1['message'];
        }

        $this->back();
    }
}
