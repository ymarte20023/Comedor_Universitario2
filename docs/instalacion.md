# 🛠️ Guía de Instalación Local

Siga estos pasos para ejecutar el proyecto en su entorno local (XAMPP/WAMP):

1. **Clonar el repositorio:** `git clone <url>`
2. **Configurar DB:** Importar `database.sql` en phpMyAdmin.

Configurar la base de datos:

Crear una base de datos MySQL (ej. comedor_db).

Importar el archivo database.sql (debes crearlo con la estructura inicial).

Configurar la aplicación:

Copiar config/database.example.php a config/database.php.

Editar config/database.php con los datos de conexión a tu BD.

Acceder al sistema: Navegar a la URL configurada. El primer usuario administrador deberá crearse manualmente en la BD o a través de un script de instalación.
