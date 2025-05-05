<!DOCTYPE html>
<html>
<head>
    <title>Tareas</title>
    <link rel="stylesheet" href="<?= base_url('public/estilos.css/') ?>">
</head>
<body>
    <h2>Tareas</h2>

    <?php if(!empty($tareas)) : ?>
            <ul>
                <?php foreach ($tareas as $tarea) : ?>
                    <li>
                        <strong><?= esc($tarea['asunto']) ?></strong><br>
                        Descripción: <?= esc($tarea['descripcion']) ?><br>
                        Prioridad: <?= esc($tarea['prioridad']) ?><br>
                        Estado: <?= esc($tarea['estado']) ?><br>
                        Vence: <?= esc($tarea['fecha_vencimiento']) ?><br>
                        Recordatorio: <?= esc($tarea['fecha_recordatorio'] ?? 'No definido') ?><br>
                        <a href="<?= site_url('tareas/mis_subtareas') ?>">Ver subtareas</a>
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
    <a href="<?=  site_url('tareas/subtareas_asignadas/') ?>">Mis subtareas</a>
    <a href="<?= site_url('auth/logout') ?>">Salir</a>
</body>
</html>