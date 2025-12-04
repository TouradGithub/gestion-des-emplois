<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send push notification via Expo
     */
    public static function sendPushNotification($token, $title, $body, $data = [])
    {
        if (empty($token)) {
            return ['success' => false, 'error' => 'Token is empty'];
        }

        $payload = [
            "to" => $token,
            "sound" => "default",
            "title" => $title,
            "body" => $body,
            "data" => array_merge(["type" => "schedule_update"], $data),
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://exp.host/--/api/v2/push/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Accept-Encoding: gzip, deflate',
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error('Expo Push Notification Error', ['error' => $error]);
            return ['success' => false, 'error' => $error];
        }

        return ['success' => true, 'response' => json_decode($response, true)];
    }

    /**
     * Send bulk notifications to multiple tokens
     */
    public static function sendBulkNotifications($tokens, $title, $body, $data = [])
    {
        $tokens = array_filter($tokens); // Remove empty tokens

        if (empty($tokens)) {
            return ['sent' => 0, 'failed' => 0];
        }

        $messages = [];
        foreach ($tokens as $token) {
            $messages[] = [
                "to" => $token,
                "sound" => "default",
                "title" => $title,
                "body" => $body,
                "data" => array_merge(["type" => "schedule_update"], $data),
            ];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://exp.host/--/api/v2/push/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Accept-Encoding: gzip, deflate',
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messages));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error('Expo Bulk Push Notification Error', ['error' => $error]);
            return ['sent' => 0, 'failed' => count($messages)];
        }

        $result = json_decode($response, true);
        $sent = 0;
        $failed = 0;

        if (isset($result['data']) && is_array($result['data'])) {
            foreach ($result['data'] as $item) {
                if (isset($item['status']) && $item['status'] === 'ok') {
                    $sent++;
                } else {
                    $failed++;
                }
            }
        }

        return ['sent' => $sent, 'failed' => $failed];
    }

    /**
     * Notify all students in a class
     */
    public static function notifyClassStudents($classId, $title, $body, $data = [])
    {
        $tokens = Student::where('class_id', $classId)
            ->where('notifications_enabled', true)
            ->whereNotNull('expo_push_token')
            ->where('expo_push_token', '!=', '')
            ->pluck('expo_push_token')
            ->toArray();

        return self::sendBulkNotifications($tokens, $title, $body, $data);
    }

    /**
     * Notify schedule created
     */
    public static function notifyScheduleCreated($emploi)
    {
        $emploi->load(['subject', 'teacher', 'jour', 'ref_horaires']);

        $horaires = $emploi->ref_horaires->pluck('libelle_fr')->implode(', ');
        $title = "Nouvelle séance";
        $body = "{$emploi->subject->name} - {$emploi->jour->libelle_fr} ({$horaires})";

        return self::notifyClassStudents($emploi->class_id, $title, $body);
    }

    /**
     * Notify schedule updated
     */
    public static function notifyScheduleUpdated($emploi)
    {
        $emploi->load(['subject', 'teacher', 'jour', 'ref_horaires']);

        $horaires = $emploi->ref_horaires->pluck('libelle_fr')->implode(', ');
        $title = "Emploi du temps modifié";
        $body = "{$emploi->subject->name} - {$emploi->jour->libelle_fr} ({$horaires})";

        return self::notifyClassStudents($emploi->class_id, $title, $body);
    }

    /**
     * Notify schedule deleted
     */
    public static function notifyScheduleDeleted($classId, $subjectName, $jourName, $horaires)
    {
        $title = "Séance annulée";
        $body = "{$subjectName} - {$jourName} ({$horaires})";

        return self::notifyClassStudents($classId, $title, $body);
    }
}
