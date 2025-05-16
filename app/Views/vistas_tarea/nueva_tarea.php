<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Tarea</title>
</head>
<body>
    <a href="<?= base_url('/mis_tareas') ?>">Volver</a><br>

    <h2>Crear Tarea</h2>

    <?php if (!empty($errors['general'])): ?>
        <p style="color: red;"><?= esc($errors['general']) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('tareas/nueva_tarea') ?>" method="post">
        <div>
            <label for="asunto">Asunto: </label>
            <input type="text" minlength="4" name="asunto" id="asunto" value="<?= esc(old('asunto', $datos['asunto'] ?? '')) ?>" required>
            <?php if (!empty($errors['asunto'])): ?>
                <small class="text-danger"><?= esc($errors['asunto']) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="descripcion">Descripción: </label>
            <input type="text" minlength="4" name="descripcion" id="descripcion" value="<?= esc(old('descripcion', $datos['descripcion'] ?? '')) ?>" required>
            <?php if (!empty($errors['descripcion'])): ?>
                <small class="text-danger"><?= esc($errors['descripcion']) ?></small>
            <?php endif; ?>
        </div>
        <br>
        
        <div>
            <label for="prioridad">Prioridad: </label>
            <select name="prioridad" id="prioridad" required>
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

        <input type="hidden" name="estado" value="definido">
        
        <div>
            <label for="fecha_vencimiento">Fecha de vencimiento: </label>
            <input type="date" min="<?= date('Y-m-d') ?>" name="fecha_vencimiento" id="fecha_vencimiento"
                value="<?= esc(old('fecha_vencimiento', $datos['fecha_vencimiento'] ?? '')) ?>" required>
            <?php if (!empty($errors['fecha_vencimiento'])): ?>
                <small class="text-danger"><?= esc($errors['fecha_vencimiento']) ?></small>
            <?php endif; ?>
        </div>
        <br>
        
        <div>
            <label for="fecha_recordatorio">Fecha de recordatorio: </label>
            <input type="date" min="<?= date('Y-m-d') ?>" name="fecha_recordatorio" id="fecha_recordatorio"
                value="<?= esc(old('fecha_recordatorio', $datos['fecha_recordatorio'] ?? '')) ?>">
            <?php if (!empty($errors['fecha_recordatorio'])): ?>
                <small class="text-danger"><?= esc($errors['fecha_recordatorio']) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div><button type="submit">Crear</button></div>
    </form>
</body>
</html>
