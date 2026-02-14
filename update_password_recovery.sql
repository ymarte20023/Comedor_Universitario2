-- Actualización de Base de Datos para Recuperación de Contraseña
--UPDATE database schema para soporte de tokens de recuperación

ALTER TABLE usuarios 
ADD COLUMN reset_token VARCHAR(255) NULL AFTER rol, 
ADD COLUMN reset_expires DATETIME NULL AFTER reset_token;
