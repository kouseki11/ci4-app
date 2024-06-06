<?php

namespace App\Controllers;

use App\Models\KomikModel;

class Komik extends BaseController
{
    protected $komikModel;
    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }
    public function index(): string
    {
        $komik = $this->komikModel->findAll();
        $data = [
            'title' => 'Daftar Komik',
            'komik' => $komik
        ];


        return view('komik/index', $data);
    }

    public function detail($slug): string
    {
        $komik = $this->komikModel->getKomik($slug);
        $data = [
            'title' => $komik['title'],
            'komik' => $komik
        ];

        if(empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul komik '.$data['title'].' tidak ditemukan');
        }

        return view('komik/detail', $data);
    }

    public function create() 
    {
        $validation = \Config\Services::validation();
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => $validation
        ];

        return view('komik/create', $data);
    }

    public function save()
    {
        //validation
        if(!$this->validate([
            'title' => [
                'rules' => 'required|is_unique[komik.title]',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} komik sudah terdaftar.'
                ]
            ],
            'cover' => [
                'rules' => 'max_size[cover,1024]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar.',
                    'is_image' => 'Yang anda pilih bukan gambar.',
                    'mime_in' => 'Yang anda pilih bukan gambar.'
                ]
            ]
        
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
            return redirect()->to('/komik/create')->withInput();
        }

        $fileCover = $this->request->getFile('cover');
        if($fileCover->getError() == 4) {
            $fileName = 'default.png';
        } else {
            $fileName = $fileCover->getRandomName();
            $fileCover->move('img', $fileName);
        }

        
        $slug = url_title($this->request->getVar('title'), '-', true);
        $this->komikModel->save([
            'title' => $this->request->getVar('title'),
            'slug' => $slug,
            'writer' => $this->request->getVar('writer'),
            'publisher' => $this->request->getVar('publisher'),
            'cover' => $fileName,
        ]);
        session()->setFlashdata('success', 'Data berhasil ditambahkan.');
        return redirect()->to('/komik');
    }

    public function delete($id)
    {
        $komik = $this->komikModel->find($id);
        if($komik['cover'] != 'default.png') {
            unlink('img/' . $komik['cover']);
        }

        $this->komikModel->delete($id);
        session()->setFlashdata('success', 'Data berhasil dihapus.');
        return redirect()->to('/komik');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Edit Data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];
        return view('komik/edit', $data);
    }

    public function update($id)
    {

        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        if($komikLama['title'] == $this->request->getVar('title')) {
            $rule_title = 'required';
        } else {
            $rule_title = 'required|is_unique[komik.title]';
        }

        //validation
        if(!$this->validate([
            'title' => [
                'rules' => $rule_title,
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} komik sudah terdaftar.'
                ]
            ],
            'cover' => [
                'rules' => 'max_size[cover,1024]|is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar.',
                    'is_image' => 'Yang anda pilih bukan gambar.',
                    'mime_in' => 'Yang anda pilih bukan gambar.'
                ]
            ]
        ])) {
            return redirect()->to('/komik/edit/'.$this->request->getVar('slug'))->withInput();
        }

        $fileCover = $this->request->getFile('cover');

        //cek gambar apakah tetap gambar lama
        if($fileCover->getError() == 4) {
            $fileName = $this->request->getVar('old_cover');
        } else {
            $fileName = $fileCover->getRandomName();
            $fileCover->move('img', $fileName);
            if($this->request->getVar('old_cover') != 'default.png') {
                unlink('img/' . $this->request->getVar('old_cover'));
            }
        }

        
        $slug = url_title($this->request->getVar('title'), '-', true);
        $this->komikModel->save([
            'id' => $id,
            'title' => $this->request->getVar('title'),
            'slug' => $slug,
            'writer' => $this->request->getVar('writer'),
            'publisher' => $this->request->getVar('publisher'),
            'cover' => $fileName,
        ]);
        session()->setFlashdata('success', 'Data berhasil diubah.');
        return redirect()->to('/komik');
    }
}
