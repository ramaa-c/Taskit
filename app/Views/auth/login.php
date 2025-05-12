<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <p style="color:green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <form action="<?= site_url('/login') ?>" method="post">
        
        <div>
            <label>Usuario:</label><br>
            <input type="text" name="usuario" value='<?= old('usuario') ?>' required>
            <?php if (session('errors.usuario')): ?>
                <small class="text-danger"><?= esc(session('errors.usuario')) ?></small>
            <?php endif; ?>
        </div><br>

        <div>
            <label>Contraseña:</label><br>
            <input type="password" name="clave" required>
            <?php if (session('errors.clave')): ?>
                <small class="text-danger"><?= esc(session('errors.clave')) ?></small>
            <?php endif; ?>
        </div><br>

        <div>
            <input type="submit" value="Ingresar">
        </div><br>

    </form>


    <p>¿No tenés una cuenta? <a href="<?= site_url('/registro') ?>">Registrarse</a></p>
</body>
</html>