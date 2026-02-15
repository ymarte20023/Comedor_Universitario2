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

Visión y Alcance
Resumen Ejecutivo
El presente documento describe la visión, el alcance y los objetivos del Sistema de Control de Inventario del Comedor Universitario. Este sistema ha sido desarrollado como proyecto final para la materia de Lenguaje de Programación 2, con el objetivo de aplicar los conceptos de programación orientada a objetos, desarrollo web con PHP y diseño de bases de datos relacionales en un entorno de gestión real.

Propósito del Sistema
Optimizar la gestión de insumos del comedor universitario, garantizando un control preciso de inventario, reduciendo el desperdicio de alimentos perecederos mediante el sistema FIFO, y facilitando la planificación de menús. El sistema busca reemplazar los métodos manuales (hojas de cálculo o papel) por una plataforma centralizada, segura y accesible.
---------------------------------------------------------------------------

1.1. Alcance y Funcionalidades Clave
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

1.2. Usuarios del Sistema
Administrador: Gestiona usuarios, proveedores y visualiza todos los reportes.

Encargado de Inventario: Realiza el control de productos, lotes y entradas de mercancía.

Personal de Cocina: Consulta el stock disponible y ejecuta los menús planificados.


-----------------------------------------

1.3.  Tecnologías Utilizadas
Backend: PHP (orientado a objetos, siguiendo el patrón MVC).

Base de Datos: MySQL/MariaDB.

Frontend: HTML5, CSS3, JavaScript.

Control de Versiones: Git.

-----------------------------------------