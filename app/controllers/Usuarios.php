<?php
/* ============================================
   app/controllers/Usuarios.php
   Gestión de usuarios del sistema
   Acceso: EXCLUSIVO rol 'administrador'
   ============================================ */

class Usuarios extends Controller {
    private $usuarioModel;

    public function __construct() {
        AuthMiddleware::handle(); // Requiere login
        
        // 🔒 SOLO administradores - nadie más accede
        if ($_SESSION['usuario_rol'] !== 'administrador') {
            redirect('/');
            exit;
        }

        $this->usuarioModel = $this->model('UsuarioModel');
    }

    // ------------------------------------------
    // GET /usuarios - Lista usuarios activos
    // ------------------------------------------
    public function index() {
        $this->view('usuarios/index', [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $this->usuarioModel->getAll()
        ]);
    }

    // ------------------------------------------
    // GET /usuarios/inactivos - Lista deshabilitados
    // ------------------------------------------
    public function inactivos() {
        $this->view('usuarios/inactivos', [
            'title' => 'Usuarios Deshabilitados',
            'usuarios' => $this->usuarioModel->getInactive()
        ]);
    }

    // ------------------------------------------
    // GET  /usuarios/crear - Formulario
    // POST /usuarios/crear - Guardar nuevo usuario
    // ------------------------------------------
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => trim($_POST['nombre']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'],
                'rol' => $_POST['rol']
            ];

            // 🚫 REGLA DE NEGOCIO: No se pueden crear admins
            if ($data['rol'] === 'administrador') {
                die('No está permitido crear usuarios administradores.');
            }

            // ✅ Validaciones obligatorias
            $errors = [];
            
            // 1. Nombre: solo letras y espacios (con tildes)
            if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['nombre'])) {
                $errors[] = 'El nombre solo debe contener letras y espacios.';
            }

            // 2. Email: SOLO dominio institucional
            if (!str_ends_with($data['email'], '@comedor.edu')) {
                $errors[] = 'El correo debe pertenecer al dominio @comedor.edu.';
            }

            // 3. Password: 6+ chars, mayúscula, número, especial
            if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/', $data['password'])) {
                $errors[] = 'La contraseña debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.';
            }

            if (empty($errors)) {
                // Verificar email duplicado
                if ($this->usuarioModel->findByEmail($data['email'])) {
                    $data['error'] = 'El correo electrónico ya está registrado.';
                } else {
                    if ($this->usuarioModel->create($data)) {
                        redirect('/usuarios');
                    }
                    $data['error'] = 'Error al crear el usuario.';
                }
            } else {
                $data['error'] = implode('<br>', $errors);
            }
        } else {
            // Valores por defecto del formulario
            $data = [
                'nombre' => '',
                'email' => '',
                'password' => '',
                'rol' => 'inventario' // Rol por defecto
            ];
        }

        $data['title'] = 'Registrar Usuario';
        $this->view('usuarios/crear', $data);
    }

    // ------------------------------------------
    // GET  /usuarios/editar/{id} - Formulario con datos
    // POST /usuarios/editar/{id} - Actualizar
    // ------------------------------------------
    public function editar($id) {
        $usuario = $this->usuarioModel->getById($id);
        
        if (!$usuario) {
            redirect('/usuarios');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $id,
                'nombre' => trim($_POST['nombre']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'], // Vacío = no cambiar
                'rol' => $_POST['rol']
            ];

            $errors = [];

            // Mismas validaciones que en crear
            if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['nombre'])) {
                $errors[] = 'El nombre solo debe contener letras y espacios.';
            }

            if (!str_ends_with($data['email'], '@comedor.edu')) {
                $errors[] = 'El correo debe pertenecer al dominio @comedor.edu.';
            }

            // Password solo se valida si se envía uno nuevo
            if (!empty($data['password']) && !preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/', $data['password'])) {
                $errors[] = 'La nueva contraseña debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.';
            }

            // 🚫 No permitir asignar rol admin si no lo era antes
            if ($usuario['rol'] !== 'administrador' && $data['rol'] === 'administrador') {
                $errors[] = 'No está permitido asignar el rol de administrador.';
            }

            if (empty($errors)) {
                if ($this->usuarioModel->update($data)) {
                    redirect('/usuarios');
                }
                $data['error'] = 'Error al actualizar el usuario.';
            } else {
                $data['error'] = implode('<br>', $errors);
            }
        }

        $this->view('usuarios/editar', [
            'title' => 'Editar Usuario',
            'usuario' => $usuario,
            'error' => $data['error'] ?? ''
        ]);
    }

    // ------------------------------------------
    // POST /usuarios/deshabilitar/{id} - Soft delete
    // 🚫 No permite deshabilitarse a sí mismo
    // ------------------------------------------
    public function deshabilitar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 🚫 Evitar que un admin se deshabilite a sí mismo
            if ($id == $_SESSION['usuario_id']) {
                redirect('/usuarios'); // No puedes deshabilitarte
                exit;
            }

            if ($this->usuarioModel->disable($id)) {
                redirect('/usuarios');
            }
        }
    }

    // ------------------------------------------
    // POST /usuarios/activar/{id} - Restaurar
    // ------------------------------------------
    public function activar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->usuarioModel->activate($id)) {
                redirect('/usuarios/inactivos');
            }
        }
    }
}