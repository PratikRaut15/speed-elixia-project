DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transporteractualshare`$$
CREATE  PROCEDURE `get_transporteractualshare`(
	IN custno INT
    , IN currenttransporterid INT
    , IN currentfactid INT
    , IN currentzoneid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(currenttransporterid = '' OR currenttransporterid = 0) THEN
		SET currenttransporterid = NULL;
	END IF;
	IF(currentfactid = '' OR currentfactid = 0) THEN
		SET currentfactid = NULL;
	END IF;
    IF(currentzoneid = '' OR currentzoneid = 0) THEN
		SET currentzoneid = NULL;
	END IF;
    
	SELECT    tas.transporterid
			, tas.zoneid
			, tas.factoryid			
			, tas.total_weight
			, tas.shared_weight
			, tas.actualpercent
			, tas.customerno
   FROM 	transporter_actualshare AS tas
   WHERE 	(tas.customerno = custno OR custno IS NULL)
   AND		(tas.transporterid = currenttransporterid OR currenttransporterid IS NULL)
   AND		(tas.zoneid = currentzoneid OR currentzoneid IS NULL)
   AND		(tas.factoryid = currentfactid OR currentfactid IS NULL)
   AND 		tas.isdeleted = 0;
END$$
DELIMITER ;
