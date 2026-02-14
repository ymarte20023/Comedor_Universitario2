<?php
/* ============================================
   app/controllers/Lotes.php
   CRUD de lotes - Gestión de inventario por lotes
   Acceso: usuarios autenticados (cualquier rol)
   ============================================ */

class Lotes extends Controller {
    private $loteModel;
    private $productoModel;
    private $movimientoModel;

    public function __construct() {
        AuthMiddleware::handle(); // Cualquier usuario autenticado
        $this->loteModel = $this->model('LoteModel');
        $this->productoModel = $this->model('ProductoModel');
        $this->movimientoModel = $this->model('MovimientoModel');
    }

    // ------------------------------------------
    // GET /lotes - Listado de lotes activos
    // Muestra todos los lotes + alerta de próximos a vencer
    // ------------------------------------------
    public function index() {
        $lotes = $this->loteModel->getAllWithDetails();
        $lotesProximosVencer = $this->loteModel->getLotesProximosVencer();

        $this->view('lotes/index', [
            'title' => 'Gestión de Lotes',
            'lotes' => $lotes,
            'lotesProximosVencer' => $lotesProximosVencer
        ]);
    }

    // ------------------------------------------
    // GET  /lotes/crear - Formulario
    // POST /lotes/crear - Guardar nuevo lote + movimiento de entrada
    // ------------------------------------------
    public function crear() {
        $productos = $this->productoModel->getAllWithDetails();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'producto_id' => $_POST['producto_id'],
                'numero_lote' => trim($_POST['numero_lote']),
                'cantidad' => $_POST['cantidad'],
                'fecha_ingreso' => $_POST['fecha_ingreso'],
                'fecha_caducidad' => $_POST['fecha_caducidad'],
                'ubicacion' => trim($_POST['ubicacion'])
            ];

            $loteId = $this->loteModel->create($data);
            if ($loteId) {
                // Registrar movimiento de inventario (entrada)
                $this->movimientoModel->registrarEntrada(
                    $data['producto_id'],
                    $loteId,
                    $data['cantidad'],
                    $_SESSION['usuario_id'],
                    'Ingreso de nuevo lote'
                );

                header('Location: ' . URLROOT . '/lotes');
                exit;
            }
        }

        $this->view('lotes/crear', [
            'title' => 'Registrar Lote',
            'productos' => $productos
        ]);
    }

    // ------------------------------------------
    // GET  /lotes/editar/{id} - Formulario con datos
    // POST /lotes/editar/{id} - Actualizar lote (sin movimiento)
    // ------------------------------------------
    public function editar($id) {
        $lote = $this->loteModel->getById($id);
        $productos = $this->productoModel->getAllWithDetails();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'producto_id' => $_POST['producto_id'],
                'numero_lote' => trim($_POST['numero_lote']),
                'cantidad' => $_POST['cantidad'],
                'fecha_ingreso' => $_POST['fecha_ingreso'],
                'fecha_caducidad' => $_POST['fecha_caducidad'],
                'ubicacion' => trim($_POST['ubicacion'])
            ];

            if ($this->loteModel->update($data)) {
                // NOTA: No se registra movimiento en edición
                // Solo se actualizan datos del lote
                header('Location: ' . URLROOT . '/lotes');
                exit;
            }
        }

        $this->view('lotes/editar', [
            'title' => 'Editar Lote',
            'lote' => $lote,
            'productos' => $productos
        ]);
    }

    // ------------------------------------------
    // POST /lotes/deshabilitar/{id} - Soft delete
    // ------------------------------------------
    public function deshabilitar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->loteModel->disable($id)) {
                header('Location: ' . URLROOT . '/lotes');
                exit;
            }
        }
    }

    // ------------------------------------------
    // GET /lotes/inactivos - Listado de lotes deshabilitados
    // ------------------------------------------
    public function inactivos() {
        $lotes = $this->loteModel->getInactive();

        $this->view('lotes/inactivos', [
            'title' => 'Lotes Deshabilitados',
            'lotes' => $lotes
        ]);
    }

    // ------------------------------------------
    // POST /lotes/activar/{id} - Restaurar lote
    // ------------------------------------------
    public function activar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->loteModel->activate($id)) {
                header('Location: ' . URLROOT . '/lotes/inactivos');
                exit;
            }
        }
    }
}