<?php

namespace App\Models;

 
use CodeIgniter\Model;

class Dispositivos extends Model{

    public function insertDevice($name,$type,$esp_id,$led){
        $tabla=$this->db->table('dispositivos');
        $data = array(
            "nombre"=>$name,
            "ID_tipo"=>$type,
            "ID_esp32"=>$esp_id,
            "led"=>$led
        );
        $tabla->insert($data);

        return $this->db->insertID();
    }

    public function user_has_permission($device,$user){

        $tabla=$this->db->table('dispositivos d');

        $tabla->join('acceso_usuarios au','au.ID_dispositivo=d.ID_dispositivo');

        $tabla->join('usuarios u','u.ID_usuario=au.ID_usuario');

        $tabla->where(['d.ID_dispositivo'=>$device,'u.ID_usuario'=>$user]);

        return $tabla->get()->getResultArray();

    }

    public function updateDevice($name,$type,$led,$id){

        $tabla=$this->db->table('dispositivos');

        $data = array(
            "nombre"=>$name,
            "ID_tipo"=>$type,
            "led"=>$led
        );

        $tabla->where('ID_dispositivo',$id);

        $tabla->update($data);

    }

    public function deleteDevice($id){

        $tabla=$this->db->table('dispositivos');

        $tabla->where('ID_dispositivo',$id);

        $tabla->delete();

        $tabla2=$this->db->table('acceso_usuarios');

        $tabla2->where('ID_dispositivo',$id);

        $tabla2->delete();

    }

    public function insertSignal($signal,$device,$function,$protocol,$bits,$config){
        $tabla=$this->db->table('senalesir');
        $data = array(
            "codigo"=>$signal,
            "ID_dispositivo"=>$device,
            "ID_funcion"=>$function,
            "ID_protocolo"=>$protocol,
            "bits"=>$bits,
            "ID_configuracion"=>$config
        );

        $tabla->insert($data);
    }


        

    public function updateSignal($signal,$device,$function,$protocol,$bits,$config){
        $tabla=$this->db->table('senalesir');
        $data = array(
            "codigo"=>$signal,
            "ID_dispositivo"=>$device,
            "ID_funcion"=>$function,
            "ID_protocolo"=>$protocol,
            "bits"=>$bits,
            "ID_configuracion"=>$config
        );

        $tabla->where('ID_dispositivo',$device);
        $tabla->where('ID_funcion',$function);

        $tabla->update($data);

    }


    public function updateAirsignal($signal,$device,$function,$protocol,$bits,$config){
        $tabla=$this->db->table('senalesir');
        $data = array(
            "codigo"=>$signal,
            "ID_dispositivo"=>$device,
            "ID_funcion"=>$function,
            "ID_protocolo"=>$protocol,
            "bits"=>$bits,
            "ID_configuracion"=>$config
        );

        $tabla->where('ID_dispositivo',$device);
        $tabla->where('ID_configuracion',$config);

        $tabla->update($data);

    }

    public function verifyAirsignal($device,$config){

        $tabla=$this->db->table('senalesir');

        $tabla->where('ID_dispositivo',$device);

        $tabla->where('ID_configuracion',$config);

        return $tabla->get()->getResultArray();

    }

    public function getSignal($disp,$func){
        $tabla=$this->db->table('senalesir');
        $tabla->where('ID_dispositivo',$disp);
        $tabla->where('ID_funcion',$func);
        return $tabla->get()->getResultArray();
    }

    public function getAirsginal($disp,$config){

        $tabla=$this->db->table('senalesir');
        $tabla->where('ID_dispositivo',$disp);
        $tabla->where('ID_configuracion',$config);
        return $tabla->get()->getResultArray();

    }


    public function deleteSignalsbyDevice($id){
        $tabla=$this->db->table('senalesir');
        $tabla->where('ID_dispositivo',$id);
        $tabla->delete();
    }

    public function getConfigbyDevice($id){

        $tabla=$this->db->table('configuraciones c');

        $tabla->select('c.temperatura,c.swing,c.modo,c.fanspeed,s.ID_senal,c.ID_configuracion');

        $tabla->join('senalesir s','s.ID_configuracion=c.ID_configuracion');

        $tabla->join('dispositivos d','d.ID_dispositivo=s.ID_dispositivo');

        $tabla->where(['d.ID_dispositivo' => $id]);

        return $tabla->get()->getResultArray();

    }

    public function verifyConfig($temp,$modo,$swing,$fan){
        $tabla = $this->db->table('configuraciones');

        $tabla->where('temperatura',$temp);

        $tabla->where('swing',$swing);

        $tabla->where('modo',$modo);

        $tabla->where('fanspeed',$fan);

        return $tabla->get()->getResultArray();


    }

    public function insertConfig($temp,$modo,$swing,$fan){

        $tabla = $this->db->table('configuraciones');

        $data=array(

            'temperatura' =>$temp,
            'swing'=>$swing,
            'modo'=>$modo,
            'fanspeed'=>$fan

        );

        $tabla->insert($data);

        return $this->db->insertID();

    }

    public function deleteConfig($id){
        $tabla=$this->db->table('senalesir');

        $tabla->where('ID_senal',$id);

        $tabla->delete();
    }

    public function getProtocolbySignal($id){

        $tabla = $this->db->table('protocolos p');

        $tabla->select('p.nombre');

        $tabla->join('senalesir s','s.ID_protocolo=p.ID_protocolo');

        $tabla->where('s.ID_senal',$id);

        return $tabla->get()->getResultArray();

    }

    public function getSignalsforcron($id){

        $tabla=$this->db->table('senalesir s');

        $tabla->select([ 
            'f.nombre',
            's.codigo',
            's.ID_protocolo',
            's.bits',
            's.ID_senal'
        ]);

        $tabla->join('funciones f','f.ID_funcion=s.ID_funcion');

        $tabla->where('s.ID_dispositivo',$id);

        return $tabla->get()->getResultArray();

    }

    public function getAirforcron($id){

        $tabla= $this->db->table('senalesir s');

        $tabla->select([
            'c.temperatura',
            'c.modo',
            'c.swing',
            'c.fanspeed',
            's.codigo',
            's.ID_protocolo',
            's.bits',
            's.ID_senal',
            's.ID_configuracion'

        ]);

        $tabla->join('configuraciones c', 's.ID_configuracion=c.ID_configuracion', 'left');

        $tabla->where(['s.ID_dispositivo'=>$id]);

        $tabla->where('s.codigo IS NOT NULL');

        return $tabla->get()->getResultArray();

    }

    public function getSignalbypk($id){
        $tabla=$this->db->table('senalesir s');

        $tabla->where('ID_senal',$id);

        return $tabla->get()->getResultArray();

    }

    public function getDeviceswithoutsignals() {
        $tabla = $this->db->table('dispositivos d');
        $tabla->select('d.ID_dispositivo, u.email');
        $tabla->join('senalesir s', 'd.ID_dispositivo = s.ID_dispositivo', 'left');
        $tabla->join('disp_esp32 e', 'd.ID_esp32 = e.ID_dispositivo');
        $tabla->join('usuarios u', 'u.ID_usuario = e.ID_administrador');
        $tabla->where('s.ID_dispositivo IS NULL');
        $tabla->where('d.fecha_creacion <=', date('Y-m-d H:i:s', strtotime('-24 hours'))); // Condición de 24 horas

        
        // Obtener los datos
        $data = $tabla->get()->getResultArray();
    
        // Eliminar los registros encontrados
        if (!empty($data)) {
            $ids = array_column($data, 'ID_dispositivo'); // Extraer los IDs de los dispositivos
            $this->db->table('dispositivos')->whereIn('ID_dispositivo', $ids)->delete();
        }
    
        return $data;
    }

    public function getDevicesand($data){

        $tabla = $this->db->table('dispositivos d');

        $tabla->where($data);

        return $tabla->get()->getResultArray();


    }

    public function verifyDevicetoUpdate($data,$id){

        $tabla = $this->db->table('dispositivos d');

        $tabla->where($data);
        $tabla->where('ID_dispositivo !=',$id);

        return $tabla->get()->getResultArray();

    }

    public function getAirsignalforvoice($data){

        $tabla = $this->db
            ->table('configuraciones c')
            ->join('senalesir s', 'c.ID_configuracion = s.ID_configuracion')
            ->where($data) // acá pasás array asociativo con las condiciones
            ->select('s.ID_senal, s.codigo, s.ID_protocolo, s.bits, s.ID_dispositivo, s.ID_funcion, s.ID_configuracion')
            ->limit(1)
            ->get()->getResultArray();

        return $tabla;


    }



}