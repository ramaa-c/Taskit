<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
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
        
        .login-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .login-title {
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
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input[type="text"]:focus,
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
        
        .login-footer {
            margin-top: 20px;
            color: #777;
        }
        
        .login-footer a {
            color: #3498db;
            text-decoration: none;
        }
        
        .login-footer a:hover {
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
    <div class="login-container">
        <h1 class="login-title">Iniciar Sesión</h1>
        
        <?php if (session()->getFlashdata('success')): ?>
            <p class="success-message"><?= session()->getFlashdata('success') ?></p>
        <?php endif; ?>
        
        <?php if (isset($errors)): ?>
            <div style="color:#e74c3c; margin-bottom:20px;">
                <?php foreach ($errors as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form action="<?= site_url('/login') ?>" method="post">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario" value='<?= old('usuario') ?>' required>
                <?php if (session('errors.usuario')): ?>
                    <small class="error-message"><?= esc(session('errors.usuario')) ?></small>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="clave" required>
                <?php if (session('errors.clave')): ?>
                    <small class="error-message"><?= esc(session('errors.clave')) ?></small>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="submit-btn">Ingresar</button>
        </form>
        
        <div class="login-footer">
            <p>¿No tenés una cuenta? <a href="<?= site_url('/registro') ?>">Registrarse</a></p>
        </div>
    </div>
</body>
</html>