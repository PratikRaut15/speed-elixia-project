DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_routecheckpoint`$$
CREATE  PROCEDURE `delete_routecheckpoint`(
	IN routechkptid INT
    , IN routemstid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	IF routechkptid = '' OR routechkptid = 0 THEN
		SET routechkptid = NULL;
    END IF;
    IF routemstid = '' OR routemstid = 0 THEN
		SET routemstid = NULL;
    END IF;
	UPDATE 	routecheckpoint
    SET  	isdeleted = 1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	(routecheckpointid = routechkptid OR routechkptid IS NULL)
    AND 	(routemasterid = routemstid OR routemstid IS NULL);
END$$
DELIMITER ;
