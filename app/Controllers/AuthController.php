<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\GamificationService;
use App\Services\PusherService;

class AuthController extends BaseController
{
    protected AuthService $authService;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->authService = new AuthService();
    }

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
        // Rate Limiting: Maksimal 5 percobaan per menit per IP
        $throttler = \Config\Services::throttler();
        if ($throttler->check(md5('login_' . $this->request->getIPAddress()), 5, MINUTE) === false) {
            return redirect()->to('/login')->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam 1 menit.');
        }

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/login')->withInput()->with('error', 'Format email atau kata sandi tidak valid.');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        try {
            $user = $this->authService->attemptLogin($email, $password);
            
            if ($user) {
                // Buat Session jika berhasil login
                $sesData = [
                    'user_id'         => $user['id'],
                    'nama_panggilan' => $user['nama_panggilan'],
                    'email'          => $user['email'],
                    'logged_in'      => TRUE
                ];
                session()->set($sesData);
                
                // Tambahkan poin prestise untuk login harian
                $gamificationService = new GamificationService();
                $gamificationService->addPrestise($user['id'], 'LOGIN_DAILY', 5);
                
                // Lempar ke halaman Beranda!
                return redirect()->to('/beranda');
                
            } else {
                return redirect()->to('/login')->with('error', 'Email atau kata sandi salah.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', $e->getMessage());
        }
    }

    public function register()
    {
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
                'tempat_lahir'         => 'required',
                'tanggal_lahir'        => 'required|valid_date',
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
                $profileService = new \App\Services\ProfileService();
                // Gunakan 0 sebagai userId karena user belum memiliki record/foto lama
                $namaFileFoto = $profileService->processProfilePhoto($fotoBase64, 0);
            }

            // ---------------------------------------------------------
            // [TRANSAKSI DIMULAI] - Menjaga Database agar tetap bersih
            // ---------------------------------------------------------
            $db->transBegin();

            $dataUser = [
                'nama_lengkap'         => $this->request->getPost('nama_lengkap'),
                'nama_panggilan'       => $this->request->getPost('nama_panggilan'),
                'jenis_kelamin'        => $this->request->getPost('jenis_kelamin'),
                'tempat_lahir'         => $this->request->getPost('tempat_lahir'),
                'tanggal_lahir'        => $this->request->getPost('tanggal_lahir'),
                'tempat_tanggal_lahir' => $this->request->getPost('tempat_lahir') . ', ' . date('d F Y', strtotime($this->request->getPost('tanggal_lahir'))),
                'alamat_lengkap'       => $this->request->getPost('alamat_lengkap'),
                'email'                => $this->request->getPost('email'),
                'no_whatsapp'          => $this->request->getPost('no_whatsapp'),
                'motivasi_hidup'       => $this->request->getPost('motivasi_hidup'),
                'cita_cita'            => $this->request->getPost('cita_cita'),
                'akun_ig'              => $this->request->getPost('akun_ig'),
                'akun_tiktok'          => $this->request->getPost('akun_tiktok'),
                'foto_profil'          => $namaFileFoto,
                'face_data'            => $this->request->getPost('face_data') 
            ];

            // Panggil service untuk handle register (sudah termasuk hash password & kirim email)
            $password = $this->request->getPost('password');
            $dataUser['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            $this->authService->registerUser($dataUser);

            $db->transCommit();
                
            return redirect()->to('/login')->with('success', 'Pendaftaran berhasil! Silakan cek kotak masuk email Anda untuk verifikasi.');

        } catch (\Exception $e) { 
            // JIKA TERJADI ERROR FATAL: Pastikan rollback dilakukan jika transaksi aktif
            if ($db->transStatus() === FALSE) {
                $db->transRollback();
            }
            log_message('critical', 'Sistem Crash di Register: ' . $e->getMessage() . ' di ' . $e->getFile() . ':' . $e->getLine());
            return redirect()->to('/auth/register')->with('error', 'Terjadi kesalahan sistem. Mungkin email gagal terkirim.');
        }
    }
    
    public function logout()
    {
        // ActivityLogger sudah dihandle saat logout lewat authService kalau mau, tapi cukup destroy session saja.
        \App\Libraries\ActivityLogger::log('LOGOUT', 'User logout', session()->get('user_id'));
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil keluar.');
    }

    // ================= FORGOT PASSWORD =================
    public function forgotPasswordView()
    {
        return view('auth/forgot_password');
    }

    public function sendResetCode()
    {
        // Rate Limiting: Maksimal 3 email reset per 5 menit per IP
        $throttler = \Config\Services::throttler();
        if ($throttler->check(md5('reset_' . $this->request->getIPAddress()), 3, 300) === false) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Terlalu banyak permintaan. Silakan coba lagi dalam 5 menit.');
        }

        $email = $this->request->getPost('email');

        $isSent = $this->authService->sendResetCode($email);

        if ($isSent) {
            session()->set('reset_email', $email);
            return redirect()->to('/auth/forgot-password')->with('step', 'verify')->with('success', 'Kode verifikasi telah dikirim ke email Anda.');
        } else {
            return redirect()->to('/auth/forgot-password')->with('error', 'Gagal memproses permintaan atau email tidak ditemukan.');
        }
    }

    public function verifyResetCode()
    {
        $code = $this->request->getPost('code');
        $email = session()->get('reset_email');

        if (!$email) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Sesi habis. Silakan ulangi.');
        }

        $isValid = $this->authService->verifyResetCode($email, $code);

        if (!$isValid) {
            return redirect()->to('/auth/forgot-password')->with('step', 'verify')->with('error', 'Kode salah atau sudah kedaluwarsa.');
        }

        session()->set('reset_verified', true);
        return redirect()->to('/auth/forgot-password')->with('step', 'reset')->with('success', 'Kode valid! Silakan buat kata sandi baru.');
    }

    public function resetPassword()
    {
        $email = session()->get('reset_email');
        $verified = session()->get('reset_verified');

        if (!$email || !$verified) {
            return redirect()->to('/auth/forgot-password')->with('error', 'Sesi tidak valid. Silakan ulangi.');
        }

        $password = $this->request->getPost('password');
        $confirm = $this->request->getPost('password_confirm');

        if (strlen($password) < 8) {
            return redirect()->to('/auth/forgot-password')->with('step', 'reset')->with('error', 'Kata sandi minimal 8 karakter.');
        }
        if ($password !== $confirm) {
            return redirect()->to('/auth/forgot-password')->with('step', 'reset')->with('error', 'Konfirmasi kata sandi tidak cocok.');
        }

        $isSuccess = $this->authService->resetPassword($email, $password);

        if ($isSuccess) {
            session()->remove(['reset_email', 'reset_verified']);
            return redirect()->to('/login')->with('success', 'Kata sandi berhasil diubah! Silakan login.');
        }

        return redirect()->to('/auth/forgot-password')->with('error', 'Terjadi kesalahan sistem.');
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
            // TRIGGER PUSHER: Kirim Notifikasi Real-time
            // =========================================================
            $pusherService = new PusherService();
            $pusherService->notifyNewAlumni($user['nama_panggilan']);
            // =========================================================

            return redirect()->to('/login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
        } else {
            return redirect()->to('/login')->with('error', 'Tautan tidak valid atau email sudah terverifikasi.');
        }
    }
}