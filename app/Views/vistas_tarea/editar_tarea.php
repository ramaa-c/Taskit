<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar tarea</title>
</head>
<body>

    <form action="<?= site_url('tareas/editar_tarea/' . $datos['id']) ?>" method="post">

            <input type="hidden" name="id" id="id" value="<?= esc($datos['id']) ?>">
            
        <div>
            <label for="asunto">Asunto: </label>
            <input type="text" name="asunto" id="asunto" value="<?= esc($datos['asunto']) ?>">
            <?php if (session('errors.asunto')): ?>
                <small class="text-danger"><?= esc(session('errors.asunto')) ?></small>
            <?php endif; ?>
        </div><br>

        <div>
            <label for="descripcion">Descripción: </label>
            <input type="text" name="descripcion" id="descripcion" value="<?= esc($datos['descripcion']) ?>">
            <?php if (session('errors.descripcion')): ?>
                <small class="text-danger"><?= esc(session('errors.descripcion')) ?></small>
            <?php endif; ?>
        </div><br>

        <div>
            <?php
                $prioridades = ['alta', 'media', 'baja'];
                $prioridadActual = $datos['prioridad'];
            ?>
            <label for="prioridad">Prioridad: </label>
            <select name="prioridad" id="prioridad" required>
                <?php foreach ($prioridades as $prioridad): ?>
                <option value="<?= esc($prioridad) ?>" <?= $prioridad === $prioridadActual ? 'selected' : '' ?>>
                    <?= esc($prioridad) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <?php if (session('errors.prioridad')): ?>
                <small class="text-danger"><?= esc(session('errors.prioridad')) ?></small>
            <?php endif; ?>
        </div><br>

        <div>
            <label for="estado">Estado: </label>
            <input type="hidden" name="estado" value="<?= esc($datos['estado']) ?>">
            <span><?= esc(ucwords(str_replace('_', ' ', $datos['estado']))) ?></span>
        </div>
        <br>

        <div>
            <label for="fecha_vencimiento">Fecha de vencimiento: </label>
            <input type="date" min='<?= date('Y-m-d') ?>' name="fecha_vencimiento" id="fecha_vencimiento" value="<?= esc($datos['fecha_vencimiento']) ?>">
            <?php if (session('errors.fecha_vencimiento')): ?>
                <small class="text-danger"><?= esc(session('errors.fecha_vencimiento')) ?></small>
            <?php endif; ?>
        </div><br>

        <div>
            <label for="fecha_recordatorio">Fecha de recordatorio: </label>
            <input type="date" min='<?= date('Y-m-d') ?>' name="fecha_recordatorio" id="fecha_recordatorio" value="<?= esc($datos['fecha_recordatorio']) ?>">
            <?php if (session('errors.fecha_recordatorio')): ?>
                <small class="text-danger"><?= esc(session('errors.fecha_recordatorio')) ?></small>
            <?php endif; ?>
        </div><br>

        <div>
            <button type="submit">Guardar</button>
        </div>

    </form>
</body>
</html>