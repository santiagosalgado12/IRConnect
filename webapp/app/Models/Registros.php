<?php

namespace App\Models;


use CodeIgniter\Model;

class Registros extends Model{

    public function insertRegistro($user_id,$device_id,$function_id,$config_id){
        $table=$this->db->table('registros_disp');

        $table->insert([
            'id_usuario' =>$user_id,
            'id_dispositivo'=>$device_id,
            'id_funcion'=>$function_id,
            'id_configuracion'=>$config_id
        ]);
    }

    public function getRegistro($id){

        $builder = $this->db->table('registros_disp rd');
        $builder->select('u.nombre_usuario');
        $builder->select('f.nombre AS funcion');
        $builder->select(
    "IF(rd.ID_configuracion = 0, 
                'Off',
               CONCAT('Temp:', c.temperatura, 
               ' Modo:', c.modo, 
               ' Swing:', c.swing, 
               ' Fanspeed:', c.fanspeed)
            ) AS configuracion"
        );
        $builder->select('rd.fecha');
        $builder->select('d.nombre');

        $builder->join('usuarios u', 'u.ID_usuario = rd.id_usuario');
        $builder->join('funciones f', 'f.ID_funcion = rd.id_funcion', 'left');
        $builder->join('configuraciones c', 'c.ID_configuracion = rd.id_configuracion', 'left');
        $builder->join('dispositivos d', 'd.ID_dispositivo = rd.id_dispositivo');

        $builder->where('rd.id_dispositivo', $id);
        $builder->orderBy('rd.fecha', 'DESC');

        // Para ejecutar y obtener resultados:
        $query = $builder->get()->getResultArray();

        return $query;

    }

}
