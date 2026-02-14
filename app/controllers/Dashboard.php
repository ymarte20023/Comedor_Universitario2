<?php
/* ============================================
   app/controllers/Dashboard.php
   Panel principal - Estadísticas y alertas
   Acceso: cualquier usuario autenticado
   ============================================ */

class Dashboard extends Controller {
    // Modelos para alertas de stock y caducidad
    private $productoModel;
    private $loteModel;

    public function __construct() {
        AuthMiddleware::handle(); // Requiere login (cualquier rol)
        $this->productoModel = $this->model('ProductoModel');
        $this->loteModel = $this->model('LoteModel');
    }

    // ------------------------------------------
    // GET /dashboard - Vista principal
    // Muestra tarjetas con estadísticas y alertas
    // ------------------------------------------
    public function index() {
        // 1. ALERTAS - Datos críticos
        $stockCritico = $this->productoModel->checkStockCritico();     // Productos con stock < mínimo
        $lotesProximosVencer = $this->loteModel->getLotesProximosVencer(); // Lotes a vencer en 30 días
        
        // 2. ESTADÍSTICAS - Totales para tarjetas
        $productos = $this->productoModel->getAllWithDetails();
        $categoriaModel = $this->model('CategoriaModel');
        $proveedorModel = $this->model('ProveedorModel');
        
        $categorias = $categoriaModel->getAll();      // Solo activas
        $proveedores = $proveedorModel->getAll();      // Solo activos
        $totalUsuarios = $this->model('UsuarioModel')->countActive(); // Usuarios activos

        // 3. PREPARAR VISTA
        $data = [
            'title' => 'Dashboard - Comedor Universitario',
            // Tarjetas superiores
            'totalProductos' => count($productos),
            'productosStockCritico' => count($stockCritico),
            'lotesVencenProximo' => count($lotesProximosVencer),
            'totalCategorias' => count($categorias),
            'totalProveedores' => count($proveedores),
            'totalUsuarios' => $totalUsuarios,
            // Listados de alertas (tablas)
            'stockCritico' => $stockCritico,
            'lotesProximosVencer' => $lotesProximosVencer
        ];

        $this->view('dashboard/index', $data);
    }
}