<?php

namespace App\Controllers;

class Profile extends BaseController
{
  public function index()
  {
    $data = [
      'title' => 'Profile',
      'info' => $this->userModel->getUserLog()
    ];

    return view('umum/profile.html', $data);
  }

  public function editProfile($iduser)
  {
    $data = [
      'title' => 'edit Profile',
      'validation' => \Config\Services::validation(),
      'user' => $this->userModel->getUser($iduser),
      'info' => $this->userModel->getUserLog()
    ];

    return view('umum/edit-profile.html', $data);
  }

  public function saveProfile($iduser)
  {
    if (!$this->validate([
      'username' => [
        'rules' => 'required|is_unique[user.username,iduser,{iduser}]',
        'errors' => [
          'required' => '{field} harus di isi.',
          'is_unique' => '{field} sudah ada.'
        ]
      ],
      'email' => [
        'rules' => 'required|valid_email|valid_emails|trim|is_unique[user.email,iduser,{iduser}]',
        'errors' => [
          'required' => '{field} harus di isi.',
          'valid_email' => '{field} tidak valid',
          'valid_emails' => '{field} ada koma',
          'is_unique' => '{field} sudah ada'
        ]
      ],
      'gambar' => [
        'rules' => 'max_size[gambar,1024]|mime_in[gambar,image/png,image/jpg,image/jpeg]|is_image[gambar]',
        'errors' => [
          'max_size' => 'max ukuran {field} 1MB',
          'mime_in' => 'format {field} yang didukung jpg/jpeg dan png',
          'is_image' => 'file yang anda upload bukan gambar'
        ]
      ]
    ])) {
      return redirect()->to('/profile/editprofile/' . $this->request->getVar('iduser'))->withInput();
    }

    // ambil file gambar
    $fileGambar = $this->request->getFile('gambar');
    // cek gambar, gambar lama atau baru
    if ($fileGambar->getError() == 4) {
      $namaGambar = $this->request->getVar('gambarLama');
    } else {
      // generate nama file random
      $namaGambar = $fileGambar->getRandomName();
      // pindahkan gambar
      $fileGambar->move('assets/img', $namaGambar);
      // hapus file lama
      // cek jika file gambar default jangan di hapus
      if ($this->request->getVar('gambarLama') != 'default.jpg') {
        unlink('assets/img/' . $this->request->getVar('gambarLama'));
      }
    }

    $this->userModel->save([
      'iduser' => $iduser,
      'username' => $this->request->getPost('username'),
      'email' => $this->request->getPost('email'),
      'gambar' => $namaGambar,
    ]);

    session()->setFlashdata('pesan', 'profile berhasil diubah');
    session()->setFlashdata('pesan2', '<div class="alert alert-warning" role="alert">Silahkan login ulang,gunakan username yang baru di edit</div>');

    return redirect()->to('/profile');
  }

  //--------------------------------------------------------------------

}