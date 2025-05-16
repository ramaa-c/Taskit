<?php

namespace App\Models;

use CodeIgniter\Model;

class TareaModel extends Model{

    protected $table = 'tarea';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_usuario',
        'asunto',
        'descripcion',
        'prioridad',
        'estado',
        'fecha_vencimiento',
        'fecha_recordatorio',
        'archivada',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_creacion';
    protected $updatedField  = '';

    protected $validationRules = [
        'asunto'                => 'required|min_length[4]',
        'descripcion'           => 'required|min_length[4]',
        'prioridad'             => 'required|in_list[baja,normal,alta]',
        'estado'                => 'required|in_list[en_proceso,completada]',
        'fecha_vencimiento'     => 'required|valid_date[Y-m-d]|after_today',
        'fecha_recordatorio'    => 'permit_empty|valid_date[Y-m-d]|recordatorio_valido',
    ];

    protected $validationMessages = [
        'asunto' => [
            'required' => 'El asunto es obligatorio.',
            'min_length' => 'Debe tener al menos 4 caracteres.'
        ],
        'descripcion' => [
            'required' => 'La descripción es obligatoria.',
            'min_length' => 'Debe tener al menos 4 caracteres.'
        ],
        'prioridad' => [
            'required' => 'La prioridad es obligatoria.',
            'in_list' => 'Debe ser baja, normal o alta.'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio.',
            'in_list' => 'Debe ser en proceso o completada.'
        ],
        'fecha_vencimiento' => [
            'required' => 'La fecha de vencimiento es obligatoria.',
            'valid_date' => 'La fecha de vencimiento no es válida.',
            'after_today' => 'La fecha de vencimiento debe ser posterior a hoy.'
        ],
        'fecha_recordatorio' => [
            'valid_date' => 'La fecha de recordatorio no es válida.',
            'recordatorio_valido' => 'La fecha de recordatorio debe estar entre hoy y la fecha de vencimiento.'
        ]

    ];
    protected $skipValidation = false;

    public function getTareasPorUsuario($idUsuario){
        
        return $this->where('id_usuario', $idUsuario)
                    ->where('archivada',0)
                    ->findAll();
    }

    public function getTarea ( $id ) { return $this->find($id);}

    public function insertTarea($data) { 
        if ($this->insert($data)) {
            return $this->getInsertID();
        } else {
            return false;
        }
    }

    public function updateTarea($id, $data) {
        if ($this->update($id, $data)) {
            return true;
        } else {
            return false;
        }
    }    

    public function deleteTarea ( $id ) { return $this->delete($id); }

    public function archivarTarea($id, $archivar = true){

        $tarea = $this->find($id);

        if (!$tarea) {
            return false;
        }

        if ($archivar) {
            if ($tarea['estado'] !== 'completada') {
                return false;
            }
            return $this->update($id, ['archivada' => 1]);
        } else {
            return $this->update($id, ['archivada' => 0]);
        }
    }

    public function updateEstado($id, $nuevoEstado){

        return $this->update($id, ['estado' => $nuevoEstado]);

    }

    public function actualizarEstadoTarea($idTarea, $subtareaModel) {

        $tarea = $this->find($idTarea);
        if (!$tarea) return false;

        $totalSubtareas = $subtareaModel->where('id_tarea', $idTarea)->countAllResults();

        if ($totalSubtareas === 0) {
            return true;
        }

        $todasCompletadas = $subtareaModel->todasSubtareasCompletadas($idTarea);
        $hayEnProceso = $subtareaModel->haySubtareasEnProceso($idTarea);

        $nuevoEstado = $tarea['estado']; // por defecto, no cambiar

        if ($todasCompletadas) {
            $nuevoEstado = 'completada';
        } elseif ($hayEnProceso) {
            $nuevoEstado = 'en_proceso';
        }

        if ($tarea['estado'] !== $nuevoEstado) {
            return $this->update($idTarea, ['estado' => $nuevoEstado]);
        }

        return true;
    }

}