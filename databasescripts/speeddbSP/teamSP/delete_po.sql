/*
    Name		-	delete_po
    Description 	-	Delete PO Details
    Parameters		-	poidparam INT,custparam INT
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL delete_po(1,2,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_po`$$
CREATE  PROCEDURE `delete_po`(
	IN poidparam INT
    , IN custparam INT
    , IN updatedby INT
    , IN updatedon DATETIME
	)
BEGIN
UPDATE po
SET isdeleted = 1
 ,updatedby = updatedby
 ,updatedon = updatedon
WHERE poid  =  poidparam
AND customerno = custparam
;
END$$
DELIMITER ;
