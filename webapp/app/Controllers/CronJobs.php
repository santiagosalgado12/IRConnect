<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\Esp32;
use App\Models\Dispositivos;
use App\Models\Acceso_usuarios;
use App\Models\Manejador;
use App\Models\Usuarios;
use App\Models\Eventos;


class CronJobs extends BaseController
{
    protected $esp32;
    protected $dispositivos;
    protected $manejador;
    protected $usuarios;
    protected $eventos;
    protected $acceso_usuarios;

    public function __construct(){
        #se instancian las clases de los modelos a utilizar
        $this->esp32 = new Esp32();
        $this->dispositivos = new Dispositivos();
        $this->manejador = new Manejador();
        $this->usuarios = new Usuarios();
        $this->eventos = new Eventos();
        $this->acceso_usuarios = new Acceso_usuarios();
    }

    #funcion que borra un usuario de tipo profesor en caso de que expire su tiempo de verificacion
    public function deletestandarduser(){
        $users=$this->usuarios->getUserand(['fecha_creacion <' => date('Y-m-d H:i:s', strtotime('-2 days')),'ID_permiso' => 2, 'hash_contrasena' => null]);
        #se busca un usuario de tipo 2 (profesor), que no haya seteado su contrasena, 
        #y que hayan pasado 2 dias desde su creacion (es la duracion que tiene el codigo para crear su
        #contrasena)

        if($users){
            #si se encuentran usuarios, se iteran
            foreach($users as $user){

                $mails=[];
                #por cada usuario, se guarda en un array el mail del administrador del usuario profesor
                #para notificarlo posteriormente
                $admin=$this->usuarios->getUser(['ID_usuario' => $user['ID_administrador']]);
    
                 if (!in_array($admin[0]['email'], $mails)) {
                    #este if valida que se añada solo 1 vez cada mail, en caso de que 2 o mas
                    #usuarios profesor pertenezcan al mismo administrador
                     $mails[] = $admin[0]['email'];
                 }
                 #finalmente se borra el usuario, esto evita sobrecarga en la bd. Tambien se borran los accesos en caso de que el admin ya le haya asignado
                 $this->acceso_usuarios->deleteaccessbyuser($user['ID_usuario']);
                 $this->usuarios->deleteUser($user['ID_usuario']);
    
            }
    
             for($i=0;$i < count($mails);$i++){
                #por cada mail en el array, se le envia una notificacion diciendo que 1 o mas de sus
                #usuarios creados fueron eliminados. Puede crearlos nuevamente pero si no se vuelven
                #a verificar este cron los va a eliminar
                 \Config\Services::sendEmail($mails[$i],"Eliminación de usuarios creados","<h1>Se ha eliminado 1 o más usuarios creados por usted.</h1> <h3>Esto es debido a que no han creado sus contraseñas luego de 2 días. Puede volver a crearlos, pero serán eliminados nuevamente en caso de no volver a crear su contraseña</h3>");
             }
        }else{
            return false;
        }
   }

   public function deleteEsp32(){
    #esta funcion borra los registros de dispositivos que no fueron conectados a wi fi
    #cuando el usuario completa el formulario de vinculacion, se inserta el registro pero
    #con su ip nula, lo que indica que no fue conectada
    $device =$this->esp32->getEsp32and([ 'ultima_conexion <'=>date('Y-m-d H:i:s', strtotime("-1 hour")),'direccion_ip'=> null]);

    #se buscan registros que cumplan esta condicion y que haya pasado mas de 1 hora desde que se 
    #registro, (se guarda en el campo ultima conexion)

    if ($device) {
        // echo "<br>bien";
        foreach ($device as $dispositivo) {
            $mails=[];
    
                $admin=$this->usuarios->getUser(['ID_usuario' => $dispositivo['ID_administrador']]);
    
                 if (!in_array($admin[0]['email'], $mails)) {
                     $mails[] = $admin[0]['email'];
                 }
            $this->esp32->DeleteEsp32($dispositivo['ID_dispositivo']);
        }
        for($i=0;$i < count($mails);$i++){
            \Config\Services::sendEmail($mails[$i],"Eliminación de IRConnect","<h1>Se ha eliminado 1 o más IRConnects vinculados por usted. Puede volver a completar el formulario de registro</h1> <h3>Esto es debido a que no se han conectado a wifi luego de 1 hora</h3>");
        }
        #por cada registro, se hace el mismo procedimiento que los usuarios profesor, se 
        #notifica por mail al administrador que dio de alta el registro
        }else{
    // echo 'Mal';
    return false;
        }
}
    /*public function deleteEsp32(){
    $minutos = 30;
        $expiradas = $this->esp32->getNoVinculadasExpiradas($minutos);

        foreach ($expiradas as $esp) {
            $usuario = $this->usuarios->find($esp['ID_administrador']);

            if ($usuario) {
                $this->notificarExpiracion($usuario['email'], $usuario['nombre_usuario'], $esp['codigo']);
            }

            $this->esp32->DeleteEsp32($esp['ID_dispositivo']);
            log_message('info', "IRConnect eliminado por expiración: ID={$esp['ID_dispositivo']}, código={$esp['codigo']}");
        }

        echo "Limpieza completada.\n";
    }
    */

   public function insertprogrammedsignal($eventId){
    #cuando los usuarios creen un evento para enviar señales automaticas, se ejecutara esta funcion
    #cada cron esta configurado para pasar como parametro el id del evento en la base de datos
        $eventdata = $this->eventos->getEvent(['ID_evento' => $eventId]);
        #se busca el evento por id, y si se encuentra:
        if($eventdata){
            #se buscan los datos de la señal por su llave primaria almacenada en el evento. Cada evento
            #puede ejecutar solo 1 señal
            $signaldata = $this->dispositivos->getSignalbypk($eventdata[0]['ID_senal']);
            #tambien se buscan los datos de la esp
            $espdata = $this->esp32->getEsp32and(['ID_dispositivo' => $eventdata[0]['ID_esp']]);
            

            if($signaldata && $espdata[0]['estado']==1){
                #si se encuentra la señal y la esp esta enchufada, (tiene estado 1):
                $protocolo = $this->dispositivos->getProtocolbySignal($signaldata[0]['ID_senal']);

                $solicitud = $this->manejador->insertActionQuery(1,$espdata[0]['codigo']);
                
                #se obtiene el nombre del protocolo a partir del id del registro de la señal y
                #se inserta una nueva solicitud en la bd manejador

                $this->manejador->insertDataQuery('hexadecimal',$solicitud,$signaldata[0]['codigo']);
                $this->manejador->insertDataQuery('protocolo',$solicitud,$protocolo[0]['nombre']);
                $this->manejador->insertDataQuery('bits',$solicitud,$signaldata[0]['bits']);
                $this->manejador->insertDataQuery('led',$solicitud,$eventdata[0]['led']);
                #se insertan en los datos_solicitud, el codigo hexadecimal, el protocolo, los bits (extraidos de la tabla señales)
                #tambien el led, que se extrae del dispositivo y se encuentra en el registro del evento
                $this->manejador->updateQuerybyId($solicitud);
                #se setea la solicitud como realizada, esto en realidad funciona como un indicador de que
                #la solicitud forma parte de un cron, lo que hace que despues la esp pueda eliminarlo, a diferencia de las solicitudes del usuario que se borran cuando este sale de la vista
                 
                echo 'El evento ha sido ejecutado correctamente.';



            }else{
                echo false;
            }
            if($eventdata[0]['tipo'] == 'unica_vez'){

                #en caso de que sea de tipo unica vez, el cron job debe eliminarse para no saturar el servidor

                // Obtener la fecha y hora del evento
                 $fecha = $eventdata[0]['fecha']; // Ejemplo: '2025-04-24'
                 $hora = $eventdata[0]['hora'];   // Ejemplo: '20:14'

                // Usar las funciones de formateo para obtener los valores necesarios
                 $processedDate = \Config\Services::setUniquedateforcron($fecha); // Devuelve ['dia' => '24', 'mes' => '04']
                 $processedTime = \Config\Services::setTimeforcron($hora);       // Devuelve ['minuto' => '14', 'hora' => '20']
                #en esta funcion, se formatean los datos para que sean aptos para el servidor, en lugar
                #de usar una fecha como 2025-01-01, se guarda 01, 01
                #en lugar de usar una hora como 10:15, se guarda como 10, 15
               // Construir el comando completo del cron job
                 $cronJobToDelete = $processedTime['minuto'] . ' ' .
                 $processedTime['hora'] . ' ' .
                 $processedDate['dia'] . ' ' .
                 $processedDate['mes'] . ' * ' .
                 '/usr/bin/php /var/www/html/irconnect/public/index.php cronjobs insertProgrammedsignal ' . $eventId . " >> /root/ir_cron.log 2>&1";
                #se construye el comando que elimina el cron con los datos exactos de fecha y hora y el 
                #id del evento
// // Llamar a la función para eliminar el cron job y ejecutar el comando
                 if (\Config\Services::deleteCronJob($cronJobToDelete)) {
                     error_log('Cron job eliminado correctamente: ' . $cronJobToDelete);
                 } else {
                     error_log('Error al eliminar el cron job: ' . $cronJobToDelete);
                 }

                $this->eventos->deleteEvent($eventId);
                 #se elimina tambien de la bd
                echo 'Evento eliminado correctamente';
            
            }
    
            
        }else{
            return false;
        }
        
    }

    public function deleteDeviceswithoutSignal(){
        //Funcion para eliminar dispositivos que no tienen señales grabadas
        $data = $this->dispositivos->getDeviceswithoutsignals();

        #el modelo busca dispositivos que luego de 24 horas de ser registrados no tienen ninguna señal 
        #grabada. La misma funcion es la que se encarga de retornar los datos a este controlador, y luego borrarlos de la bd

        if(!empty($data)){

            foreach ($data as $d){

                \Config\Services::sendEmail($d['email'],'Uno o más dispositivos que has vinculado a un IRConnect han sido eliminados','Esto es debido a que no has grabado ninguna señal en 24 horas. Puedes volver a registrar el dispositivo, pero puede volver a eliminarse');

                return 'Dispositivos sin señales eliminados.';

            }
            #se hace el procedimiento de notificacion por mail a los admins que lo dieron de alta
        }else{

            return false;

        }
        
    }

    public function deleteadminusers(){
            #funcion que elimina usuarios administrador que no se verificaron
            $users = $this->usuarios->getUserand(['ID_permiso' => 1, 'verificado' => 0, 'fecha_creacion <' => date('Y-m-d H:i:s', strtotime('-2 days'))]);

            #busca usuarios administradores, que no esten verificados y que se hayan creado hace 2 dias
            #(en este caso, no es relevante que el codigo expire porque se genera uno automaticamente cada vez que el usuario quiere loguearse, ya que el controlador detecta que no esta verificado, sin embargo, para evitar saturacion de usuarios en la bd, se tienen que eliminar de igual forma)
    
            if($users){
    
                foreach($users as $user){
                    
                    \Config\Services::sendEmail($user['email'],'Se ha eliminado su usuario','Esto es debido a que no ha verificado su cuenta. Puedes volver a registrarte, pero puede volver a eliminarse');

                    $this->usuarios->deleteUser($user['ID_usuario']);
    
                }
                #se itera sobre los usuarios encontrados y se los notifica por mail de que su usuario fue eliminado. No hay problema con esto ya que si no estan verificados es imposible que hayan, por ejemplo, registrado nuevas esp o nuevos usuarios profesor.
                return 'Usuarios administradores eliminados.';
    
            }else{
    
                return false;
    
            }
    }

 }


