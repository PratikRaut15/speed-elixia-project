DELIMITER $$
DROP PROCEDURE IF EXISTS `get_factory_delivery`$$
CREATE PROCEDURE `get_factory_delivery`(
        IN custno INT
        , IN fdidparam INT
        , IN factoryidparam INT
        , IN depotidparam INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(fdidparam = '' OR fdidparam = 0) THEN
		SET fdidparam = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
		SET factoryidparam = NULL;
    END IF;
    IF(depotidparam = '' OR depotidparam = 0) THEN
		SET depotidparam = NULL;
    END IF;

    SELECT 	
            f.factoryid
            , fd.fdid
            , f.factoryname
            , s.skuid
            , s.skucode
            , s.sku_description
            , d.depotid
            , d.depotname
            , date_required
            , fd.netWeight
            , fd.grossWeight
            , fd.customerno
            , fd.created_on
            , fd.updated_on
    FROM    factory_delivery fd
    INNER JOIN factory f ON f.factoryid = fd.factoryid
    INNER JOIN sku s ON s.skuid = fd.skuid
    INNER JOIN depot d ON d.depotid = fd.depotid
    WHERE (fd.customerno = custno OR custno IS NULL)
    AND   (fdid = fdidparam OR fdidparam IS NULL)
    AND   (fd.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND   (fd.depotid = depotidparam OR depotidparam IS NULL)
    AND   fd.isdeleted = 0;
END$$
DELIMITER ;
