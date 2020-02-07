DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transporters`$$
CREATE  PROCEDURE `get_transporters`(
	IN custno INT
    , IN tranid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(tranid = '' OR tranid = 0) THEN
		SET tranid = NULL;
	END IF;
	SELECT 	transporterid
			, transportercode
            , transportername
            , transportermail
			, transportermobileno
			, customerno
			, created_on
			, updated_on
   FROM transporter
   WHERE (customerno = custno OR custno IS NULL)
   AND (transporterid = tranid OR tranid IS NULL)
	AND	isdeleted = 0;
END$$
DELIMITER ;
