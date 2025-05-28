CREATE DATABASE  IF NOT EXISTS `empresapz` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `empresapz`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: empresapz
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `budget`
--

DROP TABLE IF EXISTS `budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `budget` (
  `idBudget` int(11) NOT NULL AUTO_INCREMENT,
  `numBudget` int(11) NOT NULL,
  `descBudget` longtext NOT NULL,
  `budgetValue` decimal(10,2) NOT NULL,
  `budgetCost` decimal(10,2) NOT NULL,
  `budgetClient` varchar(30) NOT NULL,
  PRIMARY KEY (`idBudget`),
  UNIQUE KEY `numBudget` (`numBudget`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budget`
--

LOCK TABLES `budget` WRITE;
/*!40000 ALTER TABLE `budget` DISABLE KEYS */;
INSERT INTO `budget` VALUES (1,1231,'Descrição Legal 1',123.86,200.46,'Farofilson Bananilson'),(2,3277,'Descrição Legal 2',1254.65,1444.23,'Ana Banana'),(3,12,'Descrição Legal 3',195.85,403.20,'Tutu'),(4,65,'Descrição Legal 4',554.30,662.00,'Fenilson');
/*!40000 ALTER TABLE `budget` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee` (
  `idEmpl` int(11) NOT NULL AUTO_INCREMENT,
  `nameEmpl` varchar(30) NOT NULL,
  `bDayEmpl` date NOT NULL,
  `emailEmpl` varchar(40) NOT NULL,
  `genderEmpl` enum('M','F','O') NOT NULL,
  `numberEmpl` varchar(16) DEFAULT NULL,
  `emplPos` varchar(30) NOT NULL,
  `entryDate` date NOT NULL,
  `areaEmpl` varchar(30) NOT NULL,
  `emplPassword` varchar(100) NOT NULL,
  `profilePicPath` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idEmpl`),
  UNIQUE KEY `emailEmpl` (`emailEmpl`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (1,'Fagner Abreu','2001-06-05','fagbreu88@gmail.com','M','(31) 9 7X5I 5O0Z','Infraestrutura','2018-01-11','Projetos','$2y$10$40jqOXWnjVJOgf0cgARSEOFfp3OAhgAF.xsvFE8Nq2wNd135pJ98O','../users-page/user-images/img_6830da8f0764d.jpg'),(2,'Clara Fenizza','1979-11-18','fenizza10@gmail.com','F','(33) 9 4B1U 9X7I','Segurança','2009-03-19','RH','$2y$10$9QDBZOjGnnKIf7vAQUKWtOymnBAMsHFlNdEiVuZARPXem/8uPlroi','../users-page/user-images/img_682bab5f60d5b.jpg'),(3,'Ademir Silva','1990-10-11','ademir944@gmail.com','M','(35) 9 7Y8O 91AB','SAC','2020-02-18','Comercial','$2y$10$s5K8c8hDQ1sZGx3K1JQZWes7qaDk71vNfQQ0G1HMGHtuPSlv8dTMG','../users-page/user-images/img_682bab5f60d5c.jpg'),(4,'Ana Alice','2006-05-15','analice81@gmail.com ','F','(31) 9 3X6A 8A6C','Gerente Projetos','2024-10-24','Gerencia','$2y$10$zFDotE8yLBYWTDNhSKcHZelN6te9H7NDN5o3iWeC7OY56Wx59/v6e','../users-page/user-images/img_682bab5f60d5d.png');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rescuepassword`
--

DROP TABLE IF EXISTS `rescuepassword`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rescuepassword` (
  `idRescue` int(11) NOT NULL AUTO_INCREMENT,
  `rescueToken` varchar(6) NOT NULL,
  `dayLimit` varchar(10) NOT NULL,
  `hourLimit` varchar(5) NOT NULL,
  `emailReciever` varchar(40) NOT NULL,
  PRIMARY KEY (`idRescue`),
  UNIQUE KEY `rescueToken` (`rescueToken`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rescuepassword`
--

LOCK TABLES `rescuepassword` WRITE;
/*!40000 ALTER TABLE `rescuepassword` DISABLE KEYS */;
INSERT INTO `rescuepassword` VALUES (47,'029d44','2025-05-22','00:15','anaBnn@gmail.com');
/*!40000 ALTER TABLE `rescuepassword` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-27 22:24:47
