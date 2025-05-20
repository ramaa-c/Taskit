-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-05-2025 a las 18:19:52
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `admin_tareas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subtarea`
--

DROP TABLE IF EXISTS `subtarea`;
CREATE TABLE IF NOT EXISTS `subtarea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tarea` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('en_proceso','completada') NOT NULL,
  `prioridad` enum('baja','normal','alta') DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `id_responsable` int(11) NOT NULL,
  `fecha_creacion` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id`),
  KEY `id_tarea` (`id_tarea`),
  KEY `id_responsable` (`id_responsable`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subtarea`
--

INSERT INTO `subtarea` (`id`, `id_tarea`, `descripcion`, `estado`, `prioridad`, `fecha_vencimiento`, `comentario`, `id_responsable`, `fecha_creacion`) VALUES
(24, 21, 'Composición', '', 'normal', '2025-06-08', 'ayudar a componer', 6, '2025-05-20'),
(25, 21, 'Composición', '', 'normal', '2025-06-08', 'ayudar a componer', 5, '2025-05-20'),
(26, 22, 'Preparativos', 'completada', 'alta', '2025-05-30', 'realizar preparativos para el show', 6, '2025-05-20'),
(27, 24, 'Practica', '', 'normal', '2025-05-23', 'practica de marca y ataque', 5, '2025-05-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea`
--

DROP TABLE IF EXISTS `tarea`;
CREATE TABLE IF NOT EXISTS `tarea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `asunto` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('baja','normal','alta') NOT NULL,
  `estado` enum('en_proceso','completada') NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fecha_recordatorio` date DEFAULT NULL,
  `archivada` tinyint(1) DEFAULT 0,
  `fecha_creacion` date NOT NULL DEFAULT curdate(),
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarea`
--

INSERT INTO `tarea` (`id`, `id_usuario`, `asunto`, `descripcion`, `prioridad`, `estado`, `fecha_vencimiento`, `fecha_recordatorio`, `archivada`, `fecha_creacion`) VALUES
(21, 4, 'Componer', 'pensar letras y melodias', 'normal', 'en_proceso', '2025-05-31', '2025-05-26', 0, '2025-05-20'),
(22, 4, 'Cantar', 'cantar en un teatro', 'alta', 'completada', '2025-06-08', '2025-06-05', 0, '2025-05-20'),
(23, 4, 'Organizar gira', 'organizar la gira por el pais', 'baja', 'en_proceso', '2025-08-02', '0000-00-00', 0, '2025-05-20'),
(24, 6, 'Planificar', 'planificar los próximos encuentros', 'alta', 'en_proceso', '2025-05-24', '0000-00-00', 0, '2025-05-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `email`, `usuario`, `clave`) VALUES
(4, 'Andres Calamaro', 'calamaro79@gmail.com', 'calamaro', '$2y$10$dIPut2id0pvhY78K4yy4o.A6xK5hZ4gNhHFMZCDYQlM1K.ughLVK.'),
(5, 'Malcom Braida', 'malcomBR@gmail.com', 'braida', '$2y$10$qOnEubFfWT7HC1EMWp67QOE07OC8nIsZxkr7CTBfANeYIQDdJ7v5C'),
(6, 'Miguel Ángel Russo', 'miguelo7@gmail.com', 'russo', '$2y$10$I.YuH.65RxrupwliAK.RUuEqoqgrX61mUF3yB8CYau/GC7KRXaM2K');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `subtarea`
--
ALTER TABLE `subtarea`
  ADD CONSTRAINT `subtarea_ibfk_1` FOREIGN KEY (`id_tarea`) REFERENCES `tarea` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subtarea_ibfk_2` FOREIGN KEY (`id_responsable`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD CONSTRAINT `tarea_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
