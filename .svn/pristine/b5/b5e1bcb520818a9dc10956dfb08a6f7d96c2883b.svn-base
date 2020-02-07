DELIMITER $$
DROP PROCEDURE IF EXISTS `get_left_over_details`$$
CREATE PROCEDURE `get_left_over_details`(
        IN custno INT
        ,IN factoryidparam INT
        ,IN dateparam DATE
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(factoryidparam = '' OR factoryidparam = 0) THEN
	SET factoryidparam = NULL;
    END IF;
    IF(dateparam = '' OR dateparam = 0) THEN
		SET dateparam = NULL;
    END IF;
    
    SELECT 	
            ld.leftoverid
            , f.factoryid
            , f.factoryname
            , d.depotid
            , d.depotname
            , daterequired
            , ld.weight
            , ld.volume
            , ld.customerno
    FROM    leftoverdetails ld
    INNER JOIN factory f ON f.factoryid = ld.factoryid
    INNER JOIN depot d ON d.depotid = ld.depotid
    WHERE (ld.customerno = custno OR custno IS NULL)
    AND (ld.factoryid = factoryidparam OR factoryidparam IS NULL)
    AND ((ld.daterequired = dateparam) OR dateparam IS NULL)
    AND ld.isProcessed = 0
    AND   ld.isdeleted = 0;
END$$
DELIMITER ;
