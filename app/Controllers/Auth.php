<?php

namespace App\Controllers;

use App\Models\usuarioModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        $validation = \Config\Services::validation();
    
        $rules = [
            'usuario' => 'required',
            'clave'   => 'required'
        ];
    
        $messages = [
            'usuario' => [
                'required'    => 'El nombre de usuario es obligatorio.'
            ],
            'clave' => [
                'required'    => 'La contraseña es obligatoria.'
            ]
        ];
    
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        $usuarioModel = new usuarioModel();
    
        $usuarioInput = $this->request->getPost('usuario');
        $claveInput   = $this->request->getPost('clave');
    
        $usuario = $usuarioModel->verificarCredenciales($usuarioInput, $claveInput);
    
        
        if (!$usuario) {
            return redirect()->back()->withInput()->with('errors', [
                'usuario' => 'Usuario no encontrado.'
            ]);
        }
        
        if (!$usuario) {
            return redirect()->back()->withInput()->with('errors', [
                'clave' => 'Contraseña incorrecta.'
            ]);
        }
    
        session()->set([
            'usuario'    => $usuario['usuario'],
            'id'         => $usuario['id'],
            'logged_in'  => true
        ]);
    
        return redirect()->to('/tareas');
    }

    public function guardarUsuario()
    {
        $usuarioModel = new usuarioModel();

        if ($this->request->getMethod() == 'POST') {

            $postData = $this->request->getPost();


            if (!$usuarioModel->validate($postData)){
                return view('auth/registro/', [
                    'errors' => $usuarioModel->errors(),
                    'datos' => $postData
                ]);
            }
    
            $idInsertado = $usuarioModel->insertUsuario($postData);
    
            if (!$idInsertado) {
                return view('auth/registro/', [
                    'errors' => $usuarioModel->errors(),
                    'datos' => $postData
                ]);
            }
    
            $usuario = $usuarioModel->find($idInsertado);
    
            session()->set([
                'usuario'   => $usuario['usuario'],
                'id'        => $usuario['id'],
                'logged_in' => true
            ]);
    
            return redirect()->to('/tareas')->with('success', 'Usuario creado con éxito.');    

        }

        return view('auth/registro/');
}

    public function tarea()
    {
        return view('tasks/tarea');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}