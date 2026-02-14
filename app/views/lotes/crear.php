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
                <h1>📦 Registrar Nuevo Lote</h1>
                <a href="<?php echo URLROOT; ?>/lotes" class="btn btn-secondary">Cancelar</a>
            </div>

            <div class="card">
                <form action="<?php echo URLROOT; ?>/lotes/crear" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="producto_id">Producto</label>
                            <select id="producto_id" name="producto_id" required>
                                <option value="">Seleccione un producto...</option>
                                <?php foreach ($data['productos'] as $prod): ?>
                                    <option value="<?php echo $prod['id']; ?>">
                                        <?php echo htmlspecialchars($prod['nombre']); ?> (<?php echo $prod['stock_actual'] . ' ' . $prod['unidad_medida']; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="numero_lote">Número de Lote / Código</label>
                            <input type="text" id="numero_lote" name="numero_lote" required placeholder="Ej: LOTE-2024-001">
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad Recibida</label>
                            <input type="number" step="0.01" id="cantidad" name="cantidad" required placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label for="fecha_ingreso">Fecha de Ingreso</label>
                            <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha_caducidad">Fecha de Vencimiento (Opcional)</label>
                            <input type="date" id="fecha_caducidad" name="fecha_caducidad">
                            <small>Importante para alertas de inventario</small>
                        </div>

                        <div class="form-group">
                            <label for="ubicacion">Ubicación en Almacén</label>
                            <input type="text" id="ubicacion" name="ubicacion" placeholder="Ej: Estante A-1">
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Registrar Entrada</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
