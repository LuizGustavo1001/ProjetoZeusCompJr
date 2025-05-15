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
-- Table structure for table `funcionario`
--

DROP TABLE IF EXISTS `funcionario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `funcionario` (
  `idEmpl` int(11) NOT NULL AUTO_INCREMENT,
  `nameEmpl` varchar(30) NOT NULL,
  `bDayEmpl` date NOT NULL,
  `emailEmpl` varchar(40) NOT NULL,
  `genderEmpl` enum('M','F','O') NOT NULL,
  `numberEmpl` varchar(15) DEFAULT NULL,
  `emplPos` varchar(30) NOT NULL,
  `entryDate` date NOT NULL,
  `areaEmpl` varchar(30) NOT NULL,
  `emplPassword` varchar(30) NOT NULL,
  PRIMARY KEY (`idEmpl`),
  UNIQUE KEY `emailEmpl` (`emailEmpl`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funcionario`
--

LOCK TABLES `funcionario` WRITE;
/*!40000 ALTER TABLE `funcionario` DISABLE KEYS */;
INSERT INTO `funcionario` VALUES (1,'Farofilson Bananilson','2001-06-10','bananilson@gmail.com','M','(31) 9 7505 425','Recursos Humanos','2018-01-11','Gerencia','farofa1'),(2,'Luiz Lopes','2004-01-18','guatavw1001@gmail.com','M','(31) 9 7403 314','Projetos','2020-05-13','Gerencia','123asd'),(3,'Daniel Drumond','2001-10-22','gonzaguinha@gmail.com','M','(31) 9 5424 865','Operacoes','2023-10-23','SAC','gons135'),(4,'Ademilson Tupilson','1990-10-11','ademilson@gmail.com','M','(35) 9 4533 903','Operacoes','2020-02-18','Projetos','asdasd'),(5,'Ana Banana','2006-05-15','anaBnn@gmail.com','F','Operacoes','(31) 9 8779 4255','2024-10-24','Projetos','bnana123');
/*!40000 ALTER TABLE `funcionario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orcamento`
--

DROP TABLE IF EXISTS `orcamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orcamento` (
  `idOrc` int(11) NOT NULL AUTO_INCREMENT,
  `numOrc` int(11) NOT NULL,
  `descProj` longtext DEFAULT NULL,
  `valorOrc` decimal(10,2) NOT NULL,
  `custoOrc` decimal(10,2) NOT NULL,
  `cliente` varchar(30) NOT NULL,
  PRIMARY KEY (`idOrc`),
  UNIQUE KEY `numOrc` (`numOrc`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orcamento`
--

LOCK TABLES `orcamento` WRITE;
/*!40000 ALTER TABLE `orcamento` DISABLE KEYS */;
INSERT INTO `orcamento` VALUES (25,1231,'Descrição Legal 1',123.86,200.46,'Farofilson'),(26,3242,'Descrição Legal 2',1267.24,1552.12,'Ana Banana'),(28,12,'Descrição Legal 3',195.04,403.20,'Tutu');
/*!40000 ALTER TABLE `orcamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'empresapz'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-14 11:25:25
