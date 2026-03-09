<?php

namespace App\Models;

use CodeIgniter\Model;

class Login_attemps extends Model{

    public function insertLoginattemp($exitoso,$hash,$id=null){

        $table=$this->db->table("login_attemps");
        $data=[
            "ID_usuario"=>$id,
            "exitoso"=>$exitoso,
            "dispositivo"=> $hash
        ];

        if($id==null){
            $data["ID_usuario"]=null;
        }

        $table->insert($data);

 
    }
  
    public function verifyDevicetoblock($hash){
       $table = $this->db->table("login_attemps");
        $table->where('dispositivo', $hash);
        $table->where('exitoso', 0);
        $table->where('fecha >=', date('Y-m-d H:i:s', strtotime('-10 minutes')));

        $data = $table->get()->getResultArray();
       if (count($data) >= 5) {
            return true; 
        }

        return false;
    }

public function blockDevice($hash){
        $table = $this->db->table("disp_bloqueados");
        $data = [
           'dispositivo' => $hash
        ];
        $table->insert($data);
   }

   public function verifyBlockedDevice($hash){
        $table = $this->db->table("disp_bloqueados");
        $table->where('dispositivo', $hash);
       $data = $table->get()->getResultArray();
       if (count($data) > 0) {
            return true; 
        }
        return false;
    }

}