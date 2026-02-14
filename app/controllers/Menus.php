<?php
/* ============================================
   app/controllers/Menus.php
   Planificación y consumo de menús
   Acceso: todos excepto inventario (solo admin/caja)
   ============================================ */

class Menus extends Controller {
    private $menuModel;
    private $productoModel;

    public function __construct() {
        AuthMiddleware::handle(); // Requiere login
        
        // ❌ Rol inventario NO puede acceder a menús
        if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'inventario') {
            redirect('/dashboard');
            exit;
        }

        $this->menuModel = $this->model('MenuModel');
        $this->productoModel = $this->model('ProductoModel');
    }

    // ------------------------------------------
    // GET /menus - Listado de menús planificados
    // ------------------------------------------
    public function index() {
        $this->view('menus/index', [
            'title' => 'Planificación de Menús',
            'menus' => $this->menuModel->getAllWithDetails()
        ]);
    }

    // ------------------------------------------
    // GET  /menus/crear - Formulario
    // POST /menus/crear - Guardar menú + productos asociados
    // ------------------------------------------
    public function crear() {
        $productos = $this->productoModel->getAllWithDetails();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuData = [
                'nombre' => trim($_POST['nombre']),
                'dia_semana' => $_POST['dia_semana'],
                'tipo' => $_POST['tipo'],
                'descripcion' => trim($_POST['descripcion']),
                'fecha' => $_POST['fecha'],
                'activo' => 1
            ];

            $menuId = $this->menuModel->create($menuData);
            if ($menuId) {
                // Asignar productos al menú (cantidad necesaria por ración)
                if (isset($_POST['productos']) && is_array($_POST['productos'])) {
                    foreach ($_POST['productos'] as $productoId => $cantidad) {
                        if ($cantidad > 0) {
                            $this->menuModel->addProducto($menuId, $productoId, $cantidad);
                        }
                    }
                }

                redirect('/menus');
            }
        }

        $this->view('menus/crear', [
            'title' => 'Crear Menú',
            'productos' => $productos
        ]);
    }

    // ------------------------------------------
    // GET  /menus/editar/{id} - Formulario con datos
    // POST /menus/editar/{id} - Actualizar menú y productos
    // ------------------------------------------
    public function editar($id) {
        $menu = $this->menuModel->getByIdWithProducts($id);
        
        if (!$menu) {
            redirect('/menus');
        }

        $productos = $this->productoModel->getAllWithDetails();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menuData = [
                'nombre' => trim($_POST['nombre']),
                'dia_semana' => $_POST['dia_semana'],
                'tipo' => $_POST['tipo'],
                'descripcion' => trim($_POST['descripcion']),
                'fecha' => $_POST['fecha']
            ];

            if ($this->menuModel->update($id, $menuData)) {
                // 🔄 Reemplazar productos: eliminar todos y volver a insertar
                $this->menuModel->clearProductos($id);

                if (isset($_POST['productos']) && is_array($_POST['productos'])) {
                    foreach ($_POST['productos'] as $productoId => $cantidad) {
                        if ($cantidad > 0) {
                            $this->menuModel->addProducto($id, $productoId, $cantidad);
                        }
                    }
                }

                redirect('/menus');
            }
        }

        // Preparar array de productos actuales para el checkbox
        $menuProductos = [];
        if (isset($menu['productos'])) {
            foreach ($menu['productos'] as $prod) {
                $menuProductos[$prod['producto_id']] = $prod['cantidad_necesaria'];
            }
        }

        $this->view('menus/editar', [
            'title' => 'Editar Menú',
            'menu' => $menu,
            'productos' => $productos,
            'menuProductos' => $menuProductos // Para pre-seleccionar en el formulario
        ]);
    }

    // ------------------------------------------
    // POST /menus/consumir/{id} - Ejecutar menú
    // Consume productos del inventario (FIFO) y registra movimientos
    // ------------------------------------------
    public function consumir($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->menuModel->consumirMenu($id, $_SESSION['usuario_id']);
            
            // Guardar resultado para mostrar feedback en la vista
            $_SESSION['menu_consumo_result'] = $result;
            
            redirect('/menus');
        }
    }
}