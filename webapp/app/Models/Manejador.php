<?php

namespace App\Models;


use CodeIgniter\Model;

class Manejador extends Model{

    public function __construct(){
        $this->db = \Config\Database::connect('handle');
    }

    public function insertActionQuery($action,$esp){
        $tabla=$this->db->table('solicitudes');

        $tabla->insert(['ID_accion'=>$action,'codigo_esp32'=>$esp,'estado'=>0]);

        if($this->db->affectedRows()>0){
            return $this->db->insertID();
        }else{
            return false;
        }

    }

    public function updateActionQuery($esp){
        $tabla=$this->db->table('solicitudes');

        $tabla->where('codigo_esp32',$esp);

        $tabla->update(['estado'=>1]);

        if($this->db->affectedRows()>0){
            return true;
        }else{
            return false;
        }
    }

    public function updateQuerybyId($id){
        $tabla=$this->db->table('solicitudes');

        $tabla->where('ID_solicitud',$id);

        $tabla->update(['estado'=>1]);

        if($this->db->affectedRows()>0){
            return true;
        }else{
            return false;
        }
    }

    public function deleteActionQuery($esp){

        $tabla=$this->db->table('solicitudes');

        $tabla->where('codigo_esp32',$esp);

        $tabla->delete();

    }

    public function deleteQuerybyId($id){
        $tabla=$this->db->table('solicitudes');

        $tabla->where('ID_solicitud',$id);

        $tabla->delete();

        if($this->db->affectedRows()>0){
            return true;
        }else{
            return false;
        }
    }

    public function getActionQuery($esp){
        $tabla=$this->db->table('solicitudes');

        $tabla->where('codigo_esp32',$esp);

        $result=$tabla->get()->getResultArray();

        return $result;
    }

    public function insertDataQuery($key,$id,$value=null){

        if($value==null){
            $valor=null;
        }else{
            $valor=$value;
        }

        $tabla=$this->db->table('datos_solicitud');
        
        $tabla->insert(['ID_solicitud'=>$id,'clave'=>$key,'valor'=>$valor]);
    }

    public function getActionData($id){
        $tabla=$this->db->table('datos_solicitud');

        $tabla->where('ID_solicitud',$id);

        $result=$tabla->get()->getResultArray();

        return $result;
    }

    public function updateActionData($id, $data, $clave){
        $tabla=$this->db->table('datos_solicitud');

        $tabla->where('ID_solicitud',$id);

        $tabla->where('clave',$clave);

        $tabla->update(['valor'=>$data]);
    }

    public function deleteActionData($id){
        $tabla=$this->db->table('datos_solicitud');

        $tabla->where('ID_solicitud',$id);

        $tabla->delete();

    }

}