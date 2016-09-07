/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50542
 Source Host           : localhost
 Source Database       : webtest

 Target Server Type    : MySQL
 Target Server Version : 50542
 File Encoding         : utf-8

 Date: 06/24/2016 11:35:56 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `test_admin`
-- ----------------------------
DROP TABLE IF EXISTS `test_admin`;
CREATE TABLE `test_admin` (
  `aid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '姓名',
  `pwd` char(36) CHARACTER SET utf8 DEFAULT NULL COMMENT '密码',
  `auth` char(4) DEFAULT NULL COMMENT '权限',
  PRIMARY KEY (`aid`),
  KEY `aid` (`aid`),
  KEY `auth_id` (`auth`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COMMENT='管理员';

-- ----------------------------
--  Records of `test_admin`
-- ----------------------------
BEGIN;
INSERT INTO `test_admin` VALUES ('1', '初审专家一', 'cgbcfyz1', '1'), ('2', '初审专家二', 'cgbcfyz2', '1'), ('3', '初审专家三', 'cgbcfyz3', '1'), ('4', '初审专家四', 'cgbcfyz4', '1'), ('5', '初审专家五', 'cgbcfyz5', '1'), ('6', '初审专家六', 'cgbcfyz6', '1'), ('7', '初审专家七', 'cgbcfyz7', '1'), ('8', '初审专家八', 'cgbcfyz8', '1'), ('9', '初审专家九', 'cgbcfyz9', '1'), ('10', '初审专家十', 'cgbcfyz10', '1'), ('11', '终审专家', 'cgbcfyz11', '2'), ('12', '超级管理员', 'cgbcfyz12', '0');
COMMIT;

-- ----------------------------
--  Table structure for `test_auth`
-- ----------------------------
DROP TABLE IF EXISTS `test_auth`;
CREATE TABLE `test_auth` (
  `id` bigint(11) unsigned NOT NULL COMMENT '权限id',
  `type` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '权限描述',
  KEY `id` (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='管理员权限';

-- ----------------------------
--  Records of `test_auth`
-- ----------------------------
BEGIN;
INSERT INTO `test_auth` VALUES ('0', '超级管理员'), ('1', '初审管理员'), ('2', '终审管理员');
COMMIT;

-- ----------------------------
--  Table structure for `test_giftcount`
-- ----------------------------
DROP TABLE IF EXISTS `test_giftcount`;
CREATE TABLE `test_giftcount` (
  `gid` bigint(20) unsigned NOT NULL COMMENT '礼品号',
  `count` bigint(20) unsigned DEFAULT NULL COMMENT '礼品计数',
  `total` bigint(20) DEFAULT NULL COMMENT '礼品总量',
  `type` varchar(50) DEFAULT NULL COMMENT '礼品描述',
  KEY `uid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='礼品数量统计';

-- ----------------------------
--  Records of `test_giftcount`
-- ----------------------------
BEGIN;
INSERT INTO `test_giftcount` VALUES ('0', '530', '810', '2元现金红包'), ('1', '9', '20', '长隆家庭乐票'), ('2', '134', '200', '星巴克电子咖啡券'), ('3', '110', '300', '10元话费'), ('4', '0', '11', '院线通电影票'), ('5', '0', '0', '不中奖');
COMMIT;

-- ----------------------------
--  Table structure for `test_issue`
-- ----------------------------
DROP TABLE IF EXISTS `test_issue`;
CREATE TABLE `test_issue` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `count` bigint(20) DEFAULT NULL COMMENT '问卷计数',
  `total` bigint(20) DEFAULT NULL COMMENT '问卷总量',
  `time` decimal(20,0) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `total` (`total`),
  KEY `time` (`time`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='每日问卷计数';

-- ----------------------------
--  Records of `test_issue`
-- ----------------------------
BEGIN;
INSERT INTO `test_issue` VALUES ('1', '0', '100', '1462531387'), ('2', '0', '200', '1462600045'), ('3', '0', '300', '1462709281'), ('4', '0', '400', '1462761005'), ('5', '0', '0', '1462853185'), ('6', '0', '0', '1462902525');
COMMIT;

-- ----------------------------
--  Table structure for `test_u2a`
-- ----------------------------
DROP TABLE IF EXISTS `test_u2a`;
CREATE TABLE `test_u2a` (
  `uid` bigint(20) unsigned DEFAULT NULL COMMENT '用户id',
  `aid` bigint(20) DEFAULT NULL COMMENT '管理员id',
  KEY `uid` (`uid`),
  KEY `aid` (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='用户id-管理员id';

-- ----------------------------
--  Records of `test_u2a`
-- ----------------------------
BEGIN;
INSERT INTO `test_u2a` VALUES ('1', '1'), ('2', '2'), ('3', '3'), ('4', '4'), ('5', '5'), ('6', '6'), ('7', '7'), ('8', '8'), ('9', '9'), ('10', '10'), ('11', '1'), ('12', '2'), ('13', '3'), ('14', '4'), ('15', '5'), ('16', '6'), ('17', '7'), ('18', '8'), ('19', '9'), ('20', '10'), ('21', '1'), ('22', '2'), ('23', '3'), ('24', '4'), ('25', '5'), ('26', '6');
COMMIT;

-- ----------------------------
--  Table structure for `test_users`
-- ----------------------------
DROP TABLE IF EXISTS `test_users`;
CREATE TABLE `test_users` (
  `uid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscribe` char(4) DEFAULT NULL COMMENT '0:未关注;1:已关注',
  `openid` char(48) DEFAULT NULL COMMENT '微信用户id',
  `access_token` varchar(512) DEFAULT NULL COMMENT '微信网页授权秘匙',
  `nickname` varchar(512) DEFAULT NULL COMMENT '微信昵称',
  `headimgurl` varchar(512) DEFAULT NULL COMMENT '头像图片',
  `sex` char(2) DEFAULT NULL COMMENT '0:男;1:女;',
  `language` char(8) DEFAULT NULL COMMENT '语言',
  `city` char(12) DEFAULT NULL COMMENT '市',
  `province` char(12) DEFAULT NULL COMMENT '省',
  `country` char(12) DEFAULT NULL COMMENT '国家',
  `time` decimal(20,0) DEFAULT NULL COMMENT '注册时间',
  `ip` char(15) DEFAULT NULL COMMENT '访问地址',
  PRIMARY KEY (`uid`),
  KEY `openid` (`openid`),
  KEY `city` (`city`),
  KEY `province` (`province`),
  KEY `country` (`country`),
  KEY `subscribe` (`subscribe`),
  KEY `sex` (`sex`),
  KEY `time` (`time`),
  KEY `ip` (`ip`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='用户基本信息';

-- ----------------------------
--  Records of `test_users`
-- ----------------------------
BEGIN;
INSERT INTO `test_users` VALUES ('1', '1', 'oitpEuCgqmMo-Ob8kpxNum4FGC-o', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP15EiwLefJ2DgGgBiDdm819xQrN2gZELRvjhC2pTw6FqlGRm14qnBQVMvk8oYqEMbGjz-bYkOoNYErxUy2Y83ytE2NpBf3rSPDeNZgb5sZk9w', 'somatop<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji1f4ad\"></span></span>', 'http://wx.qlogo.cn/mmopen/R3DXEpTMwblcRLyWichVCoZQrLicAZBDcgFVBqZuBHncwLoibdJyvuY7tbPOkTOic5hwgiaJdjnc5cZBBPcNEtnF5M2gB1ZI65RfI/0', '0', 'zh_CN', '', '', '中国', '1463572691', '121.32.13.98'), ('2', '1', 'oitpEuPjAkDe_xAwaqxbyEahxt5s', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP2zxns2FGbtW31TpYPD08JtTthqBiEM-eHBWZ6YexzsZI-YnAhkfjJ66D-dA5ciX-BEbgjtZsXZa54Bzq95LI0KAgEwK91VgI9rSEs0shHjyQ', '二牙', 'http://wx.qlogo.cn/mmopen/ogelBGKcjvZn7Gic1gHkkYbsQ5UsWYcWm0Rf0ibKnlCSUJ87HsiaaqichuZtgotuSUEV0Ny2icp11DfYGjHcdcDMJmriaiav1ajdak0/0', '1', 'zh_CN', '广州', '广东', '中国', '1463624699', '112.96.164.115'), ('3', '', 'oitpEuDkWvtW-mM-wXbOcK9yZoZE', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP3hE5BNHHwh6oYGYkdJ5WDeksklT31VsaEfGDEN7YIlVOD8QGy4WKOHRl75CRxV364it-5_0lwltosl7CruCsSdYfM_wyXgbjD3oNjrWr2tsA', '<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji2728\"></span></span>GaRy.ho<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji2728\"></span></span>', 'http://wx.qlogo.cn/mmopen/nvvEia4IRdseOWicVEc8pEakUyNqTmXETVibfoxzzZe5xYHNpBJAiaUg7os9IFPz5Tzib63G6UyyLYKZ5s2nIK5MBA94fQvKZAupj/0', '1', 'zh_CN', '广州', '广东', '中国', '1463626221', '121.32.127.36'), ('4', '', 'oitpEuPwkX7xkvNwShSrcAXXo6iI', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP1wmMdKZoqu7aXtotemyXHUpRp8o2nPnaiPjgJMvC5SqSv38JoOA7PHzoAK9TdgbSlwJuxgQbIeUhHAkCUVRHqb1U7SRR4V8H_XjAXgMIto7g', '我噶细号', 'http://wx.qlogo.cn/mmopen/95rWEbN4jDejTckVpaib0CCYKzUSGNLjAZlkvib0aBW7B6ISx30YCMibPO1t7flVxYBic5QUcbXqKbJTpdDFe1OPHH2dv5NDentP/0', '2', 'zh_CN', '', '', '中国', '1463626229', '121.32.127.36'), ('5', '1', 'oitpEuIxxgI5oq_HczOWHH92qUQ0', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP3YZExSgn_AV6ea1-EBbZs8u8k9JuOFEi5_OXNmVXABiQMiSbf_aEw8JOYSjq_tDHVB-HHNzgI5w1UOFT9Y6HteD7nNVFNB7S69mIFx7M0bAw', '偉權', 'http://wx.qlogo.cn/mmopen/ogelBGKcjvaBvEIkpmvCOBkvHU6LVlt37O1gqwUZvhicIibnSqibeqgc8zia22RfF8ooic89ibTtONuX7BTVcAglh8dOSKvO202dWia/0', '1', 'zh_CN', '', '', '中国', '1463626229', '117.136.79.52'), ('6', '1', 'oitpEuFgyDTLRYe_TSV0-n7kh4qE', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP1e3IKTnWpKFobspi-JwkL7w0Krx2Y27X42erFoiGirZqzk0xljSfDpVSjF9d0wNYQnRR8vazpS7jWWGoicftovz9PZqUFDNfvFWumgqv4epA', '阿泰', 'http://wx.qlogo.cn/mmopen/6YQGLCTXoX9rdSVFMRUymia16kfmjW7jNgvwDBhVtqsrRKeRXCVmIjJ7xtERLneQufCTyMRyvd7e5tPlTXHdY77MwR1ZoRfria/0', '1', 'zh_CN', '广州', '广东', '中国', '1463708951', '183.41.33.14'), ('7', '', 'oitpEuBjShpcTvFOaqypFLtnAVTo', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP2bjpvlxy8Ocem3PqueshZhmNyNivG_RCjlFfTAXAVELHMQpUSsomWHi1a0Iw6GUTn-Q9RawEQKk3Ek-V6TZxD89YJg7S-9yUvD_mJ0Rl52Rw', '杨八狼', 'http://wx.qlogo.cn/mmopen/6YQGLCTXoXiba9EsZ4AYG6sgKfnv4WSgnTdicJDibNnfFiaNqpziaHFiaqZGiaL1RyiaxJDHlM2WFYtV7e6CgKCHc4aTtrESXY4Oibktf/0', '1', 'zh_CN', '广州', '广东', '中国', '1463709011', '113.68.192.58'), ('8', '1', 'oitpEuMAJqkJbChfn3xb5ykv7RFM', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP0vC7dwo5Y24pfniptYXt1n3ZapWHVzpJnVmRQkZOCyqQ9x9CA-kWb3aucqzr0C4m4CM5W-tLd9MxAQX8EaOquFI0XpyS5HRrVjmRRlfcxNMA', 'Rain', 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM6u8gZmMaSZfJxqrXvGzpyskohWia4IGK7GsJnagib0Xu3PsKgFowvK27vGwIKyPaibibthhOkUG1jBKVwH6s77F6ib5l6NMtb6uaGY/0', '2', 'zh_TW', '广州', '广东', '中国', '1463725614', '119.129.70.37'), ('9', '1', 'oitpEuAtlS1pEFNgmscmxcdjTYFU', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP1hJQEl-M7hj3bxC48Hd7A_uGClAmK23RmE-I06U06DqRDGyqYKZGxo0dButp9flBmbF_wr58IjCOBsfRXmJJuU8ZnJVQTZ0ZEZtzCjTr61TQ', 'Devil', 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM5aIOdibnibQNibcyricxWrC3dCaT1ezoWFgjLXqDgqvfTqoznKazicpF8SQicnMdTnnMaNfn2HWxQTMPoL0iaVE0PIfibqwFuMaNibOOA8/0', '1', 'zh_CN', '广州', '广东', '中国', '1463725632', '117.136.79.54'), ('10', '0', 'oitpEuA6weV8vuQm8FIeICKuiI9U', 'OezXcEiiBSKSxW0eoylIeIjNtZTq-cExtwJV49C_mP3IurG2s2Jxr2qwRBSL6fN9kcNt1r2LWUV6Sk6UhbdQ0GJmp-bSyy4WNbrnvMUM0wqkCkFfF1dCo_HayhCuOjIrGeBuNYPC-xkUy1n4WTZaBw', 'LEO wu 伍宏宇', 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLCIWwTH0VjJOakpmRplcGqek6GDbpOVQeG8dz03BBwdx14k2I9eHcvZibGdbt8pLzI4NISysrFEE6A/0', '1', 'zh_CN', '广州', '广东', '中国', '1463725637', '223.104.1.96');
COMMIT;

-- ----------------------------
--  Table structure for `test_users_get`
-- ----------------------------
DROP TABLE IF EXISTS `test_users_get`;
CREATE TABLE `test_users_get` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户id',
  `nick` varchar(512) DEFAULT NULL,
  `sex` char(2) DEFAULT '0' COMMENT '男-女',
  `gift` char(2) DEFAULT '0' COMMENT '用户标记',
  `time` decimal(20,0) unsigned DEFAULT NULL,
  `ip` char(15) DEFAULT NULL COMMENT '用户签名',
  KEY `uid` (`uid`) USING BTREE,
  KEY `sex` (`sex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户获取状态';

-- ----------------------------
--  Records of `test_users_get`
-- ----------------------------
BEGIN;
INSERT INTO `test_users_get` VALUES ('1', 'somatop<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji1f4ad\"></span></span>', '0', '0', '1463949058', '59.41.64.227'), ('2', '二牙', '1', '0', '1463966794', '112.96.173.111'), ('3', '<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji2728\"></span></span>GaRy.ho<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji2728\"></span></span>', '1', '2', '1463999330', '121.32.126.92'), ('4', '我噶细号', '2', '2', '1463998487', '113.68.192.192'), ('5', '偉權', '1', '3', '1463995307', '121.32.126.92'), ('6', '阿泰', '1', '3', '1464016693', '112.96.164.80'), ('7', '杨八狼', '1', '4', '1463997609', '117.136.79.53'), ('8', 'Rain', '1', '4', '1463997496', '183.43.239.215'), ('9', 'Devil', '1', '5', '1463997635', '58.63.53.191'), ('10', 'LEO wu 伍宏宇', '1', '5', '1463963446', '59.41.64.227');
COMMIT;

-- ----------------------------
--  Table structure for `test_users_help`
-- ----------------------------
DROP TABLE IF EXISTS `test_users_help`;
CREATE TABLE `test_users_help` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '本用户id',
  `u_nick` varchar(512) DEFAULT NULL COMMENT '本用户微信昵称',
  `fid` bigint(20) unsigned DEFAULT NULL COMMENT '朋友用户id',
  `f_nick` varchar(512) DEFAULT '' COMMENT '朋友用户昵称',
  `f_bool` enum('true','false') DEFAULT NULL COMMENT '布尔值',
  KEY `uid` (`uid`) USING BTREE,
  KEY `fid` (`fid`) USING BTREE,
  KEY `f_bool` (`f_bool`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户互助关系';

-- ----------------------------
--  Table structure for `test_users_info`
-- ----------------------------
DROP TABLE IF EXISTS `test_users_info`;
CREATE TABLE `test_users_info` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户id',
  `photo` varchar(512) DEFAULT NULL COMMENT '照片',
  `name` varchar(30) DEFAULT NULL COMMENT '姓名',
  `sex` char(2) DEFAULT '0' COMMENT '性别',
  `age` char(4) DEFAULT '0' COMMENT '年龄',
  `mobile` char(20) DEFAULT NULL COMMENT '联系手机号',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `idcard` char(20) DEFAULT NULL COMMENT '身份证号',
  `account` char(20) DEFAULT NULL COMMENT '帐号',
  `type` char(20) DEFAULT NULL COMMENT '客户类型',
  `device` varchar(512) DEFAULT NULL COMMENT '用户设备',
  KEY `uid` (`uid`),
  KEY `age` (`age`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户预留信息';

-- ----------------------------
--  Records of `test_users_info`
-- ----------------------------
BEGIN;
INSERT INTO `test_users_info` VALUES ('1', null, '', null, null, '13560087652', '', '', '', '', null), ('2', null, '', null, null, '15088138567', '', '', '', '', null), ('3', null, '', '1', null, '15017536644', '', '', '', '', null), ('4', null, '', '1', null, '13600024601', '', '', '', '', null), ('5', null, '', '1', null, '18588891945', '', '', '', '', null), ('6', null, '', '1', null, '13160689499', '', '', '', '', null), ('7', null, '', '2', null, '13726188714', '', '', '', '', null), ('8', null, '', '1', null, '18565090128', '', '', '', '', null), ('9', null, '', '1', null, '18688901377', '', '', '', '', null), ('10', null, '', '2', null, '13432296303', '', '', '', '', null);
COMMIT;

-- ----------------------------
--  Table structure for `test_users_post`
-- ----------------------------
DROP TABLE IF EXISTS `test_users_post`;
CREATE TABLE `test_users_post` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户id',
  `issue` varchar(512) DEFAULT NULL COMMENT '问卷选项序列化',
  `type` char(255) DEFAULT NULL COMMENT '标记',
  `health` char(255) DEFAULT NULL COMMENT '健康值',
  `height` varchar(255) DEFAULT NULL COMMENT '过高选项和建议',
  `low` varchar(255) DEFAULT NULL COMMENT '过低选项和建议',
  `answ_type` char(4) DEFAULT NULL COMMENT '回复类型 [height&low:1]or[content:2]',
  `content` varchar(512) DEFAULT NULL COMMENT '手动输入建议',
  `check` char(4) DEFAULT NULL COMMENT '审核状态[never:0][first:1]or[end:2]',
  `time` decimal(20,0) unsigned DEFAULT NULL,
  `ip` char(15) DEFAULT NULL,
  KEY `uid` (`uid`) USING BTREE,
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户提交状态';

-- ----------------------------
--  Records of `test_users_post`
-- ----------------------------
BEGIN;
INSERT INTO `test_users_post` VALUES ('1', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"13\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"21\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"21\";}i:9;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"0\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:12:\"中等健康\";s:3:\"val\";s:2:\"51\";}', 'a', 'b', null, '建议文本', '0', '1462464000', '127.0.0.1'), ('2', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"13\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"21\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"21\";}i:9;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"0\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:12:\"中等健康\";s:3:\"val\";s:2:\"51\";}', 'a', 'b', null, '建议文本', '1', '1462464000', '127.0.0.1'), ('3', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:9;a:2:{s:3:\"sel\";s:1:\"d\";s:3:\"val\";s:2:\"19\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:15:\"财富亚健康\";s:3:\"val\";s:2:\"70\";}', 'a', 'b', null, '建议文本', '2', '1462464000', '127.0.0.1'), ('4', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:9;a:2:{s:3:\"sel\";s:1:\"d\";s:3:\"val\";s:2:\"19\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:15:\"财富亚健康\";s:3:\"val\";s:2:\"70\";}', 'b', 'a', null, '建议文本', '0', '1462550400', '127.0.0.1'), ('5', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:9;a:2:{s:3:\"sel\";s:1:\"d\";s:3:\"val\";s:2:\"19\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:15:\"财富亚健康\";s:3:\"val\";s:2:\"70\";}', 'b', 'a', null, '建议文本', '1', '1462550400', '127.0.0.1'), ('6', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:9;a:2:{s:3:\"sel\";s:1:\"d\";s:3:\"val\";s:2:\"19\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:15:\"财富亚健康\";s:3:\"val\";s:2:\"70\";}', 'b', 'a', null, '建议文本', '2', '1462550400', '127.0.0.1'), ('7', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:9;a:2:{s:3:\"sel\";s:1:\"d\";s:3:\"val\";s:2:\"19\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:15:\"财富亚健康\";s:3:\"val\";s:2:\"70\";}', 'c', 'c', null, '建议文本', '0', '1462550400', '127.0.0.1'), ('8', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"13\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"21\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"21\";}i:9;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"0\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:12:\"中等健康\";s:3:\"val\";s:2:\"51\";}', 'c', 'c', null, '建议文本', '1', '1462550400', '127.0.0.1'), ('9', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"13\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"21\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"21\";}i:9;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"0\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:12:\"中等健康\";s:3:\"val\";s:2:\"51\";}', 'c', 'c', null, '建议文本', '2', '1462636800', '127.0.0.1'), ('10', 'a:10:{i:0;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"20\";}i:1;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:2;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:3;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"10\";}i:4;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"3\";}i:5;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:1:\"1\";}i:6;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:7;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:8;a:2:{s:3:\"sel\";s:1:\"a\";s:3:\"val\";s:2:\"15\";}i:9;a:2:{s:3:\"sel\";s:1:\"d\";s:3:\"val\";s:2:\"19\";}}', 'a:2:{s:4:\"type\";s:10:\"B类客户\";s:3:\"val\";s:2:\"54\";}', 'a:2:{s:4:\"type\";s:15:\"财富亚健康\";s:3:\"val\";s:2:\"70\";}', 'd', 'a', null, '建议文本', '0', '1462636800', '127.0.0.1');
COMMIT;

-- ----------------------------
--  Table structure for `test_users_rec`
-- ----------------------------
DROP TABLE IF EXISTS `test_users_rec`;
CREATE TABLE `test_users_rec` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户id',
  `nick` varchar(512) DEFAULT NULL COMMENT '昵称',
  `sex` char(2) DEFAULT '0' COMMENT '男-女',
  `reced` bigint(20) DEFAULT NULL COMMENT '录音次数',
  `recid` varchar(255) DEFAULT NULL COMMENT '微信录音id',
  `time` decimal(20,0) unsigned DEFAULT NULL,
  `ip` char(15) DEFAULT NULL COMMENT '用户签名',
  KEY `uid` (`uid`) USING BTREE,
  KEY `sex` (`sex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户录音记录';

-- ----------------------------
--  Records of `test_users_rec`
-- ----------------------------
BEGIN;
INSERT INTO `test_users_rec` VALUES ('1', 'somatop<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji1f4ad\"></span></span>', '0', '10', 'RntsU4pQ-G-Q6FYveS_WLBZPS-D6hAT9oy3XsAGSxGrHuFpiznNRo087jm-e1KDm', '1463984939', '121.32.126.92'), ('2', '二牙', '1', '46', '0CDZfZh53fjZ-I1JEr9K0f8MgESxV1m4NQ2G_Ap1vh2PSuQT0MltR2rqGt2cqH-4', '1464095067', '112.96.109.79'), ('3', '<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji2728\"></span></span>GaRy.ho<span class=\"emoji-outer emoji-sizer\"><span class=\"emoji-inner emoji2728\"></span></span>', '1', '3', 'h2fsfXLWM-gnL9WAF3hUNvq518XbPrKeGpzDxpR3mexaL_tfQvaL315bzPRYTs2V', '1463999314', '121.32.126.92'), ('4', '我噶细号', '2', '1', 'ojlC24MSQVufNjB_gN_msSpWxvY2llsL7X98fRDUfl5oye46qPT6lFMNq-3juCzR', '1463998463', '113.68.192.192'), ('5', '偉權', '1', '3', 'WBlJztyCCniEBijceOpPHkFACUbMN87duZlr45ry-5VhJM-Aj2L3GyGXh3_RLKQF', '1464143171', '117.136.41.40'), ('6', '阿泰', '1', '2', '7jxjHuCDOt_WoOrwOOx99HPcsAUNor-Xmy2ACHny7V8ygZtbq1bSq8yCzyuFK6cH', '1464003672', '183.43.176.159'), ('7', '杨八狼', '1', '0', 'iZdBrDsEOgCI-6-nXmpKRQcdB5yZLnLlbB_JETDA2shu23IN6MCQkFdmyW1qrX77', '1463709062', '113.68.192.58'), ('8', 'Rain', '1', '9', 'RxJ7SJnQC9vjfjnj5ZkFy5X4aopbV0nLLYPz_O4OKdxkO6YvYkfuq2XabvEk7cYR', '1464016743', '112.96.164.80'), ('9', 'Devil', '2', '4', 'KEwbJl7n5Z8eQOXwTmm5aIGQAbBDANlcf-cLo6kqdPcCnESfBxxSGOY-go_D_o9C', '1464007844', '58.63.76.61'), ('10', 'LEO wu 伍宏宇', '1', '6', 'fMhviW7gcguQ4IPKBrn4G5LDSiAurlxeZOxalvWe96yQS8RKzKVP0utQXNhyH7bb', '1464093550', '61.140.102.81');
COMMIT;

-- ----------------------------
--  Table structure for `test_vcode`
-- ----------------------------
DROP TABLE IF EXISTS `test_vcode`;
CREATE TABLE `test_vcode` (
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户id',
  `vcode` char(6) DEFAULT NULL COMMENT '验证码',
  `createtime` decimal(20,0) unsigned DEFAULT NULL COMMENT '用户初次登记时间',
  `sendtime` decimal(20,0) unsigned DEFAULT NULL COMMENT '验证码创建时间',
  `boolean` enum('true','flase') DEFAULT NULL COMMENT '是否验证通过',
  KEY `uid` (`uid`) USING BTREE,
  KEY `boolean` (`boolean`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='验证码测试';

SET FOREIGN_KEY_CHECKS = 1;
