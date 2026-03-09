<?php


namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\Esp32;
use App\Models\Login_attemps;



class Home extends BaseController
{

    public function index(): string
    {
         $session = session();

         if($session->get("verificado")){ #VERIFICA SI EL USUARIO ESTA VERIFICADO Y HA INICIADO SESIÓN
             $obj=new Esp32(); #INSTANCIA EL MODELO DE ESP32 PARA BUSCAR LOS DISPOSITIVOS A LOS QUE TIENE ACCESO EL USUARIO
             if($session->get('tipo')==2){
                 $datos=$obj->getEsp32byUser($session->get("user_id"));
             }else{
                 $datos=$obj->getEsp32byAdmin($session->get("user_id"));
             }

                 return view("inicio", ["datos" => $datos]);
             
         }else{
             return view('landing_page/index'); #EN CASO DE QUE EL USUARIO NO HAYA INICIADO SESION, LO ENVIA A LA VISTA DE INICIO
         }

    }

    public function inicio(){
        return view("inicio");
    }

    public function viewPrueba(){
        return view("prueba");
    }

    public function viewlogin(){
  
        if(session()->get('verificado')){
            return redirect()->to(base_url());
        }else{
            $log_att = new Login_attemps();
            $ip=$this->request->getIPAddress();
            $userAgent=$this->request->getServer('HTTP_USER_AGENT'); #user agent como string para obtener el mismo hash que en el controlador del login

            $device_identificator = hash('md5', $ip . '|' . $userAgent );

            $validator = $log_att->verifyBlockedDevice($device_identificator);

            if($validator){
                return view('login_blocked');
            }

            return view('login');
        }

    }

    public function viewcomprar(){
        return view('compra_session');
    }

    public function pruebalogin(){
        return view('login1');
    }

    public function viewintructions(){
        return view('signals_intructions');
    }
    public function viewAirintructions(){
        return view('air_intructions');
    }

    public function formcontact(){

        #formulario de contacto de la landing page de inicio

        $nombre=$this->request->getPost('name');

        $email=$this->request->getPost('email');

        $asunto=$this->request->getPost('subject');

        $mensaje=$this->request->getPost('message');

        #recibe por post lo que envia el usuario

        \Config\Services::sendEmail('irconnect33@gmail.com','Consulta nueva de '.$email,'
        Nombre: '.$nombre.'
        <br>Email: '.$email.'
        <br>Asunto: '.$asunto.'
        <br>Mensaje: '.$mensaje);

        #la empresa se envia un mail a ella misma, notificando que un nuevo correo electronico realizo una consulta

        \Config\Services::sendEmail($email,'Consulta enviada correctamente','<h1>Hola '.$nombre.'! Tu consulta fue enviada correctamente. En instantes, un miembro de nuestro soporte le enviará un correo electrónico respondiendo su mensaje</h1>');

        #luego se le notifica al usuario que su mensaje fue enviado correctamente
        return $this->response->setStatusCode(200)->setBody('OK');
       


    }

    // public function expo(){
    //     return view('landing_page');
    // }
    // public function actualizar(){
    //     $estado = $this->request->getGet('estado');
    //     if ($estado) {
    //         // Verifica el valor del estado y actualiza el archivo
    //         $estadoAnterior = file_get_contents(WRITEPATH . 'semaforo_estado.txt');
            
    //         // Guarda el nuevo estado solo si es diferente
    //         if ($estado !== $estadoAnterior) {
    //             file_put_contents(WRITEPATH . 'semaforo_estado.txt', $estado);
    //         }
            
    //         return $this->response->setStatusCode(200);
    //     }
    // }

    // public function getEstado(){
    //     $estado = file_get_contents(WRITEPATH . 'semaforo_estado.txt');
    //     return $this->response->setJSON(['estado' => $estado]);
    // }

    // public function testcronjob(){
    //     if (php_sapi_name() !== 'cli') {
    //         echo "Acceso denegado: solo CLI";
    //         return;
    //     }

    //     $logMessage = "[" . date('Y-m-d H:i:s') . "] El cron se ejecutó correctamente.\n";
    //     $filePath = WRITEPATH . 'logs/cron_test.log';
    //     echo "prueba ruta cron job";
    //     file_put_contents($filePath, $logMessage, FILE_APPEND);

    //     echo "Log escrito en: $filePath";
    // }
//COSAS VIEJAS, DE LA EXPO 2024
    
}
