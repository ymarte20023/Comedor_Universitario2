<script>window.URLROOT = '<?php echo URLROOT; ?>';</script>
<nav class="navbar">
    <div class="nav-brand">
        <img src="<?php echo URLROOT; ?>/public/assets/images/logo.svg" alt="Logo Comedor Universitario">
        <h2><?php echo SITENAME; ?></h2>
    </div>
    <div class="nav-user">
        <span>Bienvenido, <?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?></span>
        <span class="badge"><?php echo $_SESSION['usuario_rol'] ?? ''; ?></span>
        <a href="<?php echo URLROOT; ?>/logout" class="btn btn-sm">Cerrar Sesión</a>
    </div>
</nav>
