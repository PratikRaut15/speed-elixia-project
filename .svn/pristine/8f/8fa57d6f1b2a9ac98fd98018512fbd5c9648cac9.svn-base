DELIMITER $$
DROP PROCEDURE IF EXISTS `get_routemaster`$$
CREATE PROCEDURE `get_routemaster`(
	IN custno INT
    , IN rtmasterid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(rtmasterid = '' OR rtmasterid = 0) THEN
		SET rtmasterid = NULL;
	END IF;
	SELECT 	r.routemasterid
			, r.routename
			, r.routedescription
			, r.fromlocationid
            , fact.factoryname
			, r.tolocationid
            , d.depotname
            , r.distance
            , r.travellingtime
			, r.customerno
   FROM routemaster AS r
   INNER JOIN factory AS fact ON fact.factoryid = r.fromlocationid
   INNER JOIN depot AS d ON d.depotid = r.tolocationid
   WHERE 	(r.customerno = custno OR custno IS NULL)
   AND 		(r.routemasterid = rtmasterid OR rtmasterid IS NULL)
   AND 		r.isdeleted = 0;
END$$
DELIMITER ;
