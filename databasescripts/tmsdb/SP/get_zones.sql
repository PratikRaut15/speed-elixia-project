DELIMITER $$
DROP PROCEDURE IF EXISTS `get_zones`$$
CREATE  PROCEDURE `get_zones`(
	IN custno INT
	,IN zid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
	IF(zid = '' OR zid = 0) THEN
		SET zid = NULL;
	END IF;
	SELECT zoneid
			, zonename
			, customerno
			, created_on
			, updated_on
            , created_by
            , updated_by
   FROM zone
   WHERE (customerno = custno OR custno IS NULL) 
   AND 	(zoneid = zid OR zid IS NULL)
   AND  isdeleted = 0;
END$$
DELIMITER ;
