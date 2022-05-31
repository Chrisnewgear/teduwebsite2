/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50733
Source Host           : localhost:3306
Source Database       : tedu

Target Server Type    : MYSQL
Target Server Version : 50733
File Encoding         : 65001

Date: 2022-05-26 23:54:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for man_suscripcion
-- ----------------------------
DROP TABLE IF EXISTS `man_suscripcion`;
CREATE TABLE `man_suscripcion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `juridica` varchar(255) DEFAULT NULL,
  `usuario` varchar(255) DEFAULT NULL,
  `ruc` varchar(13) DEFAULT NULL,
  `rnatural` varchar(255) DEFAULT NULL,
  `paterno` varchar(255) DEFAULT NULL,
  `materno` varchar(255) DEFAULT NULL,
  `nombreusuario` varchar(255) DEFAULT NULL,
  `tipoidentificacion` varchar(255) DEFAULT NULL,
  `numidentificacion` varchar(255) DEFAULT NULL,
  `nacimiento` date DEFAULT NULL,
  `provincia` varchar(255) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `checkboxMailSi` bit(1) DEFAULT NULL,
  `tipocuenta` varchar(255) DEFAULT NULL,
  `numcuenta` varchar(255) DEFAULT NULL,
  `banco` varchar(255) DEFAULT NULL,
  `p_paterno` varchar(255) DEFAULT NULL,
  `p_materno` varchar(255) DEFAULT NULL,
  `p_nombre` varchar(255) DEFAULT NULL,
  `p_celular` varchar(255) DEFAULT NULL,
  `p_telefono` varchar(255) DEFAULT NULL,
  `p_codigo` varchar(255) DEFAULT NULL,
  `fechasolicitud` date DEFAULT NULL,
  `creadodate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i1` (`ruc`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of man_suscripcion
-- ----------------------------
INSERT INTO `man_suscripcion` VALUES ('1', 'SI', 'USU002093', '0924372808001', 'NO', 'SALINAS ', 'VARAS', 'NELSON XAVIER', 'Cédula', '423423423', '2022-05-04', 'GUAYAS', 'GUAYAQUIL', 'VILALS DEL REY', '0984235572', '0984235572', 'xaviersalinasv18@gmail.com', '', 'Cédula', '23423423', 'PACIFICO', 'SALINAS', 'VARAS', 'XAVIER NELSON', '23423423', '234234', '234234', '2022-05-24', '2022-05-24 00:00:00');
INSERT INTO `man_suscripcion` VALUES ('4', 'SI', 'USU002093', '0924372808002', 'NO', 'SALINAS ', 'VARAS', 'NELSON XAVIER', 'CÃ©dula', '0924372808', '2022-05-02', 'GUAYAS', 'GUAYAQUIL', 'VILALS DEL REY', '0984235572', '0984235572', 'xaviersalinasv181@gmail.com', '', 'CÃ©dula', '2213123123', 'PACIFICO', 'SALINAS', 'VARAS', 'XAVIER NELSON', '123123123123', '123123123', '123123123', '2022-05-02', '2022-05-24 00:00:00');

-- ----------------------------
-- Table structure for man_usuarios
-- ----------------------------
DROP TABLE IF EXISTS `man_usuarios`;
CREATE TABLE `man_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) DEFAULT NULL,
  `cedula` varchar(255) DEFAULT NULL,
  `nombres` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mensaje` varchar(255) DEFAULT NULL,
  `aceptatermino` bit(1) DEFAULT NULL,
  `creadodate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of man_usuarios
-- ----------------------------
INSERT INTO `man_usuarios` VALUES ('1', null, null, 'Nelson Salinas', '0984235572', 'xavier_salinasv18@hotmail.com', 'ljnjn', '', '2022-05-23 00:00:00');
INSERT INTO `man_usuarios` VALUES ('2', null, null, '', '', '', '', '', '2022-05-24 00:00:00');
SET FOREIGN_KEY_CHECKS=1;
