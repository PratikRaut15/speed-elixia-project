/*
	Name			- delete_unprocessed_leftoverdetails
    Description 	-	 delete 
    Parameters		-	customernoparam, transporterid
    Module			-TMS
    Sub-Modules 	- 	Indents
    Sample Call		-	CALL delete_unprocessed_leftoverdetails('116','2016-01-01');

    Created by		-	Shrikant Suryawanshi
    Created	on		- 08 Jan 2015
    Change details 	-
    1) 	Updated by	-	
	Updated	on	-	
        Reason		-	
    2) 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_unprocessed_leftoverdetails`$$
CREATE PROCEDURE `delete_unprocessed_leftoverdetails`(
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

    DELETE FROM leftoverdetails 
    WHERE (customerno = custno OR custno IS NULL)
    AND ((created_on <= daterequiredparam + INTERVAL 1 DAY + INTERVAL -1 SECOND) OR daterequiredparam IS NULL)
    AND   isProcessed = 0
    AND   isdeleted = 0;
END$$
DELIMITER ;
