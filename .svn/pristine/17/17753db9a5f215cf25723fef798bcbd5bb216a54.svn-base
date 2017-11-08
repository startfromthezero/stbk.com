-- GPRS
DROP TABLE `cc_zone`,`cc_country`, `cc_customer_group`,`cc_customer_group_description`,`cc_customer_ip`,`cc_customer_ip_blacklist`;

DELETE FROM `cc_setting` WHERE `group` != 'config' AND `group` != 'wxpay';
ALTER TABLE `cc_setting` DROP COLUMN `setting_id`;
ALTER TABLE `cc_setting` ADD COLUMN `setting_id` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY(`setting_id`);
ALTER TABLE `cc_user` ADD COLUMN `org_id` INT(11) DEFAULT 0 NOT NULL COMMENT '机构编号' AFTER user_id, DROP COLUMN partner_id, DROP COLUMN partner_key;

ALTER TABLE `cc_gprs_alert` CHANGE `user_id` `org_id` INT(11) DEFAULT 0 NOT NULL COMMENT '机构编号'; 
ALTER TABLE `cc_gprs_batch` CHANGE `user_id` `org_id` INT(11) DEFAULT 0 NOT NULL COMMENT '机构编号'; 
ALTER TABLE `cc_gprs_card` CHANGE `user_id` `org_id` INT(11) DEFAULT 0 NOT NULL COMMENT '机构编号'; 
ALTER TABLE `cc_gprs_pack` CHANGE `user_id` `org_id` INT(11) DEFAULT 0 NOT NULL COMMENT '机构编号'; 
ALTER TABLE `cc_gprs_pay` CHANGE `user_id` `org_id` INT(11) DEFAULT 0 NOT NULL COMMENT '机构编号'; 
ALTER TABLE `cc_gprs_czk` CHANGE `user_id` `org_id` INT(11) DEFAULT 0 NOT NULL COMMENT '机构编号'; 

ALTER TABLE `cc_gprs_card` DROP COLUMN `device_id`;
ALTER TABLE `cc_gprs_log` DROP COLUMN `device_id`;
ALTER TABLE `cc_gprs_pay` DROP COLUMN `device_id`;
ALTER TABLE `cc_gprs_pay` DROP COLUMN `customer_id`;
ALTER TABLE `cc_gprs_czk` DROP COLUMN `device_id`;

-- New DB GPRS
CREATE DATABASE `gprs`CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE gprs.cc_currency LIKE clw.cc_currency;
INSERT INTO gprs.cc_currency SELECT * FROM clw.cc_currency;
CREATE TABLE gprs.cc_extension LIKE clw.cc_extension;
INSERT INTO gprs.cc_extension SELECT * FROM clw.cc_extension;
CREATE TABLE gprs.cc_gprs_alert LIKE clw.cc_gprs_alert;
INSERT INTO gprs.cc_gprs_alert SELECT * FROM clw.cc_gprs_alert;
CREATE TABLE gprs.cc_gprs_batch LIKE clw.cc_gprs_batch;
INSERT INTO gprs.cc_gprs_batch SELECT * FROM clw.cc_gprs_batch;
CREATE TABLE gprs.cc_gprs_card LIKE clw.cc_gprs_card;
INSERT INTO gprs.cc_gprs_card SELECT * FROM clw.cc_gprs_card;
CREATE TABLE gprs.cc_gprs_czk LIKE clw.cc_gprs_czk;
INSERT INTO gprs.cc_gprs_czk SELECT * FROM clw.cc_gprs_czk;
CREATE TABLE gprs.cc_gprs_log LIKE clw.cc_gprs_log;
INSERT INTO gprs.cc_gprs_log SELECT * FROM clw.cc_gprs_log;
CREATE TABLE gprs.cc_gprs_pack LIKE clw.cc_gprs_pack;
INSERT INTO gprs.cc_gprs_pack SELECT * FROM clw.cc_gprs_pack;
CREATE TABLE gprs.cc_gprs_pay LIKE clw.cc_gprs_pay;
INSERT INTO gprs.cc_gprs_pay SELECT * FROM clw.cc_gprs_pay;
CREATE TABLE gprs.cc_gprs_stats LIKE clw.cc_gprs_stats;
INSERT INTO gprs.cc_gprs_stats SELECT * FROM clw.cc_gprs_stats;
CREATE TABLE gprs.cc_language LIKE clw.cc_language;
INSERT INTO gprs.cc_language SELECT * FROM clw.cc_language;
CREATE TABLE gprs.cc_nation LIKE clw.cc_nation;
INSERT INTO gprs.cc_nation SELECT * FROM clw.cc_nation;
CREATE TABLE gprs.cc_setting LIKE clw.cc_setting;
INSERT INTO gprs.cc_setting SELECT * FROM clw.cc_setting;
CREATE TABLE gprs.cc_store LIKE clw.cc_store;
INSERT INTO gprs.cc_store SELECT * FROM clw.cc_store;
CREATE TABLE gprs.cc_user LIKE clw.cc_user;
INSERT INTO gprs.cc_user SELECT * FROM clw.cc_user;
CREATE TABLE gprs.cc_user_group LIKE clw.cc_user_group;
INSERT INTO gprs.cc_user_group SELECT * FROM clw.cc_user_group;
CREATE TABLE gprs.session_mem LIKE clw.session_mem;
INSERT INTO gprs.session_mem SELECT * FROM clw.session_mem;
CREATE TABLE gprs.session_wcore LIKE clw.session_wcore;
INSERT INTO gprs.session_wcore SELECT * FROM clw.session_wcore;

CREATE TABLE gprs.cc_ota (
  `ota_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '版本自增号',
  `org_id` INT(11) NOT NULL DEFAULT '0' COMMENT '机构编号',
  `org_code` VARCHAR(20) DEFAULT NULL COMMENT '机构编号',
  `hard_code` VARCHAR(20) NOT NULL COMMENT '硬件方案号',
  `soft_code` VARCHAR(20) NOT NULL COMMENT '软件版本号',
  `soft_for` VARCHAR(20) NOT NULL COMMENT '适合版本号',
  `soft_desc` TEXT COMMENT '升级版本描述',
  `pack_url` VARCHAR(255) NOT NULL COMMENT '升级包下载URL',
  `pack_md5` VARCHAR(50) NOT NULL COMMENT '升级包文件MD5值',
  `pack_size` INT(11) DEFAULT NULL COMMENT '升级包文件大小',
  `pack_count` INT(11) DEFAULT '0' COMMENT '升级包下载次数',
  `is_forced` TINYINT(1) DEFAULT '0' COMMENT '强制升级0否1是',
  `is_valid` TINYINT(1) DEFAULT '1' COMMENT '版本是否有效',
  `time_publish` DATE DEFAULT NULL COMMENT '版本发布时间',
  `time_added` DATETIME DEFAULT NULL COMMENT '增加版本时间',
  PRIMARY KEY (`ota_id`),
  KEY `user_id` (`org_id`),
  KEY `org_code` (`org_code`,`hard_code`),
  KEY `soft_code` (`soft_code`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8 COMMENT='OTA升级表';

CREATE TABLE gprs.cc_ota_device (
  `otad_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'OTA设备编号',
  `org_id` INT(11) NOT NULL DEFAULT '0' COMMENT '机构编号',
  `org_code` VARCHAR(20) DEFAULT NULL COMMENT '机构编号',
  `hard_code` VARCHAR(20) NOT NULL COMMENT '硬件方案号',
  `soft_code` VARCHAR(20) NOT NULL COMMENT '软件版本号',
  `device_sn` VARCHAR(50) DEFAULT NULL COMMENT '设备SN号',
  `device_ip` VARCHAR(20) NOT NULL COMMENT '设备IP地址',
  `device_mac` VARCHAR(50) NOT NULL COMMENT '设备MAC地址',
  `upgrade_count` INT(11) DEFAULT '0' COMMENT '升级次数',
  `upgrade_time` DATETIME DEFAULT NULL COMMENT '升时时间',
  `added_time` DATETIME DEFAULT NULL COMMENT '登记时间',
  PRIMARY KEY (`otad_id`),
  UNIQUE KEY `device_sn` (`device_sn`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8 COMMENT='版本升级设备表';

CREATE TABLE gprs.cc_org (
  `org_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '机构编号',
  `name` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '机构名称',
  `memo` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '机构描述',
  `email` VARCHAR(96) NOT NULL DEFAULT '' COMMENT '负责人邮箱',
  `tel` VARCHAR(15) DEFAULT '' COMMENT '负责人电话',
  `partner_id` VARCHAR(50) DEFAULT '' COMMENT '合作伙伴编号',
  `partner_key` VARCHAR(50) DEFAULT '' COMMENT '合作伙伴密匙',
  `notify_url` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '异步通知地址',
  `user_num` INT(11) NOT NULL COMMENT '可开的账户数量',
  `time_added` DATETIME DEFAULT NULL COMMENT '增加时间',
  `time_modify` DATETIME DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`org_id`),
  UNIQUE KEY `partner_id` (`partner_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8 COMMENT='机构管理表';

INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('1','自营','公司自营','hoojar@163.com','13800138000','rpfud85l6c4apfh','eb9dd737d672f8d551ac1344180b7a48','http://w.cyagps.com/app/gprs','5','2016-05-13 11:23:12',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('2','双平泰','深圳市双平泰科技有限公司','zhangshulin@spt-tek.com','','df6nm4kwzdkduz9','1320fba3867f9ead6ac368d90c0be4ba','http://m.spt-tek.com/app/gprs','5','2016-05-13 13:48:00',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('3','爱路客','爱路客，让汽车更安全，让旅途不寂寞！ ','ilook168@126.com','','huzgp8prrug5wll','c9f7c651857adb4015bd85e0f7b453f7','http://wx.ilook168.com/app/gprs','5','2016-05-13 13:49:22',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('5','博毅','博毅','','','hchr3wlcksdwasg','cd6ac292130490e38e03227ce9a2bc38','http://wx.spt-tek.com/app/gprs','5','2016-05-13 13:51:27',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('6','卡士特（美伴）','卡士特（美伴）','','','zklzbzzw4zp6mnd','324d232c06f19d120853ccf870379c26','http://wx.spt-tek.com/app/gprs','5','2016-05-13 13:51:46',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('7','天之眼','深圳市天之眼科技有限公司','','','pphmkp52elrmp8e','f8a4f198db7f42861d411223195e11a7','http://yunos.teyes.cn/app/gprs','5','2016-05-13 13:51:59',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('8','纽曼','湖南纽思曼汽车电子科技有限公司','','','g97vlzrzehab23w','431fbfd723d84f1e3f356868f0854b99','http://iov.newsmy.com/app/gprs','5','2016-05-13 13:52:22',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('9','奇橙天下','奇橙天下科技（深圳）有限公司','','','tk3n3frctypl3dv','c6ae9c8fa8c1051df002ada8263ebfa1','http://wx.qiorange.com/app/gprs','5','2016-05-13 13:52:39',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('10','云智易联','深圳市云智易联科技有限公司','','','ywascrn6wv8skvm','682e92883de90d8289abbd4e3d3853c7','http://wx.spt-tek.com/app/gprs','5','2016-05-13 13:53:00',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('11','先知','深圳市先知车联网科技有限公司','','','pausbren4hn56w4','8627f34286939a50e90ee42271dd8bb1','http://clw.xianzhigps.com/app/gprs','5','2016-05-13 13:53:15',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('13','云智测试','云智测试当前主要是对流量管理进行测试','','','wgvk8gak83nlyvw','4e914cffb50c8c652a6fcc7c6ab1413b','http://wx.spt-tek.com/app/gprs','5','2016-05-13 13:53:34',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('14','神行者','深圳市盈科创展科技有限公司','','','peefec68e9gfysw','427c4621b510111d817751447fdd7f93','http://wx.freelander.net.cn/app/gprs','5','2016-05-13 13:54:04',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('15','大智在线','深圳市大智创新科技股份有限公司','','','ekycdfcr7y7hsgt','f6dd2963cb364ef5348c427e2ed8cd8d','http://wx.daza168.com/app/gprs','5','2016-05-13 13:54:15',NULL);
INSERT INTO gprs.cc_org (`org_id`, `name`, `memo`, `email`, `tel`, `partner_id`, `partner_key`, `notify_url`, `user_num`, `time_added`, `time_modify`) VALUES('17','瞄拍客','深圳市橙彩科技有限公司','chenlong@yunovo.cn','','yybb4gu3ge23krm','7d996f0e990c0418ed409a2134b1166c','http://wx.mpk21.com/app/gprs','5','2016-05-13 13:54:52',NULL);
UPDATE gprs.cc_user SET org_id = user_id;

-- Remove cyagps DB some tables
DROP TABLE `cc_currency`,`cc_extension`,`cc_gprs_alert`,`cc_gprs_batch`,`cc_gprs_card`,`cc_gprs_czk`,`cc_gprs_log`,`cc_gprs_pack`,`cc_gprs_pay`,`cc_gprs_stats`,`cc_gprs_yck`,`cc_nation`,`cc_user`,`cc_user_group`;
DELETE FROM `cc_setting` WHERE `group` != 'config';
ALTER TABLE `cc_setting` DROP COLUMN `setting_id`;
ALTER TABLE `cc_setting` ADD COLUMN `setting_id` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY(`setting_id`);
