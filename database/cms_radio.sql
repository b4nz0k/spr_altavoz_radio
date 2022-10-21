-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.7.36 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para spr_altavozradio
DROP DATABASE IF EXISTS `spr_altavozradio`;
CREATE DATABASE IF NOT EXISTS `spr_altavozradio` /*!40100 DEFAULT CHARACTER SET latin2 COLLATE latin2_bin */;
USE `spr_altavozradio`;

-- Volcando estructura para tabla spr_altavozradio.programacion
DROP TABLE IF EXISTS `programacion`;
CREATE TABLE IF NOT EXISTS `programacion` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estaciones_radio_id` bigint(20) unsigned NOT NULL,
  `programa_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `tipo_transmision` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hora_inicio` timestamp NOT NULL,
  `hora_final` timestamp NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla spr_altavozradio.programacion: 14 rows
/*!40000 ALTER TABLE `programacion` DISABLE KEYS */;
REPLACE INTO `programacion` (`id`, `estaciones_radio_id`, `programa_id`, `user_id`, `dia`, `tipo_transmision`, `hora_inicio`, `hora_final`, `color`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1, NULL, 1, 'radio', '2022-09-07 17:21:12', '2022-09-07 17:23:16', '', '2022-09-07 17:20:20', NULL, NULL),
	(2, 0, 2, 2, NULL, 'Transmision1', '2022-09-08 05:00:00', '2022-09-08 08:25:00', '#2B47FFFF', '2022-09-08 15:41:47', '2022-09-13 22:16:49', NULL),
	(3, 0, 5, 2, NULL, 'Transmision2', '2022-09-08 21:00:00', '2022-09-08 23:00:00', '', '2022-09-08 15:52:05', '2022-09-08 15:52:05', NULL),
	(4, 1, 1, 2, NULL, 'Transmision1', '2022-09-12 06:01:00', '2022-09-12 10:58:00', '#3FF3D0FF', '2022-09-11 06:06:44', '2022-09-13 22:52:52', NULL),
	(8, 1, 5, 2, NULL, 'Transmision2', '2022-09-13 08:15:00', '2022-09-13 13:00:00', '#2DCE17FF', '2022-09-12 19:07:02', '2022-09-13 22:29:41', NULL),
	(7, 1, 1, 2, NULL, 'Transmision2', '2022-09-17 06:01:00', '2022-09-17 07:00:00', '#1D59FCFF', '2022-09-12 16:53:59', '2022-09-12 22:26:45', NULL),
	(9, 1, 5, 2, NULL, 'Transmision1', '2022-09-11 05:01:00', '2022-09-11 08:59:00', '#2B47FFFF', '2022-09-13 16:49:17', '2022-09-13 22:52:45', NULL),
	(10, 1, 2, 2, NULL, 'Transmision1', '2022-09-14 06:05:00', '2022-09-14 08:01:00', '#2B47FFFF', '2022-09-13 16:52:39', '2022-09-13 16:52:49', NULL),
	(11, 1, 1, 2, NULL, 'Transmision4', '2022-09-19 06:05:00', '2022-09-19 11:30:00', '#2B47FFFF', '2022-09-13 17:20:21', '2022-09-13 17:20:21', NULL),
	(12, 1, 1, 2, NULL, 'Transmision4', '2022-09-20 05:00:00', '2022-09-20 11:30:00', '#2BFF64FF', '2022-09-13 17:21:09', '2022-09-13 22:28:44', NULL),
	(13, 1, 3, 2, NULL, 'Transmision4', '2022-09-21 05:01:00', '2022-09-21 08:00:00', '#2B47FFFF', '2022-09-13 17:21:52', '2022-09-13 17:21:52', NULL),
	(14, 1, 3, 2, NULL, 'Transmision4', '2022-09-16 05:01:00', '2022-09-16 09:00:00', '#2B47FFFF', '2022-09-13 17:22:38', '2022-09-13 22:52:30', NULL),
	(15, 1, 3, 2, NULL, 'Transmision4', '2022-10-01 05:01:00', '2022-10-01 08:00:00', '', '2022-09-13 17:23:08', '2022-09-13 17:23:08', NULL),
	(16, 1, 1, 2, NULL, 'Transmision1', '2022-09-09 05:00:00', '2022-09-09 09:20:00', '', '2022-09-13 22:01:07', '2022-09-13 22:01:07', NULL);
/*!40000 ALTER TABLE `programacion` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
