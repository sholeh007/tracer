<?php

namespace App\Controllers;

use App\Models\GrafikModel;

class Umum extends BaseController
{
  protected $umum;

  public function __construct()
  {
    $this->umum = new GrafikModel();
  }

  public function index()
  {
    if (session()->username) {
      if (session()->role_id == 1) {
        return redirect()->to('/berandaadmin');
      } else {
        return redirect()->to('/berandaalumni');
      }
    }
    $data = [
      'title' => 'Tracer Study',
      'angkatan' => $this->umum->getAngkatan(),
      'angkatanPria' => $this->umum->getAngkatanPria(),
      'angkatanWanita' => $this->umum->getAngkatanWanita(),
      'jurusanPria' => $this->umum->getJurusanPria(),
      'jurusanWanita' => $this->umum->getJurusanWanita(),
      'jurusan' => $this->umum->getJurusan(),
      'alumni' => $this->userModel->jumlahAlumni(),
      'dataJurusan' => $this->umum->getDataJurusan(),
      'pria' => $this->umum->getPersenPria(),
      'wanita' => $this->umum->getPersenWanita(),
      'jmlh_pria' => $this->umum->getTotalJumlahPria(),
      'jmlh_wanita' => $this->umum->getTotalJumlahWanita(),
      'bekerja' => $this->umum->sudahBekerja(),
      'belum_bekerja' => $this->umum->belumBekerja(),
      'persen_bekerja' => $this->umum->getPersenSudahKerja(),
      'persen_belum_bekerja' => $this->umum->getPersenBelumKerja(),
      'tahun_sudah_bekerja' => $this->umum->getTahunSudahBekerja(),
      'tahun_belum_bekerja' => $this->umum->getTahunBelumBekerja(),
      'jurusan_sudah_bekerja' => $this->umum->getJurusanSudahBekerja(),
      'jurusan_belum_bekerja' => $this->umum->getJurusanBelumBekerja(),
      'penggunaanPengetahuan' => $this->umum->totalPenggunaanPengetahuan(),
      'persenPenggunaanPengetahuan' => $this->umum->getPersenPengetahuanSudah(),
      'persenBelumPenggunaanPengetahuan' => $this->umum->getPersenPengetahuanBelum()
    ];

    return view('halaman-utama.html', $data);
  }

  //--------------------------------------------------------------------

}