<?php

namespace App\Controllers;

use App\Models\GenesisLogModel;

class GenesisController extends BaseController
{
    public function index()
    {
        return view('genesis_core');
    }

    public function log()
    {
        $model = new GenesisLogModel();
        
        $model->insert([
            'user_id' => session()->get('user_id'),
            'event'   => 'Singularity Initiated',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Singularity Logged to Genesis Core.');
    }
}
