<?php

namespace App\Models;

use CodeIgniter\Model;

class KuesionerModel extends Model
{
  public function saveKuesioner($data)
  {
    $builder = $this->db->table('alumni');

    $builder->insert($data);
  }

  public function saveKuesioner2($data2)
  {
    $builder = $this->db->table('kuesioner');

    $builder->insert($data2);
  }

  public function getKuesioner($id = false)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('user');

    // if ($id == false) {
    //   return $builder->select('*')->join('alumni', 'user_iduser = user.iduser')
    //     ->join('kuesioner', 'alumni_iduser = alumni.user_iduser')->get()->getRowArray();
    // }

    return $builder->select('*')->join('alumni', 'user_iduser = user.iduser')
      ->join('kuesioner', 'alumni_iduser = alumni.user_iduser')->where('alumni_iduser', $id)->get()->getRowArray();
  }

  public function editKuesioner($data, $id)
  {
    $builder = $this->db->table('alumni');

    $builder->where('user_iduser', $id)->update($data);
  }

  public function editKuesioner2($data2, $id)
  {
    $builder = $this->db->table('kuesioner');

    $builder->where('alumni_iduser', $id)->update($data2);
  }
}