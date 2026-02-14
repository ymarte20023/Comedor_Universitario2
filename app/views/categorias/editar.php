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
                <h1>Editar Categoría</h1>
                <a href="<?php echo URLROOT; ?>/categorias" class="btn btn-secondary">Cancelar</a>
            </div>

            <div class="card">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
                        <?php echo $data['error']; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/categorias/editar/<?php echo $data['categoria']['id']; ?>" method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre">Nombre de la Categoría</label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($data['categoria']['nombre']); ?>" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo de Conservación</label>
                            <div style="display: flex; gap: 20px; align-items: center; margin-top: 10px;">
                                <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                    <input type="checkbox" name="perecedero" id="perecedero" <?php echo $data['categoria']['perecedero'] ? 'checked' : ''; ?> onclick="toggleDias(this)">
                                    Es Perecedero
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="dias-container" style="display: <?php echo $data['categoria']['perecedero'] ? 'block' : 'none'; ?>;">
                            <label for="dias_caducidad">Días de Caducidad Estimados</label>
                            <input type="number" id="dias_caducidad" name="dias_caducidad" value="<?php echo $data['categoria']['dias_caducidad']; ?>" min="1">
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($data['categoria']['descripcion']); ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function toggleDias(checkbox) {
            const container = document.getElementById('dias-container');
            if (checkbox.checked) {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
                document.getElementById('dias_caducidad').value = '';
            }
        }
    </script>
</body>
</html>
