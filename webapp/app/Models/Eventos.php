<?php

namespace App\Models;

use CodeIgniter\Model;

class Eventos extends Model{


    public function insertEvent($esp,$signal,$type,$time,$led,$days=null,$date=null){

        $tabla=$this->db->table('eventos');

        $data=[
            'ID_esp' => $esp,
            'ID_senal' => $signal,
            'tipo' => $type,
            'hora' => $time,
            'dias'=> $days,
            'fecha' => $date,
            'led' => $led
        ];

        $tabla->insert($data);

        return $this->db->insertID();


    }

    public function getEvent($data){

        $tabla=$this->db->table('eventos');

        $tabla->where($data);

        return $tabla->get()->getResultArray();

    }

    public function viewEvents($id_signal){

        $tabla = $this->db->table('senalesir s');

        $tabla->where('ID_senal',$id_signal);

        return $tabla->get()->getResultArray();

    }

    public function viewEventssingal($id_signal){

        $tabla = $this->db->table('senalesir s');

        $tabla->select(
            'f.nombre AS funcion , d.nombre AS dispositivo'
        );

        $tabla->join('dispositivos d', 'd.ID_dispositivo = s.ID_dispositivo');
        

        $tabla->join('funciones f','f.ID_funcion=s.ID_funcion');

        $tabla->where('s.ID_senal',$id_signal);

        return $tabla->get()->getResultArray();

    }

    public function viewEventsair($id_signal){
        $tabla = $this->db->table('senalesir s');

        $tabla->select(
    '   c.temperatura, 
                c.modo, 
                c.fanspeed, 
                c.swing,  
                d.nombre AS dispositivo'
        );

        $tabla->join('dispositivos d', 'd.ID_dispositivo = s.ID_dispositivo');
        

        $tabla->join('configuraciones c','c.ID_configuracion=s.ID_configuracion');

        $tabla->where('s.ID_senal',$id_signal);

        return $tabla->get()->getResultArray();
    }

    public function deleteEvent($id){

        $tabla=$this->db->table('eventos');

        $tabla->where('ID_evento',$id);

        $tabla->delete();

        if($this->db->affectedRows()>0){
            return true;
        }else{
            return false;
        }

    }

    public function checkTimeConflict($espId, $hora, $dias) {
    $tabla = $this->db->table('eventos');
    $tabla->where('ID_esp', $espId);
    $tabla->where('hora', $hora);
    
    // Convertir a array si es necesario
    $diasArray = is_array($dias) ? $dias : [$dias];
    
    // Buscar cualquier evento que contenga alguno de los días
    $tabla->groupStart();
    foreach ($diasArray as $dia) {
        $tabla->orLike('dias', $dia);
    }
    $tabla->groupEnd();
    
    $result = $tabla->get()->getResultArray();
    return !empty($result) ? $result[0] : false;
}

}