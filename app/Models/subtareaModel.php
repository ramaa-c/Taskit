<?php
namespace App\Models;

use CodeIgniter\Model;

class subtareaModel extends Model{
    protected $table = 'subtarea';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'descripcion',
        'estado',
        'prioridad',
        'fecha_vencimiento',
        'comentario'
    ];
}