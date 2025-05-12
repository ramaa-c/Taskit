<?php

namespace App\Models;

use CodeIgniter\Model;

class SubtareaModel extends Model
{
    protected $table = 'subtarea';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_tarea',
        'descripcion',
        'estado',
        'prioridad',
        'fecha_vencimiento',
        'comentario',
        'id_responsable'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules  = [
        'descripcion' => 'required|min_length[4]',
        'estado' => 'required',
        'prioridad' => 'permit_empty',
        'fecha_vencimiento' => 'permit_empty|valid_date[Y-m-d]',
        'comentario' => 'permit_empty|max_length[255]',
        'id_responsable' => 'required|is_natural_no_zero'
    ];

    protected $validationMessages = [
        'descripcion' => [
            'required' => 'La descripción es obligatoria.',
            'min_length' => 'Debe tener al menos 4 caracteres.'
        ],
        'estado' => [
            'required' => 'El estado es obligatorio.'
        ],
        'fecha_vencimiento' => [
            'valid_date' => 'La fecha de vencimiento no es válida.'
        ],
        'comentario' => [
            'max_length' => 'El comentario no debe superar los 255 caracteres.'
        ],
        'id_responsable' => [
            'required' => 'Debe seleccionar un responsable.',
            'is_natural_no_zero' => 'El responsable seleccionado no es válido.'
        ]
    ];

    protected $skipValidation = false;

    public function getSubtarea($id){
        return $this->find($id);
    }

    public function insertSubtarea($data){

        if ($this->insert($data)) {
            return $this->getInsertID();
        }
        return false;
    }

    public function updateSubtarea($id, $data){
        return $this->update($id, $data);
    }
    
    public function deleteSubtarea($id){ 
        return $this->delete($id);
    }

    public function getSubtareasPorTarea($idTarea) {
        return $this->where('id_tarea', $idTarea)->findAll();
    }

    public function getSubtareasDeUsuario($idUsuario){

        return $this->select('subtarea.*')
                    ->join('tarea', 'subtarea.id_tarea = tarea.id')
                    ->where('tarea.id_usuario', $idUsuario)
                    ->findAll();
    }

    public function getSubtareasAsignadas($idResponsable){

        return $this->where('id_responsable', $idResponsable)
                    ->findAll();
    }

    public function updateEstado($id, $nuevoEstado){

        return $this->update($id, ['estado' => $nuevoEstado]);

    }

    public function todasSubtareasCompletadas($idTarea){

        return $this->where('id_tarea', $idTarea)->where('estado !=', 'completada')->countAllResults() === 0;

    }



}
