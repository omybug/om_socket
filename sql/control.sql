/*
Navicat MySQL Data Transfer

Source Server         : localtest
Source Server Version : 50623
Source Host           : localhost:3306
Source Database       : control

Target Server Type    : MYSQL
Target Server Version : 50623
File Encoding         : 65001

Date: 2015-11-10 14:29:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ban_chat`
-- ----------------------------
DROP TABLE IF EXISTS `ban_chat`;
CREATE TABLE `ban_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `ts` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ban_chat
-- ----------------------------

-- ----------------------------
-- Table structure for `online_stat`
-- ----------------------------
DROP TABLE IF EXISTS `online_stat`;
CREATE TABLE `online_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`server_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of online_stat
-- ----------------------------
