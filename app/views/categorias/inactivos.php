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
                <div>
                    <h1>Categorías Deshabilitadas</h1>
                    <p>Estas categorías no aparecerán al registrar nuevos productos.</p>
                </div>
                <a href="<?php echo URLROOT; ?>/categorias" class="btn btn-secondary">Volver a Activas</a>
            </div>

            <div class="table-container">
                <?php if (empty($data['categorias'])): ?>
                    <p style="padding: 2rem; text-align: center; color: #666;">No hay categorías deshabilitadas.</p>
                <?php else: ?>
                    <div class="table-container">
                        <div class="search-bar-wrapper">
                            <span class="search-icon">🔍</span>
                            <input type="text" id="searchCategoriasInactivas" class="search-input" placeholder="Buscar por nombre...">
                        </div>
                        <table id="categorias-inactivos-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['categorias'] as $cat): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($cat['nombre']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($cat['descripcion']); ?></td>
                                <td><?php echo $cat['perecedero'] ? 'Perecedero' : 'No Perecedero'; ?></td>
                                <td class="actions-cell">
                                    <form action="<?php echo URLROOT; ?>/categorias/activar/<?php echo $cat['id']; ?>" method="POST">
                                        <button type="submit" class="btn btn-sm btn-success">♻️ Reactivar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>
    </div>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            initTableSearch('searchCategoriasInactivas', 'categorias-inactivos-table');
        });
    </script>
</body>
</html>
