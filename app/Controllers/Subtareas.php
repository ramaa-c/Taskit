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

        public function misSubtareas($idTarea)
        {
            $userId = session()->get('id');

            if (!$userId) {
                return redirect()->to('/login');
            }

            $tareaModel = new tareaModel();
            $tarea = $tareaModel->where('id', $idTarea)
                                ->where('id_usuario', $userId)
                                ->first();

            if (!$tarea) {
                return redirect()->to('mis_tareas')->with('error', 'No se encontró la tarea.');
            }

            session()->set('id_tarea', $idTarea);

            $subtareaModel = new subtareaModel();
            $usuarioModel = new usuarioModel();

            $usuarios = $usuarioModel->getUsuarios();
            $subtareas = $subtareaModel->getSubtareasPorUsuarioYTarea($userId, $idTarea);

            return view('vistas_subtarea/mis_subtareas', [
                'subtareas' => $subtareas,
                'idTarea'   => $idTarea,
                'usuarios'  => $usuarios
            ]);
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

            $subTareaModel = new SubtareaModel();

            if (strtolower($this->request->getMethod()) === 'post') {
                $postData = $this->request->getPost();

                if (!$subTareaModel->validate($postData)) {
                    return redirect()->to('subtareas/mis_subtareas/' . $idTarea)
                                    ->withInput()
                                    ->with('errors', $subTareaModel->errors())
                                    ->with('datos', $postData)
                                    ->with('show_modal', true);
                }

                $idInsertado = $subTareaModel->insertSubtarea($postData);

                if (!$idInsertado) {
                    return redirect()->to('subtareas/mis_subtareas/' . $idTarea)
                                    ->withInput()
                                    ->with('errors', ['general' => 'No se pudo guardar la subtarea.'])
                                    ->with('datos', $postData)
                                    ->with('show_modal', true);
                }

                return redirect()->to('subtareas/mis_subtareas/' . $idTarea)
                                ->with('success', 'Subtarea creada correctamente.');
            }

            return redirect()->to('subtareas/mis_subtareas/' . $idTarea);
        }


        public function editarSubtarea($idSubtarea){

            $subtareaModel = new subtareaModel();
            $usuarioModel = new usuarioModel();
            $tareaModel = new tareaModel();
            $subtarea = $subtareaModel->getSubtarea($idSubtarea);
            $idTarea = $subtareaModel->obtenerIdTareaPorSubtarea($idSubtarea);

            if(!$subtarea){
                return redirect()->to('subtareas/mis_subtareas/' . $idTarea)->with('error','Subtarea no encontrada.');
            }

            $subtarea['nombre_responsable'] = $usuarioModel->obtenerNombrePorId($subtarea['id_responsable']) ?? 'Desconocido';

            if (strtolower($this->request->getMethod()) === 'post') {
                $postData = $this->request->getPost();

                if (!$subtareaModel->validate($postData)) {
                    return redirect()->to('subtareas/mis_subtareas/' . $idTarea)
                        ->with('errorsEditSub', $subtareaModel->errors())
                        ->with('datosEditSub', array_merge($postData, ['id' => $idSubtarea,'nombre_responsable' => $subtarea['nombre_responsable']]))
                        ->with('show_modal_editar', true);
                }

                if (!$subtareaModel->updateSubtarea($idSubtarea, $postData)) {
                    return redirect()->to('subtareas/mis_subtareas/' . $idTarea)
                        ->with('errorsEdit', $subtareaModel->errors())
                        ->with('datosEditSub', array_merge($postData, ['id' => $idSubtarea,'nombre_responsable' => $subtarea['nombre_responsable']]))
                        ->with('show_modal_editar', true);
                }

                    return redirect()->to('subtareas/mis_subtareas/' . $idTarea)->with('success','Subtarea actualizada correctamente.');

            }
            session()->remove(['datosEditSub', 'show_modal_editar', 'errorsEditSub']);
            return view('vistas_subtarea/editar_subtarea', ['datos' => $subtarea]);

        }

        public function borrarSubtarea($idSubtarea){

            $subtareaModel = new subtareaModel();
            $tareaModel = new tareaModel();
            $idTarea = $subtareaModel->obtenerIdTareaPorSubtarea($idSubtarea);
            $session = session();

            $subtarea = $subtareaModel->getSubtarea($idSubtarea);

            if (!$subtarea) {
                return redirect()->to('subtareas/mis_subtareas/' . $idTarea)->with('error', 'Subtarea no encontrada.');
            }

            $tarea = $tareaModel->getTarea($subtarea['id_tarea']);

            if (!$tarea) {
                return redirect()->to('subtareas/mis_subtareas/' . $idTarea)->with('error', 'Tarea asociada no encontrada.');
            }

            if ($tarea['id_usuario'] != $session->get('id')) {
                return redirect()->to('subtareas/mis_subtareas/' . $idTarea)->with('error', 'No tienes permiso para eliminar esta subtarea.');
            }

            if ($subtareaModel->deleteSubtarea($idSubtarea)) {
                return redirect()->to('subtareas/mis_subtareas/' . $idTarea)->with('success', 'Subtarea eliminada con éxito.');
            } else {
                return redirect()->to('subtareas/mis_subtareas/' . $idTarea)->with('error', 'No se pudo eliminar la subtarea.');
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

            if (!$subtareaModel->updateEstado($id, $nuevoEstado)) {
                return redirect()->back()->with('error', 'No se pudo actualizar el estado.');
            }

            $tareaModel->actualizarEstadoTarea($subtarea['id_tarea'], $subtareaModel);

            return redirect()->back()->with('success', 'Estado actualizado correctamente.');
        }

        public function validarSubtareasCompletas() {
            $idTarea = $this->request->getPost('id_tarea');
            $subtareaModel = new subtareaModel();

            $todoCompletado = $subtareaModel->todasSubtareasCompletadas($idTarea);

            return $this->response->setJSON(['todoCompletado' => $todoCompletado]);
        }

    }