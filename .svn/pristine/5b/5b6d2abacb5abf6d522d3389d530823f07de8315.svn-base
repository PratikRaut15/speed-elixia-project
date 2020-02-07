/*
    Name		-	delete_ledger
    Description 	-	Delete Ledger Details
    Parameters		-	ledgeridparam INT,
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL delete_ledger(1,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	03 May, 2016
        Reason		-	delete mapping with the vehicle while ledger gets deleted.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_ledger`$$
CREATE PROCEDURE `delete_ledger`( 
	IN ledgeridparam INT
	, IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
	UPDATE ledger SET 
    isdeleted = 1
    ,updatedby = updatedby
    ,updatedon = updatedon
    WHERE ledgerid = ledgeridparam
    ;
    /* DELETE LEDGER MAPPING WITH VEHICLE*/
    CALL `delete_ledger_veh_mapping`(0,0,ledgeridparam,updatedby,updatedon);    
END$$
DELIMITER ;
