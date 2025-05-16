<?php

namespace App\Models;

use CodeIgniter\Model;

class usuarioModel extends Model{

    protected $table = 'usuario';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre',
                                'email',
                                'usuario',
                                'clave',
                                'confirmClave'];
    
    protected $validationRules = [
        'nombre'         => 'required|regex_match[/^[A-Za-zÀ-ÿ\s\.,\'-]+$/]',
        'email'          => 'required|valid_email|is_unique[usuario.email]',
        'usuario'        => 'required|is_unique[usuario.usuario]',
        'clave'          => 'required|min_length[8]|regex_match[/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).+$/]'
        ];

    protected $validationMessages = [
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

    protected $skipValidation = false;

    public function insertUsuario($data)
    {
        if (isset($data['clave'])) {
            $data['clave'] = password_hash($data['clave'], PASSWORD_DEFAULT);
        }
    
        if ($this->insert($data)) {
            return $this->getInsertID();
        } else {
            return false;
        }
    }
    
    public function getUsuario ( $id ) { return $this->find($id);}

    public function getUsuarios () { return $this->findAll(); }

    public function updateUsuario($id, $data) {
        if ($this->update($id, $data)) {
            return true;
        } else {
            return false;
        }
    }    

    public function deleteUsuario ( $id ) { return $this->delete($id); }

    public function verificarCredenciales($usuario, $clave){

        $data = $this->where('usuario', $usuario)
             ->orWhere('email', $usuario)
             ->first();

        if (!$data) {
            return null;
        }

        if (password_verify($clave, $data['clave'])) {
            return $data;
        }

        return false;
    }

    public function obtenerNombrePorId($id){

        $usuario = $this->select('nombre')->find($id);
        return $usuario ? $usuario['nombre'] : null;
        
    }

    public function obtenerUsuarioPorId($id){

        return $this->where('id', $id)->first();
        
    }

}