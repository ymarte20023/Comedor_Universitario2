<?php
/* ============================================
   app/core/Model.php
   Clase base abstracta para todos los modelos
   Proporciona conexión PDO a los modelos hijos
   ============================================ */

abstract class Model {
    // Objeto PDO - disponible en todos los modelos
    protected $db;

    public function __construct() {
        // Obtiene la instancia única de PDO (Singleton)
        $this->db = Database::getInstance();
    }

    // ------------------------------------------
    // 🔴 OBLIGATORIO: Cada modelo debe definir su tabla
    // @return string - Nombre de la tabla en BD
    // ------------------------------------------
    abstract protected function getTableName();
}