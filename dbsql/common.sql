SELECT * FROM `cc_gprs_card` WHERE card_iccid='8986011670901098999';
SELECT * FROM `cc_gprs_card` WHERE card_iccid LIKE '%8986011670901081969%';

-- 支付流量卡更新机构所属
UPDATE `cc_gprs_pay` P, `cc_gprs_card` C SET P.`org_id` = C.`org_id` WHERE P.`card_id` = C.`card_id`

-- 查询联通使用总流量 > 2G + 充值总流量，
SELECT O.name AS 机构名称,C.card_sn AS 卡号,C.card_iccid AS 卡ICCID,C.used_total AS 平台统计使用总流量,C.pay_total AS 充值流量,C.unicom_total AS 联通统计使用总流量,
C.unicom_stop AS 卡状态,C.time_added AS 激活时间,C.time_last AS 最后更新时间 FROM `cc_gprs_card` C LEFT JOIN `cc_org` O ON O.`org_id` = C.`org_id` WHERE unicom_total > (pay_total + 2048)

-- 设置特殊流量卡，不做关停处理，流量卡按用量计费
UPDATE `cc_gprs_card` SET gprs_month = 99999999, time_expire = '2020-01-01 23:59:00' WHERE batch_id = 87 AND card_iccid IN ();

-- 修改流量卡所属机构与批次
UPDATE `cc_gprs_card` SET org_id = 48, batch_id = 98, time_expire = DATE_ADD(time_expire, INTERVAL 6 MONTH) WHERE card_iccid IN ();

-- 停号欠费处理
SELECT * FROM cc_gprs_card WHERE unicom_stop = 1;
UPDATE `cc_gprs_card` SET max_unused = -(unicom_total - gprs_month), gprs_month = 0, used_total = unicom_total, used_month = unicom_month
WHERE unicom_stop = 1 AND unicom_total != 0 AND gprs_month != 0 AND unicom_total > used_total;

-- 查询流量卡第一次上报数据时间
SELECT C.card_iccid, L.`time_added` FROM `cc_gprs_log` L INNER JOIN `cc_gprs_card` C ON C.`card_id`=L.`card_id` WHERE 
C.`card_iccid` IN ('8986011670901048810',
'8986011670901048793',
'8986011670901048809',
'8986011670901048818',
'8986011670901048794',
'8986011670901048790',
'8986011670901048827',
'8986011670901045851') GROUP BY card_iccid ORDER BY L.time_added ASC;

SELECT C.card_iccid, (SELECT L.time_added FROM `cc_gprs_log` L WHERE L.card_id = C.card_id ORDER BY L.time_added ASC LIMIT 1) AS time_added
FROM cc_gprs_card C WHERE C.`card_iccid` IN ('8986011670901048810',
'8986011670901048793',
'8986011670901048809',
'8986011670901048818',
'8986011670901048794',
'8986011670901048790',
'8986011670901048827',
'8986011670901045851');