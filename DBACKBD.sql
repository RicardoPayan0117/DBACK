CREATE DATABASE  IF NOT EXISTS `dback` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dback`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: dback
-- ------------------------------------------------------
-- Server version	8.0.40

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
-- Table structure for table `datos_empresa`
--

DROP TABLE IF EXISTS `datos_empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `datos_empresa` (
  `Razon_Social` varchar(100) NOT NULL,
  `Nombre_Comercial` varchar(100) DEFAULT NULL,
  `Estado` varchar(100) DEFAULT NULL,
  `Ciudad` varchar(100) DEFAULT NULL,
  `Direccion` varchar(200) DEFAULT NULL,
  `Codigo_Postal` int DEFAULT NULL,
  `Telefono` bigint DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Fecha_Creacion` date DEFAULT NULL,
  `Descripcion` text,
  PRIMARY KEY (`Razon_Social`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datos_empresa`
--

LOCK TABLES `datos_empresa` WRITE;
/*!40000 ALTER TABLE `datos_empresa` DISABLE KEYS */;
INSERT INTO `datos_empresa` VALUES ('Gruas DBACK','SERVICIO DE GRUAS Y MANIOBRAS D´BACK (GASTELUM/BACA)','Sinaloa','Los Mochis','Manuel Castro Elizalde 895 SUR',81233,6688132905,NULL,NULL,'El Servicio de Grúas llamado SERVICIO DE GRUAS Y MANIOBRAS D´BACK (GASTELUM/BACA) ubicada en Sinaloa ciudad Los Mochis ofrece varios servicios de calidad.');
/*!40000 ALTER TABLE `datos_empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleados` (
  `ID_Empleado` int NOT NULL AUTO_INCREMENT,
  `Apellido1` varchar(50) NOT NULL,
  `Apellido2` varchar(50) NOT NULL,
  `Nombre(s)` varchar(100) NOT NULL,
  `RFC` varchar(13) NOT NULL,
  `Nomina` bigint NOT NULL,
  `Fecha_Ingreso` date NOT NULL,
  `Puesto` varchar(100) NOT NULL,
  `Sueldo` decimal(5,2) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Contraseña` varchar(20) NOT NULL,
  PRIMARY KEY (`ID_Empleado`),
  UNIQUE KEY `RFC_UNIQUE` (`RFC`),
  UNIQUE KEY `Nomina_UNIQUE` (`Nomina`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES (1,'Lopez','Payan','Kevin Ricardo','e3d2f424e2r',1542144815151,'2025-02-24','Ing. en sistemas',550.00,'KRLP','2001'),(2,'Flores','Guevara','Angel Gabriel','qfeqwbgfqd',5772553554241,'2025-02-24','sistemas',500.00,'POU','1234');
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `ActualizarFechaIngresoEmpleado` BEFORE INSERT ON `empleados` FOR EACH ROW BEGIN
    SET NEW.Fecha_Ingreso = CURRENT_DATE();
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `Bajarsueldo` BEFORE UPDATE ON `empleados` FOR EACH ROW BEGIN
    IF NEW.Sueldo < OLD.Sueldo THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'En México, reducir el sueldo de un empleado no es una práctica permitida según la Ley Federal del Trabajo.';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `gruas`
--

DROP TABLE IF EXISTS `gruas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gruas` (
  `ID` int NOT NULL,
  `Placa` varchar(7) NOT NULL,
  `Marca` varchar(100) NOT NULL,
  `Modelo` varchar(100) NOT NULL,
  `Tipo` enum('Plataforma','Arrastre','Remolque') NOT NULL,
  `Estado` enum('Activa','Mantenimiento','Inactiva') NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID_UNIQUE` (`ID`),
  UNIQUE KEY `Placa_UNIQUE` (`Placa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gruas`
--

LOCK TABLES `gruas` WRITE;
/*!40000 ALTER TABLE `gruas` DISABLE KEYS */;
/*!40000 ALTER TABLE `gruas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_servicio`
--

DROP TABLE IF EXISTS `historial_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_servicio` (
  `ID_Servicio` int NOT NULL AUTO_INCREMENT,
  `ID_Grua` int NOT NULL,
  `ID_Empleado` int NOT NULL,
  `Estado` enum('En espera','En proceso','Completado','Cancelado') NOT NULL,
  `Placa_Veiculo` varchar(7) NOT NULL,
  `Marca` varchar(50) NOT NULL,
  `Modelo` varchar(50) DEFAULT NULL,
  `Color` varchar(50) DEFAULT NULL,
  `Nombre_Completo` varchar(200) NOT NULL,
  `Telefono` bigint NOT NULL,
  `Tipo_Vehiculo` enum('Automovil','Camioneta','Motocicleta','Camion') NOT NULL,
  `Tipo_Servicio` enum('Remolque','Cambio de batería','Suministro de gasolina','Cambio de llanta','Servicio de arranque') NOT NULL,
  `Descripcion_Problema` varchar(400) NOT NULL,
  `Direccion_Inicio` varchar(200) NOT NULL,
  `Direccion_Fin` varchar(200) NOT NULL,
  `Costo_Servicio` int NOT NULL,
  `Fecha_Hora_Inicio` datetime NOT NULL,
  `Fecha_Hora_Fin` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_Servicio`),
  KEY `ID_Grua_idx` (`ID_Grua`),
  KEY `ID_Empleado_idx` (`ID_Empleado`),
  CONSTRAINT `ID_Empleado` FOREIGN KEY (`ID_Empleado`) REFERENCES `empleados` (`ID_Empleado`),
  CONSTRAINT `ID_Grua` FOREIGN KEY (`ID_Grua`) REFERENCES `gruas` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_servicio`
--

LOCK TABLES `historial_servicio` WRITE;
/*!40000 ALTER TABLE `historial_servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `historial_servicio` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `ActualizarFechaHoraInicio` BEFORE INSERT ON `historial_servicio` FOR EACH ROW BEGIN
  SET NEW.Fecha_Hora_Inicio = NOW();
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `ActualizarFechaHoraFin` BEFORE UPDATE ON `historial_servicio` FOR EACH ROW BEGIN
  IF NEW.Estado = 'Completado' THEN
    SET NEW.Fecha_Hora_Fin = NOW();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `reparacion-servicio`
--

DROP TABLE IF EXISTS `reparacion-servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reparacion-servicio` (
  `ID_Gasto` int NOT NULL AUTO_INCREMENT,
  `ID_Grua` int NOT NULL,
  `Tipo` enum('Reparacion','Mantenimiento','Gasolina') NOT NULL,
  `Descripcion` varchar(400) NOT NULL,
  `Fecha` date NOT NULL,
  `Hora` time NOT NULL,
  `Costo` int NOT NULL,
  PRIMARY KEY (`ID_Gasto`),
  KEY `ID_GruaGasto_idx` (`ID_Grua`),
  CONSTRAINT `ID_GruaGasto` FOREIGN KEY (`ID_Grua`) REFERENCES `gruas` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reparacion-servicio`
--

LOCK TABLES `reparacion-servicio` WRITE;
/*!40000 ALTER TABLE `reparacion-servicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `reparacion-servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'dback'
--

--
-- Dumping routines for database 'dback'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-26 19:41:38
