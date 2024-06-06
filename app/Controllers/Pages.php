<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Home | Kouseki'
        ];
        return view('pages/home', $data);
    }

    public function about(): string
    {
        $data = [
            'title' => 'About | Kouseki'
        ];
        return view('pages/about', $data);
    }

    public function contact(): string
    {
        $data = [
            'title' => 'Contact | Kouseki',
            'alamat' => [
                [
                    'tipe' => 'rumah',
                    'alamat' => 'Jalan Jendral Sudirman',
                    'kota' => 'Jakarta'
                ],
                [
                    'tipe' => 'kantor',
                    'alamat' => 'Jalan Mawar Merah',
                    'kota' => 'Bandung'
                ]
            ]
        ];
        return view('pages/contact', $data);
    }

}
