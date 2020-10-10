<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class User extends BaseController
{
  public function index()
  {
    if (!session()->username) {
      return redirect()->to('/auth');
    }

    $data = [
      'title' => 'User manajemen',
      'user' => $this->userModel->getRole(),
      'info' => $this->userModel->getUserLog()
    ];

    return view('admin/user.html', $data);
  }

  public function createUser()
  {
    // cek ada session tidak
    if (!session()->username) {
      return redirect()->to('/auth');
    }

    $data = [
      'title' => 'form tambah user',
      'user' => $this->userModel->getUser(),
      'info' => $this->userModel->getUserLog(),
      'validation' => \Config\Services::validation()
    ];

    return view('admin/create-user.html', $data);
  }

  public function saveUser()
  {
    // validasi form
    if (!$this->validate([
      'username' => [
        'rules' => 'required|is_unique[user.username]',
        'errors' => [
          'required' => '{field} harus di isi!',
          'is_unique' => '{field} sudah terdaftar'
        ]
      ],
      'password' => [
        'rules' => 'required|trim',
        'errors' => [
          'required' => '{field} harus di isi!'
        ]
      ],
      'role' => [
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus di isi!'
        ]
      ]
    ])) {
      return redirect()->to('/user/createUser')->withInput();
    }

    // validasi berhasil
    $this->userModel->save([
      'username' => $this->request->getPost('username'),
      'email' => '',
      'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
      'role_id' => $this->request->getVar('role'),
      'gambar' => 'default.jpg'
      // 'status' => 0
    ]);

    session()->setFlashdata('pesan', 'data berhasil ditambahkan!');
    return redirect()->to('/user');
  }

  public function editUser($iduser)
  {
    $db = \Config\Database::connect();
    $data = [
      'title' => 'form edit user',
      'user' => $this->userModel->getUser($iduser),
      'info' => $this->userModel->getUserLog(),
      'validation' => \Config\Services::validation(),
      'role' => $db->table('user_role')->get()->getResultArray()
    ];

    return view('admin/edit-user.html', $data);
  }

  public function updateUser($iduser)
  {
    // validasi form
    if (!$this->validate([
      'username' => [
        'rules' => 'required|is_unique[user.username,iduser,{iduser}]',
        'errors' => [
          'required' => '{field} harus di isi!',
          'is_unique' => '{field} sudah terdaftar'
        ]
      ],
      'role' => [
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus di isi!'
        ]
      ]
    ])) {
      return redirect()->to('/user/edituser/' . $this->request->getVar('iduser'))->withInput();
    }

    // validasi berhasil
    $this->userModel->save([
      'iduser' => $iduser,
      'username' => $this->request->getPost('username'),
      'role_id' => $this->request->getVar('role'),
    ]);

    session()->setFlashdata('pesan', 'data berhasil diubah!');
    return redirect()->to('/user');
  }

  //--------------------------------------------------------------------

}