<!DOCTYPE html>
<html>
<head>
    <title>Mis Subtareas</title>
    <link rel="stylesheet" href="<?= base_url('public/estilos.css/') ?>">
</head>
<body>
    <h2>Mis Subtareas</h2>

    <?php if (!empty($subtareas)) : ?>
        <ul>
            <?php foreach ($subtareas as $subtarea) : ?>
                <li>
                    <strong><?= esc($subtarea->descripcion) ?></strong><br>
                    Estado: <?= esc($subtarea->estado) ?><br>
                    Prioridad: <?= esc($subtarea->prioridad ?? 'No definida') ?><br>
                    Vence: <?= esc($subtarea->fecha_vencimiento ?? 'No definida') ?><br>
                    Comentario: <?= esc($subtarea->comentario ?? '-') ?><br>
                    Responsable: <?= esc($subtarea->id_responsable) ?><br>
                    <a href="<?= base_url('tareas/editarSubtarea/' . $subtarea->id) ?>">Editar</a>
                    <a href="<?= base_url('tareas/borrarSubtarea/' . $subtarea->id) ?>">Eliminar</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No tienes subtareas aún.</p>
    <?php endif; ?>

    <br>
    <a href="<?= base_url('tareas') ?>">Volver a tareas</a>
    <br>
    <a href="<?= site_url('auth/logout') ?>">Salir</a>
</body>
</html>
