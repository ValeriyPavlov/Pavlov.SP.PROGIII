-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-06-2023 a las 20:31:42
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `segundo_parcial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cripto_monedas`
--

CREATE TABLE `cripto_monedas` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `precio` int(11) NOT NULL,
  `nacionalidad` text NOT NULL,
  `foto` text NOT NULL,
  `baja` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cripto_monedas`
--

INSERT INTO `cripto_monedas` (`id`, `nombre`, `precio`, `nacionalidad`, `foto`, `baja`) VALUES
(1, 'ArgenCoin', 2000, 'Argentina', '.\\img\\\\fotoCripto-ArgenCoin.jpg', 0),
(2, 'BrazilCoin', 2500, 'Brazileña', '.\\img\\\\fotoCripto-ArgenCoin.jpg', 0),
(3, 'PeruCoin', 1500, 'Peruana', '.\\img\\\\fotoCripto-PeruCoin.jpg', 1),
(4, 'AlemanCoin', 3333, 'Alemana', '.\\img\\\\fotoCripto-AlemanCoin.jpg', 0),
(5, 'GermanCoin', 5000, 'Alemana', '.\\img\\\\fotoCripto-GermanCoin.jpg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `mail` text NOT NULL,
  `tipo` text NOT NULL,
  `fechaBaja` date NOT NULL,
  `clave` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `mail`, `tipo`, `fechaBaja`, `clave`) VALUES
(1, 'admin@admin.com', 'admin', '0000-00-00', '21232f297a57a5a743894a0e4a801fc3'),
(2, 'cliente@cliente.com', 'cliente', '0000-00-00', '4983a0ab83ed86e0e7213c8783940193'),
(3, 'pepe@cliente.com', 'cliente', '2023-06-18', '4983a0ab83ed86e0e7213c8783940193'),
(4, 'test@test.com', 'cliente', '0000-00-00', '4983a0ab83ed86e0e7213c8783940193');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idCripto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fechaCompra` date NOT NULL,
  `fechaBaja` date NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `idUsuario`, `idCripto`, `cantidad`, `fechaCompra`, `fechaBaja`, `foto`) VALUES
(1, 2, 1, 2, '2023-06-18', '0000-00-00', '.\\FotosCripto2023\\\\ArgenCoin-cliente-2023-06-18.jpg'),
(3, 1, 4, 1, '2023-07-11', '0000-00-00', '.\\FotosCripto2023\\\\AlemanCoin-admin-2023-06-18.jpg'),
(4, 1, 5, 3, '2023-06-18', '0000-00-00', '.\\FotosCripto2023\\\\GermanCoin-admin-2023-06-18.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cripto_monedas`
--
ALTER TABLE `cripto_monedas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cripto_monedas`
--
ALTER TABLE `cripto_monedas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
