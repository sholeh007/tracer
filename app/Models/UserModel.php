<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
  protected $table = 'user';
  protected $primaryKey = 'iduser';

  protected $allowedFields = ['nama', 'username', 'email', 'password', 'role_id', 'gambar', 'status_isi'];

  public function getUser($iduser = false)
  {
    if ($iduser == false) {
      return $this->db->table('user')
        ->join('user_role', 'iduser_role = user.iduser', 'right')
        ->get()->getResultArray();
    }

    return $this->where(['iduser' => $iduser])->first();
  }

  public function getRole()
  {
    return $this->db->table('user')->join('user_role', 'iduser_role = user.role_id')->get()->getResultArray();
  }

  public function getUserLog()
  {
    return $this->db->table('user')->getWhere(['username' => session()->username])->getRowArray();
  }

  public function insertToken($email, $token)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('user_token');

    $data = [
      'email' => $email,
      'token' => $token,
      'date_created' => time()
    ];

    $builder->insert($data);
  }

  public function getEmailToken($email)
  {
    $db = \Config\Database::connect();
    return $db->table('user_token')->getWhere(['email' => $email])->getRowArray();
  }

  public function getToken($token)
  {
    $db = \Config\Database::connect();
    return $db->table('user_token')->getWhere(['token' => $token])->getRowArray();
  }

  public function updatePasswordLupa($email, $password)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('user');

    $builder->set('password', $password);
    $builder->where('email', $email);
    $builder->update();
  }

  public function hapusToken($email)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('user_token');

    $builder->delete(['email' => $email]);
  }

  public function getAlumni()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('user');

    return $builder->select('*')
      ->join('alumni', 'alumni.user_iduser = user.iduser', 'left')
      ->join('kuesioner', 'alumni_iduser = alumni.user_iduser', 'left')
      ->join('jenis_kelamin', 'jenis_kelamin.idkelamin = alumni.gender', 'left')
      ->join('jurusan', 'jurusan.idjurusan = alumni.jurusan', 'left')
      ->where('role_id', 2)->get()->getResultArray();
  }

  public function jumlahUser()
  {
    return $this->countAll();
  }

  public function jumlahAlumni()
  {
    return $this->where('role_id', 2)->countAllResults();
  }
}