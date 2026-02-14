<?php
/* ============================================
   app/controllers/Home.php
   Página de inicio / landing page
   Acceso: público (sin autenticación)
   ============================================ */

class Home extends Controller {
    
    // ------------------------------------------
    // GET /home - Página de bienvenida
    // Muestra información general del sistema
    // ------------------------------------------
    public function index() {
        $data = [
            'title' => 'Bienvenido al Comedor Universitario',
            'description' => 'Sistema de gestión integral con arquitectura MVC y MVVM.'
        ];
        
        // Cargar vista directamente (sin método view())
        require_once APPROOT . '/app/views/home_view.php';
    }
}
