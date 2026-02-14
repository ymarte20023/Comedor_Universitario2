<?php
/* ============================================
   app/core/Controller.php
   Clase base abstracta para todos los controladores
   Proporciona métodos para cargar modelos y vistas
   ============================================ */

abstract class Controller {
    
    // ------------------------------------------
    // Cargar e instanciar un modelo
    // @param string $model - Nombre de la clase del modelo
    // @return object - Instancia del modelo
    // ------------------------------------------
    public function model($model) {
        $modelFile = APPROOT . '/app/models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model(); // Instanciar modelo
        }
        
        die("Modelo no encontrado: $model");
    }

    // ------------------------------------------
    // Cargar una vista y pasarle datos
    // @param string $view - Ruta relativa desde /app/views/
    // @param array $data - Datos para extraer como variables en la vista
    // ------------------------------------------
    public function view($view, $data = []) {
        $viewFile = APPROOT . '/app/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile; // $data disponible en la vista
        } else {
            die("Vista no encontrada: $view");
        }
    }
}