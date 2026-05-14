<?php

namespace App\Controllers;

use App\Models\EnigmaModel;

class EnigmaController extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $enigmaModel = new EnigmaModel();

        // Cari atau buat progres enigma untuk agen ini
        $progress = $enigmaModel->where('user_id', $userId)->first();

        if (!$progress) {
            // Generate seed acak khusus untuk agen ini
            $seed = bin2hex(random_bytes(8));
            $enigmaModel->insert([
                'user_id' => $userId,
                'puzzle_seed' => $seed,
                'current_level' => 1,
                'is_completed' => false
            ]);
            $progress = $enigmaModel->where('user_id', $userId)->first();
        }

        $data['progress'] = $progress;
        
        // Buat soal berdasarkan seed dan level saat ini
        $data['puzzle'] = $this->generatePuzzle($progress['current_level'], $progress['puzzle_seed']);

        return view('enigma_vault', $data);
    }

    public function verify()
    {
        $userId = session()->get('user_id');
        $enigmaModel = new EnigmaModel();
        $progress = $enigmaModel->where('user_id', $userId)->first();

        if (!$progress || $progress['is_completed']) {
            return redirect()->to('/enigma')->with('error', 'Simpul kebijaksanaan ini telah Anda pecahkan.');
        }

        $answer = $this->request->getPost('answer');
        $puzzle = $this->generatePuzzle($progress['current_level'], $progress['puzzle_seed']);

        if (strtolower(trim($answer)) === strtolower(trim($puzzle['solution']))) {
            // Jawaban Benar
            if ($progress['current_level'] < 3) {
                // Naik level (Asumsikan ada 3 level)
                $enigmaModel->update($progress['id'], [
                    'current_level' => $progress['current_level'] + 1
                ]);
                return redirect()->to('/enigma')->with('success', 'Simpul terbuka. Melangkah ke lapisan makna berikutnya...');
            } else {
                // Selesai semua
                $enigmaModel->update($progress['id'], [
                    'is_completed' => true,
                    'completed_at' => date('Y-m-d H:i:s')
                ]);
                return redirect()->to('/enigma')->with('success', 'RUANG KONTEMPLASI TERBUKA. Anda telah memecahkan teka-teki Panca Jiwa.');
            }
        }

        // Jawaban Salah
        return redirect()->to('/enigma')->with('error', 'Pemahaman Anda belum tepat. Silakan renungkan kembali.');
    }

    private function generatePuzzle($level, $seed)
    {
        // Logika sederhana untuk membuat puzzle berbeda tiap orang menggunakan seed
        $suffix = substr($seed, 0, 4);

        if ($level == 1) {
            return [
                'question' => "Langkah Pertama: Balikkan makna dari kesederhanaan. (Ketik mundur kata SEDERHANA $suffix)",
                'solution' => "ANAHDERES $suffix" 
            ];
        } elseif ($level == 2) {
            return [
                'question' => "Langkah Kedua: Temukan nilai dari keikhlasan. Jika A=1, B=2, berapakah jumlah huruf dari simbol '$suffix'?",
                'solution' => array_sum(array_map('ord', str_split(strtoupper($suffix)))) - (64 * strlen($suffix))
            ];
        } else {
            return [
                'question' => "Langkah Terakhir: Tuliskan kunci takdir Anda untuk membuka ruang ini.",
                'solution' => $seed
            ];
        }
    }
}
