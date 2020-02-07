
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'423', '2016-10-24 13:11:01', 'Arvind Thakur', 'Yesterdays sms consume details SP', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_detail_consume_yesterday`$$
CREATE PROCEDURE `get_sms_detail_consume_yesterday`(
    IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;
SELECT * FROM ((select user.phone,comqueue.message,comhistory.timesent from comhistory
INNER JOIN comqueue on comqueue.cqid=comhistory.comqid
INNER JOIN user on user.userid=comhistory.userid
WHERE comhistory.customerno=customernos AND DATE(comhistory.timesent)=subdate(CURRENT_DATE,1))
UNION ALL
(SELECT mobileno,message,inserted_datetime from smslog WHERE customerno=customernos AND DATE(inserted_datetime)=subdate(CURRENT_DATE,1))
 ) results
 ORDER BY timesent ASC
;     
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_all_admin_email_id`$$
CREATE PROCEDURE `get_all_admin_email_id`(
    IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;

SELECT email FROM user WHERE customerno=customernos AND role='Administrator' AND isdeleted=0 ORDER BY userid
;     
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_all_customer`$$
CREATE PROCEDURE `get_all_customer`()
BEGIN
    
SELECT     c.customerno
        ,c.customername
        ,c.customercompany
        ,c.customerTypeId
        ,c.smsleft
FROM customer AS c
WHERE c.customercompany <> 'Elixia Tech' AND c.use_tracking=1 AND c.renewal NOT IN (-1,-2)
ORDER BY c.customerno ASC
; 
END$$
DELIMITER ;




UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 423;
