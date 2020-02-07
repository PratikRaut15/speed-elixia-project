/*
    Name		-	get_po
    Description 	-	get PO Details
    Parameters		-	customernoparam INT,poidparam VARCHAR(11)
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_po(2,1);
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	28 April, 2016
        Reason		-	Changed poidparam to varchar in order to handle blank values.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_po`$$
CREATE PROCEDURE `get_po`( 
IN customernoparam INT
,IN poidparam VARCHAR(11)
)

BEGIN
IF(customernoparam = '' OR customernoparam = '0') THEN
 SET customernoparam = NULL;
END IF;
IF(poidparam = '') THEN
 SET poidparam = NULL;
END IF;
SELECT
	poid
    ,pono
    ,podate
    ,poamount
    ,poexpiry
    ,description
    ,customerno
    ,createdby
    ,createdon
    ,updatedby
    ,updatedon
FROM po
WHERE 
(customerno = customernoparam OR customernoparam IS NULL)
AND (poid = poidparam OR poidparam IS NULL)
AND isdeleted = 0
;
END$$
DELIMITER ;
