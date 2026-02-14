<?php
/* ============================================
   app/core/Database.php
   Conexión PDO a MySQL - Patrón Singleton
   No instanciar - Usar Database::getInstance()
   ============================================ */

class Database {
    // Configuración desde constantes globales
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;      // Objeto PDO
    private $error;    // Último error
    private static $instance = null; // Instancia única

    // ------------------------------------------
    // Constructor privado - Solo se llama una vez
    // Configura DSN y opciones PDO
    // ------------------------------------------
    private function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [
            PDO::ATTR_PERSISTENT => true,     // Conexión persistente
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lanza excepciones
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Arrays asociativos
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Error de conexión BD: " . $this->error);
        }
    }

    // ------------------------------------------
    // Obtener la instancia única del objeto PDO
    // @return object PDO - Conexión a base de datos
    // ------------------------------------------
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->dbh; // Retorna el PDO, no el objeto Database
    }

    // 🚫 Prevenir clonación y deserialización
    private function __clone() {}
    public function __wakeup() {}
}