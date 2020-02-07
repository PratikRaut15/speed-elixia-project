/**
Change details 	-
    1)	Updated by	-   Sanjeet
		Updated	on	- 	30 August, 2018
        Reason		-	Get Vehicle Data from Unit No
		call		-	CALL get_vehicle_data_from_unit_no('MP07HB1809','D613206','613');

*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehicle_data_from_unit_no`$$
CREATE PROCEDURE `get_vehicle_data_from_unit_no`(
    IN vehicleNoParam VARCHAR(40)
	, IN unitNoParam VARCHAR(16)
	, IN customerNoParam INT
)
BEGIN
DECLARE varUid INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        
        /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
    END;
   SET varUid = 0; 
    IF unitNoParam != '' THEN
		SET unitNoParam = REPLACE(unitNoParam, ' ','');
	    SELECT 	uid
	    INTO 	varUid
	    FROM 	unit
	    WHERE 	REPLACE(unitno, ' ','') = unitNoParam
	    AND 	customerno = customerNoParam
	    LIMIT 	1;
	ELSEIF unitNoParam = '' AND vehicleNoParam != '' THEN
		SET vehicleNoParam = REPLACE(vehicleNoParam, ' ','');
	    SELECT 	uid
	    INTO 	varUid
	    FROM 	vehicle
	    WHERE 	REPLACE(vehicleno, ' ','') = vehicleNoParam
	    AND 	customerno = customerNoParam
	    AND 	isdeleted = 0
	    LIMIT 	1;
	END IF;
    
    
    START TRANSACTION;
        IF (customerNoParam = 0) THEN
            SET customerNoParam = NULL;
        END IF;
        IF (vehicleNoParam = '') THEN
            SET vehicleNoParam = NULL;
        END IF;
        IF (unitNoParam = '') THEN
            SET unitNoParam = NULL;
        END IF;
        IF (customerNoParam IS NOT NULL AND varUid <> 0) THEN
        
            SELECT 		u.uid, u.unitno, v.vehicleid, d.deviceid, dr.driverid
			FROM		unit u
			INNER JOIN	devices d on d.uid = u.uid
			INNER JOIN	vehicle v on v.uid = u.uid
			INNER JOIN	driver dr on dr.vehicleid = v.vehicleid
			WHERE		u.uid = varUid;

        END IF;
    COMMIT;
END$$
DELIMITER ;
-- CALL get_vehicle_data_from_unit_no('MP07HB1809','D613206','613');