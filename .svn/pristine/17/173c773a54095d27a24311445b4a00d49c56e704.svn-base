/*
    Name		-	get_vehicle number by vehicleid
    Parameters	-	
    Module		-	Cron
    Created by	-  Manasvi
    Created on		- 30 Aug 2018 	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehicle_number_by_vehicleid`$$
CREATE PROCEDURE `get_vehicle_number_by_vehicleid`(
        IN vehicleidParam INT,
        IN customernoParam INT
    )
BEGIN
    IF(customernoParam = '' OR customernoParam = '0') THEN
		SET customernoParam = NULL;
	END IF;
SELECT v.vehicleNo
    FROM vehicle as v
WHERE v.customerno=customernoParam AND v.vehicleid =vehicleidParam  AND v.isdeleted=0;     
END$$
DELIMITER ;

CALL get_vehicle_number_by_vehicleid(8940,64);
