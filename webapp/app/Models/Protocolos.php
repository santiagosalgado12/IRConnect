<?php

namespace App\Models;


use CodeIgniter\Model;

class Protocolos extends Model{


    public function getIDprotocol($name){

        $tabla = $this->db->table('protocolos');

        $tabla->where('nombre',$name);

        $result=$tabla->get()->getResultArray();

        if(!empty($result)){
            
            return $result;

        }else{

            return 0;

        }

    }

}