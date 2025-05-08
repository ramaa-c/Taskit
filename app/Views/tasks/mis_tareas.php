<!DOCTYPE html>
<html>
<head>
    <title>Tareas</title>
</head>
<body>
    <h2>Tareas</h2>

    <?php
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

        helper('tareas_helper');
    ?>

    <?php if (!empty($tareas)) : ?>
        <a href="<?= base_url('tareas/addTarea') ?>">+Nueva</a>

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Asunto</th>
                    <th>Descripción</th>
                    <th><?= orden_link('prioridad', $ordenarPor, $direccion, 'Prioridad') ?></th>
                    <th><?= orden_link('estado', $ordenarPor, $direccion, 'Estado') ?></th>
                    <th><?= orden_link('fecha_vencimiento', $ordenarPor, $direccion, 'Fecha de vencimiento') ?></th>
                    <th><?= orden_link('fecha_recordatorio', $ordenarPor, $direccion, 'Fecha de recordatorio') ?></th>
                    <th><?= orden_link('fecha_creacion', $ordenarPor, $direccion, 'Fecha de creación') ?></th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tareas as $tarea) :
                    $prioridad = strtolower($tarea['prioridad']);
                    $estado = ucfirst(str_replace('_', ' ', strtolower($tarea['estado'])));
                    $descripcionCorta = strlen($tarea['descripcion']) > 40
                        ? substr($tarea['descripcion'], 0, 40) . '...'
                        : $tarea['descripcion'];
                ?>
                    <tr>
                        <td><?= esc($tarea['asunto']) ?></td>
                        <td>
                            <?= esc($descripcionCorta) ?>
                            <?php if (strlen($tarea['descripcion']) > 40): ?>
                                <button onclick="mostrarDescripcion('<?= esc($tarea['descripcion']) ?>')">Ver más</button>
                            <?php endif; ?>
                        </td>
                        <td><?= esc(ucfirst($prioridad)) ?></td>
                        <td><?= esc($estado) ?></td>
                        <td><?= esc($tarea['fecha_vencimiento']) ?></td>
                        <td><?= esc($tarea['fecha_recordatorio'] ?? 'No definido') ?></td>
                        <td><?= esc($tarea['fecha_creacion']) ?></td>
                        <td>
                            <a href="<?= site_url('tareas/mis_subtareas') ?>">Subtareas</a> |
                            <a href="<?= base_url('tareas/buscarTarea/' . $tarea['id']) ?>">Editar</a> |
                            <a href="<?= base_url('tareas/borrarTarea/' . $tarea['id']) ?>">Borrar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No hay tareas aún.</p>
    <?php endif; ?>

    <div id="modal-descripcion" style="display:none;" onclick="cerrarModal()">
        <div id="modal-contenido"></div>
    </div>

    <div>
        <a href="<?= site_url('tareas/subtareas_asignadas/') ?>">Mis subtareas</a> |
        <a href="<?= site_url('auth/logout') ?>">Salir</a>
    </div>

<script>
    function mostrarDescripcion(descripcion) {
        const modal = document.getElementById('modal-descripcion');
        const contenido = document.getElementById('modal-contenido');
        contenido.textContent = descripcion;
        modal.style.display = 'block';
    }

    function cerrarModal() {
        document.getElementById('modal-descripcion').style.display = 'none';
    }
</script>

</body>
</html>
