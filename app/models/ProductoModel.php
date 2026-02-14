<?php
/**
 * Modelo de Producto
 */
class ProductoModel extends Model {
    protected function getTableName() {
        return 'productos';
    }

    /**
     * Obtener todos los productos con detalles (Solo activos por defecto)
     * @param bool $includeDisabled Si es true, incluye productos deshabilitados
     */
    public function getAllWithDetails($includeDisabled = false) {
        $whereClause = $includeDisabled ? "" : "WHERE p.estado = TRUE";
        
        $sql = "SELECT p.*, 
                       c.nombre as categoria, 
                       pr.nombre as proveedor,
                       COALESCE(SUM(CASE WHEN l.estado = 'disponible' THEN l.cantidad ELSE 0 END), 0) as stock_actual
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                LEFT JOIN proveedores pr ON p.proveedor_id = pr.id
                LEFT JOIN lotes l ON p.id = l.producto_id
                $whereClause
                GROUP BY p.id
                ORDER BY p.nombre ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Obtener producto por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Verificar productos con stock crítico (stock actual < stock mínimo)
     */
    public function checkStockCritico() {
        $sql = "SELECT p.*, 
                       c.nombre as categoria,
                       COALESCE(SUM(CASE WHEN l.estado = 'disponible' THEN l.cantidad ELSE 0 END), 0) as stock_actual
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                LEFT JOIN lotes l ON p.id = l.producto_id
                GROUP BY p.id
                HAVING stock_actual < p.stock_minimo
                ORDER BY stock_actual ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
    * Crear nuevo producto
    */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO productos (nombre, categoria_id, unidad_medida, stock_minimo, stock_maximo, precio_unitario, proveedor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['categoria_id'],
            $data['unidad_medida'],
            $data['stock_minimo'],
            $data['stock_maximo'],
            $data['precio_unitario'],
            $data['proveedor_id']
        ]);
    }

    /**
     * Actualizar producto
     */
    public function update($data) {
        $stmt = $this->db->prepare("UPDATE productos SET nombre = ?, categoria_id = ?, unidad_medida = ?, stock_minimo = ?, stock_maximo = ?, precio_unitario = ?, proveedor_id = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['categoria_id'],
            $data['unidad_medida'],
            $data['stock_minimo'],
            $data['stock_maximo'],
            $data['precio_unitario'],
            $data['proveedor_id'],
            $data['id']
        ]);
    }

    /**
    * Deshabilitar producto (Soft delete)
    */
    public function disable($id) {
        $stmt = $this->db->prepare("UPDATE productos SET estado = FALSE WHERE id = ?");
        return $stmt->execute([$id]);
    }


    /**
    * Obtener productos inactivos
    */
    public function getInactive() {
        $sql = "SELECT p.*, 
                       c.nombre as categoria, 
                       pr.nombre as proveedor
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                LEFT JOIN proveedores pr ON p.proveedor_id = pr.id
                WHERE p.estado = FALSE
                ORDER BY p.nombre ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
    * Reactivar producto
    */
    public function activate($id) {
        $stmt = $this->db->prepare("UPDATE productos SET estado = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
    * Eliminar producto (Borrado físico)
    */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
