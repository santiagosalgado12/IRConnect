<?php

namespace App\Controllers;
#INCLUYE LOS MODELOS NECESARIOS
use CodeIgniter\Controller;

use \App\Models\Manejador;
use \App\Models\Esp32;
use \App\Models\Usuarios;


class Handle extends BaseController{

    public function receiveEsp(){

        #este es el controlador principal que recibe las peticiones de los ESP32. Lo que hace la esp una vez esta conectada a wifi, es enviar una request POST con su codigo identificador (cargado en el script) y con su ip. En caso de que sea la primera vez que se conecta, el usuario debe completar anteriormente el formulario de vinculacion, donde colocará este codigo unico y una ubicacion (que sera el nombre que le aparezca posteriormente en su vista de inicio). 

        $code= $this->request->getPost('code');

        $ip = $this->request->getPost('ipAddress');

        $espmodel = new Esp32();

        $handlemodel = new Manejador();

        $esp=$espmodel->getEsp32byCode($code);

        $user=new Usuarios;

        if(!$esp){
            return false;
        }

        if($esp && $esp[0]['direccion_ip']==null){

            #SI LA IP ES NULA PERO LA ESP ESTA EN LA BD, QUIERE DECIR QUE EL USUARIO HA COMPLETADO EL FORMULARIO DE VINCULACION Y DESEA REALIZAR UNA VINCULACION DE UN NUEVO ESP32
            #ESTE CONTROLADOR VA A ACTUALIZAR EL REGISTRO PARA REGISTRAR LA DIRECCION IP DE LA PLACA, VALIDANDO QUE YA ESTA CONECTADA A INTERNET Y PREPARADA PARA REALIZAR REQUESTS AL SERVIDOR

            $update=$espmodel->updateEsp32(['direccion_ip'=>$ip],['codigo'=>$code]);

            $userdata=$user->getUser(['ID_usuario'=>$esp[0]['ID_administrador']]);

            if($update){
                #se le notifica al usuario que su dispositivo fue vinculado exitosamente
                \Config\Services::sendEmail($userdata[0]['email'],"Dispositivo vinculado exitosamente","<h1>Su dispositivo fue vinculado con exito, vuelve al inicio de la pagina para poder configurarlo a gusto</h1>");
            }else{
                \Config\Services::sendEmail($userdata[0]['email'],"Hubo un error al vincular tu esp","<h1>Ha ocurrido un error. Intente nuevamente</h1>");

                return false;
            }
            #estos mensajes se ven en el serial de arduino, no en la vista del usuario
            return 'Esp32 vinculada con exito';
            
        }else{

            #si la esp ya tiene una ip almacenada, quiere decir que ya fue vinculada por el usuario (y que tiene conexion, porque sino no llegaria la request al servidor). En este caso se hace lo siguiente:
            $update_date=$espmodel->updateEsp32(['estado'=>1],['codigo'=>$code]); 

            #se updatea el estado de la esp a 1, esto permitirá al usuario poder acceder al dispositivo desde la vista de inicio. Tambien se actualiza el campo de ultima_conexion, esto es super necesario ya que el evento de la base de datos se encarga de actualizar el estado a 0 en caso de que hayan pasado mas de 3/5 minutos sin que la esp32 se conecte al servidor. El campo se actualiza automáticamente

            if($esp[0]['direccion_ip']!==$ip){

                $update=$espmodel->updateEsp32(['direccion_ip' => $ip],['codigo' => $code]);
                #en caso de que la ip cambie, seguramente porque el usuario cambio de red, se actualiza en la bd
                return 'Ip cambiada en la bd '.$ip;
            }
            #a partir de aca, se trabaja con el manejador de acciones. En caso de que algun usuario quiera usar la esp, ya sea para grabar o emitir señales, se maneja a partir de aca:
            $action=$handlemodel->getActionQuery($code);

            #primero se busca una accion que tenga el codigo unico de la esp, (recordar que se envia por POST en cada request)

            if(!empty($action)){

                $action_data=$handlemodel->getActionData($action[0]['ID_solicitud']);
                #en caso de que haya una accion, se busca si hay datos asociados a esta accion. En caso de que haya, quiere decir que el usuario entró a una vista de control remoto y presiono una señal para ser emitida o grabada.
                if(!empty($action_data)){

                    $response=array(); #se declara el array vacio para luego llenarlo con los datos necesarios para la respuesta a la esp32

                    if($action[0]['ID_accion']==1 && !empty($action_data[0]['valor'] && !empty($action_data[1]['valor']) && !empty($action_data[2]['valor'])) &&!empty($action_data[3]['valor'])){

                        #este primer if valida que la accion sea de tipo emitir señal (con ID 1), tambien valida que haya datos asociados a la accion (SOLO DEBERIA HABER UNA SEÑAL A LA VEZ, EN CASO DE QUE POR ALGUN ERROR ESTO NO SEA ASI, EL CONTROLADOR VA A IR PASANDO UNA SEÑAL EN CADA REQUEST, PERO NO DEBERIA SUCEDER). los datos de las acciones se guardan como una especie de json, cada registro tiene los campos 'clave' y 'valor'. Para emitir una señal, es necesario tener las claves hexadecimal, protocolo, bits y led con sus respectivos valores

                        $response["accion"] = "emitir_senal";

                        $response["hexadecimal"] = $action_data[0]["valor"];

                        $response["protocolo"]=$action_data[1]['valor'];

                        $response["bits"]=$action_data[2]['valor'];

                        $response['led']=$action_data[3]['valor'];

                        #se almacenan los valores en el array de la respuesta y se la retorna en formato json para ser procesada por la esp32.

                        return json_encode($response);

                    }

                    elseif($action[0]['ID_accion']==2 && $action[0]['estado']==0 && !empty($action_data[0]['clave']) && empty($action_data[0]['valor'])){

                        #en caso de que lo que se quiera hacer es grabar una señal, se valida que este, en primer lugar la accion, pero por otro lado se valida que esten cargados las claves con valores nulos, ya que la esp sera la que envie a otro controlador los datos de la señal una vez sea grabada, y se actualizaran en el manejador

                        $response["accion"] = "grabar_senal";

                        return json_encode($response);

                        #solo se envia esta respuesta, y la esp32 prepara el receptor IR para grabar y procesar los datos de la señal

                    }else{
                        return "nada para hacer. sin datos";
                    }

                }else{
                    return "nada para hacer";
                }

            }else{
                return "nada para hacer";
            }
            #en caso de que no haya acciones, o que no haya datos asociados a las acciones, se retorna el mensaje nada para hacer, y la esp32 no hara nada, pero seguira enviando requests cada 1 segundo

            # CABE ACLARAR QUE COMO TENEMOS LA PAGINA ALOJADA EN AWS CON HTTPS, LAS REQUESTS TIENEN UN POCO DE DELAY, HACIENDO QUE DEMOREN MAS O MENOS 2 SEGUNDOS. SE ESTA PLANTEANDO IMPLEMENTAR OTRO SERVER SIN HTTPS QUE MANEJE LAS REQUESTS DE LOS ESP32 Y SE CONECTE A LA BD DE FORMA REMOTA, PERO POR AHORA SE DEJA ASI


        }

    }

    public function updateSignal(){

        #este es el controlador que se ejecuta en caso de que la esp32 haya grabado una señal infrarroja. Envia por POST todos los datos necesarios para que el js actualice la señal en la bd principal

        $code= $this->request->getPost('code');

        $irCode1 = $this->request->getPost('codigoHex');

        $protocolo = $this->request->getPost('protocolo');

        $bits = $this->request->getPost('bits');

        $handlemodel = new Manejador();

        if($action=$handlemodel->getActionQuery($code)){
            $data=$handlemodel->getActionData($action[0]['ID_solicitud']);

            if(empty($data[0]["valor"])){
                $handlemodel->updateActionData($data[0]['ID_solicitud'],$irCode1,'codigo');

                $handlemodel->updateActionData($data[0]['ID_solicitud'],$protocolo,'protocolo');

                $handlemodel->updateActionData($data[0]['ID_solicitud'],$bits,'bits');

                #se actualiza el protocolo, el codigo hexadecimal y los bits de la señal grabada. Estos datos se guardan en la tabla de datos solicitud, en el campo valor, y se identifican por su clave (codigo, protocolo, bits)

                return "Señal actualizada";
            }else{
                return 'No se ha encontrado una solicitud de actualizacion de señal';
            }
        }else{
            return 'No hay accion solicitada';
        }
        #no deberia entrar nunca en estos 2 ultimos else, ya que si la esp llego a este punto es porque el usuario presiono una señal para grabar. Solo seria posible en caso de que el usuario abandone la vista en el medio del proceso, lo que no deberia pasar.

    }

    public function deleteData(){

        #esta funcion la ejecuta la esp y simplemente elimina los datos de la solicitud asociada a su codigo unico. Se va a ejecutar automaticamente despues de grabar o emitir una señal

        $code= $this->request->getPost('code');

        $handlemodel = new Manejador();

        if($action=$handlemodel->getActionQuery($code)){
            #se buscan las solicitudes a partir del codigo recibido por POST
            foreach($action as $a){
                #se iteran las acciones, ya que puede haber 2 teniendo en cuenta que un cron job puede ser ejecutado al mismo tiempo que un usuario tenga una solicitud activa
                if($a['estado']==1){
                    #en caso de que el estado sea 1, quiere decir que la solicitud es de un cron job, por lo que tambien hay que eliminar la solicitud de la tabla de solicitudes, ya que no es algo que maneje el usuario desde su sesion
                    $handlemodel->deleteQuerybyId($a['ID_solicitud']);

                }
                #en caso de que no, quiere decir que es una solicitud de un usuario, por lo que solo se eliminan los datos asociados a la solicitud
                $handlemodel->deleteActionData($a['ID_solicitud']);

            }

            //$handlemodel->deleteActionData($action[0]['ID_solicitud']);

            return 'informacion eliminada';
        }
    }



}