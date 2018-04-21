# Host: localhost  (Version: 5.5.53)
# Date: 2018-04-21 22:02:56
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "lv_admin"
#

DROP TABLE IF EXISTS `lv_admin`;
CREATE TABLE `lv_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) DEFAULT '',
  `password` text,
  `status` varchar(10) DEFAULT '0' COMMENT '当前账号状态0正常，1锁死，2拉黑',
  `error_count` varchar(50) DEFAULT '0' COMMENT '输入错误密码的次数',
  `more` text,
  `super` int(11) DEFAULT '0' COMMENT '是否超级管理员 0否1是',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='管理员表';

#
# Data for table "lv_admin"
#

INSERT INTO `lv_admin` VALUES (1,'849688611','debfc6c7fd10b9b2e1d1c2924c6beb10','0','0',NULL,0,1523167940,1524205589,1),(2,'nicexixi','debfc6c7fd10b9b2e1d1c2924c6beb10','0','0',NULL,0,1523169038,1523169090,1),(3,'wangluyu','debfc6c7fd10b9b2e1d1c2924c6beb10','0','0',NULL,0,1523169117,1523719548,1);

#
# Structure for table "lv_auth"
#

DROP TABLE IF EXISTS `lv_auth`;
CREATE TABLE `lv_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `uris` text,
  `parent_id` int(11) DEFAULT '0' COMMENT '父级菜单ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='权限表';

#
# Data for table "lv_auth"
#

INSERT INTO `lv_auth` VALUES (1,'管理员设置','/Base/BaseConfig',0),(2,'平台设置','/Base/Config',0),(3,'平台','/Base/Config',1),(4,'平台','/Base/Config',3);

#
# Structure for table "lv_category"
#

DROP TABLE IF EXISTS `lv_category`;
CREATE TABLE `lv_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '栏目名称',
  `excerpt` text COMMENT '栏目描述',
  `content` text COMMENT '栏目富文本内容',
  `thumbnail` text,
  `more` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目表';

#
# Data for table "lv_category"
#


#
# Structure for table "lv_category_post"
#

DROP TABLE IF EXISTS `lv_category_post`;
CREATE TABLE `lv_category_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目内容关系表';

#
# Data for table "lv_category_post"
#


#
# Structure for table "lv_comment"
#

DROP TABLE IF EXISTS `lv_comment`;
CREATE TABLE `lv_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

#
# Data for table "lv_comment"
#


#
# Structure for table "lv_config"
#

DROP TABLE IF EXISTS `lv_config`;
CREATE TABLE `lv_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配置表';

#
# Data for table "lv_config"
#


#
# Structure for table "lv_file"
#

DROP TABLE IF EXISTS `lv_file`;
CREATE TABLE `lv_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文件资源表';

#
# Data for table "lv_file"
#


#
# Structure for table "lv_post"
#

DROP TABLE IF EXISTS `lv_post`;
CREATE TABLE `lv_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` varchar(255) DEFAULT '0',
  `post_status` tinyint(3) DEFAULT '1',
  `comment_status` tinyint(3) DEFAULT '1',
  `user_id` bigint(20) DEFAULT NULL,
  `is_top` tinyint(3) DEFAULT NULL,
  `is_recommend` tinyint(3) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `published_time` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `excerpt` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `content` text,
  `more` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='内容表';

#
# Data for table "lv_post"
#


#
# Structure for table "lv_role"
#

DROP TABLE IF EXISTS `lv_role`;
CREATE TABLE `lv_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COMMENT '角色名称',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色表';

#
# Data for table "lv_role"
#

INSERT INTO `lv_role` VALUES (1,'管理员',NULL,NULL);

#
# Structure for table "lv_role_auth"
#

DROP TABLE IF EXISTS `lv_role_auth`;
CREATE TABLE `lv_role_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `auth_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='角色权限关系表';

#
# Data for table "lv_role_auth"
#

INSERT INTO `lv_role_auth` VALUES (4,1,1);

#
# Structure for table "lv_single"
#

DROP TABLE IF EXISTS `lv_single`;
CREATE TABLE `lv_single` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `excerpt` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `content` text,
  `more` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='单页表';

#
# Data for table "lv_single"
#


#
# Structure for table "lv_slide"
#

DROP TABLE IF EXISTS `lv_slide`;
CREATE TABLE `lv_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_url` text,
  `link` text,
  `type` tinyint(3) DEFAULT NULL COMMENT '0外链型1栏目型2内容型3单页型',
  `content_id` bigint(20) DEFAULT '0' COMMENT '幻灯片背后内容的id',
  `more` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='幻灯片表';

#
# Data for table "lv_slide"
#


#
# Structure for table "lv_user"
#

DROP TABLE IF EXISTS `lv_user`;
CREATE TABLE `lv_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='普通用户表';

#
# Data for table "lv_user"
#

