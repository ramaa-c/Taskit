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
        $session = session();
    
        $usuario = $this->request->getPost('usuario');
        $clave   = $this->request->getPost('clave');
    
        $data = $usuarioModel->where('usuario', $usuario)->first();
    
        if (!$data) {
            return redirect()->back()->withInput()->with('errors', [
                'usuario' => 'Usuario no encontrado.'
            ]);
        }
    
        if (!password_verify($clave, $data['clave'])) {
            return redirect()->back()->withInput()->with('errors', [
                'clave' => 'Contraseña incorrecta.'
            ]);
        }
    
        $session->set([
            'usuario'    => $data['usuario'],
            'id'         => $data['id'],
            'logged_in'  => true
        ]);
    
        return redirect()->to('/tareas');
    }
    

    public function registro()
    {
        return view('auth/registro');
    }

    public function guardarUsuario()
    {
        $usuarioModel = new usuarioModel();
        
        $validation = \Config\Services::validation();

        $rules = [
            'nombre'         => 'required|regex_match[/^[A-Za-zÀ-ÿ\s\.,\'-]+$/]',
            'email'          => 'required|valid_email|is_unique[usuario.email]',
            'usuario'        => 'required|is_unique[usuario.usuario]',
            'clave'          => 'required|min_length[8]|regex_match[/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).+$/]',
            'confirmClave'   => 'required|matches[clave]'
        ];

        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio.',
                'regex_match' => 'El nombre solo puede contener letras, espacios y signos básicos.'
            ],
            'email' => [
                'required' => 'El email es obligatorio.',
                'valid_email' => 'El email no es válido.',
                'is_unique' => 'El email ya está registrado.'
            ],
            'usuario' => [
                'required' => 'El nombre de usuario es obligatorio.',
                'is_unique' => 'Ese nombre de usuario ya está registrado.'
            ],
            'clave' => [
                'required' => 'La contraseña es obligatoria.',
                'min_length' => 'La contraseña debe tener al menos 8 caracteres.',
                'regex_match' => 'La contraseña debe contener una mayúscula, una minúscula, un número y un carácter especial.'
            ],
            'confirmClave' => [
                'required' => 'Debés confirmar la contraseña.',
                'matches' => 'Las contraseñas no coinciden.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }


        $usuarioModel->insert([
            'nombre' => $this->request->getPost('nombre'),
            'email' => $this->request->getPost('email'),
            'usuario' => $this->request->getPost('usuario'),
            'clave' => password_hash($this->request->getPost('clave'), PASSWORD_DEFAULT)
        ]);

        $usuario = $usuarioModel->where('usuario', $this->request->getPost('usuario'))->first();
        session()->set([
            'usuario'   => $usuario['usuario'],
            'id'        => $usuario['id'],
            'logged_in' => true
        ]);

        return redirect()->to('/tareas')->with('success', 'Usuario creado con éxito.');
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