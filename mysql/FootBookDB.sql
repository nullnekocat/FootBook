CREATE DATABASE  IF NOT EXISTS `footbook_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `footbook_db`;
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: footbook_db
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_categories_softdelete_cascade_posts` AFTER UPDATE ON `categories` FOR EACH ROW BEGIN
  IF NEW.status = 0 AND (OLD.status IS NULL OR OLD.status <> 0) THEN
    UPDATE posts
       SET status = 0
     WHERE category_id = NEW.id
       AND status = 1;
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `commentlikes`
--

DROP TABLE IF EXISTS `commentlikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commentlikes` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `comment_id` bigint NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comment_id` (`comment_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `commentlikes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `commentlikes_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `post_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `postlikes`
--

DROP TABLE IF EXISTS `postlikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `postlikes` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `post_id` bigint NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`post_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `postlikes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `postlikes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `category_id` int NOT NULL,
  `worldcup_id` int NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `media` longblob,
  `views` int DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `approved_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `worldcup_id` (`worldcup_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`worldcup_id`) REFERENCES `worldcups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_posts_softdelete_cascade_comments` AFTER UPDATE ON `posts` FOR EACH ROW BEGIN
  -- Solo cuando el post pase de activo (<>0) a inactivo (=0)
  IF NEW.status = 0 AND (OLD.status IS NULL OR OLD.status <> 0) THEN
    UPDATE comments
       SET status = 0
     WHERE post_id = NEW.id
       AND status = 1;
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `admin` tinyint(1) NOT NULL,
  `username` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `gender` int NOT NULL,
  `birth_country` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` longblob,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_users_softdelete_cascade` AFTER UPDATE ON `users` FOR EACH ROW BEGIN
  -- Solo cuando el usuario pase de activo (<>0) a inactivo (=0)
  IF NEW.status = 0 AND (OLD.status IS NULL OR OLD.status <> 0) THEN

    -- Apagar todos los posts del usuario
    UPDATE posts
       SET status = 0
     WHERE user_id = NEW.id
       AND status = 1;

    -- Apagar todos los comentarios que escribió el usuario (en cualquier post)
    UPDATE comments
       SET status = 0
     WHERE user_id = NEW.id
       AND status = 1;

  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Temporary view structure for view `v_lista_de_categorias`
--

DROP TABLE IF EXISTS `v_lista_de_categorias`;
/*!50001 DROP VIEW IF EXISTS `v_lista_de_categorias`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_lista_de_categorias` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `status`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_lista_de_comentarios`
--

DROP TABLE IF EXISTS `v_lista_de_comentarios`;
/*!50001 DROP VIEW IF EXISTS `v_lista_de_comentarios`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_lista_de_comentarios` AS SELECT 
 1 AS `id`,
 1 AS `post_id`,
 1 AS `user_id`,
 1 AS `username`,
 1 AS `avatar_b64`,
 1 AS `content`,
 1 AS `status`,
 1 AS `created_at`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_lista_de_mundiales`
--

DROP TABLE IF EXISTS `v_lista_de_mundiales`;
/*!50001 DROP VIEW IF EXISTS `v_lista_de_mundiales`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_lista_de_mundiales` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `country`,
 1 AS `year`,
 1 AS `description`,
 1 AS `banner_b64`,
 1 AS `status`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_lista_de_usuarios`
--

DROP TABLE IF EXISTS `v_lista_de_usuarios`;
/*!50001 DROP VIEW IF EXISTS `v_lista_de_usuarios`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_lista_de_usuarios` AS SELECT 
 1 AS `id`,
 1 AS `admin`,
 1 AS `username`,
 1 AS `email`,
 1 AS `fullname`,
 1 AS `birthday`,
 1 AS `gender`,
 1 AS `birth_country`,
 1 AS `country`,
 1 AS `status`,
 1 AS `created_at`,
 1 AS `avatar_b64`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_lista_ligera_de_mundiales`
--

DROP TABLE IF EXISTS `v_lista_ligera_de_mundiales`;
/*!50001 DROP VIEW IF EXISTS `v_lista_ligera_de_mundiales`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_lista_ligera_de_mundiales` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `country`,
 1 AS `year`,
 1 AS `description`,
 1 AS `status`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_lista_ligera_de_publicaciones`
--

DROP TABLE IF EXISTS `v_lista_ligera_de_publicaciones`;
/*!50001 DROP VIEW IF EXISTS `v_lista_ligera_de_publicaciones`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_lista_ligera_de_publicaciones` AS SELECT 
 1 AS `id`,
 1 AS `username`,
 1 AS `category_name`,
 1 AS `worldcup_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `media_b64`,
 1 AS `created_at`,
 1 AS `approved`,
 1 AS `status`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `v_lista_ligera_de_usuarios`
--

DROP TABLE IF EXISTS `v_lista_ligera_de_usuarios`;
/*!50001 DROP VIEW IF EXISTS `v_lista_ligera_de_usuarios`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_lista_ligera_de_usuarios` AS SELECT 
 1 AS `id`,
 1 AS `username`,
 1 AS `email`,
 1 AS `created_at`,
 1 AS `status`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `worldcups`
--

DROP TABLE IF EXISTS `worldcups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `worldcups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `banner` longblob,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'footbook_db'
--
/*!50003 DROP FUNCTION IF EXISTS `fn_exists_post_active` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_exists_post_active`(p_post_id BIGINT) RETURNS tinyint
    READS SQL DATA
    DETERMINISTIC
RETURN EXISTS(
  SELECT 1 FROM posts
  WHERE id = p_post_id AND status = 1
) ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `fn_exists_user_active` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `fn_exists_user_active`(p_user_id BIGINT) RETURNS tinyint
    READS SQL DATA
    DETERMINISTIC
RETURN EXISTS(
  SELECT 1 FROM Users
  WHERE id = p_user_id AND status = 1
) ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_approve_post` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_approve_post`(
    IN p_post_id     BIGINT,
    IN p_is_approved TINYINT
)
BEGIN
    DECLARE v_exists INT DEFAULT 0;

    /* ===== Validaciones ===== */
    IF p_post_id IS NULL OR p_post_id <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_post_id inválido';
    END IF;

    IF p_is_approved NOT IN (0,1) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_is_approved debe ser 0 o 1';
    END IF;

    SELECT COUNT(*) INTO v_exists
      FROM posts
     WHERE id = p_post_id;

    IF v_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El post no existe';
    END IF;

    /* ===== Update ===== */
    START TRANSACTION;

    UPDATE posts
       SET approved   = p_is_approved,
           approved_at = CASE WHEN p_is_approved = 1 THEN CURRENT_TIMESTAMP() ELSE NULL END
     WHERE id = p_post_id;

    /* Devuelve el estado final */
    SELECT id,
           approved,
           approved_at
      FROM posts
     WHERE id = p_post_id;

    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_category_update` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_category_update`(
    IN p_category_id INT,
    IN p_new_name    VARCHAR(32)
)
BEGIN
    DECLARE v_newname VARCHAR(32);

    -- Saneado básico
    SET v_newname = TRIM(p_new_name);

    IF p_category_id IS NULL OR p_category_id <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'id de categoría inválido';
    END IF;

    IF v_newname IS NULL OR CHAR_LENGTH(v_newname) = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El nombre es requerido';
    END IF;

    IF CHAR_LENGTH(v_newname) > 32 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El nombre excede 32 caracteres';
    END IF;

    -- Evitar duplicados (si tu tabla lo permite)
    IF EXISTS(SELECT 1 FROM categories c WHERE c.name = v_newname AND c.id <> p_category_id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Ya existe una categoría con ese nombre';
    END IF;

    START TRANSACTION;

    UPDATE categories
       SET name = v_newname
     WHERE id   = p_category_id;

    IF ROW_COUNT() = 0 THEN
        -- No afectó filas (id inexistente o nombre igual)
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se actualizó la categoría (id inexistente o sin cambios)';
    END IF;

    -- Devuelve el estado final
    SELECT id, name
      FROM categories
     WHERE id = p_category_id;

    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_create_category` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_category`(
    IN p_name VARCHAR(255)
)
BEGIN
    DECLARE v_name   VARCHAR(255);
    DECLARE v_exists INT DEFAULT 0;

    -- Normaliza
    SET v_name = TRIM(p_name);

    -- Requerido
    IF v_name IS NULL OR v_name = '' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El nombre de la categoría es requerido';
    END IF;

    -- Longitud
    IF CHAR_LENGTH(v_name) > 32 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El nombre de la categoría excede 32 caracteres (máx 32)';
    END IF;

    -- Duplicados (case-insensitive)
    SELECT COUNT(*) INTO v_exists
      FROM categories
     WHERE LOWER(name) = LOWER(v_name);

    IF v_exists > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La categoría ya existe';
    END IF;

    -- Inserción
    INSERT INTO categories (name)
    VALUES (v_name);

    -- Retorna el id creado
    SELECT LAST_INSERT_ID() AS category_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_create_comment` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_comment`(
  IN p_post_id BIGINT,
  IN p_user_id BIGINT,
  IN p_content TEXT
)
BEGIN
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    RESIGNAL;
  END;

  /* Validaciones */
  IF p_post_id IS NULL OR p_post_id <= 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_post_id inválido';
  END IF;

  IF p_user_id IS NULL OR p_user_id <= 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_user_id inválido';
  END IF;

  IF fn_exists_user_active(p_user_id) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario no existe o está inactivo';
  END IF;

  IF fn_exists_post_active(p_post_id) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El post no existe o está inactivo';
  END IF;

  IF p_content IS NULL OR CHAR_LENGTH(TRIM(p_content)) = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Contenido vacío';
  END IF;

  START TRANSACTION;

  INSERT INTO comments (post_id, user_id, content, status, created_at)
  VALUES (p_post_id, p_user_id, TRIM(p_content), 1, CURRENT_TIMESTAMP);

  SELECT c.id, c.post_id, c.user_id, c.content, c.status, c.created_at
  FROM comments c
  WHERE c.id = LAST_INSERT_ID();

  COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_create_post` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_post`(
    IN p_user_id     BIGINT,	   -- NOTNULL
    IN p_category_id INT,          -- NOTNULL
    IN p_worldcup_id INT,          -- NOTNULL
    IN p_team        VARCHAR(32),  -- equipo (opcional),
    IN p_title		 VARCHAR(64),  -- NOTNULL
    IN p_description TEXT,         -- NOTNULL
    IN p_media       LONGBLOB      -- imagen/video (opcional)
)
BEGIN
    DECLARE v_user    INT DEFAULT 0;
    DECLARE v_cat     INT DEFAULT 0;
    DECLARE v_wc      INT DEFAULT 0;

    /* ===== Validaciones básicas ===== */
    IF p_user_id IS NULL OR p_user_id <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_user_id es requerido';
    END IF;
    
    IF p_category_id IS NULL OR p_category_id <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_category_id es requerido';
    END IF;
    
	IF p_worldcup_id IS NULL OR p_worldcup_id <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_worldcup_id es requerido';
    END IF;
    
	IF p_description IS NULL OR CHAR_LENGTH(TRIM(p_description)) = 0 THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_description es requerido';
	END IF;
    
	IF p_title IS NULL OR CHAR_LENGTH(TRIM(p_title)) = 0 OR CHAR_LENGTH(TRIM(p_title)) > 64 THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'campo p_title invalido';
	END IF;
    
    SELECT COUNT(*) INTO v_user
      FROM Users
     WHERE id = p_user_id AND status = 1;
    IF v_user = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario no existe o está inactivo';
    END IF;

    IF p_category_id IS NOT NULL THEN
        SELECT COUNT(*) INTO v_cat
          FROM categories
         WHERE id = p_category_id;
        IF v_cat = 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La categoría no existe';
        END IF;
    END IF;

    IF p_worldcup_id IS NOT NULL THEN
        SELECT COUNT(*) INTO v_wc
          FROM worldcups
         WHERE id = p_worldcup_id;
        IF v_wc = 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El mundial (worldcup) no existe';
        END IF;
    END IF;

    IF p_description IS NULL OR CHAR_LENGTH(TRIM(p_description)) = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La descripción es requerida';
    END IF;

    IF p_team IS NOT NULL AND CHAR_LENGTH(p_team) > 32 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El campo team excede 32 caracteres';
    END IF;

    /* ===== Inserción ===== */
    START TRANSACTION;

    INSERT INTO posts(
        user_id,
        category_id,
        worldcup_id,
        team,
        title,
        description,
        media,
        approved,
        status,
        created_at
    )
    VALUES(
        p_user_id,
        p_category_id,
        p_worldcup_id,
        p_team,
        p_title,
        p_description,
        p_media,
        0,                    -- pendiente de aprobación
        1,                    -- activo
        CURRENT_TIMESTAMP()
    );

    /*Devuelve el id creado*/
    SELECT LAST_INSERT_ID() AS post_id;

    COMMIT;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_create_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_user`(
    IN p_admin TINYINT(1),
    IN p_username VARCHAR(32),
    IN p_email VARCHAR(64),
    IN p_password VARCHAR(255),
    IN p_fullname VARCHAR(255),
    IN p_birthday DATE,
    IN p_gender INT,
    IN p_birth_country VARCHAR(32),
    IN p_country VARCHAR(32),
    IN p_avatar LONGBLOB
)
BEGIN
    INSERT INTO Users (
        admin, username, email, password, fullname, birthday, gender, 
        birth_country, country, avatar, status, created_at
    )
    VALUES (
        p_admin, p_username, p_email, p_password, p_fullname, p_birthday, p_gender,
        p_birth_country, p_country, p_avatar, 1, CURRENT_TIMESTAMP
    );
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_create_worldcup` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_worldcup`(
	IN p_name VARCHAR(64),
	IN p_country VARCHAR(32),
	IN p_year INT,
	IN p_description TEXT,
	IN p_banner LONGBLOB,
	IN p_status TINYINT(1)
)
BEGIN
	INSERT INTO WorldCups (name, country, year, description, banner, status)
	VALUES (p_name, p_country, p_year, p_description, p_banner, p_status);
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_delete_category` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_category`(IN p_id INT)
BEGIN
    DECLARE v_exists INT DEFAULT 0;
    DECLARE v_posts_count INT DEFAULT 0;

    -- Verificar que existe
    SELECT COUNT(*) INTO v_exists
      FROM categories
     WHERE id = p_id;

    IF v_exists = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La categoría no existe';
    END IF;

    -- Verificar si tiene posts asociados
    SELECT COUNT(*) INTO v_posts_count
      FROM posts
     WHERE category_id = p_id
       AND status = 1;

    IF v_posts_count > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede eliminar: hay publicaciones asociadas a esta categoría';
    END IF;

    -- Soft delete
    UPDATE categories
       SET status = 0
     WHERE id = p_id;

    -- Confirmar eliminación
    SELECT id, name, status
      FROM categories
     WHERE id = p_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_get_feed` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_feed`(
    IN p_user_id     BIGINT,     -- 0 o NULL => anónimo
    IN p_after_id    BIGINT,     -- cursor: último id recibido; NULL/0 => primera página
    IN p_limit       INT,        -- tamaño de página
    IN p_category_id INT,        -- filtro opcional; NULL/0 => todos
    IN p_worldcup_id INT         -- filtro opcional; NULL/0 => todos
)
BEGIN
    IF p_limit IS NULL OR p_limit <= 0 THEN
        SET p_limit = 10;
    END IF;

    SELECT 
        p.id, p.title, p.team, p.description, p.created_at, p.approved_at,
        u.id AS user_id, u.username, TO_BASE64(u.avatar) AS avatar_b64,
        c.id AS category_id, c.name AS category_name,
        w.id AS worldcup_id, w.name AS worldcup_name, w.year AS worldcup_year,
        TO_BASE64(p.media) AS media_b64,
        (SELECT COUNT(*) FROM PostLikes pl WHERE pl.post_id = p.id AND pl.status = 1) AS likes_count,
        (SELECT COUNT(*) FROM Comments  co WHERE co.post_id = p.id AND co.status = 1) AS comments_count,
        CASE 
            WHEN p_user_id IS NULL OR p_user_id = 0 THEN 0
            ELSE EXISTS(SELECT 1 FROM PostLikes x 
                        WHERE x.post_id = p.id AND x.user_id = p_user_id AND x.status = 1)
        END AS liked_by_me
    FROM Posts p
    JOIN Users      u ON u.id = p.user_id     AND u.status = 1
    JOIN Categories c ON c.id = p.category_id AND c.status = 1
    JOIN WorldCups  w ON w.id = p.worldcup_id AND w.status = 1
    WHERE p.status = 1 
      AND p.approved = 1
      AND (p_after_id    IS NULL OR p_after_id    = 0 OR p.id < p_after_id)
      AND (p_category_id IS NULL OR p_category_id = 0 OR p.category_id = p_category_id)
      AND (p_worldcup_id IS NULL OR p_worldcup_id = 0 OR p.worldcup_id = p_worldcup_id)
      AND (p_user_id     IS NULL OR p_user_id     = 0 OR p.user_id = p_user_id)
    ORDER BY p.id DESC
    LIMIT p_limit;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_get_user_for_login` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_user_for_login`(IN p_identity VARCHAR(64))
BEGIN
    SELECT 
        id, admin, username, email, password, fullname, birthday, gender,
        birth_country, country, status, created_at
    FROM Users
    WHERE (username = p_identity)
      AND status = 1
    LIMIT 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_soft_delete_category` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_soft_delete_category`(IN p_id INT)
BEGIN
    DECLARE v_exists INT DEFAULT 0;

    IF p_id IS NULL OR p_id <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_id inválido';
    END IF;

    SELECT COUNT(*) INTO v_exists
      FROM categories
     WHERE id = p_id;

    IF v_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La categoría no existe';
    END IF;

    -- Soft delete
    UPDATE categories
       SET status = 0
     WHERE id = p_id
       AND status <> 0;

    -- Resumen
    SELECT id, name, status
      FROM categories
     WHERE id = p_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_soft_delete_user` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_soft_delete_user`(IN p_user_id BIGINT)
BEGIN
  DECLARE v_exists INT DEFAULT 0;

  IF p_user_id IS NULL OR p_user_id <= 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_user_id inválido';
  END IF;

  -- ¿Existe y está activo?
  SELECT COUNT(*) INTO v_exists
  FROM Users
  WHERE id = p_user_id AND status = 1;

  IF v_exists = 0 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Usuario no encontrado o ya inactivo';
  END IF;

  START TRANSACTION;

    UPDATE Users
       SET status = 0
     WHERE id = p_user_id
       AND status = 1;

    IF ROW_COUNT() = 0 THEN
      ROLLBACK;
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No se actualizó el usuario (ya estaba inactivo)';
    END IF;

  COMMIT;

  -- Resumen
  SELECT p_user_id AS user_id, 0 AS new_status, 'Usuario dado de baja (soft delete)' AS message;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_update_category` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_category`(
    IN p_id INT,
    IN p_name VARCHAR(32)
)
BEGIN
    DECLARE v_name VARCHAR(32);
    DECLARE v_exists INT DEFAULT 0;

    -- Validar que el ID existe
    SELECT COUNT(*) INTO v_exists
      FROM categories
     WHERE id = p_id;

    IF v_exists = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La categoría no existe';
    END IF;

    -- Normalizar nombre
    SET v_name = TRIM(p_name);

    -- Validar nombre
    IF v_name IS NULL OR v_name = '' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El nombre de la categoría es requerido';
    END IF;

    IF CHAR_LENGTH(v_name) > 32 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El nombre de la categoría excede 32 caracteres';
    END IF;

    -- Verificar duplicados (excluyendo el mismo registro)
    SELECT COUNT(*) INTO v_exists
      FROM categories
     WHERE LOWER(name) = LOWER(v_name)
       AND id != p_id;

    IF v_exists > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Ya existe otra categoría con ese nombre';
    END IF;

    -- Actualizar
    UPDATE categories
       SET name = v_name
     WHERE id = p_id;

    -- Retornar datos actualizados
    SELECT id, name
      FROM categories
     WHERE id = p_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `sp_update_user_profile` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_user_profile`(
    IN p_id BIGINT,
    IN p_fullname VARCHAR(255),
    IN p_username VARCHAR(32),
    IN p_email VARCHAR(64),
    IN p_birthday DATE,
    IN p_gender INT,
    IN p_birth_country VARCHAR(32),
    IN p_country VARCHAR(32),
    IN p_avatar LONGBLOB,
    IN p_password VARCHAR(255)
)
BEGIN
    DECLARE v_exists INT DEFAULT 0;

    SELECT COUNT(*) INTO v_exists
    FROM Users
    WHERE id = p_id AND status = 1;

    IF v_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Usuario no encontrado';
    END IF;

    -- Actualizar solo los campos que no son NULL
    UPDATE Users
    SET
        fullname = COALESCE(p_fullname, fullname),
        username = COALESCE(p_username, username),
        email = COALESCE(p_email, email),
        birthday = COALESCE(p_birthday, birthday),
        gender = COALESCE(p_gender, gender),
        birth_country = COALESCE(p_birth_country, birth_country),
        country = COALESCE(p_country, country),
        avatar = COALESCE(p_avatar, avatar),
        password = COALESCE(p_password, password)
    WHERE id = p_id;

    -- Retornar confirmación
    SELECT 
        id, 
        username, 
        email, 
        fullname,
        'Perfil actualizado correctamente' AS message
    FROM Users
    WHERE id = p_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `v_lista_de_categorias`
--

/*!50001 DROP VIEW IF EXISTS `v_lista_de_categorias`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_lista_de_categorias` AS select `categories`.`id` AS `id`,`categories`.`name` AS `name`,`categories`.`status` AS `status` from `categories` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_lista_de_comentarios`
--

/*!50001 DROP VIEW IF EXISTS `v_lista_de_comentarios`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=MERGE */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY INVOKER */
/*!50001 VIEW `v_lista_de_comentarios` AS select `c`.`id` AS `id`,`c`.`post_id` AS `post_id`,`c`.`user_id` AS `user_id`,`u`.`username` AS `username`,(case when (`u`.`avatar` is null) then NULL else to_base64(`u`.`avatar`) end) AS `avatar_b64`,`c`.`content` AS `content`,`c`.`status` AS `status`,`c`.`created_at` AS `created_at` from (`comments` `c` left join `users` `u` on((`u`.`id` = `c`.`user_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_lista_de_mundiales`
--

/*!50001 DROP VIEW IF EXISTS `v_lista_de_mundiales`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=MERGE */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY INVOKER */
/*!50001 VIEW `v_lista_de_mundiales` AS select `worldcups`.`id` AS `id`,`worldcups`.`name` AS `name`,`worldcups`.`country` AS `country`,`worldcups`.`year` AS `year`,`worldcups`.`description` AS `description`,to_base64(`worldcups`.`banner`) AS `banner_b64`,`worldcups`.`status` AS `status` from `worldcups` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_lista_de_usuarios`
--

/*!50001 DROP VIEW IF EXISTS `v_lista_de_usuarios`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_lista_de_usuarios` AS select `users`.`id` AS `id`,`users`.`admin` AS `admin`,`users`.`username` AS `username`,`users`.`email` AS `email`,`users`.`fullname` AS `fullname`,`users`.`birthday` AS `birthday`,`users`.`gender` AS `gender`,`users`.`birth_country` AS `birth_country`,`users`.`country` AS `country`,`users`.`status` AS `status`,`users`.`created_at` AS `created_at`,to_base64(`users`.`avatar`) AS `avatar_b64` from `users` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_lista_ligera_de_mundiales`
--

/*!50001 DROP VIEW IF EXISTS `v_lista_ligera_de_mundiales`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_lista_ligera_de_mundiales` AS select `worldcups`.`id` AS `id`,`worldcups`.`name` AS `name`,`worldcups`.`country` AS `country`,`worldcups`.`year` AS `year`,`worldcups`.`description` AS `description`,`worldcups`.`status` AS `status` from `worldcups` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_lista_ligera_de_publicaciones`
--

/*!50001 DROP VIEW IF EXISTS `v_lista_ligera_de_publicaciones`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_lista_ligera_de_publicaciones` AS select `p`.`id` AS `id`,`u`.`username` AS `username`,`c`.`name` AS `category_name`,`w`.`name` AS `worldcup_name`,`p`.`title` AS `title`,`p`.`description` AS `description`,(case when (`p`.`media` is null) then NULL else to_base64(`p`.`media`) end) AS `media_b64`,`p`.`created_at` AS `created_at`,`p`.`approved` AS `approved`,`p`.`status` AS `status` from (((`posts` `p` left join `users` `u` on((`u`.`id` = `p`.`user_id`))) left join `categories` `c` on((`c`.`id` = `p`.`category_id`))) left join `worldcups` `w` on((`w`.`id` = `p`.`worldcup_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `v_lista_ligera_de_usuarios`
--

/*!50001 DROP VIEW IF EXISTS `v_lista_ligera_de_usuarios`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_lista_ligera_de_usuarios` AS select `users`.`id` AS `id`,`users`.`username` AS `username`,`users`.`email` AS `email`,`users`.`created_at` AS `created_at`,`users`.`status` AS `status` from `users` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-10  0:49:00
