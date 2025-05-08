<?php 

    namespace App\Controllers;
    
    use App\Models\subtareaModel;
    use App\Models\usuarioModel;

    class Subtareas extends BaseController{


        public function misSubtareas(){

            $userId = session()->get('id');

            if (!$userId) {
                return redirect()->to('auth/login');
            }
        
            $subtareaModel = new subtareaModel();
            $subtareas = $subtareaModel->getSubtareasDeUsuario($userId);
        
            return view('tasks/mis_subtareas', ['subtareas' => $subtareas]);
        }

        public function subtareasAsignadas(){

            $userId = session()->get('id');

            if (!$userId) {
                return redirect()->to('auth/login');
            }
        
            $subtareaModel = new subtareaModel();
            $subtareas = $subtareaModel->getSubtareasAsignadas($userId);
        
            return view('tasks/subtareas_asignadas', ['subtareas' => $subtareas]);
        }

        public function guardarSubTarea($idTarea){

            $subTareaModel = new subtareaModel();

            $usuarioModel = new usuarioModel();

            $usuarios = $usuarioModel->getUsuarios();

            if ($this->request->getMethod() === 'POST') {

                $postData = $this->request->getPost();

                if (!$subTareaModel->validate($postData)) {
                    return view('tasks/addTarea', [
                        'errors' => $subTareaModel->errors(),
                        'datos' => $postData
                    ]);
                }
    
                $idInsertado = $subTareaModel->insertSubtarea($postData);
    
                if (!$idInsertado) {
                    return view('task/addSubtarea/', [
                        'errors' => ['general' => 'No se pudo guardar la subtarea.'],
                        'datos' => $postData
                    ]);
                }

                return redirect()->to('tareas/mis_subtareas/')->with('success', 'Tarea guardada correctamente.');    

            }

            return view('tasks/addSubTarea', [
                'idTarea' => $idTarea,
                'usuarios' => $usuarios
            ]);
                    
        }

    }