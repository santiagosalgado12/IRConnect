<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginDataFIlter implements FilterInterface{

    public function before(RequestInterface $request, $arguments = null){

        $user=$_POST['username'];

        $password=$_POST['password'];

        if(empty($user) || empty($password)){
            return redirect()->to(base_url('/viewlogin'))->with('error', 'Faltan datos de inicio de sesión');
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        
    }

}