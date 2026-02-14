<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo URLROOT; ?>/public/favicon.ico">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/dashboard.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include APPROOT . '/app/views/partials/navbar.php'; ?>
    
    <div class="dashboard">
        <?php include APPROOT . '/app/views/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1>Usuarios Deshabilitados</h1>
                    <p>Estos usuarios no tienen acceso al sistema.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/usuarios" class="btn btn-secondary">Volver a Activos</a>
            </div>

            <?php if (empty($data['usuarios'])): ?>
                <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
                    <h3>No hay usuarios deshabilitados</h3>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <div class="search-bar-wrapper">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchUsuariosInactivos" class="search-input" placeholder="Buscar por nombre o email...">
                    </div>
                    <table id="usuarios-inactivos-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['usuarios'] as $usuario): ?>
                            <tr>
                                <td style="opacity: 0.7;"><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                <td><?php echo ucfirst($usuario['rol']); ?></td>
                                <td class="actions-cell">
                                    <form action="<?php echo URLROOT; ?>/usuarios/activar/<?php echo $usuario['id']; ?>" method="POST">
                                        <button type="submit" class="btn btn-sm btn-success">♻️ Reactivar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js?v=<?php echo time(); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initTableSearch('searchUsuariosInactivos', 'usuarios-inactivos-table');
        });
    </script>
</body>
</html>
