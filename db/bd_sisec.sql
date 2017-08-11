-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 24-04-2016 a las 22:28:58
-- Versión del servidor: 1.0.24
-- Versión de PHP: 5.5.33-1~dotdeb+7.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `bd_sisec`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciclo_evaluacion`
--

CREATE TABLE IF NOT EXISTS `ciclo_evaluacion` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla',
  `fecha_creacion` date NOT NULL COMMENT 'Fecha de la creación de cada registro',
  `hora_creacion` time NOT NULL COMMENT 'Hora de la creación de cada registro',
  `fecha_cierre` date NOT NULL COMMENT 'Fecha de hora del usuario',
  `hora_cierre` time NOT NULL COMMENT 'Hora de cierre del usuario',
  `descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Breve descripción del nuevo cico de evaluación',
  `estatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Indica si el ciclo está abierto "1" o cerrado "0"',
  `id_usuario` int(3) NOT NULL COMMENT 'Llave foranea de la tabla usuario',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Son los nuevos ciclos de evaluación que se crean en el sistema' AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `ciclo_evaluacion`
--

INSERT INTO `ciclo_evaluacion` (`id`, `fecha_creacion`, `hora_creacion`, `fecha_cierre`, `hora_cierre`, `descripcion`, `estatus`, `id_usuario`) VALUES
(1, '2016-04-03', '15:41:36', '2016-04-03', '16:38:15', 'Ciclo de evaluación de prueba para evaluar el sistema SIRAH', 0, 1),
(2, '2016-04-22', '03:28:22', '0000-00-00', '00:00:00', 'Ciclo de Prueba', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE IF NOT EXISTS `historial` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `accion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `ip_cliente` varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'IP de donde se conecta el usuario',
  `navegador` varchar(150) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Navegador que utiliza para conectar al servdiro',
  `so` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Sistema Operativo donde inicia sesión',
  `isp` varchar(200) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Proveedor de servicio de la conexión',
  `id_usuario` int(3) NOT NULL,
  PRIMARY KEY (`id`) COMMENT 'Código de la tabla',
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Historial de acciones que hacen los usuarios en el software' AUTO_INCREMENT=333 ;

--
-- Volcado de datos para la tabla `historial`
--

INSERT INTO `historial` (`id`, `accion`, `fecha`, `hora`, `ip_cliente`, `navegador`, `so`, `isp`, `id_usuario`) VALUES
(191, 'Inició Sesión', '2016-04-03', '05:27:40', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(192, 'Generó un diccionario de usuarios para la prueba de sesión (100 usuarios)', '2016-04-03', '07:07:21', '::1', 'Firefox 45.0', 'Linux', '', 1),
(193, 'Realizó una prueba de carga de sesiones', '2016-04-03', '07:07:30', '::1', 'Firefox 45.0', 'Linux', '', 1),
(194, 'Inició Sesión', '2016-04-03', '09:14:11', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(195, 'Generó un diccionario de usuarios para la prueba de sesión (100 usuarios)', '2016-04-03', '09:15:19', '::1', 'Firefox 45.0', 'Linux', '', 1),
(196, 'Realizó una prueba de carga de sesiones', '2016-04-03', '09:15:28', '::1', 'Firefox 45.0', 'Linux', '', 1),
(197, 'Generó un diccionario de usuarios para la prueba de sesión (100 usuarios)', '2016-04-03', '09:22:09', '::1', 'Firefox 45.0', 'Linux', '', 1),
(198, 'Realizó una prueba de carga de sesiones', '2016-04-03', '09:22:18', '::1', 'Firefox 45.0', 'Linux', '', 1),
(199, 'Inició Sesión', '2016-04-03', '09:27:31', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(200, 'Generó un diccionario de usuarios para la prueba de sesión (100 usuarios)', '2016-04-03', '10:06:52', '::1', 'Firefox 45.0', 'Linux', '', 1),
(201, 'Generó un diccionario de usuarios para la prueba de sesión (100 usuarios)', '2016-04-03', '10:14:04', '::1', 'Firefox 45.0', 'Linux', '', 1),
(202, 'Cerró Sesión', '2016-04-03', '11:01:36', '::1', 'Firefox 45.0', 'Linux', '', 1),
(203, 'Inició Sesión', '2016-04-03', '11:03:11', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(204, 'Inició Sesión', '2016-04-03', '15:18:22', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(205, 'Creó Nuevo Ciclo de Evaluación', '2016-04-03', '15:41:36', '::1', 'Firefox 45.0', 'Linux', '', 1),
(206, 'Generó un diccionario de usuarios para la prueba de sesión (100 usuarios)', '2016-04-03', '15:58:16', '::1', 'Firefox 45.0', 'Linux', '', 1),
(207, 'Realizó una prueba de carga de sesiones', '2016-04-03', '15:58:25', '::1', 'Firefox 45.0', 'Linux', '', 1),
(208, 'Generó un diccionario de usuarios para la prueba de sesión (100 usuarios)', '2016-04-03', '16:05:22', '::1', 'Firefox 45.0', 'Linux', '', 1),
(209, 'Realizó una prueba de carga de sesiones', '2016-04-03', '16:05:30', '::1', 'Firefox 45.0', 'Linux', '', 1),
(210, 'Creó Nuevo Ciclo de Evaluación', '2016-04-03', '16:42:57', '::1', 'Firefox 45.0', 'Linux', '', 1),
(211, 'Inició Sesión', '2016-04-04', '06:30:24', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(212, 'Cerró Sesión', '2016-04-04', '07:02:49', '::1', 'Firefox 45.0', 'Linux', '', 1),
(213, 'Inició Sesión', '2016-04-04', '07:04:54', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(214, 'Cerró Sesión', '2016-04-04', '07:05:52', '::1', 'Firefox 45.0', 'Linux', '', 1),
(215, 'Inició Sesión', '2016-04-04', '07:33:01', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(216, 'Cerró Sesión', '2016-04-04', '07:34:35', '::1', 'Firefox 45.0', 'Linux', '', 1),
(217, 'Inició Sesión', '2016-04-04', '07:51:49', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(218, 'Cerró Sesión', '2016-04-04', '07:51:56', '::1', 'Firefox 45.0', 'Linux', '', 1),
(219, 'Recuperó contraseña mediante respuesta secreta', '2016-04-04', '07:53:26', '', '', '', '', 1),
(220, 'Recuperó contraseña mediante respuesta secreta', '2016-04-04', '07:55:53', '', '', '', '', 1),
(324, 'Cerró Sesión', '2016-04-22', '03:25:20', '201.211.154.97', 'Firefox 45.0', 'Linux', '', 2),
(325, 'Inició Sesión', '2016-04-22', '03:25:31', '201.211.154.97', 'Firefox 45.0', 'Linux', 'dc9d39a61.dslam-172-17-96-251-682-0473-anz-07.dsl.cantv.net', 1),
(326, 'Creó Nuevo Ciclo de Evaluación', '2016-04-22', '03:28:23', '201.211.154.97', 'Firefox 45.0', 'Linux', '', 1),
(327, 'Inició Sesión', '2016-04-22', '14:54:19', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(328, 'Inició Sesión', '2016-04-22', '14:55:39', '201.211.154.97', 'Firefox 45.0', 'Linux', 'dc9d39a61.dslam-172-17-96-251-682-0473-anz-07.dsl.cantv.net', 1),
(329, 'Inició Sesión', '2016-04-24', '04:09:17', '::1', 'Firefox 45.0', 'Linux', 'localhost', 1),
(330, 'Inició Sesión', '2016-04-24', '17:22:51', '201.211.154.97', 'Firefox 45.0', 'Linux', 'dc9d39a61.dslam-172-17-96-251-682-0473-anz-07.dsl.cantv.net', 1),
(331, 'Cerró Sesión', '2016-04-24', '17:22:58', '201.211.154.97', 'Firefox 45.0', 'Linux', '', 1),
(332, 'Inició Sesión', '2016-04-24', '17:23:03', '201.211.154.97', 'Firefox 45.0', 'Linux', 'dc9d39a61.dslam-172-17-96-251-682-0473-anz-07.dsl.cantv.net', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prueba`
--

CREATE TABLE IF NOT EXISTS `prueba` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `tipo` enum('Fuerza Bruta','Inyección SQL','Inyección XSS','Carga de Datos','Carga de Sesiones') COLLATE utf8_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `ruta_de_archivo` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'El nombre de la ruta donde se alamacena el .log con los detalles de la prueba',
  `id_usuario` int(3) NOT NULL,
  `id_ciclo_evaluacion` int(5) NOT NULL COMMENT 'Llave foranea de la tabla diclo de evaluación',
  PRIMARY KEY (`id`) COMMENT 'Campo clave de la tabla',
  KEY `id_usuario` (`id_usuario`),
  KEY `id_ciclo_evaluacion` (`id_ciclo_evaluacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla que registra la apertura de un nuevo ciclo de pruebas' AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `prueba`
--

INSERT INTO `prueba` (`id`, `tipo`, `fecha`, `hora`, `ruta_de_archivo`, `id_usuario`, `id_ciclo_evaluacion`) VALUES
(1, 'Carga de Sesiones', '2016-04-03', '16:05:30', 'http://localhost/sisec/files/tests-results/login-load/prueba_de_sesiones03-04-2016_16-05-30_prueba-de-carga.log', 1, 1),
(2, 'Carga de Datos', '2016-04-03', '16:13:22', 'http://localhost/sisec/files/tests-results/data-load/03-04-2016_16-13-22_prueba-de-carga.log', 1, 1),
(3, 'Inyección SQL', '2016-04-03', '16:22:35', 'http://localhost/sisec/files/tests-results/sql/prueba-sql_fecha_03-04-2016_hora_16-22-35.log', 1, 1),
(4, 'Inyección XSS', '2016-04-03', '16:24:47', 'http://localhost/sisec/files/tests-results/xss/prueba-xss_fecha_03-04-2016_hora_16-24-47.log', 1, 1),
(5, 'Fuerza Bruta', '2016-04-03', '16:29:13', 'http://localhost/sisec/files/tests-results/brute-force/prueba-fuerza-bruta_fecha_03-04-2016_hora_16-29-13.log', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultado_carga_de_datos`
--

CREATE TABLE IF NOT EXISTS `resultado_carga_de_datos` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Campo clave de la tabla',
  `cargas_totales` int(6) NOT NULL COMMENT 'Cantidad de cargas realizados',
  `cargas_exitosas` int(6) NOT NULL COMMENT 'N° de cargas exitosos',
  `cargas_fallidas` int(6) NOT NULL COMMENT 'N° de cargas fallidas',
  `tiempo_de_ejecucion` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tiempo de ejecución de la prueba en texto',
  `tiempo_optimo` float NOT NULL COMMENT 'Tiempo óptimo o mejor tiempo esperado para la prueba',
  `tiempo_intermedio` float NOT NULL COMMENT 'Tiempo intermedio o tiempo normal esperado para la prueba',
  `tiempo_minimo` float NOT NULL COMMENT 'Tiempo mínimo o el tiempo aceptable más bajo esperado para la prueba',
  `tiempo_de_la_prueba` float NOT NULL COMMENT 'Tiempo de ejecucución de la prueba en segundos',
  `resultado` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'El restulado en texto de la prueba',
  `ruta_de_archivo` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'El nombre de la ruta donde se alamacena el .log con los detalles de la prueba',
  `id_prueba` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_prueba` (`id_prueba`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenar los resultados de la prueba de rendimiento' AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `resultado_carga_de_datos`
--

INSERT INTO `resultado_carga_de_datos` (`id`, `cargas_totales`, `cargas_exitosas`, `cargas_fallidas`, `tiempo_de_ejecucion`, `tiempo_optimo`, `tiempo_intermedio`, `tiempo_minimo`, `tiempo_de_la_prueba`, `resultado`, `ruta_de_archivo`, `id_prueba`) VALUES
(1, 1, 1, 0, '0.218 segundos', 0, 0, 0, 0.218, 'Rendimiento Intermedio.<br><strong>¡Muy bien!</strong>, de acuerdo a los tiempos definidos, el software tiene un rendimiento en carga de datos bueno,  es decir, no es de tan alto, pero tampoco bajo y se considera en un rendimiento normal y bastante aceptable.', 'http://localhost/sisec/files/tests-results/data-load/03-04-2016_15-52-43_prueba-de-carga.log', 4),
(2, 100, 100, 0, '6.9134 segundos', 0.1, 0.2, 0.25, 6.9134, '"Rendimiento Óptimo".<br><strong>¡Enhorabuena!</strong>, el software ha alcanzado y/o sobrepasado los tiempos de medición óptimos por lo tanto puede considerarse como un software de alto rendimiento en carga de datos.', 'http://localhost/sisec/files/tests-results/data-load/03-04-2016_16-13-22_prueba-de-carga.log', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultado_carga_de_sesiones`
--

CREATE TABLE IF NOT EXISTS `resultado_carga_de_sesiones` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Campo clave de la tabla',
  `sesiones_totales` int(6) NOT NULL COMMENT 'Cantidad de sesiones realizados',
  `sesiones_exitosas` int(6) NOT NULL COMMENT 'N° de sesiones exitosas',
  `sesiones_fallidas` int(6) NOT NULL COMMENT 'N° de sesiones fallidas',
  `tiempo_de_ejecucion` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tiempo de ejecución de la prueba en texto',
  `resultado` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'El restulado en texto de la prueba',
  `ruta_de_archivo` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'El nombre de la ruta donde se alamacena el .log con los detalles de la prueba',
  `id_prueba` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_prueba` (`id_prueba`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenar los resultados de la prueba de rendimiento' AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `resultado_carga_de_sesiones`
--

INSERT INTO `resultado_carga_de_sesiones` (`id`, `sesiones_totales`, `sesiones_exitosas`, `sesiones_fallidas`, `tiempo_de_ejecucion`, `resultado`, `ruta_de_archivo`, `id_prueba`) VALUES
(4, 100, 98, 2, '8.721 segundos', 'Todos los usuarios iniciaron una sesión correctamente.<br><b>Resumen Final</b>: El sistema es capaz de soportar la carga simultánea de 100 sesiones.\r\n', 'http://localhost/sisec/files/tests-results/login-load/prueba_de_sesiones03-04-2016_16-05-30_prueba-de-carga.log', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultado_seguridad`
--

CREATE TABLE IF NOT EXISTS `resultado_seguridad` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Campo clave de la tabla',
  `intentos_totales` int(6) NOT NULL COMMENT 'Cantidad de intentos realizados',
  `intentos_exitosos` int(5) NOT NULL COMMENT 'N° de intentos exitosos',
  `intentos_fallidos` int(5) NOT NULL COMMENT 'N° de intentos fallidos',
  `tiempo_de_ejecucion` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tiempo de ejecución de la prueba',
  `resultado` tinyint(1) NOT NULL COMMENT 'Resultado en booleano para comprobar el exito o gracaso de la prueba',
  `id_prueba` int(5) NOT NULL COMMENT 'Llave foaranea de la tabla prueba',
  PRIMARY KEY (`id`),
  KEY `id_prueba` (`id_prueba`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `resultado_seguridad`
--

INSERT INTO `resultado_seguridad` (`id`, `intentos_totales`, `intentos_exitosos`, `intentos_fallidos`, `tiempo_de_ejecucion`, `resultado`, `id_prueba`) VALUES
(7, 38, 9, 29, '2.3598 segundos', 0, 3),
(8, 2, 2, 0, '0.4609 segundos', 0, 4),
(10, 15, 1, 14, '0.3917 segundos', 0, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(80) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre completo del usuario',
  `nombre` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del usuario para ingresar al sistema',
  `contrasenia` varchar(42) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Contraseña de acceso del usuario',
  `email` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Correo electrónico del usuario',
  `telefono` varchar(12) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Teléfono de contacto del usuario',
  `nivel_usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Este campo indica el nivel o tipo de usuario del sistema',
  `pregunta_secreta` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Pregunta de seguridad para recuperar contraseña',
  `respuesta_secreta` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Respuesta de seguridad para recuperar contraseña',
  `fecha_creacion` date NOT NULL COMMENT 'Fecha de creación del usuario',
  `hora_creacion` time NOT NULL COMMENT 'Hora de creación del usuario',
  `eliminado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Campo que indica si el usuario se ecuentra eliminado o no',
  `eliminado_permanente` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Campo para definir la eliminación del registro permanentemente',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `respuesta_secreta` (`respuesta_secreta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Almacena los datos de los usuarios que manejan el sistema' AUTO_INCREMENT=35 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre_completo`, `nombre`, `contrasenia`, `email`, `telefono`, `nivel_usuario`, `pregunta_secreta`, `respuesta_secreta`, `fecha_creacion`, `hora_creacion`, `eliminado`, `eliminado_permanente`) VALUES
(1, 'Miguel Salazar', 'Miguel', '63a51b0da99b19129308f7a4a18356066244c693', 'ryukzero92@gmail.com', '0426-1858112', 'Evaluador', 'o9Gd0MKgUpmZ15/Uoqai', '0tKo0tKpm6eZ', '0000-00-00', '00:00:00', 0, 0),
(2, 'Miguel Salazar', 'RyukZ', '63a51b0da99b19129308f7a4a18356066244c693', 'miguel__salazar@hotmail.com', '0294-3318514', 'Administrador', '09WdmNqlppk=', '1cirodqcpazF', '0000-00-00', '00:00:00', 0, 0),
(3, '', 'miguel1', '63a51b0da99b19129308f7a4a18356066244c693', 'migue1langel.bux@gmail.com', '', 'Administrador', '', '', '0000-00-00', '00:00:00', 1, 1),
(4, '', 'RyukZ92', '63a51b0da99b19129308f7a4a18356066244c693', 'miiguelangel.bux@gmail.com', '', 'Evaluador', '', '', '0000-00-00', '00:00:00', 1, 1),
(6, '', 'UsuarioDePru', '672772fdc191289aed30dc543b38b7a89c0abae9', 'miguelangel...bux@gmail.com', '', 'Administrador', '', '', '2016-02-24', '12:42:21', 1, 1),
(7, 'Nuevo Usuario del Sistema', 'NuevoUsuario', 'f97be23f499a3a48c60076b4f3b8d27f02080191', 'nuevousaurio@email.com', '0412-3226262', 'Administrador', '', '', '2016-02-27', '12:48:06', 0, 0),
(8, '', 'miguel2016', '718f9b907292909c23d0858ea6247e6438a20e9d', 'miguel__salazar@hotmail.com', '', 'Evaluador', '', '', '2016-02-27', '12:50:14', 1, 1),
(9, '', 'miguel20162', '2cac85339d83a7c258aef016963abc126b8a6e54', 'miguel__salazar@hotmail.com', '', 'Administrador', '', '', '2016-02-27', '12:55:07', 1, 1),
(17, 'Mónica Eyeu Bastardo Méndez', 'MonicaEyeu', 'e95f886c8c0a576a40e5502fc6853de0c5fcecf1', 'monicaeyeu@gmail.com', '', 'Administrador', 'Ufbtt96wVfEiZD2WcV9jdA==', 'UfbtVCUhVfEia6BR7/VHjQa5dSj0IJUj8fOXJSHybWfGTJd7MA', '2016-02-27', '20:25:10', 0, 0),
(18, '', 'unusuarionue', 'bc0b67a25281dc2a7d8f9c703bc22d017ae58359', 'unsuaruio@gmail.com', '', 'Administrador', '', '', '2016-03-02', '15:16:13', 1, 1),
(19, 'Ok', 'MiguelAngel', '5dc31db78596fe0872b348c78097d82a3e626d80', 'miguelangel.bux@gmail.com', '', 'Evaluador', '', '', '2016-03-11', '09:19:01', 0, 0),
(20, '', '213123', '943753e4189a5b82cd7bf94f1380bef125419d22', 'asda@gamil.com', '', 'Evaluador', '', '', '2016-03-12', '16:44:04', 1, 1),
(21, '', 'MiguelAngelS', '63a51b0da99b19129308f7a4a18356066244c693', 'miguela12@gmail.com', '', 'Administrador', '', '', '2016-03-25', '14:23:25', 0, 0),
(22, '', 'Un usuario', '63a51b0da99b19129308f7a4a18356066244c693', 'miguel__sa@gmail.com', '', 'Evaluador', '', '', '2016-03-25', '14:28:13', 0, 0),
(23, '', 'adadasads', '63a51b0da99b19129308f7a4a18356066244c693', 'miguela1@gmail.com.ve', '', 'Evaluador', '', '', '2016-03-25', '14:29:09', 1, 1),
(24, '', 'Miguadsadas', '63a51b0da99b19129308f7a4a18356066244c693', 'madasd@gmail.com', '', 'Evaluador', '', '', '2016-03-25', '14:30:21', 1, 1),
(25, '', '12321312321', '63a51b0da99b19129308f7a4a18356066244c693', 'masdsa@gmail.com', '', 'Administrador', '', '', '2016-03-25', '14:30:49', 1, 1),
(26, '', '1313131', '63a51b0da99b19129308f7a4a18356066244c693', 'adasdasd', '', 'Evaluador', '', '', '2016-03-25', '14:31:07', 1, 1),
(27, '12', 'miguel1234', '986a7a3632a6ca69affd2f08901c0f2513a19b00', 'ryukzero292@gmail.com', '1232-1', 'Evaluador', '', '', '2016-03-25', '14:31:58', 0, 0),
(28, '', 'akjdnadasnn', '7a85f4764bbd6daf1c3545efbbf0f279a6dc0beb', 'ok@gmail.com', '', 'Administrador', '', '', '2016-03-25', '18:09:00', 1, 1),
(29, 'Miguel Ángel Salazar Castillo', 'UnNombreDeUs', '63a51b0da99b19129308f7a4a18356066244c693', 'miguel1231@gmail.com', '', 'Evaluador', '', '', '2016-03-26', '23:37:15', 0, 0),
(30, 'Prueba de Usuario', 'Prueba', '63a51b0da99b19129308f7a4a18356066244c693', 'adadasdas@mail.com', '0426-1858112', 'Evaluador', '', '', '2016-04-04', '08:59:56', 0, 0),
(31, 'Test Php Unit', 'yrTz1v7DEm_PhpUnit', '3033c3ef4b24f9e61c732e5649ee4680b25884db', 'TestPhpUnit@mail.com', '0294-6987422', 'Administrador', '', '', '2016-04-19', '13:01:50', 0, 0),
(32, 'Test Php Unit', 'PhpUnit_PVieuDZ3iJ', '1f896f0416af915809673277ca9d229f79f0faec', 'TestPhpUnit@mail.com', '0294-6987422', 'Administrador', '', '', '2016-04-19', '13:02:33', 0, 0),
(33, 'Test Php Unit', 'TestPhpUnit_tJbW7bVCP2', '1500a033ffccded38fa6b738d1f30238e9040e5a', 'TestPhpUnit@mail.com', '0294-6987422', 'Evaluador', '', '', '2016-04-19', '13:03:18', 0, 0),
(34, 'Test Php Unit', 'TestPhpUnit_IVvmdYEP20', 'f4c66ea3cd8b99640f6f3075ed53e0ecf3a83f5b', 'TestPhpUnit@mail.com', '0294-6987422', 'Evaluador', '', '', '2016-04-19', '13:05:26', 1, 0);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ciclo_evaluacion`
--
ALTER TABLE `ciclo_evaluacion`
  ADD CONSTRAINT `ciclo_evaluacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `historial_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prueba`
--
ALTER TABLE `prueba`
  ADD CONSTRAINT `prueba_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prueba_ibfk_2` FOREIGN KEY (`id_ciclo_evaluacion`) REFERENCES `ciclo_evaluacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `resultado_carga_de_datos`
--
ALTER TABLE `resultado_carga_de_datos`
  ADD CONSTRAINT `resultado_carga_de_datos_ibfk_1` FOREIGN KEY (`id_prueba`) REFERENCES `prueba` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `resultado_carga_de_sesiones`
--
ALTER TABLE `resultado_carga_de_sesiones`
  ADD CONSTRAINT `resultado_carga_de_sesiones_ibfk_1` FOREIGN KEY (`id_prueba`) REFERENCES `prueba` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `resultado_seguridad`
--
ALTER TABLE `resultado_seguridad`
  ADD CONSTRAINT `resultado_seguridad_ibfk_1` FOREIGN KEY (`id_prueba`) REFERENCES `prueba` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
