<?php

namespace App\Controllers;

use App\Models\UserModel;

class BirthdayController extends BaseController
{
    /**
     * Halaman daftar alumni yang berulang tahun hari ini.
     */
    public function index()
    {
        $userModel = new UserModel();

        // Cari semua user yang berulang tahun hari ini
        $birthdayUsers = $userModel
            ->where('MONTH(tanggal_lahir)', date('m'))
            ->where('DAY(tanggal_lahir)', date('d'))
            ->findAll();

        $data = [
            'birthday_users' => $birthdayUsers,
        ];

        return view('birthday_list', $data);
    }

    /**
     * Halaman birthday card spesifik untuk 1 user (124 desain unik).
     */
    public function show($userId = null)
    {
        if (!$userId) {
            return redirect()->to('/beranda');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user || empty($user['tanggal_lahir'])) {
            return redirect()->to('/beranda')->with('pesan', 'Data ulang tahun entitas belum tersedia.');
        }

        // Hitung usia
        $birthDate = new \DateTime($user['tanggal_lahir']);
        $today = new \DateTime();
        $usia = $today->diff($birthDate)->y;

        // Tentukan zodiak
        $zodiak = $this->getZodiak($user['tanggal_lahir']);

        // Foto profil
        $fotoUrl = '';
        if (!empty($user['foto_profil'])) {
            $fotoUrl = base_url('uploads/profiles/' . $user['foto_profil']);
        }

        $data = [
            'user'      => $user,
            'usia'      => $usia,
            'zodiak'    => $zodiak,
            'foto_url'  => $fotoUrl,
            'design_id' => (int)$userId, // Digunakan sebagai seed untuk desain unik
        ];

        return view('birthday', $data);
    }

    /**
     * Menentukan zodiak berdasarkan tanggal lahir.
     */
    private function getZodiak(string $tanggal): array
    {
        $d = (int)date('d', strtotime($tanggal));
        $m = (int)date('m', strtotime($tanggal));

        $zodiak = [
            ['nama' => 'Capricorn',    'icon' => '♑', 'range' => [[1,1,1,19],[12,22,12,31]]],
            ['nama' => 'Aquarius',     'icon' => '♒', 'range' => [[1,20,2,18]]],
            ['nama' => 'Pisces',       'icon' => '♓', 'range' => [[2,19,3,20]]],
            ['nama' => 'Aries',        'icon' => '♈', 'range' => [[3,21,4,19]]],
            ['nama' => 'Taurus',       'icon' => '♉', 'range' => [[4,20,5,20]]],
            ['nama' => 'Gemini',       'icon' => '♊', 'range' => [[5,21,6,20]]],
            ['nama' => 'Cancer',       'icon' => '♋', 'range' => [[6,21,7,22]]],
            ['nama' => 'Leo',          'icon' => '♌', 'range' => [[7,23,8,22]]],
            ['nama' => 'Virgo',        'icon' => '♍', 'range' => [[8,23,9,22]]],
            ['nama' => 'Libra',        'icon' => '♎', 'range' => [[9,23,10,22]]],
            ['nama' => 'Scorpio',      'icon' => '♏', 'range' => [[10,23,11,21]]],
            ['nama' => 'Sagittarius',  'icon' => '♐', 'range' => [[11,22,12,21]]],
        ];

        foreach ($zodiak as $z) {
            foreach ($z['range'] as $r) {
                $start = $r[0] * 100 + $r[1];
                $end   = $r[2] * 100 + $r[3];
                $curr  = $m * 100 + $d;
                if ($curr >= $start && $curr <= $end) {
                    return ['nama' => $z['nama'], 'icon' => $z['icon']];
                }
            }
        }

        return ['nama' => 'Capricorn', 'icon' => '♑']; // Default
    }
}
