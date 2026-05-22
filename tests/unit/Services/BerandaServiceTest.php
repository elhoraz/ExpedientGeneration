<?php

namespace Tests\Unit\Services;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Services\BerandaService;
use App\Models\UserModel;

class BerandaServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';

    public function testGetDashboardDataReturnsExpectedKeys()
    {
        $service = new BerandaService();
        $data = $service->getDashboardData();

        $this->assertArrayHasKey('total_alumni', $data);
        $this->assertArrayHasKey('alumni_aktif', $data);
        $this->assertArrayHasKey('total_provinsi', $data);
        $this->assertArrayHasKey('tahun_kebangkitan', $data);
        $this->assertArrayHasKey('syndicate_count', $data);
        $this->assertArrayHasKey('misi_count', $data);
        $this->assertArrayHasKey('events', $data);
        $this->assertArrayHasKey('announcements', $data);
        $this->assertArrayHasKey('latest_birthdays', $data);
        $this->assertArrayHasKey('recent_members', $data);
    }
}
