<?php

namespace App\Models;

use CodeIgniter\Model;

class Esp32 extends Model
{


    public function getEsp32byUser($id)
    {
     
        $builder = $this->db->table('disp_esp32 e');
        $builder->select('u.nombre_usuario, e.ubicacion, e.ID_dispositivo, e.direccion_ip, e.codigo');
        $builder->join('dispositivos d', 'd.ID_esp32 = e.ID_dispositivo');
        $builder->join('acceso_usuarios au', 'au.ID_dispositivo = d.ID_dispositivo');
        $builder->join('usuarios u', 'u.ID_usuario = au.ID_usuario');
        $builder->where(['u.ID_usuario'=> $id]);
        $builder->where("direccion_ip IS NOT NULL", null, false); // Use raw SQL condition
        $builder->groupBy('u.ID_usuario, e.ID_dispositivo');
    
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getDevicesbyEsp($esp,$user){

        $table = $this->db->table('dispositivos d');
        $table->select('d.nombre,d.ID_tipo,d.ID_dispositivo, d.led');
        $table->join('disp_esp32 e','e.ID_dispositivo=d.ID_esp32');
        $table->join('acceso_usuarios au','au.ID_dispositivo=d.ID_dispositivo');
        $table->join('usuarios u','u.ID_usuario=au.ID_usuario');
        $table->where(['e.ID_dispositivo'=> $esp,'u.ID_usuario'=> $user]);

        return $table->get()->getResultArray();

    }

    public function insertEsp($ubicacion,$id,$code,$ip=null){

        $table=$this->db->table(tableName: "disp_esp32");

        $table->insert([
            "direccion_ip" => $ip,
            "estado" => 1,
            "ubicacion" => $ubicacion,
            'ID_administrador' => $id,
            'codigo' => $code

        ]);

        return $this->db->insertID();

    }

    public function getEsp32byAdmin($id){
        $table = $this->db->table('disp_esp32');

        $table->select('*');

        $table->where(["ID_administrador" => $id]);
        $table->where("direccion_ip IS NOT NULL", null, false); // Use raw SQL condition


        return $table->get()->getResultArray();
    }

    public function getEsp32byCode($code){
        $table = $this->db->table('disp_esp32');

        $table->select('*');

        $table->where(["codigo" => $code]);

        return $table->get()->getResultArray();
    }

    public function updateEsp32($data,$condition){

        $table = $this->db->table('disp_esp32');

        $table->where($condition);

        $table->update($data);

        if($this->db->affectedRows() > 0){
            return true;
        }else{
            return false;
        }

    }

    public function getEsp32($id){
        $table = $this->db->table('disp_esp32');

        $table->select('*');

        $table->where(["ID_dispositivo" => $id]);

        return $table->get()->getResultArray();
    }

    public function arrayByDevices($id){

        $esp=$this->getEsp32byAdmin($id);
        $r=[];
        foreach($esp as $e){
            $r[$e['ubicacion']]=$this->db->table('dispositivos')->select('ID_dispositivo, nombre')->where(['ID_esp32'=>$e['ID_dispositivo']])->get()->getResultArray();
        }

        return $r;

    }

    public function DeleteEsp32($id){
        $table=$this->db->table("disp_esp32");

        $table->where(["ID_dispositivo" => $id])->delete();

        if($this->db->affectedRows() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function getEsp32and($data){
        $esp32=$this->db->table("disp_esp32");

        $esp32->where($data);

        return $esp32->get()->getResultArray();
    }
    public function getNoVinculadasExpiradas($minutos = 30)
    {
        $builder = $this->db->table('disp_esp32 d');
        $builder->select('d.ID_dispositivo, d.codigo, d.ubicacion, d.ID_administrador');
        $builder->where('d.estado', 1);
        $builder->where('d.ultima_conexion <', date('Y-m-d H:i:s', strtotime("-{$minutos} minutes")));
        $builder->whereNotIn('d.ID_dispositivo', function($sub) {
            return $sub->select('ID_esp32')->from('dispositivos');
        });
    
        return $builder->get()->getResultArray();
    }

    public function verifycurrentvinculation($id)
    {
    $builder = $this->db->table('disp_esp32');
    $builder->where('ID_administrador', $id);
    $builder->where('direccion_ip IS NULL', null, false);
    $builder->where('ultima_conexion >=', date('Y-m-d H:i:s', strtotime('-1 hour')));
    return $builder->get()->getResultArray();
    }
}
    
