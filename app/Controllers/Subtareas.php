<?php 

    namespace App\Controllers;
    
    use App\Models\subtareaModel;
    use App\Models\usuarioModel;
    use App\Models\tareaModel;

    class Subtareas extends BaseController{

        public function __construct(){

            $session = session();

            if (!$session->has('id')) {
                redirect()->to('/login')->send();
                exit;
            }
        }

        public function misSubtareas(){

            $userId = session()->get('id');

            if (!$userId) {
                return redirect()->to('/login');
            }
        
            $subtareaModel = new subtareaModel();
            $subtareas = $subtareaModel->getSubtareasDeUsuario($userId);
        
            return view('vistas_subtarea/mis_subtareas', ['subtareas' => $subtareas]);
        }

        public function subtareasAsignadas(){

            $userId = session()->get('id');

            if (!$userId) {
                return redirect()->to('/login');
            }
        
            $subtareaModel = new subtareaModel();
            $subtareas = $subtareaModel->getSubtareasAsignadas($userId);
        
            return view('vistas_subtarea/subtareas_asignadas', ['subtareas' => $subtareas]);
        }

        public function crearSubTarea($idTarea){

            $subTareaModel = new subtareaModel();

            $usuarioModel = new usuarioModel();

            $usuarios = $usuarioModel->getUsuarios();

            if ($this->request->getMethod() === 'POST') {

                $postData = $this->request->getPost();

                if (!$subTareaModel->validate($postData)) {
                    return view('vistas_subtarea/nueva_subtarea', [
                        'errors' => $subTareaModel->errors(),
                        'datos' => $postData
                    ]);
                }
    
                $idInsertado = $subTareaModel->insertSubtarea($postData);
    
                if (!$idInsertado) {
                    return view('vistas_subtarea/nueva_subtarea/', [
                        'errors' => ['general' => 'No se pudo guardar la subtarea.'],
                        'datos' => $postData
                    ]);
                }

                return redirect()->to('subtareas/mis_subtareas/')->with('success', 'Tarea guardada correctamente.');    

            }

            return view('vistas_subtarea/nueva_subtarea', [
                'idTarea' => $idTarea,
                'usuarios' => $usuarios
            ]);
                    
        }

        public function editarSubtarea($idSubtarea){

            $subtareaModel = new subtareaModel();
            $usuarioModel = new usuarioModel();
            $tareaModel = new tareaModel();
            $subtarea = $subtareaModel->getSubtarea($idSubtarea);

            if(!$subtarea){
                return redirect()->to('subtareas/mis_subtareas/')->with('error','Subtarea no encontrada.');
            }

            $subtarea['nombre_responsable'] = $usuarioModel->obtenerNombrePorId($subtarea['id_responsable']) ?? 'Desconocido';

            if ($this->request->getMethod() === 'POST') {
                $postData = $this->request->getPost();

                if (!$subtareaModel->validate($postData)) {
                    return view('vistas_subtarea/editar_subtarea/', [
                        'datos' => array_merge($postData, ['nombre_responsable' => $subtarea['nombre_responsable']]),
                        'errors'=> $subtareaModel->errors()

                    ]);
                }

                if (!$subtareaModel->updateSubtarea($idSubtarea, $postData)) {
                    return view('vistas_subtarea/editar_subtarea/', [
                        'datos' => array_merge($postData, ['nombre_responsable' => $subtarea['nombre_responsable']]),
                        'errors'=> ['general'=> 'No se pudo actualizar la subtarea.']
                    ]);

                }

                if (isset($postData['estado']) && $postData['estado'] === 'completada') {
                    $tarea = $tareaModel->getTarea($subtarea['id_tarea']);

                    if ($tarea && $tarea['estado'] === 'definido') {
                        $tareaModel->update($tarea['id'], ['estado' => 'en_proceso']);
                    }
                }

                    return redirect()->to('subtareas/mis_subtareas/')->with('success','Subtarea actualizada correctamente.');

            }

            return view('vistas_subtarea/editar_subtarea/', ['datos' => $subtarea]);

        }

        public function borrarSubtarea($idSubtarea){

            $subtareaModel = new subtareaModel();
            $tareaModel = new tareaModel();
            $session = session();

            $subtarea = $subtareaModel->getSubtarea($idSubtarea);

            if (!$subtarea) {
                return redirect()->to('subtareas/mis_subtareas/')->with('error', 'Subtarea no encontrada.');
            }

            $tarea = $tareaModel->getTarea($subtarea['id_tarea']);

            if (!$tarea) {
                return redirect()->to('subtareas/mis_subtareas/')->with('error', 'Tarea asociada no encontrada.');
            }

            if ($tarea['id_usuario'] != $session->get('id')) {
                return redirect()->to('subtareas/mis_subtareas/')->with('error', 'No tienes permiso para eliminar esta subtarea.');
            }

            if ($subtareaModel->deleteSubtarea($idSubtarea)) {
                return redirect()->to('subtareas/mis_subtareas/')->with('success', 'Subtarea eliminada con éxito.');
            } else {
                return redirect()->to('subtareas/mis_subtareas/')->with('error', 'No se pudo eliminar la subtarea.');
            }
        }


        public function cambiarEstado($id){

            $nuevoEstado = $this->request->getPost('estado');
            $subtareaModel = new subtareaModel();
            $tareaModel = new tareaModel();

            $subtarea = $subtareaModel->getSubtarea($id);
            if (!$subtarea) {
                return redirect()->back()->with('error', 'Subtarea no encontrada.');
            }

            if (!in_array($nuevoEstado, ['en_proceso', 'completada'])) {
                return redirect()->back()->with('error', 'Estado no permitido.');
            }

            if (!$subtareaModel->actualizarEstado($id, $nuevoEstado)) {
                return redirect()->back()->with('error', 'No se pudo actualizar el estado.');
            }

            $tareaModel->actualizarEstadoTarea($subtarea['id_tarea'], $subtareaModel);

            return redirect()->back()->with('success', 'Estado actualizado correctamente.');
        }


    }