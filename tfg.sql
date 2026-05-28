-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: tfg
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `albums`
--

DROP TABLE IF EXISTS `albums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `albums` (
  `albumId` varchar(100) NOT NULL,
  `groupId` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `korean_title` varchar(200) DEFAULT NULL,
  `album_type` varchar(50) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `photocard_count` int(11) DEFAULT 0,
  `album_version_count` int(11) DEFAULT 0,
  PRIMARY KEY (`albumId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `albums`
--

/*!40000 ALTER TABLE `albums` DISABLE KEYS */;
/*!40000 ALTER TABLE `albums` ENABLE KEYS */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `clienteId` int(11) NOT NULL AUTO_INCREMENT,
  `clienteGuid` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido1` varchar(100) DEFAULT NULL,
  `apellido2` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_alta` datetime DEFAULT current_timestamp(),
  `pais` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `nombre_usuario` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `rol` enum('user','admin') DEFAULT 'user',
  PRIMARY KEY (`clienteId`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  UNIQUE KEY `clienteGuid` (`clienteGuid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'63016deead7b7cc62b1e4911d66d397b','Jose','Sola',NULL,'1993-03-31','2026-05-22 23:24:47','espanya','josesolav23@gmail.com','baishuo','$2y$10$u1gQKG0NlECTzCHpSFc9bOOiAcmSpNVD3sVAIJ2j9SxV8SzTp99FW',1,'admin'),(2,'35b3dc343e80e5512cc4cf7745bb81a5','Mario','Garcia',NULL,'2000-01-19','2026-05-28 18:16:00','espanya','solaygarcia@gmail.com','mariogg','$2y$10$mZTsotXruJ/WME/dK8aHXu/M0QR5gtkuPYRlB4uMSfChyc5KwrhSe',1,'user');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;

--
-- Table structure for table `direcciones`
--

DROP TABLE IF EXISTS `direcciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `direcciones` (
  `direccionId` int(11) NOT NULL AUTO_INCREMENT,
  `clienteId` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `linea2` varchar(100) DEFAULT NULL,
  `calle` varchar(150) NOT NULL,
  `codpostal` varchar(20) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `pais` char(2) NOT NULL,
  `es_principal` tinyint(1) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`direccionId`),
  KEY `fk_direcciones_clientes` (`clienteId`),
  CONSTRAINT `fk_direcciones_clientes` FOREIGN KEY (`clienteId`) REFERENCES `clientes` (`clienteId`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direcciones`
--

/*!40000 ALTER TABLE `direcciones` DISABLE KEYS */;
INSERT INTO `direcciones` VALUES (2,2,'Mario','Garcia','Alarico Lopez','03330','Crevillent','Es',0,'2026-05-28 18:18:34');
/*!40000 ALTER TABLE `direcciones` ENABLE KEYS */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `groupId` varchar(100) NOT NULL,
  `name` varchar(150) NOT NULL,
  `korean_name` varchar(150) DEFAULT NULL,
  `agency` varchar(150) DEFAULT NULL,
  `fandom_name` varchar(150) DEFAULT NULL,
  `debut_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `image_url` text DEFAULT NULL,
  `member_count` int(11) DEFAULT 0,
  `album_count` int(11) DEFAULT 0,
  `photocard_count` int(11) DEFAULT 0,
  `photocard_count_visible` int(11) DEFAULT 0,
  `album_version_count` int(11) DEFAULT 0,
  PRIMARY KEY (`groupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members` (
  `memberId` varchar(100) NOT NULL,
  `groupId` varchar(100) NOT NULL,
  `name` varchar(150) NOT NULL,
  `korean_name` varchar(150) DEFAULT NULL,
  `stage_name` varchar(150) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `position` varchar(100) DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `photocard_count` int(11) DEFAULT 0,
  `unit_only_count` int(11) DEFAULT 0,
  `group_pc_count` int(11) DEFAULT 0,
  PRIMARY KEY (`memberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

/*!40000 ALTER TABLE `members` DISABLE KEYS */;
/*!40000 ALTER TABLE `members` ENABLE KEYS */;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `pedidoId` int(11) NOT NULL AUTO_INCREMENT,
  `clienteId` int(11) NOT NULL,
  `fechaPedido` datetime DEFAULT current_timestamp(),
  `totalPedido` decimal(10,2) NOT NULL,
  `estadoPedido` varchar(20) DEFAULT 'paid',
  PRIMARY KEY (`pedidoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;

--
-- Table structure for table `pedidos_detalle`
--

DROP TABLE IF EXISTS `pedidos_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos_detalle` (
  `detalleId` int(11) NOT NULL AUTO_INCREMENT,
  `pedidoId` int(11) NOT NULL,
  `publicacionId` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`detalleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos_detalle`
--

/*!40000 ALTER TABLE `pedidos_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedidos_detalle` ENABLE KEYS */;

--
-- Table structure for table `photocards`
--

DROP TABLE IF EXISTS `photocards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `photocards` (
  `photocardId` varchar(100) NOT NULL,
  `nombreCarta` varchar(255) NOT NULL,
  `imagenCarta` text DEFAULT NULL,
  PRIMARY KEY (`photocardId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photocards`
--

/*!40000 ALTER TABLE `photocards` DISABLE KEYS */;
INSERT INTO `photocards` VALUES ('cmjxejuad001nih044ln9e5qc','ITZY - Lia - GOLD Album counting stars guangzhou fansign','img_6a1864a41991e.png');
/*!40000 ALTER TABLE `photocards` ENABLE KEYS */;

--
-- Table structure for table `publicaciones`
--

DROP TABLE IF EXISTS `publicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publicaciones` (
  `publicacionId` int(11) NOT NULL AUTO_INCREMENT,
  `publicacionGuid` varchar(100) NOT NULL,
  `clienteId` int(11) NOT NULL,
  `photocardId` varchar(100) NOT NULL,
  `estadoCarta` varchar(50) NOT NULL,
  `imagenCarta` varchar(255) DEFAULT NULL,
  `observacionesCarta` text DEFAULT NULL,
  `precioCarta` decimal(10,2) NOT NULL,
  `cantidadCarta` int(11) NOT NULL DEFAULT 1,
  `fechaPublicacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`publicacionId`),
  UNIQUE KEY `publicacionGuid` (`publicacionGuid`),
  KEY `clienteId` (`clienteId`),
  KEY `photocardId` (`photocardId`),
  CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`clienteId`) REFERENCES `clientes` (`clienteId`),
  CONSTRAINT `publicaciones_ibfk_2` FOREIGN KEY (`photocardId`) REFERENCES `photocards` (`photocardId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicaciones`
--

/*!40000 ALTER TABLE `publicaciones` DISABLE KEYS */;
INSERT INTO `publicaciones` VALUES (1,'pub_6a1864a41f7039.73090752',1,'cmjxejuad001nih044ln9e5qc','mint','img_6a1864a41991e.png','',11.00,1,'2026-05-28 17:52:04');
/*!40000 ALTER TABLE `publicaciones` ENABLE KEYS */;

--
-- Dumping routines for database 'tfg'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-28 18:37:32
