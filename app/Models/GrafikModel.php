<?php

namespace App\Models;

use CodeIgniter\Model;

class GrafikModel extends Model
{
  protected $table = 'alumni';
  protected $primaryKey = 'idalumni';

  // function jurusan
  public function getJurusan()
  {
    return $this->db->table('jurusan')->select('Jurusan')->get()->getResultArray();
  }

  public function totalJurusan()
  {
    return $this->db->table('jurusan')->countAll();
  }

  public function getDataJurusan()
  {
    $query = [];
    for ($i = 1; $i <= $this->totalJurusan(); $i++) {
      $query[] = $this->select('*')->join('jurusan', 'idjurusan = alumni.jurusan')->where('idjurusan', $i)->countAllResults();
    }
    return $query;
  }

  public function getJurusanPria()
  {
    $query = [];
    for ($i = 1; $i <= $this->totalJurusan(); $i++) {
      $query[] = $this->where(['gender' => 1, 'jurusan' => $i])->countAllResults();
    }
    return $query;
  }

  public function getJurusanWanita()
  {
    $query = [];
    for ($i = 1; $i <= $this->totalJurusan(); $i++) {
      $query[] = $this->where(['gender' => 2, 'jurusan' => $i])->countAllResults();
    }
    return $query;
  }
  // akhir function jurusan

  // function thn_lulus
  public function getAngkatan()
  {
    $query = [];
    for ($i = 2011; $i <= date('Y'); $i++) {
      $query[] = $this->where('thn_lulus', $i)->countAllResults();
    }
    return $query;
  }
  // akhir function thn lulus

  // function gender
  public function totalGenderAlumni()
  {
    return $this->countAll();
  }
  public function getTotalJumlahPria()
  {
    return $this->select("*")->join("jenis_kelamin", "idkelamin = alumni.gender")
      ->where("idkelamin", 1)->countAllResults();
  }

  public function getTotalJumlahWanita()
  {
    return $this->select("*")->join("jenis_kelamin", "idkelamin = alumni.gender")
      ->where("idkelamin", 2)->countAllResults();
  }

  public function getPersenPria()
  {
    if ($this->totalGenderAlumni() == 0.0) {
      $data = 0;
    } else {
      $data = (($this->getTotalJumlahPria() / $this->totalGenderAlumni()) * 100);
    }

    return $data;
  }

  public function getPersenWanita()
  {
    if ($this->totalGenderAlumni() == 0.0) {
      $data = 0;
    } else {
      $data = (($this->getTotalJumlahWanita() / $this->totalGenderAlumni()) * 100);
    }
    return $data;
  }


  public function getAngkatanPria()
  {
    $query = [];
    for ($i = 2011; $i <= date('Y'); $i++) {
      $query[] = $this->where(['gender' => 1, 'thn_lulus' => $i])->countAllResults();
    }
    return $query;
  }

  public function getAngkatanWanita()
  {
    $query = [];
    for ($i = 2011; $i <= date('Y'); $i++) {
      $query[] = $this->where(['gender' => 2, 'thn_lulus' => $i])->countAllResults();
    }
    return $query;
  }
  // akhir function gender

  // function respon isi kuesioner
  public function getJumlahUser()
  {
    return $this->db->table('user')->where('role_id', 2)->countAllResults();
  }
  public function getResponSuccess()
  {
    return $this->db->table('user')->where(['status_isi' => 1, 'role_id' => 2])->countAllResults();
  }

  public function getResponFaile()
  {
    return $this->db->table('user')->where(['status_isi' => 0, 'role_id' => 2])->countAllResults();
  }

  public function getPersenResponSuccess()
  {
    if ($this->getResponSuccess() == 0) {
      $data = 0;
    } else {
      $data = (($this->getResponSuccess() / $this->getJumlahUser()) * 100);
    }
    return $data;
  }

  public function getPersenResponFail()
  {
    if ($this->getResponFaile() == 0) {
      $data = 0;
    } else {
      $data = (($this->getResponFaile() / $this->getJumlahUser()) * 100);
    }
    return $data;
  }
  // akhir function respon isi kuesioner

  // function status bekerja
  public function totalBekerja()
  {
    return $this->db->table('kuesioner')->countAll();
  }

  public function sudahBekerja()
  {
    $builder = $this->db->table('kuesioner');
    return $builder->where('jawaban1', 'sudah')->countAllResults();
  }

  public function belumBekerja()
  {
    $builder = $this->db->table('kuesioner');
    return $builder->where('jawaban1', 'belum')->countAllResults();
  }

  public function getPersenSudahKerja()
  {
    if ($this->totalBekerja() == 0.0) {
      $data = 0;
    } else {
      $data = (($this->sudahBekerja() / $this->totalBekerja()) * 100);
    }
    return $data;
  }

  public function getPersenBelumKerja()
  {
    if ($this->totalBekerja() == 0.0) {
      $data = 0;
    } else {
      $data = (($this->belumBekerja() / $this->totalBekerja()) * 100);
    }
    return $data;
  }

  public function getTahunSudahBekerja()
  {
    $builder = $this->db->table('alumni');
    $query = [];
    for ($i = 2011; $i <= date('Y'); $i++) {
      $query[] = $builder->join('kuesioner', 'alumni_iduser = alumni.user_iduser', 'left')->where(['thn_lulus' => $i, 'jawaban1' => 'sudah'])->countAllResults();
    }
    return $query;
  }

  public function getTahunBelumBekerja()
  {
    $builder = $this->db->table('alumni');
    $query = [];
    for ($i = 2011; $i <= date('Y'); $i++) {
      $query[] = $builder->join('kuesioner', 'alumni_iduser = alumni.user_iduser', 'left')->where(['thn_lulus' => $i, 'jawaban1' => 'belum'])->countAllResults();
    }
    return $query;
  }

  public function getJurusanSudahBekerja()
  {
    $builder = $this->db->table('alumni');
    $query = [];

    for ($i = 1; $i <= $this->totalJurusan(); $i++) {
      $query[] = $builder->join('kuesioner', 'alumni_iduser = alumni.user_iduser', 'left')->where(['jurusan' => $i, 'jawaban1' => 'sudah'])->countAllResults();
    }
    return $query;
  }

  public function getJurusanBelumBekerja()
  {
    $builder = $this->db->table('alumni');
    $query = [];

    for ($i = 1; $i <= $this->totalJurusan(); $i++) {
      $query[] = $builder->join('kuesioner', 'alumni_iduser = alumni.user_iduser', 'left')->where(['jurusan' => $i, 'jawaban1' => 'belum'])->countAllResults();
    }
    return $query;
  }
  // akhir function status bekerja

  // function penggunaan pengetahuan
  public function totalPenggunaanPengetahuan()
  {
    return $this->db->table('kuesioner')->select('jawaban6')->where('jawaban1', 'sudah')->countAllResults();
  }

  public function pengetahuanSudahDigunakan()
  {
    $builder = $this->db->table('kuesioner');
    return $builder->where('jawaban6', 'sudah')->countAllResults();
  }

  public function pengetahuanBelumDigunakan()
  {
    $builder = $this->db->table('kuesioner');
    return $builder->where('jawaban6', 'belum')->countAllResults();
  }

  public function getPersenPengetahuanSudah()
  {
    if ($this->totalPenggunaanPengetahuan() == 0.0) {
      $data = 0;
    } else {
      $data = (($this->pengetahuanSudahDigunakan() / $this->totalPenggunaanPengetahuan()) * 100);
    }
    return $data;
  }

  public function getPersenPengetahuanBelum()
  {
    if ($this->totalPenggunaanPengetahuan() == 0.0) {
      $data = 0;
    } else {
      $data = (($this->pengetahuanBelumDigunakan() / $this->totalPenggunaanPengetahuan()) * 100);
    }
    return $data;
  }
  // akhir function penggunaan pengetahuan
}