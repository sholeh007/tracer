<?php

namespace App\Controllers\Alumni;

use App\Controllers\BaseController;
use App\Models\KuesionerModel;
use App\Models\PertanyaanModel;

class KuesionerAlumni extends BaseController
{
  protected $quest;
  protected $kuesioner;

  public function __construct()
  {
    $this->quest = new PertanyaanModel();
    $this->kuesioner = new KuesionerModel();
  }

  public function index($id)
  {
    if (!session()->username) {
      return redirect()->to('/');
    }

    $data = [
      'title' => 'kuesioner',
      'validation' => \Config\Services::validation(),
      'info' => $this->userModel->getUserLog(),
      'quest' => $this->quest->findColumn('Pertanyaan'),
      'pilihan' => $this->quest->findColumn('isi1'),
      'pilihan2' => $this->quest->findColumn('isi2'),
      'pilihan3' => $this->quest->findColumn('isi3'),
      'pilihan4' => $this->quest->findColumn('isi4'),
      'pilihan5' => $this->quest->findColumn('isi5'),
      'gender' => $this->quest->getGender(),
      'jurusan' => $this->quest->getJurusan(),
      'kuesioner' => $this->kuesioner->getKuesioner($id)
    ];

    return view('alumni/kuesioner.html', $data);
  }

  public function saveKuesioner($id)
  {
    // cek validasi
    if (!$this->validate([
      'nama' => [
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus di isi'
        ]
      ],
      'gender' => [
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus di isi'
        ]
      ],
      'lulus' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'harus di isi'
        ]
      ],
      'jurusan' => [
        'rules' => 'required',
        'errors' => [
          'required' => 'harus di isi'
        ]
      ]
    ])) {
      return redirect()->to('/kuesioneralumni/' . $id)->withInput();
    }

    $data = [
      'status_isi' => 1
    ];
    $this->userModel->update($id, $data);

    $data2 = [
      'user_iduser' => $id,
      'nama' => $this->request->getPost('nama'),
      'gender' => $this->request->getPost('gender'),
      'jurusan' => $this->request->getPost('jurusan'),
      'thn_lulus' => $this->request->getPost('lulus'),
    ];
    $this->kuesioner->saveKuesioner($data2);

    $data3 = [
      'alumni_iduser' => $id,
      'jawaban1' => $this->request->getPost('bekerja'),
      'jawaban2' => $this->request->getPost('instansi'),
      'jawaban3' => $this->request->getPost('menunggu'),
      'jawaban4' => $this->request->getPost('gaji'),
      'jawaban5' => $this->request->getPost('materi'),
      'jawaban6' => $this->request->getPost('pendukung'),
      'jawaban7' => $this->request->getPost('hambatan'),
      'jawaban8' => $this->request->getPost('skill'),
      'jawaban9' => $this->request->getPost('saran')
    ];
    $this->kuesioner->saveKuesioner2($data3);

    session()->setFlashdata('pesan', 'Berhasil mengisi kuesioner');
    return redirect()->to('/kuesioneralumni/' . $id);
  }

  // public function editKuesioner($id)
  // {
  //   if (!session()->username) {
  //     return redirect()->to('/');
  //   }

  //   $data = [
  //     'title' => 'kuesioner',
  //     'validation' => \Config\Services::validation(),
  //     'info' => $this->userModel->getUserLog(),
  //     'quest' => $this->quest->findColumn('Pertanyaan'),
  //     'pilihan' => $this->quest->findColumn('isi1'),
  //     'pilihan2' => $this->quest->findColumn('isi2'),
  //     'pilihan3' => $this->quest->findColumn('isi3'),
  //     'pilihan4' => $this->quest->findColumn('isi4'),
  //     'pilihan5' => $this->quest->findColumn('isi5'),
  //     'gender' => $this->quest->getGender(),
  //     'jurusan' => $this->quest->getJurusan(),
  //     'kuesioner' => $this->kuesioner->getKuesioner($id)
  //   ];

  //   return view('alumni/edit-kuesioner.html', $data);
  // }

  // public function saveEditKuesioner($id)
  // {
  //   // cek validasi
  //   if (!$this->validate([
  //     'nama' => [
  //       'rules' => 'required',
  //       'errors' => [
  //         'required' => '{field} harus di isi'
  //       ]
  //     ],
  //     'gender' => [
  //       'rules' => 'required',
  //       'errors' => [
  //         'required' => '{field} harus di isi'
  //       ]
  //     ],
  //     'lulus' => [
  //       'rules' => 'required',
  //       'errors' => [
  //         'required' => 'harus di isi'
  //       ]
  //     ],
  //     'jurusan' => [
  //       'rules' => 'required',
  //       'errors' => [
  //         'required' => 'harus di isi'
  //       ]
  //     ]
  //   ])) {
  //     return redirect()->to('/kuesioneralumni/editkuesioner/' . $id)->withInput();
  //   }

  //   $data = [
  //     'nama' => $this->request->getPost('nama'),
  //     'gender' => $this->request->getPost('gender'),
  //     'jurusan' => $this->request->getPost('jurusan'),
  //     'thn_lulus' => $this->request->getPost('lulus')
  //   ];
  //   $this->kuesioner->editKuesioner($data, $id);

  //   $data2 = [
  //     'jawaban1' => $this->request->getPost('bekerja'),
  //     'jawaban2' => $this->request->getPost('instansi'),
  //     'jawaban3' => $this->request->getPost('menunggu'),
  //     'jawaban4' => $this->request->getPost('gaji'),
  //     'jawaban5' => $this->request->getPost('materi'),
  //     'jawaban6' => $this->request->getPost('pendukung'),
  //     'jawaban7' => $this->request->getPost('hambatan'),
  //     'jawaban8' => $this->request->getPost('skill'),
  //     'jawaban9' => $this->request->getPost('saran')
  //   ];
  //   $this->kuesioner->editKuesioner2($data2, $id);

  //   session()->setFlashdata('pesan', 'Berhasil mengubah kuesioner');
  //   return redirect()->to('/kuesioneralumni/' . $id);
  // }

  //--------------------------------------------------------------------

}