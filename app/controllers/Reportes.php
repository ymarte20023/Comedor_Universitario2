<?php
/* ============================================
   app/controllers/Reportes.php
   Generación de reportes en PDF/Excel
   Acceso: SOLO administradores e inventario
   ============================================ */

class Reportes extends Controller {
    
    public function __construct() {
        AuthMiddleware::handle(); // Requiere login
        
        // 🔒 Restricción: solo admin e inventario
        $rolesPermitidos = ['administrador', 'inventario'];
        if (!isset($_SESSION['usuario_rol']) || !in_array($_SESSION['usuario_rol'], $rolesPermitidos)) {
            redirect('/dashboard');
            exit;
        }
    }

    // ------------------------------------------
    // GET /reportes - Página principal de reportes
    // Muestra opciones disponibles
    // ------------------------------------------
    public function index() {
        $this->view('reportes/index', [
            'title' => 'Generador de Reportes'
        ]);
    }

    // ------------------------------------------
    // GET /reportes/inventario - Exportar reporte
    // Genera PDF/Excel con stock actual, crítico y vencimientos
    // ------------------------------------------
    public function inventario() {
        require_once APPROOT . '/app/core/ReportGenerator.php';
        ReportGenerator::generarReporteInventario(); // Forzar descarga
    }

    // ------------------------------------------
    // GET /reportes/consumo - Exportar reporte por fechas
    // Parámetros opcionales: ?fecha_inicio=YYYY-MM-DD&fecha_fin=YYYY-MM-DD
    // Por defecto: últimos 30 días
    // ------------------------------------------
    public function consumo() {
        // Rango de fechas (default: últimos 30 días)
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-30 days'));
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        require_once APPROOT . '/app/core/ReportGenerator.php';
        ReportGenerator::generarReporteConsumo($fechaInicio, $fechaFin);
    }
}