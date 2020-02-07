create index index_customerno on driver(customerno);

create index index_customerno on vehicle(customerno);

create index index_customerno on groupman(customerno);

create index index_customerno on devices(customerno);

create index index_customerno on unit(customerno);

create index index_customerno on `group`(customerno);


DELIMITER $$
DROP PROCEDURE IF EXISTS map_vehicle_user_driver$$
CREATE PROCEDURE `map_vehicle_user_driver`(
	IN custnoparam INT
	, IN vehicleidparam INT
	, IN useridparam VARCHAR(50)
	, IN driveridparam INT
    , IN drivernameparam varchar(40)
    , IN todaysdate DATETIME
)
BEGIN
    DECLARE tempVehicleId INT;
    DECLARE tempDriverId INT;
    DECLARE notAllocatedDriverId INT;
    DECLARE tempGroupId INT;
    DECLARE tempUserId INT;
    DECLARE noOfUsers INT;
    DECLARE tempCount INT;
    
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    IF(driveridparam = 0) THEN
		SET driveridparam = NULL;
	END IF;
    IF(vehicleidparam = 0) THEN
		SET vehicleidparam = NULL;
	END IF;
    IF(useridparam = '') THEN
		SET useridparam = NULL;
	END IF;

    START TRANSACTION;
		IF (driveridparam IS NULL AND drivernameparam != '' ) THEN
			BEGIN
				/* Check if the driver name already exists */
				SELECT	driverid
				INTO	driveridparam
				FROM	driver
				WHERE 	drivername = TRIM(drivernameparam)
                LIMIT 1;
                
				IF driveridparam IS NULL THEN
					BEGIN
						INSERT INTO driver(
										drivername
										,customerno
										)
									values(
										drivernameparam
										,custnoparam
										);
						SET driveridparam = LAST_INSERT_ID();
					END;
				END IF;
			END;
		END IF;
		IF (driveridparam IS NOT NULL AND vehicleidparam IS NOT NULL AND useridparam IS NOT NULL) THEN 
        BEGIN
        
        /* Check whether any vehicle has the passed driver assigned */
		SELECT 	vehicleid
		INTO 	tempVehicleId
		FROM	vehicle
		WHERE 	driverid = driveridparam
		AND		customerno = custnoparam
        LIMIT 	1;
		
        /* Check whether any driver is assigned to passed vehicle */
		SELECT 	driverid
		INTO 	tempDriverId
		FROM	driver
		WHERE 	vehicleid = vehicleidparam
		AND		customerno = custnoparam
        LIMIT 	1;


		/* If any vehicle has that driver, we need to unmap that driver from the existing vehicle */
		IF (tempVehicleId IS NOT NULL) THEN
        BEGIN
            SELECT 	driverid 
            INTO 	notAllocatedDriverId
            FROM 	driver 
            WHERE 	drivername ='Not Allocated' 
            AND 	customerno = custnoparam 
            AND 	vehicleid = 0 
            AND 	isdeleted= 0
            LIMIT 1;
            
            IF(notAllocatedDriverId IS NULL) THEN 
				BEGIN
					INSERT INTO driver(
									drivername
									,customerno
									,vehicleid
									,driverphone
									)
								values(
									'Not Allocated'
									,custnoparam
									,0
									,'8888888888'
									);
					SET notAllocatedDriverId = LAST_INSERT_ID();
				END;
            END IF;
            
            IF(notAllocatedDriverId IS NOT NULL) THEN 
            UPDATE 	vehicle
			SET 	driverid = notAllocatedDriverId
			WHERE	vehicleid = tempVehicleId
            AND		customerno = custnoparam;

            UPDATE 	driver
			SET 	vehicleid = tempVehicleId
			WHERE	driverid = notAllocatedDriverId
            AND		customerno = custnoparam;
            END IF;
  
        END;
		END IF;
		
        /* If any driver is assigned to that vehicle, we need to unmap that vehicle from the existing driver */
        IF (tempDriverId IS NOT NULL) THEN
			UPDATE 	driver
			SET 	vehicleid = 0
			WHERE	driverid = tempDriverId
			AND		customerno = custnoparam;
		END IF;

        /* Assign the driver to passed vehicle param */
		UPDATE 	vehicle
		SET 	driverid = driveridparam
		WHERE	vehicleid = vehicleidparam;
		
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
		AND		customerno = custnoparam
        LIMIT 1;
		
		 /* Check whether any existing user has the group */
		IF(tempGroupId IS NOT NULL AND tempGroupId != 0) THEN
			/* Remove all the users mapped to the group */
			UPDATE 	groupman
			SET 	isdeleted = 1
					, timestamp = todaysdate
			WHERE 	groupid = tempGroupId
			AND		customerno = custnoparam
			AND 	isdeleted = 0;
            
            /* Loop around the each user and insert in groupman table */
			SELECT LENGTH(useridparam) - LENGTH(REPLACE(useridparam, ',', '')) INTO @noOfCommas;
            SET noOfUsers = @noOfCommas + 1;
            SET tempCount = 1;
			WHILE (tempCount  <=  noOfUsers) DO
				SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(useridparam, ',', tempCount), ',', -1 ) INTO @userid;

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
							,@userid
							,0
							,todaysdate
						);
			   SET  tempCount = tempCount + 1;
			END WHILE;
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
		AND 	FIND_IN_SET(user.userid, useridparam)
		AND 	driver.driverid = driveridparam
		AND 	vehicle.customerno = custnoparam
		AND		user.isdeleted = 0
		AND		driver.isdeleted = 0
        AND 	groupman.isdeleted = 0;
        END;
        END IF;
    COMMIT;
END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 341, NOW(), 'Shrikant Suryawanshi','Set Limit 1 for map driver SP select into queries');

