<?php 

    namespace App\Controllers;

    use App\Models\tareaModel;
    use App\Models\subtareaModel;
    use App\Models\usuarioModel;

    class Tareas extends BaseController{

        public function __construct(){

            $session = session();

            if (!$session->has('id')) {
                redirect()->to('/login')->send();
                exit;
            }
        }

        public function index(){
            

            $userId = session()->get('id');
        
            if (!$userId) {
                return redirect()->to('/login');
            }
        
            $tareaModel = new tareaModel();
            $tareas = $tareaModel->getTareasPorUsuario($userId);

            return view('vistas_tarea/mis_tareas', ['tareas' => $tareas]);
        }

        public function crearTarea(){
            
            $tareaModel = new tareaModel();

            if (strtolower($this->request->getMethod()) === 'post') {

                $postData = $this->request->getPost();
                $postData['id_usuario'] = session()->get('id');

                if (!$tareaModel->validate($postData)) {
                    return redirect()->to('/mis_tareas')
                        ->withInput()
                        ->with('errors', $tareaModel->errors())
                        ->with('datos', $postData)
                        ->with('show_modal', true);

                }

                $idInsertado = $tareaModel->insertTarea($postData);

                if (!$idInsertado) {
                    return redirect()->to('/mis_tareas')
                        ->withInput()
                        ->with('errors', ['general' => 'No se pudo guardar la tarea. Intente nuevamente.'])
                        ->with('show_modal', true);
                }

                return redirect()->to('/mis_tareas')->with('success', 'Tarea guardada correctamente.');
            }

            return redirect()->to('/mis_tareas');
        }


        public function editarTarea($id){
            
            $tareaModel = new tareaModel();
            $tarea = $tareaModel->getTarea($id);

            if (!$tarea) {
                return redirect()->to('/mis_tareas')->with('error', 'Tarea no encontrada.');
            }

            if (strtolower($this->request->getMethod()) === 'post') {
                $postData = $this->request->getPost();
                $postData['id'] = $id;

                if (!$tareaModel->validate($postData)) {
                    return redirect()->to('/mis_tareas')
                        ->with('errorsEdit', $tareaModel->errors())
                        ->with('datosEdit', $postData)
                        ->with('show_modal_editar', true);
                }

                if (!$tareaModel->updateTarea($id, $postData)) {
                    return redirect()->to('/mis_tareas')
                        ->with('errorsEdit', $tareaModel->errors())
                        ->with('datosEdit', $postData)
                        ->with('show_modal_editar', true);
                }

                return redirect()->to('/mis_tareas')->with('success', 'Tarea actualizada correctamente.');
            }

            return redirect()->to('/mis_tareas')
                ->with('datosEdit', $tarea)
                ->with('id_tarea', $id)
                ->with('show_modal_editar', true);
        }

        public function borrarTarea($id){

            $tareaModel = new tareaModel();
            $session = session();

            $tarea = $tareaModel->getTarea($id);

            if (!$tarea) {
                return redirect()->to('/mis_tareas')->with('error', 'Tarea no encontrada.');
            }

            if ($tarea['id_usuario'] != $session->get('id')) {
                return redirect()->to('/mis_tareas')->with('error', 'No tienes permiso para eliminar esta tarea.');
            }

            if ($tareaModel->deleteTarea($id)) {
                return redirect()->to('/mis_tareas')->with('success', 'Tarea eliminada con éxito.');
            } else {
                return redirect()->to('/mis_tareas')->with('error', 'Error al eliminar la tarea.');
            }
        }

        public function tareasArchivadas(){
            
            $userId = session()->get('id');

            if (!$userId) {
                return redirect()->to('/login');
            }

            $tareaModel = new tareaModel();
            $tareas = $tareaModel
                ->where('archivada', 1)
                ->where('id_usuario', $userId)
                ->findAll();

            return view('vistas_tarea/tareas_archivadas', ['tareas' => $tareas]);
        }

        public function archivar($id){

            $tareaModel = new tareaModel();

            $tarea = $tareaModel->getTarea($id);
            if (!$tarea) {
                return redirect()->back()->with('error', 'Tarea no encontrada.');
            }

            if ($tareaModel->archivarTarea($id, true)) {
                return redirect()->to('/mis_tareas')->with('success', 'Tarea archivada correctamente');
            } else {
                return redirect()->back()->with('error', 'No se pudo archivar la tarea.');
            }
        }

        public function desarchivar($id){

            $tareaModel = new tareaModel();

            $tarea = $tareaModel->getTarea($id);
            if (!$tarea) {
                return redirect()->back()->with('error', 'Tarea no encontrada.');
            }

            if ($tareaModel->archivarTarea($id, false)) {
                return redirect()->to('/mis_tareas')->with('success', 'Tarea desarchivada correctamente');
            } else {
                return redirect()->back()->with('error', 'No se pudo desarchivar la tarea.');
            }
        }


        public function cambiarEstado($id){

            $nuevoEstado = $this->request->getPost('estado');
            $tareaModel = new tareaModel();
            $subtareaModel = new subtareaModel();

            $tarea = $tareaModel->find($id);
            if (!$tarea) {
                return redirect()->back()->with('errors', ['general' => 'Tarea no encontrada']);
            }

            if ($tarea['estado'] === 'completada' && $nuevoEstado === 'en_proceso') {
                session()->set('show_modal_subtarea', true);
                session()->set('tarea_id', $id);
            }

            $tareaModel->update($id, ['estado' => $nuevoEstado]);

            $tieneSubtareas = $subtareaModel->where('id_tarea', $id)->countAllResults() > 0;
            if ($tieneSubtareas) {
                $tareaModel->actualizarEstadoTarea($id, $subtareaModel);
            }

            return redirect()->back();
        }

    }