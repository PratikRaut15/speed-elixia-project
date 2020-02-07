DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sku`$$
CREATE  PROCEDURE `get_sku`(
	IN custno INT
    , IN skuidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF (skuidparam = '' OR skuidparam = 0) THEN
		SET skuidparam = NULL;
    END IF;
	SELECT 	skucode
			, skuid
			, sku_description
			, st.type
			, st.tid
			, volume
			, weight
			, netgross
			, sku.customerno
	FROM 	sku
	left JOIN skutypes as st on st.tid = sku.skutypeid
	WHERE 	(sku.customerno = custno OR custno IS NULL)
    AND		(skuid = skuidparam OR skuidparam IS NULL)
	AND 	sku.isdeleted = 0;
END$$
DELIMITER ;
