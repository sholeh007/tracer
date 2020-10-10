<?php

namespace App\Controllers\Alumni;

use App\Controllers\BaseController;

class BerandaAlumni extends BaseController
{
    public function index()
    {
        if (!session()->username) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Beranda Alumni',
            'info' => $this->userModel->getUserLog()
        ];

        return view('alumni/index.html', $data);
    }
}