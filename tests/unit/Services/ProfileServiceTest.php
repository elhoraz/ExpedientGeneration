<?php

namespace Tests\Unit\Services;

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\ProfileService;

/**
 * ProfileServiceTest
 * 
 * Unit test untuk ProfileService — menguji validasi foto profil.
 */
class ProfileServiceTest extends CIUnitTestCase
{
    protected ProfileService $profileService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profileService = new ProfileService();
    }

    /**
     * Test Base64 foto dengan format tidak valid.
     */
    public function testProcessPhotoWithInvalidFormatThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Format Base64 tidak valid.');
        
        $this->profileService->processProfilePhoto('bukan-base64', 999);
    }

    /**
     * Test Base64 foto dengan MIME type yang tidak diizinkan.
     */
    public function testProcessPhotoWithDisallowedMimeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Format foto tidak didukung');
        
        // GIF tidak diizinkan
        $fakeBase64 = 'data:image/gif;base64,' . base64_encode('fake-image-data');
        $this->profileService->processProfilePhoto($fakeBase64, 999);
    }

    /**
     * Test Base64 foto yang melebihi ukuran maksimum (2MB).
     */
    public function testProcessPhotoWithOversizeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Ukuran foto terlalu besar');
        
        // Buat data > 2MB
        $bigData = str_repeat('x', 3 * 1024 * 1024); // 3MB
        $fakeBase64 = 'data:image/jpeg;base64,' . base64_encode($bigData);
        $this->profileService->processProfilePhoto($fakeBase64, 999);
    }
}
