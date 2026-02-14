<?php
/**
 * Modelo de Menú
 */
class MenuModel extends Model {
    protected function getTableName() {
        return 'menus';
    }

    /**
     * Obtener todos los menús con detalles de productos (solo menús activos)
     */
    public function getAllWithDetails() {
        $sql = "SELECT m.*, 
                       GROUP_CONCAT(CONCAT(p.nombre, ' (', mp.cantidad_necesaria, ' ', p.unidad_medida, ')') SEPARATOR ', ') as ingredientes
                FROM menus m
                LEFT JOIN menu_productos mp ON m.id = mp.menu_id
                LEFT JOIN productos p ON mp.producto_id = p.id
                WHERE m.activo = TRUE
                GROUP BY m.id
                ORDER BY m.fecha DESC, m.dia_semana ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Obtener menú por ID con sus productos
     */
    public function getByIdWithProducts($id) {
        $menu = $this->db->prepare("SELECT * FROM menus WHERE id = ?");
        $menu->execute([$id]);
        $menuData = $menu->fetch();

        if ($menuData) {
            $productos = $this->db->prepare("
                SELECT mp.*, p.nombre, p.unidad_medida, p.stock_minimo
                FROM menu_productos mp
                JOIN productos p ON mp.producto_id = p.id
                WHERE mp.menu_id = ?
            ");
            $productos->execute([$id]);
            $menuData['productos'] = $productos->fetchAll();
        }

        return $menuData;
    }

    /**
     * Crear nuevo menú
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO menus (nombre, dia_semana, tipo, descripcion, fecha, activo) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([
            $data['nombre'],
            $data['dia_semana'],
            $data['tipo'],
            $data['descripcion'],
            $data['fecha'],
            $data['activo'] ?? 1
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Actualizar menú
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE menus SET nombre = ?, dia_semana = ?, tipo = ?, descripcion = ?, fecha = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['dia_semana'],
            $data['tipo'],
            $data['descripcion'],
            $data['fecha'],
            $id
        ]);
    }

    /**
     * Limpiar productos del menú (antes de re-agregar)
     */
    public function clearProductos($menuId) {
        $stmt = $this->db->prepare("DELETE FROM menu_productos WHERE menu_id = ?");
        return $stmt->execute([$menuId]);
    }

    /**
     * Agregar producto al menú
     */
    public function addProducto($menuId, $productoId, $cantidad) {
        $stmt = $this->db->prepare("INSERT INTO menu_productos (menu_id, producto_id, cantidad_necesaria) VALUES (?, ?, ?)");
        return $stmt->execute([$menuId, $productoId, $cantidad]);
    }

    /**
     * Obtener menús de la semana
     */
    public function getMenusSemana($fecha = null) {
        if (!$fecha) {
            $fecha = date('Y-m-d');
        }
        
        $sql = "SELECT * FROM menus 
                WHERE fecha >= ? AND fecha < DATE_ADD(?, INTERVAL 7 DAY)
                AND activo = TRUE
                ORDER BY fecha ASC, tipo ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fecha, $fecha]);
        return $stmt->fetchAll();
    }

    /**
     * Consumir menú (usa lógica FIFO con validación de fecha)
     */
    public function consumirMenu($menuId, $usuarioId) {
        require_once APPROOT . '/app/models/ControlCaducidad.php';
        $controlCaducidad = new ControlCaducidad();
        
        $menu = $this->getByIdWithProducts($menuId);
        if (!$menu) {
            return ['success' => false, 'message' => 'Menú no encontrado'];
        }

        // Validación de fecha: solo se puede consumir en la fecha programada
        $today = date('Y-m-d');
        $menuDate = $menu['fecha'];

        if ($today < $menuDate) {
            return [
                'success' => false,
                'message' => "Este menú está programado para el {$menuDate}. No se puede consumir antes de la fecha programada."
            ];
        }

        if ($today > $menuDate) {
            return [
                'success' => false,
                'message' => "Este menú estaba programado para el {$menuDate}. La fecha ya ha pasado."
            ];
        }

        // Si llegamos aquí, today == menuDate, proceder con el consumo
        $resultados = [];
        $errores = [];

        foreach ($menu['productos'] as $producto) {
            $result = $controlCaducidad->consumirPorFIFO(
                $producto['producto_id'],
                $producto['cantidad_necesaria'],
                $usuarioId,
                "Consumo menú: {$menu['nombre']}"
            );

            if (!$result['success']) {
                $errores[] = "Producto {$producto['nombre']}: {$result['message']}";
            }
            $resultados[] = $result;
        }

        if (!empty($errores)) {
            return [
                'success' => false,
                'message' => 'Algunos productos no pudieron ser consumidos',
                'errores' => $errores,
                'resultados' => $resultados
            ];
        }

                // Marcar menú como inactivo (consumido) después del consumo exitoso
        $this->markAsConsumed($menuId);

        return [
            'success' => true,
            'message' => '✅ Menú consumido exitosamente. Los ingredientes han sido descontados del inventario.',
            'resultados' => $resultados
        ];
    }

    /**
    * Marcar menú como consumido (inactivo)
    */
    private function markAsConsumed($menuId) {
        $stmt = $this->db->prepare("UPDATE menus SET activo = FALSE WHERE id = ?");
        return $stmt->execute([$menuId]);
    }
}
