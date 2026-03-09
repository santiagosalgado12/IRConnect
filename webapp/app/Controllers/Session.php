<?php

namespace App\Controllers;
#INCLUYE LOS MODELOS NECESARIOS
use App\Models\Usuarios;

use App\Models\Login_attemps;

use CodeIgniter\Controller;
use Usersmodel;

class Session extends BaseController{

    private $usermodel; #INICIALIZA UN ATRIBUTO DE LA FUNCION PARA PODER INSTANCIAR LA CLASE DEL MODELO DE USUARIOS

    public function __construct(){

        $this->usermodel=new Usuarios(); #INSTANCIA LA CLASE

    }

    public function login(){

        $session= session(); #SE INICIALIZA LA SESION PARA PODER SETEAR LOS DATOS DEL USUARIO Y QUE PUEDA NAVEGAR DENTRO DE LA PAGINA SI LOS DATOS SON VALIDOS

        #SE OBTIENEN LOS VALORES DE LOS CAMPOS INGRESADOS POR EL FORMULARIO A TRAVES DEL METODO POST
        $user=$this->request->getPost("username");

        $password=$this->request->getPost("password");

        $remember=$this->request->getPost("remember");

        $ip=$this->request->getIPAddress(); #SE OBTIENE LA DIRECCION IP DEL USUARIO

        $userAgent = $this->request->getUserAgent();
        $browser = $userAgent->getBrowser(); #OBTENER EL NAVEGADOR DEL USUARIO DESDE UN USERAGENT
        $version = $userAgent->getVersion(); #OBTENER LA VERSION DEL NAVEGADOR
        $platform = $userAgent->getPlatform(); #OBTENER EL SO
        $userAgentString = $this->request->getServer('HTTP_USER_AGENT'); #OBTENER EL USER AGENT COMPLETO COMO STRING PARA USAR EN EL HASH DE ABAJO

        $device_identificator = hash('md5', $ip . '|' . $userAgentString ); #CREA UN IDENTIFICADOR UNICO DEL DISPOSITIVO DEL USUARIO, UTILIZANDO LA DIRECCION IP Y EL USERAGENT, PARA PODER BLOQUEARLO EN CASO DE QUE SEA NECESARIO. SE HASHEA PARA QUE OCUPE MENOS EN LA BD


        $log_att=new Login_attemps; #SE INSTANCIA EL MODELO LOGIN_ATTEMPS

        if (strpos($user, '@') !== false) {
            #en caso de que el usuario haya ingresado un mail, va a haber un arroba en el string. El array data sera la condicion por la que se busque al usuario en la bd, que puede ser por mail o por nombre de usuario
            $data = ["email" => $user];
        } else {
            #es importante aclarar que el registro filtra el nombre de usuario para que no pueda contener caracteres especiales, sino podria haber un conflicto tomando al nombre de usuario como un mail si es que contiene un arroba
            $data = ["nombre_usuario" => $user];
        }

        $datos=$this->usermodel->getUser($data); #SE BUSCA EN LA BASE DE DATOS SI EL NOMBRE DE USUARIO O MAIL EXISTE

        #SE VERIFICA SI EL USUARIO FUE ENCONTRADO Y SE VERIFICA SI LA CONTRASEÑA INGRESADA COINCIDE CON LA ALMACENADA EN LA BASE DE DATOS
        if(count($datos) > 0 and password_verify($password,$datos[0]["hash_contrasena"])){

            #SE ARMA UN ARRAY CON LOS DATOS DE SESION 
            $data=[
                "username" => $datos[0]["nombre_usuario"],
                "user_id" => $datos[0]["ID_usuario"],
                "user_email" => $datos[0]["email"],
                "tipo" => $datos[0]["ID_permiso"],
                "logged_in" => true
            ];

            $session->set($data); #SE SETEAN LOS DATOS DENTRO DE LA SESION

            if(!$remember){
                /*SI EL USUARIO NO MARCÓ LA CASILLA RECORDARME, SE ESTABLECE QUE LA SESION FINALICE UNA VEZ EL USUARIO CIERRA SU NAVEGADOR,
                POR DEFECTO, LAS SESIONES ESTAN CONFIGURADAS PARA DURAR APROXIMADAMENTE 1*/
                setcookie('ci_session', session_id(), 0, '/');

            }

            $log_att->insertLoginattemp(1,$device_identificator,$datos[0]["ID_usuario"]); #SE INSERTA UN NUEVO LOGIN ATTEMP COMO EXITOSO, CON EL ID DEL USUARIO QUE INGRESO Y EL IDENTIFICADOR DE DISPOSITIVO
            
            #SE VERIFICA SI EL USUARIO ESTA VERIFICADO (PASO QUE DEBERIA REALIZAR AL MOMENTO DE REGISTRARSE)
            if($datos[0]["verificado"]==0){
                #EN CASO DE QUE NO LO ESTE, SE LO MANDA A ESA RUTA QUE LE ENVIA AL USUARIO UN CODIGO A SU DIRECCION EMAIL QUE DEBE INGRESAR PARA PODER VERIFICARSE
                return redirect()->to(base_url("/generate/verificacion"));

            }else{
                #EN CASO DE ESTARLO, EL INICIO DE SESION FUE EXITOSO, SE SETEA EN LA SESION QUE ESTA VERIFICADO Y SE LO ENVIA AL INICIO DE LA PAGINA
                $url=base_url("/sessiondestroy/view");
                \Config\Services::sendEmail($session->get('user_email'),"Nuevo inicio de sesión en tu cuenta","<h1>Hubo un nuevo inicio de sesión desde la siguiente dirección IP: ".$ip." <br> Navegador: $browser<br>Version: $version<br>Sistema operativo: $platform</h1><br><br>Si no has sido tú, <a href='$url'>Pulsa aquí para cerrar todas las sesiones asociadas a tu usuario</a>. Luego, te recomendamos cambiar tu contraseña.");

                #se le notifica al usuario que hubo un nuevo inicio de sesion en su cuenta, tiene la opcion de destruir todas las sesiones activas desde el enlace que se le envia al correo. Se le solicitara un codigo de verificacion y podrá destruirlas, esto hara que en caso de que no haya sido el usuario quien inicio sesion, le cierre la sesion al atacante. Logicamente, deberia posteriormente cambiar su contraseña.

                $session->set("verificado",true);
                #se setea que esta verificado en la sesion, para mandarlo a la ruta base y que pueda acceder a la vista de inicio
                return redirect()->to(base_url("/"));
            }

        #EN CASO DE QUE LOS DATOS SEAN INCORRECTOS:
        }else{

            if($datos){
                #SI EL USUARIO SE ENCONTRO, QUIERE DECIR QUE LO INCORRECTO ES LA CONTRASEÑA, POR LO QUE PRIMERO SE ALMACENA EL LOGIN ATTEMP, SE MARCA COMO NO EXITOSO Y SE 
                #ENVIA UN MENSAJE DE QUE LA CONTRASEÑA FUE INCORRECTA A TRAVES DE UN FLASHDATA DE SESION (UN DATO QUE SE PODRA UTILIZAR SOLO EN LA VISTA RETORNADA EN ESTA
                #FUNCION Y LUEGO SE BORRA
                $log_att->insertLoginattemp(0,$device_identificator,$datos[0]["ID_usuario"]);

                if($validator=$log_att->verifyDevicetoblock($device_identificator)){
                    #SI EL USUARIO INGRESO MAL LA CONTRASEÑA MAS DE 5 VECES EN LOS ULTIMOS 10 MINUTOS, SE LE BLOQUEA EL IDENTIFICADOR DE USUARIO
                    $log_att->blockDevice($device_identificator);

                    return redirect()->to(base_url("/viewlogin"));
                }

                $session->setFlashdata("error","Contraseña incorrecta");

            }else{
                #SI EL USUARIO NO SE ENCONTRO, QUIERE DECIR QUE INGRESÓ MAL EL NOMBRE, POR LO QUE SE INSERTA EL LOGIN ATTEMP NO EXITOSO SIN EL ID DE USUARIO Y SE ENVIA EL MENSAJE
                $log_att->insertLoginattemp(0,$device_identificator);

                if($validator=$log_att->verifyDevicetoblock($device_identificator)){
                    
                    $log_att->blockDevice($device_identificator);
                    
                    return redirect()->to(base_url("/viewlogin"));
                }

                $session->setFlashdata("error","Usuario no encontrado"); 

            }            

            #SE LO RETORNA NUEVAMENTE A LA VISTA DE LOGIN YA QUE NO PUDIERON SER SETEADOS SUS DATOS DE SESION AL HABER INGRESADO DATOS INCORRECTOS
            return redirect()->to(base_url("/viewlogin"));

        }
    }

    public function logout(){
        #funcion simple para cerrar sesion
        $session = session();

        if($session->get("verificado")){
            #si el usuario tiene almacenado el campo verificado en su sesion, quiere decir que efectivamente esta logueado, por lo que se procede a destruir la sesion
            $session->destroy();
            return view("logout");
        }else{
            #sino, no tiene una sesion activa, por lo que se lo envia a la ruta base
            return redirect()->to(base_url("/"));
        }

        
    }

    public function register(){

        $session= session();#SE INICIALIZA LA SESION

        #SE OBTIENEN LOS DATOS DEL FORMULARIO

        $user=$this->request->getPost("username");

        $password=$this->request->getPost("password");
    
        $pw_confirm=$this->request->getPost("password_confirm");

        $mail=$this->request->getPost("email");

        /* 
        SE VALIDA LA CONTRASEÑA CON LA FUNCION INCLUIDA EN CODEIGNITER, DONDE SE HACE UN ARRAY CON EL NOMBRE DEL CAMPO, SE INCLUYEN LAS REGLAS QUE DEBE TENER
        UTILIZANDO EXPRESIONES REGULARES Y SE SETEAN LOS MENSAJES DE ERROR EN CASO DE QUE LA CONTRASEÑA NO CUMPLA CON LOS REQUISITOS. EN ESTE CASO, LOS
        REQUISITOS SON: 8 CARACTERES, UNA MAYUSCULA Y UN NUMERO
        */
        $validationrules=[
            'password' => [
            'rules' => 'required|min_length[8]|max_length[255]|regex_match[/(?=.*[A-Z])(?=.*[0-9])/]',
            'errors' => [
                    'required' => 'La contraseña es obligatoria.',
                    'min_length' => 'La contraseña debe tener al menos 8 caracteres.',
                    'regex_match' => 'La contraseña debe contener al menos una letra mayúscula y un numero',
                ],
            ],
        ];

        // Validar que el nombre de usuario solo contenga letras y números
        if (!preg_match('/^[a-zA-Z0-9]+$/', $user)) {
            $session->setFlashdata("error", "El nombre de usuario solo puede contener letras y números.");
            return redirect()->to(base_url("/viewlogin"));
        }


        if($password!=$pw_confirm){
            #EN CASO DE QUE LA CONTRASEÑA Y SU CONFIRMACION NO COINCIDAD, SE LO DEVUELVE AL LOGIN CON EL MENSAJE DE ERROR
            $session->setFlashdata("error","Las contraseñas no coinciden");

            return redirect()->to(base_url("/viewlogin"));

        }
        elseif(!$this->validate($validationrules)){
            #EN CASO DE QUE LA CONTRASEÑA NO CUMPLA CON LOS REQUISITOS, TAMBIEN SE LO DEVUELVE AL LOGIN
            $session->setFlashdata("error",'La contraseña debe tener al menos 8 caracteres, incluir al menos una letra mayúscula y un número');

            return redirect()->to(base_url("/viewlogin"));

        }
        #SI LAS CONTRASEÑA ES VALIDA Y LOS 2 CAMPOS COINCIDEN:
        else{
            #SE ARMA UN ARRAY CON LAS CONDICIONES PARA BUSCAR UN USUARIO EN LA BD
            $where=[
                "nombre_usuario" => $user,
                "email" => $mail
            ];

            #SE BUSCA SI EXISTE UN USUARIO CON LOS DATOS INGRESADOS POR EL USUARIO (MAIL O NOMBRE). EL CONTROLADOR UTILIZA UN ORWHERE
            $data=$this->usermodel->getUser($where);

            if($data){
                /*
                EN CASO DE QUE SE HAYA ENCONTRADO UN USUARIO, QUIERE DECIR QUE NO PODRA SER CREADO PORQUE SUS DATOS YA EXISTEN, POR LO QUE SE LO DEVUELVE AL FORMULARIO DE REGISTRO
                CON EL MENSAJE DE ERROR
                */
                $session->setFlashdata("error","El nombre de usuario o el correo electrónico ya estan en uso");

                return redirect()->to(base_url("/viewlogin"));

            }else{
                /*
                EN CASO DE QUE EL USUARIO NO EXISTA Y LAS CONTRASEÑAS ESTEN CORRECTAS, SE PROCEDE CON LA CREACION DEL USUARIO Y SU REGISTRO EN LA BD
                */
                #SE HASHEA LA CONTRASEÑA UTILIZANDO LA FUNCION PREDEFINIDA EN PHP, QUE UTILIZA EL ALGORITOMO BCRYPT
                $hash=password_hash($password,PASSWORD_DEFAULT);

                #SE SETEAN LOS DATOS DEL USUARIO QUE DEBEN SER INGRESADOS EN LA BD 
                $data=[
                    "nombre_usuario" => $user,
                    "email" => $mail,
                    "hash_contrasena" => $hash,
                    "salt" => null,
                    "ID_permiso" => 1,
                    "ID_administrador" => null
                ];

                /*
                SE INSERTA EL USUARIO EN LA BD (LA FUNCION DEL MODELO ESTA PROGRAMADA PARA QUE TE DEVUELVA EL ID QUE SERA NECESARIO PARA INSERTAR EN LOS DATOS DE SESION, POR ESO
                EL NOMBRE DE LA VARIABLE)
                */
               $new_user_id = $this->usermodel->insertUser($data); 
                #SE SETEAN EN UN ARRAY LOS DATOS DE SESION
               $session_data=[
                "username" => $user,
                "user_id" => $new_user_id,
                "user_email" => $mail,
                "tipo" => 1,
                "logged_in" => true
                ];

                $session->set($session_data);


                #SE LO LLEVA AL CONTROLADOR ENCARGADO PARA VERIFICAR EL USUARIO A TRAVES DE SU EMAIL
                return redirect()->to(base_url("/generate/verificacion"));

            }
        }
    }


    public function destroySessions(){
        $usuario_id = $this->usermodel->getUser(['email' =>$this->request->getPost('mail')]) ; // ID del usuario actual

        if (!$usuario_id) {
            return redirect()->to('/viewlogin')->with('error', 'No se encontró el usuario.');
        }

        if(!session()->get('user_email') || !session()->get('user_id')){
            session()->set('user_id',$usuario_id[0]['ID_usuario']);
            session()->set('user_email',$this->request->getPost('mail'));
        }

        return redirect()->to(base_url('/generate/eliminar_sesiones'));
        #esta funcion es la que ejecuta el usuario para destruir sus sesiones. Envia por POST su mail, el controlador busca si existe el usuario y en caso de que no tenga una sesion activa, le setea estos datos para ser utilizado posteriormente en el controlador que va a eliminar las sesiones. Se lo envia a la ruta que genera un token de verificacion para verificar que es el mismo usuario quien desea hacer esto

    }

    public function destroySessionsView(){
        return view('sessiondestroy');
    }

}