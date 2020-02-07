/*
	Name			- insert_unprocessed_leftoverdetails_history
    Description 	-	To insert all unprocessed leftover dettails into history table
    Parameters		-	customernoparam, transporterid
    Module			-TMS
    Sub-Modules 	- 	Indents
    Sample Call		-	CALL insert_unprocessed_leftoverdetails_history('116','2016-01-01');

    Created by		-	Shrikant Suryawanshi
    Created	on		- 08 Jan 2015
    Change details 	-
    1) 	Updated by	-	
	Updated	on	-	
        Reason		-	
    2) 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_unprocessed_leftoverdetails_history`$$
CREATE PROCEDURE `insert_unprocessed_leftoverdetails_history`(
        IN custno INT
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
    
    IF(daterequiredparam = '' OR daterequiredparam = 0) THEN
		SET daterequiredparam = NULL;
    END IF;

    INSERT INTO leftoverdetails_history(
    leftoverid,
    factoryid,
    depotid,
    weight,
    volume,
    daterequired,
    isProcessed,
    customerno,
    created_on,
    updated_on,
    created_by,
    updated_by,
    isdeleted
    )
     SELECT 	
	leftoverid,
    factoryid,
    depotid,
    weight,
    volume,
    daterequired,
    isProcessed,
    customerno,
    created_on,
    updated_on,
    created_by,
    updated_by,
    isdeleted
	FROM    leftoverdetails 
    WHERE (customerno = custno OR custno IS NULL)
    AND ((daterequired < daterequiredparam) OR daterequiredparam IS NULL)
    AND isProcessed = 0
    AND   isdeleted = 0;

	call delete_unprocessed_leftoverdetails(custno,daterequiredparam);

	COMMIT;
    
END$$
DELIMITER ;

