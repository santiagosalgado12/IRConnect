<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class EspidFilter implements FilterInterface{

    public function before(RequestInterface $request, $arguments = null){
        if(!session()->get('esp_id')){
            return redirect()->to(base_url());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

    }

}