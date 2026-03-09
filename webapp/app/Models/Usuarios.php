<?php

namespace App\Models;

use CodeIgniter\Model;

class Usuarios extends Model{
    public function getUser($data){

        $usuario=$this->db->table("usuarios");

        $usuario->orWhere($data);

        return $usuario->get()->getResultArray();

    }

    public function getUserand($data){
        $usuario=$this->db->table("usuarios");

        $usuario->where($data);

        return $usuario->get()->getResultArray();
    }

    public function insertUser($data){

        $this->db->table("usuarios")->insert($data);

        return $this->db->insertID();
        
    }

    public function verifyUser($id){

        $table=$this->db->table("usuarios");

        $table->where(["ID_usuario" => $id]);

        $table->update(["verificado" => 1]);

    }

    public function updatePw($pw,$id){

        $table=$this->db->table("usuarios");

        $table->where(["ID_usuario" => $id])->update(["hash_contrasena" => $pw]);

        if($this->db->affectedRows() > 0){
            return true;
        }else{
            return false;
        }

    }

    public function getUsersbyAdmin($id){

        $table=$this->db->table("usuarios u");

        $table->select("u.nombre_usuario, u.fecha_creacion, u.email, u.ID_usuario");

        $table->join("usuarios a","u.ID_administrador=a.ID_usuario");

        $table->where("a.ID_usuario=$id");

        return $table->get()->getResultArray();

    }

    public function updateData($data,$condition){
        $table=$this->db->table("usuarios");

        $table->where($condition)->update($data);

        if($this->db->affectedRows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function deleteUser($id){
        $table=$this->db->table("usuarios");

        $table->where(["ID_usuario" => $id])->delete();

        if($this->db->affectedRows() > 0){
            return true;
        }else{
            return false;
        }
    }

   
}