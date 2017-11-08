-- 创建数据结构
CREATE TABLE `cc_gprs_allot` (
  `allot_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分配增号',
  `card_id` INT(10) NOT NULL DEFAULT '0' COMMENT '流量卡编号',
  `gprs_total` DECIMAL(10,2) DEFAULT '0.00' COMMENT '流量总值(MB)',
  `allot_month` INT(10) NOT NULL DEFAULT '0' COMMENT '分配几个月',
  `allot_value` DECIMAL(10,2) DEFAULT '0.00' COMMENT '分配流量值(MB)',
  `allot_reset` TINYINT(1) DEFAULT '0' COMMENT '分配流量是否清零:0不清 1清零',
  `assigned_month` INT(10) NOT NULL DEFAULT '0' COMMENT '已分配几个月',
  `time_expire` DATETIME DEFAULT NULL COMMENT '过期时间',
  `time_added` DATETIME DEFAULT NULL COMMENT '添加时间',
   PRIMARY KEY (`allot_id`), KEY card_id(card_id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='流量分配表';

DROP TABLE IF EXISTS cc_gprs_value;
CREATE TABLE `cc_gprs_value` (
  `gprs_vid` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增号',
  `card_id` INT(10) NOT NULL DEFAULT '0' COMMENT '流量卡编号',
  `allot_id` INT(11) NOT NULL DEFAULT '0' COMMENT '分配编号',
  `how_month` INT(11) DEFAULT NULL COMMENT '当前月份:201601',
  `gprs_value` DECIMAL(10,2) DEFAULT '0.00' COMMENT '流量(MB)',
  `balance_dval` DECIMAL(10,2) DEFAULT '0.00' COMMENT '设备算剩余流量(MB)',
  `balance_value` DECIMAL(10,2) DEFAULT '0.00' COMMENT '剩余流量(MB)',
  `time_expire` DATETIME DEFAULT NULL COMMENT '过期时间',
  `time_added` DATETIME DEFAULT NULL COMMENT '添加时间',
  `time_modify` DATETIME DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`gprs_vid`),
  UNIQUE KEY `card_allot` (`card_id`,`allot_id`,`how_month`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='流量值表';

ALTER TABLE cc_gprs_pack ADD`allot_month` INT(10) NOT NULL DEFAULT '0' COMMENT '分配几个月' AFTER gprs_status,
ADD `allot_value` DECIMAL(10,2) DEFAULT '0.00' COMMENT '分配流量值(MB)' AFTER allot_month,
ADD `allot_reset` TINYINT(1) DEFAULT '0' COMMENT '分配流量是否清零:0不清 1清零' AFTER allot_value;


-- 迁移原来数据
INSERT INTO `cc_gprs_allot`(card_id,gprs_total,allot_month,allot_value,assigned_month,time_expire,time_added) SELECT card_id,max_unused,1,max_unused,1,time_expire,NOW() FROM `cc_gprs_card`;
INSERT INTO `cc_gprs_value`(card_id,allot_id,how_month,gprs_value,balance_dval,balance_value,time_expire,time_added) SELECT card_id,allot_id,201607,allot_value,allot_value,allot_value,time_expire,NOW() FROM `cc_gprs_allot`;