DELIMITER $$
DROP PROCEDURE IF EXISTS `get_routecheckpoints`$$
CREATE PROCEDURE `get_routecheckpoints`(
	IN custno INT
    , IN routechkptid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF routechkptid = '' OR routechkptid = 0 THEN
		SET routechkptid = NULL;
    END IF;
	SELECT 	 r.routecheckpointid
			,rm.routename				
			,r.routemasterid
			,r.fromlocationid
            , fact.factoryname
			, r.tolocationid
            , toloc.locationname
            , r.distance
			, r.customerno
   FROM routecheckpoint AS r
   INNER JOIN factory AS fact ON fact.factoryid = r.fromlocationid
   INNER JOIN location AS toloc ON toloc.locationid = r.tolocationid
   INNER JOIN routemaster AS rm ON rm.routemasterid = r.routemasterid
   WHERE 	(r.customerno = custno OR custno IS NULL)
   AND		(r.routecheckpointid = routechkptid OR routechkptid IS NULL)
   AND 		r.isdeleted = 0;
END$$
DELIMITER ;
