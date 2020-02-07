/*
    Name		-	delete_ledger_cust_mapping`
    Description 	-	Delete Ledger Customerno mapping
    Parameters		-	ledgeridparam INT,updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL delete_ledger_cust_mapping`(1,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_ledger_cust_mapping`$$
CREATE PROCEDURE `delete_ledger_cust_mapping`( 
	IN ledgeridparam INT
	, IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
	UPDATE ledger_cust_mapping SET 
    isdeleted = 1
    ,updatedby = updatedby
    ,updatedon = updatedon
    WHERE ledgerid = ledgeridparam
    ;
END$$
DELIMITER ;
