DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_pit_mapping`$$
CREATE PROCEDURE `delete_pit_mapping`( 
	IN pitmapid int
    , IN propindentid INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	IF (pitmapid = '' OR pitmapid = 0) THEN
		SET pitmapid = NULL;
    END IF;
    IF (propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
    END IF;
	UPDATE  proposed_indent_transporter_mapping
	SET 	isdeleted = 1
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	(pitmappingid = pitmapid	OR pitmapid IS NULL)
    AND 	(proposedindentid = propindentid OR propindentid IS NULL);

END$$
DELIMITER ;
