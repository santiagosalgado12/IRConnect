<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Usuario extends Seeder
{
    public function run()
    {
        $username="admin";
        $mail="admin@gmail.com";
        $password=password_hash("123",PASSWORD_DEFAULT);

        $data = [
            'nombre_usuario' => $username,
            'email'    => $mail,
            'hash_contrasena' => $password,
            'ID_permiso' => 1,
            'ID_administrador' => null
        ];


        // Using Query Builder
        $this->db->table('usuarios')->set('fecha_creacion', 'CURRENT_TIMESTAMP', false)->insert($data);
    }
}
