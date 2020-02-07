DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehicles_drivers_users`$$
CREATE PROCEDURE `get_vehicles_drivers_users`(
	IN custnoparam INT
)
BEGIN
	SELECT 		vehicleid
				, vehicleno
    FROM 		vehicle
	INNER JOIN	customer ON customer.customerno = vehicle.customerno
    WHERE		vehicle.customerno = custnoparam
    AND 		vehicle.isdeleted = 0;
    
    SELECT 		driverid
				, drivername
    FROM 		driver
	INNER JOIN 	customer ON customer.customerno = driver.customerno
    WHERE		driver.customerno = custnoparam
    AND 		driver.isdeleted = 0
    AND         driver.drivername <> 'Not Allocated';
    
    SELECT 		userid
				, realname
    FROM 		user
    INNER JOIN 	customer ON customer.customerno = user.customerno
    WHERE		user.customerno = custnoparam
    AND 		LTRIM(RTRIM(user.role)) = 'Viewer'
    AND 		user.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `map_vehicle_user_driver`$$
CREATE PROCEDURE `map_vehicle_user_driver`(
	IN custnoparam INT
	, IN vehicleidparam INT
	, IN useridparam INT
	, IN driveridparam INT
    , IN todaysdate DATETIME
)
BEGIN
    DECLARE tempVehicleId INT;
    DECLARE tempGroupId INT;
    DECLARE tempUserId INT;
    
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    
    START TRANSACTION;
		/* Check whether any vehicle has the driver */
		SELECT 	vehicleid
		INTO 	tempVehicleId
		FROM	vehicle
		WHERE 	driverid = driveridparam
		AND		customerno = custnoparam;
		
		 /* If any vehicle has the driver, we need to unmap that driver from the existing vehicle */
		IF (tempVehicleId IS NOT NULL) THEN
			UPDATE 	vehicle
			SET 	driverid = 0
			WHERE	vehicleid = tempVehicleId;
		END IF;
		
		/* Assign the driver to passed vehicle param */
		UPDATE 	vehicle
		SET 	driverid = driveridparam
		WHERE	vehicleid = vehicleidparam;
		
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

DELIMITER $$
DROP PROCEDURE IF EXISTS check_vehicle_user_mapping$$
CREATE PROCEDURE check_vehicle_user_mapping(
	IN useridparam INT
    , OUT groupidparam INT
    , OUT isUserMappedToVehicle TINYINT(1)
)
BEGIN
			SET isUserMappedToVehicle = 0;
            SET groupidparam = -1;
            
			/* Check whether any existing user has the group */
            SELECT	groupid
            INTO	groupidparam
			FROM	groupman
			WHERE 	userid = useridparam
			AND 	isdeleted = 0;
            
            IF (groupidparam IS NOT NULL) THEN
				SET isUserMappedToVehicle = 1;
			END IF;
END$$
DELIMITER ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (321, NOW(), 'Mrudang Vora','Added SP for JET airways tablet app to map vehicles user and driver');