<?php
/* ============================================
   app/helpers/Auth.php
   Autenticación y manejo de sesión
   Métodos estáticos - No instanciar
   ============================================ */

class Auth {
    
    // ------------------------------------------
    // Inicia sesión PHP si no está activa
    // ------------------------------------------
    private static function initSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ------------------------------------------
    // Autenticar usuario con email y contraseña
    // Retorna: bool - true si login exitoso
    // Efecto: Guarda id, rol y nombre en sesión
    // ------------------------------------------
    public static function login($email, $password) {
        self::initSession();
        
        // Cargar modelo manualmente (no pasa por autoloader)
        require_once APPROOT . '/app/models/UsuarioModel.php';
        $userModel = new UsuarioModel();
        $usuario = $userModel->findByEmail($email);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            return true;
        }

        return false;
    }
    
    // ------------------------------------------
    // Cerrar sesión - destruye todos los datos
    // ------------------------------------------
    public static function logout() {
        self::initSession();
        session_unset();
        session_destroy();
    }

    // ------------------------------------------
    // Verificar si hay sesión activa
    // Retorna: bool
    // ------------------------------------------
    public static function isLoggedIn() {
        self::initSession();
        return isset($_SESSION['usuario_id']);
    }

    // ------------------------------------------
    // Verificar rol específico del usuario
    // Retorna: bool
    // ------------------------------------------
    public static function checkRole($role) {
        self::initSession();
        return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === $role;
    }
}