<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
    }

    body {
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .register-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 40px;
        width: 100%;
        max-width: 450px;
        text-align: center;
    }

    .register-title {
        color: #2c3e50;
        margin-bottom: 30px;
        font-size: 24px;
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        transition: border 0.3s;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #3498db;
        outline: none;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .submit-btn:hover {
        background-color: #2980b9;
    }

    .register-footer {
        margin-top: 20px;
        color: #777;
    }

    .register-footer a {
        color: #3498db;
        text-decoration: none;
    }

    .register-footer a:hover {
        text-decoration: underline;
    }

    .error-message {
        color: #e74c3c;
        margin-top: 5px;
        font-size: 14px;
    }

    .success-message {
        color: #2ecc71;
        margin-bottom: 20px;
    }
</style>
</head>
<body>

<div class="register-container">
    <h1 class="register-title">Registro de Usuario</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <p class="success-message"><?= session('success') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('/registro') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?= old('nombre') ?>" required>
            <?php if (session('errors.nombre')): ?>
                <small class="error-message"><?= esc(session('errors.nombre')) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" value="<?= old('email') ?>" required>
            <?php if (session('errors.email')): ?>
    <small class="error-message"><?= esc(session('errors.email')) ?></small>
<?php endif; ?>

        </div>

        <div class="form-group">
            <label for="usuario">Nombre de usuario:</label>
            <input type="text" name="usuario" value="<?= old('usuario') ?>" required>
            <?php if (session('errors.usuario')): ?>
                <small class="error-message"><?= esc(session('errors.usuario')) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="clave">Contraseña:</label>
            <input type="password" name="clave" required>
            <?php if (session('errors.clave')): ?>
                <small class="error-message"><?= esc(session('errors.clave')) ?></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="confirmClave">Confirmar contraseña:</label>
            <input type="password" name="confirmClave" required>
            <?php if (session('errors.confirmClave')): ?>
                <small class="error-message"><?= esc(session('errors.confirmClave')) ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="submit-btn">Registrarse</button>
    </form>

    <div class="register-footer">
        <p>¿Ya tenés cuenta? <a href="<?= base_url('/login') ?>">Iniciar sesión</a></p>
    </div>
</div>

</body>
</html>