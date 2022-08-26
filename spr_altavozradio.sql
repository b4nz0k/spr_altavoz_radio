/*
 Navicat Premium Data Transfer

 Source Server         : WampServer
 Source Server Type    : MySQL
 Source Server Version : 50731
 Source Host           : 127.0.0.1:3306
 Source Schema         : spr_altavozradio

 Target Server Type    : MySQL
 Target Server Version : 50731
 File Encoding         : 65001

 Date: 26/03/2021 12:53:47
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for autores
-- ----------------------------
DROP TABLE IF EXISTS `autores`;
CREATE TABLE `autores`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(20) NULL DEFAULT NULL,
  `autor` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for blog
-- ----------------------------
DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `estacion_radio_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `usuario_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `programas_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `categoria_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `subtitulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `contenido` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `imagen_destacada` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `video_upload` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `video_iframe` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `productor` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `estatus` tinyint(2) NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of blog
-- ----------------------------
INSERT INTO `blog` VALUES (1, 1, 1, 1, 2, 'Publicación 1', 'test', '<p>Somos seres sexuados desde el inicio de nuestra vida, sin embargo se han normalizado estándares que distorsionan la realidad y derivan en percepciones tóxicas sobre las relaciones humanas: idealizan cuerpos, vidas y formas de ser y relacionarnos con el otro, con la comunidad.<br><br>La sexualidad es una forma de comunicación permanente, está relacionada con lo que sentimos, hacemos, pensamos y con la forma en cómo entendemos el mundo, transciende hacia todos los espacios que habitamos.<br><br>Por lo tanto, en esta serie abordaremos temas como la relación que tiene la sexualidad con la hipersexualización, el embarazo adolescente, los abusos y discriminaciones de la comunidad transgénero y la diversidad sexual, la trata de personas, así como aspectos más afables del tema que no lo encasillen en un cliché (bueno o malo) sino que expandan las posibilidades para nombrar, con múltiples matices, un aspecto vital que trasciende a la humanidad.<br><br>Un juego de palabras da origen al nombre 5 sentidos que hace alusión a los múltiples caminos que puede tomar el tema y a los 5 sentidos del ser…humano. Este blog necesita de tus pasiones, memorias, punto de vista y de tu libertad, para alimentarlo y tomar la palabra. Envía tus textos a:<a href=\"mailto:contacto@altavozradio.mx\">contacto@altavozradio.mx</a></p>', '/upload/b90fdb633c138baaa1ee3a57831fb579.jpg', NULL, NULL, 'Blog general', 1, 'b6b0f7a2eb3e9796e2b5', '2021-03-09 12:15:03', '2021-03-09 12:29:36', NULL);
INSERT INTO `blog` VALUES (2, 1, 1, 1, 2, 'Texto 1', NULL, '<p><strong>Buen oído al mal tiempo: el placer auditivo en tiempos del covid</strong> <i>Por: Héctor RG</i> El gancho al hígado asestado por el covid-19 en este 2020 ha llevado a la esquina del encierro forzoso a la sociedad mundial, esta condición, ha generado distintos estados de ánimo en los seres humanos. Para sacudirse el confinamiento, la mayoría de personas ha encontrado formas de sortearlo, cada quien, de acuerdo a sus necesidades, por ejemplo: hacer home office, hacer tareas escolares, ejercitarse, hacer reparaciones en el hogar, escuchar la radio, estar en redes sociales, leer libros, ver series y películas o escuchar música, esta última, tiene la capacidad de activar en todos los seres humanos respuestas emocionales, ya sean, positivas o negativas.</p><p>El ser humano conforme crece va asimilando de distinta forma el sonido y, por ende, la música… En los adultos, una de las principales motivaciones para acercarse a ella es la relación que tiene con las emociones (sensaciones subjetivas, cambios en el sistema nervioso autónomo y endocrino, expresiones motoras como sonrisas) y tendencias en la actividad, como bailar, cantar, aplaudir o tocar un instrumento (Custodio &amp; Cano-Campos, 2017).</p><p>La música motiva y sirve como desahogo para muchos, ha llegado a ser considerada como uno de los elementos que produce más placer en el ser humano, sobre todo, si es una composición a la que tenemos afecto, ya sea, porque nos hace recordar personas, situaciones, lugares, o nos despierta la inquietud de imaginar lo que el autor experimentó o sintió al escribirla. Esto último, es lo que ocurre al escuchar la canción Fusión del letrista uruguayo, Jorge Drexler, el autor se adentra al universo del deseo, donde lo único que importa es experimentar sensaciones que revelan dimensiones únicas, y, por unos minutos, hace que orbites en los límites de un espacio pletórico de erotismo y deseo y surja también la necesidad de no separarse nunca del ser amado, como dijera él ¿Qué tendrá de real esta locura? ¿Quién nos asegura que esto es normal? ¿Qué sustancias se generan en el cerebro humano para vivir sensaciones de deseo y atracción sexual? según Helen Fisher cuando la dopamina se genera en grandes cantidades dentro de nuestro cerebro, a la par, se desarrollan también, grandes cantidades de testosterona que es la hormona del deseo sexual. Para la especialista, son tres las sustancias importantes que generan la sensación de estar enamorados: dopamina, serotonina y norepinefrina, éstas hacen que, durante varias horas, sino es que días, no podamos sacar de nuestra cabeza al ser amado y más aún, si son estímulos sonoros, que nos recuerden a esa persona. Helen Fisher en su libro Por qué amamos, Naturaleza y Química del amor nos menciona que:</p><p>La dopamina es un neurotransmisor que en gran cantidad es un gran estimulador de la energía mental y motivacional. (…) en el cerebro, aumenta su trabajo bombeando mayores cantidades de este estimulante natural para proveerlo de energía, centrar la atención e impulsar al afectado a luchar por alcanzar su premio, en este caso, ganarse el corazón de la persona, objeto de su amor (Fisher, 2004)</p><p>Desde luego, el amor es un sentimiento universal que se genera principalmente por la dopamina, serotonina y norepinefrina. Sin embargo, hay situaciones o eventos que nos hacen sentir de capa caída, ya sea, una relación fallida, un amor mal correspondido o situaciones como el encierro forzado por la contingencia sanitaria, todos, factores que pueden disminuir la cantidad de serotonina en nuestro cerebro y en consecuencia hacernos sentir aún más depresivos. Por fortuna tenemos a la música, y si necesitas inyectar un poco de energía a tu vida, al igual que el amor, actuará como un estimulador que abrirá el umbral de la emoción, los recuerdos y el afecto. La música es considerada como uno de los elementos que produce más placer en el ser humano, y echar oído de ella, hará que nos liberemos, por un momento, de la pesadumbre y las vicisitudes que trajo consigo el covid-19 este 2020.</p>', '/upload/a59118c44e463da19af0dd84783dfe51.jpg', NULL, NULL, 'Blog general', 1, 'f49c43b02348e1eebc7d', '2021-03-09 12:22:06', '2021-03-09 12:28:23', NULL);

-- ----------------------------
-- Table structure for categorias
-- ----------------------------
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `estacion_radio_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `categoria` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tipo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `estatus` tinyint(2) NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_categoria_estacion_radio_id`(`estacion_radio_id`) USING BTREE,
  CONSTRAINT `fk_categoria_estacion_radio_id` FOREIGN KEY (`estacion_radio_id`) REFERENCES `estacion_radio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of categorias
-- ----------------------------
INSERT INTO `categorias` VALUES (1, 1, 'nueva categoria nueva categoria', NULL, 'nueva-categoria', 'podcast', NULL, NULL, '2021-03-08 17:37:18', '2021-03-08 17:37:18', NULL);
INSERT INTO `categorias` VALUES (2, 1, 'textes', NULL, 'textes', 'blog', NULL, NULL, '2021-03-08 17:39:19', '2021-03-08 17:39:19', NULL);
INSERT INTO `categorias` VALUES (3, 1, 'te x yrw asdf asd f', NULL, 'te-x-yrw-asdf-asd-f', 'video', NULL, NULL, '2021-03-08 17:40:13', '2021-03-08 17:40:13', NULL);

-- ----------------------------
-- Table structure for categorias_programas
-- ----------------------------
DROP TABLE IF EXISTS `categorias_programas`;
CREATE TABLE `categorias_programas`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `categorias_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `programas_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `created_a` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for estacion_radio
-- ----------------------------
DROP TABLE IF EXISTS `estacion_radio`;
CREATE TABLE `estacion_radio`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `estacion` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `descripcion` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of estacion_radio
-- ----------------------------
INSERT INTO `estacion_radio` VALUES (1, 'Altavoz Radio Web', NULL, '2021-03-02 12:46:42', '2021-03-02 12:46:44', NULL);
INSERT INTO `estacion_radio` VALUES (2, 'Altavoz Radio Coatzacoalcos 104.3', NULL, '2021-03-02 12:47:07', '2021-03-02 12:47:10', NULL);
INSERT INTO `estacion_radio` VALUES (3, 'Altavoz Radio Tapachula 101.1', NULL, '2021-03-02 12:47:12', '2021-03-02 12:47:14', NULL);
INSERT INTO `estacion_radio` VALUES (4, 'Altavoz Radio Mazatlán 103.5', NULL, '2021-03-05 01:40:05', '2021-03-05 01:40:07', NULL);
INSERT INTO `estacion_radio` VALUES (5, 'Altavoz Radio Colima 102.9', NULL, '2021-03-05 01:40:09', '2021-03-05 01:40:12', NULL);

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for gnrl_logs
-- ----------------------------
DROP TABLE IF EXISTS `gnrl_logs`;
CREATE TABLE `gnrl_logs`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` int(20) UNSIGNED NOT NULL,
  `debug` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `estatus` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `getMessage` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `getCode` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `getLine` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `getPath` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `getFile` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ip_address` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_usuario_id`(`usuario_id`) USING BTREE,
  CONSTRAINT `fk_usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);

-- ----------------------------
-- Table structure for multimedia
-- ----------------------------
DROP TABLE IF EXISTS `multimedia`;
CREATE TABLE `multimedia`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `estacion_radio_id` int(20) NULL DEFAULT NULL,
  `usuario_id` int(20) NULL DEFAULT NULL,
  `programas_id` int(20) NULL DEFAULT NULL,
  `categoria_id` int(11) NULL DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `subtitulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `contenido` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `imagen_destacada` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `video_upload` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `video_iframe` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `productor` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `estatus` tinyint(2) NULL DEFAULT NULL,
  `token` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for podcast
-- ----------------------------
DROP TABLE IF EXISTS `podcast`;
CREATE TABLE `podcast`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `estacion_radio_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `usuario_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `categoria_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `programas_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `subtitulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `contenido` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `productor` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `imagen_destacada` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `audio` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `estatus` tinyint(2) NULL DEFAULT NULL,
  `token` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of podcast
-- ----------------------------
INSERT INTO `podcast` VALUES (1, 1, 1, 1, 1, 'Capítulo', 'Diversidad Sexual', '<p>Asistimos a la marcha gay 2019, charlamos con algunos de los asistentes que compartieron con nosotros parte de sus historias de vida: discriminación, abandono familiar, prostitución y bullying son algunos de los problemas a los que se han enfrentado. Josué Morales, trabaja para cambiar esta realidad y como integrante de Yaaj México, da apoyo a aquellos jóvenes que son rechazados por su familia y expulsados de sus hogares.</p>', 'Elizabeth Cárdenas', '/upload/5a6d840530d86c2e5f7c71d64cf55802.jpg', '/podcastprograma/eedad39abe3647b7d93f4073247947fa.mp3', 3, '11690d73c6f01b6ba06c', '2021-03-08 02:12:51', '2021-03-08 19:14:18', '2021-03-08 19:14:18');
INSERT INTO `podcast` VALUES (2, 1, 1, 2, 1, 'Capitulo 2', 'Diversidad Sexual Des', '<p>Asistimos a la marcha gay 2019, charlamos con algunos de los asistentes que compartieron con nosotros parte de sus historias de vida: discriminación, abandono familiar, prostitución y bullying son algunos de los problemas a los que se han enfrentado. Josué Morales, trabaja para cambiar esta realidad y como integrante de Yaaj México, da apoyo a aquellos jóvenes que son rechazados por su familia y expulsados de sus hogares.</p>', 'Elizabeth Cárdenas', '/upload/5a6d840530d86c2e5f7c71d64cf55802.jpg', '/podcastprograma/eedad39abe3647b7d93f4073247947fa.mp3', 1, '5b4139934c9a7ed15c22', '2021-03-08 02:12:53', '2021-03-08 02:12:51', NULL);
INSERT INTO `podcast` VALUES (3, 1, 1, 3, 1, 'Capitulo 3', 'Diversidad Sexual Des1', '<p>Asistimos a la marcha gay 2019, charlamos con algunos de los asistentes que compartieron con nosotros parte de sus historias de vida: discriminación, abandono familiar, prostitución y bullying son algunos de los problemas a los que se han enfrentado. Josué Morales, trabaja para cambiar esta realidad y como integrante de Yaaj México, da apoyo a aquellos jóvenes que son rechazados por su familia y expulsados de sus hogares.</p>', 'Elizabeth Cárdenas', '/upload/5a6d840530d86c2e5f7c71d64cf55802.jpg', '/podcastprograma/eedad39abe3647b7d93f4073247947fa.mp3', 3, '5b4139934c9a7ed15c33', '2021-03-08 02:12:55', '2021-03-08 19:22:15', NULL);

-- ----------------------------
-- Table structure for programacion
-- ----------------------------
DROP TABLE IF EXISTS `programacion`;
CREATE TABLE `programacion`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_programa` int(255) NULL DEFAULT NULL,
  `inicio` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `final` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `id_estacion` int(11) NULL DEFAULT NULL,
  `id_podcast` int(255) NULL DEFAULT NULL,
  `id_tipo` int(255) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 60 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of programacion
-- ----------------------------
INSERT INTO `programacion` VALUES (56, 2, '1615960800000', '1615986000000', 1, NULL, 1, '2021-03-19 12:49:15', NULL, '2021-03-19 12:49:15');
INSERT INTO `programacion` VALUES (57, 1, '1615274100000', '1615295700000', 1, NULL, 1, '2021-03-19 12:52:07', NULL, '2021-03-19 12:52:07');
INSERT INTO `programacion` VALUES (58, 2, '1615297500000', '1615307400000', 1, NULL, 1, '2021-03-22 12:42:35', NULL, '2021-03-22 12:42:35');
INSERT INTO `programacion` VALUES (59, 2, '1615311900000', '1615329900000', 1, NULL, 1, '2021-03-26 11:46:04', NULL, '2021-03-26 11:46:04');

-- ----------------------------
-- Table structure for programas
-- ----------------------------
DROP TABLE IF EXISTS `programas`;
CREATE TABLE `programas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estacion_radio_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `usuario_id` int(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `subtitulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `contenido` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `autor` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `imagen_destacada` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `imagen_banner` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `estatus` tinyint(2) NULL DEFAULT NULL,
  `token` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of programas
-- ----------------------------
INSERT INTO `programas` VALUES (1, 1, 1, 'Cinco Sentidos', 'RUTAS DE LA SEXUALIDAD MÁS ALLÁ DE LA PIEL.', '<p>En 5 sentidos vamos más allá de lo que algunos medios o la pornografía dicen que es la sexualidad. Aquí Investigadores, activistas, académicos, periodistas, escritores y artistas exploramos las rutas de la sexualidad contemporánea que nos llevan a transitar por el placer, el amor, la diversidad, las nuevas tecnologías, la violencia, el dolor, las enfermedades. En 5 sentidos sabemos que la sexualidad va más allá de la piel, lo atraviesa todo… nos atraviesa a todos.</p>', 'ab', '/upload/69cf06bd07b6679ca97e7fee0e57d378.png', '/upload/e4530ae84f1eab5bb53852ae80d9f8f9.jpg', 1, '30a8a4f69bcb3a8cab5b', '2021-03-08 01:38:45', '2021-03-08 01:38:45', NULL);

-- ----------------------------
-- Table structure for programas_estacion_radio
-- ----------------------------
DROP TABLE IF EXISTS `programas_estacion_radio`;
CREATE TABLE `programas_estacion_radio`  (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `programas_id` int(20) UNSIGNED NOT NULL,
  `estacion_radio_id` int(20) UNSIGNED NOT NULL,
  `estatus` tinyint(2) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for upload_files
-- ----------------------------
DROP TABLE IF EXISTS `upload_files`;
CREATE TABLE `upload_files`  (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `estacion_radio_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `file_name_original` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `file_name_renombrado` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `file_size` double NULL DEFAULT NULL,
  `file_extension` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `file_tipo` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of upload_files
-- ----------------------------
INSERT INTO `upload_files` VALUES (1, 1, '2c93c35b80e81cd538817f1d0bc37fcb', 'a36df0aef9becd19b081e682297dc1a9', 269044, 'jpg', 'imagen', '2021-03-17 14:14:26', '2021-03-17 14:14:26', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estacion_radio_id` int(20) UNSIGNED NULL DEFAULT NULL,
  `estatus` tinyint(2) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE,
  INDEX `fk_estacion_radio_id`(`estacion_radio_id`) USING BTREE,
  CONSTRAINT `fk_estacion_radio_id` FOREIGN KEY (`estacion_radio_id`) REFERENCES `estacion_radio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Eduardo Guadarrama Aguilar', 'eduardo.guadarrama@spr.gob.mx', NULL, '$2y$10$R1O6hmhtRgsFM9ZzFl64GePKLG3k3mcCayHiocX5laHen/68l3r6i', NULL, 1, 1, '2021-03-02 11:57:41', '2021-03-02 11:57:44', NULL);

SET FOREIGN_KEY_CHECKS = 1;
