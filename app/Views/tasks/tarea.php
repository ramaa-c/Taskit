<!DOCTYPE html>
<html>
<head>
    <title>Tareas</title>
    <link rel="stylesheet" href="<?= base_url('public/estilos.css/') ?>">
</head>
<body>
    <h2>Tareas</h2>

    <?php $mostrarCompletadas = isset($_GET['completadas']) && $_GET['completadas'] == 1; ?>

    <div class="dropdown">
        <button class="dropbtn">
            <?= $mostrarCompletadas ? 'Historial' : 'Activas' ?>
        </button>
        <div class="dropdown-content">
            <a href="<?= base_url('tareas') ?>" class="<?= !$mostrarCompletadas ? 'disabled' : '' ?>">Activas</a>
            <a href="<?= base_url('tareas') ?>?completadas=1" class="<?= $mostrarCompletadas ? 'disabled' : '' ?>">Historial</a>
        </div>
    </div>

    <?php if(!empty($tareas)) : ?>
            <ul>
                <?php foreach ($tareas as $tarea) : ?>
                    <?php
                        if ($mostrarCompletadas) {
                            if ($tarea['estado'] != 'completada') {
                                continue;
                            }
                        } else {
                            if ($tarea['estado'] == 'completada') {
                                continue;
                            }
                        }
                    ?>
                    <li>
                        <strong><?= esc($tarea['asunto']) ?></strong><br>
                        Descripción: <?= esc($tarea['descripcion']) ?><br>
                        Prioridad: <?= esc($tarea['prioridad']) ?><br>
                        Estado: <?= esc($tarea['estado']) ?><br>
                        Vence: <?= esc($tarea['fecha_vencimiento']) ?><br>
                        Recordatorio: <?= esc($tarea['fecha_recordatorio'] ?? 'No definido') ?><br>
                        <a href="<?= base_url('tareas/buscarTarea/' . $tarea['id']) ?>">Editar tarea</a>
                        <a href="<?= base_url('tareas/borrarTarea/' . $tarea['id']) ?>">Borrar Tarea</a>
                    </li>
                <?php endforeach; ?>
            </ul>
    <?php else : ?>
        <p>No hay tareas aún.</p>
    <?php endif ?>
    <a href="<?= base_url('tareas/addTarea') ?>">Añadir tarea</a>
    <br>
    <a href="<?= site_url('auth/logout') ?>">Salir</a>
</body>
</html>