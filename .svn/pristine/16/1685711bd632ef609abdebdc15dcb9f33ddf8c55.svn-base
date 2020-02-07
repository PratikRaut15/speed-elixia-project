INSERT INTO `dbpatches` (`patchid`, `updatedOn`, `appliedby`, `patchdesc`)
VALUES (653, NOW(), 'Manasvi Thakur','Added SP-get_sms_consume_frm_smsmlog_for_team to get team SMSM log count as well');

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_smslog_for_team`$$
CREATE PROCEDURE `get_sms_consume_frm_smslog_for_team`(
    IN todaysdateParam DATE
)
BEGIN
    DECLARE todayVar VARCHAR(20);
    IF(todaysdateParam = '' OR todaysdateParam = '0') THEN
        SET todaysdateParam = NULL;
    END IF;

    SELECT COUNT(sm.smsid) AS count1 FROM `smslog` AS sm
    WHERE sm.customerno=0 AND sm.inserted_datetime LIKE CONCAT(todaysdateParam,'%');

END$$
DELIMITER ;



UPDATE dbpatches SET isapplied=1, updatedOn='2019-01-22 14:28:00' WHERE patchid = 653;
