<?php

namespace Config;

use App\Models\Eventos;
use CodeIgniter\Config\BaseService;
use DateTime;
use DateTimeZone;
use \App\Models\Verificacion;

use \App\Models\Manejador;


/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{

    public $manejador;

    public $eventos;

    public function __construct(){

        $this->modelo= new Manejador;

        $this->eventos = new Eventos;
    }
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */


     public static function sendEmail($email,$asunto,$cuerpo){

        $obj=\Config\Services::email();

        $obj->setTo($email);

        $obj->setSubject($asunto);

        $obj->setMessage($cuerpo);

        if($obj->send()){

            return true;
        }
        
        else{

            return false;
        }


    }


    public static function generateCode(){

        $usersmodel = new Verificacion();

        while(true){

            $hex = bin2hex(random_bytes(3)); // Genera 6 caracteres hexadecimales. Convierte 3 bytes a hexadecimal y cada byte se transforma en 2 caracteres hexadecimales.
            $number = base_convert($hex, 16, 10); // Convierte el número hexadecimal a decimal.
            $codigo = substr($number, 0, 6); // Toma los primeros 6 caracteres del número decimal.

            if(empty($usersmodel->getCode(["codigo" => $codigo]))){

                return $codigo;

            }


        }

    }

    public static function setTimeforcron($hora){

        $partes = explode(':', $hora);
    
        return [
            'minuto' => $partes[1] ?? '0',
            'hora' => $partes[0] ?? '0'
        ];
    }


    public static function setUniquedateforcron($date){

        $dateTime = new DateTime($date);
    
        return [
            'minuto' => $dateTime->format('i'),
            'hora' => $dateTime->format('H'),
            'dia' => $dateTime->format('d'),
            'mes' => $dateTime->format('m'),
            'dia_semana' => '*' // No aplica para fechas específicas
        ];

    }

    public static function setManydays($days){

        $mapeoDias = [
            'lunes' => '1',
            'martes' => '2',
            'miercoles' => '3',
            'jueves' => '4',
            'viernes' => '5',
            'sabado' => '6',
            'domingo' => '0'
        ];
        
        $diasCron = [];
        
        foreach ($days as $dia) {
            $diaLower = strtolower($dia);
            if (isset($mapeoDias[$diaLower])) {
                $diasCron[] = $mapeoDias[$diaLower];
            }
        }
        
        return !empty($diasCron) ? implode(',', $diasCron) : '*';

    }

    public static function deleteCronJob($cronJobToDelete) {
        // Leer crontab de root
        exec('sudo crontab -l 2>/dev/null', $currentCrontab, $returnCode);
        
        if ($returnCode !== 0 && $returnCode !== 1) {
            error_log("Error al leer crontab. Código: $returnCode");
            return false;
        }
    
        // Normalizar la línea a eliminar (quitar espacios múltiples)
        $target = preg_replace('/\s+/', ' ', trim($cronJobToDelete));
        
        // Filtrar coincidencias aproximadas
        $updatedCrontab = array_filter($currentCrontab, function ($line) use ($target) {
            $normalizedLine = preg_replace('/\s+/', ' ', trim($line));
            return $normalizedLine !== $target && !empty($normalizedLine) && $normalizedLine[0] !== '#';
        });
    
        // Escribir el nuevo crontab
        $tmpFile = tempnam(sys_get_temp_dir(), 'cron_');
        file_put_contents($tmpFile, implode(PHP_EOL, $updatedCrontab) . PHP_EOL);
        
        exec('sudo crontab ' . escapeshellarg($tmpFile), $output, $returnCode);
        unlink($tmpFile);
    
        return $returnCode === 0;
    }

    

    
}
