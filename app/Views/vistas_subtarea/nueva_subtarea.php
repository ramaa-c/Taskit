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
    <title>Nueva Subtarea</title>
</head>
<body>
    <a href="<?= base_url('/tareas') ?>">Volver</a><br>
    <h2>Crear SubTarea</h2>
    <form action="<?= base_url('/subtareas/nueva_subtarea/' . $datos['id']) ?>" method='post'>

        <input type="hidden" name="id_tarea" value="<?= esc($idTarea) ?>">

        <div>
            <label for="descripcion">Descripción: </label>
            <input type="text" min_length='4' name='descripcion' id='descripcion' value='<?= old('descripcion') ?>' required>
            <?php if (session('errors.descripcion')): ?>
                <small class="text-danger"><?= esc(session('errors.descripcion')) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <input type="hidden" name="estado" value="definido">
            
        <div>
            <label for="prioridad">Prioridad: </label>
            <select name="prioridad" id="prioridad">
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
        
        <div>  
            <label for="fecha_vencimiento">Fecha de vencimiento: </label>
            <input type="date" min='<?= date('Y-m-d') ?>' name='fecha_vencimiento' id='fecha_vencimiento' value='<?= old('fecha_vencimiento') ?>'>
            <?php if (session('errors.fecha_vencimiento')): ?>
                <small class="text-danger"><?= esc(session('errors.fecha_vencimiento')) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="comentario">Comentario: </label>
            <input type="text" name="comentario" id="comentario">
            <?php if (session('errors.comentario')): ?>
                <small class="text-danger"><?= esc(session('errors.comentario')) ?></small>
            <?php endif; ?>
        </div><br>

        <div>
            <label for="responsable">Responsable: </label>
            <select name="responsable" id="responsable" required>
                <option value="" selected disabled>Seleccione un colaborador</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>">
                    <?= htmlspecialchars($usuario['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div><br>
        
        <div><button type="submit">Crear</button></div>

    </form>
</body>
</html>