<?php

namespace App\Models;


use CodeIgniter\Model;

class Acceso_usuarios extends Model{

    public function insertAccess($user_id,$device_id){
        
        $tabla=$this->db->table('acceso_usuarios');

        $data=array(
            'ID_usuario' => $user_id,
            'ID_dispositivo' => $device_id
        );

        $tabla->insert($data);

        return $this->db->insertID();

    }

    public function deleteAccess($user_id,$device_id){
        $tabla=$this->db->table('acceso_usuarios');

        $tabla->where('ID_usuario',$user_id);

        $tabla->where('ID_dispositivo',$device_id);

        $tabla->delete();
    }

    public function getpermissionid($user){
        $tabla=$this->db->table('acceso_usuarios');

        $tabla->select('Id_usuario, ID_dispositivo');

        $tabla->where('ID_usuario',$user);

        return $tabla->get()->getResultArray();
    }

    public function getpermission($user,$device){
        $tabla=$this->db->table('acceso_usuarios');

        $tabla->where('ID_usuario',$user);

        $tabla->where('ID_dispositivo',$device);

        return $tabla->get()->getResultArray();
    }

    public function deleteaccessbydevice($device){
        $tabla=$this->db->table('acceso_usuarios');

        $tabla->where('ID_dispositivo',$device);

        $tabla->delete();
    }

    public function deleteaccessbyuser($user){

        $tabla=$this->db->table('acceso_usuarios');

        $tabla->where('ID_usuario',$user);

        $tabla->delete();

    }

}