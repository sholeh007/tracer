<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PertanyaanModel;
use TCPDF;

class KuesionerAdmin extends BaseController
{
  protected $quest;

  public function __construct()
  {
    $this->quest = new PertanyaanModel();
  }

  public function index()
  {
    if (!session()->username) {
      return redirect()->to('/');
    }

    $data = [
      'title' => 'Halaman Pertanyaan',
      'info' => $this->userModel->getUserLog(),
      'quest' => $this->quest->getQuest()
    ];

    return view('admin/pertanyaan.html', $data);
  }

  public function createPertanyaan()
  {
    $data = [
      'title' => 'form isi pertanyaan',
      'info' => $this->userModel->getUserLog(),
      'validation' => \Config\Services::validation()
    ];

    return view('admin/create-pertanyaan.html', $data);
  }

  public function savePertanyaan()
  {
    // cek validasi
    if (!$this->validate([
      'pertanyaan' => [
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus di isi!'
        ]
      ]
    ])) {
      return redirect()->to('/kuesioner/createpertanyaan')->withInput();
    }

    $this->quest->save([
      'Pertanyaan' => $this->request->getPost('pertanyaan'),
      'isi1' => $this->request->getPost('isi1'),
      'isi2' => $this->request->getPost('isi2'),
      'isi3' => $this->request->getPost('isi3'),
      'isi4' => $this->request->getPost('isi4'),
      'isi5' => $this->request->getPost('isi5')
    ]);

    session()->setFlashdata('pesan', 'Pertanyaan berhasil di tambahkan');
    return redirect()->to('/kuesioner');
  }

  public function editPertanyaan($idpertanyaan)
  {
    if (!session()->username) {
      return redirect()->to('/');
    }

    $data = [
      'title' => 'edit pertanyaan',
      'validation' => \Config\Services::validation(),
      'quest' => $this->quest->getQuest($idpertanyaan),
      'info' => $this->userModel->getUserLog()
    ];

    return view('admin/edit-pertanyaan.html', $data);
  }

  public function saveEditPertanyaan($idpertanyaan)
  {
    // cek validasi
    if (!$this->validate([
      'pertanyaan' => [
        'rules' => 'required',
        'errors' => [
          'required' => '{field} harus di isi'
        ]
      ]
    ])) {
      return redirect()->to('/kuesioner/editpertanyaan/' . $idpertanyaan)->withInput();
    }

    $this->quest->save([
      'idpertanyaan' => $idpertanyaan,
      'Pertanyaan' => $this->request->getPost('pertanyaan'),
      'isi1' => $this->request->getPost('isi1'),
      'isi2' => $this->request->getPost('isi2'),
      'isi3' => $this->request->getPost('isi3'),
      'isi4' => $this->request->getPost('isi4'),
      'isi5' => $this->request->getPost('isi5')
    ]);

    session()->setFlashdata('pesan', 'pertanyaan berhasil di ubah');
    return redirect()->to('/kuesioner');
  }

  public function deletePertanyaan($idpertanyaan)
  {
    $this->quest->delete($idpertanyaan);

    session()->setFlashdata('pesan', 'data berhasil dihapus');
    return redirect()->to('/kuesioner');
  }

  public function hasilKuesioner()
  {
    if (!session()->username) {
      return redirect()->to('/');
    }

    $data = [
      'title' => 'Hasil Kuesioner',
      'info' => $this->userModel->getUserLog(),
      'hasil' => $this->userModel->getAlumni()
    ];

    return view('admin/hasil-kuesioner.html', $data);
  }

  public function exportPdf()
  {
    $data = [
      'title' => 'Hasil',
      'alumni' => $this->quest->getStatusAlumni()
    ];
    $html = view('admin/export-pdf.html', $data);

    // create objek 
    $pdf = new TCPDF('p', PDF_UNIT, 'A4', true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Tracer Study');
    $pdf->SetTitle('Tracer Study Alumni');
    $pdf->SetSubject('Tracer Study Alumni');

    // remove default header/footer
    $pdf->setPrintHeader(false);
    // $pdf->setPrintFooter(false);

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(4, 4, 3);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // add page
    $pdf->AddPage();

    // output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // reset pointer to the last page
    $pdf->lastPage();

    // set jenis file
    $this->response->setContentType('application/pdf');
    //Close and output PDF document
    $pdf->Output('tracer_study.pdf', 'I');
  }

  //--------------------------------------------------------------------

}
