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
DROP PROCEDURE IF EXISTS `get_all_vehicleid_for_customer`$$
CREATE PROCEDURE `get_all_vehicleid_for_customer`(
        IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;
SELECT v.vehicleid
    ,v.vehicleno
    FROM vehicle as v
WHERE v.customerno=customernos AND v.isdeleted=0
ORDER BY v.vehicleid
;     
END$$
DELIMITER ;
