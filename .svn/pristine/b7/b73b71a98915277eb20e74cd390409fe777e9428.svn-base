DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehicles`$$
CREATE PROCEDURE `get_vehicles`(
	IN custno INT
    ,IN currenttransporterid INT
    ,IN vid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(currenttransporterid = '' OR currenttransporterid = 0) THEN
		SET currenttransporterid = NULL;
	END IF;
IF(vid = '' OR vid = 0) THEN
		SET vid = NULL;
	END IF;
	SELECT 	t.transporterid
			, transportername
			, vehicleid
			, v.vehicletypeid
            , vehiclecode
            , vehicleno
			, t.customerno
   FROM transporter as t
   INNER JOIN vehicle as v on t.transporterid = v.transporterid
   INNER JOIN vehicletype as vt on v.vehicletypeid = vt.vehicletypeid
   WHERE 	(t.customerno = custno OR custno IS NULL)
   AND		(t.transporterid = currenttransporterid OR currenttransporterid IS NULL)
   AND		(v.vehicleid = vid OR vid IS NULL)
   AND 		t.isdeleted = 0 and v.isdeleted=0;
END$$
DELIMITER ;
