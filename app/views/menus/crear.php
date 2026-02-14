<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/dashboard.css?v=<?php echo time(); ?>">
    <style>
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;
        }
        .productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem; }
        .producto-item { background: #f9f9f9; padding: 1rem; border-radius: 4px; border: 1px solid #ddd; }
        .producto-item label { font-weight: normal; margin-bottom: 0.5rem; display: block; }
        .producto-item input[type="number"] { width: 100%; }
    </style>
</head>
<body>
    <?php include APPROOT . '/app/views/partials/navbar.php'; ?>
    
    <div class="dashboard">
        <?php include APPROOT . '/app/views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <h1>Crear Nuevo Menú</h1>
            
            <form method="POST" action="<?php echo URLROOT; ?>/menus/crear" style="max-width: 1200px;">
                <div class="form-group">
                    <label for="nombre">Nombre del Menú *</label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ej: Almuerzo Especial">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="dia_semana">Día de la Semana *</label>
                        <select id="dia_semana" name="dia_semana" required>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Comida *</label>
                        <select id="tipo" name="tipo" required>
                            <option value="desayuno">Desayuno</option>
                            <option value="almuerzo">Almuerzo</option>
                            <option value="cena">Cena</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha *</label>
                        <input type="date" id="fecha" name="fecha" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="3" placeholder="Descripción del menú..."></textarea>
                </div>

                <h3>Ingredientes Necesarios</h3>
                <p style="color: #666; margin-bottom: 1rem;">Seleccione los productos y cantidades necesarias para este menú:</p>
                
                <div class="productos-grid">
                    <?php foreach ($data['productos'] as $producto): ?>
                    <div class="producto-item">
                        <label>
                            <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                            <small style="color: #666; display: block;">Stock: <?php echo $producto['stock_actual']; ?> <?php echo $producto['unidad_medida']; ?></small>
                        </label>
                        <input type="number" 
                               name="productos[<?php echo $producto['id']; ?>]" 
                               min="0" 
                               step="0.01" 
                               placeholder="Cantidad (<?php echo $producto['unidad_medida']; ?>)"
                               value="0">
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Crear Menú</button>
                    <a href="<?php echo URLROOT; ?>/menus" class="btn btn-danger">Cancelar</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
