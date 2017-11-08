/*
SQLyog Ultimate v11.3 (64 bit)
MySQL - 5.1.28-rc-community-log : Database - tsbk
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tsbk` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `tsbk`;

/*Table structure for table `cc_timer` */

DROP TABLE IF EXISTS `cc_timer`;

CREATE TABLE `cc_timer` (
  `timer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '时光轴内容',
  `time_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`timer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='时光轴表';

/*Data for the table `cc_timer` */

insert  into `cc_timer`(`timer_id`,`content`,`time_added`) values (1,'必须要加油啊','2017-11-06 09:59:48'),(2,'兄弟抖擞起来吧','2017-11-06 10:01:52'),(7,'再来一波11','2017-11-06 10:27:37'),(5,'大叔大婶大大是','2017-11-06 10:13:40'),(6,'终于好啦','2017-11-06 10:14:26'),(10,'为什么添加可以，编辑不行呢','2017-11-06 10:48:11'),(11,'来吧1','2017-11-06 10:51:33'),(12,'速度啊11111111','2017-11-06 10:51:42'),(15,'skjfsjfkjslf','2017-11-06 16:22:36'),(16,'ewdasda','2017-11-06 16:22:14');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
