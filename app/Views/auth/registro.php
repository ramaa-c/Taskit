<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<h2>Registro de Usuario</h2>

<?php if (session()->getFlashdata('success')): ?>
    <p><?= session('success') ?></p>
<?php endif; ?>

<form action="<?= base_url('/registro') ?>" method="post">
    <?= csrf_field() ?>

    <div>
        <label for="nombre">Nombre:</label><br>
        <input type="text" name="nombre" value="<?= old('nombre') ?>">
        <?= session('errors.nombre') ? '<span>' . session('errors.nombre') . '</span><br>' : '' ?>
    </div>

    <div>
    <label for="email">Correo electrónico:</label><br>
    <input type="email" name="email" value="<?= old('email') ?>">
    <?= session('errors.email') ? '<span>' . session('errors.email') . '</span><br>' : '' ?>
    </div>
    
    <div>
        <label for="usuario">Nombre de usuario:</label><br>
        <input type="text" name="usuario" value="<?= old('usuario') ?>">
        <?= session('errors.usuario') ? '<span>' . session('errors.usuario') . '</span><br>' : '' ?>
    </div> 

    <div>
        <label for="clave">Contraseña:</label><br>
        <input type="password" name="clave">
        <?= session('errors.clave') ? '<span>' . session('errors.clave') . '</span><br>' : '' ?>
    </div>
    
    <div>
        <label for="confirmClave">Confirmar contraseña:</label><br>
        <input type="password" name="confirmClave">
        <?= session('errors.confirmClave') ? '<span>' . session('errors.confirmClave') . '</span><br>' : '' ?>
    </div>

    <br>
    <div>
        <input type="submit" value="Registrarse">
    </div>

</form>

<p>¿Ya tenés cuenta? <a href="<?= base_url('/login') ?>">Iniciar sesión</a></p>

</body>
</html>