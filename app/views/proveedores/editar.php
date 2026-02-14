<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="icon" href="<?php echo URLROOT; ?>/public/favicon.ico" type="image/x-icon">
</head>
<body>
    <?php include APPROOT . '/app/views/partials/navbar.php'; ?>
    
    <div class="dashboard">
        <?php include APPROOT . '/app/views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <h1>✏️ Editar Proveedor</h1>
                <a href="<?php echo URLROOT; ?>/proveedores" class="btn btn-secondary">← Volver</a>
            </div>

            <div class="card" style="max-width: 600px; margin: 0 auto;">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
                        <?php echo $data['error']; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/proveedores/editar/<?php echo $data['id']; ?>" method="POST">
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="nombre">Nombre de la Empresa: *</label>
                        <input type="text" name="nombre" value="<?php echo $data['nombre']; ?>" required class="form-control" style="width: 100%; padding: 0.5rem;">
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="contacto">Persona de Contacto:</label>
                        <input type="text" name="contacto" value="<?php echo $data['contacto']; ?>" class="form-control" style="width: 100%; padding: 0.5rem;" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" name="telefono" value="<?php echo $data['telefono']; ?>" class="form-control" style="width: 100%; padding: 0.5rem;">
                        </div>
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="email">Email:</label>
                            <input type="email" name="email" value="<?php echo $data['email']; ?>" class="form-control" style="width: 100%; padding: 0.5rem;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="direccion">Dirección:</label>
                        <textarea name="direccion" class="form-control" style="width: 100%; padding: 0.5rem; height: 100px;"><?php echo $data['direccion']; ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Actualizar Proveedor</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
