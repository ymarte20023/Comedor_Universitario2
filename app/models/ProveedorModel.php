<?php
/**
 * Modelo de Proveedor
 */
class ProveedorModel extends Model {
    protected function getTableName() {
        return 'proveedores';
    }

    /**
     * Obtener todos los proveedores activos
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM proveedores WHERE estado = TRUE ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    /**
    * Obtener proveedor por ID
    */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM proveedores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Crear nuevo proveedor
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO proveedores (nombre, contacto, telefono, email, direccion) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['contacto'] ?? null,
            $data['telefono'] ?? null,
            $data['email'] ?? null,
            $data['direccion'] ?? null
        ]);
    }

    /**
    * Actualizar proveedor
    */
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE proveedores SET nombre = ?, contacto = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['contacto'],
            $data['telefono'],
            $data['email'],
            $data['direccion'],
            $id
        ]);
    }

    /**
    * Deshabilitar proveedor (soft delete)
    */
    public function deactivate($id) {
        $stmt = $this->db->prepare("UPDATE proveedores SET estado = FALSE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
    * Obtener proveedores inactivos (soft delete)
    */
    public function getInactive() {
        $stmt = $this->db->query("SELECT * FROM proveedores WHERE estado = FALSE ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    /**
    * Reactivar proveedor
    */
    public function activate($id) {
        $stmt = $this->db->prepare("UPDATE proveedores SET estado = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
