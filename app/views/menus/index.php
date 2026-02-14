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
                <h1>Planificación de Menús</h1>
                <a href="<?php echo URLROOT; ?>/menus/crear" class="btn btn-primary">+ Nuevo Menú</a>
            </div>

            <?php if (isset($_SESSION['menu_consumo_result'])): 
                $result = $_SESSION['menu_consumo_result'];
                unset($_SESSION['menu_consumo_result']);
            ?>
            <div class="alert-section" style="background: <?php echo $result['success'] ? '#d4edda' : '#f8d7da'; ?>; border-left: 4px solid <?php echo $result['success'] ? '#28a745' : '#dc3545'; ?>;">
                <h3><?php echo $result['success'] ? '✅ Éxito' : '⚠️ Error'; ?></h3>
                <p><?php echo htmlspecialchars($result['message']); ?></p>
                <?php if (isset($result['errores'])): ?>
                    <ul>
                        <?php foreach ($result['errores'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Día</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Ingredientes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['menus'] as $menu): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($menu['nombre']); ?></strong></td>
                            <td><?php echo $menu['dia_semana']; ?></td>
                            <td><span class="badge"><?php echo ucfirst($menu['tipo']); ?></span></td>
                            <td><?php echo $menu['fecha']; ?></td>
                            <td style="font-size: 0.9em;"><?php echo htmlspecialchars($menu['ingredientes'] ?? 'Sin ingredientes'); ?></td>
                            <td style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <a href="<?php echo URLROOT; ?>/menus/editar/<?php echo $menu['id']; ?>" class="btn btn-warning btn-sm" title="Editar Menú" style="text-align: center;">✏️ Editar</a>
                                <form method="POST" action="<?php echo URLROOT; ?>/menus/consumir/<?php echo $menu['id']; ?>">
                                    <button type="submit" class="btn btn-sm" style="background: var(--success-color); width: 100%;" 
                                            onclick="return confirm('¿Confirmar consumo de este menú? Se descontará del inventario usando FIFO.')">
                                        🍽️ Consumir
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
