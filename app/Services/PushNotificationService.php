<?php

namespace App\Services;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationService
{
    protected $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject'    => getenv('VAPID_SUBJECT') ?: 'mailto:admin@expedient.com',
                'publicKey'  => getenv('VAPID_PUBLIC_KEY'),
                'privateKey' => getenv('VAPID_PRIVATE_KEY'),
            ],
        ];

        // Guzzle HTTP Client options (Disable SSL Verification for localhost only!)
        $options = [];
        if (ENVIRONMENT === 'development') {
            $options = ['client_options' => ['verify' => false]];
        }

        $this->webPush = new WebPush($auth, $options);
    }

    public function sendToUser($userId, $title, $body, $url = '/')
    {
        $db = \Config\Database::connect();
        
        if (!$db->tableExists('push_subscriptions')) return false;

        $subscriptions = $db->table('push_subscriptions')
            ->where('user_id', $userId)
            ->get()->getResult();

        if (empty($subscriptions)) {
            return false;
        }

        $payload = json_encode([
            'title' => $title,
            'body'  => $body,
            'url'   => $url
        ]);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'keys' => [
                    'p256dh' => $sub->p256dh_key,
                    'auth'   => $sub->auth_key,
                ],
            ]);

            $this->webPush->queueNotification($subscription, $payload);
        }

        foreach ($this->webPush->flush() as $report) {
            if (!$report->isSuccess() && $report->isSubscriptionExpired()) {
                // Hapus langganan yang sudah usang/dicabut oleh pengguna
                $db->table('push_subscriptions')
                   ->where('endpoint', $report->getRequest()->getUri()->__toString())
                   ->delete();
            }
        }

        return true;
    }
}
