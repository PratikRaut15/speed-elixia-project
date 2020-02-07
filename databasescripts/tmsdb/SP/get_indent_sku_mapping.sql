DELIMITER $$
DROP PROCEDURE IF EXISTS `get_indent_sku_mapping`$$
CREATE PROCEDURE `get_indent_sku_mapping`(
	IN custno INT
       , indentidparam INT
)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    	IF(indentidparam = '' OR indentidparam = 0) THEN
		SET indentidparam = NULL;
	END IF;
	SELECT 	        
		ism.indentid
		, s.skuid
		, s.skucode
		, s.sku_description
		, ism.no_of_units
		, ism.customerno
		, ism.created_on
		, ism.updated_on 
		, ism.created_by
		, ism.updated_by
	FROM 	indent_sku_mapping as ism
	INNER JOIN sku s ON s.skuid = ism.skuid
	WHERE 	(ism.customerno = custno OR custno IS NULL)
    	AND	(ism.indentid = indentidparam OR indentidparam IS NULL)
	AND 	ism.isdeleted = 0;
END$$
DELIMITER ;
