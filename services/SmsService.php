<?php
// services/SmsService.php — PhilSMS Integration for BuligDiretso

class SmsService
{
    private static string $apiUrl   = 'https://dashboard.philsms.com/api/v3/sms/send';
    private static string $token    = PHILSMS_TOKEN;
    private static string $senderId = PHILSMS_SENDER;

    public static function send($recipient, string $message, ?string $scheduleAt = null): array
    {
        $recipients = is_array($recipient) ? $recipient : explode(',', (string)$recipient);
        $formattedRecipients = [];
        foreach ($recipients as $phone) {
            $f = self::formatPhone((string)$phone);
            if (preg_match('/^63\d{10}$/', $f)) {
                $formattedRecipients[] = $f;
            }
        }
        $formattedRecipients = array_values(array_unique($formattedRecipients));
        if (empty($formattedRecipients)) {
            return ['success' => false, 'message' => 'No valid recipient phone number.', 'data' => null];
        }

        $payload = [
            'recipient' => implode(',', $formattedRecipients),
            'type'      => 'plain',
            'message'   => trim($message),
        ];
        if (self::$senderId !== '') {
            $payload['sender_id'] = self::$senderId;
        }

        if ($scheduleAt) {
            $payload['schedule_time'] = $scheduleAt;
        }

        $result = self::sendRequest($payload);

        // If sender_id is not authorized, retry once without sender_id.
        if (
            !$result['success'] &&
            isset($payload['sender_id']) &&
            stripos($result['message'], 'sender id') !== false &&
            stripos($result['message'], 'not authorized') !== false
        ) {
            $primaryMessage = $result['message'];
            unset($payload['sender_id']);
            $retryResult = self::sendRequest($payload);
            if ($retryResult['success']) {
                return $retryResult;
            }
            return [
                'success' => false,
                'message' => $primaryMessage . ' | Fallback without sender_id also failed: ' . ($retryResult['message'] ?? 'unknown'),
                'data' => $retryResult['data'] ?? null
            ];
        }

        return $result;
    }

    private static function sendRequest(array $payload): array
    {
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

        $raw = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            error_log("[SmsService] cURL error: {$curlErr}");
            return ['success' => false, 'message' => 'Network error: ' . $curlErr, 'data' => null];
        }

        $response = json_decode((string)$raw, true);
        if ($httpCode === 200 && isset($response['status']) && $response['status'] === 'success') {
            return ['success' => true, 'message' => 'SMS sent successfully.', 'data' => $response['data'] ?? null];
        }

        $errMsg = $response['message'] ?? ('Unknown error (HTTP ' . $httpCode . ')');
        error_log('[SmsService] API error: ' . $errMsg . ' | Payload: ' . json_encode($payload));
        return ['success' => false, 'message' => $errMsg, 'data' => $response ?? null];
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
