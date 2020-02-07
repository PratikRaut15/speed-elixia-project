/*
	Name			- insert_factory_delivery_history
    Description 	-	insert isProcessed=1 records in factory_delivery_history table
    Parameters		-	customernoparam, isProcessedparam, daterequiredparam
    Module			-TMS
    Sub-Modules 	- 	Factory Delivery 
    Sample Call		-	CALL  - call insert_factory_delivery_history('116','1','2015-12-12');

    Created by		-	Shrikant Suryawanshi
    Created	on		31 Dec, 2015
    Change details 	-
    1) 	Updated by	-	
	Updated	on	-	
        Reason		-	
    2) 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_factory_delivery_history`$$
CREATE PROCEDURE `insert_factory_delivery_history`(
        IN custno INT
        , IN isProcessedparam INT
        , IN daterequiredparam DATE
)
BEGIN

DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    
    START TRANSACTION;
    
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(isProcessedparam = '' OR isProcessedparam = 0) THEN
		SET isProcessedparam = NULL;
    END IF;
    IF(daterequiredparam = '' OR daterequiredparam = 0) THEN
		SET daterequiredparam = NULL;
    END IF;

    INSERT INTO factory_delivery_history(
    fdid,
    factoryid,
    skuid,
    depotid,
    date_required,
    netWeight,
    grossWeight,
    isProcessed,
    customerno,
    created_on,
    updated_on,
    created_by,
    updated_by,
    isdeleted
    )
     SELECT 	
            fd.fdid
            , fd.factoryid
            , fd.skuid
            , fd.depotid
            , fd.date_required
            , fd.netWeight
            , fd.grossWeight
            , fd.isProcessed
            , fd.customerno
            , fd.created_on
            , fd.updated_on
			, fd.created_by
            , fd.updated_by
            , fd.isdeleted
	FROM    factory_delivery fd
    WHERE (fd.customerno = custno OR custno IS NULL)
    AND   (fd.isProcessed = isProcessedparam OR isProcessedparam IS NULL)
    AND ((fd.date_required < daterequiredparam) OR daterequiredparam IS NULL)
    AND   fd.isdeleted = 0;

	call delete_processed_factory_delivery(custno,daterequiredparam);

	COMMIT;
    
END$$
DELIMITER ;
