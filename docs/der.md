# 📊 Diagrama Entidad-Relación (DER)

El sistema se basa en una estructura relacional diseñada en MySQL.

## Tablas del Sistema

Base de Datos: Modelo Entidad-Relación (Simplificado)
Se deberá crear un diagrama detallado, pero las tablas mínimas son:

usuarios: id, nombre, email (único), password, rol (admin, inventario, cocina), fecha_creacion.

proveedores: id, nombre, telefono, email, direccion.

categorias: id, nombre, descripcion.

productos: id, nombre, categoria_id (FK), unidad_medida, stock_minimo, proveedor_id (FK).

lotes: id, producto_id (FK), cantidad_inicial, cantidad_actual, fecha_vencimiento, fecha_ingreso.

menus: id, nombre, dia_semana, tipo_comida (desayuno, almuerzo, cena), fecha_creacion.

menu_ingredientes: id, menu_id (FK), producto_id (FK), cantidad_requerida.

movimientos: id, tipo (entrada, salida), producto_id (FK), lote_id (FK), cantidad, fecha, usuario_id (FK), descripcion (ej. "Consumo del menú X").