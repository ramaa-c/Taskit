<?php 

    namespace App\Controllers;

    use App\Models\tareaModel;
    use App\Models\subtareaModel;
    use App\Models\colaboradorModel;
    use App\Models\usuarioModel;

    class Tareas extends BaseController{

        public function index(){
            

            $userId = session()->get('id');
        
            if (!$userId) {
                return redirect()->to('auth/login');
            }
        
            $tareaModel = new TareaModel();
            $tareas = $tareaModel->where('id_usuario', $userId)->findAll();
        
            return view('tasks/mis_tareas', ['tareas' => $tareas]);
        }
        

        public function misSubtareas(){

            $userId = session()->get('id');
        
            if (!$userId) {
                return redirect()->to('auth/login');
            }
        
            $db = \Config\Database::connect();
        
            $builder = $db->table('subtarea');
            $builder->select('subtarea.*');
            $builder->join('tarea', 'subtarea.id_tarea = tarea.id');
            $builder->where('tarea.id_usuario', $userId);
        
            $query = $builder->get();
            $subtareas = $query->getResult();
        
            return view('tasks/mis_subtareas', ['subtareas' => $subtareas]);
        }
        
        public function subtareasAsignadas(){

            $userId = session()->get('id');

            if (!$userId) {
                return redirect()->to('auth/login');
            }

            $subtareaModel = new subtareaModel();

            $subtareas = $subtareaModel
                ->where('id_responsable', $userId)
                ->findAll();

            return view('tasks/subtareas_asignadas', ['subtareas' => $subtareas]);
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

        public function agregarSubtarea($idTarea){

            $usuarioModel = new usuarioModel();

            $usuarios = $usuarioModel->findAll();
        
            return view('tasks/addSubTarea', [
                'idTarea' => $idTarea,
                'usuarios' => $usuarios
            ]);
        }

        public function guardarSubTarea(){

            $validation = \Config\Services::validation();
        
            $reglas = [
                'descripcion' => 'required|min_length[4]',
                'estado' => 'required',
                'prioridad' => 'permit_empty',
                'fecha_vencimiento' => 'permit_empty|valid_date[Y-m-d]',
                'comentario' => 'permit_empty|max_length[255]',
                'responsable' => 'required|is_natural_no_zero'
            ];
        
            $mensajes = [
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
                'responsable' => [
                    'required' => 'Debe seleccionar un responsable.',
                    'is_natural_no_zero' => 'El responsable seleccionado no es válido.'
                ]
            ];
        
            if (!$this->validate($reglas, $mensajes)) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
        
            $fechaHoy = date('Y-m-d');
            $fechaVencimiento = $this->request->getPost('fecha_vencimiento');
        
            $erroresFecha = [];
        
            if (!empty($fechaVencimiento) && $fechaVencimiento <= $fechaHoy) {
                $erroresFecha['fecha_vencimiento'] = 'La fecha de vencimiento debe ser posterior a hoy.';
            }
        
            if (!empty($erroresFecha)) {
                return redirect()->back()->withInput()->with('errors', $erroresFecha);
            }
        
            $subTareaModel = new SubtareaModel();

            $idTarea = $this->request->getPost('id_tarea');
        
            $subTareaModel->insert([
                'id_tarea' => $idTarea,
                'descripcion' => $this->request->getPost('descripcion'),
                'estado' => $this->request->getPost('estado'),
                'prioridad' => $this->request->getPost('prioridad') ?: null,
                'fecha_vencimiento' => $fechaVencimiento ?: null,
                'comentario' => $this->request->getPost('comentario') ?: null,
                'id_responsable' => $this->request->getPost('responsable')
            ]);
        
            return redirect()->to('tareas/addTarea/' . $idTarea)->with('mensaje', 'Subtarea creada correctamente.');
        }
        

        public function verSubtareas($idTarea) {
            
            $subtareaModel = new subtareaModel();

            $db = \Config\Database::connect();
        
            $subtareas = $subtareaModel->where('id_tarea', $idTarea)->findAll();
        
            $responsable = $db->table('tarea_colaborador')
                ->select('usuarios.nombre, usuarios.id as id_usuario')
                ->join('usuarios', 'usuarios.id = colaboradores.id_usuario')
                ->where('colaboradores.id_tarea', $idTarea)
                ->get()
                ->getRowArray();
        
            return view('tasks/verSubtareas', [
                'subtareas' => $subtareas,
                'responsable' => $responsable
            ]);
        }
          
    }