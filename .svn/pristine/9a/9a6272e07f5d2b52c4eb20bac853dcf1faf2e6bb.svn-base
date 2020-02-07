DELIMITER $$
DROP PROCEDURE IF EXISTS `get_transportershare`$$
CREATE  PROCEDURE `get_transportershare`(
	IN custno INT
    , IN currenttransporterid INT
    , IN currentfactid INT
    , IN currentzoneid INT
    , IN transhareid INT
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
    IF(transhareid = '' OR transhareid = 0) THEN
		SET transhareid = NULL;
	END IF;
    
	SELECT          t.transporterid
			, t. transportername
			, ts.sharepercent
			, ts.transportershareid
			, z.zoneid
            , z.zonename
			, f.factoryid			
			, f.factoryname
			, ts.customerno
   FROM transportershare AS ts
   INNER JOIN transporter AS t ON t.transporterid = ts.transporterid
   INNER JOIN zone AS z ON z.zoneid = ts.zoneid
   INNER JOIN factory AS f ON ts.factoryid = f.factoryid
   WHERE 	(ts.customerno = custno OR custno IS NULL)
   AND		(ts.transporterid = currenttransporterid OR currenttransporterid IS NULL)
   AND		(ts.zoneid = currentzoneid OR currentzoneid IS NULL)
   AND		(ts.transportershareid = transhareid OR transhareid IS NULL)
AND		(ts.factoryid = currentfactid OR currentfactid IS NULL)
   AND 		ts.isdeleted = 0;
END$$
DELIMITER ;
