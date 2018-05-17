# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.35)
# Database: mycms
# Generation Time: 2018-05-17 16:23:45 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table lv_admin
# ------------------------------------------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

LOCK TABLES `lv_admin` WRITE;
/*!40000 ALTER TABLE `lv_admin` DISABLE KEYS */;

INSERT INTO `lv_admin` (`id`, `account`, `password`, `status`, `error_count`, `more`, `super`, `create_time`, `update_time`, `role`)
VALUES
	(1,'849688611','debfc6c7fd10b9b2e1d1c2924c6beb10','0','0',NULL,0,1523167940,1526572251,1),
	(2,'nicexixi','debfc6c7fd10b9b2e1d1c2924c6beb10','0','0',NULL,0,1523169038,1523169090,1),
	(3,'wangluyu','debfc6c7fd10b9b2e1d1c2924c6beb10','0','0',NULL,0,1523169117,1523719548,1);

/*!40000 ALTER TABLE `lv_admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_auth
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_auth`;

CREATE TABLE `lv_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `uris` text,
  `parent_id` int(11) DEFAULT '0' COMMENT '父级菜单ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

LOCK TABLES `lv_auth` WRITE;
/*!40000 ALTER TABLE `lv_auth` DISABLE KEYS */;

INSERT INTO `lv_auth` (`id`, `name`, `uris`, `parent_id`)
VALUES
	(1,'管理员设置','/Base/BaseConfig',0),
	(2,'平台设置','/Base/Config',0),
	(3,'平台','/Base/Config',1),
	(4,'平台','/Base/Config',3);

/*!40000 ALTER TABLE `lv_auth` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_category`;

CREATE TABLE `lv_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '栏目名称',
  `excerpt` text COMMENT '栏目描述',
  `content` text COMMENT '栏目富文本内容',
  `thumbnail` text,
  `more` text,
  `parent_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目表';

LOCK TABLES `lv_category` WRITE;
/*!40000 ALTER TABLE `lv_category` DISABLE KEYS */;

INSERT INTO `lv_category` (`id`, `name`, `excerpt`, `content`, `thumbnail`, `more`, `parent_id`, `create_time`, `update_time`, `template_id`)
VALUES
	(1,'一级栏目0','一级栏目',NULL,NULL,NULL,0,1524452657,1524453069,NULL),
	(2,'一级栏目1',NULL,NULL,NULL,NULL,0,1524452670,1524452670,NULL),
	(3,'一级栏目2',NULL,NULL,NULL,NULL,0,1524452672,1524452672,NULL),
	(4,'二级栏目',NULL,NULL,NULL,NULL,1,1524452688,1524452688,NULL),
	(5,'二级栏目1',NULL,NULL,NULL,NULL,2,1524452692,1524452692,NULL),
	(6,'三级栏目',NULL,NULL,NULL,NULL,4,1524452708,1524452708,NULL),
	(8,'四级栏目0',NULL,NULL,NULL,NULL,6,1524454350,1524454350,NULL);

/*!40000 ALTER TABLE `lv_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_category_post
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_category_post`;

CREATE TABLE `lv_category_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目内容关系表';

LOCK TABLES `lv_category_post` WRITE;
/*!40000 ALTER TABLE `lv_category_post` DISABLE KEYS */;

INSERT INTO `lv_category_post` (`id`, `category_id`, `post_id`)
VALUES
	(1,1,2),
	(2,4,2),
	(5,1,5),
	(6,2,5);

/*!40000 ALTER TABLE `lv_category_post` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_comment`;

CREATE TABLE `lv_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1显示，0隐藏',
  `parent_id` int(11) DEFAULT '0' COMMENT '顶级评论id',
  `floor` int(11) DEFAULT '0',
  `reply_id` int(11) DEFAULT '0' COMMENT '回复给了哪条评论',
  `reply_user_id` int(11) DEFAULT '0' COMMENT '回复给的这条评论的作者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

LOCK TABLES `lv_comment` WRITE;
/*!40000 ALTER TABLE `lv_comment` DISABLE KEYS */;

INSERT INTO `lv_comment` (`id`, `content`, `create_time`, `update_time`, `user_id`, `post_id`, `status`, `parent_id`, `floor`, `reply_id`, `reply_user_id`)
VALUES
	(1,'test',1525942072,1525942072,3,5,1,0,1,0,0),
	(2,'test1',1525942084,1525942084,3,5,1,0,2,0,0),
	(5,'testchild',1525942952,1525942952,3,5,1,1,-1,1,3);

/*!40000 ALTER TABLE `lv_comment` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_config`;

CREATE TABLE `lv_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配置表';



# Dump of table lv_file
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_file`;

CREATE TABLE `lv_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `md5` text,
  `sha1` text,
  `url` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `ext` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文件资源表';

LOCK TABLES `lv_file` WRITE;
/*!40000 ALTER TABLE `lv_file` DISABLE KEYS */;

INSERT INTO `lv_file` (`id`, `name`, `md5`, `sha1`, `url`, `create_time`, `update_time`, `ext`)
VALUES
	(4,'目标设置-思维导图.png','53e5d454e39539319a18ada82154d4df','5ac754cb78f898584d26c6d2a719b0db60e9a6b3','/upload/53/e5d454e39539319a18ada82154d4df.png',1526194658,1526194658,'png');

/*!40000 ALTER TABLE `lv_file` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_link
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_link`;

CREATE TABLE `lv_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` text,
  `more` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table lv_nav
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_nav`;

CREATE TABLE `lv_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `excerpt` text,
  `more` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `lv_nav` WRITE;
/*!40000 ALTER TABLE `lv_nav` DISABLE KEYS */;

INSERT INTO `lv_nav` (`id`, `name`, `excerpt`, `more`)
VALUES
	(1,'主导航',NULL,NULL),
	(3,'底部导航','底部导航',NULL);

/*!40000 ALTER TABLE `lv_nav` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_nav_item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_nav_item`;

CREATE TABLE `lv_nav_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nav_id` int(11) DEFAULT '0' COMMENT '属于的导航组的id',
  `parent_id` int(11) DEFAULT '0' COMMENT '父级导航项的id',
  `name` text COMMENT '导航项的名称',
  `type` int(11) DEFAULT NULL COMMENT '导航项类别，1栏目2单页3内容',
  `item_id` int(11) DEFAULT NULL COMMENT '导航项实际id即type所对应类型的id',
  `more` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `lv_nav_item` WRITE;
/*!40000 ALTER TABLE `lv_nav_item` DISABLE KEYS */;

INSERT INTO `lv_nav_item` (`id`, `nav_id`, `parent_id`, `name`, `type`, `item_id`, `more`)
VALUES
	(1,1,0,'item1',1,1,NULL),
	(2,1,0,NULL,1,2,NULL),
	(3,1,1,NULL,3,1,NULL);

/*!40000 ALTER TABLE `lv_nav_item` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_post
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_post`;

CREATE TABLE `lv_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_status` tinyint(3) DEFAULT '1' COMMENT '文章状态1正常0不通过',
  `comment_status` tinyint(3) DEFAULT '1' COMMENT '评论状态1允许评论0禁止评论',
  `user_id` bigint(20) DEFAULT NULL COMMENT '发布用户的id',
  `is_top` tinyint(3) DEFAULT '0' COMMENT '是否置顶1置顶0正常',
  `is_recommend` tinyint(3) DEFAULT '0' COMMENT '是否推荐1推荐0普通',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `published_time` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `author` varchar(255) DEFAULT NULL COMMENT '作者',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `excerpt` varchar(255) DEFAULT NULL COMMENT '摘要',
  `source` varchar(255) DEFAULT NULL COMMENT '来源',
  `content` text COMMENT '内容',
  `more` text COMMENT '扩展属性',
  `template_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='内容表';

LOCK TABLES `lv_post` WRITE;
/*!40000 ALTER TABLE `lv_post` DISABLE KEYS */;

INSERT INTO `lv_post` (`id`, `post_status`, `comment_status`, `user_id`, `is_top`, `is_recommend`, `create_time`, `update_time`, `delete_time`, `published_time`, `title`, `author`, `keywords`, `excerpt`, `source`, `content`, `more`, `template_id`)
VALUES
	(1,1,1,1,1,0,1524492151,1524626807,NULL,NULL,'testcontent','wly',NULL,NULL,NULL,NULL,NULL,NULL),
	(2,1,1,1,0,0,1524492404,1524492404,NULL,NULL,'test',NULL,NULL,NULL,NULL,NULL,NULL,NULL),
	(5,1,1,1,0,0,1524627300,1524627300,NULL,NULL,'test3',NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `lv_post` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_role`;

CREATE TABLE `lv_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COMMENT '角色名称',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';

LOCK TABLES `lv_role` WRITE;
/*!40000 ALTER TABLE `lv_role` DISABLE KEYS */;

INSERT INTO `lv_role` (`id`, `name`, `create_time`, `update_time`)
VALUES
	(1,'管理员',NULL,NULL);

/*!40000 ALTER TABLE `lv_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_role_auth
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_role_auth`;

CREATE TABLE `lv_role_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `auth_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限关系表';

LOCK TABLES `lv_role_auth` WRITE;
/*!40000 ALTER TABLE `lv_role_auth` DISABLE KEYS */;

INSERT INTO `lv_role_auth` (`id`, `role_id`, `auth_id`)
VALUES
	(4,1,1);

/*!40000 ALTER TABLE `lv_role_auth` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_single
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_single`;

CREATE TABLE `lv_single` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `excerpt` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `content` text,
  `more` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '1' COMMENT '1发布0不发布',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='单页表';



# Dump of table lv_slide
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_slide`;

CREATE TABLE `lv_slide` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `more` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `lv_slide` WRITE;
/*!40000 ALTER TABLE `lv_slide` DISABLE KEYS */;

INSERT INTO `lv_slide` (`id`, `name`, `more`)
VALUES
	(1,'主幻灯片',NULL),
	(2,'副幻灯片',NULL);

/*!40000 ALTER TABLE `lv_slide` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_slide_item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_slide_item`;

CREATE TABLE `lv_slide_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_url` text,
  `link` text,
  `type` tinyint(3) DEFAULT NULL COMMENT '1栏目型2内容型3单页型4外链型',
  `item_id` int(20) DEFAULT '0' COMMENT '幻灯片背后内容的id',
  `more` text,
  `list_order` int(11) DEFAULT '0' COMMENT '展示次序',
  `slide_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='幻灯片表';

LOCK TABLES `lv_slide_item` WRITE;
/*!40000 ALTER TABLE `lv_slide_item` DISABLE KEYS */;

INSERT INTO `lv_slide_item` (`id`, `img_url`, `link`, `type`, `item_id`, `more`, `list_order`, `slide_id`)
VALUES
	(1,NULL,NULL,1,1,NULL,0,1),
	(3,NULL,'www.baidu.com',4,0,NULL,100,1);

/*!40000 ALTER TABLE `lv_slide_item` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lv_template
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_template`;

CREATE TABLE `lv_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` text,
  `name` text,
  `more` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT '1' COMMENT '1栏目模版2单页模版3内容模版',
  `is_default` tinyint(11) DEFAULT '0' COMMENT '0非默认1默认',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table lv_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lv_user`;

CREATE TABLE `lv_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL DEFAULT '',
  `password` text NOT NULL,
  `type` int(11) DEFAULT '0' COMMENT '会员类型',
  `status` varchar(11) DEFAULT '0' COMMENT '会员状态当前账号状态0正常，1锁死，2拉黑',
  `error_count` varchar(11) DEFAULT '0',
  `more` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='普通用户表';

LOCK TABLES `lv_user` WRITE;
/*!40000 ALTER TABLE `lv_user` DISABLE KEYS */;

INSERT INTO `lv_user` (`id`, `account`, `password`, `type`, `status`, `error_count`, `more`, `create_time`, `update_time`)
VALUES
	(1,'849688611','debfc6c7fd10b9b2e1d1c2924c6beb10',0,'0','0','\"\"',1525929699,1525930452),
	(2,'w849688611','debfc6c7fd10b9b2e1d1c2924c6beb10',0,'0','0','\"\"',1525929727,1525930008),
	(3,'wly849688611','debfc6c7fd10b9b2e1d1c2924c6beb10',0,'0','0','\"\"',1525930493,1525941641);

/*!40000 ALTER TABLE `lv_user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
