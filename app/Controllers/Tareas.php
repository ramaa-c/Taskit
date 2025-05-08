<?php 

    namespace App\Controllers;

    use App\Models\tareaModel;
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

        public function crearTarea()
        {
            $tareaModel = new TareaModel();
        
            if ($this->request->getMethod() === 'post') {
                $postData = $this->request->getPost();
        
                $postData['id_usuario'] = session()->get('id');
        
                if (!$tareaModel->validate($postData)) {
                    return view('tasks/addTarea', [
                        'errors' => $tareaModel->errors(),
                        'datos' => $postData
                    ]);
                }
        
                $idInsertado = $tareaModel->insertTarea($postData);

                if (!$idInsertado) {
                    return view('tasks/addTarea', [
                        'errors' => ['general' => 'No se pudo guardar la tarea. Intente nuevamente.'],
                        'datos' => $postData
                    ]);
                }
        
                return redirect()->to('/tareas')->with('success', 'Tarea guardada correctamente.');
            }
        
            return view('tasks/addTarea');
        }        

        public function editarTarea($id)
        {
            $tareaModel = new TareaModel();
            $tarea = $tareaModel->find($id);
        
            if (!$tarea) {
                return redirect()->to('/tareas')->with('error', 'Tarea no encontrada.');
            }
        
            if ($this->request->getMethod() === 'post') {
                $postData = $this->request->getPost();
        
                if (!$tareaModel->validate($postData)) {
                    return view('tasks/modTarea', [
                        'datos' => $postData,
                        'errors' => $tareaModel->errors()
                    ]);
                }
        
                if (!$tareaModel->updateTarea($id, $postData)) {
                    return view('tasks/modTarea', [
                        'datos' => $postData,
                        'errors' => ['general' => 'No se pudo actualizar la tarea.']
                    ]);
                }
        
                return redirect()->to('/tareas')->with('success', 'Tarea actualizada correctamente.');
            }
        
            return view('tasks/modTarea', ['datos' => $tarea]);
        }

        public function borrarTarea($id){

            $tareaModel = new tareaModel();

            if($tareaModel->deleteTarea($id)){
                return redirect()->to('/tareas')->with('success','Tarea eliminada con exito.');
            }else{
                return redirect()->to('/tareas')->with('errors', 'Error al elimanr la tarea.') ;
            
            }

        }
    }