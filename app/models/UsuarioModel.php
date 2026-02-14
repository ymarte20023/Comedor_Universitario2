<?php
/**
 * Modelo de Usuario
 */
class UsuarioModel extends Model {
    protected function getTableName() {
        return 'usuarios';
    }

    /**
     * Buscar usuario por email
     * @param string $email
     * @return array|false
     */

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Crear un nuevo usuario
     * @param array $data
     * @return bool
     */

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['rol'] ?? 'inventario'
        ]);
    }

    /**
     * Obtener todos los usuarios activos
     * @return array
     */

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE estado = 1 ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener todos los usuarios inactivos
     * @return array
     */

    public function getInactive() {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE estado = 0 ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener usuario por ID
     * @param int $id
     * @return object|false
     */

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Actualizar usuario
     * @param array $data
     * @return bool
     */

    public function update($data) {
         // Preparar consulta según si se actualiza la contraseña.
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare("UPDATE usuarios SET nombre = ?, email = ?, password = ?, rol = ? WHERE id = ?");
            return $stmt->execute([
                $data['nombre'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['rol'],
                $data['id']
            ]);
        } else {
            $stmt = $this->db->prepare("UPDATE usuarios SET nombre = ?, email = ?, rol = ? WHERE id = ?");
            return $stmt->execute([
                $data['nombre'],
                $data['email'],
                $data['rol'],
                $data['id']
            ]);
        }
    }
    /**
     * Deshabilitar usuario (Soft Delete)
     * @param int $id
     * @return bool
     */

    public function disable($id) {
        $stmt = $this->db->prepare("UPDATE usuarios SET estado = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Activar usuario
     * @param int $id
     * @return bool
     */
    public function activate($id) {
        $stmt = $this->db->prepare("UPDATE usuarios SET estado = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Contar usuarios activos
     * @return int
     */

    public function countActive() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE estado = 1");
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    /**
     * Establecer token de recuperación de contraseña
     * @param string $email
     * @param string $token
     * @return bool
     */
    public function setResetToken($email, $token) {
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $this->db->prepare("UPDATE usuarios SET reset_token = ?, reset_expires = ? WHERE email = ?");
        return $stmt->execute([$token, $expires, $email]);
    }

    /**
     * Verificar token de recuperación
     * @param string $token
     * @return array|false
     */
    public function verifyResetToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    /**
     * Restablecer contraseña
     * @param string $token
     * @param string $password
     * @return bool
     */
    public function resetPassword($token, $password) {
        $stmt = $this->db->prepare("UPDATE usuarios SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
        return $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $token]);
    }
}
