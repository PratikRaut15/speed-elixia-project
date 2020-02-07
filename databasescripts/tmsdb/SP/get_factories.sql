DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factories`$$
CREATE  PROCEDURE `get_factories`(
	IN custno INT
	, IN factid INT
	, IN zid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(factid = '' OR factid = 0) THEN
		SET factid = NULL;
	END IF;
	IF(zid = '' OR zid = 0) THEN
		SET zid = NULL;
	END IF;
	SELECT factoryid
			,factorycode
			,factoryname
			,f.zoneid
			,z.zonename
			,f.customerno
			,f.created_on
			,f.updated_on
			,f.created_by
			,f.updated_by
	FROM   	factory AS f INNER JOIN 
			zone AS z ON z.zoneid = f.zoneid
	WHERE   (f.customerno = custno OR custno IS NULL)
	AND 	(f.factoryid = factid OR factid IS NULL)
    AND 	(f.zoneid = zid OR zid IS NULL)
    AND		f.isdeleted = 0;
END$$
DELIMITER ;
