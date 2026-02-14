# Guía de Instalación Rápida

## Pasos para Iniciar el Sistema

### 1. Verificar XAMPP
- Asegúrese de que **Apache** y **MySQL** estén iniciados en el panel de control de XAMPP
- Verifique que el módulo `mod_rewrite` esté habilitado en Apache

### 2. Importar Base de Datos
1. Abra phpMyAdmin: `http://localhost/phpmyadmin`
2. Cree una nueva base de datos llamada `comedor_universitario`
3. Importe el archivo `database.sql` ubicado en la raíz del proyecto.
4. **IMPORTANTE**: Importe también el archivo `update_password_recovery.sql` para habilitar el sistema de recuperación de contraseña.

### 3. Acceder al Sistema
- URL principal: `http://localhost/Comedor_Universitario/`
- El sistema lo redirigirá automáticamente a la página de login

### 4. Credenciales de Acceso

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Administrador | admin@comedor.edu | admin123 |
| Chef | chef@comedor.edu | admin123 |
| Inventario | inventario@comedor.edu | admin123 |

## Solución de Problemas Comunes

### Error 404 al acceder
**Causa:** El módulo `mod_rewrite` de Apache no está habilitado.

**Solución:**
1. Abra el archivo `C:\xampp\apache\conf\httpd.conf`
2. Busque la línea: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Elimine el `#` al inicio para descomentarla
4. Busque `AllowOverride None` y cámbielo a `AllowOverride All`
5. Reinicie Apache desde el panel de XAMPP

### Error de conexión a la base de datos
**Causa:** Credenciales incorrectas o base de datos no creada.

**Solución:**
1. Verifique que MySQL esté corriendo
2. Revise las credenciales en `config/config.php`
3. Asegúrese de haber importado `database.sql`

### Scripts JavaScript no cargan
**Causa:** Rutas incorrectas o archivos faltantes.

**Solución:**
- Verifique que exista la carpeta `public/assets/js/viewmodels/`
- Asegúrese de que el archivo `ViewModel.js` esté presente

## Estructura de URLs

Una vez configurado correctamente, las URLs funcionarán así:

- Login: `http://localhost/Comedor_Universitario/` o `/login`
- Dashboard: `http://localhost/Comedor_Universitario/dashboard`
- Productos: `http://localhost/Comedor_Universitario/productos`
- Lotes: `http://localhost/Comedor_Universitario/lotes`
- Menús: `http://localhost/Comedor_Universitario/menus`
- Reportes: `http://localhost/Comedor_Universitario/reportes` (solo admin)

## Verificación de Instalación

Para verificar que todo está funcionando:

1. Acceda a `http://localhost/Comedor_Universitario/`
2. Debería ver la pantalla de login
3. Inicie sesión con las credenciales de administrador
4. Debería ser redirigido al Dashboard con estadísticas

Si ve el Dashboard con las tarjetas de estadísticas, ¡la instalación fue exitosa! 🎉
