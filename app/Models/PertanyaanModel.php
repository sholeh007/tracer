<?php

namespace App\Models;

use CodeIgniter\Model;

class PertanyaanModel extends Model
{
  protected $table = 'pertanyaan';
  protected $primaryKey = 'idpertanyaan';

  protected $allowedFields = ['Pertanyaan', 'isi1', 'isi2', 'isi3', 'isi4', 'isi5'];


  public function getQuest($idpertanyaan = false)
  {
    if ($idpertanyaan == false) {
      return $this->findAll();
    }

    return $this->find($idpertanyaan);
  }

  public function getJurusan()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('jurusan');

    return $builder->get()->getResultArray();
  }

  public function getGender()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('jenis_kelamin');

    return $builder->get()->getResultArray();
  }

  public function getStatusAlumni()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('user');

    return $builder->select('*')
      ->join('alumni', 'user_iduser = user.iduser', 'left')
      ->join('kuesioner', 'alumni_iduser = alumni.user_iduser', 'left')
      ->join('jenis_kelamin', 'jenis_kelamin.idkelamin = alumni.gender', 'left')
      ->join('jurusan', 'jurusan.idjurusan = alumni.jurusan', 'left')
      ->where('status_isi', 1)->orderBy('nama', 'ASC')->get()->getResultArray();
  }
}