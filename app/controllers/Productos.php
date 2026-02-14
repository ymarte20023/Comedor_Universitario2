<?php
/* ============================================
   app/controllers/Productos.php
   CRUD de productos - Catálogo de productos
   Acceso: usuarios autenticados (cualquier rol)
   ============================================ */

class Productos extends Controller {
    private $productoModel;
    private $categoriaModel;
    private $proveedorModel;

    public function __construct() {
        AuthMiddleware::handle(); // Cualquier usuario autenticado
        $this->productoModel = $this->model('ProductoModel');
        $this->categoriaModel = $this->model('CategoriaModel');
        $this->proveedorModel = $this->model('ProveedorModel');
    }

    // ------------------------------------------
    // GET /productos - Listado de productos activos
    // Muestra todos los productos + alerta de stock crítico
    // ------------------------------------------
    public function index() {
        $this->view('productos/index', [
            'title' => 'Gestión de Productos',
            'productos' => $this->productoModel->getAllWithDetails(),
            'stockCritico' => $this->productoModel->checkStockCritico() // Productos con stock < mínimo
        ]);
    }

    // ------------------------------------------
    // GET  /productos/crear - Formulario
    // POST /productos/crear - Guardar nuevo producto
    // ------------------------------------------
    public function crear() {
        $categorias = $this->categoriaModel->getAll();
        $proveedores = $this->proveedorModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => trim($_POST['nombre']),
                'categoria_id' => $_POST['categoria_id'],
                'unidad_medida' => $_POST['unidad_medida'],
                'stock_minimo' => $_POST['stock_minimo'],
                'stock_maximo' => $_POST['stock_maximo'],
                'precio_unitario' => $_POST['precio_unitario'],
                'proveedor_id' => $_POST['proveedor_id']
            ];

            // Validación: solo letras y espacios
            if (!empty($data['nombre']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['nombre'])) {
                $data['error'] = 'El nombre solo puede contener letras y espacios';
                $data['categorias'] = $categorias;
                $data['proveedores'] = $proveedores;
                $this->view('productos/crear', $data);
                return;
            }

            if ($this->productoModel->create($data)) {
                redirect('/productos');
            }
        }

        $this->view('productos/crear', [
            'title' => 'Crear Producto',
            'categorias' => $categorias,
            'proveedores' => $proveedores
        ]);
    }

    // ------------------------------------------
    // GET  /productos/editar/{id} - Formulario con datos
    // POST /productos/editar/{id} - Actualizar producto
    // ------------------------------------------
    public function editar($id) {
        $producto = $this->productoModel->getById($id);
        $categorias = $this->categoriaModel->getAll();
        $proveedores = $this->proveedorModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'nombre' => trim($_POST['nombre']),
                'categoria_id' => $_POST['categoria_id'],
                'unidad_medida' => $_POST['unidad_medida'],
                'stock_minimo' => $_POST['stock_minimo'],
                'stock_maximo' => $_POST['stock_maximo'],
                'precio_unitario' => $_POST['precio_unitario'],
                'proveedor_id' => $_POST['proveedor_id']
            ];

            // Misma validación que en crear
            if (!empty($data['nombre']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['nombre'])) {
                $data['error'] = 'El nombre solo puede contener letras y espacios';
                $data['producto'] = $producto;
                $data['categorias'] = $categorias;
                $data['proveedores'] = $proveedores;
                $this->view('productos/editar', $data);
                return;
            }

            if ($this->productoModel->update($data)) {
                redirect('/productos');
            }
        }

        $this->view('productos/editar', [
            'title' => 'Editar Producto',
            'producto' => $producto,
            'categorias' => $categorias,
            'proveedores' => $proveedores
        ]);
    }

    // ------------------------------------------
    // POST /productos/deshabilitar/{id} - Soft delete
    // ------------------------------------------
    public function deshabilitar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->productoModel->disable($id)) {
                redirect('/productos');
            }
        }
    }

    // ------------------------------------------
    // GET /productos/inactivos - Listado de productos deshabilitados
    // ------------------------------------------
    public function inactivos() {
        $this->view('productos/inactivos', [
            'title' => 'Productos Deshabilitados',
            'productos' => $this->productoModel->getInactive()
        ]);
    }

    // ------------------------------------------
    // POST /productos/activar/{id} - Restaurar producto
    // ------------------------------------------
    public function activar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->productoModel->activate($id)) {
                redirect('/productos/inactivos');
            }
        }
    }
}