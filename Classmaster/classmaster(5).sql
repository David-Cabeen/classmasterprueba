-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 29, 2025 at 10:38 PM
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
-- Table structure for table `acudientes`
--

CREATE TABLE `acudientes` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acudientes`
--

INSERT INTO `acudientes` (`id`, `email`, `password`, `nombre`, `apellido`, `telefono`, `fecha_registro`) VALUES
(1, 'jerovilla@gmail.com', '$2y$10$ENbGcY5Gu.jCJl8P029mOekFbSE9Yx2I1vm1SmEJabFRaDfXbY4pW', 'Jeronimo', 'Villa', NULL, '2025-09-15 21:54:35'),
(2, 'davidcabeen@gmail.com', '$2y$10$zcKf4KGnFu/jqnd/I1tfs.vQSvm2hHBXNtJIEJncQfxkflEqe1e8C', 'David', 'Cabeen', NULL, '2025-09-15 21:57:39'),
(6, 's@gmail.com', '$2y$10$Z0.eSkYqZEMcn0gWcCJoHey3AdJkaPZo/aqNcpti1bRi7Evw0p8ZS', 'a', '', NULL, '2025-09-16 01:19:42'),
(7, 'roberto.casas@gmail.com', '$2y$10$Z/WVL5qoNUf8n2OSLMu/9Ox8Xjqgj46CDHdUQ8/k2VHb21B5DOLZG', 'Roberto', 'Casas', NULL, '2025-09-17 13:53:46'),
(8, 'paulinavalderrama@gmail.com', '$2y$10$pXbkhF4.uu0KKVJY9UB3AOsYwPOZGlkRpikTpme3oz65onRWPLpcu', 'Paulina', 'Valderrama', NULL, '2025-09-18 21:48:15'),
(9, 'marcos.vidal@gmail.com', '$2y$10$o6bmJVJhR8juALCzOlnrgO2QKrj4wEXa5RhBJW7zpyxq5pCECzZbi', 'Marcos', 'Vidal', NULL, '2025-09-19 20:48:19'),
(10, 'adam.cabeen@gmail.com', '$2y$10$zmJGeT8omHdKqcB3vTKDQuHxRREzyUiUC56yzWi1foAFOeqiCu3rq', 'Adam', 'Cabeen', NULL, '2025-09-19 21:38:13'),
(11, 'andresito@gmail.com', '$2y$10$1Nr9ln9OJJX/U8zt0u0myuAnumPqw3n5nZb8ottWtvZBIZ2C9vYa6', 'Andres', 'Cepeda', NULL, '2025-09-20 01:51:16'),
(12, 'sarahmcabeen@gmail.com', '$2y$10$QU./xHuL3C54vYmEDTBS9OUY280rgQh/BRKiOY.SuvzX0kotNsjzi', 'Sarah', 'Cabeen', NULL, '2025-09-21 02:05:53');

-- --------------------------------------------------------

--
-- Table structure for table `administradores`
--

CREATE TABLE `administradores` (
  `id` varchar(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administradores`
--

INSERT INTO `administradores` (`id`, `nombre`, `apellido`, `email`, `password`, `fecha_registro`) VALUES
('4GARFKJVXRE', 'Raul', 'Suarez', 'raulsuarez@icolven.com', '$2y$10$kvu74SehKKfL4QELZBg2wupZrU4ANOU9VSe8O0IUOUNRB2B3W8Lym', '2025-09-22 11:56:54'),
('M6IGDUXOEMB', 'Paulina', 'Valderrama', 'valderramapaulina221@gmail.com', '$2y$10$UrWWeepHg1.AunOhFSvc1eytnDpxxxJ1ObI/8mjeuf2GsVFKCQH.W', '2025-09-23 00:15:47'),
('QX18QR6Z7JX', 'El fan', 'De shakira', 'shakirasoytufan@gmail.com', '$2y$10$/yV0TgV15HBNVkaSCb.snuDTj.rPT0o3aPBKbZlo1yShI0Wsu2UFu', '2025-09-22 12:51:20'),
('REQXQ1XKERI', 'Luciamparo', 'Cely', 'luciamparo@gmail.com', '$2y$10$u2DGVeWcZ35RtOfmf4x2xuLnn9n3AxNSYhtl7.eITunHee3lgSEfy', '2025-09-22 12:10:03'),
('U6M5ZCJBCO1', 'David', 'Cabeen', 'mediatecnicadavidcabeen@gmail.com', '$2y$10$Ebs56EUIlYwWtotGHwErcuGlF38gPimI25OJoQB1S4S1OdlXVMDbm', '2025-09-22 12:44:50');

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `grado` varchar(20) DEFAULT NULL,
  `seccion` varchar(10) DEFAULT NULL,
  `profesor_id` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `grado`, `seccion`, `profesor_id`) VALUES
(1, 'Matemáticas 1A', '1', 'A', 'P050371'),
(2, 'Español 1A', '1', 'A', 'P050371'),
(3, 'Inglés 1A', '1', 'A', 'P050371'),
(4, 'Sociales 1A', '1', 'A', 'P050371'),
(5, 'Educación Física 1A', '1', 'A', 'P050371'),
(6, 'Religión 1A', '1', 'A', 'P050371'),
(7, 'Artística 1A', '1', 'A', 'P050371'),
(8, 'Science 1A', '1', 'A', 'P050371'),
(9, 'Matemáticas 1B', '1', 'B', 'P050371'),
(10, 'Español 1B', '1', 'B', 'P050371'),
(11, 'Inglés 1B', '1', 'B', 'P050371'),
(12, 'Sociales 1B', '1', 'B', 'P050371'),
(13, 'Educación Física 1B', '1', 'B', 'P050371'),
(14, 'Religión 1B', '1', 'B', 'P050371'),
(15, 'Artística 1B', '1', 'B', 'P050371'),
(16, 'Science 1B', '1', 'B', 'P050371'),
(17, 'Matemáticas 2A', '2', 'A', 'P050371'),
(18, 'Español 2A', '2', 'A', 'P050371'),
(19, 'Inglés 2A', '2', 'A', 'P050371'),
(20, 'Sociales 2A', '2', 'A', 'P050371'),
(21, 'Educación Física 2A', '2', 'A', 'P050371'),
(22, 'Religión 2A', '2', 'A', 'P050371'),
(23, 'Artística 2A', '2', 'A', 'P050371'),
(24, 'Science 2A', '2', 'A', 'P050371'),
(25, 'Matemáticas 2B', '2', 'B', 'P050371'),
(26, 'Español 2B', '2', 'B', 'P050371'),
(27, 'Inglés 2B', '2', 'B', 'P050371'),
(28, 'Sociales 2B', '2', 'B', 'P050371'),
(29, 'Educación Física 2B', '2', 'B', 'P050371'),
(30, 'Religión 2B', '2', 'B', 'P050371'),
(31, 'Artística 2B', '2', 'B', 'P050371'),
(32, 'Science 2B', '2', 'B', 'P050371'),
(33, 'Matemáticas 3A', '3', 'A', 'P050371'),
(34, 'Español 3A', '3', 'A', 'P050371'),
(35, 'Inglés 3A', '3', 'A', 'P050371'),
(36, 'Sociales 3A', '3', 'A', 'P050371'),
(37, 'Educación Física 3A', '3', 'A', 'P050371'),
(38, 'Religión 3A', '3', 'A', 'P050371'),
(39, 'Artística 3A', '3', 'A', 'P050371'),
(40, 'Science 3A', '3', 'A', 'P050371'),
(41, 'Matemáticas 3B', '3', 'B', 'P050371'),
(42, 'Español 3B', '3', 'B', 'P050371'),
(43, 'Inglés 3B', '3', 'B', 'P050371'),
(44, 'Sociales 3B', '3', 'B', 'P050371'),
(45, 'Educación Física 3B', '3', 'B', 'P050371'),
(46, 'Religión 3B', '3', 'B', 'P050371'),
(47, 'Artística 3B', '3', 'B', 'P050371'),
(48, 'Science 3B', '3', 'B', 'P050371'),
(49, 'Matemáticas 4A', '4', 'A', 'P050371'),
(50, 'Español 4A', '4', 'A', 'P050371'),
(51, 'Inglés 4A', '4', 'A', 'P050371'),
(52, 'Sociales 4A', '4', 'A', 'P050371'),
(53, 'Educación Física 4A', '4', 'A', 'P050371'),
(54, 'Religión 4A', '4', 'A', 'P050371'),
(55, 'Artística 4A', '4', 'A', 'P050371'),
(56, 'Science 4A', '4', 'A', 'P050371'),
(57, 'Ética 4A', '4', 'A', 'P050371'),
(58, 'Matemáticas 4B', '4', 'B', 'P050371'),
(59, 'Español 4B', '4', 'B', 'P050371'),
(60, 'Inglés 4B', '4', 'B', 'P050371'),
(61, 'Sociales 4B', '4', 'B', 'P050371'),
(62, 'Educación Física 4B', '4', 'B', 'P050371'),
(63, 'Religión 4B', '4', 'B', 'P050371'),
(64, 'Artística 4B', '4', 'B', 'P050371'),
(65, 'Science 4B', '4', 'B', 'P050371'),
(66, 'Ética 4B', '4', 'B', 'P050371'),
(67, 'Matemáticas 5A', '5', 'A', 'P050371'),
(68, 'Español 5A', '5', 'A', 'P050371'),
(69, 'Inglés 5A', '5', 'A', 'P050371'),
(70, 'Sociales 5A', '5', 'A', 'P050371'),
(71, 'Educación Física 5A', '5', 'A', 'P050371'),
(72, 'Religión 5A', '5', 'A', 'P050371'),
(73, 'Artística 5A', '5', 'A', 'P050371'),
(74, 'Science 5A', '5', 'A', 'P050371'),
(75, 'Ética 5A', '5', 'A', 'P050371'),
(76, 'Matemáticas 5B', '5', 'B', 'P050371'),
(77, 'Español 5B', '5', 'B', 'P050371'),
(78, 'Inglés 5B', '5', 'B', 'P050371'),
(79, 'Sociales 5B', '5', 'B', 'P050371'),
(80, 'Educación Física 5B', '5', 'B', 'P050371'),
(81, 'Religión 5B', '5', 'B', 'P050371'),
(82, 'Artística 5B', '5', 'B', 'P050371'),
(83, 'Science 5B', '5', 'B', 'P050371'),
(84, 'Ética 5B', '5', 'B', 'P050371'),
(85, 'Matemáticas 6A', '6', 'A', 'P050371'),
(86, 'Español 6A', '6', 'A', 'P050371'),
(87, 'Inglés 6A', '6', 'A', 'P050371'),
(88, 'Sociales 6A', '6', 'A', 'P050371'),
(89, 'Educación Física 6A', '6', 'A', 'P050371'),
(90, 'Religión 6A', '6', 'A', 'P050371'),
(91, 'Artística 6A', '6', 'A', 'P050371'),
(92, 'Science 6A', '6', 'A', 'P050371'),
(93, 'Ética 6A', '6', 'A', 'P050371'),
(94, 'Matemáticas 6B', '6', 'B', 'P050371'),
(95, 'Español 6B', '6', 'B', 'P050371'),
(96, 'Inglés 6B', '6', 'B', 'P050371'),
(97, 'Sociales 6B', '6', 'B', 'P050371'),
(98, 'Educación Física 6B', '6', 'B', 'P050371'),
(99, 'Religión 6B', '6', 'B', 'P050371'),
(100, 'Artística 6B', '6', 'B', 'P050371'),
(101, 'Science 6B', '6', 'B', 'P050371'),
(102, 'Ética 6B', '6', 'B', 'P050371'),
(103, 'Matemáticas 7A', '7', 'A', 'P050371'),
(104, 'Español 7A', '7', 'A', 'P050371'),
(105, 'Inglés 7A', '7', 'A', 'P050371'),
(106, 'Sociales 7A', '7', 'A', 'P050371'),
(107, 'Educación Física 7A', '7', 'A', 'P050371'),
(108, 'Religión 7A', '7', 'A', 'P050371'),
(109, 'Artística 7A', '7', 'A', 'P050371'),
(110, 'Science 7A', '7', 'A', 'P050371'),
(111, 'Ética 7A', '7', 'A', 'P050371'),
(112, 'Matemáticas 7B', '7', 'B', 'P050371'),
(113, 'Español 7B', '7', 'B', 'P050371'),
(114, 'Inglés 7B', '7', 'B', 'P050371'),
(115, 'Sociales 7B', '7', 'B', 'P050371'),
(116, 'Educación Física 7B', '7', 'B', 'P050371'),
(117, 'Religión 7B', '7', 'B', 'P050371'),
(118, 'Artística 7B', '7', 'B', 'P050371'),
(119, 'Science 7B', '7', 'B', 'P050371'),
(120, 'Ética 7B', '7', 'B', 'P050371'),
(121, 'Matemáticas 8A', '8', 'A', 'P050371'),
(122, 'Español 8A', '8', 'A', 'P050371'),
(123, 'Inglés 8A', '8', 'A', 'P050371'),
(124, 'Sociales 8A', '8', 'A', 'P050371'),
(125, 'Educación Física 8A', '8', 'A', 'P050371'),
(126, 'Religión 8A', '8', 'A', 'P050371'),
(127, 'Artística 8A', '8', 'A', 'P050371'),
(128, 'Science 8A', '8', 'A', 'P050371'),
(129, 'Ética 8A', '8', 'A', 'P050371'),
(130, 'Matemáticas 8B', '8', 'B', 'P050371'),
(131, 'Español 8B', '8', 'B', 'P050371'),
(132, 'Inglés 8B', '8', 'B', 'P050371'),
(133, 'Sociales 8B', '8', 'B', 'P050371'),
(134, 'Educación Física 8B', '8', 'B', 'P050371'),
(135, 'Religión 8B', '8', 'B', 'P050371'),
(136, 'Artística 8B', '8', 'B', 'P050371'),
(137, 'Science 8B', '8', 'B', 'P050371'),
(138, 'Ética 8B', '8', 'B', 'P050371'),
(139, 'Matemáticas 9A', '9', 'A', 'P050371'),
(140, 'Español 9A', '9', 'A', 'P050371'),
(141, 'Inglés 9A', '9', 'A', 'P050371'),
(142, 'Sociales 9A', '9', 'A', 'P050371'),
(143, 'Educación Física 9A', '9', 'A', 'P050371'),
(144, 'Religión 9A', '9', 'A', 'P050371'),
(145, 'Artística 9A', '9', 'A', 'P050371'),
(146, 'Science 9A', '9', 'A', 'P050371'),
(147, 'Ética 9A', '9', 'A', 'P050371'),
(148, 'Matemáticas 9B', '9', 'B', 'P050371'),
(149, 'Español 9B', '9', 'B', 'P050371'),
(150, 'Inglés 9B', '9', 'B', 'P050371'),
(151, 'Sociales 9B', '9', 'B', 'P050371'),
(152, 'Educación Física 9B', '9', 'B', 'P050371'),
(153, 'Religión 9B', '9', 'B', 'P050371'),
(154, 'Artística 9B', '9', 'B', 'P050371'),
(155, 'Science 9B', '9', 'B', 'P050371'),
(156, 'Ética 9B', '9', 'B', 'P050371'),
(157, 'Matemáticas 10A', '10', 'A', 'P050371'),
(158, 'Español 10A', '10', 'A', 'P050371'),
(159, 'Inglés 10A', '10', 'A', 'P050371'),
(160, 'Sociales 10A', '10', 'A', 'P050371'),
(161, 'Educación Física 10A', '10', 'A', 'P050371'),
(162, 'Religión 10A', '10', 'A', 'P050371'),
(163, 'Ética 10A', '10', 'A', 'P050371'),
(164, 'Ciencias Políticas 10A', '10', 'A', 'P050371'),
(165, 'Física 10A', '10', 'A', 'P050371'),
(166, 'Química 10A', '10', 'A', 'P050371'),
(167, 'Filosofía 10A', '10', 'A', 'P050371'),
(168, 'Matemáticas 10B', '10', 'B', 'P050371'),
(169, 'Español 10B', '10', 'B', 'P050371'),
(170, 'Inglés 10B', '10', 'B', 'P050371'),
(171, 'Sociales 10B', '10', 'B', 'P050371'),
(172, 'Educación Física 10B', '10', 'B', 'P050371'),
(173, 'Religión 10B', '10', 'B', 'P050371'),
(174, 'Ética 10B', '10', 'B', 'P050371'),
(175, 'Ciencias Políticas 10B', '10', 'B', 'P050371'),
(176, 'Física 10B', '10', 'B', 'P050371'),
(177, 'Química 10B', '10', 'B', 'P050371'),
(178, 'Filosofía 10B', '10', 'B', 'P050371'),
(179, 'Matemáticas 11A', '11', 'A', 'P050371'),
(180, 'Español 11A', '11', 'A', 'P050371'),
(181, 'Inglés 11A', '11', 'A', 'P050371'),
(182, 'Sociales 11A', '11', 'A', 'P050371'),
(183, 'Educación Física 11A', '11', 'A', 'P050371'),
(184, 'Religión 11A', '11', 'A', 'P050371'),
(185, 'Ética 11A', '11', 'A', 'P050371'),
(186, 'Ciencias Políticas 11A', '11', 'A', 'P050371'),
(187, 'Física 11A', '11', 'A', 'P050371'),
(188, 'Química 11A', '11', 'A', 'P050371'),
(189, 'Filosofía 11A', '11', 'A', 'P050371'),
(190, 'Matemáticas 11B', '11', 'B', 'P050371'),
(191, 'Español 11B', '11', 'B', 'P050371'),
(192, 'Inglés 11B', '11', 'B', 'P050371'),
(193, 'Sociales 11B', '11', 'B', 'P050371'),
(194, 'Educación Física 11B', '11', 'B', 'P050371'),
(195, 'Religión 11B', '11', 'B', 'P050371'),
(196, 'Ética 11B', '11', 'B', 'P050371'),
(197, 'Ciencias Políticas 11B', '11', 'B', 'P050371'),
(198, 'Física 11B', '11', 'B', 'P050371'),
(199, 'Química 11B', '11', 'B', 'P050371'),
(200, 'Filosofía 11B', '11', 'B', 'P050371');

-- --------------------------------------------------------

--
-- Table structure for table `curso_estudiante`
--

CREATE TABLE `curso_estudiante` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('normal','importante','urgente') NOT NULL,
  `fecha` date NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eventos`
--

INSERT INTO `eventos` (`id`, `titulo`, `descripcion`, `prioridad`, `fecha`, `id_usuario`) VALUES
(45, 'Tarea de Mario', '', 'normal', '2025-09-19', 131),
(66, 'Valde', 'mi lov', 'urgente', '2025-09-24', 130),
(67, 'asdasd', '', 'importante', '2025-09-22', 130);

-- --------------------------------------------------------

--
-- Table structure for table `eventos_curso`
--

CREATE TABLE `eventos_curso` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `prioridad` enum('normal','importante','urgente') NOT NULL,
  `fecha` date DEFAULT NULL,
  `creado_por_profesor_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flashcards`
--

CREATE TABLE `flashcards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pregunta` varchar(100) NOT NULL,
  `respuesta` varchar(100) NOT NULL,
  `creada` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flashcards`
--

INSERT INTO `flashcards` (`id`, `user_id`, `pregunta`, `respuesta`, `creada`) VALUES
(6, 130, 'Who is prettiest', 'pauvalderrama', '2025-09-29 14:52:24'),
(7, 130, 'asd', 'asdsdasd', '2025-09-29 14:53:34'),
(8, 130, 'asds', 'sdsd', '2025-09-29 15:01:28');

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
-- Table structure for table `padre_hijo`
--

CREATE TABLE `padre_hijo` (
  `id` int(11) NOT NULL,
  `padre_id` int(11) NOT NULL,
  `hijo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` varchar(32) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profesores`
--

CREATE TABLE `profesores` (
  `id` varchar(7) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `materias` set('Matemáticas','Física','Química','Inglés','Español','Educación Física','Sociales','Filosofía','Religión','Ética','Ciencias Políticas','Artística','Science','Programación','Informática','Robótica') DEFAULT NULL,
  `grados` set('1','2','3','4','5','6','7','8','9','10','11') NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profesores`
--

INSERT INTO `profesores` (`id`, `nombre`, `apellido`, `email`, `password`, `materias`, `grados`, `fecha_registro`) VALUES
('P050371', 'Aura', 'Gonzales', 'aura@gmail.com', '$2y$10$6SRr.odNkEGYcH5uzne49.clox2kWS5MP4ub236C1iciJBUVwoju.', 'Sociales,Filosofía', '6,7,8,9,10,11', '2025-09-24 01:30:40');

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
(130, 'Minecraft', 'Arnoldo', 'betulia.farfan@estudiante.com', '$2y$10$wpK5.2ss6FqasrN5byLJYOQQ6vljGSivnfED.TsG03EuwtYqjy8Qm', '10', 'C', '2025-09-16 01:00:21', NULL),
(131, 'Mario', 'Cabeen', 'mario.cabeen@estudiante.com', '$2y$10$zFjspR.IxCZXBCWqa.ZpduaNMxfmwZ2tI2fUUclIZO2pWK0Z4b0m.', '1', 'C', '2025-09-18 22:01:07', NULL),
(133, 'Paulina', 'Valderrama', 'valderramapaulina221@gmail.com', '$2y$10$i.yZbhn6vyrQZOTAkRQPzuwTVEetYpzIX1v1vuzan2ieV8p51959S', '11', 'A', '2025-09-25 14:26:48', NULL),
(134, 'Esteban', 'Hermano', 'estebitan@gmail.com', '$2y$10$uXWEdJ3GocDnd0f2Viv2Fu/87Ah4pKUSLUQNvdxHTj9HUZZAEfHAm', '9', 'B', '2025-09-29 20:37:37', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acudientes`
--
ALTER TABLE `acudientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  ADD KEY `cursor_ibfk_1` (`profesor_id`);

--
-- Indexes for table `curso_estudiante`
--
ALTER TABLE `curso_estudiante`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `estudiante_id` (`estudiante_id`);

--
-- Indexes for table `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_eventos_users` (`id_usuario`);

--
-- Indexes for table `eventos_curso`
--
ALTER TABLE `eventos_curso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `creado_por_profesor_id` (`creado_por_profesor_id`);

--
-- Indexes for table `flashcards`
--
ALTER TABLE `flashcards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `profesor_id` (`profesor_id`);

--
-- Indexes for table `padre_hijo`
--
ALTER TABLE `padre_hijo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `padre_id` (`padre_id`),
  ADD KEY `hijo_id` (`hijo_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `acudientes`
--
ALTER TABLE `acudientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `curso_estudiante`
--
ALTER TABLE `curso_estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `eventos_curso`
--
ALTER TABLE `eventos_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flashcards`
--
ALTER TABLE `flashcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `padre_hijo`
--
ALTER TABLE `padre_hijo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursor_ibfk_1` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`);

--
-- Constraints for table `curso_estudiante`
--
ALTER TABLE `curso_estudiante`
  ADD CONSTRAINT `curso_estudiante_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curso_estudiante_ibfk_2` FOREIGN KEY (`estudiante_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_eventos_users` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `eventos_curso`
--
ALTER TABLE `eventos_curso`
  ADD CONSTRAINT `eventos_curso_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `flashcards`
--
ALTER TABLE `flashcards`
  ADD CONSTRAINT `flashcards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notas_ibfk_2` FOREIGN KEY (`estudiante_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `padre_hijo`
--
ALTER TABLE `padre_hijo`
  ADD CONSTRAINT `padre_hijo_ibfk_1` FOREIGN KEY (`padre_id`) REFERENCES `acudientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `padre_hijo_ibfk_2` FOREIGN KEY (`hijo_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_padres` FOREIGN KEY (`id_padre`) REFERENCES `acudientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
