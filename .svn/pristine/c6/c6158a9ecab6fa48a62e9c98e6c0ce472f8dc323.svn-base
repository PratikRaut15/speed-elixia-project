/*
	Name			- get_left_over_details
    Description 	-	To get left over details of indents
    Parameters		-	customernoparam, factoryid, daterequired
    Module			-TMS
    Sub-Modules 	- 	Left 
    Sample Call		-	CALL  - call get_left_over_details('116',2,'2015-11-03');

    Created by		-	Shrikant 
    Created	on		- Nov, 2015
    Change details 	-
    1) 	Updated by	-	Shrikant Suryawanshi
	Updated	on	-	21 Dec 2015 
        Reason		-	Add dateparam to get perticular date records
    2) 
*/

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


