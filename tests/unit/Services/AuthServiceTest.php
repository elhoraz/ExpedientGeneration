<?php

namespace Tests\Unit\Services;

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\AuthService;
use App\Models\UserModel;

/**
 * AuthServiceTest
 * 
 * Unit test untuk AuthService — menguji logika otentikasi
 * secara terisolasi dari HTTP layer.
 */
class AuthServiceTest extends CIUnitTestCase
{
    protected AuthService $authService;
    protected UserModel $userModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
        $this->userModel = new UserModel();
    }

    /**
     * Test login dengan email yang tidak ada di database.
     */
    public function testLoginWithInvalidEmailReturnsNull(): void
    {
        $result = $this->authService->attemptLogin('tidakada@email.com', 'password123');
        $this->assertNull($result);
    }

    /**
     * Test login dengan password yang salah.
     */
    public function testLoginWithWrongPasswordReturnsNull(): void
    {
        // Buat user test terlebih dahulu
        $testEmail = 'test_login_' . uniqid() . '@test.com';
        $this->userModel->insert([
            'nama_lengkap'    => 'Test User',
            'nama_panggilan'  => 'Test',
            'jenis_kelamin'   => 'Laki-laki',
            'tempat_tanggal_lahir' => 'Test, 01 Jan 2000',
            'alamat_lengkap'  => 'Alamat Test',
            'email'           => $testEmail,
            'password_hash'   => password_hash('correctpassword', PASSWORD_DEFAULT),
            'no_whatsapp'     => '08123456789',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        $result = $this->authService->attemptLogin($testEmail, 'wrongpassword');
        $this->assertNull($result);

        // Cleanup
        $user = $this->userModel->where('email', $testEmail)->first();
        if ($user) $this->userModel->delete($user['id']);
    }

    /**
     * Test login dengan kredensial yang benar.
     */
    public function testLoginWithCorrectCredentialsReturnsUser(): void
    {
        $testEmail = 'test_correct_' . uniqid() . '@test.com';
        $password = 'mySecurePassword123';
        
        $this->userModel->insert([
            'nama_lengkap'    => 'Valid User',
            'nama_panggilan'  => 'Valid',
            'jenis_kelamin'   => 'Laki-laki',
            'tempat_tanggal_lahir' => 'Test, 01 Jan 2000',
            'alamat_lengkap'  => 'Alamat Valid',
            'email'           => $testEmail,
            'password_hash'   => password_hash($password, PASSWORD_DEFAULT),
            'no_whatsapp'     => '08123456780',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);

        $result = $this->authService->attemptLogin($testEmail, $password);
        
        $this->assertIsArray($result);
        $this->assertEquals($testEmail, $result['email']);
        $this->assertEquals('Valid', $result['nama_panggilan']);

        // Cleanup
        if ($result) $this->userModel->delete($result['id']);
    }

    /**
     * Test login dengan email yang belum diverifikasi harus throw exception.
     */
    public function testLoginWithUnverifiedEmailThrowsException(): void
    {
        $testEmail = 'test_unverified_' . uniqid() . '@test.com';
        
        $this->userModel->insert([
            'nama_lengkap'    => 'Unverified User',
            'nama_panggilan'  => 'Unverified',
            'jenis_kelamin'   => 'Perempuan',
            'tempat_tanggal_lahir' => 'Test, 01 Jan 2000',
            'alamat_lengkap'  => 'Alamat Unverified',
            'email'           => $testEmail,
            'password_hash'   => password_hash('password', PASSWORD_DEFAULT),
            'no_whatsapp'     => '08123456781',
            'email_verified_at' => null, // Belum diverifikasi!
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Email belum diverifikasi.');
        
        $this->authService->attemptLogin($testEmail, 'password');

        // Cleanup
        $user = $this->userModel->where('email', $testEmail)->first();
        if ($user) $this->userModel->delete($user['id']);
    }

    /**
     * Test reset code verification dengan kode salah.
     */
    public function testVerifyResetCodeWithWrongCodeReturnsFalse(): void
    {
        $result = $this->authService->verifyResetCode('tidakada@email.com', '000000');
        $this->assertFalse($result);
    }
}
