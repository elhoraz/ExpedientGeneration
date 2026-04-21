<?php

namespace App\Controllers;

use App\Models\UserModel; // Gunakan UserModel, bukan AlumniModel

class RadarController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        // Inisialisasi model yang sama dengan yang dipakai di AuthController
        $this->userModel = new UserModel(); 
    }

    public function index()
    {
        // 1. Titik Pusat Statis
        $pusat = [
            'id'   => 'pusat', 
            'name' => 'Pusat Komando Arrisalah', 
            'city' => 'Ponorogo, ID', 
            'lat'  => -7.8667, 
            'lng'  => 111.4667, 
            'type' => 'center'
        ];


        // 2. Tarik Data ASLI dari tabel users (Hanya yang koordinatnya sudah ada)
        $dbUsers = $this->userModel
                        ->where('lat !=', null)
                        ->where('lng !=', null)
                        ->findAll();
        
        $alumniNodes = [$pusat];

        foreach ($dbUsers as $row) {
            $alumniNodes[] = [
                'id'   => $row['id'],
                'name' => $row['nama_panggilan'] ?? $row['nama_lengkap'] ?? 'Agen Rahasia',
                'city' => $row['alamat_lengkap'] ?? 'Terdeteksi Radar',
                'lat'  => (float)$row['lat'],
                'lng'  => (float)$row['lng'],
                'type' => 'agent'
            ];
        }

        $data['alumni_nodes'] = $alumniNodes;

        return view('radar', $data);
    }
public function flatMap()
    {
        // Tarik data yang sama persis seperti Globe 3D
        $pusat = [
            'id' => 'pusat', 'name' => 'Pusat Komando Arrisalah', 
            'city' => 'Ponorogo, ID', 'lat' => -7.8667, 'lng' => 111.4667, 'type' => 'center'
        ];

        // Ganti $this->userModel dengan nama model Anda yang benar jika berbeda
        $dbUsers = $this->userModel->where('lat !=', null)->where('lng !=', null)->findAll();
        $alumniNodes = [$pusat];

        foreach ($dbUsers as $row) {
            $alumniNodes[] = [
                'id'   => $row['id'],
                'name' => $row['nama_panggilan'] ?? 'Agen Rahasia',
                'city' => $row['alamat_lengkap'] ?? 'Terdeteksi Radar',
                'lat'  => (float)$row['lat'],
                'lng'  => (float)$row['lng'],
                'type' => 'agent'
            ];
        }

        $data['alumni_nodes'] = $alumniNodes;

        // Tampilkan view peta datar tanpa layout
        return view('radar_flat', $data);
    }
    public function updateLocation()
    {
        $json = $this->request->getJSON();
        $lat = $json->lat ?? null;
        $lng = $json->lng ?? null;
        
        // =======================================================
        // KUNCI PERBAIKAN: Menggunakan 'user_id' dari AuthController
        // =======================================================
        $userId = session()->get('user_id'); 

        if ($userId && $lat && $lng) {
            
            // Langsung UPDATE koordinat ke tabel users berdasarkan ID mereka
            $this->userModel->update($userId, [
                'lat' => $lat,
                'lng' => $lng
            ]);
            
            return $this->response->setJSON([
                'status'  => 'success', 
                'message' => 'Satelit terkunci. Koordinat Anda telah masuk ke arsip pusat.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error', 
            'message' => 'Otorisasi gagal. Sesi login Anda (' . ($userId ? 'Ditemukan' : 'Kosong') . ').'
        ]);
    }
}