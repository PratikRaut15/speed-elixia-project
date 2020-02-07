DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_mapped_depot`$$
CREATE PROCEDURE `delete_mapped_depot`(
	IN did INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	UPDATE multidepot_mapping 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE depotid = did;
END$$
DELIMITER ;
