<?php

/*1. Documentacion*/
/*Archivo: public/assets/index.php*/

/**
 * Front Controller: 
 * Gestiona todas las peticiones HTTP, configura el entorno, 
 * carga automaticamente las clases necesarias y enruta las solicitudes
 * al controlador y metodo correspondiente.
 * 
 * @package    InventarioApp
 * @subpackage Public
 * @author     Miguel Camejo (MiguelIgnacio03), Josfrancis Torres (JosTorres28), Alanys Arcaya (ymarte20023)
 * @version    1.0.0 
 */

/** 2. Cargar de configuración
*
* Incluye el archivo de config.php que contiene:  
* - Definición de rutas (APPROOT, URLROOT)
* - Configuración de la base de datos.
* - Parametro globales de la palicación. 
*/
require_once dirname(dirname(__FILE__)) . '/config/config.php';


/** 3. Informe de errores básicos: deshabilitar en producción
* Activa la visualización de todos los errores produccion.
*
* E_All: muestra todos los errores, advertencias y avisos.
* display_errors: muestra los errores en pantalla.
*
* @TODO Cambia a 0 en entorno de produccion.
* @see https://www.php.net/manual/es/function.error-reporting.php
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);


/** 4. Autoload de clases. 
* Registra una función anónima para la carga automática de clases.
* Busca archivos .php en los directorios core, models y controllers.
*
* @param string $className Nombre completo de la clase a cargar
* @return void
* 
* Directorios de búsqueda:
* - APPROOT/app/core/     → Clases base (Router, Controller, Database)
* - APPROOT/app/models/   → Modelos de datos
* - APPROOT/app/controllers/ → Controladores de la aplicación
* 
* @example Si se instancia new Productos(), busca:
*          - /app/controllers/Productos.php
*          - /app/models/Productos.php
*          - /app/core/Productos.php
*/
spl_autoload_register(function($className) {
    // Array con rutas donde para buscar clases
    $directories = [
        APPROOT . '/app/core/',       // Clases base como Router, Controller, Database.
        APPROOT . '/app/models/',     // Modelos de datos como Usuario.php, Producto.php, Categoria.php.
        APPROOT . '/app/controllers/' // Controladores como Dashboard.php, Productos.php, Categorias.php.
    ];

    foreach ($directories as $directory) {
        if (file_exists($directory . $className . '.php')) {
            require_once $directory . $className . '.php';
            return; // Detiene la búsqueda al encontrar el archivo.
        }
    }

    // Opcional: Puedes agregar un manejo de error aquí si la clase no se encuentra.
    // error_log("Clase no encontrada: " . $className);
});

/**  5. Incializacion del Router y definición de rutas. 
* Crea una instancia del Router y define las rutas de la aplicación (ubicada en app/core/Router.php).
* que se encargar de gestionar todas las rutas de la aplicación. 
* @var Ruouter $router objeto principal de enrutamiento
*/
$router = new Router();

/** Definición de rutas:
* Define todas las Url disponibles den la aplicacion y las asocia 
* con un controlador y método específico.
* Formato: $router->add('ruta', 'Controlador', 'metodo');
* @see Router:add ()
*/

/** Rutas api: Endpoints para servicios REST*/ 

$router->add('api/dashboard', 'Api', 'dashboard'); //  GET / api/dashboard.
$router->add('api/productos', 'Api', 'productos'); //  GET / api/productos.
$router->add('api/lotes', 'Api', 'lotes');         //  GET / api/lotes.
$router->add('api/consumir', 'Api', 'consumir');   //  POST / api/consumir. 

// $router->add('api', 'Api', 'index') (Rutas del modulo del dashboard);

$router->add('dashboard', 'Dashboard', 'index'); //  panel principal.

// RUTAS DEL MÓDULO CATEGORÍAS (CRUD Completo)
$router->add('categorias', 'Categorias', 'index');                      // Listar activos.
$router->add('categorias/inactivos', 'Categorias', 'inactivos');        // Listar inactivos.
$router->add('categorias/crear', 'Categorias', 'crear');                // crear (GET/POST).
$router->add('categorias/editar', 'Categorias', 'editar');              // editar (GET/POST).  
$router->add('categorias/deshabilitar', 'Categorias', 'deshabilitar');  // Soft delete. 
$router->add('categorias/activar', 'Categorias', 'activar');            // Restaurar categoría inactiva.
$router->add('productos', 'Productos', 'index');
$router->add('productos/crear', 'Productos', 'crear');
$router->add('productos/editar', 'Productos', 'editar');
$router->add('productos/deshabilitar', 'Productos', 'deshabilitar');
$router->add('productos/inactivos', 'Productos', 'inactivos');
$router->add('productos/activar', 'Productos', 'activar');
$router->add('lotes', 'Lotes', 'index');
$router->add('lotes/crear', 'Lotes', 'crear');
$router->add('lotes/editar', 'Lotes', 'editar');
$router->add('lotes/deshabilitar', 'Lotes', 'deshabilitar');
$router->add('lotes/inactivos', 'Lotes', 'inactivos');
$router->add('lotes/activar', 'Lotes', 'activar');
$router->add('menus', 'Menus', 'index');
$router->add('menus/crear', 'Menus', 'crear');
$router->add('menus/editar', 'Menus', 'editar');
$router->add('menus/consumir', 'Menus', 'consumir');

$router->add('usuarios', 'Usuarios', 'index');
$router->add('usuarios/inactivos', 'Usuarios', 'inactivos');
$router->add('usuarios/crear', 'Usuarios', 'crear');
$router->add('usuarios/editar', 'Usuarios', 'editar');
$router->add('usuarios/deshabilitar', 'Usuarios', 'deshabilitar');
$router->add('usuarios/activar', 'Usuarios', 'activar');

$router->add('proveedores', 'Proveedores', 'index');
$router->add('proveedores/crear', 'Proveedores', 'crear');
$router->add('proveedores/editar', 'Proveedores', 'editar');
$router->add('proveedores/eliminar', 'Proveedores', 'eliminar');
$router->add('proveedores/inactivos', 'Proveedores', 'inactivos');
$router->add('proveedores/activar', 'Proveedores', 'activar');

$router->add('reportes', 'Reportes', 'index');
$router->add('reportes/inventario', 'Reportes', 'inventario');
$router->add('reportes/consumo', 'Reportes', 'consumo');
$router->add('home', 'Home', 'index');
$router->add('login', 'Login', 'index');
$router->add('login/recuperar', 'Login', 'recuperar');
$router->add('login/resetear', 'Login', 'resetear');
$router->add('logout', 'Login', 'logout');
$router->add('', 'Login', 'index'); // Default to login

/** 7. Dispatch (Ejecución del enrutamiento)
* Analiza la URL solicitada, encuentra la ruta correspondiente
* e instancia el controlador y método adecuados.
* 
* @param string $_SERVER['REQUEST_URI'] URL completa de la petición
* @return mixed Respuesta del controlador (vista, JSON, redirect)
* 
* @throws Exception Si la ruta no existe (manejado por Router)
* @see Router::dispatch()
* 
* Ejemplo de procesamiento:
* 1. URL: /productos/editar?id=5
* 2. Router busca 'productos/editar'
* 3. Instancia Controlador Productos
* 4. Ejecuta método editar()
*/

$router->dispatch($_SERVER['REQUEST_URI']);
