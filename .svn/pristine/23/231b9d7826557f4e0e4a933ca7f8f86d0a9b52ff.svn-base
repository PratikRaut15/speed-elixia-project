/*
	Name            -   listener_insert_dailyreport
	Description     -
	Parameters      -
	Module          -   Speed
	Sub-Modules     -   JSON Listener
	Sample Call     -   CALL listener_insert_dailyreport(, @isInserted);
						SELECT @isInserted;
	Created by      -   Mrudang Vora
	Created on      -   21 Sep, 2019
	Change details  -
	1)  Updated by  -   
		Updated on  -   
		Reason      - 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `listener_insert_dailyreport`$$
CREATE PROCEDURE `listener_insert_dailyreport`(
		last_online_updatedParam DATETIME
		, customernoParam INT
		, vehicleidParam INT
		, uidParam INT
		, odometerParam BIGINT
		, deviceLatParam DECIMAL(9,6)
		, deviceLongParam DECIMAL(9,6)
		, daily_dateParam DATE
		, driveridParam INT
        , OUT isInserted TINYINT(1)
)
BEGIN
		BEGIN
			ROLLBACK;
			/*
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
			@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
			SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
			SELECT @full_error;
			*/
			SET isInserted = 0;
		END;
        START TRANSACTION;
			INSERT INTO dailyreport 
					(
					last_online_updated
					, customerno
					, vehicleid
					, uid
					, harsh_break
					, sudden_acc
					, towing
					, flag_harsh_break
					, flag_sudden_acc
					, flag_towing
					, first_odometer
					, last_odometer
					, overspeed
					, fenceconflict
					, acusage
					, runningtime
					, first_lat
					, first_long
					, average_distance
					, topspeed
					, topspeed_lat
					, topspeed_long
					, topspeed_time
					, night_first_odometer
					, weekend_first_odometer
					, idleignitiontime
					, daily_date
					, driverid
					) 
			VALUES
					(
						last_online_updatedParam
						, customernoParam
						, vehicleidParam
						, uidParam
						, 0
						, 0
						, 0
						, 0
						, 0
						, 0
						, odometerParam
						, odometerParam
						, 0
						, 0
						, 0
						, 0
						, deviceLatParam
						, deviceLongParam
						, 0
						, 0
						, deviceLatParam
						, deviceLongParam
						, NULL
						, 0
						, 0
						, 0
						, daily_dateParam
						, driveridParam
					);
			SET isInserted = 1;
		COMMIT;
END$$
DELIMITER ;
