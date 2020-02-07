DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_indent_sku_mapping`$$
CREATE  PROCEDURE `insert_indent_sku_mapping`( 
	IN indentid INT
	, IN skuid INT
	, IN no_of_units INT
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
    , OUT current_indent_sku_mappingid INT
)
BEGIN

	INSERT INTO indent_sku_mapping( 
									indentid
									, skuid
									, no_of_units
									, customerno
									, created_on
									, updated_on 
									, created_by
									, updated_by
								)
	VALUES ( 
				indentid
				, skuid
                , no_of_units
                , customerno
                , todaysdate
                , todaysdate
                , userid
                , userid
			);

	SET current_indent_sku_mappingid = LAST_INSERT_ID();

END$$
DELIMITER ;
