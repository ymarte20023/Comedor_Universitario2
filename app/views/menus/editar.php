<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Menú</title>
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
            <h1>Editar Menú</h1>
            
            <form method="POST" action="<?php echo URLROOT; ?>/menus/editar/<?php echo $data['menu']['id']; ?>" style="max-width: 1200px;">
                <div class="form-group">
                    <label for="nombre">Nombre del Menú *</label>
                    <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($data['menu']['nombre']); ?>">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="dia_semana">Día de la Semana *</label>
                        <select id="dia_semana" name="dia_semana" required>
                            <?php 
                            $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                            foreach($dias as $dia): ?>
                                <option value="<?php echo $dia; ?>" <?php echo $data['menu']['dia_semana'] == $dia ? 'selected' : ''; ?>>
                                    <?php echo $dia; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Comida *</label>
                        <select id="tipo" name="tipo" required>
                            <option value="desayuno" <?php echo $data['menu']['tipo'] == 'desayuno' ? 'selected' : ''; ?>>Desayuno</option>
                            <option value="almuerzo" <?php echo $data['menu']['tipo'] == 'almuerzo' ? 'selected' : ''; ?>>Almuerzo</option>
                            <option value="cena" <?php echo $data['menu']['tipo'] == 'cena' ? 'selected' : ''; ?>>Cena</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha *</label>
                        <input type="date" id="fecha" name="fecha" required value="<?php echo $data['menu']['fecha']; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($data['menu']['descripcion']); ?></textarea>
                </div>

                <h3>Ingredientes Necesarios</h3>
                <p style="color: #666; margin-bottom: 1rem;">Modifique los productos y cantidades necesarias:</p>
                
                <div class="productos-grid">
                    <?php foreach ($data['productos'] as $producto): 
                        $cantidad = isset($data['menuProductos'][$producto['id']]) ? $data['menuProductos'][$producto['id']] : 0;
                    ?>
                    <div class="producto-item" style="<?php echo $cantidad > 0 ? 'border-color: var(--secondary-color); background: #f0f7ff;' : ''; ?>">
                        <label>
                            <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                            <small style="color: #666; display: block;">Stock: <?php echo $producto['stock_actual']; ?> <?php echo $producto['unidad_medida']; ?></small>
                        </label>
                        <input type="number" 
                               name="productos[<?php echo $producto['id']; ?>]" 
                               min="0" 
                               step="0.01" 
                               placeholder="0"
                               value="<?php echo $cantidad; ?>">
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="<?php echo URLROOT; ?>/menus" class="btn btn-danger">Cancelar</a>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
