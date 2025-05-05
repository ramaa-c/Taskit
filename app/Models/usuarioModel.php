<?php

namespace App\Models;

use CodeIgniter\Model;

class usuarioModel extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nombre',
                                'email',
                                'usuario',
                                'clave'];
}