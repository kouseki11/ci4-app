<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class OrangSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Adi',
                'address' => 'Sudirman',
            ],
            [
                'name' => 'Budi',
                'address' => 'Gatot subroto',
            ],
            [
                'name' => 'Adi',
                'address' => 'Sudirman',
            ],
            [
                'name' => 'Adi',
                'address' => 'Sudirman',
            ],
            [
                'name' => 'Adi',
                'address' => 'Sudirman',
            ],
            [
                'name' => 'Adi',
                'address' => 'Sudirman',
            ],
            [
                'name' => 'Adi',
                'address' => 'Sudirman',
            ],
            [
                'name' => 'Adi',
                'address' => 'Sudirman',
            ],
        ];

        // $this->db->query("INSERT INTO orang (name, address) VALUES (:name:, :address:)", $data);
        $this->db->table('orang')->insertBatch($data);
    }
}