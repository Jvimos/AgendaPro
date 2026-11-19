-- ============================================================
-- BASE DE DATOS: agenda_contactos
-- Sistema: AgendaPro - Agenda Personal de Contactos
-- Versión con Mejoras Avanzadas (Categorías, Favoritos, Ordenamiento, Búsqueda)
-- ============================================================

CREATE DATABASE IF NOT EXISTS agenda_contactos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE agenda_contactos;

-- ------------------------------------------------------------
-- 1. Tabla de Usuarios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. Tabla de Categorías (Mejora: Opción C)
-- Categorías requeridas: Familia, Amigos, Trabajo, Universidad, Otro
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Inserción de categorías predeterminadas
INSERT INTO categorias (id, nombre) VALUES
(1, 'Familia'),
(2, 'Amigos'),
(3, 'Trabajo'),
(4, 'Universidad'),
(5, 'Otro')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- ------------------------------------------------------------
-- 3. Tabla de Contactos
-- Incluye:
--   - id_categoria (Opción C)
--   - es_favorito (Opción D)
--   - fecha_creacion (Opción B)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_categoria INT NOT NULL DEFAULT 5,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    es_favorito TINYINT(1) NOT NULL DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_contactos_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_contactos_categoria
        FOREIGN KEY (id_categoria)
        REFERENCES categorias(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- SCRIPT DE MIGRACIÓN (Si ya tienes una base previa creada)
-- ------------------------------------------------------------
-- Si ya contabas con la base de datos anterior y deseas actualizar
-- tu tabla existente sin perder datos, ejecuta las siguientes líneas:
/*
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categorias (id, nombre) VALUES
(1, 'Familia'), (2, 'Amigos'), (3, 'Trabajo'), (4, 'Universidad'), (5, 'Otro')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

ALTER TABLE contactos 
    ADD COLUMN IF NOT EXISTS id_categoria INT NOT NULL DEFAULT 5 AFTER id_usuario,
    ADD COLUMN IF NOT EXISTS es_favorito TINYINT(1) NOT NULL DEFAULT 0 AFTER ciudad,
    ADD COLUMN IF NOT EXISTS fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER es_favorito;

ALTER TABLE contactos 
    ADD CONSTRAINT fk_contactos_categoria 
    FOREIGN KEY (id_categoria) REFERENCES categorias(id) 
    ON DELETE RESTRICT ON UPDATE CASCADE;
*/
