DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_processed_factory_delivery`$$
CREATE PROCEDURE `delete_processed_factory_delivery`(
        IN custno INT
        , IN daterequiredparam DATE
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(daterequiredparam = '' OR daterequiredparam = 0) THEN
		SET daterequiredparam = NULL;
    END IF;

    DELETE FROM factory_delivery 
    WHERE (customerno = custno OR custno IS NULL)
    AND ((date_required < daterequiredparam) OR daterequiredparam IS NULL)
    AND   isProcessed = 1
    AND   isdeleted = 0;
END$$
DELIMITER ;
