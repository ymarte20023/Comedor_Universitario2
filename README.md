# Sistema de Control de Inventario - Comedor Universitario

Sistema fullstack de gestión de inventario para comedores universitarios, desarrollado con arquitectura MVC (Backend PHP) y MVVM (Frontend JavaScript).

## 🎯 Características Principales

- **Gestión de Productos**: CRUD completo con categorías y vinculación a proveedores
- **Gestión de Proveedores**: Módulo dedicado para administración de empresas suministradoras (Admin Only)
- **Control de Lotes**: Sistema FIFO (First In, First Out) para productos perecederos
- **Planificación de Menús**: Creación de menús semanales con consumo automático de inventario
- **Alertas Inteligentes**: Notificaciones de stock crítico y productos próximos a vencer
- **Dashboard Analítico**: Visualización de métricas clave en tiempo real con actualización AJAX (incluye contador de usuarios para administradores)
- **Autenticación Segura**: Sistema de login con persistencia de sesión y recuperación de contraseña
- **Generador de Reportes PDF**: Reportes de inventario y consumo listos para imprimir
- **Historial de Movimientos**: Trazabilidad completa de entradas/salidas
- **Arquitectura MVVM**: Frontend reactivo con ViewModels y API REST

## 🛠️ Stack Tecnológico

- **Backend**: PHP 8.1+ (MVC puro)
- **Base de Datos**: MySQL 8.0
- **Frontend**: HTML5, CSS3 (sin frameworks), JavaScript (MVVM)
- **Servidor**: Apache (XAMPP/WAMP)

## 📋 Requisitos Previos

- XAMPP/WAMP con PHP 8.1+
- MySQL 8.0+
- MySQL 8.0+
- Navegador web moderno

## 📖 Documentación para Usuarios
- [Manual de Usuario - Guía Completa](MANUAL_USUARIO.md)

## 🚀 Instalación

1. **Clonar el repositorio**
   ```bash
   cd C:\xampp\htdocs
   git clone [URL_REPOSITORIO] Comedor_Universitario
   ```

2. **Configurar la base de datos**
   - Abrir phpMyAdmin (http://localhost/phpmyadmin)
   - Importar el archivo `database.sql` (para instalaciones nuevas)
   - **IMPORTANTE**: Importar también `update_password_recovery.sql` para habilitar la recuperación de contraseña si la base de datos ya existía.
   - Verificar que la base de datos `comedor_universitario` se creó correctamente

3. **Configurar credenciales** (opcional)
   - Editar `config/config.php` si tus credenciales de MySQL son diferentes

4. **Iniciar el servidor**
   - Iniciar Apache y MySQL desde el panel de XAMPP
   - Acceder a: http://localhost/Comedor_Universitario

## 👤 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@comedor.edu | admin123 | Administrador |
| chef@comedor.edu | admin123 | Cocina |
| inventario@comedor.edu | admin123 | Inventario |

## 📁 Estructura del Proyecto

```
Comedor_Universitario/
├── app/
│   ├── controllers/    # Controladores MVC
│   ├── models/         # Modelos de datos
│   ├── views/          # Vistas HTML
│   └── core/           # Núcleo (Router, Auth, Database)
├── public/
│   ├── index.php       # Punto de entrada
│   └── assets/         # CSS, JS, imágenes
├── config/             # Configuración
└── database.sql        # Script de inicialización
```

## 🔑 Funcionalidades por Rol

### Administrador
- Acceso completo a todos los módulos
- Generación de reportes PDF
- Gestión de usuarios y proveedores

### Cocina
- Visualización de menús
- Consulta de inventario disponible
- Registro de consumos

### Inventario
- Gestión de productos y lotes
- Control de entradas/salidas
- Alertas de stock

## 📊 Módulos Principales

### Dashboard
- Tarjetas de estadísticas (Total productos, Stock crítico, Lotes por vencer)
- Alertas visuales en tiempo real

### Productos
- Listado con stock actual calculado dinámicamente
- Filtros por categoría y proveedor
- Indicadores visuales de stock crítico

### Lotes
- Gestión de fechas de caducidad
- Sistema FIFO automático para consumos
- Alertas de vencimiento (7 días)

### Menús
- Planificación semanal
- Cálculo automático de ingredientes necesarios
- Consumo automático con FIFO al ejecutar menú
- Validación de disponibilidad de stock

### Proveedores (Solo Administradores)
- Administración centralizada de proveedores vinculados al inventario
- Información de contacto: Teléfono, Email, Dirección y Persona de contacto
- Sistema de deshabilitación y reactivación (Soft Delete)

### Reportes (Solo Administradores)
- Reporte de Inventario: Estado completo con alertas
- Reporte de Consumo: Movimientos en rango de fechas
- Formato HTML optimizado para impresión/PDF

## 🧪 Lógica de Negocio: FIFO

El sistema implementa consumo inteligente de lotes:

```php
// Ejemplo: Al consumir 50kg de pollo
// 1. Se buscan lotes disponibles ordenados por fecha de caducidad
// 2. Se consume primero del lote más próximo a vencer
// 3. Se registra el movimiento en el historial
// 4. Se actualiza el estado del lote (disponible/consumido)
```

## 🎨 Diseño

- CSS personalizado con variables CSS
- Layout responsivo (Grid/Flexbox)
- Paleta de colores profesional
- Sin dependencias de frameworks CSS

## ⚡ Actualizaciones Recientes (Febrero 2026)

### 🎨 Modernización del Sistema de Diseño
- **Identidad Visual Corporativa**: Integración completa del logo en el Navbar y flujos principales, con una nueva paleta de colores basada en el azul marino (#1B4965) y gris pizarra (#5A6C7D) del logo.
- **Glassmorphism UI**: Aplicación de efectos de transparencia y desenfoque (backdrop-blur) en el sidebar y tarjetas de métricas.
- **Estandarización de Botones**: Unificación visual de todas las acciones del sistema. Los botones de "Guardar", "Actualizar" y "Registrar" ahora cuentan con un diseño `btn-primary` azul consistente.
- **Cache Busting**: Sistema de versionado automático en los enlaces CSS para garantizar que las actualizaciones visuales se reflejen instantáneamente sin necesidad de limpiar caché manualmente.

### 🔍 Buscador en Tiempo Real
- **Filtrado Instantáneo**: Implementación de búsqueda por nombre en tiempo real en los 4 módulos clave (Productos, Lotes, Categorías y Proveedores).
- **Cobertura Total**: El motor de búsqueda funciona tanto en los listados activos como en los apartados de elementos deshabilitados (Papelera).
- **UI de Búsqueda Premium**: Barra de búsqueda moderna con iconos integrados, estados de focus animados y diseño responsivo.

### 📄 Reportes PDF de Alta Calidad
- **Branding en Reportes**: Los formatos de Inventario y Consumo ahora incluyen el logo oficial con un encabezado profesional alineado.
- **Layout Mejorado**: Información de metadatos (fecha, periodo, usuario) organizada de forma clara para una presentación profesional.

### 📦 Gestión de Datos y Consistencia
- **Unificación de Estados**: Los badges de estado (Activo, Disponible, Inactivo) se han unificado visualmente en todo el sistema para mejorar la semántica visual.
- **Papelera de Reciclaje (Soft Delete)**: Sistema robusto de deshabilitación y reactivación implementado en todos los módulos maestros para prevenir la pérdida accidental de datos.
- **Seguridad y Accesibilidad**:
    - **Reset de Login**: Los errores de autenticación y campos se limpian automáticamente al refrescar la página para una experiencia más fluida.
    - **Scroll de Accesibilidad**: Página de login optimizada para permitir scroll cuando aparecen mensajes de error, asegurando la visibilidad del botón de acción.
    - **Recuperación de Contraseña**: Nuevo flujo para restablecer credenciales mediante tokens de seguridad temporales (1 hora de validez).
- **Dashboard de Administración**: Nueva métrica de "Total de Usuarios" integrada mediante MVVM para visualización en tiempo real.

## 📝 Próximas Mejoras

- [ ] API REST para integración con aplicación móvil
- [ ] Gráficos estadísticos de consumo histórico (Chart.js)
- [ ] Sistema de notificaciones push para stock mínimo
- [ ] Exportación de reportes a formato Excel

## 👥 Equipo de Desarrollo

- **Desarrollado con ❤️ y la potencia de [Google Antigravity](https://deepmind.google/technologies/gemini/)** 🚀
- Proyecto académico desarrollado siguiendo metodología ágil.

## 📄 Licencia

Proyecto educativo - Universidad [U.N.E.F.A]

---

**Desarrollado con el apoyo de Google Antigravity para la gestión eficiente de comedores universitarios**
