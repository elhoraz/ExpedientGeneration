<?php

namespace App\Services;

use App\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * NexusService (The Nexus AI Matching)
 * 
 * Mesin pencocokan mandiri yang menggunakan tokenisasi teks sederhana
 * dan analisis kategori relasional untuk menghubungkan alumni dengan visi serupa.
 */
class NexusService
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userModel = new UserModel();
    }

    /**
     * Mengambil 3 kolega paling cocok untuk user_id tertentu
     */
    public function findTopMatches(int $targetUserId, int $limit = 3): array
    {
        $targetUser = $this->getUserWithSyndicate($targetUserId);
        if (!$targetUser) return [];

        $allUsers = $this->getAllUsersWithSyndicateExcept($targetUserId);
        
        $matches = [];
        
        foreach ($allUsers as $candidate) {
            $score = $this->calculateSimilarity($targetUser, $candidate);
            if ($score > 0) {
                $candidate['match_score'] = $score;
                $matches[] = $candidate;
            }
        }

        // Urutkan berdasarkan score terbesar
        usort($matches, function ($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });

        return array_slice($matches, 0, $limit);
    }

    private function getUserWithSyndicate(int $userId)
    {
        return $this->db->table('users')
            ->select('users.*, syndicate.kategori as syndicate_category')
            ->join('syndicate', 'syndicate.user_id = users.id', 'left')
            ->where('users.id', $userId)
            ->get()->getRowArray();
    }

    private function getAllUsersWithSyndicateExcept(int $userId)
    {
        return $this->db->table('users')
            ->select('users.id, users.public_token, users.nama_lengkap, users.nama_panggilan, users.foto_profil, users.cita_cita, users.motivasi_hidup, syndicate.kategori as syndicate_category')
            ->join('syndicate', 'syndicate.user_id = users.id', 'left')
            ->where('users.id !=', $userId)
            ->get()->getResultArray();
    }

    /**
     * Inti Algoritma Pencocokan
     */
    private function calculateSimilarity(array $target, array $candidate): int
    {
        $score = 0;

        // 1. Kategori Syndicate (Bobot sangat besar: 40 poin)
        if (!empty($target['syndicate_category']) && !empty($candidate['syndicate_category'])) {
            if (strtolower($target['syndicate_category']) === strtolower($candidate['syndicate_category'])) {
                $score += 40;
            }
        }

        // 2. Tokenisasi Visi (Cita-cita & Motivasi)
        $targetTokens = $this->tokenizeText(($target['cita_cita'] ?? '') . ' ' . ($target['motivasi_hidup'] ?? ''));
        $candidateTokens = $this->tokenizeText(($candidate['cita_cita'] ?? '') . ' ' . ($candidate['motivasi_hidup'] ?? ''));

        if (!empty($targetTokens) && !empty($candidateTokens)) {
            $intersection = array_intersect($targetTokens, $candidateTokens);
            $union = array_unique(array_merge($targetTokens, $candidateTokens));
            
            // Jaccard Index * 60 (maksimal 60 poin dari teks)
            $jaccardIndex = count($intersection) / count($union);
            $score += (int)round($jaccardIndex * 60);
        } else {
            // Jika data teks kosong, berikan bonus random kecil 5-15% agar selalu ada koneksi
            $score += rand(5, 15);
        }

        // Normalisasi
        if ($score > 99) $score = 99; // 99% max to feel realistic

        return $score;
    }

    private function tokenizeText(string $text): array
    {
        $text = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $text));
        $words = explode(' ', $text);
        
        $stopwords = ['dan', 'yang', 'untuk', 'di', 'ke', 'dari', 'dalam', 'akan', 'ini', 'itu', 'dengan', 'saya', 'aku', 'menjadi', 'sebagai', 'pada', 'atau', 'ingin'];
        
        $tokens = [];
        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) > 3 && !in_array($word, $stopwords)) {
                $tokens[] = $word;
            }
        }
        
        return array_unique($tokens);
    }
}
