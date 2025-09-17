-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 17, 2025 at 05:47 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `classmaster`
--

-- --------------------------------------------------------

--
-- Table structure for table `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `tipo_id` int(11) DEFAULT NULL,
  `grado` varchar(20) DEFAULT NULL,
  `seccion` varchar(10) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `curso_estudiante`
--

CREATE TABLE `curso_estudiante` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `curso_profesor`
--

CREATE TABLE `curso_profesor` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `asignado_por_admin_id` int(11) DEFAULT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eventos_curso`
--

CREATE TABLE `eventos_curso` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `creado_por_profesor_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo_evento` varchar(50) DEFAULT 'General'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `profesor_id` int(11) DEFAULT NULL,
  `tipo_eval` varchar(100) DEFAULT 'Parcial',
  `descripcion` varchar(255) DEFAULT NULL,
  `valor` decimal(5,2) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_por` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `padres`
--

CREATE TABLE `padres` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `padres`
--

INSERT INTO `padres` (`id`, `email`, `password`, `nombre`, `apellido`, `telefono`, `fecha_registro`) VALUES
(1, 'jerovilla@gmail.com', '$2y$10$ENbGcY5Gu.jCJl8P029mOekFbSE9Yx2I1vm1SmEJabFRaDfXbY4pW', 'Jeronimo', 'Villa', NULL, '2025-09-15 21:54:35'),
(2, 'davidcabeen@gmail.com', '$2y$10$zcKf4KGnFu/jqnd/I1tfs.vQSvm2hHBXNtJIEJncQfxkflEqe1e8C', 'David', 'Cabeen', NULL, '2025-09-15 21:57:39'),
(4, 'sarahmcabeen@gmail.com', '$2y$10$aDKaLANoRTelYQjZfrvFNuBrdQgpKSw5dvIwDkMMG0BWmqRYpNhHq', 'Sarah', 'Cabeen', NULL, '2025-09-16 01:08:55'),
(5, 'sarahmcaben@gmail.com', '$2y$10$hwCKQQBWE.tkNZFYIITmEOXVd./L5yYIBrbRY91E8xugUq6TjGDAG', 'Sarah', 'Cabeen', NULL, '2025-09-16 01:18:47'),
(6, 's@gmail.com', '$2y$10$Z0.eSkYqZEMcn0gWcCJoHey3AdJkaPZo/aqNcpti1bRi7Evw0p8ZS', 'a', '', NULL, '2025-09-16 01:19:42'),
(7, 'roberto.casas@gmail.com', '$2y$10$Z/WVL5qoNUf8n2OSLMu/9Ox8Xjqgj46CDHdUQ8/k2VHb21B5DOLZG', 'Roberto', 'Casas', NULL, '2025-09-17 13:53:46');

-- --------------------------------------------------------

--
-- Table structure for table `padre_hijo`
--

CREATE TABLE `padre_hijo` (
  `id` int(11) NOT NULL,
  `padre_id` int(11) NOT NULL,
  `hijo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profesores`
--

CREATE TABLE `profesores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `materias` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profesores`
--

INSERT INTO `profesores` (`id`, `nombre`, `apellido`, `email`, `password`, `materias`, `fecha_registro`) VALUES
(3, 'Fernando', 'Correa', 'fernando@icolven.com', '$2y$10$xZECPFtnh2Sijbqs0GJPlOBn6HtFTP8DgNBF5LgZ62AvEuP5vfDCK', 'Educación Física', '2025-09-17 13:57:36');

-- --------------------------------------------------------

--
-- Table structure for table `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_entrega` datetime NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipos_curso`
--

CREATE TABLE `tipos_curso` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `grado` varchar(20) DEFAULT NULL,
  `seccion` varchar(10) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_padre` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nombre`, `apellido`, `email`, `password`, `grado`, `seccion`, `fecha_registro`, `id_padre`) VALUES
(1, 'Juan', 'Pérez', 'juan.perez@estudiante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '10', 'A', '2025-09-09 19:58:31', NULL),
(2, 'María', 'González', 'maria.gonzalez@estudiante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '11', 'B', '2025-09-09 19:58:31', NULL),
(3, 'Carlos', 'Rodríguez', 'carlos.rodriguez@estudiante.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9', 'A', '2025-09-09 19:58:31', NULL),
(125, 'Albert', 'Casanare', 'alberto.casanare@estudiante.com', '$2y$10$cYccKFX/cEZNi6LL9EQn7eG0URI7hES/Q1OGziucq9tDkw47dvRZe', '4', 'B', '2025-09-16 00:55:49', NULL),
(126, 'Aristobulo', 'Alexander', 'aristobulo.alexcander@estudiante.com', '$2y$10$s8vLQ98LTUWLr6oklhvyRuW0r/k5iUHadI6U0SUYhrIWpILnO1pEu', '7', 'A', '2025-09-16 00:59:15', NULL),
(130, 'Minecraft', 'Arnoldo', 'betulia.farfan@estudiante.com', '$2y$10$i96wXEEuM03RNaGXg3tyAebcxzuUGmqgPtfUpsNODRCFJabialfi2', '10', 'C', '2025-09-16 01:00:21', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `tipo_id` (`tipo_id`);

--
-- Indexes for table `curso_estudiante`
--
ALTER TABLE `curso_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `estudiante_id` (`estudiante_id`);

--
-- Indexes for table `curso_profesor`
--
ALTER TABLE `curso_profesor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `profesor_id` (`profesor_id`),
  ADD KEY `asignado_por_admin_id` (`asignado_por_admin_id`);

--
-- Indexes for table `eventos_curso`
--
ALTER TABLE `eventos_curso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `creado_por_profesor_id` (`creado_por_profesor_id`);

--
-- Indexes for table `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- Indexes for table `padres`
--
ALTER TABLE `padres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `padre_hijo`
--
ALTER TABLE `padre_hijo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `padre_id` (`padre_id`),
  ADD KEY `hijo_id` (`hijo_id`);

--
-- Indexes for table `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- Indexes for table `tipos_curso`
--
ALTER TABLE `tipos_curso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_padres` (`id_padre`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `curso_estudiante`
--
ALTER TABLE `curso_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `curso_profesor`
--
ALTER TABLE `curso_profesor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eventos_curso`
--
ALTER TABLE `eventos_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `padres`
--
ALTER TABLE `padres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `padre_hijo`
--
ALTER TABLE `padre_hijo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipos_curso`
--
ALTER TABLE `tipos_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_curso` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `curso_estudiante`
--
ALTER TABLE `curso_estudiante`
  ADD CONSTRAINT `curso_estudiante_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curso_estudiante_ibfk_2` FOREIGN KEY (`estudiante_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `curso_profesor`
--
ALTER TABLE `curso_profesor`
  ADD CONSTRAINT `curso_profesor_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curso_profesor_ibfk_2` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curso_profesor_ibfk_3` FOREIGN KEY (`asignado_por_admin_id`) REFERENCES `administradores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `eventos_curso`
--
ALTER TABLE `eventos_curso`
  ADD CONSTRAINT `eventos_curso_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `eventos_curso_ibfk_2` FOREIGN KEY (`creado_por_profesor_id`) REFERENCES `profesores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`estudiante_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_ibfk_3` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `padre_hijo`
--
ALTER TABLE `padre_hijo`
  ADD CONSTRAINT `padre_hijo_ibfk_1` FOREIGN KEY (`padre_id`) REFERENCES `padres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `padre_hijo_ibfk_2` FOREIGN KEY (`hijo_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_padres` FOREIGN KEY (`id_padre`) REFERENCES `padres` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
