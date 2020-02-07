/*
    Name		-	get_ledger_map_cust
    Description 	-	get Ledger Mapped with Customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_ledger_map_cust('3');
    Created by		-	Arvind
    Created on		- 	13 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_map_cust`$$
CREATE PROCEDURE `get_ledger_map_cust`(
        IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;
SELECT l.ledgerid 
    FROM `ledger_cust_mapping` as l 
    WHERE l.customerno=customernos AND `isdeleted` = 0
;     
END$$
DELIMITER ;


   

