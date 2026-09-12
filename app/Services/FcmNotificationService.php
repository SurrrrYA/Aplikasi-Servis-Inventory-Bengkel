<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmNotificationService
{
    public function __construct(
        private Messaging $messaging
    ) {
    }

    public function sendToToken(
        string $token,
        string $title,
        string $body,
        array $data = []
    ): void {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(
                Notification::create($title, $body)
            )
            ->withData($data);

        $this->messaging->send($message);
    }

    public function notifyOwners(
        string $title,
        string $body,
        array $data = []
    ): void {
        $tokens = DeviceToken::whereHas('user', function ($query) {
            $query->where('role', 'owner');
        })->get();

        foreach ($tokens as $deviceToken) {
            try {
                $this->sendToToken(
                    $deviceToken->token,
                    $title,
                    $body,
                    $data
                );
            } catch (\Throwable $e) {
                Log::error(
                    'Gagal mengirim FCM ke Owner',
                    [
                        'device_token_id' => $deviceToken->id,
                        'error' => $e->getMessage(),
                    ]
                );
            }
        }
    }
}