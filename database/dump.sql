/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: cinevobis
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `notifiche`
--

DROP TABLE IF EXISTS `notifiche`;
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
) TYPE=InnoDB AUTO_INCREMENT=31;

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
CREATE TABLE `preferiti` (
  `id_preferito` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_aggiunto` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_preferito`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `preferiti_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) TYPE=InnoDB AUTO_INCREMENT=25;

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
(24,129,1,'2026-05-10 10:02:13');
/*!40000 ALTER TABLE `preferiti` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `profili`
--

DROP TABLE IF EXISTS `profili`;
CREATE TABLE `profili` (
  `id_profilo` int(11) NOT NULL AUTO_INCREMENT,
  `nome_profilo` varchar(100) NOT NULL,
  PRIMARY KEY (`id_profilo`)
) TYPE=InnoDB AUTO_INCREMENT=3;

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
) TYPE=InnoDB AUTO_INCREMENT=57;

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
CREATE TABLE `sessioni` (
  `id_sessione` varchar(255) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_login` datetime DEFAULT NULL,
  `data_logout` datetime DEFAULT NULL,
  PRIMARY KEY (`id_sessione`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `sessioni_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE
) TYPE=InnoDB;

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
('a33a7a8a5ee34c020cfc05f01b258640',1,'2026-05-10 12:02:00',NULL),
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
) TYPE=InnoDB AUTO_INCREMENT=58;

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
CREATE TABLE `watched` (
  `id_watched` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_aggiunto` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_watched`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `watched_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) TYPE=InnoDB AUTO_INCREMENT=45;

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
CREATE TABLE `watchlist` (
  `id_watchlist` int(11) NOT NULL AUTO_INCREMENT,
  `tmdb_id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_aggiunto` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_watchlist`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`)
) TYPE=InnoDB AUTO_INCREMENT=23;

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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-05-10 12:09:15
