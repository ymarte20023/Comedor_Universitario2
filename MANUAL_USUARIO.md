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
