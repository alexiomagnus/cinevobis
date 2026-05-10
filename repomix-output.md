This file is a merged representation of a subset of the codebase, containing files not matching ignore patterns, combined into a single document by Repomix.

# File Summary

## Purpose
This file contains a packed representation of a subset of the repository's contents that is considered the most important context.
It is designed to be easily consumable by AI systems for analysis, code review,
or other automated processes.

## File Format
The content is organized as follows:
1. This summary section
2. Repository information
3. Directory structure
4. Repository files (if enabled)
5. Multiple file entries, each consisting of:
  a. A header with the file path (## File: path/to/file)
  b. The full contents of the file in a code block

## Usage Guidelines
- This file should be treated as read-only. Any changes should be made to the
  original repository files, not this packed version.
- When processing this file, use the file path to distinguish
  between different files in the repository.
- Be aware that this file may contain sensitive information. Handle it with
  the same level of security as you would the original repository.

## Notes
- Some files may have been excluded based on .gitignore rules and Repomix's configuration
- Binary files are not included in this packed representation. Please refer to the Repository Structure section for a complete list of file paths, including binary files
- Files matching these patterns are excluded: README.md
- Files matching patterns in .gitignore are excluded
- Files matching default ignore patterns are excluded
- Files are sorted by Git change count (files with more changes are at the bottom)

# Directory Structure
```
actions/
  change_password.php
  contact.php
  logout.php
assets/
  css/
    style.css
  img/
    astronaut.jpeg
    breakingbad.jpeg
    interstellar.jpg
  js/
    script.js
config/
  config.php
  connection.php
  functions.php
database/
  credentials.md
  dump-cinevobis.sql
includes/
  footer.php
  header_logic.php
  header.php
  movie_obj.php
  user_obj.php
pages/
  admin/
    dashboard.php
    edit_user.php
    film_db.php
    films.php
    notifications.php
    sessions.php
    users.php
  public/
    community_reviews.php
    film.php
    genres.php
    login.php
    notice_board.php
    privacy.php
    recommended_films.php
    search_genre.php
    search.php
    signup.php
    terms.php
    top_films.php
  user/
    favorites.php
    profile.php
    review.php
    reviews.php
    watched.php
    watchlist.php
.gitignore
composer.json
index.php
package.json
```

# Files

## File: database/dump-cinevobis.sql
```sql
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
  `id_utente` int(11) DEFAULT NULL,
  `letta` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_notifica`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `notifiche_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preferiti`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `preferiti` WRITE;
/*!40000 ALTER TABLE `preferiti` DISABLE KEYS */;
INSERT INTO `preferiti` VALUES
(2,424,1,'2026-05-06 19:55:02'),
(3,157336,1,'2026-05-06 19:55:10'),
(6,207,1,'2026-05-06 19:56:36'),
(7,149870,1,'2026-05-06 19:56:50'),
(8,1266127,5,'2026-05-08 19:07:58'),
(9,936075,5,'2026-05-08 19:10:16'),
(11,687163,13,'2026-05-08 19:20:08'),
(13,269149,4,'2026-05-08 19:26:05'),
(14,1226863,4,'2026-05-08 19:26:55'),
(17,155,1,'2026-05-08 22:12:52'),
(21,129,10,'2026-05-09 18:55:02'),
(25,129,1,'2026-05-10 10:27:22');
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
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recensioni`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `recensioni` WRITE;
/*!40000 ALTER TABLE `recensioni` DISABLE KEYS */;
INSERT INTO `recensioni` VALUES
(15,1325734,4,'2026-05-08 19:25:27','Davvero un grande film',8.0),
(27,129,1,'2026-05-06 16:13:19','Il miglior film della Studio Ghibli',9.0),
(32,1266127,5,'2026-05-08 19:08:12','Film molto bello',8.0),
(33,936075,5,'2026-05-08 19:09:13','Incredibile',10.0),
(34,1242898,5,'2026-05-08 19:10:04','Terribile',5.0),
(35,1325734,5,'2026-05-08 19:11:15','Film intrigante',7.0),
(36,1314481,5,'2026-05-08 19:11:37','Interessante',7.5),
(37,687163,13,'2026-05-08 19:20:20','Spettacolare',10.0),
(38,157336,13,'2026-05-08 19:20:47','Bellissimo film di fantascienza',9.0),
(39,1318447,13,'2026-05-08 19:21:14','Pensavo meglio',5.5),
(40,1317288,13,'2026-05-08 19:21:39','Carino ma mi aspettavo di più',6.5),
(41,238,13,'2026-05-08 19:21:56','Capolavoro',10.0),
(42,149870,13,'2026-05-08 19:22:20','Alto livello',8.5),
(43,22794,13,'2026-05-08 19:23:09','Carino',6.5),
(44,1084242,4,'2026-05-08 19:25:51','Molto carino anche se ho preferito il primo',7.0),
(45,269149,4,'2026-05-08 19:26:32','Bellissimo film d\'animazione, molto consigliato',8.5),
(46,1226863,4,'2026-05-08 19:27:17','Non è stato male però avrei preferito qualcosa in più',6.5),
(47,950387,4,'2026-05-08 19:27:56','Davvero terribile',4.0),
(48,77338,1,'2026-05-08 19:29:33','Molto bello',8.5),
(49,567609,1,'2026-05-08 19:30:04','Carino',7.0),
(50,424,1,'2026-05-08 19:31:25','Uno dei migliori film sulla seconda guerra mondiale',10.0),
(51,278,1,'2026-05-08 19:33:07','Capolavoro',10.0),
(52,207,1,'2026-05-08 19:32:10','Alto livello, un ottimo Robin Williams',8.5),
(53,2277,1,'2026-05-08 19:32:47','Molto commovente',8.0),
(54,1214931,4,'2026-05-08 21:30:58','Gran bel film',8.0),
(55,245891,4,'2026-05-08 22:03:08','Semplice ma efficace',7.5),
(56,129,10,'2026-05-09 18:55:31','Film grandioso',10.0);
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
('058f459a4ccc6a6a7321370696b82887',1,'2026-05-08 13:24:26',NULL),
('0689b0ec162c1ffee704e4423fb716bd',1,'2026-05-08 19:46:32','2026-05-08 20:04:28'),
('0e1d91d4d809a99e6b62f3df131269fd',1,'2026-05-06 17:08:15',NULL),
('0e529b764545af08032dea96ef0ca06d',1,'2026-05-06 20:37:38','2026-05-06 21:22:35'),
('0ee152fe15e7e1781873bd894613572e',1,'2026-04-23 21:55:58','2026-04-23 22:00:51'),
('0fd4f9bf5a5185c040cb2edbd08cd7f0',1,'2026-04-22 18:34:40','2026-04-22 19:49:51'),
('1359fd81d27aa15086c80e9accb3a9dd',1,'2026-05-06 14:42:33','2026-05-06 14:44:17'),
('13656d3eed74343a1eece407fd24e001',1,'2026-05-07 21:42:26',NULL),
('13a5535ac6b8386eb7d80758fa067cb2',1,'2026-05-05 21:13:04',NULL),
('1547627f3983632f5d310998a9856a29',1,'2026-03-30 18:05:10','2026-03-30 18:07:30'),
('155b8653273553129124bf53a371e557',1,'2026-05-05 23:33:01',NULL),
('15b3b51baa435236556739bb316740be',4,'2026-05-08 23:30:39',NULL),
('16571ac889b5cefc8f0bfe7a94dd9847',1,'2026-03-25 18:29:10','2026-03-25 18:39:26'),
('172de2978f96be95c4d6855dc95b204f',1,'2026-03-27 10:09:41','2026-03-27 10:10:11'),
('1926352fe92e82605ffe094800cf4cf9',1,'2026-05-06 14:38:17','2026-05-06 14:39:45'),
('1df8f2f087345b5b7825990948542ed0',1,'2026-05-09 19:08:47','2026-05-09 20:10:01'),
('1fd1ca63d4907e9b5a517f02e103031a',1,'2026-05-08 16:34:55','2026-05-08 17:21:28'),
('1fd1fc7131a2523f6739c2ec40c2a837',1,'2026-04-23 21:01:52','2026-04-23 21:52:59'),
('22b2d1cc3150f931169e4a623ade2534',1,'2026-04-22 16:09:51','2026-04-22 18:23:03'),
('2368538c591dc3aae3813c783ec6f5f7',1,'2026-04-23 12:38:19',NULL),
('2513ff61d6ae47e29df923ac245b8898',1,'2026-05-07 12:54:28',NULL),
('257bf4ad6c8e60f48d0253ba72a1d47a',1,'2026-05-08 22:49:57','2026-05-08 23:30:31'),
('25b7114b729b540a48a53a09d51537e6',1,'2026-05-06 13:28:28','2026-05-06 13:29:16'),
('2639fbf68bbbb8a62fafea1c2c82690b',1,'2026-05-08 20:57:47','2026-05-08 21:05:35'),
('29c62cfc7e5dc741c04ad77c54daee48',1,'2026-05-07 21:28:48',NULL),
('2ca8233ba8347cb65c6c3ed193295fa3',1,'2026-04-23 20:29:10','2026-04-23 20:29:34'),
('2d145ca6f839d181245d8c2c20a115f6',4,'2026-05-06 21:22:42','2026-05-06 21:22:54'),
('2dbdd57f893b120973f9ea65a7a8cb3b',1,'2026-04-23 22:17:51','2026-04-23 22:23:04'),
('2ea1a94cc4c3fbd5200f07b4a1a7b23d',1,'2026-05-06 13:11:25','2026-05-06 13:24:36'),
('309e992fc63b90ed6b071fe06f26fdaa',1,'2026-04-23 22:49:49','2026-04-23 23:00:31'),
('30d3c15fbb854ef494aaddd7725c241b',1,'2026-05-04 12:58:37',NULL),
('3415511651f870eac45f1f1161bb303c',4,'2026-05-09 00:02:00',NULL),
('34b8ff74380d40b083f6ec8a00e00cff',1,'2026-04-24 21:10:53','2026-04-24 21:12:42'),
('36f340ec363a7da263d2d10964323caa',1,'2026-04-22 13:12:12','2026-04-22 13:18:41'),
('37f6d09c2d4fab6efcac050f05453353',1,'2026-05-06 12:12:59',NULL),
('387579df4d1d3e5315096c25b84889f0',1,'2026-04-23 23:00:42','2026-04-24 00:45:12'),
('397fb05fc96fc54448a4fdce56f62af6',1,'2026-05-03 19:36:55',NULL),
('3b4f11104ed4cfb95d88f45adb7457fb',1,'2026-05-06 14:54:23',NULL),
('3fbebb040f548d49cf0670fa61c14cca',1,'2026-05-04 10:34:49',NULL),
('410426c9d749dc165bd4aecc351a4c85',14,'2026-04-23 22:17:28','2026-04-23 22:17:45'),
('42ba650d3b1cad0bd56d3df12fc031fa',1,'2026-05-09 00:05:51','2026-05-09 00:19:32'),
('45c5fdd9888848e3bdb440d69f4f2bc2',6,'2026-03-30 18:28:01','2026-03-30 18:28:06'),
('4a250024fcdde8b1b3dd7b3467f6625f',1,'2026-05-08 09:58:26',NULL),
('4d5c2bdb6094d1e47e0e870d02d4e74e',1,'2026-04-24 01:04:45','2026-04-24 01:05:31'),
('4f2f89de9bf6fa0fe30fb8965c1f59ef',1,'2026-05-08 18:07:48',NULL),
('4f88bd5ab14f8313947555aee89cf201',1,'2026-04-23 22:02:47','2026-04-23 22:17:03'),
('510d5962e7a90bc72b538c7031eab01d',1,'2026-05-06 13:30:29','2026-05-06 13:35:34'),
('53c8547f1870e95af9513eeb5a10c91c',1,'2026-04-23 22:28:43','2026-04-23 22:39:06'),
('54c289a73c0da47c8a720a5a8bbe60d7',1,'2026-04-24 22:01:45',NULL),
('59ba8e083b31081f8af7eb04361259fc',4,'2026-05-06 14:54:10','2026-05-06 14:54:17'),
('5b7a24177e2e67c3fcf30df28b97e537',1,'2026-05-08 23:59:22','2026-05-09 00:01:50'),
('5d90c365f58461b2a83fe643c3228c53',1,'2026-05-10 02:34:44','2026-05-10 02:56:48'),
('5ebb2490022465930f0a7db4ff277011',1,'2026-05-08 17:21:57',NULL),
('60840023ddf09c631efb993715d4cc80',1,'2026-05-08 21:28:18',NULL),
('60ec08a365332c873bfe19ec467bda1a',1,'2026-05-03 15:34:15',NULL),
('623fcc0f613dc86bc8f7e5598d753b04',1,'2026-04-24 00:47:10','2026-04-24 01:04:36'),
('64193a292fcf1976cbc3c7296006a7d7',1,'2026-05-09 00:20:01',NULL),
('658971075b80eedc94cc3d441935d9c9',1,'2026-05-09 00:19:39','2026-05-09 00:19:55'),
('683f403372ea1da627cbbd560c07c66d',1,'2026-04-19 18:02:17',NULL),
('688d8e831a13e90c0a9bbf8075b964c0',1,'2026-05-06 15:36:46',NULL),
('688fd3c4f0444932796d55099b6a6a94',4,'2026-05-06 14:39:51',NULL),
('6b789cd4a1dadd1dd412bc89b3a2fe82',4,'2026-05-06 20:13:01','2026-05-06 20:37:32'),
('6bd87857ac81c9d4b9a9114dc1cbd010',1,'2026-04-22 18:31:48','2026-04-22 18:34:28'),
('6ceed541eb8e947df425b2e244a4c5ee',1,'2026-04-23 20:37:06',NULL),
('73321f607d9f9579c2c02e4cc745dd6b',1,'2026-05-07 22:09:04',NULL),
('73b33b898c631f652d41cd2e22e68352',1,'2026-04-17 13:35:17','2026-04-17 13:36:11'),
('75b55e05146627e9ccfc9516e2170efd',1,'2026-05-04 20:25:12',NULL),
('7cd956bc695e9634d123917d93c2ab24',1,'2026-05-05 16:41:50',NULL),
('8126a5e14cd8d129418bebdcb8f49f63',1,'2026-05-10 02:22:54',NULL),
('84417979d9b5f510657c09543d9acd1e',1,'2026-05-09 23:09:59',NULL),
('85b348526adf4bbe32ca46dab7a8a8f2',1,'2026-05-05 08:20:37','2026-05-05 11:50:58'),
('864f2f4472c823b57df04e3fbb5d17ab',1,'2026-05-08 20:29:03',NULL),
('8728ce84b027b0afd50bf431e2d7d22a',1,'2026-03-25 19:24:57','2026-03-25 19:26:40'),
('8818b6146ae08bf95105f76af08531b6',4,'2026-05-06 14:35:44','2026-05-06 14:38:07'),
('893a4e01a1931084e3a61bbf9753394c',1,'2026-05-08 12:26:07',NULL),
('8a63c25dd055a536dfd64cacab38ec91',1,'2026-05-05 22:44:22',NULL),
('8a967e7e0ab9c9770c00df37d77a30ee',1,'2026-04-29 13:20:32','2026-04-29 13:30:27'),
('8c09f5a44105f042d6807b1f8170e30b',1,'2026-04-24 21:22:47','2026-04-24 21:43:02'),
('8d9feef2c70ea2ca8401cc2044b15ff1',1,'2026-04-18 12:43:54','2026-04-18 12:44:18'),
('8fb61c569c3b82288f5b463ebe4a1c1e',1,'2026-03-30 18:28:12','2026-03-30 18:34:00'),
('92501a7ab73b24ee226685fec0220dd4',1,'2026-03-30 18:27:37','2026-03-30 18:27:45'),
('93ab1a4cd70fbbb540a6961a043770ac',1,'2026-04-24 13:02:49','2026-04-24 16:49:22'),
('945903064c9078d49c00bb8dddccb292',1,'2026-05-08 08:42:36',NULL),
('94f668a246d2ef0397dfb210015b3eb5',1,'2026-04-27 20:36:14',NULL),
('95818ee58eab27a55cb7928708a6604c',4,'2026-05-08 21:24:55','2026-05-08 21:28:06'),
('95fa6fe845513ffff0e269dc2a85f680',1,'2026-05-09 20:18:25','2026-05-09 20:53:11'),
('960b32e69df31b01bf9a1af546693cd8',1,'2026-03-27 10:03:00','2026-03-27 10:04:57'),
('96b466621d7b9a064c8306d9f831c3a3',1,'2026-05-06 14:44:22','2026-05-06 14:44:28'),
('9eb559deb20277da128e93422f695499',1,'2026-05-07 22:24:35',NULL),
('9eda922c7ad0804f46256f75bb3b6617',1,'2026-05-07 21:42:59','2026-05-07 21:56:27'),
('9f34f3cc99a5de0598d983529fc72f1c',1,'2026-05-06 14:46:42','2026-05-06 14:54:02'),
('9f3fa601096c26b4994ecb4a3c8306a8',1,'2026-03-25 18:23:48',NULL),
('a33a7a8a5ee34c020cfc05f01b258640',1,'2026-05-10 12:02:00','2026-05-10 12:26:17'),
('a477f9ef5225a29f395fe3fc0e596464',1,'2026-03-25 18:20:29','2026-03-25 18:22:05'),
('a484942aefe00b2f1b477fea3a39e24c',5,'2026-05-08 21:07:02','2026-05-08 21:19:26'),
('a70cd906540d07384d78ba1780eddc54',1,'2026-04-23 19:50:22','2026-04-23 20:23:47'),
('a91fe66eb52f3ff2259539121d0b1e37',1,'2026-05-10 03:21:51','2026-05-10 04:15:53'),
('ab7e66cf1dc700fe16e701ca9e9f731e',1,'2026-04-24 21:50:10','2026-04-24 21:53:27'),
('ad303b4f901d7cca28a033851c643eb4',1,'2026-05-06 16:02:02',NULL),
('afc3793009a9452203ed30b9cffef280',1,'2026-05-06 17:04:03','2026-05-06 17:05:08'),
('afc40ab20cb2d3d161c41adf83765a2e',1,'2026-05-08 23:49:14',NULL),
('b127d8f085b17c5793ccc56fe28494ad',1,'2026-05-10 02:17:45',NULL),
('b31ce4f3a7abbb66756ad127e576de58',57,'2026-05-07 13:04:40','2026-05-07 13:06:19'),
('b33fef4a591f0b6979622c98ae8274de',1,'2026-05-06 17:38:00',NULL),
('b4cdda15075c059ef6e04a17e5ba8a5f',1,'2026-05-08 19:43:54',NULL),
('b51a20d28c49e436beef32ab4a06d138',4,'2026-05-09 20:10:17','2026-05-09 20:10:45'),
('b5626e2b600011ca8d582e7d88a92eab',1,'2026-04-15 22:09:09','2026-04-15 22:10:08'),
('ba045843a6ca718e155b1d2f69987e40',4,'2026-05-06 21:26:28','2026-05-06 21:41:39'),
('bc2b8ed1b06b1bac1150640f0ff16316',1,'2026-05-09 22:44:08',NULL),
('bc43366d58b172cff0a4336ba922093d',1,'2026-03-25 18:22:14','2026-03-25 18:22:48'),
('bc76cfd9f3cb5e4ab157efef871b68f9',1,'2026-03-25 23:17:10',NULL),
('c1ccc60484d0dbbbaf193fee97820160',4,'2026-05-06 14:44:34','2026-05-06 14:46:35'),
('c2a3e0e673780deb9f534d6682fa68da',1,'2026-05-10 02:32:06','2026-05-10 02:34:23'),
('c2d3f61993676bea52b965fdab4aabaf',1,'2026-05-08 10:30:35',NULL),
('c2d4723f4c3c38bec23f1f6d8af0ad04',1,'2026-05-08 17:23:10','2026-05-08 17:23:41'),
('c4794d5e993056d70d50735b5115c9b8',13,'2026-05-08 21:20:02','2026-05-08 21:24:35'),
('c4bda5c83001afbec67582a4c4b22b7d',1,'2026-05-10 02:56:59',NULL),
('c563789bdb6efd1d2bbc6a6e89c40859',1,'2026-04-23 22:25:32',NULL),
('c56f7c4c4e05c5c2ddc85b9b41eddd78',1,'2026-04-29 12:17:11','2026-04-29 12:57:21'),
('c6a4163400c261bf5506a8f288b756eb',1,'2026-05-07 14:46:28',NULL),
('c737a4825aae7aaea1fcb195aa8ca369',1,'2026-05-06 21:22:58',NULL),
('cdb47ad9e943a8c87d1df62704c1d036',1,'2026-05-06 17:09:06',NULL),
('cdec95cb76f629cd21183acae4565cf3',1,'2026-05-09 20:10:52','2026-05-09 20:18:12'),
('cec9ef302ae3a84977c68c881b697c00',1,'2026-05-08 17:23:52','2026-05-08 18:05:49'),
('d135c63410d266728715ec7e93868bbd',1,'2026-05-09 20:10:08','2026-05-09 20:10:10'),
('d1609122ea68f5067cd3ccdf7520b77e',1,'2026-04-29 16:09:22','2026-04-29 16:17:02'),
('d1c5425c4dd5b366e617566a673398fe',1,'2026-05-08 23:51:48',NULL),
('d42cd31c43e38aa6ef2e3f24175e75a3',1,'2026-05-06 17:48:57',NULL),
('d459c0349f7a57b4b9eab999c2d85ee4',1,'2026-05-07 12:49:08','2026-05-07 12:54:17'),
('d6775379a49cbd1e359c35f0dd53d6fb',1,'2026-04-29 16:55:02','2026-04-29 17:21:06'),
('d7d83f3a5d9ae973bac6db319f7eed4b',1,'2026-04-19 16:37:05',NULL),
('d96ab62ed7351c68427a68307b6ed134',4,'2026-05-06 13:35:57','2026-05-06 13:36:50'),
('da3f07ec30e02ad0d9b7d19f80b195a4',1,'2026-03-25 19:26:56',NULL),
('de211e890f489a173ead66ef61cf8b0d',1,'2026-04-29 16:18:55','2026-04-29 16:27:56'),
('e2ed179a003b2c732a572b2bd1ec3a93',10,'2026-05-09 20:53:18','2026-05-09 21:04:03'),
('eb96335cb7140a9e3e282af7baf30085',1,'2026-05-05 11:51:07',NULL),
('ecf7440fed422a57b68cdc76bf92f5b3',1,'2026-05-09 22:59:37',NULL),
('f01ae4f0ca2ee15e70c2b43592739309',1,'2026-05-07 22:13:12',NULL),
('f2c98e62d02ae5874cff68c3aeb49465',1,'2026-03-27 09:35:22','2026-03-27 09:35:28'),
('f37460f6c45627f06a209bd7b2d20159',1,'2026-05-05 23:26:36',NULL),
('f404018aacf436676f3d15a1ea30da5e',1,'2026-03-30 18:23:39','2026-03-30 18:27:27'),
('f415446e4ca6cf80551171c5e1574c59',1,'2026-05-10 12:26:24',NULL),
('f75e4fb63aae159b3fab4008dab53681',1,'2026-05-05 19:00:56',NULL),
('f87c8ae11c66d00f8525479898a96c1d',1,'2026-05-06 18:30:30','2026-05-06 20:12:52'),
('fab084cc50d233147e319310495fa48e',1,'2026-05-07 12:27:32',NULL),
('fc62965b2147501d2a088dfe60bd4b1d',1,'2026-04-19 16:26:02','2026-04-19 16:37:00'),
('ff4fdbd86f8cc0bf475632f133eb3efb',1,'2026-05-09 21:04:09','2026-05-09 21:26:55');
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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
INSERT INTO `utenti` VALUES
(1,'alessio','$2y$12$WPIVWsisLYKRFpW8qfe7Nur6vdOIEOoHpWbiOdUpqlyzPqpcaDHhe','alessio.gualtieri24@istitutotecnicomarconipilla.edu.it','Alessio','Gualtieri',1,1,'2026-03-25 17:19:40'),
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
(44,'bruce.banner','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','banner@mail.us','Bruce','Banner',2,0,'2026-03-25 18:22:28'),
(45,'clint.barton','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','clint@mail.us','Clint','Barton',2,1,'2026-03-25 18:22:28'),
(46,'sam.wilson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sam@mail.us','Sam','Wilson',2,0,'2026-03-25 18:22:28'),
(48,'scott.lang','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','scott@mail.us','Scott','Lang',2,1,'2026-03-25 18:22:28'),
(49,'hope.vandyne','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','hope@mail.us','Hope','Van Dyne',2,1,'2026-03-25 18:22:28'),
(50,'carol.danvers','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','carol@mail.us','Carol','Danvers',2,0,'2026-03-25 18:22:28'),
(57,'mariorossi','$2y$12$z.9xE8MeNPKGbgg.bZwTtuO3eEI03.GgBLNl.e0LuWIVam1zqMFki','raffaeleboffa92@gmail.com','Raffaele','Boffa',2,1,'2026-05-07 13:04:33');
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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `watched`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `watched` WRITE;
/*!40000 ALTER TABLE `watched` DISABLE KEYS */;
INSERT INTO `watched` VALUES
(11,129,1,'2026-05-06 15:39:37'),
(14,1325734,4,'2026-05-06 19:22:47'),
(16,687163,57,'2026-05-07 11:05:21'),
(18,1266127,5,'2026-05-08 19:08:12'),
(19,936075,5,'2026-05-08 19:09:13'),
(20,1242898,5,'2026-05-08 19:10:04'),
(21,1325734,5,'2026-05-08 19:11:15'),
(22,1314481,5,'2026-05-08 19:11:37'),
(23,687163,13,'2026-05-08 19:20:20'),
(24,157336,13,'2026-05-08 19:20:47'),
(25,1318447,13,'2026-05-08 19:21:14'),
(26,1317288,13,'2026-05-08 19:21:39'),
(27,238,13,'2026-05-08 19:21:56'),
(28,149870,13,'2026-05-08 19:22:20'),
(29,22794,13,'2026-05-08 19:23:09'),
(30,1084242,4,'2026-05-08 19:25:51'),
(31,269149,4,'2026-05-08 19:26:32'),
(32,1226863,4,'2026-05-08 19:27:17'),
(33,950387,4,'2026-05-08 19:27:37'),
(34,77338,1,'2026-05-08 19:29:33'),
(35,567609,1,'2026-05-08 19:30:04'),
(36,424,1,'2026-05-08 19:30:33'),
(37,278,1,'2026-05-08 19:30:55'),
(38,207,1,'2026-05-08 19:32:10'),
(39,2277,1,'2026-05-08 19:32:47'),
(40,1214931,4,'2026-05-08 21:30:58'),
(41,245891,4,'2026-05-08 22:03:08'),
(43,129,10,'2026-05-09 18:55:10');
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
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
(16,129,5,'2026-05-08 19:16:35'),
(17,1084242,5,'2026-05-08 19:16:48'),
(18,1266127,1,'2026-05-08 19:29:50');
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

-- Dump completed on 2026-05-10 12:59:17
```

## File: pages/public/community_reviews.php
```php
<?php
// Mostra le recensioni degli utenti per un film specifico.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

$recensioni_altri = [];
$movie_id = $_GET['tmdb_id'] ?? null;

// Recuperiamo le recensioni degli altri utenti
try {   
    $sql = "SELECT r.commento, r.voto, u.nome, u.cognome
            FROM recensioni r
            JOIN utenti u ON r.id_utente = u.id_utente 
            WHERE tmdb_id = :tmdb_id
            ORDER BY r.tmdb_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':tmdb_id' => $movie_id]);

    $recensioni_altri = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recensioni della Community - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root { --accent-color: #ffc107; }
        .text-justify { text-align: justify; }
        .review-poster {
            width: 120px;
            min-width: 120px;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 0.5rem 0 0 0.5rem;
        }
        .transition-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-2">Recensioni della Community</h1>
        <p class="text-muted mb-4">Scopri cosa pensano gli altri utenti</p>

            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($recensioni_altri as $r): 
                    $poster_url = !empty($r['poster']) ? "https://image.tmdb.org/t/p/w500" . $r['poster'] : "https://via.placeholder.com/500x750?text=No+Poster";
                ?>
                <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover">
                            <div class="d-flex h-100">

                                <div class="card-body d-flex flex-column justify-content-between p-3">
                                    <div>
                                        <div class="small mb-2" style="color: var(--accent);">
                                            <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($r['nome']) . " " . htmlspecialchars($r['cognome'])?>
                                        </div>

                                        <?php if (!empty($r['commento'])): ?>
                                            <p class="text-muted small mb-2 text-justify">
                                                "<?= nl2br(htmlspecialchars($r['commento'])) ?>"
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex align-items-center gap-1 mt-2">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="fw-bold fs-5"><?= number_format($r['voto'], 1) ?></span>
                                        <span class="text-muted small">/10</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: pages/public/genres.php
```php
<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

$generi = [];
$errorMessage = null;

try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase("cinevobis");
    $collection = $db->selectCollection("films");

    $pipeline = [
        ['$unwind' => '$genres'],
        ['$group' => [
            '_id'  => '$genres.id',
            'name' => ['$first' => '$genres.name'],
        ]],
        ['$sort' => ['name' => 1]],
    ];

    $generi = $collection->aggregate($pipeline)->toArray();

} catch (Exception $e) {
    error_log("Errore con MongoDB: " . $e->getMessage());
    $errorMessage = "Impossibile caricare i generi. Riprova più tardi.";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinevobis - Generi</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include(__DIR__ . "/../../includes/header.php"); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="container">

            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-grid-fill" style="color: var(--accent); font-size: 1.6rem"></i>
                <h1 class="fw-bold mb-0">Generi</h1>
            </div>
            <p class="mb-4" style="color: var(--text-muted);">Esplora il catalogo per categoria</p>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($errorMessage) ?>
                </div>

            <?php elseif (empty($generi)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Nessun genere trovato.
                </div>

            <?php else: ?>
                <!-- row-cols-md-3 su tablet (era 4), row-cols-lg-4 solo su desktop -->
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-4 g-3">
                    <?php foreach ($generi as $genere):
                        $gId   = (int) $genere['_id'];
                        $gName = $genere['name'];
                    ?>
                    <div class="col">
                        <a href="search_genre.php?id=<?= urlencode($gId) ?>&name=<?= urlencode($gName) ?>"
                           class="card transition-hover text-decoration-none d-flex flex-row align-items-center gap-2 p-3"
                           style="color: var(--text);">
                            <i class="bi bi-film flex-shrink-0" style="font-size: 1.2rem; color: var(--accent);"></i>
                            <!-- rimosso text-truncate, aggiunto word-break per evitare tagli -->
                            <span class="fw-semibold" style="font-size: 0.9rem; word-break: break-word;">
                                <?= htmlspecialchars($gName) ?>
                            </span>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: pages/public/notice_board.php
```php
<?php
// Bacheca globale: mostra le ultime recensioni con i dettagli dei film.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Configurazione query
$limit = 20;
$ids = [];
$recensioni_map = [];
$films = [];

try {
    // Recupero le ultime 20 recensioni globali includendo nome e cognome
    $sql = "SELECT tmdb_id, commento, voto, nome, cognome
            FROM recensioni r
            JOIN utenti u ON r.id_utente = u.id_utente
            ORDER BY data_aggiunto DESC 
            LIMIT :limit";
            
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $tmdb_id = (int) $row['tmdb_id'];
        $ids[] = $tmdb_id;

        $recensioni_map[$tmdb_id] = [
            'voto' => $row['voto'],
            'commento' => $row['commento'],
            'autore' => $row['nome'] . ' ' . $row['cognome'] // Mappatura nome e cognome
        ];
    }

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}

if (!empty($ids)) {
    // Connessione a MongoDB e ricerca film
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase("cinevobis");
        $collection = $db->selectCollection("films");

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]],
            [
                'sort' => ['vote_average' => -1],
                'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array'],
            ]
        );

        $films = movie_sorting($cursor, $ids);

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
        $films = [];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bacheca Recensioni - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root { --accent-color: #ffc107; }
        .text-justify { text-align: justify; }
        .review-poster {
            width: 120px;
            min-width: 120px;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 0.5rem 0 0 0.5rem;
        }
        .transition-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="d-flex align-items-center mb-4">
            <i class="bi bi-journal-text fs-2 me-3 text-warning"></i>
            <h1 class="fw-bold m-0">Bacheca</h1>
        </div>

        <p class="text-muted mb-4">Le ultime recensioni della community</p>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non ci sono ancora recensioni in bacheca
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <?php
                foreach ($films as $film):
                    $id = (int) ($film['id'] ?? 0);
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                    
                    $rec = $recensioni_map[$id] ?? [];
                    $voto = isset($rec['voto']) ? (float) $rec['voto'] : null;
                    $commento = $rec['commento'] ?? '';
                    $autore = $rec['autore'] ?? 'Utente Anonimo';
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover position-relative">
                        <div class="d-flex">
                            <img src="<?= htmlspecialchars($poster) ?>"
                                 alt="<?= htmlspecialchars($titolo) ?>"
                                 class="review-poster">

                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark stretched-link">
                                            <?= htmlspecialchars($titolo) ?>
                                        </a>
                                    </h5>
                                    
                                    <div class="small mb-2" style="color: var(--accent);">
                                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($autore) ?>
                                    </div>

                                    <?php if (!empty($commento)): ?>
                                        <p class="text-muted small mb-2 text-justify">
                                            "<?= nl2br(htmlspecialchars($commento)) ?>"
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($voto !== null): ?>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <i class="bi bi-star-fill" style="color: var(--accent-color); font-size: 1rem;"></i>
                                        <span class="fw-bold fs-5"><?= $voto ?></span>
                                        <span class="text-muted small">/10</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/public/recommended_films.php
```php
<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Prepara l'array di dati che verrà popolato dal database.
$recommendedFilms = [];

try {
    // Connessione a MongoDB locale e selezione della collezione film.
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

    // Prende i film in evidenza ordinati per data di uscita.
    $cursor = $collection->find([], [
        'limit' => 24,
        'sort' => ['release_date' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);

    $recommendedFilms = iterator_to_array($cursor);

} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In evidenza - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include(__DIR__ . "/../../includes/header.php"); ?>
    
    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold m-0">I Film in evidenza</h1>
        </div>

        <?php if (empty($recommendedFilms)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                <?php 
                /** @var array $film */
                foreach ($recommendedFilms as $film):
                    $id     = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno   = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                ?>
                <div class="col">
                    <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                            <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" loading="lazy" style="object-fit: cover; aspect-ratio: 2/3;">
                            <div class="card-body p-2 d-flex flex-column bg-white">
                                <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                <div class="mt-auto">
                                    <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: pages/public/top_films.php
```php
<?php
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Prepara gli array di dati che verranno popolati dal database.
$topFilms = [];

try {
    // Connessione a MongoDB locale e selezione della collezione film.
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

    // Prende i migliori film ordinati per voto medio.
    $cursor = $collection->find([], [
        'limit' => 24,
        'sort' => ['vote_average' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);

    $topFilms = iterator_to_array($cursor);

} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migliori - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include(__DIR__ . "/../../includes/header.php"); ?>
    
    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold m-0">I migliori Film</h1>
        </div>

        <?php if (empty($topFilms)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                <?php 
                /** @var array $film */
                foreach ($topFilms as $film):
                    $id     = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno   = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                ?>
                <div class="col">
                    <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                            <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" loading="lazy" style="object-fit: cover; aspect-ratio: 2/3;">
                            <div class="card-body p-2 d-flex flex-column bg-white">
                                <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                <div class="mt-auto">
                                    <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: config/functions.php
```php
<?php
function movie_sorting($cursor, $ids) {
    // Trasforma il cursore MongoDB in un array associativo
    $raw_films = iterator_to_array($cursor);

    // Mappa i film usando il loro ID come chiave per un accesso rapido
    $films_map = [];
    foreach ($raw_films as $f) {
        $films_map[$f['id']] = $f;
    }

    // Inizializza l'array
    $films = [];
    
    // Ricostruisce la lista seguendo l'ordine esatto di $ids
    foreach ($ids as $id) {
        if (isset($films_map[$id])) {
            $films[] = $films_map[$id];
        }
    }

    return $films;
}

function order_of_popularity ($n, $results) {
    // Ordinare per popolarità
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            if ($results[$i]['popularity'] < $results[$j]['popularity']) {
                // scambio
                $temp = $results[$i];
                $results[$i] = $results[$j];
                $results[$j] = $temp;
            }
        }
    }

    return $results;
}
```

## File: package.json
```json
{
  "name": "cinevobis",
  "version": "1.0.0",
  "description": "PHP project with a MySQL Database",
  "main": "index.js",
  "scripts": {
    "test": "echo \"Error: no test specified\" && exit 1"
  },
  "repository": {
    "type": "git",
    "url": "git+https://github.com/alexiomagnus/cinevobis.git"
  },
  "keywords": [],
  "author": "",
  "license": "ISC",
  "type": "commonjs",
  "bugs": {
    "url": "https://github.com/alexiomagnus/cinevobis/issues"
  },
  "homepage": "https://github.com/alexiomagnus/cinevobis#readme",
  "dependencies": {
    "bootstrap": "^5.3.8",
    "bootstrap-icons": "^1.13.1",
    "tom-select": "^2.5.2"
  }
}
```

## File: database/credentials.md
```markdown
- password: film
```

## File: .gitignore
```
/vendor/
/node_modules/
.env
config/php_errors.log
repomix-output.md
```

## File: composer.json
```json
{
    "name": "alexio/cinevobis",
    "description": "PHP project with a MySQL Database",
    "require": {
        "vlucas/phpdotenv": "^5.6",
        "kiwilan/php-tmdb": "^0.1.12",
        "mongodb/mongodb": "^2.2"
    },
    "config": {
        "allow-plugins": {
            "php-http/discovery": true
        }
    }
}
```

## File: includes/header_logic.php
```php
<?php
// Logica di routing per le azioni inviate dai form dell'header.
// Gestisce redirect per logout, login, signup e profilo.
$currentPage = basename($_SERVER['SCRIPT_NAME']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        header("Location: /actions/logout.php");
        exit();
    }
    if (isset($_POST['login']) && $currentPage !== 'login.php') {
        header("Location: /pages/public/login.php");
        exit();
    }
    if (isset($_POST['signup']) && $currentPage !== 'signup.php') {
        header("Location: /pages/public/signup.php");
        exit();
    }
    if (isset($_POST['profile'])) {
        header("Location: /actions/settings.php");
        exit();
    }
}
```

## File: pages/public/search_genre.php
```php
<?php
// Pagina di esplorazione per genere. Riceve id e nome via GET, trova i film
// con quel genere in MongoDB e li mostra in una griglia di card.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Connessione a MongoDB e ricerca film per genere
$id_genere = isset($_GET['id']) ? (int)$_GET['id'] : null;
$nome_genere = isset($_GET['name']) ? $_GET['name'] : null;
$cursor = [];

if (!empty($id_genere)) {
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase('cinevobis');
        $collection = $db->selectCollection('films');

        $cursor = $collection->find(['genres.id' => $id_genere])->toArray();
        $count = count($cursor);
        
    } catch(Exception $e) {
        error_log("Errore: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ricerca genere - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <?php if(!empty($nome_genere)): ?>
            <h1 class="fw-bold mb-4"><?= htmlspecialchars($nome_genere) ?></h1>
            
            <?php if($count > 0): ?>
                <small class="text-uppercase fw-bold text-muted d-block mb-4" style="letter-spacing: 1px;">
                    <?= htmlspecialchars($count) ?> Film presenti
                </small>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (empty($cursor)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non ci sono film di questo genere salvati nel Database 
            </div>
        <?php else: ?>

            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                
                <?php 
                // Iterazione della lista di film recuperata da MongoDB.
                foreach ($cursor as $film): 
                    
                    // Recupero dell'ID per generare il link alla pagina del film.
                    $id = $film['id'] ?? '';

                    // Titolo con valore di fallback se il campo non è presente.
                    $titolo = $film['title'] ?? 'Titolo non disponibile';

                    // Costruzione dell'URL del poster o fallback placeholder.
                    $baseUrl = "https://image.tmdb.org/t/p/w500";
                    $placeholderUrl = "https://via.placeholder.com/500x750?text=Immagine+non+disponibile";

                    $posterPath = $film['poster_path'] ?? '';
                    $poster = !empty($posterPath) ? $baseUrl . $posterPath : $placeholderUrl;

                    // Estraggo l'anno dalla data di rilascio nel formato YYYY-MM-DD.
                    $dataRilascio = $film['release_date'] ?? '';
                    $anno = !empty($dataRilascio) ? substr($dataRilascio, 0, 4) : 'N.D.';
                ?>

                <div class="col">
                    <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                            <div class="position-relative">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
                            </div>
                            <div class="card-body p-2 d-flex flex-column bg-white">
                                <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                <div class="mt-auto">
                                    <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/public/terms.php
```php
<?php
// Pagina termini di servizio
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termini di Servizio - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>
    
    <main class="container flex-grow-1 py-5">
        <div class="card shadow-sm border-0 p-4 p-md-5">
            <h1 class="fw-bold mb-4">Termini di Servizio</h1>
            
            <p class="text-muted">Ultimo aggiornamento: 25 Marzo 2026</p>
            
            <section class="mb-4 mt-4">
                <h2 class="h4 fw-bold">1. Accettazione dei Termini</h2>
                <p>Creando un account su Cinevobis o utilizzando il nostro servizio, accetti di essere vincolato dai presenti Termini di Servizio. Se non accetti queste condizioni, ti invitiamo a non utilizzare la piattaforma.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">2. Account Utente</h2>
                <p>Dichiari di avere almeno 16 anni per utilizzare il servizio, dato che alcuni contenuti possono essere soggetti a restrizione di età. Sei responsabile del mantenimento della riservatezza delle credenziali del tuo account e di tutte le attività che avvengono sotto il tuo profilo. Ti impegni a fornirci informazioni accurate e complete al momento della registrazione.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">3. Contenuti e Comportamento</h2>
                <p>Gli utenti possono inserire recensioni e valutazioni. È severamente vietato pubblicare contenuti offensivi, illegali, diffamatori o che violano i diritti di copyright di terzi. Cinevobis si riserva il diritto di rimuovere tali contenuti e di sospendere gli account responsabili.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">4. Modifiche al Servizio</h2>
                <p>Ci riserviamo il diritto di modificare, sospendere o interrompere il servizio in qualsiasi momento, con o senza preavviso. Non saremo responsabili verso di te o terze parti per qualsiasi modifica o interruzione del servizio.</p>
            </section>
        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    
    <script src="/assets/js/script.js"></script>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: actions/contact.php
```php
<?php
// Gestisce l'invio dei messaggi di contatto e li salva come notifiche.
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');

$erroe = "";
$messaggio = "";

if (isset($_POST['invia'])) {
    $titolo = $_POST['titolo'];
    $descrizione = $_POST['descrizione'];

    try {
        $sql = "INSERT INTO notifiche (titolo, descrizione, data_invio, id_utente) VALUES 
            (:titolo, :descrizione, :data_invio, :id_utente)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':titolo' => $titolo,
            ':descrizione' => $descrizione,
            ':data_invio' => date('Y-m-d H:i:s'),
            ':id_utente' => empty($_SESSION['id_utente']) ? null : $_SESSION['id_utente']
        ]);
        
        $messaggio = "Messaggio inviato con successo";
    } catch (PDOException $e) {
        error_log("Errore: " . $e);
        $errore = "Si è verificato un errore durante l'invio";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contattaci - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 px-4">

                <a href="javascript:void(0)"
                   onclick="closeAndRedirect()"
                   class="btn-close position-absolute top-0 start-0 m-4"
                   aria-label="Close">
                </a>

                <div class="text-center mb-5">
                    <h1 class="display-6 fw-bolder mb-2">Contattaci</h1>
                    <p class="text-secondary">Inviaci un messaggio per qualsiasi necessità</p>
                </div>

                <?php if (!empty($errore)): ?>
                    <div class="alert alert-danger border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($errore) ?></div>
                <?php endif; ?>

                <?php if (!empty($messaggio)): ?>
                    <div class="alert alert-success border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($messaggio) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label small text-secondary">Titolo</label>
                        <input type="text"
                               name="titolo"
                               id="titolo"
                               maxlength="50"
                               class="form-control bg-light border-light py-3"
                               placeholder="Inserisci un titolo"
                               required>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small text-secondary">Descrizione</label>
                        <textarea name="descrizione"
                                  id="descrizione"
                                  class="form-control bg-light border-light py-3"
                                  rows="6"
                                  maxlength="200"
                                  placeholder="Descrivi il tuo messaggio..."
                                  required></textarea>
                        <div class="form-text text-end">
                            Limite massimo: 200 caratteri
                        </div>
                    </div>

                    <button type="submit" name="invia" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">
                        Invia
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>

</body>
</html>
```

## File: config/config.php
```php
<?php
// --- Cookie di sessione ---
ini_set('session.cookie_httponly', 1);      // JS non può leggere il cookie
ini_set('session.cookie_samesite', 'Lax');  // Protezione CSRF base
session_start();

// --- Gestione errori ---
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
error_reporting(E_ALL);

// --- Scadenza sessione per inattività ---
define('SESSION_TIMEOUT', 3600);

if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        
        // Aggiorna il DB se c'è un utente loggato
        if (isset($_SESSION['username'])) {
            require_once(__DIR__ . '/connection.php');
            require_once(__DIR__ . '/../includes/user_obj.php');

            try {
                $user = new userObj($conn, $_SESSION['username']);
                $user->setDataLogout(date('Y-m-d H:i:s'), session_id());

            } catch (Exception $e) {
                error_log("Errore logout automatico: " . $e->getMessage());
            }
        }
        
        session_unset();
        session_destroy();
        header("Location: /login.php?error=session_expired");
        exit();
    }
}

$_SESSION['last_activity'] = time();
```

## File: config/connection.php
```php
<?php
// Connessione PDO a MariaDB per il database 'cinevobis'.
// Imposta ERRMODE_EXCEPTION e FETCH_ASSOC per ottenere risultati consistenti.
// In caso di errore logga il problema e mostra un messaggio generico all'utente.
require_once(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;

// Inizializza Dotenv puntando alla cartella del progetto
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Errore critico DB: " . $e->getMessage());
    
    // Mostrare all'utente un messaggio generico
    die("Spiacenti, il servizio è momentaneamente non disponibile");
}
```

## File: pages/public/privacy.php
```php
<?php
// Pagina privacy
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informativa sulla Privacy - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>
    
    <main class="container flex-grow-1 py-5">
        <div class="card shadow-sm border-0 p-4 p-md-5">
            <h1 class="fw-bold mb-4">Informativa sulla Privacy</h1>
            
            <p class="text-muted">Ultimo aggiornamento: 25 Marzo 2026</p>
            
            <section class="mb-4 mt-4">
                <h2 class="h4 fw-bold">1. Raccolta dei dati</h2>
                <p>Quando ti registri su Cinevobis, raccogliamo informazioni come il tuo nome, cognome, indirizzo email e il paese di residenza, necessari per fornirti il nostro servizio di tracciamento e recensione dei film.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">2. Utilizzo dei dati</h2>
                <p>I tuoi dati vengono utilizzati esclusivamente per gestire il tuo profilo utente, personalizzare la tua esperienza sul sito, permetterti di salvare le tue preferenze cinematografiche e garantire la sicurezza del tuo account.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">3. Conservazione e Sicurezza</h2>
                <p>Non vendiamo né condividiamo i tuoi dati personali con terze parti per scopi commerciali o di marketing. Adottiamo misure di sicurezza standard per proteggere le tue informazioni (come le password crittografate) da accessi non autorizzati.</p>
            </section>

            <section class="mb-4">
                <h2 class="h4 fw-bold">4. I tuoi diritti</h2>
                <p>Hai il diritto in qualsiasi momento di richiedere la visualizzazione, la modifica o la cancellazione permanente del tuo account e dei dati ad esso associati tramite la tua area profilo.</p>
            </section>
        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    
    <script src="/assets/js/script.js"></script>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: includes/movie_obj.php
```php
<?php
// Gestisce la normalizzazione dei dati di un film provenienti da TMDB o MongoDB.
class movieObj
{
    private string $titolo;
    private string $titolo_orig;
    private string $trama;
    private ?string $poster_path;
    private float $voto;
    private $durata;
    private $anno;
    private array $generi;
    private array $cast;
    private ?string $trailer_key;
    private string $paese;
    private array $registi;

    // Costruttore della classe: estrae e normalizza i dettagli essenziali del film 
    // (titolo, trama, cast, ecc.) a partire dall'array di dati grezzi ricevuto in input.
    public function __construct(array $data)
    {
        $this->titolo = $data['title'] ?? 'Titolo non disponibile';
        $this->titolo_orig = $data['original_title'] ?? '';

        $this->trama = !empty($data['overview']) ? $data['overview'] : 'Nessuna trama disponibile';        
        $this->poster_path = !empty($data['poster_path']) ? $data['poster_path'] : null;

        $this->voto = (float)($data['vote_average'] ?? 0);
        $this->trailer_key = $data['videos']['results'][0]['key'] ?? null;

        $this->durata = $data['runtime'] ?? 'N/A';
        $this->anno = !empty($data['release_date']) ? substr($data['release_date'], 0, 4) : 'N/A';

        $this->generi = $data['genres'] ?? [];
        $this->paese = $data['production_countries'][0]['name'] ?? 'Nessun paese';
        
        // Limita il cast ai primi 12 attori per evitare array troppo pesanti
        $this->cast = array_slice($data['credits']['cast'] ?? [], 0, 12);
        $this->registi = $this->searchDirectors($data);
    }

    // Funzione privata di supporto: analizza i dati della troupe (crew) 
    // e filtra l'array per restituire esclusivamente i membri col ruolo di regista.
    private function searchDirectors(array $data): array
    {
        $crew = $data['credits']['crew'] ?? [];

        $directors = array_filter($crew, function ($persona) {
            return ($persona['job'] ?? '') === 'Director';
        });

        return array_values($directors);
    }

    // Metodo statico: processa una lista di risultati grezzi (es. risultati di ricerca TMDB)
    // e restituisce un array semplificato contenente solo ID, titolo, anno e URL della locandina.
    public static function search(array $movies): array
    {
        $moviesList = [];
        foreach ($movies as $movie) {
            $moviesList[] = [
                'id' => $movie['id'],
                'titolo' => $movie['title'] ?? 'Titolo non disponibile.',
                'anno'   => !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : null,
                'poster' => !empty($movie['poster_path']) ? 'https://image.tmdb.org/t/p/w92' . $movie['poster_path'] : null
            ];
        }
        return $moviesList;
    }

    // Restituisce tutte le proprietà dell'oggetto film formattate in un array associativo, 
    // ideale per il salvataggio su database documentali (come MongoDB) o per risposte JSON.
    public function toArray(): array
    {
        return [
            'titolo' => $this->titolo,
            'titolo_orig' => $this->titolo_orig,
            'trama' => $this->trama,
            'poster_path' => $this->poster_path,
            'voto' => $this->voto,
            'durata' => $this->durata,
            'anno' => $this->anno,
            'generi' => $this->generi,
            'paese' => $this->paese,
            'cast' => $this->cast,
            'registi' => $this->registi,
            'trailer_key' => $this->trailer_key
        ];
    }
}
?>
```

## File: pages/admin/film_db.php
```php
<?php
// Pagina admin di dettaglio film che legge i dati direttamente da MongoDB.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/movie_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;


// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


// Dichiarazione variabili
$movie_db = null;
$data     = [];
$errore   = "";

$movie_id = $_GET['tmdb_id'] ?? null;

if (empty($movie_id)) {
    $errore = "Nessun film selezionato";
} else {
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db          = $mongoClient->selectDatabase('cinevobis');
        $collection  = $db->selectCollection('films');

        $movie_db = $collection->findOne(
            ['id' => (int)$movie_id],
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        );

        if ($movie_db === null) {
            $errore = "Film non trovato nel database";
        } else {
            $data = (new movieObj($movie_db))->toArray();
        }

    } catch (Exception $e) {
        error_log("Errore MongoDB: " . $e->getMessage());
        $errore = "Errore di connessione al database";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $movie_db ? htmlspecialchars($data['titolo']) : 'Film' ?> - Cinevobis</title>
    
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        .cast-avatar { width: 60px; height: 60px; object-fit: cover; }
        .text-justify { text-align: justify; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container my-5 flex-grow-1">
        <?php if ($movie_db): ?>
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card p-4 p-md-5 mb-5">

                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <?php if ($data['poster_path']): ?>
                                    <img src="https://image.tmdb.org/t/p/w500<?= $data['poster_path'] ?>" 
                                         class="img-fluid rounded-4 shadow-md w-100" 
                                         alt="Poster">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center rounded-4 shadow-sm w-100" 
                                         style="aspect-ratio: 2/3; background-color: var(--bg-muted); border: 2px dashed var(--border);">
                                        <div class="text-center">
                                            <i class="bi bi-film text-muted" style="font-size: 3.5rem;"></i>
                                            <p class="text-muted small mt-2 fw-medium">Poster non disponibile</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($data['trailer_key']): ?>
                                    <div class="mt-4">
                                        <button type="button"
                                            class="btn btn-dark w-100 py-2 d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trailerModal">
                                            <i class="bi bi-play-circle-fill fs-5 me-2"></i> Trailer
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8">
                                <h1 class="fw-bold display-5 mb-3" style="color: var(--text);"><?= htmlspecialchars($data['titolo']) ?></h1>

                                <?php if (!empty($data['titolo_orig']) && strcasecmp(trim($data['titolo_orig']), trim($data['titolo'])) !== 0): ?>
                                    <p class="fs-5 mb-4" style="color: var(--text-muted);"><?= htmlspecialchars($data['titolo_orig']) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($data['registi'])): ?>
                                    <div class="mb-4">
                                        <small class="text-uppercase fw-bold d-block mb-1" style="letter-spacing: 1px; color: var(--text-muted);">Regia</small>
                                        <p class="fs-5 fw-medium mb-0" style="color: var(--text);">
                                            <?php
                                            $registi_links = array_map(function ($regista) {
                                                $name = htmlspecialchars($regista['name']);
                                                $id   = urlencode($regista['id']);
                                                return "<a href='https://www.themoviedb.org/person/$id' class='text-decoration-none' style='color: var(--accent); transition: color 0.2s;' onmouseover='this.style.color=\"var(--accent-hover)\"' onmouseout='this.style.color=\"var(--accent)\"'>$name</a>";
                                            }, $data['registi']);
                                            echo implode(', ', $registi_links);
                                            ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($data['generi'] as $genre): ?>
                                        <span class="badge px-3 py-2" 
                                              style="background-color: var(--bg-muted); color: var(--text); border: 1px solid var(--border);">
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="border-top pt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="fw-bold m-0" style="color: var(--text);">Trama</h4>
                                        <div class="d-flex align-items-center fs-4 fw-bold">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            <span>
                                                <?= number_format($data['voto'], 1) ?>
                                                <small style="color: var(--text-muted);" class="fw-normal fs-6">/ 10</small>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-justify lh-lg fs-6 mb-4" style="color: var(--text-muted);"><?= nl2br(htmlspecialchars($data['trama'])) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center py-4 rounded-4 mb-5 mx-0" style="background-color: var(--bg-muted); border: 1px solid var(--border);">
                            <div class="col-4 border-end" style="border-color: var(--border) !important;">
                                <div class="small text-uppercase fw-bold" style="color: var(--text-muted);">Durata</div>
                                <div class="fw-bold fs-5" style="color: var(--text);"><?= $data['durata'] ?> min</div>
                            </div>
                            <div class="col-4 border-end" style="border-color: var(--border) !important;">
                                <div class="small text-uppercase fw-bold" style="color: var(--text-muted);">Anno</div>
                                <div class="fw-bold fs-5" style="color: var(--text);"><?= $data['anno'] ?></div>
                            </div>
                            <div class="col-4">
                                <div class="small text-uppercase fw-bold" style="color: var(--text-muted);">Paese</div>
                                <div class="fw-bold fs-5" style="color: var(--text);"><?= $data['paese'] ?></div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <h4 class="fw-bold mb-4" style="color: var(--text);">Cast Principale</h4>
                            <div class="row g-3">
                                <?php foreach ($data['cast'] as $actor):
                                    $profile = $actor['profile_path']
                                        ? "https://image.tmdb.org/t/p/w185" . $actor['profile_path']
                                        : "https://ui-avatars.com/api/?name=" . urlencode($actor['name']) . "&background=f1f5f9&color=64748b";
                                ?>
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <a href="https://www.themoviedb.org/person/<?= $actor['id'] ?>"
                                           class="text-decoration-none d-block">
                                            <div class="d-flex align-items-center p-2 rounded-3 transition-hover" 
                                                 style="background-color: var(--bg-surface); border: 1px solid var(--border);">
                                                <img src="<?= $profile ?>"
                                                     class="cast-avatar rounded-circle border border-2 border-white shadow-sm me-3"
                                                     loading="lazy"
                                                     alt="<?= htmlspecialchars($actor['name']) ?>">
                                                <div class="overflow-hidden">
                                                    <p class="mb-0 fw-bold text-truncate" style="font-size: 0.95rem; color: var(--text);">
                                                        <?= htmlspecialchars($actor['name']) ?>
                                                    </p>
                                                    <p class="mb-0 text-truncate" style="font-size: 0.85rem; color: var(--text-muted);">
                                                        <?= htmlspecialchars($actor['character']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php if ($data['trailer_key']): ?>
            <div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="ratio ratio-16x9 shadow-lg rounded-4 overflow-hidden" style="background: #000;">
                                <iframe id="trailerVideo"
                                    data-src="https://www.youtube.com/embed/<?= $data['trailer_key'] ?>?rel=0&autoplay=1"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning shadow-sm rounded-4"><?= htmlspecialchars($errore) ?></div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: actions/logout.php
```php
<?php
// Esegue il logout dell'utente, aggiorna la sessione nel DB e termina la sessione.
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

// Controllo accesso
if (!isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

try {
    $id_sessione = session_id();
    $username = $_SESSION['username'];

    $user = new userObj($conn, $username);
    $user->setDataLogout(date('Y-m-d H:i:s'), $id_sessione);

} catch (Exception $e) {
    error_log("Errore durante il logout: " . $e->getMessage());

} finally {
    // Cancella completamente la sessione e reindirizza l'utente alla home con il flag di logout avvenuto
    // Viene chiamata nel blocco `finally` per garantire l'esecuzione in ogni caso
    session_unset();                                
    session_destroy();                              
    header("Location: /index.php?logout=success");
    exit();
}
```

## File: includes/footer.php
```php
<?php
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$adminPages = ['add_film.php', 'dashboard.php', 'sessions.php', 'users.php', 'edit_user.php', 'notifications.php', 'films.php', 'film_db.php'];

$isAdminPage = in_array($currentPage, $adminPages);
?>

<footer class="border-top px-3 px-lg-4 py-3">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">

        <!-- Brand + copyright -->
        <div class="d-flex align-items-center gap-2">
            <span lass="fw-bold text-dark">Cinevobis</span>
            <span class="text-secondary small">
                © <?= date("Y") ?>
                <?= $isAdminPage ? '— Area admin' : '' ?>
            </span>
        </div>

        <!-- Link -->
        <nav class="d-flex align-items-center gap-1" aria-label="Footer">
            <?php if ($isAdminPage): ?>
                <a href="/" class="btn btn-sm btn-link text-secondary text-decoration-none p-1 px-2" style="font-size: 0.75rem;">
                    Torna al sito
                </a>
            <?php else: ?>
                <a href="/pages/public/terms.php" class="btn btn-sm btn-link text-secondary text-decoration-none p-1 px-2" style="font-size: 0.75rem;">Termini di servizio</a>
                <span class="text-secondary" style="font-size: 0.75rem;">·</span>
                <a href="/pages/public/privacy.php" class="btn btn-sm btn-link text-secondary text-decoration-none p-1 px-2" style="font-size: 0.75rem;">Privacy</a>
                <span class="text-secondary" style="font-size: 0.75rem;">·</span>
                <a href="/actions/contact.php" class="btn btn-sm btn-link text-secondary text-decoration-none p-1 px-2" style="font-size: 0.75rem;">Contattaci</a>
            <?php endif; ?>
        </nav>

    </div>
</footer>
```

## File: includes/user_obj.php
```php
<?php
// Rappresenta un utente e raggruppa le operazioni CRUD sugli account, le sessioni
// e le liste personali (preferiti, watchlist, watched, recensioni).
class userObj {
    private string $username;
    private ?string $password;
    private ?string $nome;
    private ?string $cognome;
    private ?string $email;
    private ?int $id_profilo;
    private ?int $attivo;
    private PDO $db;

    // Costruttore della classe: inizializza le proprietà dell'oggetto utente.
    // Se viene passata una password, ne genera automaticamente l'hash.
    public function __construct(PDO $db, string $username, ?string $password = null, ?string $nome = null, ?string $cognome = null,
                            ?string $email = null, ?int $attivo = null, ?int $id_profilo = null) {
        $this->db           = $db;
        $this->username     = $username;
        $this->password     = $password ? password_hash($password, PASSWORD_DEFAULT) : null;
        $this->nome         = $nome;
        $this->cognome      = $cognome;
        $this->email        = $email;
        $this->attivo       = $attivo;
        $this->id_profilo   = $id_profilo;
    }

    // -------------------------------------------------------------------------
    // CRUD UTENTI
    // -------------------------------------------------------------------------

    // Crea un nuovo record utente nel database utilizzando i dati attualmente impostati nell'oggetto.
    public function create() {
        $sql = "INSERT INTO utenti 
                    (username, password, nome, cognome, email, attivo, id_profilo, data_registrazione)
                VALUES 
                    (:username, :password, :nome, :cognome, :email, :attivo, :id_profilo, :data_registrazione)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':username'           => $this->username,
            ':password'           => $this->password,
            ':nome'               => $this->nome,
            ':cognome'            => $this->cognome,
            ':email'              => $this->email,
            ':attivo'             => $this->attivo ?? 1,
            ':id_profilo'         => $this->id_profilo,
            ':data_registrazione' => date('Y-m-d H:i:s')
        ]);
    }

    // Cerca e restituisce tutti i dati di un singolo utente filtrando per il suo username.
    public function findByUsername() {
        $sql = "SELECT id_utente, username, password, nome, cognome, email,
                       attivo, id_profilo, data_registrazione
                FROM utenti WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Recupera la lista di tutti gli utenti registrati, includendo anche il nome del loro profilo (es. Admin, User).
    public function readAll() {
        $sql = "SELECT u.id_utente, u.username, u.nome, u.cognome, u.email,
                       u.attivo, p.nome_profilo
                FROM utenti u
                LEFT JOIN profili p ON p.id_profilo = u.id_profilo
                ORDER BY u.username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Aggiorna le informazioni anagrafiche, lo stato e il profilo di un utente esistente.
    public function update(string $usernameOriginale) {
        $sql = "UPDATE utenti SET
                    nome       = :nome,
                    cognome    = :cognome,
                    email      = :email,
                    attivo     = :attivo,
                    id_profilo = :id_profilo
                WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome'       => $this->nome,
            ':cognome'    => $this->cognome,
            ':email'      => $this->email,
            ':attivo'     => $this->attivo,
            ':id_profilo' => $this->id_profilo,
            ':username'   => $usernameOriginale
        ]);
    }

    // Modifica la password dell'utente previa verifica che la password attuale inserita sia corretta.
    public function changePassword(string $passwordAttuale, string $nuovaPassword) {
        $utente = $this->findByUsername();

        if (!$utente) {
            return ['ok' => false, 'errore' => 'Utente non trovato'];
        }

        if (!password_verify($passwordAttuale, $utente['password'])) {
            return ['ok' => false, 'errore' => 'Password attuale non corretta'];
        }

        $hash = password_hash($nuovaPassword, PASSWORD_DEFAULT);
        $sql  = "UPDATE utenti SET password = :password WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':password' => $hash, ':username' => $this->username]);

        return ['ok' => true];
    }

    // Rimuove definitivamente l'utente dal database.
    public function delete() {
        $sql  = "DELETE FROM utenti WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':username' => $this->username]);
    }

    // -------------------------------------------------------------------------
    // SESSIONI
    // -------------------------------------------------------------------------

    // Registra un nuovo evento di login, salvando l'ID specifico della sessione, l'utente associato e la data d'ingresso.
    public function createDataLogin(string $value, string $id_sessione, int $id_utente) {
        $sql = "INSERT INTO sessioni (id_sessione, id_utente, data_login)
                VALUES (:id_s, :id_u, :data_login)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_s'       => $id_sessione,
            ':id_u'       => $id_utente,
            ':data_login' => $value
        ]);
    }

    // Registra un evento di logout, aggiornando la riga della sessione esistente con la data di uscita.
    public function setDataLogout(string $value, string $id_sessione) {
        $sql = "UPDATE sessioni SET data_logout = :value WHERE id_sessione = :id_s";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':value' => $value,
            ':id_s'  => $id_sessione
        ]);
    }

    // Ottiene l'elenco degli ultimi accessi (login/logout) degli utenti, limitando il numero di risultati restituiti a $num.
    public function readAccess(int $num) {
        $sql = "SELECT u.username, u.nome, u.cognome, s.data_login, s.data_logout
                FROM sessioni s
                JOIN utenti u ON u.id_utente = s.id_utente
                ORDER BY s.data_login DESC
                LIMIT :num";
        $stmt = $this->db->prepare($sql);
        $num  = (int)$num;
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // -------------------------------------------------------------------------
    // LISTE FILM (preferiti, watchlist, watched)
    // -------------------------------------------------------------------------

    // Metodo interno condiviso: aggiunge un film a una lista (tabella) per l'utente.
    private function addToList(string $tabella, int $tmdb_id, int $id_utente): void {
        $sql  = "INSERT INTO {$tabella} (tmdb_id, id_utente, data_aggiunto) VALUES (:tmdb_id, :id_utente, :data_aggiunto)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':tmdb_id'      => $tmdb_id,
            ':id_utente'    => $id_utente,
            ':data_aggiunto' => date('Y-m-d H:i:s')
        ]);
    }

    // Metodo interno condiviso: rimuove un film da una lista (tabella) per l'utente.
    private function removeFromList(string $tabella, int $tmdb_id, int $id_utente): void {
        $sql  = "DELETE FROM {$tabella} WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utente' => $id_utente, ':tmdb_id' => $tmdb_id]);
    }

    // Metodo interno condiviso: verifica se un film è già presente in una lista (tabella) per l'utente.
    private function isInList(string $tabella, int $tmdb_id, int $id_utente): bool {
        $sql  = "SELECT 1 FROM {$tabella} WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utente' => $id_utente, ':tmdb_id' => $tmdb_id]);
        return (bool) $stmt->fetchColumn();
    }

    // Aggiunge il film ai preferiti dell'utente.
    public function addFavorite(int $tmdb_id, int $id_utente): void {
        $this->addToList('preferiti', $tmdb_id, $id_utente);
    }

    // Rimuove il film dai preferiti dell'utente.
    public function removeFavorite(int $tmdb_id, int $id_utente): void {
        $this->removeFromList('preferiti', $tmdb_id, $id_utente);
    }

    // Restituisce true se il film è tra i preferiti dell'utente.
    public function isFavorite(int $tmdb_id, int $id_utente): bool {
        return $this->isInList('preferiti', $tmdb_id, $id_utente);
    }

    // Aggiunge il film alla watchlist dell'utente.
    public function addWatchlist(int $tmdb_id, int $id_utente): void {
        $this->addToList('watchlist', $tmdb_id, $id_utente);
    }

    // Rimuove il film dalla watchlist dell'utente.
    public function removeWatchlist(int $tmdb_id, int $id_utente): void {
        $this->removeFromList('watchlist', $tmdb_id, $id_utente);
    }

    // Restituisce true se il film è nella watchlist dell'utente.
    public function isInWatchlist(int $tmdb_id, int $id_utente): bool {
        return $this->isInList('watchlist', $tmdb_id, $id_utente);
    }

    // Aggiunge il film alla lista "visti" dell'utente.
    public function addWatched(int $tmdb_id, int $id_utente): void {
        $this->addToList('watched', $tmdb_id, $id_utente);
    }

    // Rimuove il film dalla lista "visti" dell'utente.
    public function removeWatched(int $tmdb_id, int $id_utente): void {
        $this->removeFromList('watched', $tmdb_id, $id_utente);
    }

    // Restituisce true se il film è nella lista "visti" dell'utente.
    public function isWatched(int $tmdb_id, int $id_utente): bool {
        return $this->isInList('watched', $tmdb_id, $id_utente);
    }

    // -------------------------------------------------------------------------
    // RECENSIONI
    // -------------------------------------------------------------------------

    // Restituisce true se l'utente ha già scritto una recensione per il film.
    public function hasReview(int $tmdb_id, int $id_utente): bool {
        $sql  = "SELECT 1 FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_utente' => $id_utente, ':tmdb_id' => $tmdb_id]);
        return (bool) $stmt->fetchColumn();
    }

    // Conta il numero totale di recensioni della community per un dato film.
    public function countReviews(int $tmdb_id): int {
        $sql  = "SELECT COUNT(*) FROM recensioni WHERE tmdb_id = :tmdb_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tmdb_id' => $tmdb_id]);
        return (int) $stmt->fetchColumn();
    }
}
?>
```

## File: pages/user/review.php
```php
<?php
// Pagina per scrivere o modificare una recensione dell'utente.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');

// Controllo autenticazione + tmdb_id
$username = $_SESSION['username'] ?? '';
$tmdb_id = $_GET['tmdb_id'] ?? null;

if (!$username && !$tmdb_id) {
    header("Location: /index.php");
    exit();
}


// Dichiarazione variabili
$errore = '';
$messaggio = '';
$recensione_esistente = null;

// id utente dalla sessione
$id_utente = $_SESSION['id_utente'] ?? null;


// Recupera la recensione esistente (se c'è)
try {
    $sql = "SELECT * FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_utente' => $id_utente, 
        ':tmdb_id' => $tmdb_id
    ]);

    $recensione_esistente = $stmt->fetch();
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}


// Gestione POST
if (isset($_POST['write_review'])) {
    $voto = $_POST['rating'] ?? null;
    $commento = $_POST['commento'] ?? null;

    if (!$voto || !$commento) {
        $errore = "Compila tutti i campi";

    } elseif ($voto < 1 || $voto > 10) {
        $errore = "Il voto deve essere compreso tra 1 e 10";

    } else {
        try {
            if ($recensione_esistente) {
                // Aggiorna recensione esistente
                $sql = "UPDATE recensioni SET voto = :voto, commento = :commento, data_aggiunto = :data_aggiunto
                        WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

            } else {
                // Inserisce nuova recensione
                $sql = "INSERT INTO recensioni (tmdb_id, id_utente, data_aggiunto, commento, voto)
                        VALUES (:tmdb_id, :id_utente, :data_aggiunto, :commento, :voto)";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':tmdb_id' => $tmdb_id,
                ':id_utente' => $id_utente,
                ':data_aggiunto' => date('Y-m-d H:i:s'),
                ':commento' => trim($commento),
                ':voto' => (float)$voto
            ]);

            $messaggio = $recensione_esistente ? "Recensione aggiornata" : "Recensione pubblicata";

            // Segna automaticamente come visto (watched)
            $sql_check_watched = "SELECT 1 FROM watched WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";
            $stmt_check_watched = $conn->prepare($sql_check_watched);
            $stmt_check_watched->execute([
                ':id_utente' => $id_utente,
                ':tmdb_id' => $tmdb_id
            ]);

            if (!$stmt_check_watched->fetch()) {
                $sql_insert_watched = "INSERT INTO watched (tmdb_id, id_utente, data_aggiunto) 
                                       VALUES (:tmdb_id, :id_utente, :data_aggiunto)";
                $stmt_insert_watched = $conn->prepare($sql_insert_watched);
                $stmt_insert_watched->execute([
                    ':tmdb_id' => $tmdb_id,
                    ':id_utente' => $id_utente,
                    ':data_aggiunto' => date('Y-m-d H:i:s')
                ]);
            }

            // Inizializza per pulire la pagina dopo l'invio della recensione
            $recensione_esistente = [];

        } catch (PDOException $e) {
            error_log("Errore nel DB: " . $e->getMessage());
            $errore = "Errore nel salvataggio della recensione";
        }
    }
}

// Eliminare film
if (isset($_POST['delete_review'])) {
    try {
        $sql = "DELETE FROM recensioni WHERE id_utente = :id_utente AND tmdb_id = :tmdb_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id_utente' => $id_utente, 
            ':tmdb_id' => $tmdb_id
        ]);

        header("Location: /pages/public/film.php?tmdb_id=" . urlencode($tmdb_id));
        exit();

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
        $errore = "Errore nella cancellazione della recensione";
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $recensione_esistente ? 'Modifica recensione' : 'Scrivi recensione' ?> - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 px-4">

                <a href="/pages/public/film.php?tmdb_id=<?= urldecode($tmdb_id) ?>" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div class="text-center mb-4">
                    <h1 class="display-6 fw-bolder mb-2">
                        <?= $recensione_esistente ? 'Modifica recensione' : 'Scrivi recensione' ?>
                    </h1>
                    <p class="text-secondary mb-3">
                        <?= $recensione_esistente
                            ? 'Aggiorna la tua opinione'
                            : 'Condividi la tua opinione' ?>
                    </p>
                    
                    <div class="d-inline-flex align-items-center text-muted opacity-75" style="font-size: 0.85rem;">
                        <i class="bi bi-info-circle me-2"></i>
                        <span>Il film verrà aggiunto automaticamente alla tua <strong>watched</strong></span>
                    </div>
                </div>

                <?php if ($errore): ?>
                    <div class="alert alert-danger border-0 small py-2 mb-4 text-center">
                        <?= htmlspecialchars($errore) ?>
                    </div>
                <?php endif; ?>

                <?php if ($messaggio): ?>
                    <div class="alert alert-success border-0 small py-2 mb-4 text-center">
                        <?= htmlspecialchars($messaggio) ?>
                        <a href="/pages/user/reviews.php" class="fw-bold text-decoration-none">vedi</a>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="tmdb_id" value="<?= htmlspecialchars($tmdb_id) ?>">

                    <div class="mb-4">
                        <label class="form-label small text-secondary">Voto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light">
                                <i class="bi bi-star-fill text-warning"></i>
                            </span>
                            <input
                                type="number"
                                name="rating"
                                step="0.1"
                                id="rating"
                                class="form-control bg-light border-light py-3"
                                min="1"
                                max="10"
                                placeholder="Da 1 a 10"
                                value="<?= htmlspecialchars($recensione_esistente['voto'] ?? '') ?>"
                                required>
                            <span class="input-group-text bg-light border-light text-secondary">/ 10</span>
                        </div>
                    </div>

                    <hr class="my-3 opacity-25">

                    <div class="mb-4">
                        <label class="form-label small text-secondary">Commento</label>
                        <textarea
                            name="commento"
                            id="commento"
                            class="form-control bg-light border-light"
                            rows="6"
                            maxlength="200"
                            placeholder="Scrivi qui la tua recensione..."
                            required><?= htmlspecialchars($recensione_esistente['commento'] ?? '') ?></textarea>
                            <div class="form-text text-end">
                                Limite massimo: 200 caratteri
                            </div>
                    </div>

                    <button type="submit" name="write_review" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-3">
                        <?= $recensione_esistente ? 'Aggiorna recensione' : 'Pubblica recensione' ?>
                    </button>

                    <?php if ($recensione_esistente): ?>
                        <button type="submit" onclick="closeAndRedirect()" name="delete_review" class="btn btn-outline-danger btn-lg w-100 py-3 fw-bold">
                            Elimina recensione
                        </button>
                    <?php endif; ?>

                </form>
            </div>
        </div>
    </div>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: assets/js/script.js
```javascript
document.addEventListener("DOMContentLoaded", function() {
    
    // --- 1. SALVATAGGIO PROVENIENZA (Login/Signup/Profile) ---
    const paginaAttuale = window.location.pathname;
    const provenienza = document.referrer;
    
    // Raggruppiamo le pagine che condividono questa logica
    const pagineTracciate = ['login.php', 'signup.php', 'change_password.php', 'profile.php', 'contact.php'];

    // Controlliamo se siamo in una di queste pagine
    const isPaginaTracciata = pagineTracciate.some(pagina => paginaAttuale.includes(pagina));

    if (isPaginaTracciata) {
        // Controlliamo se arriviamo da una delle altre pagine tracciate
        const arrivoDaPaginaInterna = pagineTracciate.some(pagina => provenienza.includes(pagina));

        // Sovrascriviamo l'URL di origine SOLO se arriviamo da una pagina esterna a questo gruppo
        if (!arrivoDaPaginaInterna) {
            sessionStorage.setItem('origin_url', provenienza !== "" ? provenienza : '/index.php');
        }
    }

    // --- 2. MOSTRA/NASCONDI PASSWORD ---
    const iconePassword = document.querySelectorAll('.toggle-icon');
    
    iconePassword.forEach(function(icona) {
        icona.addEventListener('click', function() {

            const inputId = this.getAttribute('data-target');
            const inputField = document.getElementById(inputId);

            if (inputField.type === 'password') {
                inputField.type = 'text';
                this.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                inputField.type = 'password';
                this.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });

    // --- 3. GESTIONE TRAILER (MODAL) ---
    const trailerModal = document.getElementById('trailerModal');
    const container = document.querySelector('#trailerModal .ratio'); 

    if (trailerModal && container) {
        const iframeOriginale = container.querySelector('iframe');
        
        // Aggiungi questo controllo di sicurezza
        if (iframeOriginale) {
            const videoUrlBase = iframeOriginale.getAttribute('data-src');
            const classiIframe = iframeOriginale.className;

            const iframeHTML = `<iframe src="${videoUrlBase}" class="${classiIframe}" allowfullscreen></iframe>`;
            container.innerHTML = '';

            trailerModal.addEventListener('show.bs.modal', function() {
                container.innerHTML = iframeHTML;
            });

            trailerModal.addEventListener('hidden.bs.modal', function() {
                container.innerHTML = '';
            });
        }
    }
});

// --- 4. FUNZIONE PER TORNARE INDIETRO ---
function closeAndRedirect() {
    const destinazione = sessionStorage.getItem('origin_url');
    sessionStorage.removeItem('origin_url');
    window.location.href = destinazione || '/index.php';
}
```

## File: pages/admin/dashboard.php
```php
<?php
// Dashboard admin che mostra le statistiche del sito:
// film in MongoDB, utenti, sessioni e notifiche non lette.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;


// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


// Dichiarazione variabili
$totaleFilm = 0;
$totaleUtenti = 0;
$totaleSessioni = 0;
$totaleNotifiche = 0;


// Connessione a MongoDB
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

    // Conteggio documenti
    $totaleFilm = $collection->countDocuments([]);
} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


// Conteggio utenti
try {
    $sql = "SELECT COUNT(*) FROM utenti";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleUtenti = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}


// Conteggio sessioni
try {
    $sql = "SELECT COUNT(*) FROM sessioni WHERE data_logout IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleSessioni = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}


// Conteggio notifiche
try {
    $sql = "SELECT COUNT(*) FROM notifiche WHERE letta = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $totaleNotifiche = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Errore DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* 1. Gestione Hover (Bootstrap non ha utility per transform) */
        .hover-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .hover-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important; /* Equivale a shadow-md */
            border-color: var(--accent, #0d6efd) !important;
        }
        
        /* 2. Micro-rifiniture non presenti in Bootstrap */
        .min-h-280 { min-height: 280px; }
        .letter-spacing-sm { letter-spacing: 0.5px; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container flex-grow-1 py-5">

        <div class="row mb-4">
            <div class="col-12 text-center text-sm-start">
                <h1 class="fw-bold h3 mt-1">
                    Dashboard <span class="text-muted fw-normal">| Benvenuto <?= htmlspecialchars($username) ?></span>
                </h1>
            </div>
        </div>

        <div class="row g-3 mb-5">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-white border-start border-4 h-100 d-flex flex-column justify-content-center p-3">
                    <div class="text-muted fw-bold text-uppercase mb-1 letter-spacing-sm" style="font-size: 0.75rem;">Film</div>
                    <div class="fw-bold text-dark fs-3"><?= number_format($totaleFilm, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-white border-start border-4 h-100 d-flex flex-column justify-content-center p-3">
                    <div class="text-muted fw-bold text-uppercase mb-1 letter-spacing-sm" style="font-size: 0.75rem;">Utenti</div>
                    <div class="fw-bold text-dark fs-3"><?= number_format($totaleUtenti, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-white border-start border-4 h-100 d-flex flex-column justify-content-center p-3">
                    <div class="text-muted fw-bold text-uppercase mb-1 letter-spacing-sm" style="font-size: 0.75rem;">Sessioni Attive</div>
                    <div class="fw-bold text-dark fs-3"><?= number_format($totaleSessioni, 0, ',', '.') ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm bg-white border-start border-4 h-100 d-flex flex-column justify-content-center p-3">
                    <div class="text-muted fw-bold text-uppercase mb-1 letter-spacing-sm" style="font-size: 0.75rem;">Messaggi</div>
                    <div class="fw-bold text-dark fs-3"><?= number_format($totaleNotifiche, 0, ',', '.') ?></div>
                </div>
            </div>
        </div>

        <h2 class="h6 fw-bold text-uppercase text-muted mb-4">Gestione Sistema</h2>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-3">
                <a href="films.php" class="card hover-card min-h-280 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center py-5 px-4 border rounded">
                    <div class="text-warning mb-3 display-3 lh-1"><i class="bi bi-collection-play-fill"></i></div>
                    <h3 class="fs-4 fw-bold text-dark mb-2">Archivio Film</h3>
                    <p class="text-muted fs-6 mb-0">Gestisci il catalogo multimediale e i film</p>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="users.php" class="card hover-card min-h-280 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center py-5 px-4 border rounded">
                    <div class="text-success mb-3 display-3 lh-1"><i class="bi bi-person-lines-fill"></i></div>
                    <h3 class="fs-4 fw-bold text-dark mb-2">Utenti</h3>
                    <p class="text-muted fs-6 mb-0">Amministra gli account e i ruoli utenti</p>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="sessions.php" class="card hover-card min-h-280 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center py-5 px-4 border rounded">
                    <div class="text-primary mb-3 display-3 lh-1"><i class="bi bi-shield-lock-fill"></i></div>
                    <h3 class="fs-4 fw-bold text-dark mb-2">Log Accessi</h3>
                    <p class="text-muted fs-6 mb-0">Monitora la sicurezza e le sessioni attive</p>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <a href="notifications.php" class="card hover-card min-h-280 text-decoration-none bg-white text-center d-flex flex-column align-items-center justify-content-center py-5 px-4 border rounded">
                    <div class="text-danger mb-3 display-3 lh-1"><i class="bi bi-chat-left-dots-fill"></i></div>
                    <h3 class="fs-4 fw-bold text-dark mb-2">Messaggi</h3>
                    <p class="text-muted fs-6 mb-0">Gestisci le comunicazioni degli utenti</p>
                </a>
            </div>
        </div>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: pages/admin/films.php
```php
<?php
// Pagina admin per la gestione del catalogo film. Mostra i film salvati in MongoDB
// e consente l'eliminazione di un documento tramite POST con l'ObjectId.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


$mongoClient = null;
$db = null;
$collection = [];

// Connessione a MongoDB
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

} catch (PDOException $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


$cursor = [];

// Recuperiamo i film (ordinati per data di aggiunta)
try {
    $cursor = $collection->find([], [
        'sort' => ['last_updated' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
        ]); 

} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


// Eliminazione film
if (isset($_POST['delete'])) {
    $id = $_POST['_id'] ?? '';

    try {
        $objectId = new MongoDB\BSON\ObjectId($id);
        $collection->deleteOne(['_id' => $objectId]);

        header("Location: films.php");
        exit();

    } catch (Exception $e) {
        error_log("Errore MongoDB: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Film - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        /* Bootstrap non ha row-cols-xl-10 di default, lo aggiungiamo */
        @media (min-width: 1200px) {
            .row-cols-xl-10 > * {
                flex: 0 0 auto;
                width: 10%;
            }
        }
        @media (min-width: 992px) {
            .row-cols-lg-8 > * {
                flex: 0 0 auto;
                width: 12.5%;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-4 fw-bold mb-0">Archivio Film</h1>
        </div>

        <?php if (!empty($cursor)): ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 row-cols-lg-8 row-cols-xl-10 g-2">
                <?php foreach($cursor as $movie): 
                    $titolo = $movie['title'] ?? 'Senza titolo';
                    $anno = !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '';
                    $poster = !empty($movie['poster_path']) ? "https://image.tmdb.org/t/p/w185" . $movie['poster_path'] : null;
                ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden transition-hover">
                        <a href="/pages/admin/film_db.php?tmdb_id=<?= urlencode($movie['id']) ?>" class="text-decoration-none text-dark d-block">
                            <?php if ($poster): ?>
                                <img src="<?= $poster ?>" 
                                     alt="<?= htmlspecialchars($titolo) ?>" 
                                     class="card-img-top w-100"
                                     style="object-fit: cover; aspect-ratio: 2/3;">
                            <?php else: ?>
                                <div class="bg-secondary d-flex align-items-center justify-content-center w-100" style="aspect-ratio: 2/3;">
                                    <i class="bi bi-film text-white fs-4"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="card-body p-1">
                            <h6 class="card-title mb-1 text-truncate" style="font-size: 0.75rem;" title="<?= htmlspecialchars($titolo) ?>">
                                <?= htmlspecialchars($titolo) ?>
                            </h6>
                            
                            <form method="POST" class="mt-0">
                                <input type="hidden" name="_id" value="<?= (string)$movie['_id'] ?>">
                                <button type="submit" name="delete"
                                        class="btn btn-link p-0 text-danger"
                                        style="font-size: 0.8rem;"
                                        title="Elimina">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center rounded-3 border-0 shadow-sm">
                <i class="bi bi-info-circle me-2"></i> Nessun film trovato nel database
            </div>
        <?php endif; ?>

    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/admin/notifications.php
```php
<?php
// Pagina admin per gestire le notifiche inviate dagli utenti.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


// Aggiornare notifica letta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id_notifica'] ?? 0);

    if($id > 0) {
        try {
            $sql = "UPDATE notifiche SET letta = 1 WHERE id_notifica = :id_n";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id_n' => $id]);

        } catch (PDOException $e) {
            error_log("Errore: " . $e->getMessage());
        }
    }
}


// Recupero notifiche
$notifiche = "";

try {
    $sql = "SELECT * 
            FROM notifiche n
            LEFT JOIN utenti u ON n.id_utente = u.id_utente
            ORDER BY n.data_invio DESC";
             
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $notifiche = $stmt->fetchAll();

} catch (PDOException $e) {
    error_log("Errore: " . $e->getMessage());
}


// Eliminare notifiche lette
if (isset($_POST['delete'])) {
    try {
        $sql = "DELETE FROM notifiche WHERE letta = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        header("Location: /pages/admin/notifications.php");
        exit();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifiche - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-4 fw-bold mb-0">Messaggi</h1>
        </div>

        <?php
        $lette = [];
        $nonLette = [];

        foreach ($notifiche as $n) {
            if ($n['letta']) 
                $lette[] = $n;
            else 
                $nonLette[] = $n;        
        }
        ?>

        <?php if (empty($notifiche)): ?>
            <div class="text-center text-muted py-5">
                <p class="mb-0">Nessun messaggio disponibile</p>
            </div>
        <?php else: ?>

            <h6 class="text-uppercase text-muted fw-semibold small mb-3">Non letti</h6>
            <?php if (empty($nonLette)): ?>
                <p class="text-muted small mb-4">Nessun messaggio da leggere</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3 mb-5">
                    <?php foreach ($nonLette as $notifica): ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body px-4 py-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="d-flex flex-column gap-1 flex-grow-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold"><?= htmlspecialchars($notifica['titolo'] ?? '—') ?></span>
                                            <span class="text-muted small">·</span>
                                            <span class="text-muted small"><?= htmlspecialchars($notifica['username'] ?? 'Sconosciuto') ?></span>
                                        </div>
                                        <p class="mb-0 text-muted small"><?= nl2br(htmlspecialchars($notifica['descrizione'] ?? '')) ?></p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-1 text-nowrap">
                                        <span class="badge bg-light text-muted fw-normal border small">
                                            <?= htmlspecialchars($notifica['data_invio'] ?? '') ?>
                                        </span>
                                        <form method="POST">
                                            <input type="hidden" name="id_notifica" value="<?= $notifica['id_notifica'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Segna come letta">&#10003;</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h6 class="text-uppercase text-muted fw-semibold small mb-3">Letti</h6>

            <form method="POST" class="mb-3">
                <button type="submit" name="delete" class="btn btn-outline-danger btn-sm px-3 d-flex align-items-center gap-2">
                    <i class="bi bi-trash3"></i>
                </button>
            </form>

            <?php if (empty($lette)): ?>
                <p class="text-muted small">Nessuna notifica letta</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($lette as $notifica): ?>
                        <div class="card border-0 shadow-sm opacity-50">
                            <div class="card-body px-4 py-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="d-flex flex-column gap-1 flex-grow-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold text-muted"><?= htmlspecialchars($notifica['titolo'] ?? '—') ?></span>
                                            <span class="text-muted small">·</span>
                                            <span class="text-muted small"><?= htmlspecialchars($notifica['username'] ?? 'Sconosciuto')?></span>
                                        </div>
                                        <p class="mb-0 text-muted small"><?= nl2br(htmlspecialchars($notifica['descrizione'] ?? '')) ?></p>
                                    </div>
                                    <span class="badge bg-light text-muted fw-normal border small mt-1 text-nowrap">
                                        <?= htmlspecialchars($notifica['data_invio'] ?? '') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/public/signup.php
```php
<?php
// Pagina di registrazione: crea un nuovo account utente se i dati sono validi.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

// Controllo utente
if (isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

$errore = ""; 
$messaggio = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nome = trim($_POST['nome']);
    $cognome = trim($_POST['cognome']);
    $email = trim($_POST['email']);
    $attivo = 1;                            // Default: account attivo
    $id_profilo = 2;                        // Default: ruolo utente
    
    try {  
        $user = new userObj($conn, $username, $password, $nome, $cognome, $email, $attivo, $id_profilo);
        $user->create();
        $messaggio = "Account creato con successo";
    } catch (PDOException $e) { 
        $errore = "Username non disponibile";
        error_log("Username non disponibile: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .ts-wrapper .ts-control {
            min-height: calc(1.5em + 1rem + 2px) !important;
            padding: 1rem 0.75rem !important;
            background-color: #f8f9fa !important;
            border-color: #f8f9fa !important;
            border-radius: 0.375rem !important;
            font-size: 1rem !important;
            line-height: 1.5 !important;
            box-shadow: none !important;
        }
        .ts-wrapper .ts-control .item { line-height: 1.5 !important; }
        .ts-wrapper .ts-control .dropdown-indicator { padding-top: 0.25rem !important; }
        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.05) !important;
            border-color: #dee2e6 !important;
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4 py-5 overflow-auto">

                <a href="javascript:void(0)" 
                    onclick="closeAndRedirect()" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div style="max-width: 450px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Crea il tuo account</h1>
                    <p class="text-secondary mb-5">Unisciti alla community</p>

                    <?php if ($errore): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4"><?= htmlspecialchars($errore) ?></div>
                    <?php endif; ?>
                    <?php if ($messaggio): ?>
                        <div class="alert alert-success border-0 small py-2 mb-4"><?= htmlspecialchars($messaggio) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <input type="text" name="nome" class="form-control bg-light border-light py-3" placeholder="Nome" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="cognome" class="form-control bg-light border-light py-3" placeholder="Cognome" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="email" name="email" class="form-control bg-light border-light py-3" placeholder="Email" required>
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control bg-light border-light py-3" placeholder="Username" required>
                        </div>
                        
                        <div class="mb-5 position-relative password-wrapper">
                            <input type="password" name="password" id="password" class="form-control bg-light border-light py-3" 
                                placeholder="Password" required>
                            <i class="bi bi-eye toggle-icon" data-target="password"></i>
                        </div>
                        
                        <button type="submit" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">Crea un account</button>
                    </form>

                    <p class="text-center small text-secondary">Hai un account? <a href="login.php" class="text-dark fw-bold text-decoration-none">Accedi</a></p>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block bg-secondary" 
                 style="background-image: url('/assets/img/interstellar.jpg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/user/profile.php
```php
<?php
// Pagina profilo utente: mostra i dati dell'account e gestisce azioni sul profilo.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

$userData = null;
$dataRegistrazione = "N/D";
$user = new userObj($conn, $username);

// Recuperiamo i dati utente
$userData = $user->findByUsername();
if ($userData && $userData['data_registrazione']) {
    $date = new DateTime($userData['data_registrazione']);
    $dataRegistrazione = $date->format('Y');
}

// Gestione Cambia Password
if (isset($_POST['change_password'])) {
    header("Location: /actions/change_password.php");
    exit();
}

// Gestione Eliminazione Account (Porta subito alla Home)
if (isset($_POST['delete_user']) && $username) {
    try {
        if ($user->delete()) {
            // Distruggiamo la sessione per sicurezza prima del redirect
            session_destroy();
            header("Location: /index.php");
            exit();
        }
    } catch (PDOException $e) {
        $errore = "Errore durante l'eliminazione: " . $e->getMessage();
    }
}

// Film visti nell'anno corrente
$count = 0;

try {
    $sql = "SELECT COUNT(*) FROM watched WHERE id_utente = :id_utente AND YEAR(data_aggiunto) = YEAR(CURRENT_DATE)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_utente' => $_SESSION['id_utente']]);

    $count = $stmt->fetchColumn();

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body style="background-color: var(--bg);">

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4 vh-100 overflow-hidden">
                
                <a href="javascript:void(0)"
                   onclick="closeAndRedirect()"
                   class="btn-close position-absolute top-0 start-0 m-4"
                   aria-label="Close">
                </a>

                <div style="max-width: 550px; width: 100%;">
                    
                    <?php if (isset($errore)): ?>
                        <div class="alert alert-danger mb-4" role="alert">
                            <?= htmlspecialchars($errore) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($userData): ?>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-4" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(99, 102, 241, 0.1); color: var(--accent);">
                                            <i class="bi bi-film fs-5"></i>
                                        </div>
                                        <div>
                                            <p class="small fw-bold mb-0" style="color: var(--text-muted);">Film visti nel <?= date('Y') ?></p>
                                            <h4 class="mb-0 fw-bolder"><?= htmlspecialchars($count); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-4" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(99, 102, 241, 0.1); color: var(--accent);">
                                            <i class="bi bi-calendar-check fs-5"></i>
                                        </div>
                                        <div>
                                            <p class="small fw-bold mb-0" style="color: var(--text-muted);">Membro dal</p>
                                            <h4 class="mb-0 fw-bolder"><?= htmlspecialchars($dataRegistrazione); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-4 mb-4" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm); overflow: hidden;">
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom gap-3">
                                <span style="color: var(--text-muted); white-space: nowrap;">Username</span>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($userData['username']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom gap-3">
                                <span style="color: var(--text-muted); white-space: nowrap;">Email</span>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($userData['email']) ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 border-bottom gap-3">
                                <span style="color: var(--text-muted); white-space: nowrap;">Nome</span>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($userData['nome'] ?? 'Non inserito') ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 gap-3">
                                <span style="color: var(--text-muted); white-space: nowrap;">Cognome</span>
                                <span class="fw-medium text-end text-truncate"><?= htmlspecialchars($userData['cognome'] ?? 'Non inserito') ?></span>
                            </div>
                        </div>
                        
                        <form method="POST">
                            <div class="rounded-4 overflow-hidden" style="background-color: var(--bg-surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                                <div class="p-1 border-bottom">
                                    <button type="submit" name="change_password" class="btn w-100 d-flex justify-content-between align-items-center text-start border-0" style="color: var(--text); padding: 12px;">
                                        <span class="fw-medium">Modifica la password</span>
                                        <i class="bi bi-chevron-right" style="color: var(--text-muted);"></i>
                                    </button>
                                </div>
                                <div class="p-1 bg-danger bg-opacity-10">
                                    <button type="submit" name="delete_user" class="btn w-100 d-flex justify-content-between align-items-center text-start text-danger border-0" style="padding: 12px;" onclick="return confirm('Stai per eliminare definitivamente il tuo account su Cinevobis. Confermi?');">
                                        <span class="fw-bold">Elimina account</span>
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                            
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block" 
                 style="background-image: url('/assets/img/astronaut.jpeg'); background-size: cover; background-position: center; border-left: 1px solid var(--border);">
            </div>

        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/public/login.php
```php
<?php
// Pagina di login: valida credenziali, rigenera la sessione e imposta i dati utente.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');

// Controllo utente
if (isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

$errore = "";

// Logica login
if (isset($_POST['login'])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        $user   = new userObj($conn, $username, $password);
        $utente = $user->findByUsername();

        if ($utente && password_verify($password, $utente['password'])) {
            if ($utente['attivo'] != 0) {
                // Previene la Session Fixation rigenerando l'ID al cambio di privilegi (login)
                session_regenerate_id(true);

                $_SESSION['id_utente'] = $utente['id_utente'];
                $_SESSION['username']  = $utente['username'];
                $_SESSION['id_profilo'] = $utente['id_profilo'];
                $_SESSION['nome'] = $utente['nome'];

                $user->createDataLogin(date('Y-m-d H:i:s'), session_id(), $utente['id_utente']);

                header("Location: /index.php");
                exit();
            } else { 
                $errore = "Utente non attivo"; 
            }
            
        } else { 
            $errore = "Dati non validi"; 
        }

    } catch (PDOException $e) { 
        $errore = "Errore"; 
        error_log("Errore: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid p-0 overflow-hidden">
        <div class="row g-0 vh-100">
            <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center position-relative px-4">

                <a href="javascript:void(0)" 
                    onclick="closeAndRedirect()" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div style="max-width: 450px; width: 100%;">
                    <h1 class="display-6 fw-bolder mb-2">Accedi</h1>
                    <p class="text-secondary mb-5">Usa il tuo username</p>

                    <?php if ($errore): ?>
                        <div class="alert alert-danger border-0 small py-2 mb-4"><?= htmlspecialchars($errore) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control bg-light border-light py-3" 
                                   placeholder="Username" required>
                        </div>
                        
                        <div class="mb-5 position-relative password-wrapper">
                            <input type="password" name="password" id="password" class="form-control bg-light border-light py-3" 
                                placeholder="Password" required>
                            <i class="bi bi-eye toggle-icon" data-target="password"></i>
                        </div>

                        <button type="submit" name="login" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">Accedi</button>
                    </form>

                    <p class="text-center small text-secondary">Non hai un account? <a href="signup.php" class="text-dark fw-bold text-decoration-none">Registrati</a></p>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block bg-secondary" 
                 style="background-image: url('/assets/img/breakingbad.jpeg'); background-size: cover; background-position: center;">
            </div>
        </div>
    </div>
    
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/user/watched.php
```php
<?php
// Pagina dei film visti dall'utente, con i dati recuperati da MongoDB.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}


// Estrazione tmdb_id
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id FROM watched WHERE id_utente = :id_u ORDER BY data_aggiunto DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    // Prende l'intera colonna e la mette dentro un array
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
    $ids = [];
}


// Connessione a MongoDB e ricerca film
$films = [];
$count = 0;

if (!empty($ids)) {

    // Conteggio film nel DB corretto in 'watched'
    try {
        $sql = "SELECT COUNT(*) FROM watched WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $count = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB e ricerca film
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase("cinevobis");
        $collection = $db->selectCollection("films");

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]],
            [
                'sort' => ['vote_average' => -1],
                'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array'],
            ]
        );

        $films = movie_sorting($cursor, $ids);

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
        $films = [];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watched - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Watched</h1>

        <?php 
            if ($count > 0) {
                echo "<div class='mb-4'>";
                echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai visto " . htmlspecialchars($count) . " Film</small>";
                echo "</div>";
            }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora aggiunto film ai tuoi visti
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                
                <?php 
                /** @var array $film */
                foreach ($films as $film):
                    $id = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) 
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] 
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                ?>

                <div class="col">
                    <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                            <div class="position-relative">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
                            </div>
                            <div class="card-body p-2 d-flex flex-column bg-white">
                                <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                <div class="mt-auto">
                                    <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/user/watchlist.php
```php
<?php
// Pagina della watchlist personale, con i film da guardare dell'utente.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}


// Estrazione tmdb_id
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id FROM watchlist WHERE id_utente = :id_u ORDER BY data_aggiunto DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    // Prende l'intera colonna e la mette dentro un array
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
    $ids = [];
}



$films = [];
$count = 0;

if (!empty($ids)) {

    // Conteggio film nel DB
    try {
        $sql = "SELECT COUNT(*) FROM watchlist WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $count = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB e ricerca film
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase("cinevobis");
        $collection = $db->selectCollection("films");

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]],
            [
                'sort' => ['vote_average' => -1],
                'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array'],
            ]
        );

        $films = movie_sorting($cursor, $ids);

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
        $films = [];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Watchlist</h1>

        <?php 
        if ($count > 0) {
            echo "<div class='mb-4'>";
            echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai " . htmlspecialchars($count) . " Film che vorresti vedere</small>";
            echo "</div>";
        }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora aggiunto film alla tua watchlist
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                
                <?php 
                /** @var array $film */
                foreach ($films as $film):
                    $id = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) 
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] 
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                ?>

                <div class="col">
                    <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                            <div class="position-relative">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
                            </div>
                            <div class="card-body p-2 d-flex flex-column bg-white">
                                <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                <div class="mt-auto">
                                    <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/admin/edit_user.php
```php
<?php
// Pagina admin per modificare o eliminare un utente esistente.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}


$errore = '';
$messaggio = '';

$username_utente = isset($_GET['username']) ? $_GET['username'] : null;

// Carichiamo i dati attuali dell'utente
if (!empty($username_utente)) {
    $user = new userObj($conn, $username_utente);
    $utente = $user->findByUsername();

    if (empty($utente)) 
        $errore = "Nessun utente trovato";
} else {
    $errore = "Nessun utente trovato";
}


// Modifica dati utente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        $nome = trim($_POST['nome'] ?? '');
        $cognome = trim($_POST['cognome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $attivo = isset($_POST['attivo']) ? 1 : 0;

        if (!$nome || !$cognome || !$email) {
            $errore = "Nome, cognome ed email sono obbligatori";
        } else {
            try {
                $userUpdate = new userObj(
                    $conn,
                    $username_utente,
                    null,
                    $nome,
                    $cognome,
                    $email,
                    $attivo,
                    $utente['id_profilo']
                );

                $userUpdate->update($username_utente);
                $messaggio = "Utente aggiornato con successo";

                // Ricarichiamo i dati aggiornati
                $utente = $userUpdate->findByUsername();
            } catch (PDOException $e) {
                $errore = "Errore durante l'aggiornamento";
                error_log("Errore update utente: " . $e->getMessage());
            }
        }
    }

    // Elimazione utente
    if (isset($_POST['delete_user'])) {
        try {
            $user->delete();
            header("Location: users.php?msg=eliminato");
            exit();
        } catch (PDOException $e) {
            $errore = "Errore durante l'eliminazione";
            error_log("Errore delete utente: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica utente - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <div style="max-width: 450px; width: 100%;">

            <a href="users.php"
                class="btn-close position-absolute top-0 start-0 m-4"
                aria-label="Chiudi">
            </a>

            <h1 class="display-6 fw-bolder mb-2 text-center">Modifica utente</h1>
            <p class="text-secondary mb-5 text-center">Aggiorna i dati dell'account</p>

            <?php if ($errore): ?>
                <div class="alert alert-danger border-0 small py-2 mb-4">
                    <?= htmlspecialchars($errore) ?>
                </div>
            <?php endif; ?>

            <?php if ($messaggio): ?>
                <div class="alert alert-success border-0 small py-2 mb-4">
                    <?= htmlspecialchars($messaggio) ?>
                </div>
            <?php endif; ?>

             <?php if ($errore === ''): ?>
                <form method="POST">
                    <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <input type="text"
                                name="nome"
                                class="form-control bg-light border-light py-3"
                                placeholder="Nome"
                                value="<?= htmlspecialchars($utente['nome'] ?? '') ?>"
                                required>
                        </div>
                        <div class="col-md-6">
                            <input type="text"
                                name="cognome"
                                class="form-control bg-light border-light py-3"
                                placeholder="Cognome"
                                value="<?= htmlspecialchars($utente['cognome'] ?? '') ?>"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="email"
                            name="email"
                            class="form-control bg-light border-light py-3"
                            placeholder="Email"
                            value="<?= htmlspecialchars($utente['email'] ?? '') ?>"
                            required>
                    </div>

                    <div class="mb-4">
                        <input type="text"
                            class="form-control bg-light border-light py-3 text-muted"
                            value="<?= htmlspecialchars($utente['username'] ?? '') ?>"
                            disabled 
                            style="cursor: not-allowed;">
                    </div>

                   <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                type="checkbox" 
                                name="attivo" 
                                value="1"
                                <?= (isset($utente['attivo']) && $utente['attivo'] == 1) ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="attivo">
                                Attivo
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit"
                                name="save"
                                class="btn btn-dark btn-lg flex-fill py-3 fw-bold">
                            Salva modifiche
                        </button>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit"
                                name="delete_user"
                                class="btn btn-outline-danger btn-lg flex-fill py-3 fw-bold"
                                onclick="return confirm('Sei sicuro?');">
                            Elimina utente
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

</body>
</html>
```

## File: pages/user/favorites.php
```php
<?php
// Pagina preferiti: mostra i film che l'utente ha salvato come preferiti.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}


// Estrazione tmdb_id
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id FROM preferiti WHERE id_utente = :id_u ORDER BY data_aggiunto DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    // Prende l'intera colonna e la mette dentro un array
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    
} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
    $ids = [];
}


// Connessione a MongoDB e ricerca film
$films = [];
$count = 0;

if (!empty($ids)) {

    // Conteggio preferiti
    try {
        $sql = "SELECT COUNT(*) FROM preferiti WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $count = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB e ricerca film
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase("cinevobis");
        $collection = $db->selectCollection("films");

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]],
            [
                'sort' => ['vote_average' => -1],
                'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array'],
            ]
        );

        $films = movie_sorting($cursor, $ids);

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
        $films = [];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preferiti - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Preferiti</h1>

        <?php 
            if ($count > 0) {
                echo "<div class='mb-4'>";
                echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai " . htmlspecialchars($count) . " Film come preferiti</small>";
                echo "</div>";
            }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora aggiunto film ai tuoi preferiti
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                
                <?php 
                /** @var array $film */
                foreach ($films as $film):
                    $id = $film['id'] ?? '';
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) 
                        ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] 
                        : "https://via.placeholder.com/500x750?text=No+Poster";
                    $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                ?>

                <div class="col">
                    <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                            <div class="position-relative">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
                            </div>
                            <div class="card-body p-2 d-flex flex-column bg-white">
                                <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                <div class="mt-auto">
                                    <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/admin/users.php
```php
<?php
// Pagina admin per la lista degli utenti registrati.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username   = $_SESSION['username'] ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}

$user = new userObj($conn, $username);
$utenti = $user->readAll();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione utenti - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-3 fw-bold mb-0">Utenti</h1>
            </div>
        
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Utente</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Email</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Profilo</th>
                            <th class="text-center py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Stato</th>
                            <th class="text-end pe-4 py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($utenti as $utente): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 42px; height: 42px; background-color: var(--bg-muted); color: var(--accent);">
                                            <i class="bi bi-person-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($utente['username']) ?></div>
                                            <div class="small text-muted">
                                                <?= htmlspecialchars(trim(($utente['nome'] ?? '') . ' ' . ($utente['cognome'] ?? ''))) ?: '<span class="fst-italic">Nessun nome</span>' ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-3 text-secondary">
                                    <?= htmlspecialchars($utente['email'] ?? 'N/D') ?>
                                </td>
                                
                                <td class="py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2 fw-normal">
                                        <i class="bi bi-shield-check me-1"></i> <?= htmlspecialchars($utente['nome_profilo'] ?? 'N/D') ?>
                                    </span>
                                </td>
                                
                                <td class="text-center py-3">
                                    <?php if ($utente['attivo']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-normal">
                                            <i class="bi bi-check2-circle me-1"></i> Attivo
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2 fw-normal">
                                            <i class="bi bi-x-circle me-1"></i> Inattivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-end pe-4 py-3">
                                    <a href="edit_user.php?username=<?= urlencode($utente['username']) ?>" 
                                       class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-inline-flex align-items-center">
                                        <i class="bi bi-pencil-square me-2"></i> Modifica
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($utenti)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Nessun utente trovato nel database.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> 
    
    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/public/search.php
```php
<?php
// Pagina di ricerca film: interroga l'API TMDB e mostra i risultati all'utente.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/movie_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Kiwilan\Tmdb\Tmdb;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb = Tmdb::client($_ENV['API_KEY']);

$errore = "";
$moviesList = [];
$results = []; // Inizializziamo sempre l'array per evitare "Undefined variable"
$searched = isset($_GET['search']) ? trim($_GET['search']) : '';
$api_failed = false;

if ($searched !== '') {
    try {
        $raw = $tmdb->raw()->url('/search/movie', [
            'query' => $searched,
            'language' => 'it-IT'
        ]);

        if ($raw !== null) {
            $body = $raw->getBody();

            if (isset($body['results']) && is_array($body['results'])) {
                $results = $body['results'];
                // Ordiniamo i risultati direttamente qui
                $results = order_of_popularity(count($results), $results);
            } else {
                $api_failed = true; 
            }
        } else {
            $api_failed = true;
        }
    } catch (Exception $e) {
        // Logghiamo l'errore e segnaliamo il fallimento dell'API
        error_log("Errore connessione con TMDB: " . $e->getMessage());
        $api_failed = true;
    }
    
    // Gestione unificata degli stati finali (Errori API, Nessun risultato, Successo)
    if ($api_failed) {
        $errore = "Si è verificato un errore temporaneo nella ricerca dei film. Riprova più tardi.";
        
    } elseif (empty($results)) {
        
        $errore = "Nessun risultato trovato per: " . htmlspecialchars($searched);
    } else {
        $moviesList = movieObj::search($results);
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerca Film - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1 d-flex flex-column align-items-center">

        <div class="w-100" style="max-width: 650px;">

            <?php if ($errore): ?>
                <div class="alert alert-info shadow-sm rounded-4 border-0">
                    <i class="bi bi-info-circle me-2"></i><?= htmlspecialchars($errore) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($moviesList)): ?>
                <h5 class="text-muted mb-3 fw-normal">Risultati della ricerca</h5>
                <div class="d-flex flex-column gap-3">

                    <?php foreach ($moviesList as $movie): ?>
                        <a href="film.php?tmdb_id=<?= urlencode($movie['id']) ?>" class="text-decoration-none">
                            <div class="card border-0 shadow-sm rounded-3 card-hover bg-white search-result-card">
                                <div class="card-body px-4 py-3 d-flex align-items-center gap-3">

                                    <?php if ($movie['poster']): ?>
                                        <img src="<?= htmlspecialchars($movie['poster']) ?>"
                                            alt="Poster <?= htmlspecialchars($movie['titolo']) ?>"
                                            class="rounded-2 flex-shrink-0"
                                            style="width: 48px; height: 72px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-2 flex-shrink-0 bg-secondary d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 72px;">
                                            <i class="bi bi-film text-white fs-5"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex-grow-1 overflow-hidden">
                                        <span class="fs-6 text-dark fw-medium d-block text-truncate">
                                            <?= htmlspecialchars($movie['titolo']) ?>
                                        </span>
                                        <?php if ($movie['anno']): ?>
                                            <small class="text-muted"><?= htmlspecialchars($movie['anno']) ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <i class="bi bi-chevron-right text-muted flex-shrink-0"></i>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>

</html>
```

## File: pages/user/reviews.php
```php
<?php
// Elenca le recensioni personali dell'utente con i dettagli dei film.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/functions.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use MongoDB\Client;

// Controllo autenticazione
$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}


// Estrazione tmdb_id + dati recensione
$recensioni_map = [];
$ids = [];
$id_utente = $_SESSION['id_utente'] ?? '';

try {
    $sql = "SELECT tmdb_id, commento, voto 
            FROM recensioni 
            WHERE id_utente = :id_u 
            ORDER BY voto DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_u' => $id_utente]);

    $rows = $stmt->fetchAll();

    foreach ($rows as $row) {
        $ids[] = (int) $row['tmdb_id'];

        $recensioni_map[(int) $row['tmdb_id']] = [
            'voto' => $row['voto'],
            'commento' => $row['commento'],
        ];
    }

} catch (PDOException $e) {
    error_log("Errore nel DB: " . $e->getMessage());
}

// Connessione a MongoDB e ricerca film
$films = [];
$count = 0;

if (!empty($ids)) {

    // Conteggio recensioni
    try {
        $sql = "SELECT COUNT(*) 
                FROM recensioni 
                WHERE id_utente = :id_u";

        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_u' => $id_utente]);

        $count = $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Errore: " . $e->getMessage());
    }


    // Connessione a MongoDB e ricerca film
    try {
        $mongoClient = new Client("mongodb://localhost:27017");
        $db = $mongoClient->selectDatabase("cinevobis");
        $collection = $db->selectCollection("films");

        $cursor = $collection->find(
            ['id' => ['$in' => $ids]],
            [
                'sort' => ['vote_average' => -1],
                'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array'],
            ]
        );

        $films = movie_sorting($cursor, $ids);

    } catch (Exception $e) {
        error_log("Errore in MongoDB: " . $e->getMessage());
        $films = [];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recensioni - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        :root { --accent-color: #ffc107; }

        .text-justify { text-align: justify; }

        .cast-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        .review-poster {
            width: 120px;
            min-width: 120px;
            aspect-ratio: 2/3;
            object-fit: cover;
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .star-rating {
            color: #ccc;
            font-size: 1.1rem;
            letter-spacing: 2px;
        }

        .star-rating .filled {
            color: var(--accent-color);
        }

        .vote-badge {
            background-color: var(--accent-color);
            color: #1a1a1a;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 0.4rem;
            padding: 2px 10px;
            display: inline-block;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <h1 class="fw-bold mb-4">Recensioni</h1>

        <?php 
        if ($count > 0) {
            echo "<div class='mb-4'>";
            echo "<small class='text-uppercase fw-bold text-muted d-block mb-2' style='letter-spacing:1px'>Hai recensito " . htmlspecialchars($count) . " Film</small>";
            echo "</div>";
        }
        ?>

        <?php if (empty($films)): ?>
            <div class="alert alert-info shadow-sm rounded-4 border-0">
                <i class="bi bi-info-circle me-2"></i>Non hai ancora recensito nessun film
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <?php
                /** @var array $film */
                foreach ($films as $film):
                    $id = (int) ($film['id'] ?? 0);
                    $titolo = $film['title'] ?? 'Titolo non disponibile';
                    $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                    $rec = $recensioni_map[$id] ?? [];
                    $voto = isset($rec['voto']) ? (float) $rec['voto'] : null;
                    $commento = $rec['commento'] ?? '';
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover position-relative">
                        <div class="d-flex">

                            <img src="<?= htmlspecialchars($poster) ?>"
                                alt="<?= htmlspecialchars($titolo) ?>"
                                class="review-poster">

                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark stretched-link">
                                            <?= htmlspecialchars($titolo) ?>
                                        </a>
                                    </h5>

                                    <?php if (!empty($commento)): ?>
                                        <p class="text-muted small mb-2 text-justify">
                                            "<?= nl2br(htmlspecialchars($commento)) ?>"
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($voto !== null): ?>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <i class="bi bi-star-fill" style="color: var(--accent-color); font-size: 1rem;"></i>
                                        <span class="fw-bold fs-5"><?= $voto ?></span>
                                        <span class="text-muted small">/10</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: actions/change_password.php
```php
<?php
// Gestisce il cambio password per l'utente autenticato.
// Verifica password attuale e aggiorna il record tramite userObj.
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/connection.php');
require_once(__DIR__ . '/../includes/user_obj.php');

$username = $_SESSION['username'] ?? '';

if (!$username) {
    header("Location: /index.php");
    exit();
}

$errore = '';
$messaggio = '';

if (isset($_POST['cambia_password'])) {
    $password_attuale = $_POST['password_attuale'] ?? '';
    $nuova_password = $_POST['nuova_password']   ?? '';
    $conferma = $_POST['conferma_password'] ?? '';

    if (!$password_attuale || !$nuova_password || !$conferma) {
        $errore = "Compila tutti i campi";

    } elseif ($nuova_password !== $conferma) {
        $errore = "Le nuove password non coincidono";

    } elseif ($password_attuale === $nuova_password) {
        $errore = "La nuova password deve essere diversa dalla attuale";
        
    } else {
        try {
            $user = new userObj($conn, $username);
            $risultato = $user->changePassword($password_attuale, $nuova_password);

            if ($risultato['ok']) {
                $messaggio = "Password aggiornata con successo";
            } else {
                $errore = $risultato['errore'];
            }
        } catch (PDOException $e) {
            $errore = "Errore"; 
            error_log("Errore: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambia Password - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div class="container-fluid">

        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 px-4">

                <a href="javascript:void(0)" 
                    onclick="closeAndRedirect()" 
                    class="btn-close position-absolute top-0 start-0 m-4" 
                    aria-label="Close">
                </a>

                <div class="text-center mb-5">
                    <h1 class="display-6 fw-bolder mb-2">Cambia password</h1>
                    <p class="text-secondary">Modifica la tua password</p>
                </div>

                <?php if ($errore): ?>
                    <div class="alert alert-danger border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($errore) ?></div>
                <?php endif; ?>
                
                <?php if ($messaggio): ?>
                    <div class="alert alert-success border-0 small py-2 mb-4 text-center"><?= htmlspecialchars($messaggio) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4 position-relative password-wrapper">
                        <label class="form-label small text-secondary">Password attuale</label>
                        <input type="password" name="password_attuale" id="password_attuale" class="form-control bg-light border-light py-3" placeholder="Password attuale" required>
                        <i class="bi bi-eye toggle-icon" data-target="password_attuale"></i>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-4 position-relative password-wrapper">
                        <label class="form-label small text-secondary">Nuova password</label>
                        <input type="password" name="nuova_password" id="nuova_password" class="form-control bg-light border-light py-3" placeholder="Nuova password" required>
                        <i class="bi bi-eye toggle-icon" data-target="nuova_password"></i>
                    </div>

                    <div class="mb-5 position-relative password-wrapper">
                        <label class="form-label small text-secondary">Conferma nuova password</label>
                        <input type="password" name="conferma_password" id="conferma_password" class="form-control bg-light border-light py-3" placeholder="Ripeti nuova password" required>
                        <i class="bi bi-eye toggle-icon" data-target="conferma_password"></i>
                    </div>

                    <button type="submit" name="cambia_password" class="btn btn-dark btn-lg w-100 py-3 fw-bold mb-4">
                        Salva modifiche
                    </button>

                    <p class="text-center small text-secondary">Non ricordi la password? 
                        <a href="contact.php" class="text-dark fw-bold text-decoration-none">
                            Contattaci
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: pages/admin/sessions.php
```php
<?php
// Pagina admin che mostra le ultime sessioni di accesso al sito.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');

// Controllo autenticazione
$username   = $_SESSION['username']   ?? '';
$id_profilo = $_SESSION['id_profilo'] ?? 0;

if (!$username || $id_profilo != 1) {
    header("Location: /index.php");
    exit();
}

$user = new userObj($conn, $username);

// Recupero il numero di righe dal parametro GET, validandolo come intero (minimo 1)
$righe = (int)($_GET['righe'] ?? 15);
$sessioni = $user->readAccess($righe);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area sessioni - Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <div class="container mt-4 mb-5 pb-5 flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fs-3 fw-bold mb-0">Log accessi</h1>
            
            <form method="GET" class="d-flex align-items-center gap-2">
                <label for="righe" class="text-muted small fw-medium mb-0 d-none d-sm-block">Mostra righe:</label>
                <div class="input-group input-group-sm" style="width: 130px;">
                    <input type="number" name="righe" id="righe" class="form-control text-center" min="1" max="1000" value="<?= htmlspecialchars($righe) ?>">
                    <button type="submit" class="btn btn-outline-secondary" title="Aggiorna">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Utente</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Data Login</th>
                            <th class="py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Data Logout</th>
                            <th class="text-center pe-4 py-3 text-uppercase text-muted small fw-bold border-bottom-0" scope="col">Stato</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($sessioni as $sessione): ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 42px; height: 42px; background-color: var(--bg-muted); color: var(--accent);">
                                            <i class="bi bi-person-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($sessione['username']) ?></div>
                                            <div class="small text-muted">
                                                <?= htmlspecialchars(trim(($sessione['nome'] ?? '') . ' ' . ($sessione['cognome'] ?? ''))) ?: '<span class="fst-italic">Nessun nome</span>' ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-3 text-secondary">
                                    <i class="bi bi-box-arrow-in-right text-success me-1 opacity-75"></i> 
                                    <?= htmlspecialchars($sessione['data_login'] ?? 'N/D') ?>
                                </td>
                                
                                <td class="py-3 text-secondary">
                                    <?php if (!empty($sessione['data_logout'])): ?>
                                        <i class="bi bi-box-arrow-left text-danger me-1 opacity-75"></i> 
                                        <?= htmlspecialchars($sessione['data_logout']) ?>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic ms-4">-</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-center pe-4 py-3">
                                    <?php if (empty($sessione['data_logout'])): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-normal d-inline-flex align-items-center">
                                            <i class="bi bi-circle-fill me-2" style="font-size: 0.45rem;"></i>
                                            Attiva
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2 fw-normal">
                                            Terminata
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($sessioni)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                                    Nessuna sessione registrata.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>
    
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```

## File: assets/css/style.css
```css
:root {
    --accent: #6366f1;
    --accent-hover: #4f46e5;
    --accent-dark: #3730a3;
    --bg: #f8fafc;
    --bg-surface: #ffffff;
    --bg-muted: #f1f5f9;
    --border: #e2e8f0;
    --text: #0f172a;
    --text-muted: #64748b;

    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);

    --radius-md: 12px;
    --radius-lg: 16px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.alert-info {
    background-color: var(--accent);
    color: var(--bg);
}

body {
    background-color: var(--bg);
    color: var(--text);
    font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Bottoni */
.btn-dark {
    background-color: var(--text) !important;
    border-color: var(--text) !important;
    color: #fff !important;
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
}
.btn-dark:hover {
    background-color: #1e293b !important;
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-outline-secondary {
    border-radius: 8px;
    font-weight: 500;
    border-color: var(--border);
    color: var(--text-muted);
    transition: var(--transition);
}
.btn-outline-secondary:hover {
    background-color: var(--bg-muted);
    color: var(--text);
    border-color: var(--text-muted);
}

/* Navbar */
.navbar {
    background-color: rgba(255, 255, 255, 0.85) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border) !important;
    position: sticky;
    top: 0;
    z-index: 1050;
}
.navbar-brand { color: var(--text) !important; letter-spacing: -0.5px; }

/* Ricerca — copre sia .search-wrap che input[name="search"] */
.search-wrap,
input[name="search"] {
    background-color: var(--bg-muted) !important;
    border: 1px solid transparent !important;
    border-radius: 20px !important;
    padding: 8px 16px;
    transition: var(--transition);
}
.search-wrap { cursor: text; display: block; }

.search-wrap:hover,
.search-wrap:focus-within,
input[name="search"]:focus {
    background-color: var(--bg-surface) !important;
    border-color: var(--accent) !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
}

.search-wrap input {
    border: none;
    outline: none;
    font-size: 14px;
    background: transparent;
    width: 100%;
    color: var(--text);
}
.search-wrap input::placeholder { color: var(--text-muted); }

/* Dropdown */
.dropdown-menu {
    border: 1px solid var(--border) !important;
    background-color: var(--bg-surface);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    padding: 8px 0;
}
.dropdown-item { font-weight: 500; transition: background-color 0.2s, color 0.2s; }
.dropdown-item:hover,
.dropdown-item:active,
.dropdown-item.active {
    background-color: var(--bg-muted) !important;
    color: var(--accent) !important;
}

/* Card */
.card,
.search-result-card {
    background-color: var(--bg-surface) !important;
    border: 1px solid var(--border) !important;
    border-radius: var(--radius-lg) !important;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

/* .card-hover è un alias di .transition-hover — unificati */
.transition-hover,
.card-hover {
    will-change: transform, box-shadow;
    transition: var(--transition);
}
.transition-hover:hover,
.card-hover:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md) !important;
    border-color: rgba(99, 102, 241, 0.3) !important;
}

/* Form */
.form-control {
    background-color: var(--bg-surface) !important;
    border: 1px solid var(--border) !important;
    border-radius: 8px;
    color: var(--text) !important;
    padding: 10px 14px;
    transition: var(--transition);
}
.form-control::placeholder { color: var(--text-muted) !important; }
.form-control:focus {
    border-color: var(--accent) !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
}

/* Footer */
footer {
    border-top: 1px solid var(--border) !important;
    background-color: var(--bg-surface) !important;
}

/* Alerts */
.alert { border-radius: var(--radius-md); border: none !important; font-weight: 500; }
.alert-danger  { background-color: #fef2f2 !important; color: #b91c1c !important; }
.alert-success { background-color: #f0fdf4 !important; color: #15803d !important; }
.alert-warning { background-color: #fffbeb !important; color: #b45309 !important; }

/* Password toggle */
.password-wrapper { position: relative; width: 100%; }
.toggle-icon {
    position: absolute;
    right: 15px;
    bottom: 12px;
    cursor: pointer;
    color: var(--text-muted);
    font-size: 1.1rem;
    z-index: 10;
    transition: color 0.2s;
}
.toggle-icon:hover { color: var(--text); }

/* Placeholder poster */
.card-img-top {
    background-color: var(--bg-muted);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='48' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z'/%3E%3Cpath d='M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
}

/* Scrollbar */
::-webkit-scrollbar { width: 10px; height: 10px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
    border: 2px solid var(--bg);
}
::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
```

## File: includes/header.php
```php
<?php
$isLogged = isset($_SESSION['username']);
$currentPage = basename($_SERVER['SCRIPT_NAME']);

$publicPages = ['login.php', 'signup.php'];
$adminPages = ['add_film.php', 'dashboard.php', 'sessions.php', 'users.php', 'edit_user.php', 'notifications.php', 'films.php', 'film_db.php'];

$isPublicPage = in_array($currentPage, $publicPages);
$isAdminPage = in_array($currentPage, $adminPages);
?>

<nav class="navbar border-bottom px-3 px-lg-4">
    <div class="container-fluid gap-2">

        <!-- Brand -->
        <a href="<?= $isAdminPage ? '/pages/admin/dashboard.php' : '/' ?>" class="navbar-brand fw-bold me-3">
            Cinevobis
        </a>

        <!-- Search: sempre visibile, prende lo spazio disponibile -->
        <?php if (!$isAdminPage): ?>
            <form action="/pages/public/search.php" method="GET"
                  class="d-flex align-items-center flex-grow-1 me-2">
                <input type="text" name="search" placeholder="Cerca un film..."
                       class="form-control form-control-sm rounded-pill shadow-none"
                       style="max-width: 360px;"
                       required value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </form>
        <?php else: ?>
            <div class="flex-grow-1"></div>
        <?php endif; ?>

        <!-- Icone liste (loggato, pagine pubbliche) -->
        <?php if ($isLogged && !$isAdminPage): ?>
            <div class="d-flex align-items-center gap-1 me-1">
                <a href="/pages/user/favorites.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Preferiti">
                    <i class="bi bi-heart-fill"></i>
                </a>
                <a href="/pages/user/watchlist.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Watchlist">
                    <i class="bi bi-bookmark"></i>
                </a>
                <a href="/pages/user/watched.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Visti">
                    <i class="bi bi-eye-fill"></i>
                </a>
                <a href="/pages/user/reviews.php" class="btn btn-sm btn-outline-secondary border-0 px-2" title="Recensioni">
                    <i class="bi bi-pencil-fill"></i>
                </a>
            </div>
        <?php endif; ?>

        <!-- Non loggato: bottoni Accedi / Registrati -->
        <?php if (!$isLogged): ?>
            <div class="d-flex gap-2">
                <a href="/pages/public/login.php" class="btn btn-outline-secondary btn-sm px-4">Accedi</a>
                <a href="/pages/public/signup.php" class="btn btn-dark btn-sm px-4">Registrati</a>
            </div>
        <?php else: ?>
            <!-- Hamburger → dropdown (unico per tutte le dimensioni) -->
            <div class="dropdown">
                <button class="btn border-0 p-2 shadow-none" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">

                    <li><a class="dropdown-item py-2 small" href="/pages/user/profile.php">
                        <i class="bi bi-person me-2"></i>Profilo</a></li>

                    <?php if ($isAdminPage): ?>
                        <li><a class="dropdown-item py-2 small" href="/pages/admin/dashboard.php">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item py-2 small" href="/">
                            <i class="bi bi-house me-2"></i>Home</a></li>
                        <li><a class="dropdown-item py-2 small" href="/actions/contact.php">
                            <i class="bi bi-envelope me-2"></i>Contattaci</a></li>
                        <li><a class="dropdown-item py-2 small" href="/pages/public/notice_board.php">
                            <i class="bi bi-layout-text-sidebar-reverse me-2"></i>Bacheca</a></li>
                    <?php endif; ?>

                    <?php if (($_SESSION['id_profilo'] ?? null) == '1' && !$isAdminPage): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 small fw-bold" href="/pages/admin/dashboard.php">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
                    <?php endif; ?>

                    <?php if ($isAdminPage): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 small fw-bold" href="/">
                            <i class="bi bi-box-arrow-left me-2"></i>Esci dall'admin</a></li>
                    <?php endif; ?>

                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 small fw-bold text-danger" href="/actions/logout.php">
                        <i class="bi bi-power me-2"></i>Logout</a></li>
                </ul>
            </div>
        <?php endif; ?>

    </div>
</nav>
```

## File: index.php
```php
<?php
// Home page Cinevobis: mostra film in evidenza e migliori film.
// Recupera i dati da MongoDB dalla collezione `films` del database `cinevobis`.
require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/config/connection.php');
require_once(__DIR__ . '/includes/header_logic.php');
require_once(__DIR__ . '/vendor/autoload.php');

use MongoDB\Client;

$nome = $_SESSION['nome'] ?? '';

// Prepara gli array di dati che verranno popolati dal database.
$collection = [];
$cursor = [];

try {
    // Connessione a MongoDB locale e selezione della collezione film.
    $mongoClient = new Client("mongodb://localhost:27017");
    $db = $mongoClient->selectDatabase('cinevobis');
    $collection = $db->selectCollection('films');

} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}

// Film in evidenza: ultimi film aggiunti.
$recommendedFilms = [];

try {
    $cursor = $collection->find([], [
        'limit' => 6,
        'sort' => ['release_date' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);

    $recommendedFilms = iterator_to_array($cursor);

    // Prende i migliori film ordinati per voto medio.
    $cursor = $collection->find([], [
        'limit' => 6,
        'sort' => ['vote_average' => -1],
        'typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']
    ]);
    
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
}

// Mappa i risultati dei migliori film e sceglie il film della settimana.
$topFilms = [];
$film = [];

try {
    $topFilms = iterator_to_array($cursor);
    
    // srand((int)date('oW'));
    srand(10);
    $film = $topFilms[array_rand($topFilms)] ?? null;

} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinevobis</title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include("includes/header.php"); ?>

    <main class="container mt-5 mb-5 flex-grow-1">
        <div class="container">
            <?php if($nome != ''): ?>
                <h1 class="fw-bold mb-4">Benvenuto, <?= htmlspecialchars($nome) ?></h1>
            <?php else: ?>
                <h1 class="fw-bold mb-4">Benvenuto</h1>
            <?php endif; ?>

            <?php 
            /** @var array $film */
            if (!empty($topFilms)):
                $id = $film['id'] ?? '';
                
                $titolo = $film['title'] ?? '';
                $anno = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';  // Restituisce parte di una stringa
                
                $rating = isset($film['vote_average']) ? number_format((float)$film['vote_average'], 1) : null;
                $overview = $film['overview'] ?? '';

                $background = '';

                // Controlliamo se c'è un background
                if (!empty($film['backdrop_path'])) {
                    
                    $background = "https://image.tmdb.org/t/p/w1280" . $film['backdrop_path'];

                // Se non c'è controlliamo il poster
                } elseif (!empty($film['poster_path'])) {
                    
                    $background = "https://image.tmdb.org/t/p/w500" . $film['poster_path'];

                // Se non c'è né il background né il poster
                } else {
                    $background = ''; 
                }
            ?>

            <div class="position-relative rounded-4 overflow-hidden mb-5"
                 style="min-height: 420px; background: url('<?= htmlspecialchars($background) ?>') center/cover no-repeat #1a1a1a;">
                <div class="position-absolute top-0 start-0 w-100 h-100"
                     style="background: linear-gradient(to right, rgba(0,0,0,.85) 0%, rgba(0,0,0,.4) 60%, transparent 100%);"></div>
                <div class="position-relative d-flex align-items-end h-100 p-4 p-md-5" style="min-height: 420px;">
                    <div style="max-width: 500px;">
                        <div class="mb-2 d-flex align-items-center gap-2">
                            <span class="badge bg-white bg-opacity-25 text-white border border-white border-opacity-25 fw-semibold">Film della settimana</span>
                            <?php if ($anno): ?>
                                <span class="text-white-50 small"><?= htmlspecialchars($anno) ?></span>
                            <?php endif; ?>
                        </div>
                        <h2 class="fw-bold text-white mb-2" style="font-size: clamp(1.6rem, 3.5vw, 2.4rem);">
                            <?= htmlspecialchars($titolo) ?>
                        </h2>
                        <?php if ($rating): ?>
                            <p class="text-white fw-semibold mb-2">
                                <i class="bi bi-star-fill text-warning me-1"></i><?= $rating ?> <span class="text-white-50">/ 10</span>
                            </p>
                        <?php endif; ?>
                        <?php if ($overview): ?>
                            <p class="text-white-50 small mb-3 d-none d-md-block hero-overview">
                                <?= htmlspecialchars($overview) ?>
                            </p>
                        <?php endif; ?>
                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>"
                           class="btn btn-light fw-bold rounded-pill px-4">Scopri di più
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>


           <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h3 class="fw-bold m-0">Esplora</h3>
            </div>

           <div class="row g-4">
                <div class="col-12 col-md-6">
                    <a href="/pages/public/genres.php" class="text-decoration-none d-block h-100">
                        <div class="card transition-hover h-100">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-grid-fill fs-2" style="color: var(--accent);"></i>
                                    <div>
                                        <div class="fw-bold" style="font-size: 1rem;">Generi</div>
                                        <div class="text-muted" style="font-size: 0.85rem;">Esplora il catalogo per categoria</div>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6">
                    <a href="/pages/public/notice_board.php" class="text-decoration-none d-block h-100">
                        <div class="card transition-hover h-100">
                            <div class="card-body d-flex align-items-center justify-content-between p-4">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-journal-text fs-2 text-warning"></i>
                                    <div>
                                        <div class="fw-bold" style="font-size: 1rem;">Bacheca</div>
                                        <div class="text-muted" style="font-size: 0.85rem;">Le ultime recensioni della community</div>
                                    </div>
                                </div>
                                <i class="bi bi-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h3 class="fw-bold m-0">I Film in evidenza</h3>
                <a href="/pages/public/recommended_films.php" class="text-decoration-none fw-semibold" style="color: var(--accent);">Vedi tutti <i class="bi bi-arrow-right"></i></a>
            </div>

            <?php if (empty($recommendedFilms)): ?>
                <div class="alert alert-info shadow-sm rounded-4 border-0">
                    <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                    <?php 
                    /** @var array $film */
                    foreach ($recommendedFilms as $film):
                        $id     = $film['id'] ?? '';
                        $titolo = $film['title'] ?? 'Titolo non disponibile';
                        $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                        $anno   = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                    ?>
                    <div class="col">
                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" style="object-fit: cover; aspect-ratio: 2/3;">
                                <div class="card-body p-2 d-flex flex-column bg-white">
                                    <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                    <div class="mt-auto">
                                        <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
                <h3 class="fw-bold m-0">I migliori Film</h3>
                <a href="/pages/public/top_films.php" class="text-decoration-none fw-semibold" style="color: var(--accent);">Vedi tutti <i class="bi bi-arrow-right"></i></a>
            </div>

            <?php if (empty($topFilms)): ?>
                <div class="alert alert-info shadow-sm rounded-4 border-0">
                    <i class="bi bi-info-circle me-2"></i>Nessun film trovato nel database.
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3">
                    <?php 
                    /** @var array $film */
                    foreach ($topFilms as $film):
                        $id     = $film['id'] ?? '';
                        $titolo = $film['title'] ?? 'Titolo non disponibile';
                        $poster = !empty($film['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $film['poster_path'] : "https://via.placeholder.com/500x750?text=No+Poster";
                        $anno   = !empty($film['release_date']) ? substr($film['release_date'], 0, 4) : '';
                    ?>
                    <div class="col">
                        <a href="/pages/public/film.php?tmdb_id=<?= $id ?>" class="text-decoration-none text-dark d-block h-100">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-hover">
                                <img src="<?= $poster ?>" class="card-img-top w-100" alt="<?= htmlspecialchars($titolo) ?>" loading="lazy" style="object-fit: cover; aspect-ratio: 2/3;">
                                <div class="card-body p-2 d-flex flex-column bg-white">
                                    <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;" title="<?= htmlspecialchars($titolo) ?>"><?= htmlspecialchars($titolo) ?></h6>
                                    <div class="mt-auto">
                                        <small class="text-muted fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($anno) ?></small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <hr class="my-5 border-0" style="border-top: 0.5px solid var(--bs-border-color) !important;">

            <div class="mt-5 mb-5 py-lg-4">
                <div class="row g-4 g-lg-5 align-items-center">

                    <div class="col-12 col-lg-6">
                        <p class="text-uppercase text-muted fw-bold mb-3 d-flex align-items-center" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                            <span class="me-3 rounded-pill" style="width: 30px; height: 2px; background-color: currentColor;"></span>
                            Il progetto
                        </p>
                        
                        <h2 class="fw-bolder mb-3" style="font-size: clamp(1.75rem, 3.5vw, 2.25rem); line-height: 1.2;">
                            Perché nasce Cinevobis?
                        </h2>
                        
                        <p class="text-secondary mb-4" style="line-height: 1.8; font-size: 1.05rem;">
                            Cinevobis nasce per chi ama i film, concedendo la possibilità di condividere
                            la propria passione con gli altri. Il nome deriva da
                            <strong class="text-dark">cine</strong>, inteso come cinema, e
                            <strong class="text-dark">vobis</strong>, dal latino <em>per voi</em>.
                        </p>
                        
                        <blockquote class="mb-0 p-4 rounded-4 bg-light border-start border-4" style="border-color: var(--bs-gray-400) !important;">
                            <p class="fst-italic text-dark mb-2" style="font-size: 0.95rem; line-height: 1.6;">
                                "I film non ti dicono cosa pensare. Ti insegnano come sentire."
                            </p>
                            <cite class="text-muted fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.05em;">— Roger Ebert</cite>
                        </blockquote>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="row g-3 g-md-4">
                            <?php
                            $features = [
                                ['icon' => 'bi-heart-fill',    'titolo' => 'Preferiti',  'desc' => 'I film che ami nel tuo catalogo personale.', 'color' => '#dc3646'],
                                ['icon' => 'bi-pen-fill',      'titolo' => 'Recensioni', 'desc' => 'Scrivi, vota e condividi il tuo pensiero.', 'color' => 'var(--text)'],
                                ['icon' => 'bi-eye-fill',      'titolo' => 'Watched',    'desc' => 'Lo storico di tutto ciò che hai già visto.', 'color' => '#1b8855'],
                                ['icon' => 'bi-bookmark-fill', 'titolo' => 'Watchlist',  'desc' => 'I titoli che non vuoi assolutamente perderti.', 'color' => '#267bfd'],
                            ];
                            foreach ($features as $f): ?>
                            <div class="col-12 col-sm-6">
                                <div class="p-4 rounded-4 h-100 transition-hover" 
                                    style="background-color: var(--bg-muted); border: 1px solid var(--border);">
                                    
                                    <i class="bi <?= $f['icon'] ?> mb-3 d-block" style="font-size: 1.8rem; color: <?= $f['color'] ?>;"></i>
                                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;"><?= $f['titolo'] ?></h5>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.6;">
                                        <?= $f['desc'] ?>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <?php require_once('includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## File: pages/public/film.php
```php
<?php
// Pagina pubblica di dettaglio film: recupera dati TMDB, salva/aggiorna MongoDB
// e gestisce le azioni utente su preferiti, watchlist e watched.
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../config/connection.php');
require_once(__DIR__ . '/../../includes/user_obj.php');
require_once(__DIR__ . '/../../includes/movie_obj.php');
require_once(__DIR__ . '/../../includes/header_logic.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Dotenv\Dotenv;
use Kiwilan\Tmdb\Tmdb;
use MongoDB\Client;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$tmdb = Tmdb::client($_ENV['API_KEY']);


// Dichiarazione variabili
$movie_api = null;
$movie_db = null;
$errore = "";

$movie_id = $_GET['tmdb_id'] ?? null;
$collection = [];

// Connessione a MongoDB
try {
    $mongoClient = new Client("mongodb://localhost:27017");
    $db          = $mongoClient->selectDatabase('cinevobis');
    $collection  = $db->selectCollection('films');
} catch (Exception $e) {
    error_log("Errore MongoDB: " . $e->getMessage());
}


// 1. Recupero film da TMDB
if (!empty($movie_id)) {
    $results = $tmdb->raw()->url("/movie/{$movie_id}", [
        'language'           => 'it-IT',
        'append_to_response' => 'credits,videos'
    ]);

    $body = $results?->getBody();
    if ($body) {
        $movie_api = is_string($body) ? json_decode($body, true) : $body;
    }

    if (empty($movie_api)) {
        $errore = "Film non trovato su TMDB";
    }
} else {
    $errore = "Nessun film selezionato";
}


// 2. Controllo/inserimento o aggiornamento in MongoDB
if (!empty($movie_api)) {
    $now              = time();
    $aMonthInSeconds  = 30 * 24 * 60 * 60;

    $movie_db = $collection->findOne(
        ['id' => (int)$movie_id],
        ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
    );

    if ($movie_db === null) {
        $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();
        $collection->insertOne($movie_api);
        $movie_db = $movie_api;
    } else {
        $lastUpdateSeconds = isset($movie_db['last_updated'])
            ? $movie_db['last_updated']->toDateTime()->getTimestamp()
            : 0;

        if (($now - $lastUpdateSeconds) > $aMonthInSeconds) {
            $movie_api['last_updated'] = new \MongoDB\BSON\UTCDateTime();
            $collection->updateOne(['id' => $movie_id], ['$set' => $movie_api]);
            $movie_db = $movie_api;
        }
    }
}


// 3. Estrazione dati dal film
$titolo = $titolo_orig = $trama = $poster_path = $trailerKey = $paese = '';
$voto   = 0;
$durata = $anno = '';
$generi = $cast = $registi = [];

if ($movie_db) {
    $movieObj = new movieObj($movie_db);
    $data     = $movieObj->toArray();

    $titolo = $data['titolo'];
    $titolo_orig = $data['titolo_orig'];

    $trama = $data['trama'];
    $poster_path = $data['poster_path'];

    $voto = $data['voto'];
    $trailerKey = $data['trailer_key'];

    $durata = $data['durata'];
    $anno = $data['anno'];

    $generi = $data['generi'];
    $paese = $data['paese'];

    $cast = $data['cast'];
    $registi = $data['registi'];
}


// 4. Gestione liste utente tramite userObj
$tmdb_id   = $movie_db['id'] ?? null;
$id_utente = $_SESSION['id_utente'] ?? null;

$is_favorite = false;
$is_review = false;
$is_watchlist = false;
$is_watched = false;

if ($tmdb_id !== null && $id_utente !== null) {
    $userObj = new userObj($conn, $_SESSION['username']);

    try {
        // Gestione POST preferiti
        if (isset($_POST['favorite'])) $userObj->addFavorite((int)$tmdb_id, $id_utente);
        if (isset($_POST['delete_favorite'])) $userObj->removeFavorite((int)$tmdb_id, $id_utente);

        // Gestione POST watchlist
        if (isset($_POST['watchlist'])) $userObj->addWatchlist((int)$tmdb_id, $id_utente);
        if (isset($_POST['delete_watchlist'])) $userObj->removeWatchlist((int)$tmdb_id, $id_utente);

        // Gestione POST watched
        if (isset($_POST['watched'])) $userObj->addWatched((int)$tmdb_id, $id_utente);
        if (isset($_POST['delete_watched'])) $userObj->removeWatched((int)$tmdb_id, $id_utente);

        // Stato corrente (DOPO aver gestito i POST)
        $is_favorite = $userObj->isFavorite((int)$tmdb_id, $id_utente);
        $is_watchlist = $userObj->isInWatchlist((int)$tmdb_id, $id_utente);
        $is_watched = $userObj->isWatched((int)$tmdb_id, $id_utente);
        $is_review = $userObj->hasReview((int)$tmdb_id, $id_utente);

        // Se ha una recensione, il film è implicitamente "visto"
        if ($is_review) $is_watched = true;

    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
        $errore = "Errore nell'aggiornamento delle liste";
    }
}

// Conteggio recensioni della community
$recensioni_altri = 0;
if ($tmdb_id !== null) {
    try {
        $userObj          = $userObj ?? new userObj($conn, '');
        $recensioni_altri = $userObj->countReviews((int)$tmdb_id);
    } catch (PDOException $e) {
        error_log("Errore nel DB: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $movie_db ? htmlspecialchars($titolo) : 'Film' ?> - Cinevobis</title>
    
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        .cast-avatar {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        .text-justify {
            text-align: justify;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../../includes/header.php'); ?>

    <main class="container my-5 flex-grow-1">
        <?php if ($movie_db): ?>
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card p-4 p-md-5 mb-5">

                        <div class="row g-5 mb-5">
                            <div class="col-md-4">
                                <?php if ($poster_path): ?>
                                    <img src="https://image.tmdb.org/t/p/w500<?= $poster_path ?>" 
                                         class="img-fluid rounded-4 shadow-md w-100" 
                                         alt="Poster di <?= htmlspecialchars($titolo) ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center rounded-4 shadow-sm w-100" 
                                         style="aspect-ratio: 2/3; background-color: var(--bg-muted); border: 2px dashed var(--border);">
                                        <div class="text-center">
                                            <i class="bi bi-film text-muted" style="font-size: 3.5rem;"></i>
                                            <p class="text-muted small mt-2 fw-medium">Poster non disponibile</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($trailerKey): ?>
                                    <div class="mt-4">
                                        <button type="button"
                                            class="btn btn-dark w-100 py-2 d-flex align-items-center justify-content-center"
                                            data-bs-toggle="modal"
                                            data-bs-target="#trailerModal">
                                            <i class="bi bi-play-circle-fill fs-5 me-2"></i> Guarda Trailer
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-8">
                                <h1 class="fw-bold display-5 mb-2" style="color: var(--text);"><?= htmlspecialchars($titolo) ?></h1>

                                <?php if (!empty($titolo_orig) && strcasecmp(trim($titolo_orig), trim($titolo)) !== 0): ?>
                                    <p class="fs-5 mb-4" style="color: var(--text-muted);"><?= htmlspecialchars($titolo_orig) ?></p>
                                <?php endif; ?>

                                <?php if (!empty($registi)): ?>
                                    <div class="mb-4">
                                        <small class="text-uppercase fw-bold d-block mb-1" style="letter-spacing: 1px; color: var(--text-muted);">Regia</small>
                                        <p class="fs-5 fw-medium mb-0" style="color: var(--text);">
                                            <?php
                                            $registi_links = array_map(function ($regista) {
                                                $name = htmlspecialchars($regista['name']);
                                                $id   = urlencode($regista['id']);
                                                return "<a href='https://www.themoviedb.org/person/$id' class='text-decoration-none' style='color: var(--accent); transition: color 0.2s;' onmouseover='this.style.color=\"var(--accent-hover)\"' onmouseout='this.style.color=\"var(--accent)\"'>$name</a>";
                                            }, $registi);
                                            echo implode(', ', $registi_links);
                                            ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($generi as $genre): ?>
                                        <a href="search_genre.php?id=<?= urlencode($genre['id']) ?>&name=<?= urlencode($genre['name']) ?>" 
                                            class="badge text-decoration-none px-3 py-2" 
                                            style="background-color: var(--bg-muted); color: var(--text); border: 1px solid var(--border); transition: var(--transition);"
                                            onmouseover="this.style.borderColor='var(--accent)'; this.style.backgroundColor='white';"
                                            onmouseout="this.style.borderColor='var(--border)'; this.style.backgroundColor='var(--bg-muted)';">
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>

                                <?php if (isset($_SESSION['username'])): ?>
                                    <form method="POST" class="d-flex flex-wrap gap-2 mb-4 pb-4 border-bottom">
                                        
                                        <button class="btn <?= $is_favorite ? 'btn-danger' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3" name="<?= $is_favorite ? 'delete_favorite' : 'favorite' ?>">
                                            <i class="bi bi-heart-fill me-1"></i> <?= $is_favorite ? 'Rimuovi' : 'Preferiti' ?>
                                        </button>

                                        <button class="btn <?= $is_watchlist ? 'btn-primary' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3" name="<?= $is_watchlist ? 'delete_watchlist' : 'watchlist' ?>">
                                            <i class="bi bi-bookmark-fill me-1"></i> <?= $is_watchlist ? 'Rimuovi' : 'Watchlist' ?>
                                        </button>

                                        <button class="btn <?= $is_watched ? 'btn-success' : 'btn-outline-secondary' ?> btn-sm rounded-pill px-3" name="<?= $is_watched ? 'delete_watched' : 'watched' ?>">
                                            <i class="bi bi-eye-fill me-1"></i> <?= $is_watched ? 'Rimuovi' : 'Watched' ?>
                                        </button>

                                        <a href="/pages/user/review.php?tmdb_id=<?= urlencode($tmdb_id) ?>" class="btn btn-dark btn-sm rounded-pill px-3">
                                            <i class="bi bi-pencil-fill me-1"></i> <?= $is_review ? "Modifica recensione" : "Scrivi recensione" ?>
                                        </a>
                                    </form>
                                <?php endif; ?>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="fw-bold m-0" style="color: var(--text);">Sinossi</h4>
                                        <div class="d-flex align-items-center fs-4 fw-bold">
                                            <i class="bi bi-star-fill text-warning me-2"></i>
                                            <span>
                                                <?= number_format($voto, 1) ?>
                                                <small style="color: var(--text-muted);" class="fw-normal fs-6">/ 10</small>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-justify lh-lg fs-6 mb-4" style="color: var(--text-muted);"><?= nl2br(htmlspecialchars($trama)) ?></p>
                                    
                                    <?php if ($recensioni_altri > 0): ?>
                                        <a href="/pages/public/community_reviews.php?tmdb_id=<?= urlencode($tmdb_id) ?>" class="text-decoration-none fw-bold" style="color: var(--accent);">
                                            <i class="bi bi-chat-left-text-fill me-1"></i> Leggi le recensioni della community
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center py-4 rounded-4 mb-5 mx-0" style="background-color: var(--bg-muted); border: 1px solid var(--border);">
                            <div class="col-4 border-end" style="border-color: var(--border) !important;">
                                <div class="small text-uppercase fw-bold" style="color: var(--text-muted);">Durata</div>
                                <div class="fw-bold fs-5" style="color: var(--text);"><?= $durata ?> min</div>
                            </div>
                            <div class="col-4 border-end" style="border-color: var(--border) !important;">
                                <div class="small text-uppercase fw-bold" style="color: var(--text-muted);">Anno</div>
                                <div class="fw-bold fs-5" style="color: var(--text);"><?= $anno ?></div>
                            </div>
                            <div class="col-4">
                                <div class="small text-uppercase fw-bold" style="color: var(--text-muted);">Paese</div>
                                <div class="fw-bold fs-5" style="color: var(--text);"><?= $paese ?></div>
                            </div>
                        </div>

                        <div>
                            <h4 class="fw-bold mb-4" style="color: var(--text);">Cast Principale</h4>
                            <div class="row g-3">
                                <?php foreach ($cast as $actor):
                                    $nome   = $actor['name']      ?? 'Attore Sconosciuto';
                                    $ruolo  = $actor['character'] ?? 'Personaggio non specificato';
                                    $idTMDB = $actor['id']        ?? '';
                                    $path   = $actor['profile_path'] ?? null;
                                    $fotoUrl = $path
                                        ? "https://image.tmdb.org/t/p/w185" . $path
                                        : "https://ui-avatars.com/api/?name=" . urlencode($nome) . "&background=f1f5f9&color=64748b";
                                ?>
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="https://www.themoviedb.org/person/<?= $idTMDB ?>" class="text-decoration-none d-block" target="_blank">
                                        <div class="d-flex align-items-center p-2 rounded-3 transition-hover" 
                                            style="background-color: var(--bg-surface); border: 1px solid var(--border);">
                                            
                                            <img src="<?= $fotoUrl ?>"
                                                class="cast-avatar rounded-circle border border-2 border-white shadow-sm me-3"
                                                style="width: 50px; height: 50px; object-fit: cover;"
                                                loading="lazy"
                                                alt="<?= htmlspecialchars($nome) ?>">

                                            <div class="overflow-hidden">
                                                <p class="mb-0 fw-bold text-truncate" style="font-size: 0.95rem; color: var(--text);">
                                                    <?= htmlspecialchars($nome) ?>
                                                </p>
                                                <p class="mb-0 text-truncate" style="font-size: 0.85rem; color: var(--text-muted);">
                                                    <?= htmlspecialchars($ruolo) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php if ($trailerKey): ?>
            <div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="ratio ratio-16x9 shadow-lg rounded-4 overflow-hidden" style="background: #000;">
                                <iframe id="trailerVideo"
                                    data-src="https://www.youtube.com/embed/<?= $trailerKey ?>?rel=0&autoplay=1"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning shadow-sm rounded-4 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div><?= htmlspecialchars($errore) ?></div>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once(__DIR__ . '/../../includes/footer.php'); ?>

    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/script.js"></script>
</body>
</html>
```
