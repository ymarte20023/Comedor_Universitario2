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
        
        <main class="main-content inventario-mvvm-container">
            <div class="page-header">
                <div style="display:flex; align-items:center; gap: 1rem;">
                    <h1>Gestión de Productos</h1>
                    <button id="refresh-inventario-btn" class="btn btn-sm" style="background:var(--secondary-color)">🔄</button>
                </div>
                <div style="display:flex; gap: 10px;">
                    <a href="<?php echo URLROOT; ?>/productos/inactivos" class="btn btn-danger btn-sm" style="display:flex; align-items:center; gap: 0.5rem;">🚫 Ver Deshabilitados</a>
                    <a href="<?php echo URLROOT; ?>/productos/crear" class="btn btn-primary">+ Nuevo Producto</a>
                </div>
            </div>

            <div class="table-container">
                <div class="search-bar-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchProductos" class="search-input" placeholder="Buscar por nombre...">
                </div>
                <table id="productos-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Proveedor</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Initial SSR Content (SEO friendly) -->
                        <?php foreach ($data['productos'] as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                            <td><?php echo htmlspecialchars($producto['proveedor']); ?></td>
                            <td class="<?php echo $producto['stock_actual'] < $producto['stock_minimo'] ? 'stock-critico' : ''; ?>">
                                <?php echo $producto['stock_actual']; ?> <?php echo $producto['unidad_medida']; ?>
                            </td>
                            <td><?php echo $producto['stock_minimo']; ?></td>
                            <td>Bs. <?php echo number_format($producto['precio_unitario'], 2); ?></td>
                            <td class="actions-cell">
                                <a href="<?php echo URLROOT; ?>/productos/editar/<?php echo $producto['id']; ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                                <form action="<?php echo URLROOT; ?>/productos/deshabilitar/<?php echo $producto['id']; ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas deshabilitar este producto?');">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Deshabilitar">🚫 Deshabilitar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- MVVM Scripts -->
    <script src="<?php echo URLROOT; ?>/public/assets/js/viewmodels/ViewModel.js"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/viewmodels/InventarioViewModel.js"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/inventario-binder.js"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initTableSearch('searchProductos', 'productos-table');
        });
    </script>
</body>
</html>
