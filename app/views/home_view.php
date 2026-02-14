<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <h1><?php echo SITENAME; ?></h1>
    </header>

    <main class="container">
        <h2><?php echo $data['title']; ?></h2>
        <p><?php echo $data['description']; ?></p>
        <div id="app">
            <!-- MVVM Content will load here -->
             <p>Esperando a que el Frontend MVVM sea inicializado...</p>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Comedor Universitario - Prototipo</p>
    </footer>

    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
</body>
</html>
