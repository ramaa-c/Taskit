<?= view('layout/header', ['titulo' => 'Inicio']) ?>
<?= view('layout/navbar') ?>
<div class="contenedor-principal">
    <?= view('layout/sidebar') ?>
    <main class="contenido-principal">
        
        <?php
        $errors = session('errors') ?? [];
        $datos = session('datos') ?? [];
        $datosEdit = session('datosEdit') ?? [];
        $errorsEdit = session('errorsEdit') ?? [];
        ?>

        <?php
        helper('tareas_helper');
        $tareas = isset($tareas) && is_array($tareas) ? $tareas : [];

        if (!empty($tareas)) {
            $ordenarPor = $_GET['orden'] ?? 'fecha_creacion';
            $direccion = $_GET['dir'] ?? 'asc';
            $invertir = $direccion === 'desc' ? 'asc' : 'desc';

            usort($tareas, function ($a, $b) use ($ordenarPor, $direccion) {
                if ($ordenarPor === 'prioridad') {
                    $prioridades = ['alta' => 1, 'normal' => 2, 'baja' => 3];
                    $cmp = $prioridades[strtolower($a['prioridad'])] <=> $prioridades[strtolower($b['prioridad'])];
                    if ($cmp === 0 && strtolower($a['prioridad']) === 'baja') {
                        $cmp = strtotime($a['fecha_creacion']) <=> strtotime($b['fecha_creacion']);
                    }
                } else {
                    $cmp = strtotime($a[$ordenarPor]) <=> strtotime($b[$ordenarPor]);
                }
                return $direccion === 'asc' ? $cmp : -$cmp;
            });
        }
        ?>

        <?php if (!empty($tareas)) : ?>
            <button type="button" class="btn-nueva-tarea" onclick="abrirModalTarea()">+ Nueva Tarea</button>

            <div class="tareas-container">
                <table class="tabla-tareas">
                    <thead>
                        <tr>
                            <th>Asunto</th>
                            <th>Descripción</th>
                            <th><?= orden_link('prioridad', $ordenarPor, $direccion, 'Prioridad') ?></th>
                            <th><?= orden_link('estado', $ordenarPor, $direccion, 'Estado') ?></th>
                            <th><?= orden_link('fecha_vencimiento', $ordenarPor, $direccion, 'Vencimiento') ?></th>
                            <th><?= orden_link('fecha_recordatorio', $ordenarPor, $direccion, 'Recordatorio') ?></th>
                            <th><?= orden_link('fecha_creacion', $ordenarPor, $direccion, 'Creación') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tareas as $tarea) :
                            $prioridad = strtolower($tarea['prioridad'] ?? '');
                            $estado = ucfirst(str_replace('_', ' ', strtolower($tarea['estado'] ?? '')));
                            $descripcionCorta = strlen($tarea['descripcion'] ?? '') > 40
                                ? substr($tarea['descripcion'], 0, 40) . '...'
                                : $tarea['descripcion'];
                            $clasePrioridad = 'prioridad-badge ';
                            if ($prioridad === 'alta') $clasePrioridad .= 'prioridad-alta';
                            elseif ($prioridad === 'normal') $clasePrioridad .= 'prioridad-media';
                            elseif ($prioridad === 'baja') $clasePrioridad .= 'prioridad-baja';
                        ?>
                            <tr>
                                <td><?= esc($tarea['asunto']) ?></td>
                                <td>
                                    <?= esc($descripcionCorta) ?>
                                    <?php if (strlen($tarea['descripcion']) > 40): ?>
                                        <button onclick="mostrarDescripcion('<?= esc($tarea['descripcion']) ?>')" class="btn-ver-mas">Ver más</button>
                                    <?php endif; ?>
                                </td>
                                <td><span class="<?= $clasePrioridad ?>"><?= esc(ucfirst($prioridad)) ?></span></td>
                                <td><?= esc($estado) ?></td>
                                <td><?= esc($tarea['fecha_vencimiento']) ?></td>
                                <td><?= esc($tarea['fecha_recordatorio'] ?? 'No definido') ?></td>
                                <td><?= esc($tarea['fecha_creacion']) ?></td>
                                <td class="acciones-tarea">
                                    <button type="button" class="accion-icono" title="Editar"
                                        onclick='abrirModalEditarTarea(<?= json_encode($tarea) ?>)'>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <a href="<?= base_url('tareas/borrar_tarea/' . $tarea['id']) ?>" class="accion-icono" title="Borrar">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                    <a href="<?= site_url('subtareas/mis_subtareas/' . $tarea['id']) ?>" class="accion-texto">
                                        Subtareas
                                    </a>
                                    <?php if (($tarea['estado'] ?? '') === 'completada' && !($tarea['archivada'] ?? false)) : ?>
                                        <form action="<?= base_url('tareas/archivar/' . $tarea['id']) ?>" method="post" class="form-archivar">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="accion-icono" title="Archivar">
                                                <i class="fas fa-archive"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p class="sin-tareas">No hay tareas aún. <a href="<?= base_url('tareas/nueva_tarea') ?>" class="btn-nueva-tarea">Crear primera tarea</a></p>
        <?php endif; ?>

        <div id="modal-descripcion" onclick="cerrarModal()">
            <div id="modal-contenido"></div>
        </div>

        <script>
            function mostrarDescripcion(descripcion) {
                const modal = document.getElementById('modal-descripcion');
                const contenido = document.getElementById('modal-contenido');
                contenido.textContent = descripcion;
                modal.style.display = 'flex';
            }

            function cerrarModal() {
                document.getElementById('modal-descripcion').style.display = 'none';
            }
        </script>

        <div id="modal-tarea" class="modal-nueva_tarea">
            <div class="modal-contenido-nueva_tarea">

                <h2>Crear Tarea</h2>

                <?php if (!empty($errors['general'])): ?>
                    <p style="color: red;"><?= esc($errors['general']) ?></p>
                <?php endif; ?>

                <form action="<?= site_url('tareas/nueva_tarea') ?>" method="post">
                    <div>
                        <label for="asunto">Asunto: </label>
                        <input type="text" name="asunto" id="asunto" value="<?= esc(old('asunto', $datos['asunto'] ?? '')) ?>" required>
                        <?php if (!empty($errors['asunto'])): ?>
                            <small class="text-danger"><?= esc($errors['asunto']) ?></small>
                        <?php endif; ?>
                    </div>
                    <br>

                    <div>
                        <label for="descripcion">Descripción: </label>
                        <input type="text" minlength="4" name="descripcion" id="descripcion" value="<?= esc(old('descripcion', $datos['descripcion'] ?? '')) ?>" required>
                        <?php if (!empty($errors['descripcion'])): ?>
                            <small class="text-danger"><?= esc($errors['descripcion']) ?></small>
                        <?php endif; ?>
                    </div>
                    <br>

                    <div>
                        <label for="prioridad">Prioridad: </label>
                        <select name="prioridad" id="prioridad" required>
                            <option value="" disabled <?= !isset($datos['prioridad']) ? 'selected' : '' ?>>Seleccione</option>
                            <option value="baja" <?= (old('prioridad', $datos['prioridad'] ?? '') === 'baja') ? 'selected' : '' ?>>Baja</option>
                            <option value="normal" <?= (old('prioridad', $datos['prioridad'] ?? '') === 'normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="alta" <?= (old('prioridad', $datos['prioridad'] ?? '') === 'alta') ? 'selected' : '' ?>>Alta</option>
                        </select>
                        <?php if (!empty($errors['prioridad'])): ?>
                            <small class="text-danger"><?= esc($errors['prioridad']) ?></small>
                        <?php endif; ?>
                    </div>
                    <br>

                    <input type="hidden" name="estado" value="en_proceso">

                    <div>
                        <label for="fecha_vencimiento">Fecha de vencimiento: </label>
                        <input type="date" min="<?= date('Y-m-d') ?>" name="fecha_vencimiento" id="fecha_vencimiento"
                            value="<?= esc(old('fecha_vencimiento', $datos['fecha_vencimiento'] ?? '')) ?>" required>
                        <?php if (!empty($errors['fecha_vencimiento'])): ?>
                            <small class="text-danger"><?= esc($errors['fecha_vencimiento']) ?></small>
                        <?php endif; ?>
                    </div>
                    <br>

                    <div>
                        <label for="fecha_recordatorio">Fecha de recordatorio: </label>
                        <input type="date" min="<?= date('Y-m-d') ?>" name="fecha_recordatorio" id="fecha_recordatorio"
                            value="<?= esc(old('fecha_recordatorio', $datos['fecha_recordatorio'] ?? '')) ?>">
                        <?php if (!empty($errors['fecha_recordatorio'])): ?>
                            <small class="text-danger"><?= esc($errors['fecha_recordatorio']) ?></small>
                        <?php endif; ?>
                    </div>
                    <br>

                    <div><button type="submit">Crear</button></div>
                </form>
            </div>
        </div>

        <div id="modal-editar-tarea" class="modal-editar-tarea">
            <div class="modal-contenido-editar-tarea">

                <h2>Editar Tarea</h2>

                <form action="<?= site_url('tareas/editar_tarea/' . ($datosEdit['id'] ?? '')) ?>" method="post">
                    <input type="hidden" name="id" id="editar-id" value="<?= esc(old('id', $datosEdit['id'] ?? '')) ?>">

                    <div>
                        <label for="editar-asunto">Asunto: </label>
                        <input type="text" name="asunto" id="editar-asunto" value="<?= esc(old('asunto', $datosEdit['asunto'] ?? '')) ?>" required>
                        <?php if (session('errorsEdit.asunto')): ?>
                            <small class="text-danger"><?= esc(session('errorsEdit.asunto')) ?></small>
                        <?php endif; ?>
                    </div><br>

                    <div>
                        <label for="editar-descripcion">Descripción: </label>
                        <input type="text" name="descripcion" id="editar-descripcion" value="<?= esc(old('descripcion', $datosEdit['descripcion'] ?? '')) ?>" required>
                        <?php if (session('errorsEdit.descripcion')): ?>
                            <small class="text-danger"><?= esc(session('errorsEdit.descripcion')) ?></small>
                        <?php endif; ?>
                    </div><br>

                    <div>
                        <label for="editar-prioridad">Prioridad: </label>
                        <select name="prioridad" id="editar-prioridad" required>
                            <option value="baja" <?= old('prioridad', $datosEdit['prioridad'] ?? '') === 'baja' ? 'selected' : '' ?>>Baja</option>
                            <option value="normal" <?= old('prioridad', $datosEdit['prioridad'] ?? '') === 'normal' ? 'selected' : '' ?>>Normal</option>
                            <option value="alta" <?= old('prioridad', $datosEdit['prioridad'] ?? '') === 'alta' ? 'selected' : '' ?>>Alta</option>
                        </select>
                        <?php if (session('errorsEdit.prioridad')): ?>
                            <small class="text-danger"><?= esc(session('errorsEdit.prioridad')) ?></small>
                        <?php endif; ?>
                    </div><br>

                    <div>
                        <label for="editar-fecha_vencimiento">Fecha de vencimiento: </label>
                        <input type="date" name="fecha_vencimiento" id="editar-fecha_vencimiento" value="<?= esc(old('fecha_vencimiento', $datosEdit['fecha_vencimiento'] ?? '')) ?>" required>
                        <?php if (session('errorsEdit.fecha_vencimiento')): ?>
                            <small class="text-danger"><?= esc(session('errorsEdit.fecha_vencimiento')) ?></small>
                        <?php endif; ?>
                    </div><br>

                    <div>
                        <label for="editar-fecha_recordatorio">Fecha de recordatorio: </label>
                        <input type="date" name="fecha_recordatorio" id="editar-fecha_recordatorio" value="<?= esc(old('fecha_recordatorio', $datosEdit['fecha_recordatorio'] ?? '')) ?>">
                        <?php if (session('errorsEdit.fecha_recordatorio')): ?>
                            <small class="text-danger"><?= esc(session('errorsEdit.fecha_recordatorio')) ?></small>
                        <?php endif; ?>
                    </div><br>

                    <button type="submit">Guardar Cambios</button>
                </form>
            </div>
        </div>


        <script>
            function abrirModalTarea() {
                document.getElementById('modal-tarea').style.display = 'flex';
            }

            function cerrarModalTarea() {
                document.getElementById('modal-tarea').style.display = 'none';
            }
            window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('modal-tarea')) {
                cerrarModalTarea();
            }
        });
        </script>

        <?php if (session('show_modal')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    abrirModalTarea();
                });
            </script>
        <?php endif; ?>

        <script>
        function abrirModalEditarTarea(tarea) {
            const form = document.querySelector('#modal-editar-tarea form');
            form.action = `<?= site_url('tareas/editar_tarea/') ?>${tarea.id}`;
            
            document.getElementById('editar-id').value = tarea.id;
            document.getElementById('editar-asunto').value = tarea.asunto;
            document.getElementById('editar-descripcion').value = tarea.descripcion;
            document.getElementById('editar-prioridad').value = tarea.prioridad;
            document.getElementById('editar-fecha_vencimiento').value = tarea.fecha_vencimiento;
            document.getElementById('editar-fecha_recordatorio').value = tarea.fecha_recordatorio || '';

            document.getElementById('modal-editar-tarea').style.display = 'flex';
        }

        function cerrarModalEditarTarea() {
            document.getElementById('modal-editar-tarea').style.display = 'none';
        }

        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('modal-editar-tarea')) {
                cerrarModalEditarTarea();
            }
        });
        </script>

        <?php if (session('show_modal_editar')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    abrirModalEditarTarea(<?= json_encode($datosEdit) ?>);
                });
            </script>
        <?php endif; ?>


    </main>
</div>
<?= view('layout/footer') ?>