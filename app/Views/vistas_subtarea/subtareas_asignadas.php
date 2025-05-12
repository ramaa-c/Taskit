<?php
if (!session()->has('id')) {
    header('Location: ' . base_url('/login'));
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subtareas Asignadas</title>
    <link rel="stylesheet" href="<?= base_url('public/estilos.css/') ?>">
</head>
<body>
    <h2>Subtareas Asignadas a Mí</h2>

    <?php if (!empty($subtareas)) : ?>
        <ul>
            <?php foreach ($subtareas as $subtarea) : ?>
                <li>
                    <strong><?= esc($subtarea['descripcion']) ?></strong><br>
                    Estado: <?= esc($subtarea['estado']) ?><br>
                    Prioridad: <?= esc($subtarea['prioridad'] ?? 'No definida') ?><br>
                    Vence: <?= esc($subtarea['fecha_vencimiento'] ?? 'No definida') ?><br>
                    Comentario: <?= esc($subtarea['comentario'] ?? '-') ?><br>
                    <a href="<?= base_url('tareas/actualizarEstado/' . $subtarea['id']) ?>">Cambiar estado</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No tienes subtareas asignadas.</p>
    <?php endif; ?>

    <br>
    <a href="<?= base_url('/tareas') ?>">Volver a tareas</a>
    <br>
    <a href="<?= site_url('auth/logout') ?>">Salir</a>
</body>
</html>
