<?php
    namespace App\Models;

    use CodeIgniter\Model;

        class tareaModel extends Model{
            protected $table = 'tarea';
            protected $primaryKey = 'id';
            protected $allowedFields = [
                'id_usuario',
                'asunto',
                'descripcion',
                'prioridad',
                'estado',
                'fecha_vencimiento',
                'fecha_recordatorio'
            ];
        }
?>