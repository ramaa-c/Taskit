<?php

namespace App\Controllers;

use App\Models\usuarioModel;
use CodeIgniter\Session\Session;

class Auth extends BaseController{

    public function index(){

        return redirect()->to('/login');

    }

    public function login(){

        $usuarioModel = new usuarioModel();
        $session = session();
        
       if (strtolower($this->request->getMethod()) === 'post') {

            $validation = \Config\Services::validation();

            $rules = [
                'usuario' => 'required',
                'clave'   => 'required'
            ];

            $messages = [
                'usuario' => [
                    'required' => 'El nombre de usuario es obligatorio.'
                ],
                'clave' => [
                    'required' => 'La contraseña es obligatoria.'
                ]
            ];

            if (!$this->validate($rules, $messages)) {
                return view('auth/login', [
                    'errors' => $this->validator->getErrors(),
                    'datos'  => $this->request->getPost()
                ]);
            }

            $usuarioInput = $this->request->getPost('usuario');
            $claveInput   = $this->request->getPost('clave');

            $usuario = $usuarioModel->verificarCredenciales($usuarioInput, $claveInput);

            if (!$usuario) {
                return view('auth/login', [
                    'errors' => ['usuario' => 'Usuario o contraseña incorrectos.'],
                    'datos'  => $this->request->getPost()
                ]);
            }

            $session->set([
                'usuario'   => $usuario['usuario'],
                'id'        => $usuario['id'],
                'logged_in' => true
            ]);

            return redirect()->to('/mis_tareas')->with('success', 'Sesión iniciada correctamente');
        }

        return view('auth/login');
    }


    public function crearUsuario(){

        $usuarioModel = new usuarioModel();
        $session = session();

       if (strtolower($this->request->getMethod()) === 'post') {

            $postData = $this->request->getPost();

            if (!$usuarioModel->validate($postData)) {
                return redirect()->back()->withInput()->with('errors', $usuarioModel->errors());
            }
            unset($postData['confirmClave']);
            $idInsertado = $usuarioModel->insertUsuario($postData);
    
            if (!$idInsertado) {
                return redirect()->back()->withInput()->with('errors', $usuarioModel->errors());
            }            
    
            $usuario = $usuarioModel->obtenerUsuarioPorId($idInsertado);
    
            $session->set([
                'usuario'   => $usuario['usuario'],
                'id'        => $usuario['id'],
                'logged_in' => true
            ]);
    
            return redirect()->to('/mis_tareas')->with('success', 'Usuario creado con éxito.');    

        }

        return view('auth/registro');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}