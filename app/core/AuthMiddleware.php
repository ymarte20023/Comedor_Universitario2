<?php
/* ============================================
   app/middleware/AuthMiddleware.php
   Protección de rutas - Verifica login y roles
   Métodos estáticos - Ejecutar al inicio de controladores
   ============================================ */

class AuthMiddleware {
    
    // ------------------------------------------
    // Verifica autenticación y opcionalmente rol
    // @param string|null $role - Rol requerido (null = solo login)
    // ------------------------------------------
    public static function handle($role = null) {
        // 🔒 Paso 1: ¿Usuario logueado?
        if (!Auth::isLoggedIn()) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }
        
        // 🔒 Paso 2: ¿Tiene el rol requerido? (si se especificó)
        if ($role && !Auth::checkRole($role)) {
            header('Location: ' . URLROOT . '/acceso-denegado');
            exit;
        }
    }
}
