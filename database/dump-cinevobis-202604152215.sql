/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.2.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: cinevobis
-- ------------------------------------------------------
-- Server version	12.2.2-MariaDB

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
-- Table structure for table `films`
--

DROP TABLE IF EXISTS `films`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `films` (
  `id_film` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(255) NOT NULL,
  `trama` text DEFAULT NULL,
  `durata_minuti` int(11) DEFAULT NULL,
  `data_uscita` date DEFAULT NULL,
  `copertina_path` varchar(255) DEFAULT NULL,
  `trailer_id` varchar(20) DEFAULT NULL,
  `iso_code` char(2) DEFAULT NULL,
  PRIMARY KEY (`id_film`),
  KEY `fk_films_nazioni` (`iso_code`),
  CONSTRAINT `fk_films_nazioni` FOREIGN KEY (`iso_code`) REFERENCES `nazioni` (`iso_code`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `films`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `films` WRITE;
/*!40000 ALTER TABLE `films` DISABLE KEYS */;
/*!40000 ALTER TABLE `films` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `films_generi`
--

DROP TABLE IF EXISTS `films_generi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `films_generi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_film` int(11) NOT NULL,
  `id_genere` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_film` (`id_film`,`id_genere`),
  KEY `id_genere` (`id_genere`),
  CONSTRAINT `films_generi_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `films` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `films_generi_ibfk_2` FOREIGN KEY (`id_genere`) REFERENCES `generi` (`id_genere`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `films_generi`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `films_generi` WRITE;
/*!40000 ALTER TABLE `films_generi` DISABLE KEYS */;
/*!40000 ALTER TABLE `films_generi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `films_persone`
--

DROP TABLE IF EXISTS `films_persone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `films_persone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_film` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `ruolo` enum('Regista','Attore') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_film` (`id_film`),
  KEY `id_persona` (`id_persona`),
  CONSTRAINT `films_persone_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `films` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `films_persone_ibfk_2` FOREIGN KEY (`id_persona`) REFERENCES `persone` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `films_persone`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `films_persone` WRITE;
/*!40000 ALTER TABLE `films_persone` DISABLE KEYS */;
/*!40000 ALTER TABLE `films_persone` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `generi`
--

DROP TABLE IF EXISTS `generi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `generi` (
  `id_genere` int(11) NOT NULL AUTO_INCREMENT,
  `nome_genere` varchar(100) NOT NULL,
  PRIMARY KEY (`id_genere`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `generi`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `generi` WRITE;
/*!40000 ALTER TABLE `generi` DISABLE KEYS */;
INSERT INTO `generi` VALUES
(1,'Action'),
(2,'Adventure'),
(3,'Animation'),
(4,'Comedy'),
(5,'Crime'),
(6,'Documentary'),
(7,'Drama'),
(8,'Family'),
(9,'Fantasy'),
(10,'History'),
(11,'Horror'),
(12,'Music'),
(13,'Mystery'),
(14,'Romance'),
(15,'Science Fiction'),
(16,'Thriller'),
(17,'TV Movie'),
(18,'War'),
(19,'Western');
/*!40000 ALTER TABLE `generi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `nazioni`
--

DROP TABLE IF EXISTS `nazioni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nazioni` (
  `iso_code` char(2) NOT NULL,
  `nome_nazione` varchar(100) NOT NULL,
  PRIMARY KEY (`iso_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nazioni`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `nazioni` WRITE;
/*!40000 ALTER TABLE `nazioni` DISABLE KEYS */;
INSERT INTO `nazioni` VALUES
('AD','Andorra'),
('AE','Emirati Arabi Uniti'),
('AF','Afghanistan'),
('AG','Antigua e Barbuda'),
('AL','Albania'),
('AM','Armenia'),
('AO','Angola'),
('AR','Argentina'),
('AT','Austria'),
('AU','Australia'),
('AZ','Azerbaigian'),
('BA','Bosnia ed Erzegovina'),
('BB','Barbados'),
('BD','Bangladesh'),
('BE','Belgio'),
('BF','Burkina Faso'),
('BG','Bulgaria'),
('BH','Bahrein'),
('BI','Burundi'),
('BJ','Benin'),
('BN','Brunei'),
('BO','Bolivia'),
('BR','Brasile'),
('BS','Bahamas'),
('BT','Bhutan'),
('BW','Botswana'),
('BY','Bielorussia'),
('BZ','Belize'),
('CA','Canada'),
('CD','Repubblica Democratica del Congo'),
('CF','Repubblica Centrafricana'),
('CG','Congo'),
('CH','Svizzera'),
('CI','Costa d\'Avorio'),
('CL','Cile'),
('CM','Camerun'),
('CN','Cina'),
('CO','Colombia'),
('CR','Costa Rica'),
('CU','Cuba'),
('CV','Capo Verde'),
('CY','Cipro'),
('CZ','Repubblica Ceca'),
('DE','Germania'),
('DJ','Gibuti'),
('DK','Danimarca'),
('DM','Dominica'),
('DO','Repubblica Dominicana'),
('DZ','Algeria'),
('EC','Ecuador'),
('EE','Estonia'),
('EG','Egitto'),
('ER','Eritrea'),
('ES','Spagna'),
('ET','Etiopia'),
('FI','Finlandia'),
('FJ','Figi'),
('FM','Micronesia'),
('FR','Francia'),
('GA','Gabon'),
('GB','Regno Unito'),
('GD','Grenada'),
('GE','Georgia'),
('GH','Ghana'),
('GM','Gambia'),
('GN','Guinea'),
('GQ','Guinea Equatoriale'),
('GR','Grecia'),
('GT','Guatemala'),
('GW','Guinea-Bissau'),
('GY','Guyana'),
('HN','Honduras'),
('HR','Croazia'),
('HT','Haiti'),
('HU','Ungheria'),
('ID','Indonesia'),
('IE','Irlanda'),
('IL','Israele'),
('IN','India'),
('IQ','Iraq'),
('IR','Iran'),
('IS','Islanda'),
('IT','Italia'),
('JM','Giamaica'),
('JO','Giordania'),
('JP','Giappone'),
('KE','Kenya'),
('KG','Kirghizistan'),
('KH','Cambogia'),
('KI','Kiribati'),
('KM','Comore'),
('KN','Saint Kitts e Nevis'),
('KP','Corea del Nord'),
('KR','Corea del Sud'),
('KW','Kuwait'),
('KZ','Kazakistan'),
('LA','Laos'),
('LB','Libano'),
('LC','Santa Lucia'),
('LI','Liechtenstein'),
('LK','Sri Lanka'),
('LR','Liberia'),
('LS','Lesotho'),
('LT','Lituania'),
('LU','Lussemburgo'),
('LV','Lettonia'),
('LY','Libia'),
('MA','Marocco'),
('MC','Monaco'),
('MD','Moldavia'),
('ME','Montenegro'),
('MG','Madagascar'),
('MH','Isole Marshall'),
('MK','Macedonia del Nord'),
('ML','Mali'),
('MM','Myanmar'),
('MN','Mongolia'),
('MR','Mauritania'),
('MT','Malta'),
('MU','Mauritius'),
('MV','Maldive'),
('MW','Malawi'),
('MX','Messico'),
('MY','Malesia'),
('MZ','Mozambico'),
('NA','Namibia'),
('NE','Niger'),
('NG','Nigeria'),
('NI','Nicaragua'),
('NL','Paesi Bassi'),
('NO','Norvegia'),
('NP','Nepal'),
('NR','Nauru'),
('NZ','Nuova Zelanda'),
('OM','Oman'),
('PA','Panama'),
('PE','Perù'),
('PG','Papua Nuova Guinea'),
('PH','Filippine'),
('PK','Pakistan'),
('PL','Polonia'),
('PT','Portogallo'),
('PW','Palau'),
('PY','Paraguay'),
('QA','Qatar'),
('RO','Romania'),
('RS','Serbia'),
('RU','Russia'),
('RW','Ruanda'),
('SA','Arabia Saudita'),
('SB','Isole Salomone'),
('SC','Seychelles'),
('SD','Sudan'),
('SE','Svezia'),
('SG','Singapore'),
('SI','Slovenia'),
('SK','Slovacchia'),
('SL','Sierra Leone'),
('SM','San Marino'),
('SN','Senegal'),
('SO','Somalia'),
('SR','Suriname'),
('SS','Sud Sudan'),
('ST','Sao Tome e Principe'),
('SV','El Salvador'),
('SY','Siria'),
('SZ','Eswatini'),
('TD','Ciad'),
('TG','Togo'),
('TH','Thailandia'),
('TJ','Tagikistan'),
('TL','Timor Est'),
('TM','Turkmenistan'),
('TN','Tunisia'),
('TO','Tonga'),
('TR','Turchia'),
('TT','Trinidad e Tobago'),
('TV','Tuvalu'),
('TZ','Tanzania'),
('UA','Ucraina'),
('UG','Uganda'),
('US','Stati Uniti'),
('UY','Uruguay'),
('UZ','Uzbekistan'),
('VA','Città del Vaticano'),
('VC','Saint Vincent e Grenadine'),
('VE','Venezuela'),
('VN','Vietnam'),
('VU','Vanuatu'),
('WS','Samoa'),
('YE','Yemen'),
('ZA','Sudafrica'),
('ZM','Zambia'),
('ZW','Zimbabwe');
/*!40000 ALTER TABLE `nazioni` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `persone`
--

DROP TABLE IF EXISTS `persone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `persone` (
  `id_persona` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `wikipedia_slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persone`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `persone` WRITE;
/*!40000 ALTER TABLE `persone` DISABLE KEYS */;
/*!40000 ALTER TABLE `persone` ENABLE KEYS */;
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_film` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_inserimento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_film` (`id_film`,`id_utente`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `preferiti_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `films` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `preferiti_ibfk_2` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preferiti`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `preferiti` WRITE;
/*!40000 ALTER TABLE `preferiti` DISABLE KEYS */;
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
  `id_film` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `voto` decimal(2,1) DEFAULT NULL,
  `testo` text DEFAULT NULL,
  `data_inserimento` datetime DEFAULT NULL,
  PRIMARY KEY (`id_recensione`),
  UNIQUE KEY `id_film` (`id_film`,`id_utente`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `recensioni_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `films` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `recensioni_ibfk_2` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recensioni`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `recensioni` WRITE;
/*!40000 ALTER TABLE `recensioni` DISABLE KEYS */;
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
('0eb1ac60d8626eb1cf8045519e9444c0',2,'2026-03-27 09:45:10','2026-03-27 10:02:54'),
('1427dbbb647869250dff50346ccebf67',2,'2026-03-25 19:22:08','2026-03-25 19:24:39'),
('1547627f3983632f5d310998a9856a29',1,'2026-03-30 18:05:10','2026-03-30 18:07:30'),
('16571ac889b5cefc8f0bfe7a94dd9847',1,'2026-03-25 18:29:10','2026-03-25 18:39:26'),
('172de2978f96be95c4d6855dc95b204f',1,'2026-03-27 10:09:41','2026-03-27 10:10:11'),
('3b41f7da090d28ff20a5c453c2226d13',2,'2026-03-27 09:40:44',NULL),
('3e2de35bf38901d714e9cb2fc04ed095',51,'2026-03-31 12:29:15','2026-03-31 13:37:09'),
('45c5fdd9888848e3bdb440d69f4f2bc2',6,'2026-03-30 18:28:01','2026-03-30 18:28:06'),
('465e3238ffc522b2805a9ba74c1c99f6',2,'2026-03-25 18:41:39',NULL),
('4b960c31d175a7419ef1f0c5273ef1a5',2,'2026-03-25 23:16:55','2026-03-25 23:16:59'),
('5b7bda9b350fea0ba30093c3acfd1ff8',2,'2026-04-01 17:21:37','2026-04-01 17:22:30'),
('5c148b6cd691e7ebbf83e58b1679ed9f',2,'2026-03-27 09:41:04','2026-03-27 09:42:54'),
('709454eaa25c661910727039320da64b',2,'2026-03-31 12:26:43','2026-03-31 12:28:11'),
('7ce18c445789eee0f5a4b2063b518698',2,'2026-03-25 18:41:28','2026-03-25 18:41:32'),
('7d072893aa02a39c3c1ae38744895b0e',2,'2026-03-30 12:41:20','2026-03-30 12:41:34'),
('820740028b1527d493648cc99d077d04',2,'2026-04-01 17:23:36','2026-04-01 17:24:35'),
('86a3dbd4354229c3c5b737110fa25798',2,'2026-03-25 19:30:24','2026-03-25 19:30:32'),
('8728ce84b027b0afd50bf431e2d7d22a',1,'2026-03-25 19:24:57','2026-03-25 19:26:40'),
('8fb61c569c3b82288f5b463ebe4a1c1e',1,'2026-03-30 18:28:12','2026-03-30 18:34:00'),
('92501a7ab73b24ee226685fec0220dd4',1,'2026-03-30 18:27:37','2026-03-30 18:27:45'),
('960b32e69df31b01bf9a1af546693cd8',1,'2026-03-27 10:03:00','2026-03-27 10:04:57'),
('987337052542c7c8e22c43d8737a8c1b',2,'2026-04-12 16:29:50','2026-04-12 17:10:29'),
('9b0f43f71716e12b82dda89289bdd92f',2,'2026-04-01 17:37:59',NULL),
('9f3fa601096c26b4994ecb4a3c8306a8',1,'2026-03-25 18:23:48',NULL),
('a477f9ef5225a29f395fe3fc0e596464',1,'2026-03-25 18:20:29','2026-03-25 18:22:05'),
('a50e619319739be40ad1ae087bb078d1',2,'2026-03-27 09:43:01','2026-03-27 09:45:02'),
('a828bcb8e90b23e01d3419845f16c477',2,'2026-03-28 12:58:08',NULL),
('b2aaaaee1367be421908b1b2e875b382',2,'2026-04-15 22:10:16','2026-04-15 22:10:21'),
('b5626e2b600011ca8d582e7d88a92eab',1,'2026-04-15 22:09:09','2026-04-15 22:10:08'),
('bc43366d58b172cff0a4336ba922093d',1,'2026-03-25 18:22:14','2026-03-25 18:22:48'),
('bc76cfd9f3cb5e4ab157efef871b68f9',1,'2026-03-25 23:17:10',NULL),
('c10e102ab9185ad8a1f85f4c6108624b',51,'2026-03-31 12:28:21','2026-03-31 12:29:03'),
('c67edbcb4a36cb08c32c4408f8fbba29',2,'2026-03-27 09:35:34','2026-03-27 09:40:10'),
('d14eed9bbfcca0519cfc673d405a5dc4',2,'2026-03-25 19:40:09','2026-03-25 19:53:23'),
('da3f07ec30e02ad0d9b7d19f80b195a4',1,'2026-03-25 19:26:56',NULL),
('eebe2d92da980ef2c211163560e52b73',2,'2026-03-30 18:07:42','2026-03-30 18:23:30'),
('f2c98e62d02ae5874cff68c3aeb49465',1,'2026-03-27 09:35:22','2026-03-27 09:35:28'),
('f404018aacf436676f3d15a1ea30da5e',1,'2026-03-30 18:23:39','2026-03-30 18:27:27'),
('fb127c43b77f7b43dbca3ccb20be7b0d',2,'2026-03-31 12:26:28','2026-03-31 12:26:34'),
('fe452417214d19681992ec7a7cad7de7',2,'2026-03-30 18:04:06','2026-03-30 18:04:58');
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
  `citta` varchar(100) DEFAULT NULL,
  `id_profilo` int(11) DEFAULT NULL,
  `iso_code` char(2) DEFAULT NULL,
  `attivo` tinyint(1) DEFAULT 1,
  `foto_profilo` varchar(255) DEFAULT NULL,
  `data_registrazione` datetime DEFAULT NULL,
  PRIMARY KEY (`id_utente`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `id_profilo` (`id_profilo`),
  KEY `iso_code` (`iso_code`),
  CONSTRAINT `utenti_ibfk_1` FOREIGN KEY (`id_profilo`) REFERENCES `profili` (`id_profilo`) ON UPDATE CASCADE,
  CONSTRAINT `utenti_ibfk_2` FOREIGN KEY (`iso_code`) REFERENCES `nazioni` (`iso_code`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
INSERT INTO `utenti` VALUES
(1,'alessio','$2y$12$UZsjtBpHifZRYxdNOxfUF.jvdAfcn8XGBEN1Q2sV7xhq1khjsu.z.','alessio@gmail.com','Alessio','Gualtieri','Baranello',1,'IT',1,NULL,'2026-03-25 17:19:40'),
(2,'mario.rossi','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','mario.rossi@mail.it','Mario','Rossi','Roma',2,'IT',1,NULL,'2026-03-25 18:22:28'),
(3,'luca.bianchi','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','luca.b@mail.it','Luca','Bianchi','Milano',2,'IT',1,NULL,'2026-03-25 18:22:28'),
(4,'giulia.verdi','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','giulia.v@mail.it','Giulia','Verdi','Napoli',2,'IT',1,NULL,'2026-03-25 18:22:28'),
(5,'john.smith','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','jsmith@mail.com','John','Smith','New York',2,'US',1,NULL,'2026-03-25 18:22:28'),
(6,'emma.watson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','ewatson@mail.co.uk','Emma','Watson','Londra',2,'GB',1,NULL,'2026-03-25 18:22:28'),
(7,'pierre.dupont','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','pierre@mail.fr','Pierre','Dupont','Parigi',2,'FR',1,NULL,'2026-03-25 18:22:28'),
(8,'hans.muller','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','hans@mail.de','Hans','Muller','Berlino',2,'DE',1,NULL,'2026-03-25 18:22:28'),
(9,'carlos.santana','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','carlos@mail.es','Carlos','Santana','Madrid',2,'ES',1,NULL,'2026-03-25 18:22:28'),
(10,'kenji.sato','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','kenji@mail.jp','Kenji','Sato','Tokyo',2,'JP',1,NULL,'2026-03-25 18:22:28'),
(11,'min.ji','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','minji@mail.kr','Min','Ji','Seoul',2,'KR',1,NULL,'2026-03-25 18:22:28'),
(12,'chen.wei','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','chen@mail.cn','Chen','Wei','Pechino',2,'CN',1,NULL,'2026-03-25 18:22:28'),
(13,'olivia.brown','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','olivia@mail.ca','Olivia','Brown','Toronto',2,'CA',1,NULL,'2026-03-25 18:22:28'),
(14,'liam.neeson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','liam.n@mail.ie','Liam','Neeson','Dublino',2,'IE',1,NULL,'2026-03-25 18:22:28'),
(15,'maria.garcia','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','maria@mail.mx','Maria','Garcia','Città del Messico',2,'MX',1,NULL,'2026-03-25 18:22:28'),
(16,'joao.silva','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','joao@mail.br','Joao','Silva','Rio de Janeiro',2,'BR',1,NULL,'2026-03-25 18:22:28'),
(17,'diego.maradona','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','diego@mail.ar','Diego','Maradona','Buenos Aires',2,'AR',1,NULL,'2026-03-25 18:22:28'),
(18,'lars.svensson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','lars@mail.se','Lars','Svensson','Stoccolma',2,'SE',1,NULL,'2026-03-25 18:22:28'),
(19,'peter.jackson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','peter@mail.nz','Peter','Jackson','Wellington',2,'NZ',1,NULL,'2026-03-25 18:22:28'),
(20,'raj.patel','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','raj@mail.in','Raj','Patel','Mumbai',2,'IN',1,NULL,'2026-03-25 18:22:28'),
(21,'ivan.drago','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','ivan@mail.ru','Ivan','Drago','Mosca',2,'RU',1,NULL,'2026-03-25 18:22:28'),
(22,'ahmed.ali','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','ahmed@mail.eg','Ahmed','Ali','Il Cairo',2,'EG',1,NULL,'2026-03-25 18:22:28'),
(23,'charlize.theron','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','charlize@mail.za','Charlize','Theron','Pretoria',2,'ZA',1,NULL,'2026-03-25 18:22:28'),
(24,'sofia.gomez','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sofia@mail.co','Sofia','Gomez','Bogotá',2,'CO',1,NULL,'2026-03-25 18:22:28'),
(25,'andrea.romano','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','andrea@mail.it','Andrea','Romano','Torino',2,'IT',1,NULL,'2026-03-25 18:22:28'),
(26,'marta.ferrari','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','marta@mail.it','Marta','Ferrari','Venezia',2,'IT',1,NULL,'2026-03-25 18:22:28'),
(27,'william.wallace','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','william@mail.gb','William','Wallace','Edimburgo',2,'GB',1,NULL,'2026-03-25 18:22:28'),
(28,'elena.kournikova','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','elena@mail.ru','Elena','Kournikova','San Pietroburgo',2,'RU',1,NULL,'2026-03-25 18:22:28'),
(29,'david.beckham','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','david@mail.gb','David','Beckham','Manchester',2,'GB',1,NULL,'2026-03-25 18:22:28'),
(30,'yuki.tsunoda','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','yuki@mail.jp','Yuki','Tsunoda','Osaka',2,'JP',1,NULL,'2026-03-25 18:22:28'),
(31,'alessandro.delpiero','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','alex@mail.it','Alessandro','Del Piero','Padova',2,'IT',1,NULL,'2026-03-25 18:22:28'),
(32,'sarah.connor','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sarah@mail.us','Sarah','Connor','Los Angeles',2,'US',1,NULL,'2026-03-25 18:22:28'),
(33,'bruce.wayne','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','bruce@mail.us','Bruce','Wayne','Gotham',2,'US',1,NULL,'2026-03-25 18:22:28'),
(34,'clark.kent','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','clark@mail.us','Clark','Kent','Metropolis',2,'US',1,NULL,'2026-03-25 18:22:28'),
(35,'diana.prince','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','diana@mail.us','Diana','Prince','Washington',2,'US',1,NULL,'2026-03-25 18:22:28'),
(36,'peter.parker','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','peter.p@mail.us','Peter','Parker','New York',2,'US',1,NULL,'2026-03-25 18:22:28'),
(37,'tony.stark','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','tony@mail.us','Tony','Stark','Malibu',2,'US',1,NULL,'2026-03-25 18:22:28'),
(38,'steve.rogers','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','steve@mail.us','Steve','Rogers','Brooklyn',2,'US',1,NULL,'2026-03-25 18:22:28'),
(39,'natasha.romanoff','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','natasha@mail.ru','Natasha','Romanoff','Stalingrado',2,'RU',1,NULL,'2026-03-25 18:22:28'),
(40,'wanda.maximoff','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','wanda@mail.rs','Wanda','Maximoff','Sokovia',2,'RS',1,NULL,'2026-03-25 18:22:28'),
(41,'stephen.strange','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','stephen@mail.us','Stephen','Strange','New York',2,'US',1,NULL,'2026-03-25 18:22:28'),
(42,'thor.odinson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','thor@mail.no','Thor','Odinson','Asgard',2,'NO',1,NULL,'2026-03-25 18:22:28'),
(43,'loki.laufeyson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','loki@mail.no','Loki','Laufeyson','Jotunheim',2,'NO',1,NULL,'2026-03-25 18:22:28'),
(44,'bruce.banner','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','banner@mail.us','Bruce','Banner','Dayton',2,'US',1,NULL,'2026-03-25 18:22:28'),
(45,'clint.barton','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','clint@mail.us','Clint','Barton','Waverly',2,'US',1,NULL,'2026-03-25 18:22:28'),
(46,'sam.wilson','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','sam@mail.us','Sam','Wilson','Harlem',2,'US',1,NULL,'2026-03-25 18:22:28'),
(47,'bucky.barnes','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','bucky@mail.us','Bucky','Barnes','Brooklyn',2,'US',1,NULL,'2026-03-25 18:22:28'),
(48,'scott.lang','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','scott@mail.us','Scott','Lang','San Francisco',2,'US',1,NULL,'2026-03-25 18:22:28'),
(49,'hope.vandyne','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','hope@mail.us','Hope','Van Dyne','San Francisco',2,'US',1,NULL,'2026-03-25 18:22:28'),
(50,'carol.danvers','$2y$10$4TMwlTJPm4uzHZyA20UEVeRtjQ7qNW5jeXsrfwJ4AXPg.3iLvHb9e','carol@mail.us','Carol','Danvers','Boston',2,'US',1,NULL,'2026-03-25 18:22:28'),
(51,'dilin','$2y$12$1Wl7W4AKNMAa/6k9p3DttOJKICOPgtE0spDV6JP0dYbt45GwFOE/q','dydy16@example.com','dydy','palladain','Campobasso',2,'IT',1,NULL,'2026-03-31 10:25:42');
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_film` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_inserimento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_film` (`id_film`,`id_utente`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `watched_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `films` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `watched_ibfk_2` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `watched`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `watched` WRITE;
/*!40000 ALTER TABLE `watched` DISABLE KEYS */;
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_film` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `data_inserimento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_film` (`id_film`,`id_utente`),
  KEY `id_utente` (`id_utente`),
  CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `films` (`id_film`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `watchlist`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `watchlist` WRITE;
/*!40000 ALTER TABLE `watchlist` DISABLE KEYS */;
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

-- Dump completed on 2026-04-15 22:15:46
