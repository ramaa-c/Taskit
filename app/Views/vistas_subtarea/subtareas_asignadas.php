<?= view('layout/header', ['titulo' => 'Subtareas']) ?>
<?= view('layout/navbar') ?>

<div class="contenedor-principal">
    <?= view('layout/sidebar') ?>
    <main class="contenido-principal">
        <h2>Subtareas Asignadas a Mí</h2>

        <?php if (!empty($subtareas)) : ?>
            <div class="subtareas-container">
                <table class="tabla-subtareas">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
                            <th>Fecha de vencimiento</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subtareas as $subtarea) :
                            $prioridad = strtolower($subtarea['prioridad'] ?? 'no definida');
                            $estado = ucwords(str_replace('_', ' ', strtolower($subtarea['estado'] ?? '')));
                            $prioridadClase = '';
                            if ($prioridad === 'alta') $prioridadClase = 'alta';
                            elseif ($prioridad === 'normal') $prioridadClase = 'media';
                            elseif ($prioridad === 'baja') $prioridadClase = 'baja';
                        ?>
                            <tr>
                                <td><?= esc($subtarea['descripcion']) ?></td>
                                <td>
                                    <form action="<?= base_url('subtareas/cambiar_estado/' . $subtarea['id']) ?>" method="post" onchange="this.submit()">
                                        <select name="estado">
                                            <option value="en_proceso" <?= $subtarea['estado'] === 'en_proceso' ? 'selected' : '' ?>>En Proceso</option>
                                            <option value="completada" <?= $subtarea['estado'] === 'completada' ? 'selected' : '' ?>>Completada</option>
                                        </select>
                                    </form>
                                </td>
                                <td><span class="prioridad-subtarea <?= $prioridadClase ?>"><?= esc(ucfirst($prioridad)) ?></span></td>
                                <td><?= esc($subtarea['fecha_vencimiento'] ?? 'No definida') ?></td>
                                <td><?= esc($subtarea['comentario'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p style="text-align: center;">No tienes subtareas asignadas.</p>
        <?php endif; ?>

    </main>
</div>

<?= view('layout/footer') ?>
