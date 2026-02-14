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
                <div style="display:flex; align-items:center; gap: 1rem;">
                    <h1>Productos Deshabilitados</h1>
                </div>
                <a href="<?php echo URLROOT; ?>/productos" class="btn btn-secondary">← Volver al Inventario</a>
            </div>

            <?php if (empty($data['productos'])): ?>
                <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
                    <h3>No hay productos deshabilitados</h3>
                    <p>Los productos que elimines aparecerán aquí.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <div class="search-bar-wrapper">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchProductosInactivos" class="search-input" placeholder="Buscar por nombre...">
                    </div>
                    <table id="productos-inactivos-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Proveedor</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['productos'] as $producto): ?>
                            <tr>
                                <td style="opacity: 0.7;"><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                                <td><?php echo htmlspecialchars($producto['proveedor']); ?></td>
                                <td>Bs. <?php echo number_format($producto['precio_unitario'], 2); ?></td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/productos/activar/<?php echo $producto['id']; ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Deseas reactivar este producto?');">
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
            initTableSearch('searchProductosInactivos', 'productos-inactivos-table');
        });
    </script>
</body>
</html>
