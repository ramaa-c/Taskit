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
        
            $tareaModel = new TareaModel();
            $tareas = $tareaModel->where('id_usuario', $userId)->findAll();
        
            return view('vistas_tarea/mis_tareas', ['tareas' => $tareas]);
        }

        public function crearTarea(){

            $tareaModel = new TareaModel();
        
            if ($this->request->getMethod() === 'post') {
                $postData = $this->request->getPost();
        
                $postData['id_usuario'] = session()->get('id');
        
                if (!$tareaModel->validate($postData)) {
                    return view('vistas_tarea/nueva_tarea', [
                        'errors' => $tareaModel->errors(),
                        'datos' => $postData
                    ]);
                }
        
                $idInsertado = $tareaModel->insertTarea($postData);

                if (!$idInsertado) {
                    return view('vistas_tarea/nueva_tarea', [
                        'errors' => ['general' => 'No se pudo guardar la tarea. Intente nuevamente.'],
                        'datos' => $postData
                    ]);
                }
        
                return redirect()->to('/tareas')->with('success', 'Tarea guardada correctamente.');
            }
        
            return view('vistas_tarea/nueva_tarea');
        }        

        public function editarTarea($id){
            
            $tareaModel = new TareaModel();
            $tarea = $tareaModel->getTarea($id);
        
            if (!$tarea) {
                return redirect()->to('/tareas')->with('error', 'Tarea no encontrada.');
            }
        
            if ($this->request->getMethod() === 'post') {
                $postData = $this->request->getPost();
        
                if (!$tareaModel->validate($postData)) {
                    return view('vistas_tarea/editar_tarea', [
                        'datos' => $postData,
                        'errors' => $tareaModel->errors()
                    ]);
                }
        
                if (!$tareaModel->updateTarea($id, $postData)) {
                    return view('vistas_tarea/editar_tarea', [
                        'datos' => $postData,
                        'errors' => ['general' => 'No se pudo actualizar la tarea.']
                    ]);
                }
        
                return redirect()->to('/tareas')->with('success', 'Tarea actualizada correctamente.');
            }
        
            return view('vistas_tarea/editar_tarea', ['datos' => $tarea]);
        }

        public function borrarTarea($id){

            $tareaModel = new tareaModel();

            if($tareaModel->deleteTarea($id)){
                return redirect()->to('/tareas')->with('success','Tarea eliminada con exito.');
            }else{
                return redirect()->to('/tareas')->with('error', 'Error al elimanr la tarea.') ;
            
            }

        }

        public function archivar($id){

            $tareaModel = new tareaModel();

            $tarea = $tareaModel->getTarea($id);
            if (!$tarea) {
                return redirect()->back()->with('error', 'Tarea no encontrada.');
            }

            if ($tareaModel->archivarTarea($id)) {
                return redirect()->to('/tareas')->with('success', 'Tarea archivada correctamente');
            } else {
                return redirect()->back()->with('error', 'No se pudo archivar la tarea.');
            }
        }

        public function cambiarEstado($id){

            $nuevoEstado = $this->request->getPost('estado');

            $tareaModel = new tareaModel();
            $subtareaModel = new subtareaModel();

            $tarea = $tareaModel->getTarea($id);
            if (!$tarea) {
                return redirect()->back()->with('error', 'Tarea no encontrada.');
            }

            $estadosPermitidos = ['definido', 'en_proceso', 'completada'];
            if (!in_array($nuevoEstado, $estadosPermitidos)) {
                return redirect()->back()->with('error', 'Estado no permitido.');
            }

            $todasCompletadas = $subtareaModel->todasSubtareasCompletadas($id);

            $estadoCalculado = $todasCompletadas ? 'completada' : 'en_proceso';

            if ($nuevoEstado === 'completada' && !$todasCompletadas) {
                return redirect()->back()->with('error', 'No se puede marcar la tarea como completada mientras haya subtareas pendientes.');
            }

            if (!$tareaModel->updateTarea($id, ['estado' => $nuevoEstado])) {
                return redirect()->back()->with('error', 'No se pudo actualizar el estado.');
            }

            $tareaModel->actualizarEstadoTarea($id, $subtareaModel);

            return redirect()->back()->with('success', 'Estado actualizado correctamente.');
        }


    }