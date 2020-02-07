DELIMITER $$
DROP PROCEDURE IF EXISTS update_indent_sku_mapping$$
CREATE PROCEDURE `update_indent_sku_mapping`( 
	IN ismid INT,
	IN indentid int,
	IN skuid int,
	IN no_of_units int,
	IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
)
BEGIN
	UPDATE indent_sku_mapping
	SET 	indentid = indentid,
			skuid = skuid,
			no_of_units = no_of_units,
			updated_on = todaysdate, 
			updated_by = userid
	WHERE 	indent_sku_mappingid = ismid;

END$$
DELIMITER ;