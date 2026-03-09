<?php

namespace App\Models;


use CodeIgniter\Model;

class Ordenes extends Model{

    public function insertOrder($data){
        $builder = $this->db->table('ordenes');
        $builder->insert($data);
    }

}