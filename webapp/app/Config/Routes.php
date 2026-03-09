<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/login', 'Session::login', ['filter' => 'login']); 
$routes->post("/generate2/(:segment)" , "Verification::generateCode/$1");
$routes->get("/logout" , "Session::logout",["filter" => ['auth']]);
$routes->post("/register","Session::register",['filter' => 'register']);
$routes->get("/generate/(:segment)" , "Verification::generateCode/$1");
$routes->post("/verification" , "Verification::verifyUser");
$routes->get("/reset","Verification::resetPwView");
$routes->post("/change","Users::changePw");
$routes->get("/new_user","Users::newUserView"); //YA TIENE EL FILTRO IMPLEMENTADO EN EL CONTROLADOR (AUTH Y ADMIN)
$routes->get("/create_pw/(:num)","Users::generatePw/$1");
$routes->post("/create_user","Users::createNewUser",['filter'=>['auth','admin']]);
$routes->post("/set_pw","Users::changePw");
$routes->get("/showUsers","Users::showUsers",['filter'=>['auth','admin']]);
$routes->post("/esp32","Esp32C::devicesbyEsp");
$routes->get("userInfo","Users::viewUserinfo",['filter'=>['auth']]);
$routes->get("/comprar", "Home::viewcomprar");
// $routes->get("/prueba","Home::viewPrueba");
$routes->post("/sendIR","Esp32C::sendIR"); //ejecuta por JS, hay que ver como filtrar eso
// $routes->get("/change_user","Users::changeUserview");
$routes->post("/change_user/change","Users::change_user");
$routes->get("/new_esp","Esp32C::newEspview",["filter" => ['auth','admin']]);
$routes->post("/new_esp/insert","Esp32C::insertNewesp", ["filter" => ['auth','admin']]);
$routes->post("/new_esp/receive","Handle::receiveEsp"); //ruta ejecutada por la esp, hay que ver como filtrar eso
$routes->post('/prueba_control',"Esp32C::control_view",["filter" => ['auth']]);
$routes->post('/prueba_aircontrol',"Esp32C::air_view", ["filter" => ['auth']]);
// $routes->get('/ver_senales','Esp32C::ver_senales_vista'); //no se usa mas, ruta de prueba vieja
// $routes->post('/recibir_codigo','Esp32C::receiveIrCode'); //no se usa mas, ruta de prueba vieja
// $routes->get('/mostrar_senales','Esp32C::ver_senales'); //otra ruta de prueba
$routes->post('/prueba_ventiladorcontrol',"Esp32C::ventilador_view", ["filter" => ['auth']]);
$routes->get('/devices','Esp32C::devices', ["filter" => ['auth','devices']]);
$routes->get('/new_device','Devices::newDeviceView', ["filter" => ['auth','admin']]);
$routes->post('/new_device/insert','Devices::newDevice', ["filter" => ['auth','admin']]);
$routes->post("/edit_device" , "Devices::editDeviceview", ["filter" => ['auth','admin']]);
$routes->post("/edit_device/update","Devices::updateDevice", ["filter" => ['auth','admin']]);
$routes->get("/delete_device/(:segment)" , "Devices::deleteDevice/$1", ["filter" => ['auth','admin']]); //EL CONTROLADOR MISMO VALIDA EL PERMISO DEL USUARIO
$routes->get("/return_after_vinculation/(:segment)" , "Esp32C::return_after_vinculation/$1");
$routes->post('/permisos','Users::administrarPermisos', ["filter" => ['auth','admin']]);
$routes->post('/actualizar_permiso','Users::actualizarPermiso', ["filter" => ['auth','admin']]);
$routes->post('/registros', 'Devices::registro', ["filter" => ['auth','admin']]);

// $routes->get('/expo',"Home::expo");
// $routes->get('/expo/actualizar',"Home::actualizar");
// $routes->get('/expo/getEstado',"Home::getEstado");
$routes->post('/grabar_aire',"Esp32C::grabarAireview", ["filter" => ['auth','admin']]);
$routes->post('/grabar_tele',"Esp32C::grabarTeleview", ["filter" => ['auth','admin']]);
$routes->post('/grabar_ventilador',"Esp32C::grabarVentiladorview", ["filter" => ['auth','admin']]);
//RUTAS DE JS A LA HORA DE GRABAR / EMITIR SEÑAL
$routes->post('/insertar_senal',"Devices::insertarSenal");
$routes->post('/enviar_senal',"Esp32C::sendIR");
$routes->post('/verificar_senal', 'Devices::verifySignal');
//--
//RUTAS EJECUTADAS POR LA ESP
$routes->post('/handle/updateDbsignal', 'Handle::updateSignal');
$routes->post('/handle/deleteData', 'Handle::deleteData');
//-----
//MAS RUTAS DE JS
$routes->post('/js/verificar_senal','Esp32C::verifySignal');
$routes->post('/front/eliminar_accion','Esp32C::deleteAction');
$routes->post('/js/verificar_grabacion','Esp32C::verifyRecording');
$routes->post('/verificar_grabacion','Devices::verifySignal');
//-----
$routes->post('/crear_config','Devices::viewConfig', ["filter" => ['auth','admin']]);
$routes->get("/deleteConfig/(:num)","Devices::deleteConfig/$1", ["filter" => ['auth','admin']]);
//RUTAS DE JS PARA SEÑALES DE AIRE
$routes->post('/air/enviar_senal','Esp32C::sendAirsignal');
$routes->post('/air/insertar_senal',"Devices::insertarAirsenal");
$routes->post('/air/verificar_grabacion','Devices::verifyAirsignal');
//--
$routes->post('/sessiondestroy','Session::destroySessions');
$routes->get('/sessiondestroy/view','Session::destroySessionsView');
$routes->get('/viewlogin','Home::viewlogin');
//PAYPAL
$routes->post("paypal/createOrder", "Paypal::createOrder");
$routes->post("paypal/captureOrder", "Paypal::captureOrder");
//---
$routes->post('/landing/mail','Home::formcontact');
// $routes->get('/pruebalogin','Home::pruebalogin');
$routes->post('/programsignal','Devices::programSignals',["filter" => ['auth','admin']]);
$routes->get('/get_signals/(:num)/(:num)','Devices::getSignalforcron/$1/$2'); //JS para form de crear programacion de señales
$routes->post('/program_signals/insert','Devices::programSignalsInsert',["filter" => ['auth','admin']]);
$routes->get('/intrucciones','Home::viewintructions');
$routes->get('/intrucciones_air','Home::viewAirintructions');
$routes->get('/viewevents' , 'Devices::viewEvents',["filter" => ['auth','admin','espid']]);
$routes->get('/deleteevent/(:num)','Devices::deleteevent/$1',["filter" => ['auth','admin']]);
$routes->get('/deletecurrentvinculation', 'Esp32C::deleteCurrentVinculation',["filter" => ['auth','admin']]);
$routes->post('/voice/sendsignal', 'Esp32C::sendVoiceSignal', ["filter" => ['auth']]);
if (is_cli()) {
    $routes->cli('home/testcronjob', 'Home::testcronjob');
    $routes->cli('cronjobs/deletestandarduser', 'CronJobs::deletestandarduser');
    $routes->cli('cronjobs/insertProgrammedsignal/(:num)', 'CronJobs::insertprogrammedsignal/$1');
    $routes->cli('cronjobs/deletedeviceswithoutsignals','CronJobs::deleteDeviceswithoutSignal');
    $routes->cli('cronjobs/deleteesp32','CronJobs::deleteEsp32');
    $routes->cli('cronjobs/deleteadminusers','CronJobs::deleteadminusers');

}




