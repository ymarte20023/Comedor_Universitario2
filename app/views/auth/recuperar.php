<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css?v=<?php echo time(); ?>">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--secondary-color) 100%);
            min-height: 100vh;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 3rem;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .login-box h1 {
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            font-size: 1.75rem;
        }

        .login-box p {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: all var(--transition-base);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(27, 73, 101, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-base);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .error-message {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--danger-color);
        }

        .success-message {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            border-left: 4px solid #27ae60;
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Recuperar Contraseña</h1>
            <p>Ingresa tu correo electrónico</p>

            <?php if (!empty($data['error'])): ?>
                <div class="error-message"><?php echo $data['error']; ?></div>
            <?php endif; ?>

            <?php if (!empty($data['success'])): ?>
                <div class="success-message"><?php echo $data['success']; ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo URLROOT; ?>/login/recuperar">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required placeholder="usuario@comedor.edu">
                    <small style="color: var(--text-secondary);">Debe terminar en @comedor.edu</small>
                </div>

                <button type="submit" class="btn-primary">Enviar Instrucciones</button>
            </form>

            <div class="back-link">
                <a href="<?php echo URLROOT; ?>/login">← Volver al inicio de sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
