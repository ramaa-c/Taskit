-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-05-2025 a las 02:40:44
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

CREATE TABLE `subtarea` (
  `id` int(11) NOT NULL,
  `id_tarea` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('definido','en_proceso','completada') NOT NULL,
  `prioridad` enum('baja','normal','alta') DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `id_responsable` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subtarea`
--

INSERT INTO `subtarea` (`id`, `id_tarea`, `descripcion`, `estado`, `prioridad`, `fecha_vencimiento`, `comentario`, `id_responsable`, `fecha_creacion`) VALUES
(2, 2, 'corregir errores', 'en_proceso', NULL, '2025-05-24', 'jijoooooo', 1, '2025-05-05 13:43:10'),
(8, 2, 'borrar duplicados', 'definido', 'alta', '2025-05-06', NULL, 1, '2025-05-05 13:43:10'),
(9, 2, 'cambiar clase', 'definido', 'baja', '2025-05-15', NULL, 1, '2025-05-05 13:43:10'),
(10, 3, 'usar jabon', 'definido', 'normal', '2025-06-07', NULL, 1, '2025-05-05 13:43:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea`
--

CREATE TABLE `tarea` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `asunto` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('baja','normal','alta') NOT NULL,
  `estado` enum('definido','en_proceso','completada') NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fecha_recordatorio` date DEFAULT NULL,
  `archivada` tinyint(1) DEFAULT 0,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarea`
--

INSERT INTO `tarea` (`id`, `id_usuario`, `asunto`, `descripcion`, `prioridad`, `estado`, `fecha_vencimiento`, `fecha_recordatorio`, `archivada`, `fecha_creacion`) VALUES
(2, 1, 'Viaje', 'organizar mi viaje de negocios', 'alta', 'en_proceso', '2025-06-06', '2025-06-05', 0, '2025-05-05 13:42:55'),
(3, 3, 'Bañarse', 'pegarse un bañito', 'alta', 'en_proceso', '2025-05-17', '2025-05-15', 0, '2025-05-05 13:42:55'),
(4, 2, 'Debut', 'debutar en la primera de lanus', 'alta', 'en_proceso', '2025-07-06', '0000-00-00', 0, '2025-05-05 13:42:55'),
(5, 3, 'Salir', 'ir al patio a tomar sol', 'normal', 'definido', '2025-05-14', '2025-05-07', 0, '2025-05-05 13:57:54'),
(7, 3, 'prueba', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,', 'baja', 'definido', '2025-05-13', '2025-05-08', 0, '2025-05-05 14:18:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `email`, `usuario`, `clave`) VALUES
(1, 'Ramiro Caceres', 'raramiro.240@gmail.com', 'ramiro777', '$2y$10$pcLjIvL9dCvNc4qi4694t.gKM2srAMZvQCe1VKh1aXCxQGcWB9.7e'),
(2, 'Pepo De La Vega', 'pepito.feta@gmail.com', 'pepoo8', '$2y$10$PG7UTOzj.kdXfBwf38wd2uHhQZHbfejvBq5MKKTX8erhw03kGFrZy'),
(3, 'Panchito Gato', 'pancho666@gmail.com', 'pancho777', '$2y$10$aH7HDQXhoN1uRtg/hSWjh.dHyszpiuKYzbFcp60Aw.bmOwJorUR56');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `subtarea`
--
ALTER TABLE `subtarea`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tarea` (`id_tarea`),
  ADD KEY `id_responsable` (`id_responsable`);

--
-- Indices de la tabla `tarea`
--
ALTER TABLE `tarea`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `subtarea`
--
ALTER TABLE `subtarea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tarea`
--
ALTER TABLE `tarea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
