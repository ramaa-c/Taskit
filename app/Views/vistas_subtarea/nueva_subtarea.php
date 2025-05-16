<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Subtarea</title>
</head>
<body>
    <a href="<?= base_url('/mis_tareas') ?>">Volver</a><br>

    <h2>Crear SubTarea</h2>

    <?php if (!empty($errors['general'])): ?>
        <p style="color: red;"><?= esc($errors['general']) ?></p>
    <?php endif; ?>

    <form action="<?= base_url('/subtareas/nueva_subtarea/' . $idTarea) ?>" method="post">

        <input type="hidden" name="id_tarea" value="<?= esc($idTarea) ?>">
        <input type="hidden" name="estado" value="definido">

        <div>
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" id="descripcion" minlength="4" 
                value="<?= esc(old('descripcion', $datos['descripcion'] ?? '')) ?>" required>
            <?php if (!empty($errors['descripcion'])): ?>
                <small class="text-danger"><?= esc($errors['descripcion']) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="prioridad">Prioridad:</label>
            <select name="prioridad" id="prioridad">
                <option value="" disabled <?= !isset($datos['prioridad']) ? 'selected' : '' ?>>Seleccione</option>
                <option value="baja" <?= (old('prioridad', $datos['prioridad'] ?? '') === 'baja') ? 'selected' : '' ?>>Baja</option>
                <option value="normal" <?= (old('prioridad', $datos['prioridad'] ?? '') === 'normal') ? 'selected' : '' ?>>Normal</option>
                <option value="alta" <?= (old('prioridad', $datos['prioridad'] ?? '') === 'alta') ? 'selected' : '' ?>>Alta</option>
            </select>
            <?php if (!empty($errors['prioridad'])): ?>
                <small class="text-danger"><?= esc($errors['prioridad']) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="fecha_vencimiento">Fecha de vencimiento:</label>
            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" min="<?= date('Y-m-d') ?>" 
                value="<?= esc(old('fecha_vencimiento', $datos['fecha_vencimiento'] ?? '')) ?>">
            <?php if (!empty($errors['fecha_vencimiento'])): ?>
                <small class="text-danger"><?= esc($errors['fecha_vencimiento']) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="comentario">Comentario:</label>
            <input type="text" name="comentario" id="comentario" 
                value="<?= esc(old('comentario', $datos['comentario'] ?? '')) ?>">
            <?php if (!empty($errors['comentario'])): ?>
                <small class="text-danger"><?= esc($errors['comentario']) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="id_responsable">Responsable:</label>
            <select name="id_responsable" id="id_responsable" required>
                <option value="" disabled selected>Seleccione un colaborador</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>" <?= old('id_responsable', $datos['id_responsable'] ?? '') == $usuario['id'] ? 'selected' : '' ?>>
                        <?= esc($usuario['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['id_responsable'])): ?>
                <small class="text-danger"><?= esc($errors['id_responsable']) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <button type="submit">Crear</button>
        </div>
    </form>
</body>
</html>
