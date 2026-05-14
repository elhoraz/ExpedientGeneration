<?php

namespace App\Services;

class AnalyticsService
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Mendapatkan statistik total pengunjung hari ini.
     */
    public function getTodayVisits(): int
    {
        return $this->db->table('page_visits')
            ->where('DATE(visited_at)', date('Y-m-d'))
            ->countAllResults();
    }

    /**
     * Mendapatkan tren pengunjung 7 hari terakhir untuk Chart.js.
     */
    public function getWeeklyTrend(): array
    {
        $trend = [];
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('d M', strtotime($date));
            $count = $this->db->table('page_visits')
                ->where('DATE(visited_at)', $date)
                ->countAllResults();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data'   => $data
        ];
    }

    /**
     * Mendapatkan halaman yang paling sering dikunjungi.
     */
    public function getTopPages(int $limit = 5): array
    {
        return $this->db->table('page_visits')
            ->select('page_url, COUNT(id) as total_visits')
            ->groupBy('page_url')
            ->orderBy('total_visits', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    /**
     * Ringkasan performa sistem lainnya.
     */
    public function getSystemSummary(): array
    {
        $totalAlumni = $this->db->table('users')->countAllResults();
        $totalBisnis = $this->db->table('syndicate')->countAllResults();
        $totalWasiat = $this->db->table('wasiat_messages')->countAllResults();

        return [
            'total_alumni' => $totalAlumni,
            'total_bisnis' => $totalBisnis,
            'total_wasiat' => $totalWasiat,
        ];
    }
}
