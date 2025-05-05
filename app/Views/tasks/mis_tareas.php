<!DOCTYPE html>
<html>
<head>
    <title>Tareas</title>
    <link rel="stylesheet" href="<?= base_url('public/estilos.css') ?>">
</head>
<body>
    <h2 class="vista-tareas__titulo">Tareas</h2>

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
        <div class="contenedor-tareas">
            <div class="tarjeta-tarea tarjeta-tarea__header">
                <span>Asunto</span>
                <span>Descripción</span>
                <span><?= orden_link('prioridad', $ordenarPor, $direccion, 'Prioridad') ?></span>
                <span><?= orden_link('estado', $ordenarPor, $direccion, 'Estado') ?></span>
                <span><?= orden_link('fecha_vencimiento', $ordenarPor, $direccion, 'Fecha de vencimiento') ?></span>
                <span><?= orden_link('fecha_recordatorio', $ordenarPor, $direccion, 'Fecha de recordatorio') ?></span>
                <span><?= orden_link('fecha_creacion', $ordenarPor, $direccion, 'Fecha de creación') ?></span>
                <span><a href="<?= base_url('tareas/addTarea') ?>" class="btn-nueva-tarea">+Nueva</a></span>
            </div>

            <?php foreach ($tareas as $tarea) :
                $prioridad = strtolower($tarea['prioridad']);
                $estado = ucfirst(strtolower($tarea['estado']));
                $prioridadFormateada = ucfirst($prioridad);
                $borde = 'prioridad-' . $prioridad;
                $descripcionCorta = strlen($tarea['descripcion']) > 40
                    ? substr($tarea['descripcion'], 0, 40) . '...'
                    : $tarea['descripcion'];
            ?>
                <div class="tarjeta-tarea <?= $borde ?>">
                    <span><?= esc($tarea['asunto']) ?></span>
                    <span>
                        <?= esc($descripcionCorta) ?>
                        <?php if (strlen($tarea['descripcion']) > 40): ?>
                            <button class="btn-ver-mas" onclick="mostrarDescripcion('<?= esc($tarea['descripcion']) ?>')">Ver más</button>
                        <?php endif; ?>
                    </span>
                    <span class="tarjeta-tarea__prioridad tarjeta-tarea__prioridad--<?= $prioridad ?>">
                        <?= esc($prioridadFormateada) ?>
                    </span>
                    <span><?= esc($estado) ?></span>
                    <span><?= esc($tarea['fecha_vencimiento']) ?></span>
                    <span><?= esc($tarea['fecha_recordatorio'] ?? 'No definido') ?></span>
                    <span><?= esc($tarea['fecha_creacion']) ?></span>
                    <div class="tarjeta-tarea__acciones">
                        <a href="<?= site_url('tareas/mis_subtareas') ?>">Subtareas</a>
                        <a href="<?= base_url('tareas/buscarTarea/' . $tarea['id']) ?>">Editar</a>
                        <a href="<?= base_url('tareas/borrarTarea/' . $tarea['id']) ?>">Borrar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
            <p>No hay tareas aún.</p>
        <?php endif; ?>

    <div id="modal-descripcion" class="modal-descripcion" onclick="cerrarModal()">
        <div class="modal-descripcion__contenido" id="modal-contenido"></div>
    </div>

    <div class="vista-tareas__footer">
        <a href="<?= site_url('tareas/subtareas_asignadas/') ?>">Mis subtareas</a>
        <a href="<?= site_url('auth/logout') ?>">Salir</a>
    </div>
</body>
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
</html>
