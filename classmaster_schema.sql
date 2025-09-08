-- Schema limpio de base de datos para ClassMaster
-- Solo tablas realmente utilizadas en el código

CREATE DATABASE IF NOT EXISTS classmaster;
USE classmaster;

-- Tabla de usuarios (estudiantes)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    grado VARCHAR(20),
    seccion VARCHAR(10),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de padres/acudientes
CREATE TABLE padres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);

-- Insertar datos de ejemplo
INSERT INTO users (nombre, apellido, email, password, grado, seccion) VALUES
('Juan', 'Pérez', 'juan.perez@estudiante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '10', 'A'),
('María', 'González', 'maria.gonzalez@estudiante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '11', 'B'),
('Carlos', 'Rodríguez', 'carlos.rodriguez@estudiante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9', 'A');

INSERT INTO padres (email, contrasena, nombre, apellido, telefono) VALUES
('padre1@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Roberto', 'Pérez', '555-1234'),
('madre1@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana', 'González', '555-5678');