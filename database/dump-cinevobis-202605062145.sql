/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: cinevobis
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `notifiche`
--

DROP TABLE IF EXISTS `notifiche`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifiche` (
  `id_notifica` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(255) DEFAULT NULL,
  `descrizione` text NOT NULL,
  `data_invio` timestamp NULL DEFAULT NULL,
  `id_utente` int(11) NOT NULL,
  `letta` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_notifica`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `notifiche_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifiche`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `notifiche` WRITE;
/*!40000 ALTER TABLE `notifiche` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifiche` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `preferiti`
--

DROP TABLE IF EXISTS `preferiti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `preferiti` (
  `id_preferito` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_aggiunto` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_preferito`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `preferiti_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preferiti`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `preferiti` WRITE;
/*!40000 ALTER TABLE `preferiti` DISABLE KEYS */;
INSERT INTO `preferiti` VALUES
(10,129,1,'2026-05-05 15:40:57'),
(11,157336,1,'2026-05-05 15:41:13'),
(12,1218925,1,'2026-05-05 15:41:32'),
(13,424,1,'2026-05-05 15:42:05'),
(14,8587,1,'2026-05-05 15:42:29'),
(15,354912,1,'2026-05-05 15:42:58'),
(16,354912,1,'2026-05-05 15:47:18'),
(17,149870,1,'2026-05-05 15:51:43'),
(21,378064,1,'2026-05-06 17:44:32');
/*!40000 ALTER TABLE `preferiti` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `profili`
--

DROP TABLE IF EXISTS `profili`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `profili` (
  `id_profilo` int(11) NOT NULL AUTO_INCREMENT,
  `nome_profilo` varchar(100) NOT NULL,
  PRIMARY KEY (`id_profilo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profili`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `profili` WRITE;
/*!40000 ALTER TABLE `profili` DISABLE KEYS */;
INSERT INTO `profili` VALUES
(1,'Admin'),
(2,'User');
/*!40000 ALTER TABLE `profili` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `recensioni`
--

DROP TABLE IF EXISTS `recensioni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `recensioni` (
  `id_recensione` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_aggiunto` timestamp NULL DEFAULT NULL,
  `commento` text DEFAULT NULL,
  `voto` decimal(3,1) DEFAULT NULL,
  PRIMARY KEY (`id_recensione`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `recensioni_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recensioni`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `recensioni` WRITE;
/*!40000 ALTER TABLE `recensioni` DISABLE KEYS */;
INSERT INTO `recensioni` VALUES
(15,1325734,4,'2026-05-06 19:22:47','wow',8.0),
(27,129,1,'2026-05-06 16:13:19','Il miglior film della Studio Ghibli',9.0);
/*!40000 ALTER TABLE `recensioni` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessioni`
--

DROP TABLE IF EXISTS `sessioni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessioni` (
  `id_sessione` varchar(255) NOT NULL,
  `id_utente` int(11) NOT NULL,
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

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessioni` WRITE;
/*!40000 ALTER TABLE `sessioni` DISABLE KEYS */;
INSERT INTO `sessioni` VALUES
('012fc1cc2ed0c5c279f2feacf76ee38d',1,'2026-04-23 22:01:01','2026-04-23 22:02:42'),
('0e1d91d4d809a99e6b62f3df131269fd',1,'2026-05-06 17:08:15',NULL),
('0e529b764545af08032dea96ef0ca06d',1,'2026-05-06 20:37:38','2026-05-06 21:22:35'),
('0ee152fe15e7e1781873bd894613572e',1,'2026-04-23 21:55:58','2026-04-23 22:00:51'),
('0fd4f9bf5a5185c040cb2edbd08cd7f0',1,'2026-04-22 18:34:40','2026-04-22 19:49:51'),
('1359fd81d27aa15086c80e9accb3a9dd',1,'2026-05-06 14:42:33','2026-05-06 14:44:17'),
('13a5535ac6b8386eb7d80758fa067cb2',1,'2026-05-05 21:13:04',NULL),
('1547627f3983632f5d310998a9856a29',1,'2026-03-30 18:05:10','2026-03-30 18:07:30'),
('155b8653273553129124bf53a371e557',1,'2026-05-05 23:33:01',NULL),
('16571ac889b5cefc8f0bfe7a94dd9847',1,'2026-03-25 18:29:10','2026-03-25 18:39:26'),
('172de2978f96be95c4d6855dc95b204f',1,'2026-03-27 10:09:41','2026-03-27 10:10:11'),
('1926352fe92e82605ffe094800cf4cf9',1,'2026-05-06 14:38:17','2026-05-06 14:39:45'),
('1fd1fc7131a2523f6739c2ec40c2a837',1,'2026-04-23 21:01:52','2026-04-23 21:52:59'),
('22b2d1cc3150f931169e4a623ade2534',1,'2026-04-22 16:09:51','2026-04-22 18:23:03'),
('2368538c591dc3aae3813c783ec6f5f7',1,'2026-04-23 12:38:19',NULL),
('25b7114b729b540a48a53a09d51537e6',1,'2026-05-06 13:28:28','2026-05-06 13:29:16'),
('2ca8233ba8347cb65c6c3ed193295fa3',1,'2026-04-23 20:29:10','2026-04-23 20:29:34'),
('2d145ca6f839d181245d8c2c20a115f6',4,'2026-05-06 21:22:42','2026-05-06 21:22:54'),
('2dbdd57f893b120973f9ea65a7a8cb3b',1,'2026-04-23 22:17:51','2026-04-23 22:23:04'),
('2ea1a94cc4c3fbd5200f07b4a1a7b23d',1,'2026-05-06 13:11:25','2026-05-06 13:24:36'),
('309e992fc63b90ed6b071fe06f26fdaa',1,'2026-04-23 22:49:49','2026-04-23 23:00:31'),
('30d3c15fbb854ef494aaddd7725c241b',1,'2026-05-04 12:58:37',NULL),
('34b8ff74380d40b083f6ec8a00e00cff',1,'2026-04-24 21:10:53','2026-04-24 21:12:42'),
('36f340ec363a7da263d2d10964323caa',1,'2026-04-22 13:12:12','2026-04-22 13:18:41'),
('37f6d09c2d4fab6efcac050f05453353',1,'2026-05-06 12:12:59',NULL),
('387579df4d1d3e5315096c25b84889f0',1,'2026-04-23 23:00:42','2026-04-24 00:45:12'),
('397fb05fc96fc54448a4fdce56f62af6',1,'2026-05-03 19:36:55',NULL),
('3b4f11104ed4cfb95d88f45adb7457fb',1,'2026-05-06 14:54:23',NULL),
('3fbebb040f548d49cf0670fa61c14cca',1,'2026-05-04 10:34:49',NULL),
('410426c9d749dc165bd4aecc351a4c85',14,'2026-04-23 22:17:28','2026-04-23 22:17:45'),
('45c5fdd9888848e3bdb440d69f4f2bc2',6,'2026-03-30 18:28:01','2026-03-30 18:28:06'),
('4d5c2bdb6094d1e47e0e870d02d4e74e',1,'2026-04-24 01:04:45','2026-04-24 01:05:31'),
('4f88bd5ab14f8313947555aee89cf201',1,'2026-04-23 22:02:47','2026-04-23 22:17:03'),
('510d5962e7a90bc72b538c7031eab01d',1,'2026-05-06 13:30:29','2026-05-06 13:35:34'),
('53c8547f1870e95af9513eeb5a10c91c',1,'2026-04-23 22:28:43','2026-04-23 22:39:06'),
('54c289a73c0da47c8a720a5a8bbe60d7',1,'2026-04-24 22:01:45',NULL),
('59ba8e083b31081f8af7eb04361259fc',4,'2026-05-06 14:54:10','2026-05-06 14:54:17'),
('60ec08a365332c873bfe19ec467bda1a',1,'2026-05-03 15:34:15',NULL),
('623fcc0f613dc86bc8f7e5598d753b04',1,'2026-04-24 00:47:10','2026-04-24 01:04:36'),
('683f403372ea1da627cbbd560c07c66d',1,'2026-04-19 18:02:17',NULL),
('688d8e831a13e90c0a9bbf8075b964c0',1,'2026-05-06 15:36:46',NULL),
('688fd3c4f0444932796d55099b6a6a94',4,'2026-05-06 14:39:51',NULL),
('6b789cd4a1dadd1dd412bc89b3a2fe82',4,'2026-05-06 20:13:01','2026-05-06 20:37:32'),
('6bd87857ac81c9d4b9a9114dc1cbd010',1,'2026-04-22 18:31:48','2026-04-22 18:34:28'),
('6ceed541eb8e947df425b2e244a4c5ee',1,'2026-04-23 20:37:06',NULL),
('73b33b898c631f652d41cd2e22e68352',1,'2026-04-17 13:35:17','2026-04-17 13:36:11'),
('75b55e05146627e9ccfc9516e2170efd',1,'2026-05-04 20:25:12',NULL),
('7cd956bc695e9634d123917d93c2ab24',1,'2026-05-05 16:41:50',NULL),
('85b348526adf4bbe32ca46dab7a8a8f2',1,'2026-05-05 08:20:37','2026-05-05 11:50:58'),
('8728ce84b027b0afd50bf431e2d7d22a',1,'2026-03-25 19:24:57','2026-03-25 19:26:40'),
('8818b6146ae08bf95105f76af08531b6',4,'2026-05-06 14:35:44','2026-05-06 14:38:07'),
('8a63c25dd055a536dfd64cacab38ec91',1,'2026-05-05 22:44:22',NULL),
('8a967e7e0ab9c9770c00df37d77a30ee',1,'2026-04-29 13:20:32','2026-04-29 13:30:27'),
('8c09f5a44105f042d6807b1f8170e30b',1,'2026-04-24 21:22:47','2026-04-24 21:43:02'),
('8d9feef2c70ea2ca8401cc2044b15ff1',1,'2026-04-18 12:43:54','2026-04-18 12:44:18'),
('8fb61c569c3b82288f5b463ebe4a1c1e',1,'2026-03-30 18:28:12','2026-03-30 18:34:00'),
('92501a7ab73b24ee226685fec0220dd4',1,'2026-03-30 18:27:37','2026-03-30 18:27:45'),
('93ab1a4cd70fbbb540a6961a043770ac',1,'2026-04-24 13:02:49','2026-04-24 16:49:22'),
('94f668a246d2ef0397dfb210015b3eb5',1,'2026-04-27 20:36:14',NULL),
('960b32e69df31b01bf9a1af546693cd8',1,'2026-03-27 10:03:00','2026-03-27 10:04:57'),
('96b466621d7b9a064c8306d9f831c3a3',1,'2026-05-06 14:44:22','2026-05-06 14:44:28'),
('9f34f3cc99a5de0598d983529fc72f1c',1,'2026-05-06 14:46:42','2026-05-06 14:54:02'),
('9f3fa601096c26b4994ecb4a3c8306a8',1,'2026-03-25 18:23:48',NULL),
('a477f9ef5225a29f395fe3fc0e596464',1,'2026-03-25 18:20:29','2026-03-25 18:22:05'),
('a70cd906540d07384d78ba1780eddc54',1,'2026-04-23 19:50:22','2026-04-23 20:23:47'),
('ab7e66cf1dc700fe16e701ca9e9f731e',1,'2026-04-24 21:50:10','2026-04-24 21:53:27'),
('ad303b4f901d7cca28a033851c643eb4',1,'2026-05-06 16:02:02',NULL),
('afc3793009a9452203ed30b9cffef280',1,'2026-05-06 17:04:03','2026-05-06 17:05:08'),
('b33fef4a591f0b6979622c98ae8274de',1,'2026-05-06 17:38:00',NULL),
('b5626e2b600011ca8d582e7d88a92eab',1,'2026-04-15 22:09:09','2026-04-15 22:10:08'),
('ba045843a6ca718e155b1d2f69987e40',4,'2026-05-06 21:26:28','2026-05-06 21:41:39'),
('bc43366d58b172cff0a4336ba922093d',1,'2026-03-25 18:22:14','2026-03-25 18:22:48'),
('bc76cfd9f3cb5e4ab157efef871b68f9',1,'2026-03-25 23:17:10',NULL),
('c1ccc60484d0dbbbaf193fee97820160',4,'2026-05-06 14:44:34','2026-05-06 14:46:35'),
('c563789bdb6efd1d2bbc6a6e89c40859',1,'2026-04-23 22:25:32',NULL),
('c56f7c4c4e05c5c2ddc85b9b41eddd78',1,'2026-04-29 12:17:11','2026-04-29 12:57:21'),
('c737a4825aae7aaea1fcb195aa8ca369',1,'2026-05-06 21:22:58',NULL),
('cdb47ad9e943a8c87d1df62704c1d036',1,'2026-05-06 17:09:06',NULL),
('d1609122ea68f5067cd3ccdf7520b77e',1,'2026-04-29 16:09:22','2026-04-29 16:17:02'),
('d42cd31c43e38aa6ef2e3f24175e75a3',1,'2026-05-06 17:48:57',NULL),
('d6775379a49cbd1e359c35f0dd53d6fb',1,'2026-04-29 16:55:02','2026-04-29 17:21:06'),
('d7d83f3a5d9ae973bac6db319f7eed4b',1,'2026-04-19 16:37:05',NULL),
('d96ab62ed7351c68427a68307b6ed134',4,'2026-05-06 13:35:57','2026-05-06 13:36:50'),
('da3f07ec30e02ad0d9b7d19f80b195a4',1,'2026-03-25 19:26:56',NULL),
('de211e890f489a173ead66ef61cf8b0d',1,'2026-04-29 16:18:55','2026-04-29 16:27:56'),
('eb96335cb7140a9e3e282af7baf30085',1,'2026-05-05 11:51:07',NULL),
('f2c98e62d02ae5874cff68c3aeb49465',1,'2026-03-27 09:35:22','2026-03-27 09:35:28'),
('f37460f6c45627f06a209bd7b2d20159',1,'2026-05-05 23:26:36',NULL),
('f404018aacf436676f3d15a1ea30da5e',1,'2026-03-30 18:23:39','2026-03-30 18:27:27'),
('f75e4fb63aae159b3fab4008dab53681',1,'2026-05-05 19:00:56',NULL),
('f87c8ae11c66d00f8525479898a96c1d',1,'2026-05-06 18:30:30','2026-05-06 20:12:52'),
('fc62965b2147501d2a088dfe60bd4b1d',1,'2026-04-19 16:26:02','2026-04-19 16:37:00');
/*!40000 ALTER TABLE `sessioni` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `utenti`
--

DROP TABLE IF EXISTS `utenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `utenti` (
  `id_utente` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `id_profilo` int(11) DEFAULT NULL,
  `attivo` tinyint(1) DEFAULT 1,
  `data_registrazione` datetime DEFAULT NULL,
  PRIMARY KEY (`id_utente`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `id_profilo` (`id_profilo`),
  CONSTRAINT `utenti_ibfk_1` FOREIGN KEY (`id_profilo`) REFERENCES `profili` (`id_profilo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
INSERT INTO `utenti` VALUES
(1,'alessio','$2y$12$EHgIq7zfo21uXKRqaYYgGOcephY/oaJIRrD8M4XYybUxsPRL9c7wG','alessio.gualtieri@gmail.com','Alessio','Gualtieri',1,1,'2026-03-25 17:19:40'),
(4,'giulia.verdi','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','giulia.v@mail.it','Giulia','Verdi',2,1,'2026-03-25 18:22:28'),
(5,'john.smith','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','jsmith@mail.com','John','Smith',2,1,'2026-03-25 18:22:28'),
(6,'emma.watson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','ewatson@mail.co.uk','Emma','Watson',2,1,'2026-03-25 18:22:28'),
(7,'pierre.dupont','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','pierre@mail.fr','Pierre','Dupont',2,1,'2026-03-25 18:22:28'),
(8,'hans.muller','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','hans@mail.de','Hans','Muller',2,1,'2026-03-25 18:22:28'),
(9,'carlos.santana','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','carlos@mail.es','Carlos','Santana',2,0,'2026-03-25 18:22:28'),
(10,'kenji.sato','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','kenji@mail.jp','Kenji','Sato',2,1,'2026-03-25 18:22:28'),
(11,'min.ji','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','minji@mail.kr','Min','Ji',2,0,'2026-03-25 18:22:28'),
(12,'chen.wei','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','chen@mail.cn','Chen','Wei',2,1,'2026-03-25 18:22:28'),
(13,'olivia.brown','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','olivia@mail.ca','Olivia','Brown',2,1,'2026-03-25 18:22:28'),
(14,'liam.neeson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','liam.n@mail.ie','Liam','Neeson',2,1,'2026-03-25 18:22:28'),
(16,'joao.silva','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','joao@mail.br','Joao','Silva',2,1,'2026-03-25 18:22:28'),
(19,'peter.jackson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','peter@mail.nz','Peter','Jackson',2,1,'2026-03-25 18:22:28'),
(23,'charlize.theron','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','charlize@mail.za','Charlize','Theron',2,1,'2026-03-25 18:22:28'),
(24,'sofia.gomez','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sofia@mail.co','Sofia','Gomez',2,1,'2026-03-25 18:22:28'),
(25,'andrea.romano','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','andrea@mail.it','Andrea','Romano',2,1,'2026-03-25 18:22:28'),
(26,'marta.ferrari','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','marta@mail.it','Marta','Ferrari',2,1,'2026-03-25 18:22:28'),
(27,'william.wallace','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','william@mail.gb','William','Wallace',2,1,'2026-03-25 18:22:28'),
(28,'elena.kournikova','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','elena@mail.ru','Elena','Kournikova',2,1,'2026-03-25 18:22:28'),
(29,'david.beckham','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','david@mail.gb','David','Beckham',2,1,'2026-03-25 18:22:28'),
(30,'yuki.tsunoda','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','yuki@mail.jp','Yuki','Tsunoda',2,1,'2026-03-25 18:22:28'),
(32,'sarah.connor','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sarah@mail.us','Sarah','Connor',2,1,'2026-03-25 18:22:28'),
(33,'bruce.wayne','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','bruce@mail.us','Bruce','Wayne',2,1,'2026-03-25 18:22:28'),
(34,'clark.kent','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','clark@mail.us','Clark','Kent',2,1,'2026-03-25 18:22:28'),
(35,'diana.prince','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','diana@mail.us','Diana','Prince',2,1,'2026-03-25 18:22:28'),
(36,'peter.parker','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','peter.p@mail.us','Peter','Parker',2,1,'2026-03-25 18:22:28'),
(37,'tony.stark','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','tony@mail.us','Tony','Stark',2,1,'2026-03-25 18:22:28'),
(38,'steve.rogers','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','steve@mail.us','Steve','Rogers',2,1,'2026-03-25 18:22:28'),
(39,'natasha.romanoff','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','natasha@mail.ru','Natasha','Romanoff',2,1,'2026-03-25 18:22:28'),
(40,'wanda.maximoff','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','wanda@mail.rs','Wanda','Maximoff',2,1,'2026-03-25 18:22:28'),
(41,'stephen.strange','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','stephen@mail.us','Stephen','Strange',2,1,'2026-03-25 18:22:28'),
(42,'thor.odinson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','thor@mail.no','Thor','Odinson',2,0,'2026-03-25 18:22:28'),
(43,'loki.laufeyson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','loki@mail.no','Loki','Laufeyson',2,1,'2026-03-25 18:22:28'),
(44,'bruce.banner','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','banner@mail.us','Bruce','Banner',2,1,'2026-03-25 18:22:28'),
(45,'clint.barton','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','clint@mail.us','Clint','Barton',2,1,'2026-03-25 18:22:28'),
(46,'sam.wilson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sam@mail.us','Sam','Wilson',2,0,'2026-03-25 18:22:28'),
(48,'scott.lang','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','scott@mail.us','Scott','Lang',2,1,'2026-03-25 18:22:28'),
(49,'hope.vandyne','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','hope@mail.us','Hope','Van Dyne',2,1,'2026-03-25 18:22:28'),
(50,'carol.danvers','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','carol@mail.us','Carol','Danvers',2,0,'2026-03-25 18:22:28');
/*!40000 ALTER TABLE `utenti` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `watched`
--

DROP TABLE IF EXISTS `watched`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `watched` (
  `id_watched` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_aggiunto` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_watched`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `watched_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `watched`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `watched` WRITE;
/*!40000 ALTER TABLE `watched` DISABLE KEYS */;
INSERT INTO `watched` VALUES
(11,129,1,'2026-05-06 15:39:37'),
(14,1325734,4,'2026-05-06 19:22:47');
/*!40000 ALTER TABLE `watched` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `watchlist`
--

DROP TABLE IF EXISTS `watchlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `watchlist` (
  `id_watchlist` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_aggiunto` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_watchlist`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `watchlist`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `watchlist` WRITE;
/*!40000 ALTER TABLE `watchlist` DISABLE KEYS */;
INSERT INTO `watchlist` VALUES
(3,858024,1,'2026-05-05 21:06:24'),
(4,1214931,1,'2026-05-05 21:06:29'),
(6,687163,1,'2026-05-05 21:06:36'),
(8,1226863,1,'2026-05-05 21:06:42'),
(9,936075,1,'2026-05-05 21:06:45'),
(10,1084242,1,'2026-05-05 21:06:58'),
(12,1226863,4,'2026-05-06 12:45:25'),
(13,1325734,4,'2026-05-06 12:46:14'),
(14,687163,4,'2026-05-06 12:46:17'),
(15,149870,1,'2026-05-06 13:53:07');
/*!40000 ALTER TABLE `watchlist` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

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
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-05-06 21:45:56
