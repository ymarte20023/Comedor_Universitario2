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
                <h1>✏️ Editar Lote</h1>
                <a href="<?php echo URLROOT; ?>/lotes" class="btn btn-secondary">Cancelar</a>
            </div>

            <div class="card">
                <form action="<?php echo URLROOT; ?>/lotes/editar/<?php echo $data['lote']['id']; ?>" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="producto_id">Producto</label>
                            <select id="producto_id" name="producto_id" required>
                                <?php foreach ($data['productos'] as $prod): ?>
                                    <option value="<?php echo $prod['id']; ?>" <?php echo $data['lote']['producto_id'] == $prod['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($prod['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="numero_lote">Número de Lote / Código</label>
                            <input type="text" id="numero_lote" name="numero_lote" value="<?php echo htmlspecialchars($data['lote']['numero_lote']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" step="0.01" id="cantidad" name="cantidad" value="<?php echo $data['lote']['cantidad']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha_ingreso">Fecha de Ingreso</label>
                            <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo $data['lote']['fecha_ingreso']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="fecha_caducidad">Fecha de Vencimiento</label>
                            <input type="date" id="fecha_caducidad" name="fecha_caducidad" value="<?php echo $data['lote']['fecha_caducidad']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="ubicacion">Ubicación</label>
                            <input type="text" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($data['lote']['ubicacion']); ?>">
                        </div>
                    </div>

                    <div class="form-actions" style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Actualizar Lote</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
