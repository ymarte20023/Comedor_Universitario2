<?php
/**
 * Modelo de Movimiento - Historial de transacciones
 */
class MovimientoModel extends Model {
    protected function getTableName() {
        return 'movimientos';
    }

    /**
     * Obtener todos los movimientos con detalles
     * @param int $limit Límite de registros a devolver
     */
    public function getAllWithDetails($limit = 100) {
        $sql = "SELECT m.*, p.nombre as producto, u.nombre as usuario
                FROM movimientos m
                JOIN productos p ON m.producto_id = p.id
                JOIN usuarios u ON m.usuario_id = u.id
                ORDER BY m.fecha_movimiento DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener movimientos por rango de fechas para reportes
     * @param string $fechaInicio Fecha de inicio (YYYY-MM-DD)
     * @param string $fechaFin Fecha de fin (YYYY-MM-DD)
     */
    public function getByDateRange($fechaInicio, $fechaFin) {
        // Add one day to end date to include the full day
        $fechaFin = date('Y-m-d', strtotime($fechaFin . ' +1 day'));
        
        $sql = "SELECT m.fecha_movimiento as fecha, 
                       m.tipo, 
                       m.cantidad, 
                       m.motivo as observaciones,
                       p.nombre as producto_nombre, 
                       u.nombre as usuario_nombre
                FROM movimientos m
                JOIN productos p ON m.producto_id = p.id
                JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.fecha_movimiento >= ? AND m.fecha_movimiento < ?
                ORDER BY m.fecha_movimiento DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll();
    }


    /**
     * Registrar entrada (ingreso de stock)
     * @param int $productoId ID del producto
     * @param int $loteId ID del lote
     * @param float $cantidad Cantidad ingresada
     * @param int $usuarioId ID del usuario
     * @param string $motivo Motivo del ingreso (ej: compra, devolución)
     */

    public function registrarEntrada($productoId, $loteId, $cantidad, $usuarioId, $motivo = 'compra') {
        $stmt = $this->db->prepare("INSERT INTO movimientos (tipo, producto_id, lote_id, cantidad, usuario_id, motivo) VALUES ('entrada', ?, ?, ?, ?, ?)");
        return $stmt->execute([$productoId, $loteId, $cantidad, $usuarioId, $motivo]);
    }

    /**
     * Registrar salida (consumo de stock)
     * @param int $productoId ID del producto
     * @param int $loteId ID del lote
     * @param float $cantidad Cantidad consumida
     * @param int $usuarioId ID del usuario
     * @param string $motivo Motivo del consumo (ej: menú, venta, merma)
     */
    public function registrarSalida($productoId, $loteId, $cantidad, $usuarioId, $motivo = 'consumo') {
        $stmt = $this->db->prepare("INSERT INTO movimientos (tipo, producto_id, lote_id, cantidad, usuario_id, motivo) VALUES ('salida', ?, ?, ?, ?, ?)");
        return $stmt->execute([$productoId, $loteId, $cantidad, $usuarioId, $motivo]);
    }

    /**
     * Registrar ajuste (corrección de inventario)
     * @param int $productoId ID del producto
     * @param int $loteId ID del lote
     * @param float $cantidad Cantidad ajustada
     * @param int $usuarioId ID del usuario
     * @param string $motivo Razón del ajuste (obligatorio)
     */
    public function registrarAjuste($productoId, $loteId, $cantidad, $usuarioId, $motivo) {
        $stmt = $this->db->prepare("INSERT INTO movimientos (tipo, producto_id, lote_id, cantidad, usuario_id, motivo) VALUES ('ajuste', ?, ?, ?, ?, ?)");
        return $stmt->execute([$productoId, $loteId, $cantidad, $usuarioId, $motivo]);
    }
}
