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
            position: relative;
            overflow: hidden; /* This is fine for the radial background animation only */
        }

        /* Animated Background */
        .login-container::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(98, 182, 203, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 3.5rem;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeIn 0.6s ease-out;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .login-box h1 {
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            font-size: 2rem;
            font-weight: 700;
        }

        .login-box p {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.625rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9375rem;
        }

        .form-group input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: all var(--transition-base);
            background: var(--white);
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(27, 73, 101, 0.1);
            transform: translateY(-2px);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        .btn-login {
            width: 100%;
            padding: 1.125rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1.0625rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-base);
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover::before {
            width: 400px;
            height: 400px;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .error-message {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            padding: 1.125rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--danger-color);
            font-weight: 500;
            animation: slideIn 0.4s ease-out;
        }

        .credentials-info {
            margin-top: 2.5rem;
            padding: 1.25rem;
            background: linear-gradient(135deg, rgba(27, 73, 101, 0.05) 0%, rgba(98, 182, 203, 0.05) 100%);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            border: 1px solid rgba(27, 73, 101, 0.1);
        }

        .credentials-info strong {
            color: var(--primary-color);
            font-weight: 600;
        }

        .credentials-info small {
            line-height: 1.8;
            color: var(--text-secondary);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .login-box {
                padding: 2rem;
                margin: 1rem;
            }

            .logo-container img {
                width: 60px;
                height: 60px;
            }

            .login-box h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <script>window.URLROOT = '<?php echo URLROOT; ?>';</script>
    <div class="login-container">
        <div class="login-box">
            <div class="logo-container">
                <img src="<?php echo URLROOT; ?>/public/assets/images/logo.svg" alt="Logo Comedor Universitario">
            </div>
            <h1>Comedor Universitario</h1>
            <p>Sistema de Control de Inventario</p>

            <?php if (!empty($data['error'])): ?>
                <div class="error-message">
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['login_success'])): ?>
                <div class="success-message" style="background: rgba(46, 204, 113, 0.1); color: #27ae60; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border-left: 4px solid #27ae60;">
                    <?php echo $_SESSION['login_success']; unset($_SESSION['login_success']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo URLROOT; ?>/login">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required placeholder="usuario@comedor.edu" autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
                </div>

                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="<?php echo URLROOT; ?>/login/recuperar" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">¿Olvidaste tu contraseña?</a>
            </div>

            
        </div>
    </div>
</body>
</html>
