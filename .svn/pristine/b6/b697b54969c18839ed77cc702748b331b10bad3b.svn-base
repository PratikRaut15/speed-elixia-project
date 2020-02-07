DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factory_production`$$
CREATE PROCEDURE `get_factory_production`(
	IN custno INT
    , IN fpidparam INT
	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
    IF(fpidparam = '' OR fpidparam = 0) THEN
		SET fpidparam = NULL;
	END IF;
	SELECT 	f.factoryid
		,fp.fpid
			, f.factoryname
            , s.skuid
            , s.skucode
            , s.sku_description
            
            , fp.weight
            , fp.customerno
	FROM 	factory_production fp
    INNER JOIN factory f ON f.factoryid = fp.factoryid
    INNER JOIN sku s ON s.skuid = fp.skuid
	WHERE 	(fp.customerno = custno OR custno IS NULL)
    AND 	(fp.fpid = fpidparam OR fpidparam IS NULL)
	AND 	fp.isdeleted = 0;
END$$
DELIMITER ;
