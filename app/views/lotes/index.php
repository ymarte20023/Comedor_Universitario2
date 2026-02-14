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
        
        <main class="main-content lotes-mvvm-container">
            <div class="page-header">
                <div style="display:flex; gap:1rem; align-items:center;">
                    <h1>Gestión de Lotes</h1>
                    <button id="refresh-lotes-btn" class="btn btn-sm" style="background:var(--secondary-color)">🔄</button>
                </div>
                <div style="display:flex; gap: 10px;">
                    <a href="<?php echo URLROOT; ?>/lotes/inactivos" class="btn btn-danger btn-sm" style="display:flex; align-items:center; gap: 0.5rem;">🚫 Ver Deshabilitados</a>
                    <a href="<?php echo URLROOT; ?>/lotes/crear" class="btn btn-primary">+ Registrar Lote</a>
                </div>
            </div>

            <!-- Alertas de Lotes Próximos a Vencer -->
            <?php if (!empty($data['lotesProximosVencer'])): ?>
            <div class="alert-section">
                <h2>⚠️ Lotes Próximos a Vencer (7 días)</h2>
                <div class="table-container">
                    <table>
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
                                <td><?php echo $lote['cantidad']; ?> <?php echo $lote['unidad_medida']; ?></td>
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
            <?php endif; ?>

            <!-- Todos los Lotes -->
            <div class="table-container">
                <div class="search-bar-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchLotes" class="search-input" placeholder="Buscar por nombre de producto...">
                </div>
                <table id="lotes-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Número Lote</th>
                            <th>Cantidad</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Caducidad</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['lotes'] as $lote): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($lote['producto']); ?></td>
                            <td><?php echo htmlspecialchars($lote['numero_lote']); ?></td>
                            <td><?php echo $lote['cantidad']; ?> <?php echo $lote['unidad_medida']; ?></td>
                            <td><?php echo $lote['fecha_ingreso']; ?></td>
                            <td><?php echo $lote['fecha_caducidad']; ?></td>
                            <td><?php echo htmlspecialchars($lote['ubicacion']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $lote['estado']; ?>">
                                    <?php echo ucfirst($lote['estado']); ?>
                                </span>
                            </td>
                            <td class="actions-cell">
                                <a href="<?php echo URLROOT; ?>/lotes/editar/<?php echo $lote['id']; ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                                <form action="<?php echo URLROOT; ?>/lotes/deshabilitar/<?php echo $lote['id']; ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas deshabilitar este lote?');">
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
    <script src="<?php echo URLROOT; ?>/public/assets/js/viewmodels/LoteViewModel.js"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/lotes-binder.js"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initTableSearch('searchLotes', 'lotes-table');
        });
    </script>
</body>
</html>
