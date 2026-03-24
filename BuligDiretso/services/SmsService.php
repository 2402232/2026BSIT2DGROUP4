<?php
// services/SmsService.php — PhilSMS Integration for BuligDiretso

class SmsService
{
    private static string $apiUrl    = 'https://dashboard.philsms.com/api/v3/sms/send';
    private static string $token     = PHILSMS_TOKEN;    // defined in config.php
    private static string $senderId  = PHILSMS_SENDER;  // defined in config.php

    public static function send($recipient, string $message, ?string $scheduleAt = null): array
    {
        if (is_array($recipient)) {
            $recipient = implode(',', $recipient);
        }

        $payload = [
            'recipient' => $recipient,
            'sender_id' => self::$senderId,
            'type'      => 'plain',
            'message'   => $message,
        ];

        if ($scheduleAt) {
            $payload['schedule_time'] = $scheduleAt;
        }

        $ch = curl_init(self::$apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . self::$token,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => 15,
        ]);

        $raw      = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            error_log("[SmsService] cURL error: $curlErr");
            return ['success' => false, 'message' => 'Network error: ' . $curlErr, 'data' => null];
        }

        $response = json_decode($raw, true);

        if ($httpCode === 200 && isset($response['status']) && $response['status'] === 'success') {
            return ['success' => true, 'message' => 'SMS sent successfully.', 'data' => $response['data'] ?? null];
        }

        $errMsg = $response['message'] ?? 'Unknown error (HTTP ' . $httpCode . ')';
        error_log("[SmsService] API error: $errMsg | Payload: " . json_encode($payload));
        return ['success' => false, 'message' => $errMsg, 'data' => $response ?? null];
    }

    public static function notifyEmergencyReceived(string $phone, string $emergencyId, string $type): array
    {
        $msg = "BuligDiretso: Your {$type} emergency report (#{$emergencyId}) has been received. "
             . "We are dispatching help. Stay safe!";
        return self::send(self::formatPhone($phone), $msg);
    }

    public static function notifyResponderAssigned(string $phone, string $emergencyId, string $location): array
    {
        $msg = "BuligDiretso ALERT: You have been assigned to emergency #{$emergencyId} "
             . "at {$location}. Please respond immediately.";
        return self::send(self::formatPhone($phone), $msg);
    }

    public static function notifyEmergencyResolved(string $phone, string $emergencyId): array
    {
        $msg = "BuligDiretso: Emergency #{$emergencyId} has been marked as RESOLVED. "
             . "Thank you for using BuligDiretso. Stay safe!";
        return self::send(self::formatPhone($phone), $msg);
    }

    public static function sendOtp(string $phone, string $otp): array
    {
        $msg = "BuligDiretso: Your verification code is {$otp}. "
             . "It expires in 10 minutes. Do not share this code.";
        return self::send(self::formatPhone($phone), $msg);
    }

    public static function sendCustom($recipients, string $message): array
    {
        return self::send($recipients, $message);
    }

    public static function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '63' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '63')) {
            $phone = '63' . $phone;
        }
        return $phone;
    }
}
