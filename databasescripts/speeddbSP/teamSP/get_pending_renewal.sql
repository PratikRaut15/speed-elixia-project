/*
    Name		-	get_pending_invoices
    Description 	-	get All pending invoices of customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_pending_invoices('3');
    Created by		-	Arvind
    Created on		- 	13 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_pending_renewal`$$
CREATE PROCEDURE `get_pending_renewal`(
        IN customernos INT,
        IN startdate date,
        IN enddate date
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;

SELECT vehicle.vehicleno FROM vehicle 
INNER JOIN devices ON devices.uid = vehicle.uid 
INNER JOIN driver ON driver.driverid = vehicle.driverid 
INNER JOIN unit ON devices.uid = unit.uid 
LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
WHERE vehicle.isdeleted= 0 AND (devices.expirydate BETWEEN startdate AND enddate) AND unit.customerno NOT IN (-1,1) AND unit.customerno=customernos AND devices.expirydate !='1970-01-01' AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(23,24,10)
ORDER BY vehicle.vehicleno
;     
END$$
DELIMITER ;
