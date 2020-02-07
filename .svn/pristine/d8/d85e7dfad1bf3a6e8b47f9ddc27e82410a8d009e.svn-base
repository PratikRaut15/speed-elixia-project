
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'433', '2016-11-28 16:11:01', 'Arvind Thakur', 'SP for SMS consume', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_comq`$$
CREATE PROCEDURE `get_sms_consume_frm_comq`(
    IN customernos INT,
    IN yesterday DATE
)
BEGIN
    DECLARE yest VARCHAR(20);
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;
    IF(yesterday = '' OR yesterday = '0') THEN
            SET yesterday = NULL;
    ELSE
            SELECT CONCAT(yesterday,'%') INTO yest;
    END IF;
SELECT COUNT(cq.cqhid) AS count1 FROM `comhistory` as cq
    WHERE cq.customerno=customernos AND cq.timesent LIKE yest AND cq.comtype=1;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_smslog`$$
CREATE PROCEDURE `get_sms_consume_frm_smslog`(
    IN customernos INT,
    IN yesterday DATE
)
BEGIN
    DECLARE yest VARCHAR(20);
    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    IF(yesterday = '' OR yesterday = '0') THEN
        SET yesterday = NULL;
    ELSE 
        SELECT CONCAT(yesterday,'%') INTO yest;
    END IF;

    SELECT COUNT(sm.smsid) AS count1 FROM `smslog` AS sm 
    WHERE sm.customerno=customernos AND sm.inserted_datetime LIKE yest;

END$$
DELIMITER ;

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 433;