/*
	Name			- delete_isprocessed_factory_delivery
    Description 	-	delete isProcessed=1 records from factory_delivery
    Parameters		-	customernoparam, daterequiredparam
    Module			-TMS
    Sub-Modules 	- 	Factory Delivery 
    Sample Call		-	CALL  - call delete_processed_factory_delivery('116','2015-11-26');

    Created by		-	Shrikant Suryawanshi
    Created	on		31 Dec, 2015
    Change details 	-
    1) 	Updated by	-	
	Updated	on	-	
        Reason		-	
    2) 
*/

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


