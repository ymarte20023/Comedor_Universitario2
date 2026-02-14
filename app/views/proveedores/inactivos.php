<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
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
                <div style="display:flex; align-items:center; gap: 1rem;">
                    <h1>Proveedores Deshabilitados</h1>
                </div>
                <a href="<?php echo URLROOT; ?>/proveedores" class="btn btn-secondary">← Volver al Listado</a>
            </div>

            <?php if (empty($data['proveedores'])): ?>
                <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
                    <h3>No hay proveedores deshabilitados</h3>
                    <p>Los proveedores que elimines aparecerán aquí.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <div class="search-bar-wrapper">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchProveedoresInactivos" class="search-input" placeholder="Buscar por nombre...">
                    </div>
                    <table id="proveedores-inactivos-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Contacto</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['proveedores'] as $proveedor): ?>
                            <tr>
                                <td style="opacity: 0.7;"><strong><?php echo htmlspecialchars($proveedor['nombre']); ?></strong></td>
                                <td><?php echo htmlspecialchars($proveedor['contacto']); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['telefono']); ?></td>
                                <td class="actions-cell">
                                    <form action="<?php echo URLROOT; ?>/proveedores/activar/<?php echo $proveedor['id']; ?>" method="POST" onsubmit="return confirm('¿Deseas reactivar este proveedor?');">
                                        <button type="submit" class="btn btn-sm btn-success" title="Reactivar">♻️ Reactivar</button>
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
    </div>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initTableSearch('searchProveedoresInactivos', 'proveedores-inactivos-table');
        });
    </script>
</body>
</html>
