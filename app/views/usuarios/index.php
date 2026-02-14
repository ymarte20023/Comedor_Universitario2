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
                <div style="display:flex; align-items:center; gap: 1rem;">
                    <h1>Gestión de Usuarios</h1>
                </div>
                <div class="header-actions" style="display:flex; gap: 10px;">
                    <a href="<?php echo URLROOT; ?>/usuarios/inactivos" class="btn btn-danger btn-sm" style="display:flex; align-items:center; gap: 0.5rem;">🚫 Ver Deshabilitados</a>
                    <a href="<?php echo URLROOT; ?>/usuarios/crear" class="btn btn-primary">+ Nuevo Usuario</a>
                </div>
            </div>

            <div class="table-container">
                <div class="search-bar-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchUsuarios" class="search-input" placeholder="Buscar por nombre o email...">
                </div>
                <table id="usuarios-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['usuarios'] as $usuario): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <?php 
                                    $rolClass = '';
                                    switch($usuario['rol']) {
                                        case 'administrador': $rolClass = 'badge-primary'; break; // We don't have badge-primary yet, maybe info?
                                        case 'cocina': $rolClass = 'badge-warning'; break; 
                                        case 'inventario': $rolClass = 'badge-success'; break; 
                                        default: $rolClass = 'badge-secondary';
                                    }
                                    // Let's use existing badge classes or fallback
                                    if ($usuario['rol'] == 'administrador') echo '<span class="badge" style="background:var(--primary-color); color:white;">Administrador</span>';
                                    else if ($usuario['rol'] == 'cocina') echo '<span class="badge badge-almuerzo">Cocina</span>';
                                    else if ($usuario['rol'] == 'inventario') echo '<span class="badge badge-disponible">Inventario</span>';
                                    else echo '<span class="badge badge-secondary">' . ucfirst($usuario['rol']) . '</span>';
                                ?>
                            </td>
                            <td>
                                <span class="badge badge-disponible">Activo</span>
                            </td>
                            <td class="actions-cell">
                                <a href="<?php echo URLROOT; ?>/usuarios/editar/<?php echo $usuario['id']; ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                                
                                <?php if ($usuario['rol'] !== 'administrador' && $usuario['id'] != $_SESSION['usuario_id']): ?>
                                <form action="<?php echo URLROOT; ?>/usuarios/deshabilitar/<?php echo $usuario['id']; ?>" method="POST" onsubmit="return confirm('¿Deshabilitar usuario? Perderá acceso al sistema.');">
                                    <button type="submit" class="btn btn-sm btn-danger">🚫 Deshabilitar</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js?v=<?php echo time(); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initTableSearch('searchUsuarios', 'usuarios-table');
        });
    </script>
</body>
</html>
