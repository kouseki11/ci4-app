<?php

namespace App\Models;

use CodeIgniter\Model;

class OrangModel extends Model
{
    protected $table = 'orang';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'address'];


    public function search($keyword)
    {
        return $this->table('orang')->like('name', $keyword)->orLike('address', $keyword);
    }
}