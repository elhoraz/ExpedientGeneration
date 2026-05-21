<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BaitulMaalModel;
use App\Models\UserModel;

class BaitulMaalController extends BaseController
{
    /**
     * Cek apakah user saat ini memiliki hak akses bendahara/admin.
     */
    private function canManageFinance(): bool
    {
        $userId = session()->get('user_id');
        if (!$userId) return false;
        $user = (new UserModel())->find($userId);
        return $user && in_array($user['role'] ?? '', ['admin', 'bendahara']);
    }

    public function index()
    {
        $baitulMaalModel = new BaitulMaalModel();
        
        $db = \Config\Database::connect();
        $builder = $db->table('baitul_maal');
        $builder->select('baitul_maal.*, users.nama_panggilan, users.foto_profil');
        $builder->join('users', 'users.id = baitul_maal.user_id', 'left');
        $builder->orderBy('baitul_maal.created_at', 'DESC');
        
        $transactions = $builder->get()->getResultArray();

        $pemasukan = $db->table('baitul_maal')->selectSum('amount')->where('transaction_type', 'Pemasukan')->get()->getRow()->amount ?? 0;
        $pengeluaran = $db->table('baitul_maal')->selectSum('amount')->where('transaction_type', 'Pengeluaran')->get()->getRow()->amount ?? 0;

        $data['transactions'] = $transactions;
        $data['saldo_akhir'] = $pemasukan - $pengeluaran;
        $data['total_pemasukan'] = $pemasukan;
        $data['total_pengeluaran'] = $pengeluaran;
        $data['can_manage'] = $this->canManageFinance();

        return view('baitul_maal', $data);
    }

    public function store()
    {
        // OTORISASI: Hanya admin/bendahara yang boleh mencatat transaksi
        if (!$this->canManageFinance()) {
            return redirect()->to('/baitul-maal')->with('error', 'Akses ditolak. Hanya Bendahara atau Admin yang dapat mencatat transaksi.');
        }

        $userId = session()->get('user_id');
        
        // ================= VALIDASI INPUT =================
        $rules = [
            'amount'      => 'required|numeric',
            'type'        => 'required|in_list[Pemasukan,Pengeluaran]',
            'description' => 'required|min_length[3]|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/baitul-maal')->withInput()->with('error', 'Data transaksi tidak valid. Pastikan nominal dan jenis transaksi terisi dengan benar.');
        }

        $amount = str_replace(['.', ','], '', $this->request->getPost('amount'));
        
        // Pastikan amount positif
        if ((int)$amount <= 0) {
            return redirect()->to('/baitul-maal')->withInput()->with('error', 'Nominal transaksi harus lebih dari 0.');
        }

        $baitulMaalModel = new BaitulMaalModel();
        $baitulMaalModel->insert([
            'user_id'          => $this->request->getPost('anonim') ? null : $userId,
            'amount'           => $amount,
            'transaction_type' => $this->request->getPost('type'),
            'description'      => $this->request->getPost('description'),
            'created_at'       => date('Y-m-d H:i:s')
        ]);

        $pusher = new \App\Services\PusherService();
        $pusher->broadcastNotification(
            'Transaksi Baitul Maal',
            'Satu transaksi ' . strtolower($this->request->getPost('type')) . ' baru telah dicatat.',
            '/baitul-maal'
        );

        return redirect()->to('/baitul-maal')->with('success', 'Transaksi tercatat di Ledger Baitul Maal.');
    }
}
