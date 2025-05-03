<?php 

    namespace App\Controllers;

    use App\Models\tareaModel;

    class Tareas extends BaseController{

        public function index(){

            $tareaModel = new tareaModel();
            $tareas = $tareaModel->findAll();

            return view('tasks/tarea', ['tareas' => $tareas]);

        }

        public function agregarTarea(){
            return view('tasks/addTarea');
        }

        public function guardarTarea(){

            $tareaModel = new tareaModel();

            $validation = \Config\Services::validation();

            $rules = [
                'asunto'                =>   'required|min_length[4]',
                'descripcion'           =>   'required|min_length[4]',
                'prioridad'             =>   'required|in_list[baja, normal, alta]',
                'estado'                =>   'required|in_list[definido, en_proceso, completada]',
                'fecha_vencimiento'     =>   'required|valid_date[Y-m-d]',
                'fecha_recordatorio'    =>   'permit_empty|valid_date[Y-m-d]'
            ];

            $messages = [
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
                    'in_list' => 'Debe ser definido, en proceso o completada.'
                ],
                'fecha_vencimiento' => [
                    'required' => 'La fecha de vencimiento es obligatoria.',
                    'valid_date' => 'La fecha de vencimiento no es válida.',
                    'after_today' => 'La fecha de vencimiento debe ser posterior a hoy.'
                ],
                'fecha_recordatorio' => [
                    'valid_date' => 'La fecha de recordatorio no es válida.',
                    'after_today' => 'La fecha de recordatorio debe ser posterior a hoy.'
                ]
            ];

            if (!$this->validate($rules, $messages)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        
            $fechaHoy = date('Y-m-d');
            $fechaVencimiento = $this->request->getPost('fecha_vencimiento');
            $fechaRecordatorio = $this->request->getPost('fecha_recordatorio');
        
            $erroresFecha = [];
        
            if ($fechaVencimiento <= $fechaHoy) {
                $erroresFecha['fecha_vencimiento'] = 'La fecha de vencimiento debe ser posterior a hoy.';
            }
        
            if (!empty($fechaRecordatorio)) {
                if ($fechaRecordatorio <= $fechaHoy) {
                    $erroresFecha['fecha_recordatorio'] = 'La fecha de recordatorio debe ser posterior a hoy.';
                }
        
                if ($fechaRecordatorio >= $fechaVencimiento) {
                    $erroresFecha['fecha_recordatorio'] = 'La fecha de recordatorio debe ser anterior a la fecha de vencimiento.';
                }
            }
        
            if (!empty($erroresFecha)) {
                return redirect()->back()->withInput()->with('errors', $erroresFecha);
            }

            $tareaModel->insert([
                'id_usuario' => session()->get('id'),
                'asunto' => $this->request->getPost('asunto'),
                'descripcion' => $this->request->getPost('descripcion'),
                'prioridad' => $this->request->getPost('prioridad'),
                'estado' => $this->request->getPost('estado'),
                'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
                'fecha_recordatorio' => $this->request->getPost('fecha_recordatorio')
            ]);

            return redirect()->to('/tareas')->with('success', 'Tarea guardada correctamente.');

        }

        public function buscarTarea($id){

            $tareaModel = new tareaModel();
            $tarea = $tareaModel->find($id);

            if(!$tarea){
                return redirect()->to('/tareas')->with('error', 'Tarea no encontrada.');
            }
            return view('tasks/modTarea', ['datos' => $tarea]);
        }

        public function actualizarTarea(){

            $tareaModel = new tareaModel();

            $id = $this->request->getPost('id');

            $validation = \Config\Services::validation();

            $rules = [
                'asunto'                =>   'required|min_length[4]',
                'descripcion'           =>   'required|min_length[4]',
                'prioridad'             =>   'required|in_list[baja, normal, alta]',
                'estado'                =>   'required|in_list[definido, en_proceso, completada]',
                'fecha_vencimiento'     =>   'required|valid_date[Y-m-d]',
                'fecha_recordatorio'    =>   'permit_empty|valid_date[Y-m-d]'
            ];

            $messages = [
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
                    'in_list' => 'Debe ser definido, en proceso o completada.'
                ],
                'fecha_vencimiento' => [
                    'required' => 'La fecha de vencimiento es obligatoria.',
                    'valid_date' => 'La fecha de vencimiento no es válida.',
                    'after_today' => 'La fecha de vencimiento debe ser posterior a hoy.'
                ],
                'fecha_recordatorio' => [
                    'valid_date' => 'La fecha de recordatorio no es válida.',
                    'after_today' => 'La fecha de recordatorio debe ser posterior a hoy.'
                ]
            ];

            if (!$this->validate($rules, $messages)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $fechaHoy = date('Y-m-d');
            $fechaVencimiento = $this->request->getPost('fecha_vencimiento');
            $fechaRecordatorio = $this->request->getPost('fecha_recordatorio');

            $erroresFecha = [];

            if ($fechaVencimiento <= $fechaHoy) {
                $erroresFecha['fecha_vencimiento'] = 'La fecha de vencimiento debe ser posterior a hoy.';
            }

            if (!empty($fechaRecordatorio)) {
                if ($fechaRecordatorio <= $fechaHoy) {
                    $erroresFecha['fecha_recordatorio'] = 'La fecha de recordatorio debe ser posterior a hoy.';
                }

                if ($fechaRecordatorio >= $fechaVencimiento) {
                    $erroresFecha['fecha_recordatorio'] = 'La fecha de recordatorio debe ser anterior a la fecha de vencimiento.';
                }
            }

            if (!empty($erroresFecha)) {
                return redirect()->back()->withInput()->with('errors', $erroresFecha);
            }

            $tareaModel->update($id, [
                'asunto' => $this->request->getPost('asunto'),
                'descripcion' => $this->request->getPost('descripcion'),
                'prioridad' => $this->request->getPost('prioridad'),
                'estado' => $this->request->getPost('estado'),
                'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
                'fecha_recordatorio' => $this->request->getPost('fecha_recordatorio')
            ]);

            return redirect()->to('/tareas')->with('success', 'Tarea actualizada correctamente.');
        }

        public function borrarTarea($id){

            $tareaModel = new tareaModel();
            $tarea = $tareaModel->find($id);

            if(!$tarea){
                return redirect()->to('/tareas')->with('error', 'Tarea no encontrada.');
            }

            if($tareaModel->delete($id)){
                return redirect()->to('/tareas')->with('success', 'Tarea eliminada correctamente.');
            }else {
                return redirect()->to('/tareas')->with('error', 'No se eliminó la tarea.');
            }

        }
    
    }

?>