
/*
    Name		-	get_will_expire_devices
    Description 	-	get Devices that will expire in 15 days of customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_expired_devices(3,'2016-09-16','2016-10-01');
    Created by		-	Arvind
    Created on		- 	16 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_will_expire_devices`$$
CREATE PROCEDURE `get_will_expire_devices`(
        IN customernos INT,
        IN today date,
        IN enddate date
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;
    IF(today = '' OR today = '0') THEN
            SET today = NULL;
    END IF;
    IF(enddate = '' OR enddate = '0') THEN
            SET enddate = NULL;
    END IF;
SELECT unit.unitno FROM vehicle 
           INNER JOIN devices ON devices.uid = vehicle.uid INNER JOIN driver ON driver.driverid = vehicle.driverid 
           INNER JOIN unit ON devices.uid = unit.uid LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
           WHERE vehicle.isdeleted= 0 AND (devices.expirydate BETWEEN today AND enddate) AND unit.customerno=customernos AND unit.customerno NOT IN (-1,1) AND devices.expirydate !='1970-01-01' AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(23,24,10)
;  
END$$
DELIMITER ;
