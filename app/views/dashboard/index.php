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
        
        <main class="main-content dashboard-mvvm-container">
            <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1>Dashboard - Control de Inventario</h1>
                <button id="refresh-stats-btn" class="btn" style="background: var(--secondary-color);">🔄 Actualizar Datos</button>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="card">
                    <h3>Total Productos</h3>
                    <p class="stat-number" id="stat-total-productos"><?php echo $data['totalProductos']; ?></p>
                </div>
                <div class="card critico">
                    <h3>Stock Crítico</h3>
                    <p class="stat-number" id="stat-stock-critico"><?php echo $data['productosStockCritico']; ?></p>
                </div>
                <div class="card warning">
                    <h3>Lotes por Vencer</h3>
                    <p class="stat-number" id="stat-lotes-vencen"><?php echo $data['lotesVencenProximo']; ?></p>
                </div>
                <?php if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'administrador'): ?>
                <div class="card" style="border-left: 4px solid var(--secondary-color);">
                    <h3>Categorías</h3>
                    <p class="stat-number" id="stat-total-categorias"><?php echo $data['totalCategorias']; ?></p>
                </div>
                <div class="card" style="border-left: 4px solid var(--success-color);">
                    <h3>Proveedores</h3>
                    <p class="stat-number" id="stat-total-proveedores"><?php echo $data['totalProveedores']; ?></p>
                </div>
                <div class="card" style="border-left: 4px solid var(--primary-color);">
                    <h3>Usuarios</h3>
                    <p class="stat-number" id="stat-total-usuarios"><?php echo $data['totalUsuarios'] ?? 0; ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Alertas de Stock Crítico -->
            <div class="alert-section" id="stock-alert-section" style="<?php echo empty($data['stockCritico']) ? 'display:none;' : ''; ?>">
                <h2>⚠️ Productos con Stock Crítico</h2>
                <div class="table-container">
                    <table id="stock-critico-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['stockCritico'] as $producto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                                <td class="stock-critico"><?php echo $producto['stock_actual']; ?></td>
                                <td><?php echo $producto['stock_minimo']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Alertas de Lotes Próximos a Vencer -->
            <div class="alert-section" id="lotes-alert-section" style="<?php echo empty($data['lotesProximosVencer']) ? 'display:none;' : ''; ?>">
                <h2>📅 Lotes Próximos a Vencer (7 días)</h2>
                <div class="table-container">
                    <table id="lotes-vencen-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Lote</th>
                                <th>Cantidad</th>
                                <th>Fecha Caducidad</th>
                                <th>Días Restantes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['lotesProximosVencer'] as $lote): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($lote['producto']); ?></td>
                                <td><?php echo htmlspecialchars($lote['numero_lote']); ?></td>
                                <td><?php echo $lote['cantidad']; ?></td>
                                <td><?php echo $lote['fecha_caducidad']; ?></td>
                                <td class="<?php echo $lote['dias_restantes'] <= 3 ? 'stock-critico' : ''; ?>">
                                    <?php echo $lote['dias_restantes']; ?> días
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- MVVM Scripts -->
    <script src="<?php echo URLROOT; ?>/public/assets/js/viewmodels/ViewModel.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/viewmodels/DashboardViewModel.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/dashboard-binder.js?v=<?php echo time(); ?>"></script>
</body>
</html>
