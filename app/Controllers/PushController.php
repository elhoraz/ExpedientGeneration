<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PushController extends ResourceController
{
    public function subscribe()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->failUnauthorized('Silakan login.');
        }

        $json = $this->request->getJSON();

        if (empty($json->endpoint) || empty($json->keys->auth) || empty($json->keys->p256dh)) {
            return $this->failValidationErrors('Data subscription tidak lengkap.');
        }

        $db = \Config\Database::connect();
        
        // Cek apakah endpoint sudah ada
        $existing = $db->table('push_subscriptions')
            ->where('endpoint', $json->endpoint)
            ->get()->getRow();

        if ($existing) {
            // Update user_id jika ternyata endpoint ini dipakai user lain
            $db->table('push_subscriptions')
                ->where('id', $existing->id)
                ->update(['user_id' => $userId]);
        } else {
            $db->table('push_subscriptions')->insert([
                'user_id'    => $userId,
                'endpoint'   => $json->endpoint,
                'auth_key'   => $json->keys->auth,
                'p256dh_key' => $json->keys->p256dh,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->respondCreated(['message' => 'Berhasil mendaftarkan Push Subscription.']);
    }

    public function publicKey()
    {
        return $this->respond([
            'publicKey' => getenv('VAPID_PUBLIC_KEY')
        ]);
    }
}
