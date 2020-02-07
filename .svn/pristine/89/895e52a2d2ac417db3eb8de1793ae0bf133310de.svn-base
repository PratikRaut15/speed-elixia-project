DELIMITER $$
DROP PROCEDURE IF EXISTS `get_depots`$$
CREATE PROCEDURE `get_depots`(
	IN custno INT
    , IN zid INT
    , IN did INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    
    IF(zid = '' OR zid = 0) THEN
		SET zid = NULL;
	END IF;
	IF(did = '' OR did = 0) THEN
		SET did = NULL;
	END IF;
	SELECT 	depotid
			, depotcode
            , depotname
            , d.zoneid
			
			, d.customerno
			, d.created_on
			, d.updated_on
			, z.zonename
			
   FROM depot as d
   INNER JOIN zone as z on z.zoneid = d.zoneid
   WHERE (d.customerno = custno OR custno IS NULL)
   AND 	(d.zoneid = zid OR zid IS NULL)
   AND 	(d.depotid = did OR did IS NULL)
   AND 	d.isdeleted = 0;
END$$
DELIMITER ;
