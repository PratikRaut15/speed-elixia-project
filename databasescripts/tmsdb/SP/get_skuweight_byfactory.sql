DELIMITER $$
DROP PROCEDURE IF EXISTS `get_skuweight_byfactory`$$
CREATE  PROCEDURE `get_skuweight_byfactory`(
	IN custno INT
    	)
BEGIN
	IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
	END IF;
       
	SELECT 	fd.skuid
            ,sum(fd.weight) as weight
            , fd.date_required
            , fd.depotid
            , fd.factoryid
            FROM factory_delivery fd
    	WHERE 	(customerno = custno OR custno IS NULL) group by factoryid, depotid, date_required, skuid;

END$$
DELIMITER ;
