<?php
/**
 * Categorias Controller
 * Admin Only
 */
class Categorias extends Controller {
    private $categoriaModel;

    public function __construct() {
        AuthMiddleware::handle('administrador'); // Strict admin access
        $this->categoriaModel = $this->model('CategoriaModel');
    }

    public function index() {
        $categorias = $this->categoriaModel->getAll(); // Only active

        $data = [
            'title' => 'Gestión de Categorías',
            'categorias' => $categorias
        ];

        $this->view('categorias/index', $data);
    }

    public function inactivos() {
        $categorias = $this->categoriaModel->getInactive();

        $data = [
            'title' => 'Categorías Deshabilitadas',
            'categorias' => $categorias
        ];

        $this->view('categorias/inactivos', $data);
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion']),
                'perecedero' => isset($_POST['perecedero']) ? 1 : 0,
                'dias_caducidad' => !empty($_POST['dias_caducidad']) ? $_POST['dias_caducidad'] : null
            ];

            if (!empty($data['nombre']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['nombre'])) {
                $data['error'] = 'El nombre de la categoría solo puede contener letras y espacios';
                $this->view('categorias/crear', $data);
                return;
            }

            if ($this->categoriaModel->create($data)) {
                header('Location: ' . URLROOT . '/categorias');
                exit;
            }
        }

        $data = [
            'title' => 'Nueva Categoría'
        ];

        $this->view('categorias/crear', $data);
    }

    public function editar($id) {
        $categoria = $this->categoriaModel->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => trim($_POST['nombre']),
                'descripcion' => trim($_POST['descripcion']),
                'perecedero' => isset($_POST['perecedero']) ? 1 : 0,
                'dias_caducidad' => !empty($_POST['dias_caducidad']) ? $_POST['dias_caducidad'] : null
            ];

            if (!empty($data['nombre']) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $data['nombre'])) {
                $data['error'] = 'El nombre de la categoría solo puede contener letras y espacios';
                $data['categoria'] = $categoria; // Preserve category ID for the view
                $this->view('categorias/editar', $data);
                return;
            }

            if ($this->categoriaModel->update($id, $data)) {
                header('Location: ' . URLROOT . '/categorias');
                exit;
            }
        }

        $data = [
            'title' => 'Editar Categoría',
            'categoria' => $categoria
        ];

        $this->view('categorias/editar', $data);
    }

    public function deshabilitar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->categoriaModel->disable($id);
            header('Location: ' . URLROOT . '/categorias');
            exit;
        }
    }

    public function activar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->categoriaModel->activate($id);
            header('Location: ' . URLROOT . '/categorias/inactivos');
            exit;
        }
    }
}
