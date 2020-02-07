/*
    Name		-	get_all_vehicleid_for_customer
    Description 	-	get All vehicleids for customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_all_vehicleid_for_customer('3');
    Created by		-	Arvind
    Created on		- 	12 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_for_vehicle_id`$$
CREATE PROCEDURE `get_ledger_for_vehicle_id`(
        IN vehicleids INT
)
BEGIN
    IF(vehicleids = '' OR vehicleids = '0') THEN
		SET vehicleids = NULL;
	END IF;

SELECT l.ledgerid  
    FROM `ledger_veh_mapping` as l
    WHERE l.vehicleid=vehicleids 
    AND l.isdeleted=0
;     
END$$
DELIMITER ;
