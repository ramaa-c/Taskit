<?php
if (!session()->has('id')) {
    header('Location: ' . base_url('/login'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Tarea</title>
</head>
<body>
    <a href="<?= base_url('/tareas') ?>">Volver</a><br>

    <h2>Crear Tarea</h2>

    <form action="<?= site_url('tareas/nueva_tarea/') ?>" method="post">
        <div>
            <label for="asunto">Asunto: </label>
            <input type="text" minlength='4' name='asunto' id='asunto' value='<?= old('asunto') ?>' required>
            <?php if (session('errors.asunto')): ?>
                <small class="text-danger"><?= esc(session('errors.asunto')) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="descripcion">Descripción: </label>
            <input type="text" minlength='4' name='descripcion' id='descripcion' value='<?= old('descripcion') ?>' required>
            <?php if (session('errors.descripcion')): ?>
                <small class="text-danger"><?= esc(session('errors.descripcion')) ?></small>
            <?php endif; ?>
        </div>
        <br>
            
        <div>
            <label for="prioridad">Prioridad: </label>
            <select name="prioridad" id="prioridad" required>
                <option value="" selected disabled>seleccione</option>
                <option value="baja">Baja</option>
                <option value="normal">Normal</option>
                <option value="alta">Alta</option>
            </select>
            <?php if (session('errors.prioridad')): ?>
                <small class="text-danger"><?= esc(session('errors.prioridad')) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <input type="hidden" name="estado" value="definido">
        
        <div>  
            <label for="fecha_vencimiento">Fecha de vencimiento: </label>
            <input type="date" min='<?= date('Y-m-d') ?>' name='fecha_vencimiento' id='fecha_vencimiento' value='<?= old('fecha_vencimiento') ?>' required>
            <?php if (session('errors.fecha_vencimiento')): ?>
                <small class="text-danger"><?= esc(session('errors.fecha_vencimiento')) ?></small>
            <?php endif; ?>
        </div>
        <br>
        
        <div>
            <label for="fecha_recordatorio">Fecha de recordatorio: </label>
            <input type="date" min='<?= date('Y-m-d') ?>' name='fecha_recordatorio' id='fecha_recordatorio' value='<?= old('fecha_recordatorio') ?>'>
            <?php if (session('errors.fecha_recordatorio')): ?>
                <small class="text-danger"><?= esc(session('errors.fecha_recordatorio')) ?></small>
            <?php endif; ?>
        </div>
        <br>
        <div><button type="submit">Crear</button></div>

    </form>
</body>
</html>