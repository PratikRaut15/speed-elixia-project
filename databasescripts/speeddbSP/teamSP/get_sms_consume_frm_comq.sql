

/*
    Name		-	get_sms_consume_frm_comq
    Description 	-	get sms consume by customer from comqueue table on todaysdateParam.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_sms_consume_frm_comq(3,'2016-09-16');
    Created by		-	Arvind
    Created on		- 	16 September, 2016
    Change details 	-	
    1) 	Updated by	- 	Arvind Thakur
	Updated	on	- 	2016-11-29
        Reason		-	Wrong DateFormat
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_comq`$$
CREATE PROCEDURE `get_sms_consume_frm_comq`(
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
SELECT COUNT(cq.cqhid) AS count1 FROM `comhistory` as cq
    WHERE cq.customerno=customernoParam AND cq.timesent LIKE todayVar AND cq.comtype=1;
END$$
DELIMITER ;
