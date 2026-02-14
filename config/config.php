<?php
/*2. Documentacion*/
/*Archivo: /config/config.php*/
/* Configuración global de la aplicación*/

// ==========================================
// Base de datos
// ==========================================
define('DB_HOST', 'localhost');     // Servidor MySQL
define('DB_USER', 'root');          // Usuario
define('DB_PASS', '');             // Contraseña (¡cambiar en producción!)
define('DB_NAME', 'comedor_universitario'); // Nombre BD

// ==========================================
// Rutas del sistema
// ==========================================
// Ruta ABSOLUTA en el servidor (para includes)
if (!defined('APPROOT')) {
    define('APPROOT', dirname(dirname(__FILE__)));
}

// URL BASE del sitio (para enlaces web)
if (!isset($_SERVER['HTTP_HOST'])) {
    define('URLROOT', 'http://localhost/Comedor_Universitario');
} else {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $folder = str_replace('/public', '', dirname($_SERVER['PHP_SELF']));
    define('URLROOT', $protocol . '://' . $_SERVER['HTTP_HOST'] . $folder);
}

// ==========================================
// Información del sitio
// ==========================================
define('SITENAME', 'Comedor Universitario'); // Nombre del sistema
define('APPVERSION', '1.0.0');               // Versión actual