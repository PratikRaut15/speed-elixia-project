DELIMITER $$
DROP PROCEDURE IF EXISTS map_vehicle_user_driver$$
CREATE PROCEDURE `map_vehicle_user_driver`(
	IN custnoparam INT
	, IN vehicleidparam INT
	, IN useridparam INT
	, IN driveridparam INT
    , IN todaysdate DATETIME
)
BEGIN
    DECLARE tempVehicleId INT;
    DECLARE tempDriverId INT;
    DECLARE tempGroupId INT;
    DECLARE tempUserId INT;
    
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    
    START TRANSACTION;
		/* Check whether any vehicle has the passed driver assigned */
		SELECT 	vehicleid
		INTO 	tempVehicleId
		FROM	vehicle
		WHERE 	driverid = driveridparam
		AND		customerno = custnoparam
        LIMIT 	1;
		
		 /* If any vehicle has that driver, we need to unmap that driver from the existing vehicle */
		IF (tempVehicleId IS NOT NULL) THEN
			UPDATE 	vehicle
			SET 	driverid = 0
			WHERE	vehicleid = tempVehicleId
            AND		customerno = custnoparam;
		END IF;
		
		/* Assign the driver to passed vehicle param */
		UPDATE 	vehicle
		SET 	driverid = driveridparam
		WHERE	vehicleid = vehicleidparam;
		
        
        /* Check whether any driver is assigned to passed vehicle */
		SELECT 	driverid
		INTO 	tempDriverId
		FROM	driver
		WHERE 	vehicleid = vehicleidparam
		AND		customerno = custnoparam
        LIMIT 	1;
        
         /* If any driver is assigned to that vehicle, we need to unmap that vehicle from the existing driver */
        IF (tempDriverId IS NOT NULL) THEN
			UPDATE 	driver
			SET 	vehicleid = 0
			WHERE	driverid = tempDriverId
			AND		customerno = custnoparam;
		END IF;
        
        /* Assign the vehicle in driver table */
        UPDATE 	driver
		SET 	vehicleid = vehicleidparam
		WHERE	driverid = driveridparam;
        
		/* 
			Please note that 1 group is related to 1 vehicle. We are mapping the same group to user.
			Hence, we are making the relationship between vehicle and user
		*/
		/* Check the group for passed vehicle */
		SELECT 	groupid
		INTO 	tempGroupId
		FROM	vehicle
		WHERE	vehicleid = vehicleidparam
		AND		customerno = custnoparam;
		
		 /* Check whether any existing user has the group */
		IF(tempGroupId IS NOT NULL AND tempGroupId != 0) THEN
			SELECT	userid
			INTO	tempUserId
			FROM	groupman
			WHERE 	groupid = tempGroupId
			AND		customerno = custnoparam
			AND 	isdeleted = 0;
			
			 /* If any user has the group, we need to unmap that group from the existing user */
			IF (tempUserId IS NOT NULL) THEN
				UPDATE 	groupman
				SET 	isdeleted = 1
						, timestamp = todaysdate
				WHERE	userid = tempUserId
				AND 	isdeleted = 0;
			END IF;
			
			/* Assign the group to passed user param */
			INSERT INTO groupman
								(
									groupid
									,vehicleid
									,customerno
									,userid
									,isdeleted
									,timestamp
								)
			VALUES
					(
						tempGroupId
						,vehicleidparam
						,custnoparam
						,useridparam
						,0
						,todaysdate
					);
		END IF;
		
		SELECT 	user.realname AS username
				, user.email AS useremail
				, user.phone AS userphone
				, vehicle.vehicleno AS vehicleno
				, driver.drivername AS drivername
				, driver.driverphone AS driverphone
		FROM	vehicle 
		INNER JOIN	driver 	ON 	vehicle.driverid = driver.driverid
		INNER JOIN	groupman ON groupman.groupid = vehicle.groupid
		INNER JOIN	user	ON	user.userid = groupman.userid
		WHERE	vehicle.vehicleid = vehicleidparam
		AND 	user.userid = useridparam
		AND 	driver.driverid = driveridparam
		AND 	vehicle.customerno = custnoparam
		AND		user.isdeleted = 0
		AND		driver.isdeleted = 0
        AND 	groupman.isdeleted = 0;
    
    COMMIT;
    
    
END$$
DELIMITER ;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (329, NOW(), 'Mrudang Vora','Modified SP to demap passed vehicles in driver table ');