<?php

namespace App\Controllers;

use App\Models\Usuarios;
use App\Models\Esp32;
use App\Models\Acceso_usuarios;

use App\Models\Verificacion;

class Users extends BaseController{

    private $usersmodel;

    private $verificationmodel;

    #SE CREAN 2 ATRIBUTOS EN LA CLASE QUE CONTIENE LA INSTANCIA DE LOS MODELOS NECESARIOS
    public function __construct(){

        $this->usersmodel=new Usuarios();

        $this->verificationmodel=new Verificacion();

    }

    #FUNCION PARA CAMBIAR LA CONTRASEÑA DE UN USUARIO, PARA LLEGAR ACA TIENE QUE INGRESAR PREVIAMENTE UN CODIGO ENVIADO A SU MAIL POR SEGURIDAD:
    #TAMBIEN SE UTILIZA PARA QUE UN NUEVO USUARIO CREADO POR UN ADMIN SETEE SU CONTRASEÑA
    public function changePw(){

        $session=session();
        #SE OBTIENEN LOS DATOS INGRESADOS A TRAVES DEL METODO POST:
        $pw1=$this->request->getPost("password");

        $pw2=$this->request->getPost("pw-confirm");

        if(!$pw1 || !$pw2){
            #EN CASO DE QUE ALGUNO DE LOS CAMPOS NO HAYA SIDO COMPLETADO
            echo "<script>
            alert('Debe completar todos los campos. Inténtenlo nuevamente');
            window.location.href = '" . base_url('/viewlogin') . "';
            </script>";
            exit(); // Asegura que el script se detenga aquí
        }

        #SE VALIDA LA CONTRASEÑA CON CODEIGNITER, EXPLICADO EN LA LINEA 130 DEL CONTROLADOR SESSION.PHP
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

        if($pw1!=$pw2){
            #EN CASO DE QUE LOS 2 CAMPOS NO COINCIDAN
            echo "<script>
            alert('Las contraseñas no coinciden. Inténtenlo nuevamente');
            window.location.href = '" . base_url('/viewlogin') . "';
            </script>";
            exit(); // Asegura que el script se detenga aquí

        }
        
        elseif(!$this->validate($validationrules)){
            #EN CASO DE QUE LA CONTRASEÑA NO CUMPLA CON LOS REQUISITOS SE MUESTRA ESTE MENSAJE
            echo "<script>
            alert('La contraseña debe tener al menos 8 caracteres, una letra mayúscula y un número. Inténtelo nuevamente');
            window.location.href = '" . base_url('/viewlogin') . "';
            </script>";
            exit(); // Asegura que el script se detenga aquí
        }

        else{
            #EN CASO DE QUE LOS DATOS SEAN CORRECTOS:
            $session = session();

            $code=(string)$session->get('code');


            if(isset($code)){
                $this->verificationmodel->updateCode(['codigo'=>$code]);
                $this->usersmodel->verifyUser($session->get("user_id"));
                
                #SE ACTUALIZA EL CODIGO EN LA BD PARA QUE NO PUEDA SER UTILIZADO NUEVAMENTE
            }

            $hash=password_hash($pw1,PASSWORD_DEFAULT);#SE HASHEA LA CONTRASEÑA

            $n=$this->usersmodel->updatePw($hash,$session->get("user_id"));#SE ACTUALIZA LA CONTRASEÑA EN LA BD OBTENIENDO EL ID DEL USUARIO A TRAVES DE LA SESION

            if($n==true){
                #SI LA ACTUALIZACION FUE EXITOSA, SE MUESTRA ESTE MENSAJE CON EL BOTON PARA VOLVER AL INICIO
                echo "<script>
                alert('Contraseña actualizada correctamente');
                window.location.href = '" . base_url('/viewlogin') . "';
                </script>";
                exit(); // Asegura que el script se detenga aquí
            }else{
                $error='Ha ocurrido un error inesperado, intente nuevamente';
                return view('email',["error" => $error]);
            }

        }

    }

    public function newUserView(){

        #ESTA FUNCION SERA EJECUTADA POR LOS ADMINS EN CASO DE QUE DESEEN CREAR UN NUEVO USUARIO
        $session = session();

        if($session->get("logged_in") && $session->get("verificado") && $session->get("tipo") == 1){
            #SI EL USUARIO ESTA CORRECTAMENTE LOGEADO Y ES DE TIPO ADMINISTRADOR (1), SE LO LLEVA A ESA VISTA
            $users_at_same_day=$this->usersmodel->getUserand([
                'ID_administrador' => $session->get('user_id'),
                'fecha_creacion >=' => date('Y-m-d H:i:s', strtotime('-24 hours'))
            ]);

            if(count($users_at_same_day) >=10){
                return redirect()->back()->with('error', 'Solo puedes crear hasta 10 usuarios cada 24 horas');
            }

            return view("new_user");
        }else{
            return redirect()->to(base_url());
        }
       

    }

    public function createNewUser(){
        #FUNCION PARA CREAR UN NUEVO USUARIO DE TIPO PROFESOR (CREADO POR UN ADMINISTRADOR)
        $session = session();
        #SE OBTIENEN LOS DATOS DEL FORMULARIO
        $username=$this->request->getPost("username");

        $mail=$this->request->getPost("email");

        if(!$username || !$mail){
            #EN CASO DE QUE ALGUNO DE LOS CAMPOS NO HAYA SIDO COMPLETADO, SE MUESTRA ESTE MENSAJE
            $session->setFlashdata("error","Debe completar todos los campos");
            return redirect()->to(base_url("/showUsers"));
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            $session->setFlashdata("error", "El nombre de usuario solo puede contener letras y números.");
            return redirect()->to(base_url("/showUsers"));
        }
        #SE BUSCA SI EXISTE UN USUARIO CON ESOS DATOS
        $user= $this->usersmodel->getUser(["nombre_usuario" => $username,"email"=> $mail]);
 
        if($user){
            #EN CASO DE QUE EXISTA, EL USUARIO NO PODRA SER CREADO
            $session->setFlashdata("error","El nombre de usuario o el correo electrónico ya estan en uso");
            return redirect()->to(base_url("/showUsers"));

        }else{
            #SI LOS DATOS SON CORRECTOS, SE ARMA UN ARRAY CON LOS DATOS A INSERTAR EN LA BD
            $user_data=[
                "nombre_usuario" => $username,
                "email" => $mail,
                "hash_contrasena" => null, # SU CONTRASEÑA ES NULA YA QUE DEBE SER SETEADA POR EL USUARIO
                "salt" => null,
                "ID_permiso" => 2,
                "ID_administrador" => $session->get("user_id") #ID DEL ADMINISTRADOR QUE CREO EL USUARIO

            ];
            #SE INSERTA EL USUARIO EN LA BD
            $user_id=$this->usersmodel->insertUser($user_data);

            $code= \Config\Services::generateCode(); #SE GENERA UN NUEVO CODIGO

            $url= base_url("/create_pw/".$code); #SE GENERA UNA URL PARA ENVIAR AL USUARIO Y QUE GENERE SU CONTRASEÑA

            $this->verificationmodel->insertCode($user_id,$code,"crear_contrasena"); # SE INSERTA EL CODIGO EN LA BD

            #SE ENVIA UN EMAIL AL NUEVO USUARIO CREADO CON LA URL PARA SETEAR SU CONTRASEÑA
            \Config\Services::sendEmail($mail,"Te han creado un usuario en nuestro sitio web. Tu enlace para generar tu contraseña","<h2>El usuario ".$session->get("username")." te ha creado un usuario dentro de nuestro sitio. Utiliza este enlace para generar tu contraseña <br>".$url."<br>Para iniciar sesión, utiliza este nombre de usuario: ".$username."</h2>");
            #SE RETORNA AL ADMINISTRADOR A LA LISTA DE USUARIOS CREADOS
            $session->setFlashdata("success","Usuario creado correctamente");
            return redirect()->to(base_url("/showUsers"));

        }



    }
    #FUNCION PARA GENERAR UNA CONTRASEÑA, SERA EJECUTADA POR UN NUEVO USUARIO CREADO POR UN ADMIN
    public function generatePw($code){
        #SE BUSCA EL CODIGO EN LA BASE DE DATOS, SI EXISTE Y SI FUE USADO
        $findcode=$this->verificationmodel->getCode(["codigo" => $code,"usado" => 0]);

        if($findcode){
            #SI EL CODIGO ES VALIDO SE ALMACENA UNA VARIABLE CON EL ID DEL USUARIO
            $user_id=$findcode[0]["ID_usuario"];

            #SE BUSCA EL USUARIO EN LA BD
            $finduser= $this->usersmodel->getUser(["ID_usuario" => $user_id]);

            if($finduser[0]["hash_contrasena"] != null){
                #SI LA CONTRASEÑA DEL USUARIO YA FUE SETEADA, SE LE MUESTRA ESTE MENSAJE
                echo "<script>
                alert('Ya has creado tu contraseña');
                window.location.href = '" . base_url('/viewlogin') . "';
                </script>";
                exit(); // Asegura que el script se detenga aquí

            }else{
                #SI EL USUARIO EFECTIVAMENTE DEBE CREAR SU CONTRASEÑA, SE ALMACENA SU ID EN UNA VARIABLE DE SESION QUE SERA NECESARIA LUEGO PARA INSERTAR SU CONTRASEÑA EN LA BD Y SE 
                #LO ENVIA A LA VISTA PARA QUE PUEDA CREAR SU CONTRASEÑA
                $session=session();
                
                $session->set("code",$code);

                $session->set("user_id", $user_id);

                return view("create_pw");

            }

        }else{
            echo "<script>
            alert('Código inválido o ya utilizado.');
            window.location.href = '" . base_url() . "';
            </script>";
            exit();
        }

    }

    #FUNCION PARA QUE UN ADMIN PUEDA VER LA LISTA DE USUARIOS QUE HA CREADO
    public function showUsers(){

        $session=session();
        # SE BUSCAN LOS USUARIOS EN LA BD QUE TENGA COMO ID_ADMINISTRADOR AL USUARIO QUE DESEA ACCEDER A ESTA INFORMACION
        $datos=$this->usersmodel->getUsersbyAdmin($session->get("user_id"));
        #SE RETORNA LA VISTA CON EL RESULTADO DE LOS DATOS
        return view("show_users",["datos" => $datos]);

    }

    #FUNCION PARA QUE UN USUARIO VEA LA INFORMACION DE SU CUENTA
    public function viewUserinfo(){

        $session=session();
        # SE BUSCA AL USUARIO POR SU ID ALMACENADO EN LA SESION
        $data=$this->usersmodel->getUser(["ID_usuario" => $session->get("user_id")]);
        
        if(isset($data[0]["ID_administrador"])){
            #EN CASO DE QUE EL USUARIO HAYA SIDO CREADO POR UN ADMINISTRADOR, SE BUSCA TAMBIEN LOS DATOS DE ESTE Y SE RETORNA A LA VISTA CON LOS DATOS DEL USUARIO Y DE SU ADMINISTRADOR
            $admin=$this->usersmodel->getUser(["ID_usuario"=> $data[0]["ID_administrador"]]);
            return view("userinfo",["data"=> $data,"admin"=> $admin]);

        }else{
            #SINO, SE RETORNA A LA VISTA SOLO CON LOS DATOS DEL USUARIO
            return view("userinfo",["data"=> $data]);
        }
        
    }
 
    public function changeUserview(){

        return view("change_user");

    }

    public function change_user(){
        #FUNCION PARA CAMBIAR EL NOMBRE DE USUARIO

        $session=session();
        $username=$this->request->getPost("username");

        if(!$username){
            #EN CASO DE QUE NO SE HAYA INGRESADO UN NOMBRE DE USUARIO, SE MUESTRA ESTE MENSAJE
            $session->setFlashdata("error","Debe ingresar un nombre de usuario");
            return redirect()->to(base_url("/userInfo"));
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            #SI EL NOMBRE DE USUARIO NO CUMPLE CON LOS REQUISITOS, SE MUESTRA ESTE MENSAJE
            $session->setFlashdata("error", "El nombre de usuario solo puede contener letras y números.");
            return redirect()->to(base_url("/userInfo"));
        }

        if($this->usersmodel->getUser(["nombre_usuario" => $username])){
            #SI EL NOMBRE DE USUARIO YA ESTA EN USO
            $session->setFlashdata("error","El nombre de usuario ya está en uso");
            return redirect()->to(base_url("/userInfo"));
            
        }else{
            #SI EL NOMBRE DE USUARIO ESTA DISPONIBLE
            $this->usersmodel->updateData(["nombre_usuario" => $username],["ID_usuario" => $session->get("user_id")]);
            $session->set("username", $username);
            $session->setFlashdata("success","Nombre de usuario cambiado correctamente");
            return redirect()->to(base_url("/userInfo"));
        }

      
    }

    public function administrarPermisos(){

        $espmodel=new Esp32();

        $usermodel=new Usuarios();

        $aumodel=new Acceso_usuarios();

        $profesorid=$this->request->getPost("id");

        $verifyuser=$usermodel->getUserand(["ID_usuario" => $profesorid, "ID_administrador" => session()->get("user_id")]);

        if(!$verifyuser){
            session()->setFlashdata("error","El usuario no existe o no tienes permisos para administrarlo");
            return redirect()->to(base_url("/showUsers"));
        }

        $permisos=$aumodel->getpermissionid($profesorid);



        $user=$usermodel->getUser(["ID_usuario" => $profesorid]);

        $datos=$espmodel->arrayByDevices(session()->get("user_id"));


        return view("permisos",["datos" => $datos,"user" => $user,'permisos' => $permisos]);
    }

    public function actualizarPermiso(){

        $user = $this->request->getPost("user");
        $permisos = $this->request->getPost("permiso");
        $aumodel = new Acceso_usuarios();
    
        foreach ($permisos as $idDispositivo => $permiso) {
            if ($permiso == "permitido") {
                if (!$aumodel->getpermission($user, $idDispositivo)) {
                    $aumodel->insertAccess($user, $idDispositivo);
                }
            } elseif ($permiso == "denegado") {
                if ($aumodel->getpermission($user, $idDispositivo)) {
                    $aumodel->deleteAccess($user, $idDispositivo);
                }
            }
        }
    
        // Redirigir con un mensaje de éxito
        session()->setFlashdata("success", "Permisos actualizados correctamente");
        return redirect()->to(base_url("/showUsers"));

    }
 


}