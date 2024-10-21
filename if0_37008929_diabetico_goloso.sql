-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql311.byetcluster.com
-- Tiempo de generación: 16-10-2024 a las 10:35:35
-- Versión del servidor: 10.6.19-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_37008929_diabetico_goloso`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes`
--

CREATE TABLE `ingredientes` (
  `Codigo` int(5) NOT NULL,
  `Ingredientes` varchar(100) NOT NULL,
  `Medidas` varchar(100) NOT NULL,
  `Precios` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredientes`
--

INSERT INTO `ingredientes` (`Codigo`, `Ingredientes`, `Medidas`, `Precios`) VALUES
(2, 'Aceite', '3L', '28.000'),
(3, 'Huevos A', 'Cubeta completa', '14.000'),
(4, 'Polvo de hornear', '20g', '2.000'),
(5, 'Agua o jugo', '', ''),
(7, 'Escencias de sabores(pequenas)', '130ml', '6.000'),
(8, 'Colorantes(liquidos)', '7ml', '3.000'),
(9, 'Colorantes(gel)', '60ml', '16.500'),
(10, 'Jalea frutos rojos', '220g', '5.000'),
(11, 'Jalea frutos amarillos', '220g', '5.000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medidas`
--

CREATE TABLE `medidas` (
  `Codigo` int(5) NOT NULL,
  `Porciones` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `Ingredientes` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL,
  `Cantidades` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medidas`
--

INSERT INTO `medidas` (`Codigo`, `Porciones`, `Ingredientes`, `Cantidades`) VALUES
(1, '5', 'Harina\n Huevos A\n Aceite\n Polvo de hornear\n Agua o jugo', '250gr\n75gr\n75gr\n4gr\n62.5gr'),
(2, '10', 'Harina\nHuevos A\nAceite\nPolvo de hornear\nAgua o jugo', '500gr\n150gr\n150gr\n4gr\n125gr'),
(3, '15', 'Harina\nHuevos A\nAceite\nPolvo de hornear\nAgua o jugo', '750gr\n225gr\n225gr\n4gr\n187.5gr'),
(4, '20', 'Harina\nHuevos A\nAceite\nPolvo de hornear\nAgua o jugo', '1000gr\n300gr\n300gr\n4gr\n250gr');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `imagen_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `precio_5` int(10) NOT NULL,
  `precio_10` int(10) NOT NULL,
  `precio_15` int(10) NOT NULL,
  `precio_20` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `imagen_url`, `precio_5`, `precio_10`, `precio_15`, `precio_20`) VALUES
(2, 'Torta de chocolate', 'Esta torta de chocolate es una nube de suavidad, esponjosa que se derrite en la boca. Su sabor intenso a chocolate la convierte en el postre perfecto para cualquier ocasion.', 'img/torta17.jpg', 48000, 78000, 110000, 145000),
(4, 'Maracuya con amapola', 'Una torta esponjosa con el aroma exotico del maracuya y la textura de las semillas de amapola. Perfecta para cualquier ocasion especial.', 'img/torta9.jpg', 48000, 78000, 110000, 145000),
(5, 'Naranja con amapola', 'Una torta esponjosa con el aroma citrico de la naranja y la textura de las semillas de amapola. Perfecta para cualquier ocasion.', 'img/torta15.jpg', 48000, 78000, 110000, 145000),
(6, 'Zanahoria con arandanos', 'Nuestra torta de zanahoria con arandanos es una combinacion perfecta de lo dulce y lo saludable. La humedad de la zanahoria y la acidez de los arandanos crean una explosion de sabor en cada bocado.', 'img/torta14.jpg', 48000, 78000, 110000, 145000),
(7, 'Fruto rojos', 'Nuestra torta de frutos rojos es una explosion de sabores y colores, con fresas jugosas, frambuesas dulces y arandanos aciditos que crean una sinfonia en tu paladar.', 'img/torta12.png', 48000, 78000, 110000, 145000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sabores_crema`
--

CREATE TABLE `sabores_crema` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `sabores_crema`
--

INSERT INTO `sabores_crema` (`id`, `nombre`) VALUES
(1, 'Cafe'),
(2, 'Maracuya'),
(3, 'Vainilla'),
(4, 'Chocolate'),
(5, 'Frutos rojos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sabores_relleno`
--

CREATE TABLE `sabores_relleno` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `sabores_relleno`
--

INSERT INTO `sabores_relleno` (`id`, `nombre`) VALUES
(1, 'Arequipe'),
(2, 'Arequipe y mani'),
(3, 'Jalea de frutos rojos'),
(4, 'Jalea de frutos amarillos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sabores_tortas`
--

CREATE TABLE `sabores_tortas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `sabores_tortas`
--

INSERT INTO `sabores_tortas` (`id`, `nombre`) VALUES
(1, 'Maracuya con amapola'),
(2, 'Naranja con amapola'),
(3, 'Chocolate'),
(4, 'Frutos rojos'),
(5, 'Zanahoria con arandanos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sedes`
--

CREATE TABLE `sedes` (
  `id` int(11) NOT NULL,
  `sede` varchar(50) NOT NULL,
  `numero` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sedes`
--

INSERT INTO `sedes` (`id`, `sede`, `numero`) VALUES
(1, 'Sede_Bogota', '3107623278'),
(2, 'Sede_Villavicencio', '3178531932');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tortas`
--

CREATE TABLE `tortas` (
  `Codigo` int(5) NOT NULL,
  `Porciones` varchar(5) NOT NULL,
  `Sabores_tortas` varchar(100) NOT NULL,
  `Sabores_relleno` varchar(100) NOT NULL,
  `Sabores_crema` varchar(100) NOT NULL,
  `Precios` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tortas`
--

INSERT INTO `tortas` (`Codigo`, `Porciones`, `Sabores_tortas`, `Sabores_relleno`, `Sabores_crema`, `Precios`) VALUES
(1, '5', 'Naranja con amapolaChocolateFrutos rojosZanahoria con arandanos', 'ArequipeArequipe y maniJalea frutos rojosJalea frutos amarillo', 'Cafe', '48.000'),
(2, '10', 'Maracuya con amapola\r\nNaranja con amapola\r\nChocolate\r\nFrutos rojos\r\nZanahoria con arandanos', 'Arequipe\r\nArequipe y mani\r\nJalea frutos rojos\r\nJalea frutos amarillos', 'Cafe\r\nMaracuya\r\nVainilla\r\nFrutos rojos\r\nChocolate', '78.000'),
(3, '15', 'Maracuya con amapola\r\nNaranja con amapola\r\nChocolate\r\nFrutos rojos\r\nZanahoria con arandanos', 'Arequipe\r\nArequipe y mani\r\n Jalea frutos rojos\r\nJalea frutos amarillos', 'Cafe\r\nMaracuya\r\nVainilla\r\nFrutos rojos\r\nChocolate', '110.000'),
(4, '20', 'Maracuya con amapola\r\nNaranja con amapola\r\nChocolate\r\nFrutos rojos\r\nZanahoria con arandanos', 'Arequipe\r\nArequipe y mani\r\n Jalea frutos rojos\r\nJalea frutos amarillos', 'Cafe\r\nMaracuya\r\nVainilla\r\nFrutos rojos\r\nChocolate', '145.000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_db`
--

CREATE TABLE `usuarios_db` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `token` varchar(6) NOT NULL,
  `activo` int(10) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_db`
--

INSERT INTO `usuarios_db` (`id`, `email`, `password`, `nombre`, `token`, `activo`, `rol`, `foto`) VALUES
(24, 'danielat5586@gmail.com', '$2y$10$/Up/Ve9wBxLc7OEcH6xMBe.MK6gNPAslPqTnJr2ayoQSBO1Ih3Z8C', 'Karen Daniela Torres', 'da18cd', 1, '1', 'img/perfil/horario escolar kawaii infantil rosa pastel.png'),
(25, 'vanessacdiabeticog@gmail.com', '$2y$10$lVX9NgJWCCSPqyFVJzI/lOX8nTKkavYk9ngkZtrKNSbPA8ikeGrye', 'Vanessa Cendales', '16278f', 1, '2', ''),
(26, 'panchecendalessofia@gmail.com', '$2y$10$VBUmrF.9EoVS91f9ee9/MO0tO5xvzVAV6RR0uwqZps2D034b/hVni', 'Sofia Panche', 'eb6710', 1, '1', 'img/perfil/e43c8059-e321-49b5-ad86-ddc011dc8847.jpg'),
(27, 'anabespinozas@juandelcorral.edu.co', '$2y$10$NmXzuByQCBZZq2fzPJG/9OicNxBcOvnwpFXBCvzaruFlJnGoGVl5i', 'ana', 'e58500', 0, '1', ''),
(32, 'alejandrapcendales@gmail.com', '$2y$10$DxbZYc72mCigN1WQSF038OY7yLecdJePrbfQKy5lO/Y0CjGwKX0A.', 'Alejandra panche Cen', 'd63db2', 1, '1', ''),
(33, 'anae23449@gmail.com', '$2y$10$IxXjtH3OLMY34hsVt.sGru2DHoE.GCztkX0ghLkJYz8R.40mMRFBS', 'ana', 'a1b6cc', 1, '1', 'img/perfil/ana.jpg'),
(35, 'sofiapanchec@juandelcorral.edu.co', '$2y$10$s6xf8oN4j2yxiQmWJ2KWJuYA7fFEvzA4J8m8pDsQBx3oveyUHwaFm', 'sofia panche', 'e24475', 1, '1', ''),
(36, 'sergio.forero@gmail.com', '$2y$10$2bfDT0fUWXeeQ6bifi4NeeLzYbnazSnOCiWfG4tBH7TMtCZcwcwQK', 'Sergio', 'e943f3', 1, '2', 'img/perfil/g4wn_ie3h_140917.jpg'),
(40, 'karendtorresh@juandelcorral.edu.co', '$2y$10$A.BIVdzz9eu5m5AMtdCzLOMGzwEYo4vc8LlhxtsXVEt5izfM.cf1S', 'Karen Daniela', '0', 1, '2', 'img/perfil/GrÃ¡fica Cuadro Comparativo Ilustrativo Amarillo.png'),
(44, 'metenesmamaasapa@gmail.com', '$2y$10$numol5vG.v1cB3/VhIVUD.Q/fkJL/1aEtmGo5oHsTVy/Nnj5HSqY2', 'olaa', '3', 1, '1', ''),
(45, 'sandragiraldo3023@gmail.com', '$2y$10$Qcpw4HZH/LkwtrPJvQePcOZv8punaW8rBhlkJf54VNYBzmpXsK62m', 'Sandra', '0', 1, '1', ''),
(46, 'johansrodriguezc@juandelcorral.edu.co', '$2y$10$6WwzO9KeHnDrrQp6Vutuau/AN5OIqdnyonvMu2j.4QSpU0ZY8d5aC', 'Larry Cañonga', '864b17', 0, '1', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `medidas`
--
ALTER TABLE `medidas`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sabores_crema`
--
ALTER TABLE `sabores_crema`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sabores_relleno`
--
ALTER TABLE `sabores_relleno`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sabores_tortas`
--
ALTER TABLE `sabores_tortas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sedes`
--
ALTER TABLE `sedes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tortas`
--
ALTER TABLE `tortas`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `usuarios_db`
--
ALTER TABLE `usuarios_db`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `sabores_crema`
--
ALTER TABLE `sabores_crema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sabores_relleno`
--
ALTER TABLE `sabores_relleno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sabores_tortas`
--
ALTER TABLE `sabores_tortas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sedes`
--
ALTER TABLE `sedes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios_db`
--
ALTER TABLE `usuarios_db`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
