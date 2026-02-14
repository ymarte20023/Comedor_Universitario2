<?php
/* ============================================
   app/controllers/Api.php
   Endpoints JSON para el frontend (MVVM)
   Todas las respuestas son application/json
   ============================================ */

class Api extends Controller {
    // Modelos compartidos
    private $productoModel;
    private $loteModel;
    private $categoriaModel;

    public function __construct() {
        // Configuración base para TODOS los endpoints
        header('Content-Type: application/json');
        
        // Seguridad: todos los endpoints requieren login
        if (!Auth::isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        // Inicializar modelos de uso común
        $this->productoModel = $this->model('ProductoModel');
        $this->loteModel = $this->model('LoteModel');
        $this->categoriaModel = $this->model('CategoriaModel');
    }

    // ------------------------------------------
    // GET /api/dashboard - Datos para el panel principal
    // ------------------------------------------
    public function dashboard() {
        try {
            // Alertas
            $stockCritico = $this->productoModel->checkStockCritico();
            $lotesProximosVencer = $this->loteModel->getLotesProximosVencer();
            
            // Conteos para tarjetas
            $productos = $this->productoModel->getAllWithDetails();
            $categorias = $this->categoriaModel->getAll();
            $proveedorModel = $this->model('ProveedorModel');
            $proveedores = $proveedorModel->getAll();

            echo json_encode([
                'stats' => [
                    'total_productos' => count($productos),
                    'stock_critico' => count($stockCritico),
                    'lotes_vencen' => count($lotesProximosVencer),
                    'total_categorias' => count($categorias),
                    'total_proveedores' => count($proveedores),
                    'total_usuarios' => $this->model('UsuarioModel')->countActive()
                ],
                'stock_critico_list' => $stockCritico,      // Productos con bajo stock
                'lotes_vencen_list' => $lotesProximosVencer // Próximos a caducar
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // ------------------------------------------
    // GET /api/productos - Listado completo de productos
    // ------------------------------------------
    public function productos() {
        $productos = $this->productoModel->getAllWithDetails();
        echo json_encode($productos);
    }

    // ------------------------------------------
    // GET /api/lotes - Listado completo de lotes
    // ------------------------------------------
    public function lotes() {
        $lotes = $this->loteModel->getAllWithDetails();
        echo json_encode($lotes);
    }

    // ------------------------------------------
    // POST /api/consumir - Registrar consumo (FIFO)
    // Body esperado: { "producto_id": 1, "cantidad": 5 }
    // ------------------------------------------
    public function consumir() {
        // Verificar método
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            return;
        }

        // Leer y validar datos
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['producto_id']) || !isset($data['cantidad'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        // Procesar consumo con política FIFO
        require_once APPROOT . '/app/models/ControlCaducidad.php';
        $controlCaducidad = new ControlCaducidad();
        
        $result = $controlCaducidad->consumirPorFIFO(
            $data['producto_id'],
            $data['cantidad'],
            $_SESSION['usuario_id'],
            'Consumo API MVVM'
        );

        echo json_encode($result);
    }
}