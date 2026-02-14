<?php
/**
 * Control de Caducidad - Lógica FIFO
 */
class ControlCaducidad {
    private $db;
    private $loteModel;
    private $movimientoModel;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->loteModel = new LoteModel();
        $this->movimientoModel = new MovimientoModel();
    }

    /**
     * Consumir productos usando FIFO (First In, First Out)
     * Prioriza lotes más próximos a vencer
     */
    public function consumirPorFIFO($productoId, $cantidad, $usuarioId, $motivo = 'consumo_menu') {
        // Obtener lotes disponibles ordenados por fecha de caducidad
        $lotes = $this->loteModel->getByProducto($productoId);
        
        if (empty($lotes)) {
            return ['success' => false, 'message' => 'No hay lotes disponibles para este producto'];
        }

        $cantidadRestante = $cantidad;
        $lotesConsumidos = [];

        foreach ($lotes as $lote) {
            if ($cantidadRestante <= 0) break;

            $cantidadAConsumir = min($lote['cantidad'], $cantidadRestante);
            $nuevaCantidad = $lote['cantidad'] - $cantidadAConsumir;

            // Actualizar cantidad del lote
            if ($nuevaCantidad == 0) {
                $this->loteModel->updateEstado($lote['id'], 'consumido');
            } else {
                $this->loteModel->updateCantidad($lote['id'], $nuevaCantidad);
            }

            // Registrar movimiento
            $this->movimientoModel->registrarSalida(
                $productoId,
                $lote['id'],
                $cantidadAConsumir,
                $usuarioId,
                $motivo
            );

            $cantidadRestante -= $cantidadAConsumir;
            $lotesConsumidos[] = [
                'lote_id' => $lote['id'],
                'cantidad' => $cantidadAConsumir
            ];
        }

        if ($cantidadRestante > 0) {
            return [
                'success' => false,
                'message' => "Stock insuficiente. Faltaron {$cantidadRestante} unidades",
                'lotes_consumidos' => $lotesConsumidos
            ];
        }

        return [
            'success' => true,
            'message' => 'Consumo registrado exitosamente',
            'lotes_consumidos' => $lotesConsumidos
        ];
    }

    /**
     * Marcar lotes vencidos
     */
    public function marcarLotesVencidos() {
        $sql = "UPDATE lotes SET estado = 'vencido' 
                WHERE estado = 'disponible' 
                AND fecha_caducidad < CURDATE()";
        return $this->db->exec($sql);
    }
}
