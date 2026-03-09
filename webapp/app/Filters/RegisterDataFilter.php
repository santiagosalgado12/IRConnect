<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RegisterDataFilter implements FilterInterface{

    public function before(RequestInterface $request, $arguments = null){

        $username=$_POST['username'];

        $password=$_POST['password'];

        $pw_confirm=$_POST['password_confirm'];

        $email=$_POST['email'];

        if(empty($username) || empty($password) || empty($pw_confirm) || empty($email)){
            session()->setFlashdata('error', 'Complete todos los campos');
            return redirect()->to(base_url('/viewlogin'));
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

    }

}