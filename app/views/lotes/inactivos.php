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
                    <h1>Lotes Deshabilitados</h1>
                </div>
                <a href="<?php echo URLROOT; ?>/lotes" class="btn btn-secondary">← Volver a Lotes</a>
            </div>

            <?php if (empty($data['lotes'])): ?>
                <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
                    <h3>No hay lotes deshabilitados</h3>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <div class="search-bar-wrapper">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="searchLotesInactivos" class="search-input" placeholder="Buscar por nombre de producto...">
                    </div>
                    <table id="lotes-inactivos-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Lote</th>
                                <th>Cantidad</th>
                                <th>Vencimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['lotes'] as $lote): ?>
                            <tr>
                                <td style="opacity: 0.7;"><?php echo htmlspecialchars($lote['producto']); ?></td>
                                <td><?php echo htmlspecialchars($lote['numero_lote']); ?></td>
                                <td><?php echo $lote['cantidad']; ?> <?php echo $lote['unidad_medida']; ?></td>
                                <td><?php echo $lote['fecha_caducidad']; ?></td>
                                <td class="actions-cell">
                                    <form action="<?php echo URLROOT; ?>/lotes/activar/<?php echo $lote['id']; ?>" method="POST" onsubmit="return confirm('¿Deseas reactivar este lote?');">
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
            initTableSearch('searchLotesInactivos', 'lotes-inactivos-table');
        });
    </script>
</body>
</html>
