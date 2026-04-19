-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: cinevobis
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `profili`
--

DROP TABLE IF EXISTS `profili`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profili` (
  `id_profilo` int NOT NULL AUTO_INCREMENT,
  `nome_profilo` varchar(100) NOT NULL,
  PRIMARY KEY (`id_profilo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profili`
--

LOCK TABLES `profili` WRITE;
/*!40000 ALTER TABLE `profili` DISABLE KEYS */;
INSERT INTO `profili` VALUES (1,'Admin'),(2,'User');
/*!40000 ALTER TABLE `profili` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessioni`
--

DROP TABLE IF EXISTS `sessioni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessioni` (
  `id_sessione` varchar(255) NOT NULL,
  `id_utente` int NOT NULL,
  `data_login` datetime DEFAULT NULL,
  `data_logout` datetime DEFAULT NULL,
  PRIMARY KEY (`id_sessione`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `sessioni_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessioni`
--

LOCK TABLES `sessioni` WRITE;
/*!40000 ALTER TABLE `sessioni` DISABLE KEYS */;
INSERT INTO `sessioni` VALUES ('0eb1ac60d8626eb1cf8045519e9444c0',2,'2026-03-27 09:45:10','2026-03-27 10:02:54'),('1427dbbb647869250dff50346ccebf67',2,'2026-03-25 19:22:08','2026-03-25 19:24:39'),('1547627f3983632f5d310998a9856a29',1,'2026-03-30 18:05:10','2026-03-30 18:07:30'),('16571ac889b5cefc8f0bfe7a94dd9847',1,'2026-03-25 18:29:10','2026-03-25 18:39:26'),('172de2978f96be95c4d6855dc95b204f',1,'2026-03-27 10:09:41','2026-03-27 10:10:11'),('3b41f7da090d28ff20a5c453c2226d13',2,'2026-03-27 09:40:44',NULL),('3e2de35bf38901d714e9cb2fc04ed095',51,'2026-03-31 12:29:15','2026-03-31 13:37:09'),('45c5fdd9888848e3bdb440d69f4f2bc2',6,'2026-03-30 18:28:01','2026-03-30 18:28:06'),('465e3238ffc522b2805a9ba74c1c99f6',2,'2026-03-25 18:41:39',NULL),('4b960c31d175a7419ef1f0c5273ef1a5',2,'2026-03-25 23:16:55','2026-03-25 23:16:59'),('5b7bda9b350fea0ba30093c3acfd1ff8',2,'2026-04-01 17:21:37','2026-04-01 17:22:30'),('5c148b6cd691e7ebbf83e58b1679ed9f',2,'2026-03-27 09:41:04','2026-03-27 09:42:54'),('683f403372ea1da627cbbd560c07c66d',1,'2026-04-19 18:02:17',NULL),('709454eaa25c661910727039320da64b',2,'2026-03-31 12:26:43','2026-03-31 12:28:11'),('73b33b898c631f652d41cd2e22e68352',1,'2026-04-17 13:35:17','2026-04-17 13:36:11'),('7ce18c445789eee0f5a4b2063b518698',2,'2026-03-25 18:41:28','2026-03-25 18:41:32'),('7d072893aa02a39c3c1ae38744895b0e',2,'2026-03-30 12:41:20','2026-03-30 12:41:34'),('820740028b1527d493648cc99d077d04',2,'2026-04-01 17:23:36','2026-04-01 17:24:35'),('86a3dbd4354229c3c5b737110fa25798',2,'2026-03-25 19:30:24','2026-03-25 19:30:32'),('8728ce84b027b0afd50bf431e2d7d22a',1,'2026-03-25 19:24:57','2026-03-25 19:26:40'),('8d9feef2c70ea2ca8401cc2044b15ff1',1,'2026-04-18 12:43:54','2026-04-18 12:44:18'),('8fb61c569c3b82288f5b463ebe4a1c1e',1,'2026-03-30 18:28:12','2026-03-30 18:34:00'),('92501a7ab73b24ee226685fec0220dd4',1,'2026-03-30 18:27:37','2026-03-30 18:27:45'),('960b32e69df31b01bf9a1af546693cd8',1,'2026-03-27 10:03:00','2026-03-27 10:04:57'),('97b43f406afb71382604cd0cfc835891',2,'2026-04-18 13:07:27','2026-04-18 14:22:48'),('987337052542c7c8e22c43d8737a8c1b',2,'2026-04-12 16:29:50','2026-04-12 17:10:29'),('9b0f43f71716e12b82dda89289bdd92f',2,'2026-04-01 17:37:59',NULL),('9cd52691f6fbdff73753189147d53145',2,'2026-04-18 12:30:23','2026-04-18 12:43:45'),('9f3fa601096c26b4994ecb4a3c8306a8',1,'2026-03-25 18:23:48',NULL),('a1ebfa835dc8ba99a5eafec171c353ee',2,'2026-04-17 08:27:12','2026-04-17 08:27:16'),('a477f9ef5225a29f395fe3fc0e596464',1,'2026-03-25 18:20:29','2026-03-25 18:22:05'),('a50e619319739be40ad1ae087bb078d1',2,'2026-03-27 09:43:01','2026-03-27 09:45:02'),('a828bcb8e90b23e01d3419845f16c477',2,'2026-03-28 12:58:08',NULL),('b2aaaaee1367be421908b1b2e875b382',2,'2026-04-15 22:10:16','2026-04-15 22:10:21'),('b5626e2b600011ca8d582e7d88a92eab',1,'2026-04-15 22:09:09','2026-04-15 22:10:08'),('b78ea2c983abf8f55dfb0b1187a50f66',2,'2026-04-18 12:45:39','2026-04-18 12:58:44'),('bc43366d58b172cff0a4336ba922093d',1,'2026-03-25 18:22:14','2026-03-25 18:22:48'),('bc76cfd9f3cb5e4ab157efef871b68f9',1,'2026-03-25 23:17:10',NULL),('c10e102ab9185ad8a1f85f4c6108624b',51,'2026-03-31 12:28:21','2026-03-31 12:29:03'),('c67edbcb4a36cb08c32c4408f8fbba29',2,'2026-03-27 09:35:34','2026-03-27 09:40:10'),('d14eed9bbfcca0519cfc673d405a5dc4',2,'2026-03-25 19:40:09','2026-03-25 19:53:23'),('d7d83f3a5d9ae973bac6db319f7eed4b',1,'2026-04-19 16:37:05',NULL),('da3f07ec30e02ad0d9b7d19f80b195a4',1,'2026-03-25 19:26:56',NULL),('eebe2d92da980ef2c211163560e52b73',2,'2026-03-30 18:07:42','2026-03-30 18:23:30'),('f1a91c5a90e884132d4ec1ffcf5e3aec',2,'2026-04-18 10:05:15','2026-04-18 10:34:44'),('f2c98e62d02ae5874cff68c3aeb49465',1,'2026-03-27 09:35:22','2026-03-27 09:35:28'),('f3ac2e2d613b3c0b4cd70735e8624455',2,'2026-04-18 10:50:26','2026-04-18 12:19:33'),('f404018aacf436676f3d15a1ea30da5e',1,'2026-03-30 18:23:39','2026-03-30 18:27:27'),('f4b0a35795f920f27ef1b833a4ccc75c',2,'2026-04-18 13:06:23','2026-04-18 13:06:41'),('fb127c43b77f7b43dbca3ccb20be7b0d',2,'2026-03-31 12:26:28','2026-03-31 12:26:34'),('fc62965b2147501d2a088dfe60bd4b1d',1,'2026-04-19 16:26:02','2026-04-19 16:37:00'),('fe452417214d19681992ec7a7cad7de7',2,'2026-03-30 18:04:06','2026-03-30 18:04:58');
/*!40000 ALTER TABLE `sessioni` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utenti`
--

DROP TABLE IF EXISTS `utenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utenti` (
  `id_utente` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `id_profilo` int DEFAULT NULL,
  `attivo` tinyint(1) DEFAULT '1',
  `data_registrazione` datetime DEFAULT NULL,
  PRIMARY KEY (`id_utente`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `id_profilo` (`id_profilo`),
  CONSTRAINT `utenti_ibfk_1` FOREIGN KEY (`id_profilo`) REFERENCES `profili` (`id_profilo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
INSERT INTO `utenti` VALUES (1,'alessio','$2y$12$EHgIq7zfo21uXKRqaYYgGOcephY/oaJIRrD8M4XYybUxsPRL9c7wG','alessio@gmail.com','Alessio','Gualtieri',1,1,'2026-03-25 17:19:40'),(2,'mario.rossi','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','mario.rossi@mail.it','Mario','Rossi',2,1,'2026-03-25 18:22:28'),(3,'luca.bianchi','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','luca.b@mail.it','Luca','Bianchi',2,1,'2026-03-25 18:22:28'),(4,'giulia.verdi','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','giulia.v@mail.it','Giulia','Verdi',2,1,'2026-03-25 18:22:28'),(5,'john.smith','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','jsmith@mail.com','John','Smith',2,1,'2026-03-25 18:22:28'),(6,'emma.watson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','ewatson@mail.co.uk','Emma','Watson',2,1,'2026-03-25 18:22:28'),(7,'pierre.dupont','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','pierre@mail.fr','Pierre','Dupont',2,1,'2026-03-25 18:22:28'),(8,'hans.muller','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','hans@mail.de','Hans','Muller',2,1,'2026-03-25 18:22:28'),(9,'carlos.santana','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','carlos@mail.es','Carlos','Santana',2,1,'2026-03-25 18:22:28'),(10,'kenji.sato','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','kenji@mail.jp','Kenji','Sato',2,1,'2026-03-25 18:22:28'),(11,'min.ji','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','minji@mail.kr','Min','Ji',2,1,'2026-03-25 18:22:28'),(12,'chen.wei','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','chen@mail.cn','Chen','Wei',2,1,'2026-03-25 18:22:28'),(13,'olivia.brown','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','olivia@mail.ca','Olivia','Brown',2,1,'2026-03-25 18:22:28'),(14,'liam.neeson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','liam.n@mail.ie','Liam','Neeson',2,1,'2026-03-25 18:22:28'),(15,'maria.garcia','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','maria@mail.mx','Maria','Garcia',2,1,'2026-03-25 18:22:28'),(16,'joao.silva','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','joao@mail.br','Joao','Silva',2,1,'2026-03-25 18:22:28'),(17,'diego.maradona','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','diego@mail.ar','Diego','Maradona',2,0,'2026-03-25 18:22:28'),(18,'lars.svensson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','lars@mail.se','Lars','Svensson',2,1,'2026-03-25 18:22:28'),(19,'peter.jackson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','peter@mail.nz','Peter','Jackson',2,1,'2026-03-25 18:22:28'),(20,'raj.patel','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','raj@mail.in','Raj','Patel',2,1,'2026-03-25 18:22:28'),(21,'ivan.drago','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','ivan@mail.ru','Ivan','Drago',2,1,'2026-03-25 18:22:28'),(22,'ahmed.ali','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','ahmed@mail.eg','Ahmed','Ali',2,1,'2026-03-25 18:22:28'),(23,'charlize.theron','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','charlize@mail.za','Charlize','Theron',2,1,'2026-03-25 18:22:28'),(24,'sofia.gomez','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sofia@mail.co','Sofia','Gomez',2,1,'2026-03-25 18:22:28'),(25,'andrea.romano','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','andrea@mail.it','Andrea','Romano',2,1,'2026-03-25 18:22:28'),(26,'marta.ferrari','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','marta@mail.it','Marta','Ferrari',2,1,'2026-03-25 18:22:28'),(27,'william.wallace','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','william@mail.gb','William','Wallace',2,1,'2026-03-25 18:22:28'),(28,'elena.kournikova','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','elena@mail.ru','Elena','Kournikova',2,1,'2026-03-25 18:22:28'),(29,'david.beckham','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','david@mail.gb','David','Beckham',2,1,'2026-03-25 18:22:28'),(30,'yuki.tsunoda','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','yuki@mail.jp','Yuki','Tsunoda',2,1,'2026-03-25 18:22:28'),(31,'alessandro.delpiero','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','alex@mail.it','Alessandro','Del Piero',2,0,'2026-03-25 18:22:28'),(32,'sarah.connor','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sarah@mail.us','Sarah','Connor',2,1,'2026-03-25 18:22:28'),(33,'bruce.wayne','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','bruce@mail.us','Bruce','Wayne',2,1,'2026-03-25 18:22:28'),(34,'clark.kent','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','clark@mail.us','Clark','Kent',2,1,'2026-03-25 18:22:28'),(35,'diana.prince','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','diana@mail.us','Diana','Prince',2,1,'2026-03-25 18:22:28'),(36,'peter.parker','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','peter.p@mail.us','Peter','Parker',2,1,'2026-03-25 18:22:28'),(37,'tony.stark','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','tony@mail.us','Tony','Stark',2,1,'2026-03-25 18:22:28'),(38,'steve.rogers','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','steve@mail.us','Steve','Rogers',2,1,'2026-03-25 18:22:28'),(39,'natasha.romanoff','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','natasha@mail.ru','Natasha','Romanoff',2,1,'2026-03-25 18:22:28'),(40,'wanda.maximoff','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','wanda@mail.rs','Wanda','Maximoff',2,1,'2026-03-25 18:22:28'),(41,'stephen.strange','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','stephen@mail.us','Stephen','Strange',2,1,'2026-03-25 18:22:28'),(42,'thor.odinson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','thor@mail.no','Thor','Odinson',2,0,'2026-03-25 18:22:28'),(43,'loki.laufeyson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','loki@mail.no','Loki','Laufeyson',2,1,'2026-03-25 18:22:28'),(44,'bruce.banner','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','banner@mail.us','Bruce','Banner',2,1,'2026-03-25 18:22:28'),(45,'clint.barton','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','clint@mail.us','Clint','Barton',2,1,'2026-03-25 18:22:28'),(46,'sam.wilson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sam@mail.us','Sam','Wilson',2,1,'2026-03-25 18:22:28'),(47,'bucky.barnes','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','bucky@mail.us','Bucky','Barnes',2,1,'2026-03-25 18:22:28'),(48,'scott.lang','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','scott@mail.us','Scott','Lang',2,1,'2026-03-25 18:22:28'),(49,'hope.vandyne','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','hope@mail.us','Hope','Van Dyne',2,1,'2026-03-25 18:22:28'),(50,'carol.danvers','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','carol@mail.us','Carol','Danvers',2,1,'2026-03-25 18:22:28'),(51,'dilin','$2y$12$1Wl7W4AKNMAa/6k9p3DttOJKICOPgtE0spDV6JP0dYbt45GwFOE/q','dydy16@example.com','dydy','palladain',2,1,'2026-03-31 10:25:42');
/*!40000 ALTER TABLE `utenti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'cinevobis'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-19 18:03:05
