<?php
/**
 * Categoria Model
 */
class CategoriaModel extends Model {
    protected function getTableName() {
        return 'categorias';
    }

    /**
     * Obtener todas las categorías activas
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categorias WHERE estado = TRUE ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    public function getAllAdmin() {
        $stmt = $this->db->query("SELECT * FROM categorias ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    /**
     * Obtener categorías inactivas
     */ 
    public function getInactive() {
        $stmt = $this->db->query("SELECT * FROM categorias WHERE estado = FALSE ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }

    /**
     *  Obtener categoría por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
    * Crear nueva categoría
    */
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre, descripcion, perecedero, dias_caducidad, estado) VALUES (?, ?, ?, ?, TRUE)");
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['perecedero'] ?? 1,
            $data['dias_caducidad'] ?? null
        ]);
    }

    /**
     * Actualizar categoría existente
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE categorias SET nombre = ?, descripcion = ?, perecedero = ?, dias_caducidad = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['perecedero'],
            $data['dias_caducidad'],
            $id
        ]);
    }

    /**
    * Deshabilitar categoría (soft delete)
    */

    public function disable($id) {
        $stmt = $this->db->prepare("UPDATE categorias SET estado = FALSE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function activate($id) {
        $stmt = $this->db->prepare("UPDATE categorias SET estado = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
