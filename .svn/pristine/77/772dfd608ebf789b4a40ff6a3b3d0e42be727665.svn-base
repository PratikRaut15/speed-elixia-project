DELIMITER $$
DROP PROCEDURE IF EXISTS `get_proposed_indent_sku_mapping`$$
CREATE PROCEDURE `get_proposed_indent_sku_mapping`(
	IN custno INT
    , propindentid INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(propindentid = '' OR propindentid = 0) THEN
		SET propindentid = NULL;
	END IF;
	SELECT 	        pism.proposedindentid
			, s.skuid
			, s.skucode
		        , s.sku_description
			, pism.no_of_units
			, pism.weight
			, pism.volume
			, pism.customerno
			, pism.created_on
			, pism.updated_on 
			, pism.created_by
			, pism.updated_by
	FROM 	proposed_indent_sku_mapping as pism
    
    
    INNER JOIN sku s ON s.skuid = pism.skuid
	WHERE 	(pism.customerno = custno OR custno IS NULL)
    AND		(pism.proposedindentid = propindentid OR propindentid IS NULL)
	AND 	pism.isdeleted = 0;
END$$
DELIMITER ;
