DELIMITER $$
DROP PROCEDURE IF EXISTS `get_left_over_sku`$$
CREATE PROCEDURE `get_left_over_sku`(
        IN custno INT
        ,IN leftoveridparam INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(leftoveridparam = '' OR leftoveridparam = 0) THEN
	SET leftoveridparam = NULL;
    END IF;
    
    SELECT 	
            lsm.leftover_sku_mappingid
            , lsm.leftoverid
            , lsm.skuid
            , lsm.no_of_units
            , lsm.totalWeight
            , lsm.totalVolume
            , sku.skucode
            , sku.sku_description
            , lsm.customerno
    FROM    leftover_sku_mapping lsm
    INNER JOIN sku ON sku.skuid = lsm.skuid
    WHERE (lsm.customerno = custno OR custno IS NULL)
    AND (lsm.leftoverid = leftoveridparam OR leftoveridparam IS NULL)
    AND   lsm.isdeleted = 0;
END$$
DELIMITER ;
