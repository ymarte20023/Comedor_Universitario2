<?php
/* ============================================
   app/controllers/Proveedores.php
   CRUD de proveedores - Solo administradores
   Acceso: EXCLUSIVO rol 'administrador'
   ============================================ */

class Proveedores extends Controller {
    private $proveedorModel;

    public function __construct() {
        // 🔒 SOLO administradores - más restrictivo que otros controladores
        AuthMiddleware::handle('administrador');
        $this->proveedorModel = $this->model('ProveedorModel');
    }

    // ------------------------------------------
    // GET /proveedores - Lista proveedores activos
    // ------------------------------------------
    public function index() {
        $this->view('proveedores/index', [
            'title' => 'Gestión de Proveedores',
            'proveedores' => $this->proveedorModel->getAll()
        ]);
    }

    // ------------------------------------------
    // GET  /proveedores/crear - Formulario
    // POST /proveedores/crear - Guardar nuevo
    // ------------------------------------------
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitizar entrada
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'nombre' => trim($_POST['nombre']),
                'contacto' => trim($_POST['contacto']),
                'telefono' => trim($_POST['telefono']),
                'email' => trim($_POST['email']),
                'direccion' => trim($_POST['direccion']),
                'error' => ''
            ];

            // Validaciones
            if (empty($data['nombre'])) {
                $data['error'] = 'El nombre del proveedor es obligatorio';
            }
            
            // Contacto: solo letras y espacios
            if (empty($data['error']) && !empty($data['contacto']) && 
                !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['contacto'])) {
                $data['error'] = 'El contacto solo puede contener letras y espacios';
            }

            if (empty($data['error'])) {
                if ($this->proveedorModel->create($data)) {
                    redirect('/proveedores');
                }
                die('Error al crear proveedor');
            }
            
            $this->view('proveedores/crear', $data);
        } else {
            // Formulario vacío
            $this->view('proveedores/crear', [
                'nombre' => '', 'contacto' => '', 'telefono' => '',
                'email' => '', 'direccion' => '', 'error' => ''
            ]);
        }
    }

    // ------------------------------------------
    // GET  /proveedores/editar/{id} - Formulario con datos
    // POST /proveedores/editar/{id} - Actualizar
    // ------------------------------------------
    public function editar($id) {
        $proveedor = $this->proveedorModel->getById($id);
        
        // Verificar que existe
        if (!$proveedor) {
            redirect('/proveedores');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'id' => $id,
                'nombre' => trim($_POST['nombre']),
                'contacto' => trim($_POST['contacto']),
                'telefono' => trim($_POST['telefono']),
                'email' => trim($_POST['email']),
                'direccion' => trim($_POST['direccion']),
                'error' => ''
            ];

            // Mismas validaciones que en crear
            if (empty($data['nombre'])) {
                $data['error'] = 'El nombre del proveedor es obligatorio';
            }

            if (empty($data['error']) && !empty($data['contacto']) && 
                !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['contacto'])) {
                $data['error'] = 'El contacto solo puede contener letras y espacios';
            }

            if (empty($data['error'])) {
                if ($this->proveedorModel->update($id, $data)) {
                    redirect('/proveedores');
                }
                die('Error al actualizar proveedor');
            }
            
            $this->view('proveedores/editar', $data);
        } else {
            // Cargar datos actuales
            $this->view('proveedores/editar', [
                'id' => $id,
                'nombre' => $proveedor['nombre'],
                'contacto' => $proveedor['contacto'],
                'telefono' => $proveedor['telefono'],
                'email' => $proveedor['email'],
                'direccion' => $proveedor['direccion'],
                'error' => ''
            ]);
        }
    }

    // ------------------------------------------
    // POST /proveedores/eliminar/{id} - Soft delete
    // ------------------------------------------
    public function eliminar($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->proveedorModel->deactivate($id)) {
                redirect('/proveedores');
            }
            die('Error al deshabilitar proveedor');
        }
        redirect('/proveedores');
    }

    // ------------------------------------------
    // GET /proveedores/inactivos - Lista deshabilitados
    // ------------------------------------------
    public function inactivos() {
        $this->view('proveedores/inactivos', [
            'title' => 'Proveedores Deshabilitados',
            'proveedores' => $this->proveedorModel->getInactive()
        ]);
    }

    // ------------------------------------------
    // POST /proveedores/activar/{id} - Restaurar
    // ------------------------------------------
    public function activar($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->proveedorModel->activate($id)) {
                redirect('/proveedores/inactivos');
            }
            die('Error al activar proveedor');
        }
        redirect('/proveedores/inactivos');
    }
}