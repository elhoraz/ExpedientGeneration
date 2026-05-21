<?php

namespace App\Controllers;

use App\Models\UserModel;

class DirektoriController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $isLoggedIn = session()->get('logged_in');
        $search = $this->request->getGet('q');

        if ($isLoggedIn) {
            $builder = $userModel->orderBy('nama_lengkap', 'ASC');
            if (!empty($search)) {
                $builder->groupStart()
                    ->like('nama_lengkap', $search)
                    ->orLike('nama_panggilan', $search)
                    ->orLike('email', $search)
                ->groupEnd();
            }
            $data['alumni'] = $builder->paginate(24);
        } else {
            $builder = $userModel
                ->select('id, nama_lengkap, nama_panggilan, foto_profil')
                ->orderBy('nama_lengkap', 'ASC');
            if (!empty($search)) {
                $builder->groupStart()
                    ->like('nama_lengkap', $search)
                    ->orLike('nama_panggilan', $search)
                ->groupEnd();
            }
            $data['alumni'] = $builder->paginate(24);
        }

        $data['pager'] = $userModel->pager;
        $data['isLoggedIn'] = $isLoggedIn;
        $data['search'] = $search;

        return view('direktori', $data);
    }
}