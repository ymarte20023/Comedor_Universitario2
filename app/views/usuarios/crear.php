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
            <div class="page-header">
                <h1>Registrar Nuevo Usuario</h1>
                <a href="<?php echo URLROOT; ?>/usuarios" class="btn btn-secondary">Cancelar</a>
            </div>

            <div class="card" style="max-width: 600px; margin: 0 auto;">
                <?php if (!empty($data['error'])): ?>
                    <div class="alert alert-danger" style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #fecaca;">
                        <?php echo $data['error']; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/usuarios/crear" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" required 
                               pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" 
                               title="El nombre solo debe contener letras y espacios"
                               value="<?php echo htmlspecialchars($data['nombre']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required 
                               placeholder="usuario@comedor.edu"
                               value="<?php echo htmlspecialchars($data['email']); ?>">
                        <small class="text-muted">Debe terminar en @comedor.edu</small>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required 
                               minlength="6"
                               pattern="(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}"
                               title="Al menos 6 caracteres, una mayúscula, un número y un carácter especial"
                               placeholder="••••••••">
                        <small class="text-muted">Mínimo 6 caracteres, 1 mayúscula, 1 número y 1 símbolo.</small>
                    </div>

                    <div class="form-group">
                        <label for="rol">Rol de Usuario</label>
                        <select id="rol" name="rol" required>
                            <option value="inventario" <?php echo $data['rol'] == 'inventario' ? 'selected' : ''; ?>>Inventario</option>
                            <option value="cocina" <?php echo $data['rol'] == 'cocina' ? 'selected' : ''; ?>>Cocina</option>
                            <!-- Admin role creation not allowed -->
                        </select>
                        <small class="text-muted">Nota: No se pueden crear usuarios administradores.</small>
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Registrar Usuario</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
