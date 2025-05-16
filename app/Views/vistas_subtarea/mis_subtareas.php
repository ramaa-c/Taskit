    <?= view('layout/header', ['titulo' => 'Subtareas']) ?>
    <?= view('layout/navbar') ?>
    <div class="contenedor-principal">
        <?= view('layout/sidebar') ?>
        <main class="contenido-principal">

            <?php
                $datosEditSub = session('datosEditSub') ?? [];
                $errorsEditSub = session('errorsEditSub') ?? [];
            ?>

            <?php
            helper('subtareas_helper');
            $subtareas = isset($subtareas) && is_array($subtareas) ? $subtareas : [];

            if (!empty($subtareas)) {
                $ordenarPor = $_GET['orden'] ?? 'fecha_creacion';
                $direccion = $_GET['dir'] ?? 'asc';
                $invertir = $direccion === 'desc' ? 'asc' : 'desc';

                usort($subtareas, function ($a, $b) use ($ordenarPor, $direccion) {
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

            <?php if (!empty($idTarea)): ?>
                <button type="button" class="btn-nueva-tarea" onclick="abrirModalSubtarea()">+ Nueva Subtarea</button>
            <?php endif; ?>

            <?php if (!empty($subtareas)) : ?>

                <div class="subtareas-container">
                    <table class="tabla-subtareas">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Fecha de vencimiento</th>
                                <th>Comentario</th>
                                <th>Responsable</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subtareas as $subtarea) :
                                $prioridad = strtolower($subtarea['prioridad'] ?? 'no definida');
                                $estado = ucwords(str_replace('_', ' ', strtolower($subtarea['estado'] ?? '')));
                                $descripcionCorta = strlen($subtarea['descripcion'] ?? '') > 40
                                ? substr($subtarea['descripcion'], 0, 40) . '...'
                                : $subtarea['descripcion'];
                                $prioridadClase = '';
                                if ($prioridad === 'alta') $prioridadClase = 'alta';
                                elseif ($prioridad === 'media') $prioridadClase = 'media';
                                elseif ($prioridad === 'baja') $prioridadClase = 'baja';
                            ?>
                                <tr>
                                    <td>
                                        <?= esc($descripcionCorta) ?>
                                        <?php if (strlen($subtarea['descripcion']) > 40): ?>
                                            <button onclick="mostrarTextoModal('<?= esc($subtarea['descripcion']) ?>')" class="btn-ver-mas">+</button>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="<?= base_url('subtareas/cambiar_estado/' . $subtarea['id']) ?>" method="post" onchange="this.submit()">
                                            <select name="estado">
                                                <option value="en_proceso" <?= $subtarea['estado'] === 'en_proceso' ? 'selected' : '' ?>>En Proceso</option>
                                                <option value="completada" <?= $subtarea['estado'] === 'completada' ? 'selected' : '' ?>>Completada</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td><span class="prioridad-subtarea <?= $prioridadClase ?>"><?= esc(ucfirst($prioridad)) ?></span></td>
                                    <td><?= esc($subtarea['fecha_vencimiento'] ?? 'No definida') ?></td>
                                    <?php
                                        $comentarioCorto = strlen($subtarea['comentario'] ?? '') > 40
                                            ? substr($subtarea['comentario'], 0, 40) . '...'
                                            : $subtarea['comentario'];
                                    ?>
                                    <td>
                                        <?= esc($comentarioCorto ?: '-') ?>
                                        <?php if (strlen($subtarea['comentario'] ?? '') > 40): ?>
                                            <button onclick="mostrarTextoModal('Comentario', '<?= esc($subtarea['comentario']) ?>')" class="btn-ver-mas">+</button>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($subtarea['nombre_responsable']) ?></td>
                                    <td class="acciones-subtarea">
                                        <button type="button" class="accion-icono" onclick='abrirModalEditarSubtarea(<?= json_encode((array)$subtarea) ?>)'>
                                            <i class="fa-solid fa-pen-to-square"></i></button>
                                        <a href="<?= base_url('subtareas/borrar_subtarea/' . $subtarea['id']) ?>" class="accion-icono" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <?php if (!empty($idTarea)): ?>
                    <p style="text-align: center;">Esta tarea no tiene subtareas aún.</p>
                <?php endif; ?>
            <?php endif; ?>


            <div id="modal-subtarea" class="modal-nueva-subtarea">
                <div class="modal-contenido-subtarea">

                    <h2>Crear SubTarea</h2>

                    <?php if (!empty(session('errors.general'))): ?>
                        <p style="color: red;"><?= esc(session('errors.general')) ?></p>
                    <?php endif; ?>

                    <form action="<?= base_url('subtareas/nueva_subtarea/' . $idTarea) ?>" method="post">
                        <input type="hidden" name="id_tarea" value="<?= esc($idTarea) ?>">
                        <input type="hidden" name="estado" value="definido">

                        <div>
                            <label for="descripcion">Descripción:</label>
                            <input type="text" name="descripcion" id="descripcion"
                                value="<?= esc(old('descripcion', session('datos.descripcion') ?? '')) ?>" required>
                            <?php if (!empty(session('errors.descripcion'))): ?>
                                <small class="text-danger"><?= esc(session('errors.descripcion')) ?></small>
                            <?php endif; ?>
                        </div><br>

                        <div>
                            <label for="prioridad">Prioridad:</label>
                            <select name="prioridad" id="prioridad">
                                <option value="" disabled <?= !isset(session('datos')['prioridad']) ? 'selected' : '' ?>>Seleccione</option>
                                <option value="baja" <?= old('prioridad', session('datos.prioridad') ?? '') === 'baja' ? 'selected' : '' ?>>Baja</option>
                                <option value="normal" <?= old('prioridad', session('datos.prioridad') ?? '') === 'normal' ? 'selected' : '' ?>>Normal</option>
                                <option value="alta" <?= old('prioridad', session('datos.prioridad') ?? '') === 'alta' ? 'selected' : '' ?>>Alta</option>
                            </select>
                            <?php if (!empty(session('errors.prioridad'))): ?>
                                <small class="text-danger"><?= esc(session('errors.prioridad')) ?></small>
                            <?php endif; ?>
                        </div><br>

                        <div>
                            <label for="fecha_vencimiento">Fecha de vencimiento:</label>
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" min="<?= date('Y-m-d') ?>"
                                value="<?= esc(old('fecha_vencimiento', session('datos.fecha_vencimiento') ?? '')) ?>">
                            <?php if (!empty(session('errors.fecha_vencimiento'))): ?>
                                <small class="text-danger"><?= esc(session('errors.fecha_vencimiento')) ?></small>
                            <?php endif; ?>
                        </div><br>

                        <div>
                            <label for="comentario">Comentario:</label>
                            <input type="text" name="comentario" id="comentario"
                                value="<?= esc(old('comentario', session('datos.comentario') ?? '')) ?>">
                            <?php if (!empty(session('errors.comentario'))): ?>
                                <small class="text-danger"><?= esc(session('errors.comentario')) ?></small>
                            <?php endif; ?>
                        </div><br>

                        <div>
                            <label for="id_responsable">Responsable:</label>
                            <select name="id_responsable" id="id_responsable" required>
                                <option value="" disabled selected>Seleccione un colaborador</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?= $usuario['id'] ?>"
                                        <?= old('id_responsable', session('datos.id_responsable') ?? '') == $usuario['id'] ? 'selected' : '' ?>>
                                        <?= esc($usuario['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty(session('errors.id_responsable'))): ?>
                                <small class="text-danger"><?= esc(session('errors.id_responsable')) ?></small>
                            <?php endif; ?>
                        </div><br>

                        <div><button type="submit">Crear</button></div>
                    </form>
                </div>
            </div>

            <div id="modal-editar-subtarea" class="modal-nueva-subtarea">
                <div class="modal-contenido-editar-tarea">
                    <h2>Editar SubTarea</h2>

                    <?php if (!empty(session('errorsEditSub.general'))): ?>
                        <p style="color: red;"><?= esc(session('errorsEditSub.general')) ?></p>
                    <?php endif; ?>

                    <form action="<?= site_url('subtareas/editar_subtarea/' . ($datosEditSub['id'] ?? '')) ?>" method="post">

                        <input type="hidden" name="id_tarea" id="editar_id_tarea" value="<?= esc(session('datosEditSub.id_tarea') ?? '') ?>">
                        <input type="hidden" name="estado" id="editar_estado" value="<?= esc(session('datosEditSub.estado') ?? '') ?>">

                        <div>
                            <label for="descripcion">Descripción:</label>
                            <input type="text" name="descripcion" id="editar_descripcion" required
                                value="<?= esc(old('descripcion', session('datosEditSub.descripcion') ?? '')) ?>">
                            <?php if (session('errorsEditSub.descripcion')): ?>
                                <small class="text-danger"><?= esc(session('errorsEditSub.descripcion')) ?></small>
                            <?php endif; ?>
                        </div><br>

                        <div>
                            <label for="prioridad">Prioridad:</label>
                            <select name="prioridad" id="editar_prioridad" required>
                                <option value="" disabled>Seleccione</option>
                                <option value="baja" <?= old('prioridad', session('datosEditSub.prioridad') ?? '') === 'baja' ? 'selected' : '' ?>>Baja</option>
                                <option value="normal" <?= old('prioridad', session('datosEditSub.prioridad') ?? '') === 'normal' ? 'selected' : '' ?>>Normal</option>
                                <option value="alta" <?= old('prioridad', session('datosEditSub.prioridad') ?? '') === 'alta' ? 'selected' : '' ?>>Alta</option>
                            </select>
                            <?php if (session('errorsEditSub.prioridad')): ?>
                                <small class="text-danger"><?= esc(session('errorsEditSub.prioridad')) ?></small>
                            <?php endif; ?>
                        </div><br>

                        <div>
                            <label for="fecha_vencimiento">Fecha de vencimiento:</label>
                            <input type="date" name="fecha_vencimiento" min="<?= date('Y-m-d') ?>" id="editar_fecha_vencimiento"
                                value="<?= esc(old('fecha_vencimiento', session('datosEditSub.fecha_vencimiento') ?? '')) ?>">
                            <?php if (session('errorsEditSub.fecha_vencimiento')): ?>
                                <small class="text-danger"><?= esc(session('errorsEditSub.fecha_vencimiento')) ?></small>
                            <?php endif; ?>
                        </div><br>

                        <div>
                            <label for="comentario">Comentario:</label>
                            <input type="text" name="comentario" id="editar_comentario"
                                value="<?= esc(old('comentario', session('datosEditSub.comentario') ?? '')) ?>">
                            <?php if (session('errorsEditSub.comentario')): ?>
                                <small class="text-danger"><?= esc(session('errorsEditSub.comentario')) ?></small>
                            <?php endif; ?>
                        </div><br>

                        <div>
                            <label>Responsable:</label>
                            <span id="editar_nombre_responsable"><?= esc(session('datosEditSub.nombre_responsable') ?? '') ?></span>
                            <input type="hidden" name="id_responsable" id="editar_id_responsable" value="<?= esc(session('datosEditSub.id_responsable') ?? '') ?>">
                        </div><br>

                        <div><button type="submit" name="submit">Actualizar</button></div>
                    </form>
                </div>
            </div>

            <div id="modal-descripcion" onclick="cerrarModal()">
                <div id="modal-contenido"></div>
            </div>

            <script>
                function mostrarTextoModal(titulo, texto) {
                    const modal = document.getElementById('modal-descripcion');
                    const contenido = document.getElementById('modal-contenido');
                    contenido.innerHTML = `<strong>${titulo}:</strong><br>${texto}`;
                    modal.style.display = 'flex';
                }


                function cerrarModal() {
                    document.getElementById('modal-descripcion').style.display = 'none';
                }
            </script>


            <script>
                function abrirModalSubtarea() {
                    document.getElementById('modal-subtarea').style.display = 'flex';
                }

                function cerrarModalSubtarea() {
                    document.getElementById('modal-subtarea').style.display = 'none';
                }
            </script>

            <?php if (session('show_modal') || session('show_modal_subtarea')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    abrirModalSubtarea();
                });
            </script>
            <?php endif; ?>

            <script>
            function abrirModalEditarSubtarea(subtarea) {
                const form = document.querySelector('#modal-editar-subtarea form');
                form.action = '<?= site_url('subtareas/editar_subtarea') ?>/' + subtarea.id;
                
                document.getElementById('editar_id_tarea').value = subtarea.id_tarea;
                document.getElementById('editar_estado').value = subtarea.estado;
                document.getElementById('editar_descripcion').value = subtarea.descripcion;
                document.getElementById('editar_prioridad').value = subtarea.prioridad;
                document.getElementById('editar_fecha_vencimiento').value = subtarea.fecha_vencimiento;
                document.getElementById('editar_comentario').value = subtarea.comentario;
                document.getElementById('editar_id_responsable').value = subtarea.id_responsable;
                document.getElementById('editar_nombre_responsable').textContent = subtarea.nombre_responsable;

                document.getElementById('modal-editar-subtarea').style.display = 'flex';
            }

            document.addEventListener('DOMContentLoaded', function () {
                const modalNueva = document.getElementById('modal-subtarea');
                const modalEditar = document.getElementById('modal-editar-subtarea');

                const cerrarBtnNueva = document.querySelector('.cerrar-nueva-subtarea');
                const cerrarBtnEditar = document.querySelector('.cerrar-editar-subtarea');

                function cerrarModalNuevaSubtarea() {
                    modalNueva.style.display = 'none';
                    modalNueva.querySelector('form').reset();
                }

                function cerrarModalEditarSubtarea() {
                    modalEditar.style.display = 'none';
                    modalEditar.querySelector('form').action = '';
                    modalEditar.querySelector('form').reset();
                }

                if (cerrarBtnNueva) cerrarBtnNueva.addEventListener('click', cerrarModalNuevaSubtarea);
                if (cerrarBtnEditar) cerrarBtnEditar.addEventListener('click', cerrarModalEditarSubtarea);

                window.addEventListener('click', function (event) {
                    if (event.target === modalNueva) {
                        cerrarModalNuevaSubtarea();
                    }
                    if (event.target === modalEditar) {
                        cerrarModalEditarSubtarea();
                    }
                });
            });
            </script>

            <?php if (session('show_modal_editar') && session('errorsEditSub')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    abrirModalEditarSubtarea(<?= json_encode(session('datosEditSub')) ?>);
                });
            </script>
            <?php endif; ?>


        </main>
    </div>
    <?= view('layout/footer') ?>