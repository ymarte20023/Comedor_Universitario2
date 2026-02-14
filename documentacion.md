# Documentación del Proyecto: 
## Sistema de Control de Inventario del Comedor Universitario
 * Versión: 1.0
 * Fecha: 2026-02-14
 * Materia: Lenguaje de Programación 2
 * Profesor: Alonzo Centeno
 * Estudiantes:

- Miguel Camejo (MiguelIgnacio03)

- Josfrancis Torres (JosTorres28)

- Alanys Arcaya (ymarte20023)

Parte 1: Documento de Visión y Alcance
1.1 Resumen Ejecutivo
El presente documento describe la visión, el alcance y los objetivos del Sistema de Control de Inventario del Comedor Universitario. Este sistema ha sido desarrollado como proyecto final para la materia de Lenguaje de Programación 2, con el objetivo de aplicar los conceptos de programación orientada a objetos, desarrollo web con PHP y diseño de bases de datos relacionales en un entorno de gestión real.

-----------------------------------------

1.2 Propósito del Sistema
Optimizar la gestión de insumos del comedor universitario, garantizando un control preciso de inventario, reduciendo el desperdicio de alimentos perecederos mediante el sistema FIFO, y facilitando la planificación de menús. El sistema busca reemplazar los métodos manuales (hojas de cálculo o papel) por una plataforma centralizada, segura y accesible.

-----------------------------------------

1.3 Alcance y Funcionalidades Clave
El sistema cubrirá los siguientes procesos:

Gestión de Usuarios y Roles: Control de acceso basado en roles (Administrador, Inventario, Cocina).

Administración de Proveedores: CRUD completo y registro de información de contacto.

Control de Inventario:

Gestión de productos con categorías y unidades de medida.

Registro de lotes con fechas de caducidad.

Lógica de negocio FIFO (First-In, First-Out) para el consumo de insumos.

Planificación de Menús: Creación de menús diarios con recetas y descuento automático de inventario al ser ejecutados.

Alertas Automáticas: Identificación visual de stock crítico y lotes próximos a vencer.

Generación de Reportes: Reportes de inventario actual y consumo histórico en formato imprimible/PDF.


-----------------------------------------

1.4 Usuarios del Sistema
Administrador: Gestiona usuarios, proveedores y visualiza todos los reportes.

Encargado de Inventario: Realiza el control de productos, lotes y entradas de mercancía.

Personal de Cocina: Consulta el stock disponible y ejecuta los menús planificados.


-----------------------------------------

1.5 Tecnologías Utilizadas
Backend: PHP (orientado a objetos, siguiendo el patrón MVC).

Base de Datos: MySQL/MariaDB.

Frontend: HTML5, CSS3, JavaScript.

Control de Versiones: Git.

-----------------------------------------

Parte 2: Manual Técnico / Guía de Desarrollo
2.1 Estándares de Codificación y Buenas Prácticas
Para garantizar la mantenibilidad y legibilidad del código, se seguirán las siguientes prácticas:

Estándares PHP: Se adherirá a los estándares PSR-1 (Basic Coding Standard) y PSR-12 (Extended Coding Style) para un estilo de código consistente.

Nomenclatura:

Clases: PascalCase (ej. ProductoController, LoteModel).

Métodos y funciones: camelCase (ej. obtenerPorId(), calcularStockCritico()).

Variables: camelCase (ej. $nombreProducto, $fechaCaducidad).

Tablas de BD: snake_case en plural (ej. productos, lotes, usuarios).

Arquitectura: Patrón Modelo-Vista-Controlador (MVC) para separar la lógica de negocio, la presentación y la interacción con los datos.

-----------------------------------------

2.2 Estructura Detallada del Proyecto

proyecto-comedor/
├── app/
│   ├── controllers/       # Controladores (ProductoController.php, LoteController.php)
│   ├── models/            # Modelos (Producto.php, Lote.php, Usuario.php)
│   ├── views/             # Plantillas de vista (productos/index.php, dashboard.php)
│   └── core/              # Clases del núcleo del framework
│       ├── Router.php     # Enrutador de URLs
│       ├── Database.php   # Conexión a la BD (PDO)
│       ├── Controller.php # Controlador base
│       └── Model.php      # Modelo base (con métodos CRUD genéricos)
├── public/                # Documento raíz del servidor web
│   ├── index.php          # Punto de entrada frontal (Front Controller)
│   ├── assets/            # Archivos públicos (CSS, JS, imágenes)
│   │   ├── css/
│   │   ├── js/
│   │   └── uploads/       # Para posibles imágenes de productos (con .gitkeep)
│   └── .htaccess          # Reglas de reescritura para URLs amigables
├── config/
│   └── database.php       # Configuración de conexión a la BD
├── storage/               # Archivos generados por la app (logs, caché)
├── vendor/                # Dependencias de Composer (si se usan)
├── .gitignore             # Archivos y carpetas ignorados por Git
├── composer.json          # Dependencias de PHP (si se usan)
└── README.md              # Descripción general del proyecto


-----------------------------------------

2.3 Base de Datos: Modelo Entidad-Relación (Simplificado)
Se deberá crear un diagrama detallado, pero las tablas mínimas son:

usuarios: id, nombre, email (único), password, rol (admin, inventario, cocina), fecha_creacion.

proveedores: id, nombre, telefono, email, direccion.

categorias: id, nombre, descripcion.

productos: id, nombre, categoria_id (FK), unidad_medida, stock_minimo, proveedor_id (FK).

lotes: id, producto_id (FK), cantidad_inicial, cantidad_actual, fecha_vencimiento, fecha_ingreso.

menus: id, nombre, dia_semana, tipo_comida (desayuno, almuerzo, cena), fecha_creacion.

menu_ingredientes: id, menu_id (FK), producto_id (FK), cantidad_requerida.

movimientos: id, tipo (entrada, salida), producto_id (FK), lote_id (FK), cantidad, fecha, usuario_id (FK), descripcion (ej. "Consumo del menú X").


-----------------------------------------

2.4 Funcionalidades Técnicas Clave (Lógica de Negocio)
Sistema FIFO (First-In, First-Out) para Consumo
Al registrar una salida de producto (por ejemplo, al ejecutar un menú), la lógica en el modelo de Movimiento o Producto debe ser:

Obtener todos los lotes del producto con cantidad_actual > 0, ordenados por fecha_vencimiento ASC (los más próximos a vencer primero).

Recorrer estos lotes y descontar la cantidad necesaria, lote por lote, hasta cubrir la cantidad total solicitada.

Actualizar la cantidad_actual de cada lote afectado.

Si no hay suficiente stock total, la operación debe cancelarse y mostrar un error al usuario.

Autenticación y Seguridad
Contraseñas: Deben ser hasheadas con password_hash() y verificadas con password_verify().

Recuperación de Contraseña:

Generar un token único y seguro (ej. bin2hex(random_bytes(32))).
Almacenar el token y su fecha de expiración (1 hora) en la tabla usuarios o en una tabla password_resets.
Enviar un enlace al correo que incluya el token.
Al hacer clic, verificar el token y su vigencia, y permitir el cambio de contraseña.
Protección contra CSRF: Implementar tokens CSRF en todos los formularios de acción (crear, editar, eliminar).

Sanitización: Usar htmlspecialchars() al mostrar datos en las vistas para prevenir XSS.

Soft Delete
En lugar de eliminar físicamente un producto de la base de datos, se añade un campo deleted_at (timestamp) a la tabla productos. Las consultas principales deben filtrar por deleted_at IS NULL.


-----------------------------------------

2.5 Instalación y Configuración (Para Desarrolladores)
Clonar el repositorio: git clone <url-del-repositorio>

Configurar el servidor web: Apuntar el dominio virtual (o la raíz del servidor local) a la carpeta public/.

Configurar la base de datos:

Crear una base de datos MySQL (ej. comedor_db).

Importar el archivo database.sql (debes crearlo con la estructura inicial).

Configurar la aplicación:

Copiar config/database.example.php a config/database.php.

Editar config/database.php con los datos de conexión a tu BD.

Acceder al sistema: Navegar a la URL configurada. El primer usuario administrador deberá crearse manualmente en la BD o a través de un script de instalación.


-----------------------------------------


# 📘 Manual de Usuario - Comedor Universitario

Bienvenido al Manual de Usuario del **Sistema de Control de Inventario del Comedor Universitario**. Este documento le guiará a través de todas las funcionalidades del sistema según su rol asignado.

---

## 🔐 1. Acceso y Seguridad

### Inicio de Sesión
1. Ingrese su correo institucional (ej: `usuario@comedor.edu`) y su contraseña.
2. Si comete un error, puede refrescar la página para limpiar los campos y el mensaje de error automáticamente.

### Recuperación de Contraseña
Si olvida su clave:
1. Haga clic en **"¿Olvidaste tu contraseña?"** en la pantalla de login.
2. Ingrese su correo `@comedor.edu`.
3. El sistema validará su correo y le mostrará un **enlace temporal de recuperación** (válido por 1 hora).
4. Siga el enlace para definir una nueva contraseña que cumpla con los requisitos de seguridad (mínimo 6 caracteres, 1 mayúscula, 1 número y 1 símbolo).

---

## 👥 2. Roles y Permisos

El sistema se adapta según el tipo de usuario:

*   **Administrador**: Acceso total. Gestión de usuarios, proveedores, reportes avanzados y estadísticas globales.
*   **Inventario**: Gestión de productos, ingreso de lotes y control de almacén.
*   **Cocina**: Consulta de stock, planificación de menús y registro de consumos.

---

## 📊 3. Panel de Control (Dashboard)

Al ingresar, verá un resumen visual del estado del comedor:
- **Total de Productos/Usuarios**: Cifras generales de gestión.
- **Stock Crítico**: Productos que han bajado de su nivel mínimo permitido.
- **Lotes por Vencer**: Alerta de productos que caducarán en los próximos 7 días.
- **Botón Actualizar**: Refresca las métricas en tiempo real sin recargar la página.

---

## 📦 4. Gestión de Inventario

### Productos
- **Creación**: Defina el nombre, categoría, unidad de medida (Kg, Litros, etc.) y niveles de stock.
- **Búsqueda**: Use la barra superior para filtrar productos por nombre al instante.
- **Soft Delete**: Si elimina un producto, este va a la **Papelera**, desde donde puede reactivarlo si fue un error.

### Lotes (Sistema FIFO)
El sistema utiliza la lógica **FIFO (Primero en Entrar, Primero en Salir)**:
- Al registrar una entrada, asigne la fecha de caducidad.
- El sistema consumirá automáticamente los productos de los lotes más próximos a vencer para evitar desperdicios.

---

## 🥗 5. Menús y Consumo

1. **Planificación**: Cree menús indicando el día de la semana y tipo (desayuno/almuerzo/cena).
2. **Ingredientes**: Agregue los productos necesarios y la cantidad requerida.
3. **Ejecución**: Al ejecutar un menú, el sistema descuenta automáticamente la cantidad del inventario siguiendo la lógica de vencimiento.

---

## 🏢 6. Proveedores (Solo Admin)

Controle la red de suministros:
- Vincule cada producto a un proveedor específico.
- Mantenga actualizada la información de contacto (teléfono, correo, dirección).

---

## 📄 7. Reportes (Solo Admin y Inventario)

Genere documentos profesionales con el logo institucional:
- **Reporte de Inventario**: Estado actual de todos los insumos.
- **Reporte de Consumo**: Historial de movimientos entre dos fechas específicas.
- *Tip: Use Ctrl+P en la vista del reporte para guardarlo directamente como PDF.*

---

## 🔍 8. Búsqueda y Filtrado

En cada módulo (Productos, Lotes, Categorías, Usuarios), encontrará una **Barra de Búsqueda Moderna**:
- El filtrado es **en tiempo real**: la tabla se actualiza mientras escribe.
- Puede buscar por nombres o identificadores clave.

---

## 🛠️ 9. Soporte Técnico

Si experimenta problemas técnicos:
1. Verifique que su conexión a la red universitaria esté activa.
2. Asegúrese de estar usando un navegador moderno (Chrome, Edge, Firefox).
3. Contacte al administrador del sistema si su rol no le permite acceder a una función necesaria.

---
*Desarrollado para la gestión eficiente y transparente del Comedor Universitario.*
