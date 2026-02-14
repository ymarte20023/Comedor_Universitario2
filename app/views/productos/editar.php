<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include APPROOT . '/app/views/partials/navbar.php'; ?>
    
    <div class="dashboard">
        <?php include APPROOT . '/app/views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="header-flex">
                <h1>✏️ Editar Producto</h1>
                <a href="<?php echo URLROOT; ?>/productos" class="btn btn-secondary">Cancelar</a>
            </div>

            <div class="card">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
                        <?php echo $data['error']; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/productos/editar/<?php echo $data['producto']['id']; ?>" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre">Nombre del Producto</label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($data['producto']['nombre']); ?>" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                        </div>

                        <div class="form-group">
                            <label for="categoria_id">Categoría</label>
                            <select id="categoria_id" name="categoria_id" required>
                                <?php foreach ($data['categorias'] as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $data['producto']['categoria_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="unidad_medida">Unidad de Medida</label>
                            <select id="unidad_medida" name="unidad_medida" required>
                                <option value="kg" <?php echo $data['producto']['unidad_medida'] == 'kg' ? 'selected' : ''; ?>>Kilogramos (kg)</option>
                                <option value="litros" <?php echo $data['producto']['unidad_medida'] == 'litros' ? 'selected' : ''; ?>>Litros (L)</option>
                                <option value="unidades" <?php echo $data['producto']['unidad_medida'] == 'unidades' ? 'selected' : ''; ?>>Unidades (u)</option>
                                <option value="paquetes" <?php echo $data['producto']['unidad_medida'] == 'paquetes' ? 'selected' : ''; ?>>Paquetes / Latas</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="precio_unitario">Precio Unitario (Bs)</label>
                            <input type="number" step="0.01" id="precio_unitario" name="precio_unitario" value="<?php echo $data['producto']['precio_unitario']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="stock_minimo">Stock Mínimo (Alerta)</label>
                            <input type="number" step="0.01" id="stock_minimo" name="stock_minimo" value="<?php echo $data['producto']['stock_minimo']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="stock_maximo">Stock Máximo</label>
                            <input type="number" step="0.01" id="stock_maximo" name="stock_maximo" value="<?php echo $data['producto']['stock_maximo']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="proveedor_id">Proveedor Principal</label>
                            <select id="proveedor_id" name="proveedor_id" required>
                                <?php foreach ($data['proveedores'] as $prov): ?>
                                    <option value="<?php echo $prov['id']; ?>" <?php echo $prov['id'] == $data['producto']['proveedor_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($prov['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
