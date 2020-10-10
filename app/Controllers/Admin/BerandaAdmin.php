<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GrafikModel;

class BerandaAdmin extends BaseController
{

    public function __construct()
    {
        $this->grafik = new GrafikModel();
    }

    public function index()
    {
        if (!session()->username) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Beranda admin',
            'info' => $this->userModel->getUserLog(),
            'jumlah_user' => $this->userModel->jumlahUser(),
            'jumlah_alumni' => $this->userModel->jumlahAlumni(),
            'sudah_isi' => $this->grafik->getResponSuccess(),
            'belum_isi' => $this->grafik->getResponFaile(),
            'persen_sudah_isi' => $this->grafik->getPersenResponSuccess(),
            'persen_belum_isi' => $this->grafik->getPersenResponFail(),
        ];

        return view('admin/index.html', $data);
    }
}