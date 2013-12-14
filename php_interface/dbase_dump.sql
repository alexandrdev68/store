CREATE DATABASE  IF NOT EXISTS `store` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `store`;
-- MySQL dump 10.13  Distrib 5.6.14, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: store
-- ------------------------------------------------------
-- Server version	5.6.14-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `engine_logs`
--

DROP TABLE IF EXISTS `engine_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `engine_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_date` datetime DEFAULT NULL,
  `operation` varchar(512) DEFAULT '',
  `request` varchar(2048) DEFAULT '',
  `response` varchar(4096) DEFAULT '',
  `status` varchar(45) DEFAULT '',
  `message` varchar(256) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='сюда записываются все неверные результаты запросов и исключения';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `engine_logs`
--

LOCK TABLES `engine_logs` WRITE;
/*!40000 ALTER TABLE `engine_logs` DISABLE KEYS */;
INSERT INTO `engine_logs` VALUES (1,'2013-12-10 21:47:54','','','','','користувач з login 224234не знайдено'),(2,'2013-12-10 21:55:17','','','','','користувач з login 224234не знайдено'),(3,'2013-12-10 22:15:18','','','','','користувач з login 224234не знайдено'),(4,'2013-12-10 22:18:32','','','','','користувач з login 224234 не знайдено'),(5,'2013-12-10 22:25:47','','','','','користувач з login root успішно авторизовано'),(6,'2013-12-10 22:33:11','','','','','користувач з login root успішно авторизовано'),(7,'2013-12-10 22:43:09','','','','','користувач з login root успішно авторизовано'),(8,'2013-12-10 22:46:02','','','','','користувач з login root успішно авторизовано'),(9,'2013-12-11 21:29:39','','','','','користувач з login 34234 не знайдено'),(10,'2013-12-11 21:29:51','','','','','користувач з login 34234 не знайдено'),(11,'2013-12-11 21:32:45','','','','','користувач з login 324234234 не знайдено'),(12,'2013-12-11 21:33:09','','','','','користувач з login 324234234 не знайдено'),(13,'2013-12-11 21:38:57','','','','','користувач з login root успішно авторизовано'),(14,'2013-12-11 21:57:45','','','','','користувач з login 23434234 не знайдено'),(15,'2013-12-11 22:00:30','','','','','користувач з login root успішно авторизовано'),(16,'2013-12-11 22:14:36','','','','','користувач з login root успішно авторизовано'),(17,'2013-12-11 23:30:29','','','','','користувач з login eqweqwe не знайдено'),(18,'2013-12-12 22:01:11','','','','','користувач з login root успішно авторизовано'),(19,'2013-12-14 12:54:38','','','','','користувач з login root успішно авторизовано');
/*!40000 ALTER TABLE `engine_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goods`
--

DROP TABLE IF EXISTS `goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goods` (
  `id_goods` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `discount` int(11) NOT NULL DEFAULT '0',
  `measure` varchar(45) DEFAULT NULL COMMENT 'единица измерения товара (кг. м. см. и т.д.)',
  `properties` text,
  `quantity` float NOT NULL DEFAULT '0',
  `manufacture_date` int(11) DEFAULT NULL COMMENT 'дата изготовления',
  `expiration_period` int(11) DEFAULT NULL COMMENT 'срок годности',
  PRIMARY KEY (`id_goods`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goods`
--

LOCK TABLES `goods` WRITE;
/*!40000 ALTER TABLE `goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process`
--

DROP TABLE IF EXISTS `process`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `process` (
  `id_process` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` int(11) DEFAULT NULL,
  `end_date` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `retail_item_id` int(11) DEFAULT NULL,
  `transport_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `properties` text,
  PRIMARY KEY (`id_process`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='в таблицу записываются все операции с товаром и не только (приход расход, передвижение их точки в точку)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process`
--

LOCK TABLES `process` WRITE;
/*!40000 ALTER TABLE `process` DISABLE KEYS */;
/*!40000 ALTER TABLE `process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retail_items`
--

DROP TABLE IF EXISTS `retail_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `retail_items` (
  `id_retail_items` int(11) NOT NULL AUTO_INCREMENT,
  `adress` varchar(128) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `properties` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_retail_items`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retail_items`
--

LOCK TABLES `retail_items` WRITE;
/*!40000 ALTER TABLE `retail_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `retail_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transport`
--

DROP TABLE IF EXISTS `transport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `type` int(11) DEFAULT '1',
  `properties` text,
  `number` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transport`
--

LOCK TABLES `transport` WRITE;
/*!40000 ALTER TABLE `transport` DISABLE KEYS */;
/*!40000 ALTER TABLE `transport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT '',
  `patronymic` varchar(45) DEFAULT '',
  `surname` varchar(45) DEFAULT '',
  `login` varchar(25) DEFAULT '',
  `password` varchar(512) DEFAULT NULL,
  `photo` varchar(512) DEFAULT '',
  `user_level` int(1) unsigned DEFAULT '2' COMMENT '552071- admin\\\\n1 - reception\\\\n2- user\\n3-klient',
  `properties` text,
  `email` varchar(45) DEFAULT '',
  `phone` varchar(45) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Олександр','Борисов','Володимирович','root','$6$rounds=5000$auDrKYuLxAnvjYCn$BxPU70/snKCHLyg8iw3qlOk4e2h7vP/YLb6.l5pC.fuUxio/X4CsCQqyy81ejF3ikHAOmEbeIURVnfJv2sRT00','',552071,NULL,'','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'store'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-12-14 20:22:37
