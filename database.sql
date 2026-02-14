-- Database Initialization: Comedor Universitario
CREATE DATABASE IF NOT EXISTS comedor_universitario;
USE comedor_universitario;

-- TABLA 1: Usuarios (Sistema de autenticación)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'cocina', 'inventario') DEFAULT 'inventario',
    estado BOOLEAN DEFAULT TRUE,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- TABLA 2: Proveedores
CREATE TABLE IF NOT EXISTS proveedores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    estado BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- TABLA 3: Categorías de productos
CREATE TABLE IF NOT EXISTS categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    perecedero BOOLEAN DEFAULT TRUE,
    dias_caducidad INT
) ENGINE=InnoDB;

-- TABLA 4: Productos/Insumos
CREATE TABLE IF NOT EXISTS productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(150) NOT NULL,
    categoria_id INT,
    unidad_medida ENUM('kg', 'litros', 'unidades', 'paquetes'),
    stock_minimo DECIMAL(10,2),
    stock_maximo DECIMAL(10,2),
    precio_unitario DECIMAL(10,2),
    proveedor_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id)
) ENGINE=InnoDB;

-- TABLA 5: Inventario (Lotes)
CREATE TABLE IF NOT EXISTS lotes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    producto_id INT,
    numero_lote VARCHAR(50),
    cantidad DECIMAL(10,2),
    fecha_ingreso DATE,
    fecha_caducidad DATE,
    ubicacion VARCHAR(100),
    estado ENUM('disponible', 'consumido', 'vencido', 'dañado') DEFAULT 'disponible',
    FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE=InnoDB;

-- TABLA 6: Menús (Planificación semanal)
CREATE TABLE IF NOT EXISTS menus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    dia_semana ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'),
    tipo ENUM('desayuno', 'almuerzo', 'cena'),
    descripcion TEXT,
    fecha DATE,
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB;

-- TABLA 7: Menú_Productos (Relación N:M)
CREATE TABLE IF NOT EXISTS menu_productos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    menu_id INT,
    producto_id INT,
    cantidad_necesaria DECIMAL(10,2),
    FOREIGN KEY (menu_id) REFERENCES menus(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE=InnoDB;

-- TABLA 8: Movimientos/Transacciones
CREATE TABLE IF NOT EXISTS movimientos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo ENUM('entrada', 'salida', 'ajuste'),
    producto_id INT,
    lote_id INT,
    cantidad DECIMAL(10,2),
    usuario_id INT,
    motivo TEXT,
    fecha_movimiento DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (lote_id) REFERENCES lotes(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- Data de ejemplo básica

-- Usuarios (password: admin123 para todos)
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Administrador Sistema', 'admin@comedor.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrador'),
('Chef Principal', 'chef@comedor.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cocina'),
('Encargado Inventario', 'inventario@comedor.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'inventario');

-- Proveedores
INSERT INTO proveedores (nombre, contacto, telefono, email, direccion) VALUES
('Distribuidora La Fresca', 'Juan Pérez', '555-1234', 'ventas@lafresca.com', 'Av. Principal 123'),
('Carnes Premium S.A.', 'María González', '555-5678', 'pedidos@carnespremium.com', 'Zona Industrial 456'),
('Lácteos del Valle', 'Carlos Ruiz', '555-9012', 'contacto@lacteosval.com', 'Calle Comercio 789');

-- Categorías
INSERT INTO categorias (nombre, descripcion, perecedero, dias_caducidad) VALUES
('Carnes', 'Productos cárnicos frescos', TRUE, 5),
('Lácteos', 'Leche, quesos y derivados', TRUE, 7),
('Verduras', 'Vegetales frescos', TRUE, 3),
('Granos', 'Arroz, frijoles, lentejas', FALSE, NULL),
('Condimentos', 'Especias y sazonadores', FALSE, NULL);

-- Productos
INSERT INTO productos (nombre, categoria_id, unidad_medida, stock_minimo, stock_maximo, precio_unitario, proveedor_id) VALUES
('Pollo Entero', 1, 'kg', 50.00, 200.00, 5.50, 2),
('Carne de Res', 1, 'kg', 30.00, 150.00, 8.75, 2),
('Leche Entera', 2, 'litros', 100.00, 500.00, 1.20, 3),
('Queso Fresco', 2, 'kg', 20.00, 80.00, 4.50, 3),
('Tomate', 3, 'kg', 40.00, 200.00, 1.80, 1),
('Cebolla', 3, 'kg', 30.00, 150.00, 1.50, 1),
('Arroz Blanco', 4, 'kg', 100.00, 500.00, 2.20, 1),
('Frijoles Negros', 4, 'kg', 50.00, 300.00, 2.80, 1),
('Sal', 5, 'kg', 10.00, 50.00, 0.80, 1),
('Aceite Vegetal', 5, 'litros', 20.00, 100.00, 3.50, 1);

-- Lotes (algunos próximos a vencer para demostración)
INSERT INTO lotes (producto_id, numero_lote, cantidad, fecha_ingreso, fecha_caducidad, ubicacion, estado) VALUES
(1, 'POLLO-2024-001', 80.00, '2024-02-01', '2024-02-08', 'Refrigerador A', 'disponible'),
(2, 'CARNE-2024-001', 45.00, '2024-02-02', '2024-02-09', 'Refrigerador B', 'disponible'),
(3, 'LECHE-2024-001', 200.00, '2024-02-03', '2024-02-12', 'Refrigerador C', 'disponible'),
(4, 'QUESO-2024-001', 25.00, '2024-02-01', '2024-02-10', 'Refrigerador C', 'disponible'),
(5, 'TOMATE-2024-001', 60.00, '2024-02-04', '2024-02-09', 'Almacén Fresco', 'disponible'),
(6, 'CEBOLLA-2024-001', 50.00, '2024-02-03', '2024-02-15', 'Almacén Fresco', 'disponible'),
(7, 'ARROZ-2024-001', 300.00, '2024-01-15', '2025-01-15', 'Almacén Seco', 'disponible'),
(8, 'FRIJOL-2024-001', 150.00, '2024-01-20', '2025-01-20', 'Almacén Seco', 'disponible'),
(9, 'SAL-2024-001', 30.00, '2024-01-10', '2026-01-10', 'Almacén Seco', 'disponible'),
(10, 'ACEITE-2024-001', 40.00, '2024-01-25', '2025-01-25', 'Almacén Seco', 'disponible');

-- Menús de ejemplo
INSERT INTO menus (nombre, dia_semana, tipo, descripcion, fecha, activo) VALUES
('Almuerzo Lunes', 'Lunes', 'almuerzo', 'Pollo guisado con arroz y ensalada', '2024-02-05', TRUE),
('Almuerzo Martes', 'Martes', 'almuerzo', 'Carne en salsa con frijoles', '2024-02-06', TRUE);

-- Relación menú-productos
INSERT INTO menu_productos (menu_id, producto_id, cantidad_necesaria) VALUES
(1, 1, 15.00), -- Pollo
(1, 7, 10.00), -- Arroz
(1, 5, 5.00),  -- Tomate
(2, 2, 12.00), -- Carne
(2, 8, 8.00),  -- Frijoles
(2, 6, 3.00);  -- Cebolla
