<?php 

namespace App\Controllers;

use App\Models\Esp32;
use App\Models\Dispositivos;
use App\Models\Manejador;
use App\Models\Registros;
use \App\Models\Usuarios;

class Esp32C extends BaseController{

    public function devicesbyEsp(){
        #FUNCION QUE OBTIENE LOS DISPOSTIVOS VINCULADOS A UN IRCONNECT SELECCIONADO POR EL USUARIO
        #LOS DATOS SE GUARDAN EN LA SESION DEL USUARIO PARA PODER SER UTILIZADOS EN OTRAS FUNCIONES
        #SE REDIRIGE A LA RUTA DEVICES QUE EJECUTARA EL CONTROLADOR QUE HARA LA CONSULTA A LA BD A TRAVES DEL MODELO
        #ESTO SE HACE ASI PORQUE SINO CUANDO EL USUARIO VUELVE A LA PAGINA PRINCIPAL, EL NAVEGADOR PIERDE LOS DATOS DEL FORMULARIO
        $session = session();

        $espmodel = new Esp32;

        $esp_id = $this->request->getPost("esp_id");

        $esp_ip= $this->request->getPost('esp_ip');

        $esp_code = $this->request->getPost('esp_code');

        $verify=$espmodel->getEsp32byCode($this->request->getPost('esp_code'));

        if($verify[0]['estado']==0){
            return redirect()->to('/')->with('error','El dispositivo no se encuentra disponible. Verifique su conexión y vuelva a intentarlo');
        }

        if(!$verify || $verify[0]['ID_dispositivo']!=$esp_id){
            return redirect()->to('/')->with('error','Los datos del dispositivo son incorrectos. Intente nuevamente');
        }

        if(session()->get('tipo') == 1){

            if($verify[0]['ID_administrador'] != session()->get('user_id')){

                return redirect()->to('/')->with('error','No tiene permisos para acceder a este dispositivo');

            }

        }else{

            $devices = $espmodel->getDevicesbyEsp($esp_id,session()->get('user_id'));

            if(count($devices) == 0){

                return redirect()->to('/')->with('error','No tiene permisos para acceder a este dispositivo');

            }

        }

        $session->set('esp_id',$esp_id);

        $session->set('esp_ip',$esp_ip);

        $session->set('esp_code',$esp_code);

        return redirect()->to(base_url('/devices')); 

    }

    public function devices(){
        #en esta funcion, se obtienen por la sesion todos los datos de la funcion anterior
        $espmodel = new Esp32;
        $session = session();
        $esp_id = $session->get('esp_id'); 

        $datos = $espmodel->getDevicesbyEsp($esp_id,$session->get('user_id'));
        #se buscan los dispositivos por la esp seleccionada y se pasa el user id para devolver SOLO los que el usuario tiene acceso. Si es el admin se va a devolver todo, pero si es profesor puede tener acceso solo a algunos
        $esp=$espmodel->getEsp32($esp_id);
        #se obtiene la esp para verificar el estado de la misma
        if($esp[0]['estado']==0){
            return redirect()->to('/')->with('error','El dispositivo no se encuentra disponible. Verifique su conexión y vuelva a intentarlo');
            #si el estado es 0 la esp esta desconectada y no se esta comunicando con el sitio, por lo que el usuario no tiene que poder acceder
        }
        
        if(session()->getFlashdata('error') || session()->getFlashdata('success')){
            $error=session()->getFlashdata('error');
            $success=session()->getFlashdata('success');
            return view("devices", ["datos" => $datos, "esp" => $esp, "error"=>$error, 'success'=>$success]);

            #si hay algun dato de error o success (por ejemplo si se inserta un dispositivo correcta o incorrectamente), se retorna a la vista con estos datos mas los datos de la esp y de los dispositivos

        }

        #TRAE LOS DATOS DE LOS DISPOSITIVOS Y DE LA ESP SELECCIONADA POR EL USUARIO Y LO ENVIA A LA VISTA DEVICES
        return view("devices", ["datos" => $datos, "esp" => $esp]);

    }


    public function newEspview(){
        $model=new Esp32;

        $last_esp=$model->verifycurrentvinculation(session()->get('user_id'));
         if(count($last_esp)>0){
            return redirect()->to('/')->with('error','Ya tiene una vinculación en curso. Completela y vuelva a intentarlo. <a href="'.base_url('/deletecurrentvinculation').'">Pulse aquí para eliminar su vinculación en curso y volver a completar el formulario</a>');
         }

        #VISTA PARA VINCULAR UN NUEVO IRCONNECT
         return view('new_esp');
    }

    public function insertNewesp(){
        #CUANDO EL USUARIO COMPLETA EL FORMULARIO DE VINCULACION DE UN NUEVO IRCONNECT, SE OBTIENE EL CODIGO Y LA UBICACION DEL FORMULARIO, Y EL ID Y MAIL DE USUARIO ALMACENADOS
        #EN LA SESION
        $code = $this->request->getPost('code');
        $id = session()->get('user_id');
        $location = $this->request->getPost('location');
        $model=new Esp32;

        if(!$code || !$location){
            #SI NO SE INGRESA EL CODIGO O LA UBICACION, SE RETORNA UN MENSAJE DE ERROR
            session()->setFlashdata('error', 'Debe ingresar un código y una ubicación para vincular el dispositivo');

            return redirect()->to(base_url('/'));
        }

        $validator =$model->getEsp32byCode($code);

        if($validator || $model->getEsp32and(['ID_administrador'=>$id,'ubicacion'=>$location])){
            #SI EL CODIGO YA EXISTE EN LA BD, O YA HAY UNA MISMA UBICACION ASOCIADA AL USUARIO, SE RETORNA UN MENSAJE DE ERROR
            session()->setFlashdata('error', 'El código o la ubicación ya están registrados. Intentelo nuevamente');

            return redirect()->to(base_url('/'));
        }
        #SE INSERTA EL REGISTRO CON LA IP NULA, SE VA A ACTUALIZAR CUANDO EL USUARIO CONECTE LA PLACA A WI FI UTILIZANDO SMARTCONFIG

        $esp=$model->insertEsp($location,$id,code: $code);

        if($esp){
            /*Se retorna a la vista esp_instructions con el codigo insertado, para que un js valide si la ip se actualizó o no*/
            return view('esp_instructions.php',['code'=>$code]);

        }else{

            return redirect()->to(base_url())->with('error','Ha ocurrido un error inesperado');

        }

        #SE RETORNA AL USUARIO A UNA VISTA QUE LE DETALLA COMO TIENE QUE HACER PARA CONECTAR LA PLACA A WIFI

        
    }

    


    public function sendIR() {
        #esta funcion es ejecutada por el js de las vistas de control de televisor y ventilador. 
        #el js envia el id de la accion, el id del dispositivo, el id de la funcion, el led y un numero que indica si se va a enviar una señal o se va a grabar una señal
        $action_id = $this->request->getPost('action_id');
        $deviceId = $this->request->getPost('deviceId');
        $functionId = $this->request->getPost('functionId');
        $led = $this->request->getPost('led');
        $num = $this->request->getPost('num');
       
 
            $devicemodel=new Dispositivos;

            $handlemodel=new Manejador;

            $signal=$devicemodel->getSignal($deviceId,$functionId);
            #en primer lugar se busca la señal en la base de datos, dependiendo del dispositivo y la funcion seleccionada por el usuario
            if($num==1 && $signal){

                $protocolo=$devicemodel->getProtocolbySignal($signal[0]['ID_senal']);

                $handlemodel->insertDataQuery('hexadecimal',$action_id,$signal[0]['codigo']);

                $handlemodel->insertDataQuery('protocolo',$action_id,$protocolo[0]['nombre']);

                $handlemodel->insertDataQuery("bits",$action_id,$signal[0]['bits']);

                $handlemodel->insertDataQuery('led',$action_id,$led);

                return $this->response->setStatusCode(200)->setBody('Señal enviada a la bd');
                #si la señal existe, se insertan los datos de la señal en la tabla de acciones en la bd de manejador, y se retorna un 200
            }elseif($num==2){
                #si el numero es 2, significa que se va a grabar una señal, por lo que se eliminan los datos de la accion actual para evitar duplicacion de datos
                $handlemodel->deleteActionData($action_id);

                $handlemodel->insertDataQuery('codigo',$action_id,null);

                $handlemodel->insertDataQuery("protocolo",$action_id,null);

                $handlemodel->insertDataQuery("bits",$action_id,null);
                #se insertan los mismos campos que antes pero con valor nulo, que seran rellenados cuando el usuario grabe una señal
                return $this->response->setStatusCode(200)->setBody('Señal enviada a la bd');

            }else{

                return $this->response->setStatusCode(500)->setBody('Error al enviar la señal');

            }



    }

    public function sendAirsignal(){
        #hace lo mismo que la funcion anterior pero con el dispositivo aire acondicionado
        #la diferencia es que se busca la señal por el id del dispositivo y el id de la configuracion, ya que cada señal de un aire acondicionado envia una configuracion completa (temperatura, velocidad, modo, etc) y no solo una funcion como en el caso del televisor o ventilador
        $action_id = $this->request->getPost('action_id');
        $deviceId = $this->request->getPost('deviceId');
        $configId = $this->request->getPost('configId');
        $led = $this->request->getPost('led');
        $num = $this->request->getPost('num');
       
 
            $devicemodel=new Dispositivos;

            $handlemodel=new Manejador;

            $signal=$devicemodel->getAirsginal($deviceId,$configId);

            if($num==1 && !$signal[0]['codigo']==null){

                $protocolo=$devicemodel->getProtocolbySignal($signal[0]['ID_senal']);

                $handlemodel->insertDataQuery('hexadecimal',$action_id,$signal[0]['codigo']);

                $handlemodel->insertDataQuery('protocolo',$action_id,$protocolo[0]['nombre']);

                $handlemodel->insertDataQuery("bits",$action_id,$signal[0]['bits']);
                $handlemodel->insertDataQuery("led",$action_id,$led);


                return $this->response->setStatusCode(200)->setBody('Señal enviada a la bd');

            }elseif($num==2){

                if(!$signal && $configId==0){
                    $devicemodel->insertSignal(null, $deviceId, null, null, null, 0);
                }

                $handlemodel->deleteActionData($action_id);

                $handlemodel->insertDataQuery('codigo',$action_id,null);

                $handlemodel->insertDataQuery("protocolo",$action_id,null);

                $handlemodel->insertDataQuery("bits",$action_id,null);

                return $this->response->setStatusCode(200)->setBody('Señal enviada a la bd');

            }else{

                return $this->response->setStatusCode(500)->setBody('Error al enviar la señal');

            }

 

    }
    
    public function return_after_vinculation($code){
        /*Codigo ejecutado por un js en la vista de esp_instructions. Se valida cada segundo si la esp actualizo su ip en la bd (es decir si el usuario completo la vinculacion conectando la placa a wifi). Si la respuesta es true, el js retorna al usuario a la ruta base de forma automática */ 
        $espmodel=new Esp32;

        $esp=$espmodel->getEsp32byCode($code);

        if($esp[0]['direccion_ip']!==null){
            return $this->response->setJSON(true);
        } else {
        
            return $this->response->setJSON(false);
        }

    }

    public function control_view(){
        #esta funcion retorna el control remoto de un televisor. Antes de esto, inserta una nueva solicitud de accion en la tabla de acciones del manejador
        #esta accion marca la esp como ocupada, por lo que si otro usuario intenta realizar una accion con la misma esp, se le mostrara un mensaje de error
        #el funcionamiento de la esp se explica mejor en el controlador Handle
        $handlemodel=new Manejador;
        $devicemodel=new Dispositivos;

        $verify = $devicemodel->user_has_permission($this->request->getPost('id'),session()->get('user_id'));

        if(count($verify) == 0){
            return redirect()->back()->with('error','No tiene permisos para acceder a este dispositivo');
        }

        if($handlemodel->getActionQuery(session()->get('esp_code'))){
            return redirect()->back()->with('error','No es posible realizar una acción ya que el dispositivo seleccionado se encuentra en uso. Aguarde unos segundos y vuelva a intentarlo');
        }
        $action_id=$handlemodel->insertActionQuery(1,session()->get('esp_code'));

        



        session()->set('action_id',$action_id);

        return view('tele2',['id'=>$this->request->getPost('id'),'led'=>$this->request->getPost('led')]);
        #junto con la vista se envia el id del dispositivo y el led que se va a utilizar para enviar las señales
    }
    public function air_view(){
        #hace lo mismo que la funcion anterior pero con el aire acondicionado. Debe devolver tambien los datos de las configuraciones, para que el usuario vea distintas cards dependiendo de su configuracion
        $handlemodel=new Manejador;
        $devicemodel=new Dispositivos;

        $verify = $devicemodel->user_has_permission($this->request->getPost('id'),session()->get('user_id'));

        if(count($verify) == 0){
            return redirect()->back()->with('error','No tiene permisos para acceder a este dispositivo');
        }

        if($handlemodel->getActionQuery(session()->get('esp_code'))){

            return redirect()->back()->with('error','No es posible realizar una acción ya que el dispositivo seleccionado se encuentra en uso. Aguarde unos segundos y vuelva a intentarlo');

        }

        $action_id=$handlemodel->insertActionQuery(1,session()->get('esp_code'));


        $devicemodel = new Dispositivos;

        $config=$devicemodel->getConfigbyDevice($this->request->getPost('id'));

        session()->set('action_id',$action_id);


        return view('aire2',['id'=>$this->request->getPost('id'),'config' => $config,'led'=>$this->request->getPost('led')]);
    }

    public function ventilador_view(){
        #funciona igual que la funcion del televisor pero retorna el control del ventilador
        $handlemodel=new Manejador;
                $devicemodel=new Dispositivos;

        $verify = $devicemodel->user_has_permission($this->request->getPost('id'),session()->get('user_id'));

        if(count($verify) == 0){
            return redirect()->back()->with('error','No tiene permisos para acceder a este dispositivo');
        }
        if($handlemodel->getActionQuery(session()->get('esp_code'))){
            return redirect()->back()->with('error','No es posible realizar una acción ya que el dispositivo seleccionado se encuentra en uso. Aguarde unos segundos y vuelva a intentarlo');
        }
        $action_id=$handlemodel->insertActionQuery(1,session()->get('esp_code'));

        session()->set('action_id',$action_id);

        return view('ventilador',['id'=>$this->request->getPost('id'),'led'=>$this->request->getPost('led')]);
        
    }
    
    #LAS SIGUIENTES 2 FUNCIONES SON DE PRUEBA Y LAS UTILIZAMOS PARA QUE CUANDO LA ESP RECIBA UNA SEÑAL INFRARROJA, ENVIE SU CODIGO A LA PAGINA Y EL USUARIO LO VEA EN PANTALLA
    public function receiveIrCode()
    {
        #EL ESP ENVIA EL CODIGO HEXADECIMAL DE LA SEÑAL (ACTUALMENTE NO ESTAMOS TRABAJANDO CON SEÑALES EN FORMA HEXADECIMAL SINO EN FORMATO RAW (CRUDO) PERO SOLO ERA UNA PRUEBA)
        #LA ESP TAMBIEN ENVIA SU CODIGO IDENTIFICADOR, EL MISMO QUE SE USA PARA LA VINCULACION

        $irCode1 = str_replace(" ",",",$this->request->getPost('irCode'));

        $irCode2 = preg_replace('/^\d+\s/', '', $irCode1);

        $irCode3 = substr($irCode2, 1);

        $irCode = str_replace(["\r", "\n"], '', subject: $irCode3);

        $code= $this->request->getPost('code');

        $filePath = WRITEPATH . 'data/'.$code.'.csv';

        #LA LETRA A SIGNIFICA APPEND, Y EL ARCHIVO SE ABRIRA PARA ESCRIBIR AL FINAL DEL ARCHIVO. SI EL ARCHIVO NO EXISTE, PHP LO CREA
        $file = fopen($filePath, 'a');
        if ($file) {
            file_put_contents($filePath, '');
            #SE CREA EL ARCHIVO CSV Y SE INSERTA EL ARCHIVO
            fputcsv($file, [$irCode]);
            fclose($file);
            return $this->response->setStatusCode(200)->setBody('Código IR recibido y guardado.');
        } else {
            return $this->response->setStatusCode(500)->setBody('Error al guardar el código IR.');
        }
    }

    public function ver_senales(){
        #ESTA FUNCION BUSCA EL ARCHIVO CSV QUE CONTIENE LAS SEÑALES IR RECIBIDAS POR EL IRCONNECT
        #EL ARCHIVO LO BUSCA POR EL CODIGO IDENTIFICADOR DEL DISPOSITIVO QUE ESTA ALMACENADO EN LA SESION DEL USUARIO
        $espmodel=new Esp32;

        $esp=$espmodel->getEsp32(session()->get('esp_id'));

        $filePath = WRITEPATH . 'data/'.$esp[0]['codigo'].'.csv';

        if (file_exists($filePath)) {
            if (($handle = fopen($filePath, 'r')) !== false) {
                #DENTRO DEL IF SE DECLARA A HANDLE COMO UN MANEJADOR DEL CSV, SI LA APERTURA FUE EXITOSA SE 
                #DECLARA UN ARRAY VACIO PARA LUEGO ITERAR LOS DATOS DEL CSV Y GUARDARLOS EN EL ARRAY
                $data = [];
                while (($row = fgetcsv($handle)) !== false) {
                    #LA CONDICION VALIDA QUE HAYA DATOS PARA LEER
                    #ITERA LAS SEÑALES Y LAS GUARDA EN UN ARRAY
                    $data[] = $row[0]; #EN ESTE CASO SE PONE 0 PORQUE SOLO HAY UNA COLUMNA
                }
                fclose($handle);
                
             register_shutdown_function(function() use ($filePath) {
                    #ESTA FUNCION SE EJECUTA CUANDO EL CONTROLADOR DEJA DE SER EJECUTADO
                 #CUANDO EL USUARIO SALGA DE LA PAGINA, SE ELIMINA EL ARCHIVO CSV
                 if (file_exists($filePath)) {
                    file_put_contents($filePath, '');
                 }
             });

                #DESCOMENTANDO ESTO, UNA VEZ QUE SE RECIBE UNA SEÑAL SE BORRA EL CSV POR LO QUE SOLO ES POSIBLE VER UNA SEÑAL A LA VEZ
                
                #RETORNA LAS SEÑALES EN FORMATO JSON PARA SER PROCESADAS POR UNA FUNCION DE JS
                return $this->response->setStatusCode(200)->setJSON($data);
                                
                
            }else {
                return $this->response->setStatusCode(500)->setBody('No se pudo abrir el archivo.');
            }
        } else {
            return $this->response->setStatusCode(404)->setBody('El archivo no existe.');
        }
    }
    
    public function ver_senales_vista(){
        return view('senales');
    }

    public function grabarAireview(){
        $handlemodel=new Manejador;
                $devicemodel=new Dispositivos;

        $verify = $devicemodel->user_has_permission($this->request->getPost('id'),session()->get('user_id'));

        if(count($verify) == 0){
            return redirect()->back()->with('error','No tiene permisos para acceder a este dispositivo');
        }
        if($handlemodel->getActionQuery(session()->get('esp_code'))){
            return redirect()->back()->with('error','No es posible realizar una acción ya que el dispositivo seleccionado se encuentra en uso. Aguarde unos segundos y vuelva a intentarlo');
        }
        $action_id=$handlemodel->insertActionQuery(2,session()->get('esp_code'));

        session()->set('action_id',$action_id);
        $devicemodel = new Dispositivos;

        $config=$devicemodel->getConfigbyDevice($this->request->getPost('id'));
        return view('grabar_aire',['id'=>$this->request->getPost('id'),'config'=>$config]);
    }

    public function grabarTeleview(){
        $handlemodel=new Manejador;
                $devicemodel=new Dispositivos;

        $verify = $devicemodel->user_has_permission($this->request->getPost('id'),session()->get('user_id'));

        if(count($verify) == 0){
            return redirect()->back()->with('error','No tiene permisos para acceder a este dispositivo');
        }
        if($handlemodel->getActionQuery(session()->get('esp_code'))){
            return redirect()->back()->with('error','No es posible realizar una acción ya que el dispositivo seleccionado se encuentra en uso. Aguarde unos segundos y vuelva a intentarlo');
        }
        $action_id=$handlemodel->insertActionQuery(2,session()->get('esp_code'));

        session()->set('action_id',$action_id);
        return view('grabar_tele',['id'=>$this->request->getPost('id')]);
    }

    public function grabarVentiladorview(){
        $handlemodel=new Manejador;
                $devicemodel=new Dispositivos;

        $verify = $devicemodel->user_has_permission($this->request->getPost('id'),session()->get('user_id'));

        if(count($verify) == 0){
            return redirect()->back()->with('error','No tiene permisos para acceder a este dispositivo');
        }
        if($handlemodel->getActionQuery(session()->get('esp_code'))){
            return redirect()->back()->with('error','No es posible realizar una acción ya que el dispositivo seleccionado se encuentra en uso. Aguarde unos segundos y vuelva a intentarlo');
        }
        $action_id=$handlemodel->insertActionQuery(2,session()->get('esp_code'));

        session()->set('action_id',$action_id);
        return view('grabar_ventilador',['id'=>$this->request->getPost('id')]);
    }

    #las 3 funciones anteriores hacen lo mismo, solo que retorna la vista del control remoto que utiliza el javascript para grabar una señal, no para emitir

    public function verifySignal(){
    #esta funcion es ejecutada por un js, lo que hace es verificar si se envio la señal. Esto se hace verificando si todavia la accion tiene datos en la base de datos, ya que la esp una vez emite la señal, hace una request al servidor para eliminar los datos de la accion (NO ASI LA ACCION, YA QUE EL USUARIO PUEDE EMITIR MAS DE UNA SEÑAL)
    $handlemodel=new Manejador;

    $action_id = $this->request->getPost('action_id');
    $device_id = $this->request->getPost('deviceId');

    if($data=$handlemodel->getActionData($action_id)){
        return $this->response->setStatusCode(500);
    }else{

        $registermodel = new Registros;

        $functionId = $this->request->getPost('functionId');
        $configId = $this->request->getPost('configId');

        // Verificación más robusta para functionId
        if($functionId !== null && $functionId !== '' && $functionId !== false){
            $registermodel->insertRegistro(session()->get('user_id'),$device_id,$functionId,null);
        }elseif($configId !== null && $configId !== '' && $configId !== false){
            $registermodel->insertRegistro(session()->get('user_id'),$device_id,null,$configId);
        }else{
            // Log para debugging si es necesario
            error_log("verifySignal: No se recibió functionId ni configId válidos. functionId: " . var_export($functionId, true) . ", configId: " . var_export($configId, true));
        }

        return $this->response->setStatusCode(code: 200);
    }
}

    public function deleteAction(){
        #esta funcion es ejecutada por un js. Se ejecuta cuando el usuario abandona la vista del control remoto, lo que libera la esp para que otro usuario pueda utilizarla
        $json = $this->request->getJSON();
        if ($json && isset($json->action_id)) {
            $handlemodel = new Manejador;
            $id = $json->action_id;
            $handlemodel->deleteActionData($id);
            $handlemodel->deleteActionQuery(session()->get('esp_code'));
    
            session()->remove('action_id');
        }

    }
 
    public function verifyRecording(){

        #esta funcion es similar a la anterior. En lugar de verificar que la accion no tiene datos, verifica que la señal se grabo correctamente en la base de datos del manejador. Esta funcion retorna los valores al js para que pueda guardar la señal en la base de datos principal, lo que finaliza la grabacion de la señal
        $action_id= $this->request->getJSON()->action_id;

        $handlemodel=new Manejador;

        $data=$handlemodel->getActionData($action_id);

        if(!$data){
            return $this->response->setStatusCode(400);
            #si no hay datos, significa que la esp no envio la señal, por lo que se retorna un error
        }

        if($data && !empty($data[0]['valor'])){
            $senal=$data[0]['valor'];

            $protocolo=$data[1]['valor'];

            $bits=$data[2]['valor'];

            $handlemodel->deleteActionData($action_id);

            return $this->response->setStatusCode(200)->setJSON(['hexadecimal'=>$senal,'protocolo'=>$protocolo,'bits'=>$bits]);
        }else{
            return $this->response->setStatusCode(500);
        }
    }
 
    public function deleteCurrentVinculation(){

        $user_id = session()->get('user_id');

        $espmodel = new Esp32;

        $data=$espmodel->getEsp32and(['ID_administrador'=>$user_id,'direccion_ip'=>null]);

        if($data && $espmodel->DeleteEsp32($data[0]['ID_dispositivo'])){

            return redirect()->to(base_url())->with('success','Vinculación eliminada correctamente.');
        }

        return redirect()->to(base_url());

    }

    public function sendVoiceSignal(){

        $json = $this->request->getJSON();
        // \Config\Services::sendEmail("santiagosalgado@alumnos.itr3.edu.ar","Voice Command Debug",json_encode($json));
        
        $devicename = $json->device_name ?? null;

        $location = $json->location ?? null;

        $function_id = $json->function_id ?? null;

        $signal_action = $json->signal_action ?? null;

        $user_id = session()->get('user_id');

        $espmodel = new Esp32;

        $devicemodel = new Dispositivos;

        $manejador = new Manejador;

        $registros = new Registros;

        $esp = $espmodel->getEsp32and(['ubicacion' => $location]);

        if($esp && $esp[0]['direccion_ip'] !== null && $esp[0]['estado'] == 1){

            $device=$devicemodel->getDevicesand(['nombre' => $devicename, 'ID_esp32' => $esp[0]['ID_dispositivo']]);

            if($device && $devicemodel->user_has_permission($device[0]['ID_dispositivo'],$user_id)){
                
                if($device[0]['ID_tipo'] != 1){

                    if($signal_action == "velocidad" && $device[0]['ID_tipo'] != 3){
                        return $this->response->setJSON(['success'=>false, 'message'=>'La función velocidad solo está disponible para ventiladores']);
                    }   

                    if(($signal_action == "subir_volumen" || $signal_action == "bajar_volumen") && $device[0]['ID_tipo'] != 2){
                        return $this->response->setJSON(['success'=>false, 'message'=>'Las funciones de control de volumen solo están disponibles para televisores']);
                    }

                    $signal=$devicemodel->getSignal($device[0]['ID_dispositivo'],$function_id);

                }else{

                    if($signal_action=='apagar'){
                        
                        $signal=$devicemodel->getAirsginal($device[0]['ID_dispositivo'],0);

                    }else{

                        $temperatura = $json->temperatura ?? null;

                        $modo = $json->modo ?? null;

                        if(!$temperatura && !$modo){

                            return $this->response->setJSON(['success'=>false, 'message'=>'Para encender un aire acondicionado debe especificar al menos la temperatura o el modo. Ejemplo: "Encender aire del dormitorio a 22 grados en frío"']);

                        }elseif($temperatura && !$modo){

                            $signal = $devicemodel->getAirsignalforvoice(['s.ID_dispositivo'=>$device[0]['ID_dispositivo'],'c.temperatura'=>$temperatura]);

                        }elseif($modo && !$temperatura){

                            $signal = $devicemodel->getAirsignalforvoice(['s.ID_dispositivo'=>$device[0]['ID_dispositivo'],'c.modo'=>$modo]);

                        }else{

                            $signal = $devicemodel->getAirsignalforvoice(['s.ID_dispositivo'=>$device[0]['ID_dispositivo'],'c.temperatura'=>$temperatura,'c.modo'=>$modo]);

                        }
                    }

                }
                
                if($signal){

                    if($manejador->getActionQuery($esp[0]['codigo'])){

                        return $this->response->setJSON(['success'=>false,'message'=>'No es posible realizar una acción ya que el IRConnect se encuentra en uso']);

                    }

                    $action_id=$manejador->insertActionQuery(1,$esp[0]['codigo']);

                    $manejador->updateQuerybyId($action_id);

                    $manejador->insertDataQuery('hexadecimal',$action_id,$signal[0]['codigo']);

                    $protocolo=$devicemodel->getProtocolbySignal($signal[0]['ID_senal']);

                    $manejador->insertDataQuery('protocolo',$action_id,$protocolo[0]['nombre']);

                    $manejador->insertDataQuery("bits",$action_id,$signal[0]['bits']);

                    $manejador->insertDataQuery('led',$action_id,$device[0]['led']);

                    $registros->insertRegistro($user_id,$device[0]['ID_dispositivo'],$function_id,null);

                    $response = ['success'=>true,'message'=>'Señal enviada correctamente.'];

                    return $this->response->setJSON($response);

                }else{

                    $response = ['success'=>false,'message'=>'No se encontró la señal solicitada.'];

                    return $this->response->setJSON($response);

                }
            }else{

                $response = ['success'=>false,'message'=>'No se encontró el dispositivo solicitado o no tiene permisos para acceder a él.'];

                return $this->response->setJSON($response);

            }

        }else{

            $response = ['success'=>false,'message'=>'El IRConnect está desconectado o no se encuentra registrado a su usuario.'];

            return $this->response->setJSON($response);

        }

    }

}