<?php

namespace App\Services;

use App\Models\SyndicateModel;
use App\Services\GamificationService;
use App\Libraries\ActivityLogger;

/**
 * SyndicateService
 * 
 * Menangani logika bisnis CRUD portofolio bisnis dan pengelolaan file logo.
 */
class SyndicateService
{
    protected SyndicateModel $syndicateModel;

    public function __construct()
    {
        $this->syndicateModel = new SyndicateModel();
    }

    /**
     * Menyimpan portofolio bisnis baru beserta logonya.
     */
    public function storeBisnis(int $userId, array $data, $fileLogo = null): void
    {
        $namaFileLogo = null;
        if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
            $namaFileLogo = $fileLogo->getRandomName();
            $fileLogo->move(FCPATH . 'uploads/bisnis/', $namaFileLogo);
        }

        $data['user_id'] = $userId;
        $data['logo_bisnis'] = $namaFileLogo;
        
        $this->syndicateModel->insert($data);

        // Tambahkan poin prestise
        $gamificationService = new GamificationService();
        $gamificationService->addPrestise($userId, 'SYNDICATE_ADD', 50);
    }

    /**
     * Memperbarui data bisnis dan logo (menghapus logo lama jika ada).
     * @throws \Exception Jika user tidak memiliki otoritas
     */
    public function updateBisnis(int $id, int $userId, array $data, $fileLogo = null): void
    {
        $bisnis = $this->syndicateModel->find($id);
        if (!$bisnis || $bisnis['user_id'] != $userId) {
            throw new \Exception('Otorisasi gagal. Anda tidak memiliki akses.');
        }

        if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
            if (!empty($bisnis['logo_bisnis'])) {
                $oldPath = FCPATH . 'uploads/bisnis/' . $bisnis['logo_bisnis'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                    ActivityLogger::log('LOGO_CLEANUP', "File logo lama dihapus: {$bisnis['logo_bisnis']}", $userId);
                }
            }
            $namaFileLogo = $fileLogo->getRandomName();
            $fileLogo->move(FCPATH . 'uploads/bisnis/', $namaFileLogo);
            $data['logo_bisnis'] = $namaFileLogo;
        }

        $this->syndicateModel->update($id, $data);
    }

    /**
     * Menghapus portofolio beserta logonya.
     * @throws \Exception Jika user tidak memiliki otoritas
     */
    public function deleteBisnis(int $id, int $userId): void
    {
        $bisnis = $this->syndicateModel->find($id);
        if (!$bisnis || $bisnis['user_id'] != $userId) {
            throw new \Exception('Otorisasi gagal.');
        }

        if (!empty($bisnis['logo_bisnis'])) {
            $oldPath = FCPATH . 'uploads/bisnis/' . $bisnis['logo_bisnis'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
                ActivityLogger::log('LOGO_CLEANUP', "File logo dihapus (delete data): {$bisnis['logo_bisnis']}", $userId);
            }
        }

        $this->syndicateModel->delete($id);
    }
}
