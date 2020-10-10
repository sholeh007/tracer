<?php

namespace App\Controllers;

class Auth extends BaseController
{
  public function index()
  {
    // cek kalau sudah lagin gak boleh kembali
    if (session()->username) {
      if (session()->role_id == 1) {
        return redirect()->to('/berandaadmin');
      } else {
        return redirect()->to('/berandaalumni');
      }
    }

    $data = [
      'title' => 'Login',
      'validation' => \Config\Services::validation()
    ];

    return view('auth/login.html', $data);
  }

  public function loginCek()
  {
    // cek validasi
    if (!$this->validate([
      'username' => [
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus di isi!'
        ]
      ],
      'password' => [
        'rules' => 'required|trim',
        'errors' => [
          'required' => '{field} harus di isi!'
        ]
      ]
    ])) {
      return redirect()->to('/auth')->withInput();
    }
    // validasi berhasil
    return $this->login();
  }

  private function login()
  {
    $username = $this->request->getVar('username');
    $password = $this->request->getVar('password');

    $user = $this->userModel->getWhere(['username' => $username])->getRowArray();

    // cek jika user ada
    if ($user) {
      // cek password
      if (password_verify($password, $user['password'])) {
        // login berhasil
        $data = [
          'username' => $user['username'],
          'role_id' => $user['role_id']
        ];

        session()->set($data);
        // arahkan sesuai rolenya
        if ($user['role_id'] == 1) {
          return redirect()->to('/berandaadmin');
        } else {
          return redirect()->to('/berandaalumni');
        }
      } else {
        session()->setFlashdata('pesan', '<div class="alert alert-danger" role="alert">Oops! Password salah!</div>');
        return redirect()->to('/auth');
      }
    } else {
      session()->setFlashdata('pesan', '<div class="alert alert-danger" role="alert">Oops! Username tidak ada!</div>');
      return redirect()->to('/auth');
    }
  }

  public function logout()
  {
    session()->destroy();
    return redirect()->to('/');
  }

  public function blocked()
  {
    return view('auth/block.html');
  }

  public function ubahPassword()
  {
    $data = [
      'title' => 'Ubah password',
      'validation' => \Config\Services::validation(),
      'info' => $this->userModel->getUserLog()
    ];

    return view('auth/reset_password.html', $data);
  }

  public function savePasswordBaru()
  {
    // cek validasi
    if (!$this->validate([
      'passwordLama' => [
        'rules' => 'required|trim',
        'errors' => [
          'required' => 'Password lama harus di isi!'
        ]
      ],
      'password1' => [
        'rules' => 'required|trim|min_length[6]|matches[password2]',
        'errors' => [
          'required' => 'password harus di isi!',
          'min_length' => 'password terlalu pendek, min 6 karakter.',
          'matches' => 'password tidak sama'
        ]
      ],
      'password2' => [
        'rules' => 'required|trim|min_length[6]|matches[password1]',
        'errors' => [
          'required' => 'password harus di isi!',
          'min_length' => 'password terlalu pendek, min 6 karakter.',
          'matches' => 'password tidak sama'
        ]
      ]
    ])) {
      return redirect()->to('/auth/ubahpassword')->withInput();
    }

    // cek password 
    $passwordLama = $this->request->getVar('passwordLama');
    $passwordBaru = $this->request->getVar('password1');

    $db = \Config\Database::connect();
    $builder = $db->table('user');

    $user = $builder->getWhere(['username' => session()->username])->getRowArray();

    if (!password_verify($passwordLama, $user['password'])) {
      session()->setFlashdata('pesan', 'Password lama salah!');
      return redirect()->to('/auth/ubahpassword');
    } else {
      if ($passwordLama == $passwordBaru) {
        session()->setFlashdata('pesan', 'Password lama tidak boleh sama dengan password baru!');
        return redirect()->to('/auth/ubahpassword');
      } else {
        // password sudah ok
        $password_hash = password_hash($passwordBaru,  PASSWORD_DEFAULT);

        $builder->set('password', $password_hash);
        $builder->where('username', session()->username);
        $builder->update();

        session()->setFlashdata('pesan2', 'Password berhasil diubah, silahkan logout!');
        return redirect()->to('/auth/ubahpassword');
      }
    }
  }

  private function _sendEmail($token, $emailUser)
  {
    $email = \Config\Services::email();

    $email->setFrom('tracerstimik@gmail.com', 'Admin');
    $email->setTo($emailUser);
    $email->setSubject('Reset Password');
    $email->setMessage('Klik link ini untuk reset password, token akan kadaluarsa dalam 10 menit <a href="' . base_url() . '/auth/resetpassword?email=' . $emailUser . '&token=' . urlencode($token) . '">Reset Password</a>');

    $email->send();
  }

  public function forgotPassword()
  {
    $data = [
      'title' => 'Lupa Password',
      'validation' => \Config\Services::validation()
    ];

    return view('auth/lupa-password.html', $data);
  }

  public function saveForgot()
  {
    if (!$this->validate([
      'email' => [
        'rules' => 'required|valid_email|valid_emails|trim',
        'errors' => [
          'required' => '{field} harus di isi.',
          'valid_email' => '{field} tidak valid',
          'valid_emails' => '{field} ada koma'
        ]
      ]
    ])) {
      return redirect()->to('/auth/forgotpassword')->withInput();
    }
    // berhasil
    $email = $this->request->getPost('email');

    $user = $this->userModel->where('email', $email)->get()->getRowArray();

    if ($user) {
      $token = base64_encode(random_bytes(32));

      $this->userModel->insertToken($email, $token);

      $this->_sendEmail($token, $email);

      session()->setFlashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success!</strong> silahkan cek email anda untuk reset password.<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button></div>');
      return redirect()->to('/auth');
    } else {
      session()->setFlashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Ooops!</strong> Password tidak ada atau belum terdaftar!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button></div>');
      return redirect()->to('/auth/forgotpassword');
    }
  }

  public function resetPassword()
  {
    $email = $this->request->getVar('email');
    $token = $this->request->getVar('token');

    $user = $this->userModel->getEmailToken($email);

    if ($user) {
      $user_token = $this->userModel->getToken($token);

      if ($user_token) {
        if (time() - $user_token['date_created'] < (60 * 10)) {
          // buat session 
          session()->set('reset_email', $email);
          return $this->changePassword();
        } else {
          $this->userModel->hapusToken($email);
          session()->setFlashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Ooops!</strong> Token kadaluarsa<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span></button></div>');
          return redirect()->to('/auth');
        }
      } else {
        session()->setFlashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Ooops!</strong> Token salah.<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span></button></div>');
        return redirect()->to('/auth');
      }
    } else {
      session()->setFlashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Ooops!</strong> Reset password gagal, email salah.<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button></div>');
      return redirect()->to('/auth');
    }
  }

  public function changePassword()
  {
    if (!session()->reset_email) {
      return redirect()->to('/auth');
    }

    $data = [
      'title' => 'Ubah Password',
      'validation' => \Config\Services::validation()
    ];

    return view('auth/ubah-lupa-password.html', $data);
  }

  public function prosesUbahPassword()
  {
    if (!$this->validate([
      'password' => [
        'rules' => 'required|trim|min_length[6]|matches[password2]',
        'errors' => [
          'required' => 'password harus di isi!',
          'min_length' => 'password terlalu pendek, min 6 karakter.',
          'matches' => 'password tidak sama'
        ]
      ],
      'password2' => [
        'rules' => 'required|trim|min_length[6]|matches[password]',
        'errors' => [
          'required' => 'password harus di isi!',
          'min_length' => 'password terlalu pendek, min 6 karakter.',
          'matches' => 'password tidak sama'
        ]
      ]
    ])) {
      return redirect()->to('/auth/changepassword')->withInput();
    }

    $password = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);

    $email = session()->reset_email;

    $this->userModel->updatePasswordLupa($email, $password);

    $this->userModel->hapusToken($email);

    session()->remove('reset_email');
    session()->setFlashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success</strong> password berhasil di ubah, silahkan login.<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button></div>');
    return redirect()->to('/auth');
  }
  //--------------------------------------------------------------------

}