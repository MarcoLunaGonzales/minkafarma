/*
 Navicat Premium Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 100130
 Source Host           : localhost:3306
 Source Schema         : ifinancierov2

 Target Server Type    : MySQL
 Target Server Version : 100130
 File Encoding         : 65001

 Date: 07/07/2023 08:38:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cargos_autoridades
-- ----------------------------
DROP TABLE IF EXISTS `cargos_autoridades`;
CREATE TABLE `cargos_autoridades`  (
  `cod_autoridad` int NOT NULL AUTO_INCREMENT,
  `cod_cargo` int NOT NULL,
  `nombre_autoridad` varchar(500) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `orden` int NULL DEFAULT 0,
  `cod_estadoautoridad` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `modified_at` datetime NULL DEFAULT NULL,
  `modified_by` int NULL DEFAULT NULL,
  `fecha_aprobacion` datetime NULL DEFAULT NULL,
  `cod_configuracion` int NULL DEFAULT NULL,
  PRIMARY KEY (`cod_autoridad`) USING BTREE,
  INDEX `cod_estadoreferencial`(`cod_estadoautoridad`) USING BTREE,
  INDEX `cod_cargo`(`cod_cargo`) USING BTREE,
  CONSTRAINT `cargos_autoridades_ibfk_1` FOREIGN KEY (`cod_estadoautoridad`) REFERENCES `estados_referenciales` (`codigo`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `cargos_autoridades_ibfk_2` FOREIGN KEY (`cod_cargo`) REFERENCES `cargos` (`codigo`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
