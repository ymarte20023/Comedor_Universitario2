# 🏗️ Arquitectura del Sistema

## Estándares de Codificación

Estándares de Codificación y Buenas Prácticas
Para garantizar la mantenibilidad y legibilidad del código, se seguirán las siguientes prácticas:

Estándares PHP: Se adherirá a los estándares PSR-1 (Basic Coding Standard) y PSR-12 (Extended Coding Style) para un estilo de código consistente.

Nomenclatura:

Clases: PascalCase (ej. ProductoController, LoteModel).

Métodos y funciones: camelCase (ej. obtenerPorId(), calcularStockCritico()).

Variables: camelCase (ej. $nombreProducto, $fechaCaducidad).

Tablas de BD: snake_case en plural (ej. productos, lotes, usuarios).

Arquitectura: Patrón Modelo-Vista-Controlador (MVC) para separar la lógica de negocio, la presentación y la interacción con los datos.



## Estructura de Carpetas

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

## Lógica de Negocio (Backend)

Funcionalidades Técnicas Clave (Lógica de Negocio)
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