

/*
    Name		-	get_sms_consume_frm_smslog
    Description 	-	get sms consume by customer from smslog table on todaysdateParam.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_sms_consume_frm_smslog(3,'2016-09-16');
    Created by		-	Arvind
    Created on		- 	16 September, 2016
    Change details 	-	
    1) 	Updated by	-       Arvind Thakur
	Updated	on	- 	2016-11-29
        Reason		-	Wrong DateFormat
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_smslog`$$
CREATE PROCEDURE `get_sms_consume_frm_smslog`(
    IN customernoParam INT,
    IN todaysdateParam DATE
)
BEGIN
    DECLARE todayVar VARCHAR(20);
    IF(customernoParam = '' OR customernoParam = '0') THEN
        SET customernoParam = NULL;
    END IF;

    IF(todaysdateParam = '' OR todaysdateParam = '0') THEN
        SET todaysdateParam = NULL;
    ELSE 
        SELECT CONCAT(todaysdateParam,'%') INTO todayVar;
    END IF;

    SELECT COUNT(sm.smsid) AS count1 FROM `smslog` AS sm 
    WHERE sm.customerno=customernoParam AND sm.inserted_datetime LIKE todayVar;

END$$
DELIMITER ;
