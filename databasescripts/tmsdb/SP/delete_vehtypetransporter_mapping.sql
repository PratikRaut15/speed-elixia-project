DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_vehtypetransporter_mapping`$$
CREATE PROCEDURE `delete_vehtypetransporter_mapping`(
	IN vtmidparam INT	
	,IN transid INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	IF(vtmidparam = '' OR vtmidparam = 0) THEN
		SET vtmidparam = NULL;
	END IF;
	UPDATE vehtypetransmapping 
    SET  isdeleted = 1
		, updated_on = todaysdate
        , updated_by = userid
	WHERE transporterid = transid
	AND (vtmid = vtmidparam OR vtmidparam IS NULL);
END$$
DELIMITER ;
