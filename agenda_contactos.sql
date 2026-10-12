
CREATE DATABASE IF NOT EXISTS agenda_contactos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE agenda_contactos;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,

    CONSTRAINT fk_contactos_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
