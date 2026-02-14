<?php
/**
 * Punto de Entrada Principal
 */

// Cargar Configuración
require_once dirname(dirname(__FILE__)) . '/config/config.php';

// Configuración básica de errores - Deshabilitar en producción
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autocargar Librerías del Núcleo
spl_autoload_register(function($className) {
    if (file_exists(APPROOT . '/app/core/' . $className . '.php')) {
        require_once APPROOT . '/app/core/' . $className . '.php';
    }
});

// Inicializar Librería Principal (Router)
$router = new Router();

// Agregar Rutas
$router->add('home', 'Home', 'index');
$router->add('', 'Home', 'index');

// Despachar Router
$router->dispatch($_SERVER['REQUEST_URI']);
