<?php

namespace App\Controllers;

use App\Models\OracleModel;

class OracleController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $oracleModel = new OracleModel();

        // Ambil semua visi masa depan milik user ini
        $data['visions'] = $oracleModel->where('user_id', $userId)->orderBy('unlock_date', 'ASC')->findAll();

        return view('oracle_vision', $data);
    }

    public function store()
    {
        $userId = session()->get('user_id');
        $rules = [
            'vision_text' => 'required|min_length[10]',
            'unlock_date' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/oracle')->withInput()->with('error', 'Gagal menyimpan pesan. Cek kembali input Anda.');
        }

        $unlockDate = $this->request->getPost('unlock_date');
        
        // Pastikan tanggal unlock di masa depan
        if (strtotime($unlockDate) <= strtotime(date('Y-m-d'))) {
            return redirect()->to('/oracle')->withInput()->with('error', 'Tanggal pembukaan harus di masa depan.');
        }

        $oracleModel = new OracleModel();
        $oracleModel->insert([
            'user_id'     => $userId,
            'vision_text' => $this->request->getPost('vision_text'),
            'unlock_date' => $unlockDate,
            'is_unlocked' => false,
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/oracle')->with('success', 'Pesan Masa Depan berhasil dikunci dan diamankan.');
    }

    public function unlock($id)
    {
        $userId = session()->get('user_id');
        $oracleModel = new OracleModel();
        
        $vision = $oracleModel->find($id);

        if (!$vision || $vision['user_id'] != $userId) {
            return redirect()->to('/oracle')->with('error', 'Otorisasi gagal.');
        }

        if (strtotime($vision['unlock_date']) > strtotime(date('Y-m-d'))) {
            return redirect()->to('/oracle')->with('error', 'Pesan ini masih dikunci. Waktu untuk membukanya belum tiba.');
        }

        $oracleModel->update($id, ['is_unlocked' => true]);

        return redirect()->to('/oracle')->with('success', 'Pesan berhasil dibuka.')->with('unlocked_vision', $vision['vision_text']);
    }
}
