<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Subtarea</title>
</head>
<body>

    <a href="<?= base_url('subtareas/mis_subtareas') ?>">Volver</a><br>
    <h2>Editar SubTarea</h2>

    <form action="<?= site_url('subtareas/editar_subtarea/' . $datos['id']) ?>" method="post">
        
        <input type="hidden" name="id_tarea" value="<?= esc($datos['id_tarea']) ?>">

        <div>
            <label for="descripcion">Descripción: </label>
            <input type="text" name="descripcion" id="descripcion" value="<?= esc(old('descripcion', $datos['descripcion'])) ?>" required>
            <?php if (session('errors.descripcion')): ?>
                <small class="text-danger"><?= esc(session('errors.descripcion')) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="estado">Estado: </label>
            <input type="hidden" name="estado" value="<?= esc($datos['estado']) ?>">
            <span><?= esc(ucwords(str_replace('_', ' ', $datos['estado']))) ?></span>
        </div>
        <br>

        <div>
            <label for="prioridad">Prioridad: </label>
            <select name="prioridad" id="prioridad" required>
                <option value="" disabled>Seleccione</option>
                <option value="baja" <?= $datos['prioridad'] == 'baja' ? 'selected' : '' ?>>Baja</option>
                <option value="normal" <?= $datos['prioridad'] == 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="alta" <?= $datos['prioridad'] == 'alta' ? 'selected' : '' ?>>Alta</option>
            </select>
            <?php if (session('errors.prioridad')): ?>
                <small class="text-danger"><?= esc(session('errors.prioridad')) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="fecha_vencimiento">Fecha de vencimiento: </label>
            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" min="<?= date('Y-m-d') ?>" value="<?= esc(old('fecha_vencimiento', $datos['fecha_vencimiento'])) ?>">
            <?php if (session('errors.fecha_vencimiento')): ?>
                <small class="text-danger"><?= esc(session('errors.fecha_vencimiento')) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label for="comentario">Comentario: </label>
            <input type="text" name="comentario" id="comentario" value="<?= esc(old('comentario', $datos['comentario'])) ?>">
            <?php if (session('errors.comentario')): ?>
                <small class="text-danger"><?= esc(session('errors.comentario')) ?></small>
            <?php endif; ?>
        </div>
        <br>

        <div>
            <label>Responsable: </label>
            <span><?= esc($datos['nombre_responsable']) ?></span>
            <input type="hidden" name="id_responsable" value="<?= esc($datos['id_responsable']) ?>">
        </div>
        <br>

        <div><button type="submit">Actualizar</button></div>

    </form>
</body>
</html>
