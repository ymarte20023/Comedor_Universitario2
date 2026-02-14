<?php
/* ============================================
   app/core/ReportGenerator.php
   Generador de reportes HTML listos para impresión
   Métodos estáticos - No instanciar
   Estrategia: HTML + CSS + window.print()
   ============================================ */

class ReportGenerator {
    
    // ------------------------------------------
    // GET /reportes/inventario - Reporte completo
    // Stock actual, alertas críticas, próximos a vencer
    // ------------------------------------------
    public static function generarReporteInventario() {
        // Cargar modelos manualmente
        require_once APPROOT . '/app/models/ProductoModel.php';
        require_once APPROOT . '/app/models/LoteModel.php';
        
        $productoModel = new ProductoModel();
        $loteModel = new LoteModel();
        
        // Datos del reporte
        $productos = $productoModel->getAllWithDetails();
        $stockCritico = $productoModel->checkStockCritico();
        $lotesVencen = $loteModel->getLotesProximosVencer();
        
        // Renderizar HTML + auto-print
        header('Content-Type: text/html; charset=UTF-8');
        self::renderInventarioHTML($productos, $stockCritico, $lotesVencen);
    }
    
    // ------------------------------------------
    // GET /reportes/consumo?fecha_inicio=&fecha_fin=
    // Reporte de movimientos por rango de fechas
    // ------------------------------------------
    public static function generarReporteConsumo($fechaInicio, $fechaFin) {
        require_once APPROOT . '/app/models/MovimientoModel.php';
        
        $movimientoModel = new MovimientoModel();
        $movimientos = $movimientoModel->getByDateRange($fechaInicio, $fechaFin);
        
        header('Content-Type: text/html; charset=UTF-8');
        self::renderConsumoHTML($movimientos, $fechaInicio, $fechaFin);
    }

    // ------------------------------------------
    // Renderizado interno - Inventario
    // ------------------------------------------
    private static function renderInventarioHTML($productos, $stockCritico, $lotesVencen) {
        ?>
        <!-- 
            🖨️ REPORTE DE INVENTARIO 
            Secciones:
            1. Cabecera con logo y fecha
            2. Resumen general (tarjetas)
            3. Inventario completo
            4. Alertas de stock crítico
            5. Auto-print al cargar
        -->
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Inventario</title>
            <style>
                /* 🎨 Estilos optimizados para impresión */
                @media print { @page { margin: 1cm; } body { margin: 0; } }
                body { font-family: Arial, sans-serif; font-size: 12px; }
                h1 { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
                h2 { color: #34495e; margin-top: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th { background: #3498db; color: white; padding: 8px; text-align: left; }
                td { padding: 6px; border-bottom: 1px solid #ddd; }
                .critico { color: #e74c3c; font-weight: bold; }
                .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; }
            </style>
        </head>
        <body>
            <!-- Cabecera del reporte -->
            <div class="header">
                <img src="<?= URLROOT; ?>/public/assets/images/logo.svg" class="logo">
                <div class="header-info">
                    <h1 style="margin: 0; border: none;">📊 REPORTE DE INVENTARIO</h1>
                    <p><strong>Comedor Universitario</strong> | <?= date('d/m/Y H:i'); ?></p>
                </div>
            </div>

            <!-- 📊 Resumen general - KPIs -->
            <h2>Resumen General</h2>
            <table>
                <tr><th>Métrica</th><th>Valor</th></tr>
                <tr><td>Total Productos</td><td><?= count($productos); ?></td></tr>
                <tr><td>Stock Crítico</td><td class="critico"><?= count($stockCritico); ?></td></tr>
                <tr><td>Lotes a Vencer (7d)</td><td class="critico"><?= count($lotesVencen); ?></td></tr>
            </table>

            <!-- 📋 Listado completo -->
            <h2>Inventario Completo</h2>
            <table>
                <thead><tr><th>Producto</th><th>Categoría</th><th>Stock</th><th>Mínimo</th><th>Proveedor</th></tr></thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nombre']); ?></td>
                        <td><?= htmlspecialchars($p['categoria']); ?></td>
                        <td class="<?= $p['stock_actual'] < $p['stock_minimo'] ? 'critico' : ''; ?>">
                            <?= $p['stock_actual']; ?> <?= $p['unidad_medida']; ?>
                        </td>
                        <td><?= $p['stock_minimo']; ?></td>
                        <td><?= htmlspecialchars($p['proveedor']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- ⚠️ Alertas de stock crítico -->
            <?php if (!empty($stockCritico)): ?>
            <h2 style="color: #e74c3c;">⚠️ Stock Crítico</h2>
            <table>
                <thead><tr><th>Producto</th><th>Actual</th><th>Mínimo</th><th>Faltante</th></tr></thead>
                <tbody>
                    <?php foreach ($stockCritico as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nombre']); ?></td>
                        <td class="critico"><?= $p['stock_actual']; ?></td>
                        <td><?= $p['stock_minimo']; ?></td>
                        <td class="critico"><?= $p['stock_minimo'] - $p['stock_actual']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <div class="footer">
                <p>Generado por Sistema de Control de Inventario - Confidencial</p>
            </div>

            <script>window.onload = function() { window.print(); };</script>
        </body>
        </html>
        <?php
        exit;
    }
    
    // ------------------------------------------
    // Renderizado interno - Consumo
    // ------------------------------------------
    private static function renderConsumoHTML($movimientos, $fechaInicio, $fechaFin) {
        ?>
        <!-- 
            🖨️ REPORTE DE CONSUMO 
            Período: fecha inicio - fecha fin
            Listado de movimientos (entradas/salidas)
        -->
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Consumo</title>
            <style>
                @media print { @page { margin: 1cm; } body { margin: 0; } }
                body { font-family: Arial, sans-serif; font-size: 12px; }
                h1 { color: #2c3e50; border-bottom: 3px solid #e74c3c; }
                th { background: #e74c3c; color: white; padding: 8px; }
                .salida { color: #e74c3c; font-weight: bold; }
                .entrada { color: #27ae60; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="header">
                <img src="<?= URLROOT; ?>/public/assets/images/logo.svg" class="logo">
                <div>
                    <h1>📉 REPORTE DE CONSUMO</h1>
                    <p><strong>Período:</strong> <?= date('d/m/Y', strtotime($fechaInicio)); ?> - <?= date('d/m/Y', strtotime($fechaFin)); ?></p>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Usuario</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movimientos as $m): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($m['fecha'])); ?></td>
                        <td><?= htmlspecialchars($m['producto_nombre']); ?></td>
                        <td class="<?= $m['tipo']; ?>"><?= strtoupper($m['tipo']); ?></td>
                        <td><?= $m['cantidad']; ?></td>
                        <td><?= htmlspecialchars($m['usuario_nombre']); ?></td>
                        <td><?= htmlspecialchars($m['observaciones']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <script>window.onload = function() { window.print(); };</script>
        </body>
        </html>
        <?php
        exit;
    }
}