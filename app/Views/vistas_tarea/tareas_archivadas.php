<?= view('layout/header', ['titulo' => 'Tareas Archivadas']) ?>
<?= view('layout/navbar') ?>

<div class="contenedor-principal">
    <?= view('layout/sidebar') ?>
    <main class="contenido-principal">
        <?php helper('tareas_helper'); ?>

        <?php if (!empty($tareas)) : ?>
            <div class="tareas-container">
                <table class="tabla-tareas">
                    <thead>
                        <tr>
                            <th>Asunto</th>
                            <th>Descripción</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Fecha Vencimiento</th>
                            <th>Fecha Recordatorio</th>
                            <th>Fecha Creación</th>
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
                                    <form action="<?= base_url('tareas/desarchivar/' . $tarea['id']) ?>" method="post" class="form-archivar">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="accion-icono" title="Desarchivar">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p class="sin-tareas">No tienes tareas archivadas.</p>
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
    </main>
</div>

<?= view('layout/footer') ?>
