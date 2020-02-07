DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_indent`$$
CREATE PROCEDURE `delete_indent`( 
	IN indentidparam int
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	indent
	SET 	isdeleted = 1 
			, updated_on = todaysdate
            , updated_by = userid
	WHERE 	indentid = indentidparam;

END$$
DELIMITER ;
