<?php

namespace App\Models;

use CodeIgniter\Model;

class Verificacion extends Model{


    public function insertCode($user_id,$codigo,$tipo){

        $data = [
            'ID_usuario' => $user_id,
            'codigo' => $codigo,
            'tipo' => $tipo,
            'usado' => 0
        ];

        $table=$this->db->table("codigos_verificacion");
        $table->insert($data);

        return $this->db->insertID();

    }

    public function getCode($data){

        $table=$this->db->table("codigos_verificacion");

        $table->where($data);

        return $table->get()->getResultArray();

    }

    public function updateCode($data){

        $table=$this->db->table("codigos_verificacion");

        $table->where($data);

        $set=[
            "usado" => 1
        ];

        return $table->update($set);

    }

}