<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use Pusher\Pusher;

class AuthController extends BaseController
{
    public function index()
    {
        // Tampilkan halaman Login/Register yang berdesain Glassmorphism
        return view('auth/login'); 
    }

    public function registerView()
    {
        // Menampilkan halaman Register Superior yang baru saja kita buat
        return view('auth/register');
    }

    public function processLogin()
    {
        $session = session();
        $userModel = new UserModel();
        
        // Ambil inputan dari form login
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email di database
        $dataUser = $userModel->where('email', $email)->first();

        if ($dataUser) {
            // Cek apakah password cocok dengan hash di database
            $cekPassword = password_verify($password, $dataUser['password_hash']);
            
            if ($cekPassword) {
                
                // Cek apakah user sudah klik link verifikasi di email
                if (is_null($dataUser['email_verified_at'])) {
                    return redirect()->to('/login')->with('error', 'Silakan cek email Anda dan lakukan verifikasi terlebih dahulu sebelum login.');
                }

                // Buat Session jika berhasil login
                $sesData = [
                    'user_id'         => $dataUser['id'],
                    'nama_panggilan' => $dataUser['nama_panggilan'],
                    'email'          => $dataUser['email'],
                    'logged_in'      => TRUE
                ];
                $session->set($sesData);
                
                // Lempar ke halaman Beranda!
                return redirect()->to('/beranda');
                
            } else {
                return redirect()->to('/login')->with('error', 'Kata sandi salah.');
            }
        } else {
            return redirect()->to('/login')->with('error', 'Email tidak ditemukan.');
        }
    }

    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function register()
    {
        // Hubungkan ke koneksi database untuk kontrol transaksi
        $db = \Config\Database::connect();

        try {
            if (!$this->request->is('post')) {
                return view('auth/register'); 
            }

            // ================= VALIDASI INPUT =================
            $rules = [
                'nama_lengkap'         => 'required|min_length[3]',
                'nama_panggilan'       => 'required|min_length[2]',
                'jenis_kelamin'        => 'required|in_list[Laki-laki,Perempuan]',
                'tempat_tanggal_lahir' => 'required',
                'alamat_lengkap'       => 'required|min_length[10]',
                'email'                => 'required|valid_email|is_unique[users.email]',
                'password'             => 'required|min_length[8]',
                'no_whatsapp'          => 'required|numeric|min_length[10]',
                'snk'                  => 'required' // Wajib centang S&K
            ];

            $messages = [
                'email' => [
                    'is_unique' => 'Email ini sudah terdaftar di database alumni.'
                ],
                'snk' => [
                    'required' => 'Anda harus menyetujui Syarat & Ketentuan.'
                ]
            ];

            if (!$this->validate($rules, $messages)) {
                return redirect()->to('/auth/register')->withInput()->with('validation_errors', $this->validator->getErrors());
            }

            // ================= PROSES FOTO PROFIL (BASE64) =================
            $fotoBase64 = $this->request->getPost('foto_profil_base64');
            $namaFileFoto = null;

            if (!empty($fotoBase64)) {
                $imageParts = explode(";base64,", $fotoBase64);
                if (count($imageParts) == 2) {
                    $imageBase64 = base64_decode($imageParts[1]);
                    $namaFileFoto = uniqid('expedient_') . '.jpg';
                    $path = FCPATH . 'uploads/profiles/' . $namaFileFoto;
                    file_put_contents($path, $imageBase64);
                }
            }

            // ---------------------------------------------------------
            // [TRANSAKSI DIMULAI] - Menjaga Database agar tetap bersih
            // ---------------------------------------------------------
            $db->transBegin();

            // ================= SIMPAN KE DATABASE =================
            $userModel = new UserModel();
            $tokenVerifikasi = bin2hex(random_bytes(32)); 

            $dataUser = [
                'nama_lengkap'         => $this->request->getPost('nama_lengkap'),
                'nama_panggilan'       => $this->request->getPost('nama_panggilan'),
                'jenis_kelamin'        => $this->request->getPost('jenis_kelamin'),
                'tempat_tanggal_lahir' => $this->request->getPost('tempat_tanggal_lahir'),
                'alamat_lengkap'       => $this->request->getPost('alamat_lengkap'),
                'email'                => $this->request->getPost('email'),
                'password_hash'        => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'no_whatsapp'          => $this->request->getPost('no_whatsapp'),
                'motivasi_hidup'       => $this->request->getPost('motivasi_hidup'),
                'cita_cita'            => $this->request->getPost('cita_cita'),
                'akun_ig'              => $this->request->getPost('akun_ig'),
                'akun_tiktok'          => $this->request->getPost('akun_tiktok'),
                'foto_profil'          => $namaFileFoto,
                'email_verify_token'   => $tokenVerifikasi,
                'face_data'            => $this->request->getPost('face_data') 
            ];

            $userModel->insert($dataUser);

            // ================= KIRIM EMAIL VERIFIKASI =================
            $emailService = \Config\Services::email();
            
            $config = [
                'protocol'   => 'smtp',
                'SMTPHost'   => 'smtp.gmail.com',
                'SMTPUser'   => 'elhorastudying@gmail.com', 
                'SMTPPass'   => 'nnphysndazvhrypg', 
                'SMTPPort'   => 587,  
                'SMTPCrypto' => 'tls', 
                'mailType'   => 'html',
                'charset'    => 'utf-8',
                'newline'    => "\r\n", 
                'CRLF'       => "\r\n" 
			
            ];
            
            $emailService->initialize($config);

            $emailService->setFrom('elhorastudying@gmail.com', 'Expedient Generation');
            $emailService->setTo($dataUser['email']);
            $emailService->setSubject('Verifikasi Akun - Expedient 42nd Arrisalah');

            $linkVerifikasi = base_url('auth/verify/' . $tokenVerifikasi);
            $pesanEmail = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #d4af37; border-radius: 10px; background-color: #0d0d0d; color: #e0e0e0;'>
                    <h2 style='color: #ffd700; text-align: center;'>Selamat Datang di Keluarga Besar Expedient!</h2>
                    <p>Halo <strong>{$dataUser['nama_panggilan']}</strong>,</p>
                    <p>Pendaftaran Anda telah kami terima. Silakan verifikasi email Anda dengan mengklik tautan di bawah ini:</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='{$linkVerifikasi}' style='background-color: #d4af37; color: #111; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verifikasi Akun Saya</a>
                    </div>
                    <p style='text-align: center; margin-top: 40px; border-top: 1px solid #333; padding-top: 20px;'>42nd Arrisalah Expedient Generation</p>
                </div>
            ";

            $emailService->setMessage($pesanEmail);

            if ($emailService->send()) {
                // JIKA EMAIL TERKIRIM: Simpan permanen ke DB
                $db->transCommit();
                return redirect()->to('/login')->with('success', 'Pendaftaran berhasil! Silakan cek kotak masuk email Anda untuk melakukan verifikasi.');
            } else {
                // JIKA EMAIL GAGAL: Tarik kembali (Hapus) data yang tadi di-insert
                $db->transRollback();
                
                echo "<h1>Sistem Gagal Mengirim Email! (Data Database telah di-Rollback)</h1>";
                echo "<p>Silakan coba daftar ulang, data sebelumnya tidak tersimpan agar tidak duplikat.</p>";
                echo "<pre>";
                print_r($emailService->printDebugger(['headers']));
                echo "</pre>";
                die();
            }

        } catch (\Exception $e) { 
            // JIKA TERJADI ERROR FATAL: Pastikan rollback dilakukan jika transaksi aktif
            if ($db->transStatus() === FALSE) {
                $db->transRollback();
            }
            echo "<h1 style='color:red; background:#fff; padding:20px;'>SISTEM CRASH (FATAL ERROR)</h1>";
            echo "<h3 style='background:#fff; color:#000;'>Pesan Error: " . $e->getMessage() . "</h3>";
            echo "<p style='background:#fff; color:#000;'>Terjadi di File: " . $e->getFile() . " pada baris ke-" . $e->getLine() . "</p>";
            die();
        }
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil keluar.');
    }

    public function verifyEmail($token = null)
    {
        if (empty($token)) {
            return redirect()->to('/login')->with('error', 'Token verifikasi tidak valid.');
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('email_verify_token', $token)->first();

        if ($user) {
            $userModel->update($user['id'], [
                'email_verify_token' => null,
                'email_verified_at'  => date('Y-m-d H:i:s')
            ]);

            // =========================================================
            // TRIGGER SOKETI: Kirim Notifikasi Real-time
            // =========================================================
            $pusher = new Pusher(
                'app-key',      // App Key bawaan Soketi
                'app-secret',   // App Secret
                'app-id',       // App ID
                [
                    'host'   => '127.0.0.1', // Alamat server Soketi lokal
                    'port'   => 6001,
                    'scheme' => 'http',
                    'useTLS' => false,
                ]
            );

            $dataEvent = [
                'nama' => $user['nama_panggilan'],
                'pesan' => 'Baru saja bergabung ke portal angkatan!'
            ];

            // Pancarkan event 'alumni-baru' ke channel 'expedient-channel'
            $pusher->trigger('expedient-channel', 'alumni-baru', $dataEvent);
            // =========================================================

            return redirect()->to('/login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
        } else {
            return redirect()->to('/login')->with('error', 'Tautan tidak valid atau email sudah terverifikasi.');
        }
    }
}