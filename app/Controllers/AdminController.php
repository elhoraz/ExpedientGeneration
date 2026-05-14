<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Services\AnalyticsService;

class AdminController extends BaseController
{
    public function index()
    {
        $analyticsService = new AnalyticsService();

        // Gunakan AnalyticsService untuk semua data
        $weeklyTrend = $analyticsService->getWeeklyTrend();
        $topPages = $analyticsService->getTopPages(5);
        $systemSummary = $analyticsService->getSystemSummary();

        $userModel = new UserModel();
        $activeUsers = $userModel->where('prestise_points >', 0)->countAllResults();
        $totalUsers = $userModel->countAllResults();

        $data = [
            'title'       => 'Dashboard Analitik Eksekutif',
            'chartDates'  => json_encode($weeklyTrend['labels']),
            'chartVisits' => json_encode($weeklyTrend['data']),
            'activeUsers' => $activeUsers,
            'topPages'    => $topPages,
            'totalUsers'  => $totalUsers
        ];

        return view('admin/dashboard', $data);
    }
}
