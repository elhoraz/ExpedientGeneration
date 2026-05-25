<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class WrappedController extends BaseController
{
    protected $helpers = ['cms'];

    public function index()
    {
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();
        
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Entitas tidak ditemukan.');
        }

        // 1. Prestise (Total poin dari activity logs/prestise logs)
        $totalPrestise = $user['prestise_points'] ?? 0;
        
        // Cek login harian / kunjungan
        $loginCount = 0;
        if ($db->tableExists('activity_logs')) {
            $loginCount = $db->table('activity_logs')
                ->where('user_id', $userId)
                ->where('type', 'LOGIN')
                ->countAllResults();
        }
            
        if ($loginCount == 0 && $db->tableExists('prestise_logs')) {
             $loginCount = $db->table('prestise_logs')
                 ->where('user_id', $userId)
                 ->where('activity_name', 'LOGIN_DAILY')
                 ->countAllResults();
        }

        // 2. Baitul Maal (Total Sedekah)
        $totalSedekah = 0;
        if ($db->tableExists('baitul_maal')) {
            $bmResult = $db->table('baitul_maal')
                ->selectSum('amount')
                ->where('user_id', $userId)
                ->where('transaction_type', 'Pemasukan')
                ->get()->getRow();
            $totalSedekah = $bmResult->amount ?? 0;
        }

        // 3. Majlis (Total partisipasi vote/diskusi)
        $majlisVotes = 0;
        if ($db->tableExists('majlis_votes')) {
            $majlisVotes = $db->table('majlis_votes')
                ->where('user_id', $userId)
                ->countAllResults();
        }

        // 4. Oracle (Pesan masa depan)
        $oracleCount = 0;
        if ($db->tableExists('oracle_visions')) {
            $oracleCount = $db->table('oracle_visions')
                ->where('user_id', $userId)
                ->countAllResults();
        }

        $data = [
            'user'          => $user,
            'totalPrestise' => $totalPrestise,
            'loginCount'    => $loginCount,
            'totalSedekah'  => $totalSedekah,
            'majlisVotes'   => $majlisVotes,
            'oracleCount'   => $oracleCount,
            'year'          => date('Y')
        ];

        return view('wrapped', $data);
    }
}
