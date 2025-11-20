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

-- Dump completed on 2025-11-10 13:34:25
