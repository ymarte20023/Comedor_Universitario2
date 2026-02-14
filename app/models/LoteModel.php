<?php
/**
 * Modelo de Lote - Gestión de inventario por lotes
 */
class LoteModel extends Model {
    protected function getTableName() {
        return 'lotes';
    }

    /**
     * Obtener todos los lotes con detalles del producto
     */
    public function getAllWithDetails() {
        $sql = "SELECT l.*, p.nombre as producto, p.unidad_medida
                FROM lotes l
                JOIN productos p ON l.producto_id = p.id
                WHERE l.estado != 'deshabilitado'
                ORDER BY l.fecha_caducidad ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Obtener lotes inactivos (deshabilitados)
     */
    public function getInactive() {
        $sql = "SELECT l.*, p.nombre as producto, p.unidad_medida
                FROM lotes l
                JOIN productos p ON l.producto_id = p.id
                WHERE l.estado = 'deshabilitado'
                ORDER BY l.fecha_caducidad ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
    * Obtener lote por ID
    */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM lotes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
    * Actualizar lote
    */
    public function update($data) {
        $stmt = $this->db->prepare("UPDATE lotes SET producto_id = ?, numero_lote = ?, cantidad = ?, fecha_ingreso = ?, fecha_caducidad = ?, ubicacion = ? WHERE id = ?");
        return $stmt->execute([
            $data['producto_id'],
            $data['numero_lote'],
            $data['cantidad'],
            $data['fecha_ingreso'],
            $data['fecha_caducidad'],
            $data['ubicacion'],
            $data['id']
        ]);
    }

    /**
    * Deshabilitar lote
    */
    public function disable($id) {
        return $this->updateEstado($id, 'deshabilitado');
    }

    /**
    * Activar lote
    */
    public function activate($id) {
    // El valor predeterminado es 'disponible' al reactivar, suponiendo que la cantidad es > 0.
    // ¿O comprobar la cantidad? Supongamos que está disponible por ahora o usemos la lógica anterior si es compleja.
    // Reactivación simple:
        return $this->updateEstado($id, 'disponible');
    }

    /**
     * Obtener lotes por ID de producto
     */
    public function getByProducto($productoId) {
        $stmt = $this->db->prepare("SELECT * FROM lotes WHERE producto_id = ? AND estado = 'disponible' ORDER BY fecha_caducidad ASC");
        $stmt->execute([$productoId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener lotes próximos a vencer (7 días)
     */
    public function getLotesProximosVencer() {
        $sql = "SELECT l.*, p.nombre as producto, p.unidad_medida,
                       DATEDIFF(l.fecha_caducidad, CURDATE()) as dias_restantes
                FROM lotes l
                JOIN productos p ON l.producto_id = p.id
                WHERE l.estado = 'disponible'
                AND l.fecha_caducidad BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                ORDER BY l.fecha_caducidad ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Actualizar cantidad del lote
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO lotes (producto_id, numero_lote, cantidad, fecha_ingreso, fecha_caducidad, ubicacion) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([
            $data['producto_id'],
            $data['numero_lote'],
            $data['cantidad'],
            $data['fecha_ingreso'],
            $data['fecha_caducidad'],
            $data['ubicacion'] ?? 'Almacén General'
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Actualizar cantidad del lote
     */
    public function updateCantidad($id, $cantidad) {
        $stmt = $this->db->prepare("UPDATE lotes SET cantidad = ? WHERE id = ?");
        return $stmt->execute([$cantidad, $id]);
    }

   /**
     * Actualizar estado del lote
     */
    public function updateEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE lotes SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }
}
