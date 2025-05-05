<?php

namespace App\Models;

use CodeIgniter\Model;

class colaboradorModel extends Model
{
    protected $table = 'tarea_colaborador';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_tarea',
        'id_usuario'];
}
