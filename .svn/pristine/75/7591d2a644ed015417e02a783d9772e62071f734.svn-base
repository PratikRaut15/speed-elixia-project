DELIMITER $$
DROP PROCEDURE IF EXISTS `get_SMS_store_log`$$
CREATE PROCEDURE `get_SMS_store_log`(
     IN startdateparam DATE
    ,IN enddateparam DATE
     ,IN customernoparam INT	

)
BEGIN

	IF(customernoparam != NULL OR customernoparam != "")THEN
	   	SELECT s.vehicleno,s.cname,s.message,s.phone,s.timestamp as message_sent_on 
	   	FROM storesms AS s 
	   	LEFT JOIN checkpoint AS c ON c.checkpointid = s.checkpointid 
	   	WHERE s.customerno =customernoparam
	   	AND DATE(timestamp) >=startdateparam AND DATE(timestamp) <=enddateparam;
	END IF;

END$$
DELIMITER ;
