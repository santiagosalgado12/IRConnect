<?php

namespace App\Controllers;
#INCLUYE LOS MODELOS NECESARIOS
use App\Models\Acceso_usuarios;
use App\Models\Dispositivos;
use App\Models\Esp32;
use App\Models\Manejador;
use App\Models\Protocolos;
use App\Models\Eventos;
use App\Models\Registros;


use CodeIgniter\Controller;

class Devices extends BaseController{
        #FUNCION PARA MOSTRAR LA VISTA AGREGAR UNA ESP32
    public function newDeviceView(){

        $devicemodel = new Dispositivos;

        $data = $devicemodel->getDevicesand(['ID_esp32'=>session()->get('esp_id')]);
        #REVISA SI SE SUPERA EL LIMITE DE PLACAS REGISTRADAS (se puede registrar hasta 20 dispositivos por placa)
        if(count($data)>=20){

            return redirect()->to(base_url('/devices'))->with('error','Solo puedes agregar hasta 20 dispositivos por cada IRConnect');

        }

        return view('new_device');
    }
        #FUNCION PARA AGREGAR UN NUEVO DISPOSITIVO A LA ESP32
    public function newDevice(){
        #OBTIENE LOS DATOS DEL FORMULARIO
        $name=$this->request->getPost('name');

        $deviceType = $_POST['device_type'];

        $led = $this->request->getPost('led');

        if(!$name OR !$deviceType OR !$led){
            #verifica que todas las variables esten definidas
            return redirect()->to('/devices')->with('error','Faltan campos por completar');
        }

        $type=0;

        $devicemodel=new Dispositivos;
        #VERIFICA SI YA HAY EXSITENCIA DE UN DISPOSITIVO CON EL MISMO NOMBRE
        if($verify=$devicemodel->getDevicesand(['nombre'=>$name,'ID_esp32'=>session()->get('esp_id')])){
            return redirect()->to(base_url('/devices'))->with('error','Ya existe un dispositivo con ese nombre');
        }


        $user_access=new Acceso_usuarios();
        #SEGUN EL TIPO DE DISPOSITIVO SE LE ASIGNA UN ID CORRESPONDIENTE QUE ESTA EN LA BASE DE DATO
        switch ($deviceType) {
            case 'tv':
                $type=2;
                break;
            case 'aire_acondicionado':
                $type=1;
                break;
            case 'ventilador':
                $type=3;
                break;
            default:
                echo "Error";
                break;
        }
        #INSERTA EL NUEVO DISPOSITIVO A LA BD Y EN LA TABLA DE ACCESO SE LE ASIGNA EL ID DEL DISPOSITIVO AL USUARIO QUE AGREGO EL DISPOSITIVO
        $device=$devicemodel->insertDevice($name,$type,session()->get('esp_id'),$led);

        $user_access->insertAccess(session()->get('user_id'),$device);

        #se retorna a la ruta que muestra la lista de dispositivos
        return redirect()->to(base_url('/devices'));

    }
        #FUNCION PARA MOSTRAR LA VISTA DE EDITAR DISPOSITIVO
    public function editDeviceview(){
        #recibe el id por post, que se envia en un campo oculto. El boton editar funciona como un formulario que envia este dato
        $id=$this->request->getPost('id');
        $devicemodel=new Dispositivos;
        #VERIFICA SI EL USUARIO TIENE PERMISO PARA ACCEDER AL DISPOSITIVO 
        $device=$devicemodel->user_has_permission($id,session()->get('user_id'));

        if(empty($device)){
            #validacion 
            return redirect()->back()->with('error','No tiene permisos para acceder a este dispositivo');

        }else{

            return view('edit_device',['device'=>$device]);

        }

    }
        #FUNCION PARA ACTUALIZAR EL DISPOSITIVO
    public function updateDevice(){
        #OBTIENE LOS DATOS DEL FORMULARIO
        $name=$this->request->getPost('name');

        $id=$this->request->getPost('id');

        $deviceType = $_POST['device_type'];

        $led = $this->request->getPost('led');

         if(!$name OR !$deviceType OR !$led OR !$id){
            #verifica que todas las variables esten definidas
            return redirect()->to('/devices')->with('error','Faltan campos por completar');
        }

        $type=0;

        $devicemodel=new Dispositivos;
        #VERIFICA SI EXISTE OTRO DISPOSITIVO CON EL MISMO NOMBRE QUE EL ACTUAL
        #la funcion del modelo excluye el id del propio dispositivo, sino si se quisiera cambiar otra cosa que no sea el nombre, lanzaría error, ya que detectaria como que ya existe un dispositivo con ese nombre pero no es asi, ya que es el mismo dispositivo
        $verify=$devicemodel->verifyDevicetoUpdate(['nombre'=>$name,'ID_esp32'=>session()->get('esp_id')],$id);

        if($verify){
            return redirect()->to(base_url(relativePath: '/devices'))->with('error','Ya existe un dispositivo con ese nombre');
        }
        #SEGUN EL TIPO DE DISPOSITIVO SE LE ASIGNA UN ID CORRESPONDIENTE QUE ESTA EN LA BASE DE DATO
        switch ($deviceType) {
            case 'tv':
                $type=2;
                break;
            case 'aire_acondicionado':
                $type=1;
                break;
            case 'ventilador':
                $type=3;
                break;
            default:
                echo "Error";
                break;
        }
        #VERIFICA QUE TODAS LAS VARIABLES ESTEN DEFINIDAS
        if(!isset($name) OR !isset($id) OR !isset($type) OR !isset($led)){

            return redirect()->back();

        }else{
            
            $devicemodel->updateDevice($name,$type,$led,$id);

            return redirect()->to(base_url('/devices'));
        }
 
    }
        #FUNCION PARA ELIMINAR UN DISPOSITIVO
    public function deleteDevice($id){
        #en la vista de disposiivos, el boton eliminar tiene un pequeño js por el que el usuario debe confirmar si realmente desea eliminarlo para evitar que lo elimine sin querer por un missclick
        $devicemodel=new Dispositivos;
        $aumodel=new Acceso_usuarios;
        #VERIFICA SI EL USUARIO TIENE PERMISO PARA ACCEDER AL DISPOSITIVO 
        $device=$devicemodel->user_has_permission($id,session()->get('user_id'));

        if(empty($device)){

            return redirect()->back()->with('error','No tiene permisos para acceder a este dispositivo');

        }else{

            $devicemodel->deleteDevice($id);
            $aumodel->deleteaccessbydevice($id);
            $devicemodel->deleteSignalsbyDevice($id);
            return redirect()->to(base_url('/devices'));
            #ademas del dispositivo, se eliminan todos los accesos y todas las señales vinculadas a el
        }

    }
        #FUNCION PARA GUARDAR SEÑALES. ES EJECUTADA POR UN SCRIPT DE JS
    public function insertarSenal(){
        $senal=$this->request->getJSON()->irCode;

        $dispositivo=$this->request->getJSON()->deviceId;

        $funcion=$this->request->getJSON()->functionId;

        $protocolo=$this->request->getJSON()->protocolo;

        $bits=$this->request->getJSON()->bits;

        #se manda por json el codigo hexadecimal, el id de dispositivo, la funcion que cumple la señal
        #(prender, subir volumen, etc.), el nombre del protocolo y los bits

        $devicemodel=new Dispositivos;

        $handlemodel=new Manejador;

        $protocolmodel=new Protocolos;
        #se instancian los modelos a usar

        $id_protocolo=$protocolmodel->getIDprotocol($protocolo);
        #se obtiene el id del protocolo a partir del nombre
            #VERIFICA QUE EL USUARIO TENGA PERMISO DE ACCESO A ESE DISPOSITIVO
        $device=$devicemodel->user_has_permission($dispositivo,session()->get('user_id'));

        if(empty($device)){

            return redirect()->back();
        }else{
            if($devicemodel->getSignal($dispositivo,$funcion)){
                #SI LA SEÑAL YA ESTABA GRABADA PREVIAMENTE, QUIERE DECIR QUE EL USUARIO LA QUIERE SOBREESCRIBIR (ACTUALIZAR)
                if($devicemodel->updateSignal($senal,$dispositivo,$funcion,$id_protocolo[0]['ID_protocolo'],$bits,null)){
                    $handlemodel->deleteActionData(session()->get('action_id'));

                    return $this->response->setStatusCode(200)->setBody('Señal guardada correctamente.');
                }else{
                    return $this->response->setStatusCode(500)->setBody('Error al guardar la señal.');
                }
            }else{
                #sino, quiere decir que la señal nunca fue grabada y debe ser insertada por primera vez, por lo que se ejecuta insertSiganl en lugar de updateSignal
                if($devicemodel->insertSignal($senal,$dispositivo,$funcion,$id_protocolo[0]['ID_protocolo'],$bits,null)){
                    $handlemodel->deleteActionData(session()->get('action_id'));

                    return $this->response->setStatusCode(200)->setBody('Señal guardada correctamente.');
                }else{
                    return $this->response->setStatusCode(500)->setBody('Error al guardar la señal.');
                }
            }
        }

    }

    public function insertarAirsenal(){
        #funcion que inserta una señal de un aire, es igual que la otra nada mas que en vez de trabajar con funciones trabaja con configuraciones. El ID se manda directamente ya que el js ya lo puede obtener desde la vista
        $senal=$this->request->getJSON()->irCode;

        $dispositivo=$this->request->getJSON()->deviceId;

        $config=$this->request->getJSON()->configId;

        $protocolo=$this->request->getJSON()->protocolo;

        $bits=$this->request->getJSON()->bits;

        #se mandan los datos por json

        $devicemodel=new Dispositivos;

        $handlemodel=new Manejador;

        $protocolmodel=new Protocolos;

        $id_protocolo=$protocolmodel->getIDprotocol($protocolo);

        $device=$devicemodel->user_has_permission($dispositivo,session()->get('user_id'));

        if(empty($device)){

            return redirect()->back();

        }else{

            #en este caso el primer if esta de mas, porque siempre que se crea una configuracion la señal ya esta creada, solo que hasta que el 
            #usuario no la graba no se actualizan los campos de codigo, protocolo y bits
            #por lo que siempre va a entrar al primer if

            if($devicemodel->getAirsginal($dispositivo,$config)){
                if($devicemodel->updateAirsignal($senal,$dispositivo,null,$id_protocolo[0]['ID_protocolo'],$bits,$config)){
                    $handlemodel->deleteActionData(session()->get('action_id'));

                    return $this->response->setStatusCode(200)->setBody('Señal guardada correctamente.');
                }else{
                    return $this->response->setStatusCode(500)->setBody('Error al guardar la señal.');
                }
            }else{
                if($devicemodel->insertSignal($senal,$dispositivo,null,$id_protocolo[0]['ID_protocolo'],$bits,$config)){
                    $handlemodel->deleteActionData(session()->get('action_id'));

                    return $this->response->setStatusCode(200)->setBody('Señal guardada correctamente.');
                }else{
                    return $this->response->setStatusCode(500)->setBody('Error al guardar la señal.');
                }
            }
            #las funciones trabajan igual pero con configuraciones en lugar de funciones
        }

    }

    #funcion ejecutada por js tambien, verifica si una señal ya esta previamente grabada
    public function verifySignal(){
        $function=$this->request->getJSON()->functionId;

        $dispositivo=$this->request->getJSON()->deviceId;
        #recive el id de dispositivo y la funcion
        $devicemodel=new Dispositivos;
        #verifica permiso de acceso
        $device=$devicemodel->user_has_permission($dispositivo,session()->get('user_id'));

        if(empty($device)){

            return redirect()->back();
        }else{
            if($devicemodel->getSignal($dispositivo,$function)){
                return $this->response->setStatusCode(200)->setBody('Señal ya existe.');
            }else{
                return $this->response->setStatusCode(500)->setBody('Señal no existe.');
            }
        #si obtiene señal, manda una respuesta 200 y el js va a mostrar un mensaje que diga que la señal ya esta grabada y si se desea sobreescribir, en caso de que si, se va a ejecutar la funcion insertar senal
        #si no esta grabada, el js la va a ejecutar sin consultar.
        }

    }


#esta funcion funciona igual que la de arriba nada mas que con configuraciones en vez de funciones
    public function verifyAirsignal(){

        $config=$this->request->getJSON()->configId;

        $dispositivo=$this->request->getJSON()->deviceId;

        $devicemodel=new Dispositivos;

        $device=$devicemodel->user_has_permission($dispositivo,session()->get('user_id'));

        if(empty($device)){

            return redirect()->back();
        }else{

            $senal=$devicemodel->getAirsginal($dispositivo,$config);

            if(!$senal[0]['codigo']==null){
                return $this->response->setStatusCode(200)->setBody('Señal ya existe.');
            }else{
                return $this->response->setStatusCode(500)->setBody('Señal no existe.');
            }
        }

    }

    public function viewConfig(){
        #funcion que inserta una nueva configuracion para un aire acondicionado (el nombre esta mal puesto porque no tiene nada que ver con lo que hace)

        #la bd esta normalizada para insertar las configuraciones en una tabla (que guarda temperatura, modo, etc.) y despues ese id se le asigna a las señales del aire

        #esto es porque es muy comun que se repitan las configuraciones

        $devicemodel=new Dispositivos;

        $temperatura = $this->request->getPost('temperatura');
        $swing = $this->request->getPost('swing');
        $modo = $this->request->getPost('modo');
        $fanspeed = $this->request->getPost('fanspeed');
        $deviceid=$this->request->getPost('id'); #id del dispositivo

        $data=$devicemodel->verifyConfig($temperatura,$modo,$swing,$fanspeed);
        #se busca si la configuracion ya esta registrada en la tabla configuraciones
        if($data){

            $verify=$devicemodel->verifyAirsignal($deviceid,$data[0]['ID_configuracion']);
            #si lo esta, no es necesario registrarla, por lo que se procede a insertar la señal con el id del dispositivo asociado
            if($verify){
                #si ya hay una señal, la configuracion ya esta creada
                return $this->response->setJSON(['error' => 'Ya has creado esta configuración']);
            }else{

                $devicemodel->insertSignal(null,$deviceid,null,null,null,$data[0]['ID_configuracion']);
                return $this->response->setJSON(['success' => 'Configuración creada con éxito. Actualiza la página para verla']);

                #sino, se inserta una señal con sus datos nulos (excepto el id de dispositivo y de configuracion). Cuando el usuario grabe la señal, se van a actualizar los campos de codigo, protocolo y bits para poder posteriormente emitirla
            }

        }else{
            $config=$devicemodel->insertConfig($temperatura,$modo,$swing,$fanspeed);

            $devicemodel->insertSignal(null,$deviceid,null,null,null,$config);

            return $this->response->setJSON(['success' => 'Configuración creada con éxito. Actualiza la página para verla']);

            #si la configuracion es nueva, primero se guarda en la tabla configuraciones y luego la señal con el id de dispositivo y configuracion. No es necesario validar que la configuracion ya esta creada en el dispositivo porque justamente no existia antes en la bd


        }

    }

    public function deleteConfig($id){
        #
        $devicemodel=new Dispositivos;

        $devicemodel->deleteConfig($id);

        return $this->response->setJSON(['success' => 'Configuración eliminada']);

        #elimina la señal del aire que posee x configuracion. No se elimina la configuracion en si, ya que puede seguir siendo usada en otros dispositivos

    }

    public function programSignals(){
        #devuelve la vista del formulario para programar un nuevo evento
        $esp=new Esp32;

        if(!$validator=$esp->getEsp32and(['ID_administrador'=>session()->get('user_id'), 'ID_dispositivo' => $this->request->getPost('id')])){
            return redirect()->to(base_url('/devices'))->with('error','El dispositivo no existe o no tienes permiso para acceder a él');
        }

        $devices=$esp->getDevicesbyEsp($this->request->getPost('id'),session()->get('user_id'));

        return view('program_signals',["esp" => $this->request->getPost('id'),'devices' => $devices]);
    }

    public function programSignalsInsert()
{
    helper(['form']);

    // Validación inicial de campos comunes
    $rules = [
        'device_type' => 'required|integer',
        'signal' => 'required|integer',
        'schedule_type' => 'required|in_list[unica_vez,periodica]',
    ];

    $scheduleType = $this->request->getPost('schedule_type');

    if ($scheduleType === 'unica_vez') {
        $rules['once_date'] = 'required|valid_date';
        $rules['once_time'] = 'required';
    } elseif ($scheduleType === 'periodica') {
        $rules['recurring_time'] = 'required';
        $rules['recurring_days'] = 'required';
    }

    // Si no pasa validación, redirige con errores
    if (!$this->validate($rules)) {
        return redirect()->to('/devices')->with('error', 'Faltan campos o hay datos inválidos');
    }

    // OK, ya podemos procesar datos confiando en su existencia y formato
    $eventsmodel = new Eventos;

    $deviceType = $this->request->getPost('device_type');
    $signal = $this->request->getPost('signal');
    $led = $this->request->getPost('led');
    $scheduleType = $this->request->getPost('schedule_type');

    if ($scheduleType === "unica_vez") {
        $date = $this->request->getPost('once_date');
        $time = $this->request->getPost('once_time');
        $dia_semana = date('w', strtotime($date));

    if ($conflictEvent = $eventsmodel->checkTimeConflict(
        session()->get('esp_id'), 
        $time, 
        [$dia_semana]
    )) {
        return redirect()->to(base_url('/devices'))->with('error', 'Ya existe un evento que se superpone con este horario');
    }

        $processeddate = \Config\Services::setUniquedateforcron($date);
        $processedtime = \Config\Services::setTimeforcron($time);

        $eventid = $eventsmodel->insertEvent(
            session()->get('esp_id'),
            $signal,
            $scheduleType,
            $time,
            $led,
            $dia_semana,
            $date
        );

        $cronExpression = $processedtime['minuto'] . ' ' .
            $processedtime['hora'] . ' ' .
            $processeddate['dia'] . ' ' .
            $processeddate['mes'] . ' *';

        $command = '/usr/bin/php /var/www/html/irconnect/public/index.php cronjobs insertProgrammedsignal ' .
            $eventid . " >> /root/ir_cron.log 2>&1";

        $currentCrontab = [];
        exec('sudo crontab -l 2>&1', $currentCrontab, $returnVar);

        $cronJob = $cronExpression . ' ' . $command . PHP_EOL;
        $currentCrontab[] = $cronJob;

        $tmpFile = tempnam(sys_get_temp_dir(), 'cron');
        file_put_contents($tmpFile, implode(PHP_EOL, $currentCrontab) . PHP_EOL);
        exec('sudo crontab ' . $tmpFile);
        unlink($tmpFile);

        return redirect()->to(base_url('/devices'))->with('success', 'Evento creado correctamente');

    } else {
        $date = $this->request->getPost('recurring_days');
        $time = $this->request->getPost('recurring_time');

        $proccesseddays = \Config\Services::setManydays($date);
        $diasArray = explode(',', $proccesseddays);

    if ($conflictEvent = $eventsmodel->checkTimeConflict(
        session()->get('esp_id'), 
        $time, 
        $diasArray
    )) {
        return redirect()->to(base_url('/devices'))->with('error', 'Ya existe un evento que se superpone con este horario');
    }

        $processedtime = \Config\Services::setTimeforcron($time);

        $eventId = $eventsmodel->insertEvent(
            session()->get('esp_id'),
            $signal,
            $scheduleType,
            $time,
            $led,
            $proccesseddays,
            null
        );

        $cronExpression = $processedtime['minuto'] . ' ' .
            $processedtime['hora'] . ' * * ' .
            $proccesseddays;

        $command = '/usr/bin/php /var/www/html/irconnect/public/index.php cronjobs insertProgrammedsignal ' .
            $eventId . " >> /root/ir_cron.log 2>&1";

        $currentCrontab = [];
        exec('sudo crontab -l 2>&1', $currentCrontab, $returnVar);

        $cronJob = $cronExpression . ' ' . $command . PHP_EOL;
        $currentCrontab[] = $cronJob;

        $tmpFile = tempnam(sys_get_temp_dir(), 'cron');
        file_put_contents($tmpFile, implode(PHP_EOL, $currentCrontab) . PHP_EOL);
        exec('sudo crontab ' . $tmpFile);
        unlink($tmpFile);

        return redirect()->to(base_url('/devices'))->with('success', 'Evento recurrente creado correctamente');
    }
}


    public function getSignalforcron($id,$type){
        #funcion ejecutada por un js que funciona a la hora de seleccionar un dispositivo en el formulario de crear un nuevo evento
        $devicemode = new Dispositivos;

        if($type==2 || $type==3){
            #si es un ventilador o un aire, es mucho mas sencillo porque solo se necesita el nombre de la funcion que realiza la señal (ademas del codigo, protocolo, id, etc que se manejan de forma oculta). Todo eso lo retorna el modelo 
            $signals=$devicemode->getSignalsforcron($id);
        }else{
            #en el caso de los aires, se necesita mostrarle al usuario que hace la señal, por lo que se necesita ademas del codigo, protocolo, id, etc. La temperatura, el modo, el swing, etc.
            $data=$devicemode->getAirforcron($id);  


             $signals = array_map(function ($signal){
                 return [
                     'nombre' => $signal['ID_configuracion'] == 0 ? 'off' : 'T: ' . $signal['temperatura'] .' °C'. ', modo: ' . $signal['modo'] . ', swing: ' . $signal['swing'] . ', velocidad: '. $signal['fanspeed'],
                     'codigo' => $signal['codigo'],
                     'ID_protocolo' => $signal['ID_protocolo'],
                     'bits' => $signal['bits'],
                     'ID_senal' => $signal['ID_senal']
                 ];
             },$data);

             #se arma un array con estos datos y se retorna la señal
            
        }   

        if (empty($signals)) {
            return $this->response->setJSON(['error' => 'No se encontraron señales para este dispositivo.']);
        }
        #en caso de que el dispositivo no tenga señales grabadas, se retorna esto, pero el usuario no va a ver los dispositivos sin señales en el form ya que el modelo filtra esto

        return $this->response->setJSON($signals);
        
    }

    public function viewEvents(){

        $eventsmodel = new Eventos;

        $data=[];

        $evento = $eventsmodel->getEvent(['ID_esp' => session()->get('esp_id')]);
        $clave = 0;
        foreach($evento as $e){

            if($e['tipo']=="unica_vez"){
                $data[$clave]['tipo']="Unica vez";
                $data[$clave]['fecha']=$e['fecha'];
            }else{
                $data[$clave]['tipo']="Recurrente";
                if ($e['dias'] == "1,2,3,4,5,6,0") {
                    $data[$clave]['dias'] = "Todos los días";
                } else {
                    $daysMap = [
                        '0' => 'Domingo',
                        '1' => 'Lunes',
                        '2' => 'Martes',
                        '3' => 'Miércoles',
                        '4' => 'Jueves',
                        '5' => 'Viernes',
                        '6' => 'Sábado'
                    ];
                
                    // Filtrar caracteres no válidos
                    $daysArray = array_filter(str_split($e['dias']), function ($day) use ($daysMap) {
                        return isset($daysMap[$day]);
                    });
                
                    // Mapear días válidos
                    $readableDays = array_map(function ($day) use ($daysMap) {
                        return $daysMap[$day];
                    }, $daysArray);
                
                    $data[$clave]['dias'] = implode(', ', $readableDays);
                }

            }

            $data[$clave]['hora']=$e['hora'];

            $data[$clave]['ID_evento']=$e['ID_evento'];

            $datasignal = $eventsmodel->viewEvents($e['ID_senal']);

            if(!$datasignal[0]['ID_funcion']==null){

                $signalinfo=$eventsmodel->viewEventssingal($e['ID_senal']);
                $data[$clave]['funcion'] = $signalinfo[0]['funcion'];


            }else{
                $signalinfo=$eventsmodel->viewEventsair($e['ID_senal']);
                $data[$clave]['funcion'] = "T: ".$signalinfo[0]['temperatura'].", M: ".$signalinfo[0]['modo'].", FS: ".$signalinfo[0]['fanspeed'].", S: ".$signalinfo[0]['swing'];

            }

            $data[$clave]['dispositivo'] = $signalinfo[0]['dispositivo'];

            $clave +=1;
        }


        return view('viewevents' , ['eventos' => $data]);

    }

    public function deleteevent($id){
        $eventsmodel = new Eventos;

        $esp32model=new Esp32;

        $eventdata = $eventsmodel->getEvent(['ID_evento' => $id]);

        if($eventdata){

            if($user_has_permission=$esp32model->getEsp32and([
                'ID_dispositivo' => $eventdata[0]['ID_esp'],
                'ID_administrador' => session()->get('user_id')
            ])){

                $currentCrontab = [];
                exec('sudo crontab -l 2>&1', $currentCrontab, $returnVar);

                $cronJobToRemove = null;

                if ($eventdata[0]['tipo'] == "unica_vez") {
                    $processeddate = \Config\Services::setUniquedateforcron($eventdata[0]['fecha']);
                    $processedtime = \Config\Services::setTimeforcron($eventdata[0]['hora']);

                    $cronExpression = $processedtime['minuto'] . ' ' .
                        $processedtime['hora'] . ' ' .
                        $processeddate['dia'] . ' ' .
                        $processeddate['mes'] . ' *';
                } else {
                    $processedtime = \Config\Services::setTimeforcron($eventdata[0]['hora']);
                    $cronExpression = $processedtime['minuto'] . ' ' .
                        $processedtime['hora'] . ' * * ' .
                        $eventdata[0]['dias'];
                }

                $command = '/usr/bin/php /var/www/html/irconnect/public/index.php cronjobs insertProgrammedsignal ' .
                    $id . " >> /root/ir_cron.log 2>&1";

                $cronJobToRemove = $cronExpression . ' ' . $command;

                $updatedCrontab = array_filter($currentCrontab, function ($line) use ($cronJobToRemove) {
                    return trim($line) !== trim($cronJobToRemove);
                });

                $tmpFile = tempnam(sys_get_temp_dir(), 'cron');
                file_put_contents($tmpFile, implode(PHP_EOL, $updatedCrontab) . PHP_EOL);

                exec('sudo crontab ' . $tmpFile);
                unlink($tmpFile);

                $eventsmodel->deleteEvent($id);

                return redirect()->to(base_url('/devices'))->with('success', 'Evento eliminado correctamente');

            }

            return redirect()->to(base_url('/devices'))->with('error', 'Error al eliminar el evento: no tienes permisos para hacerlo');


        }
        return redirect()->to(base_url('/devices'))->with('error', 'Error al eliminar el evento: no se encontró almacenado en la base de datos');
        
    }

    public function registro(){

        $model = new Registros();

        $devicemodel = new Dispositivos();

        $deviceId = $this->request->getPost('id'); //id de dispositivo

        $permiso=$devicemodel->user_has_permission($deviceId,session()->get('user_id'));

        if(empty($permiso) || $deviceId === null){

            return redirect()->to(base_url('/devices'))->with('error','El dispositivo no existe o no tienes permiso para acceder a el');

        }

        $data = $model->getRegistro($deviceId);

        return view('registros',["registros" =>$data]);
        
        #falta la logica aca. El modelo ya esta hecho es $model->getRegistro($deviceId), ya te mapea los datos desde sql, solo falta armar la vista, enviarle
        #los datos desde aca y armar la tabla 

    }

}