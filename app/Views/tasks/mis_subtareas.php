<!DOCTYPE html>
<html>
<head>
    <title>Mis Subtareas</title>
    <link rel="stylesheet" href="<?= base_url('public/estilos.css') ?>">
</head>
<body>
    <h2>Mis Subtareas</h2>

    <?php if (!empty($subtareas)) : ?>
        <div>
            <div>
                <span>Descripción</span>
                <span>Estado</span>
                <span>Prioridad</span>
                <span>Fecha de vencimiento</span>
                <span>Comentario</span>
                <span>Responsable</span>
                <span>Acciones</span>
            </div>

            <?php foreach ($subtareas as $subtarea) :
                $prioridad = strtolower($subtarea->prioridad ?? 'no definida');
                $estado = ucwords(str_replace('_', ' ', strtolower($subtarea->estado)));
                $prioridadFormateada = ucfirst($prioridad);
            ?>
                <div>
                    <span><?= esc($subtarea->descripcion) ?></span>
                    <span><?= esc($estado) ?></span>
                    <span><?= esc($prioridadFormateada) ?></span>
                    <span><?= esc($subtarea->fecha_vencimiento ?? 'No definida') ?></span>
                    <span><?= esc($subtarea->comentario ?? '-') ?></span>
                    <span><?= esc($subtarea->id_responsable) ?></span>
                    <div>
                        <a href="<?= base_url('tareas/editarSubtarea/' . $subtarea->id) ?>">Editar</a>
                        <a href="<?= base_url('tareas/borrarSubtarea/' . $subtarea->id) ?>">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>No tienes subtareas aún.</p>
    <?php endif; ?>

    <div>
    <a href="<?= base_url('tareas/agregarSubtarea/' . $subtarea->id_tarea) ?>">+Subtarea</a>
        <a href="<?= base_url('tareas') ?>">Volver a tareas</a>
        <a href="<?= site_url('auth/logout') ?>">Salir</a>
    </div>
</body>
</html>
