DELIMITER $$
DROP PROCEDURE IF EXISTS `edit_indent`$$
CREATE PROCEDURE `edit_indent`( 
	IN indentidparam int,
	IN loadstatus int,
	IN shipmentno varchar(50),
	IN remark varchar(250)
	, IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE 	indent
	SET 	
			loadstatus = loadstatus
			,shipmentno = shipmentno
			,remarks = remark
			,updated_on = todaysdate
			,updated_by = userid
	WHERE 	indentid = indentidparam;

END$$
DELIMITER ;
