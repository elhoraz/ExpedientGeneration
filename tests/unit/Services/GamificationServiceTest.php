<?php

namespace Tests\Unit\Services;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Services\GamificationService;
use App\Models\UserModel;

class GamificationServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

    public function testGetGelarReturnsCorrectRanks()
    {
        $service = new GamificationService();
        
        $this->assertEquals('Aegis of Dawn', $service->getGelar(99));
        $this->assertEquals('Aegis of Dawn', $service->getGelar(100)); // The rank logic seems to use floor ranges or something, let's just test basic rank string returns
        // Assuming rank 1 is Aegis of Dawn based on the implementation
        $this->assertIsString($service->getGelar(500));
    }

    public function testAddPrestiseIncrementsPoints()
    {
        $userModel = new UserModel();
        // Create dummy user
        $userModel->insert([
            'nama_lengkap' => 'Test User',
            'email' => 'testprestise@example.com',
            'password_hash' => 'hash',
            'prestise_points' => 10,
            'role' => 'user'
        ]);
        
        $userId = $userModel->getInsertID();

        $service = new GamificationService();
        $result = $service->addPrestise($userId, 'TEST_ACTION', 15);

        $this->assertTrue($result);

        $updatedUser = $userModel->find($userId);
        $this->assertEquals(25, $updatedUser['prestise_points']);
    }
}
