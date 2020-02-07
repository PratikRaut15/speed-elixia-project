DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_vehicle`$$
CREATE PROCEDURE `delete_vehicle`(
	IN vehid INT
    ,IN tranid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	IF(vehid = '' OR vehid = 0) THEN
		SET vehid = NULL;
	END IF;
    IF(tranid = '' OR tranid = 0) THEN
		SET tranid = NULL;
	END IF;
	UPDATE vehicle 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE 	(vehicleid = vehid OR vehid IS NULL)
    AND		(transporterid = tranid OR tranid IS NULL);
END$$
DELIMITER ;
