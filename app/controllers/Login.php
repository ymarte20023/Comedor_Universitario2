<?php
/* ============================================
   app/controllers/Login.php
   Autenticación, recuperación y cierre de sesión
   Acceso: público (redirige si ya está logueado)
   ============================================ */

class Login extends Controller {
    
    // ------------------------------------------
    // GET  /login  - Formulario de acceso
    // POST /login  - Procesar autenticación
    // ------------------------------------------
    public function index() {
        // Redirigir si ya está logueado
        if (Auth::isLoggedIn()) {
            header('Location: ' . URLROOT . '/dashboard');
            exit;
        }

        // Mostrar error de sesión anterior y limpiarlo
        $data = [
            'title' => 'Iniciar Sesión',
            'error' => $_SESSION['login_error'] ?? ''
        ];
        unset($_SESSION['login_error']);

        // Procesar POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (Auth::login($email, $password)) {
                header('Location: ' . URLROOT . '/dashboard');
                exit;
            } else {
                $_SESSION['login_error'] = 'Credenciales incorrectas o cuenta inactiva.';
                header('Location: ' . URLROOT . '/login');
                exit;
            }
        }

        $this->view('login_view', $data);
    }

    // ------------------------------------------
    // GET /logout - Cerrar sesión
    // ------------------------------------------
    public function logout() {
        Auth::logout();
        header('Location: ' . URLROOT . '/login');
        exit;
    }

    // ------------------------------------------
    // GET  /login/recuperar - Formulario
    // POST /login/recuperar - Enviar solicitud
    // ------------------------------------------
    public function recuperar() {
        $data = [
            'title' => 'Recuperar Contraseña',
            'error' => $_SESSION['recovery_error'] ?? '',
            'success' => $_SESSION['recovery_success'] ?? ''
        ];
        unset($_SESSION['recovery_error'], $_SESSION['recovery_success']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);

            // Regla de negocio: solo emails institucionales
            if (!str_ends_with($email, '@comedor.edu')) {
                $_SESSION['recovery_error'] = 'El correo debe pertenecer al dominio @comedor.edu.';
                header('Location: ' . URLROOT . '/login/recuperar');
                exit;
            }

            $usuarioModel = $this->model('UsuarioModel');
            $usuario = $usuarioModel->findByEmail($email);

            if ($usuario) {
                $token = bin2hex(random_bytes(32));
                $usuarioModel->setResetToken($email, $token);
                $resetLink = URLROOT . '/login/resetear/' . $token;
                
                // MODO DESARROLLO: muestra enlace directamente
                $_SESSION['recovery_success'] = 'Instrucciones enviadas. <strong>Enlace de prueba:</strong> <a href="' . $resetLink . '">' . $resetLink . '</a>';
            } else {
                // Seguridad: no revelar si el correo existe
                $_SESSION['recovery_success'] = 'Si el correo existe, recibirás instrucciones.';
            }

            header('Location: ' . URLROOT . '/login/recuperar');
            exit;
        }

        $this->view('auth/recuperar', $data);
    }

    // ------------------------------------------
    // GET  /login/resetear/{token} - Formulario
    // POST /login/resetear/{token} - Cambiar contraseña
    // ------------------------------------------
    public function resetear($token = null) {
        // Token requerido
        if (!$token) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $usuarioModel = $this->model('UsuarioModel');
        $usuario = $usuarioModel->verifyResetToken($token);

        // Token inválido o expirado
        if (!$usuario) {
            $_SESSION['login_error'] = 'Enlace inválido o expirado.';
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        $data = [
            'title' => 'Restablecer Contraseña',
            'token' => $token,
            'error' => $_SESSION['reset_error'] ?? ''
        ];
        unset($_SESSION['reset_error']);

        // Procesar POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $confirm = $_POST['password_confirm'];

            // Validaciones
            if ($password !== $confirm) {
                $_SESSION['reset_error'] = 'Las contraseñas no coinciden.';
                header('Location: ' . URLROOT . '/login/resetear/' . $token);
                exit;
            }

            // Regla: mínimo 6 chars, mayúscula, número, especial
            if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/', $password)) {
                $_SESSION['reset_error'] = 'La contraseña debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.';
                header('Location: ' . URLROOT . '/login/resetear/' . $token);
                exit;
            }

            if ($usuarioModel->resetPassword($token, $password)) {
                $_SESSION['login_success'] = 'Contraseña restablecida. Puedes iniciar sesión.';
                header('Location: ' . URLROOT . '/login');
                exit;
            } else {
                $_SESSION['reset_error'] = 'Error al restablecer la contraseña.';
                header('Location: ' . URLROOT . '/login/resetear/' . $token);
                exit;
            }
        }

        $this->view('auth/resetear', $data);
    }
}