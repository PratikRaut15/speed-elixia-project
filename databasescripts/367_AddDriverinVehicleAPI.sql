ALTER TABLE `dbpatches` ADD `isapplied` TINYINT( 1 ) NOT NULL DEFAULT '1';

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehiclewarehouse_details`$$
CREATE PROCEDURE `get_vehiclewarehouse_details`(
   IN pageIndex INT
   , IN pageSize INT   
   , IN custnoparam INT
   , IN userkeyparam INT
   , IN isWareHouse TINYINT
   , IN searchstring VARCHAR(40)
   , IN groupidparam VARCHAR(250)
)
BEGIN
    DECLARE recordCount INT;
	DECLARE fromRowNum INT DEFAULT 1;
	DECLARE toRowNum INT DEFAULT 1;
    SET searchstring = LTRIM(RTRIM(searchstring));
    IF searchstring = '' THEN
		SET searchstring = NULL;
	END IF;
    IF groupidparam = '0' ||  groupidparam = '' THEN
		SET groupidparam = NULL;
    END IF;
    SET recordCount =  (SELECT 		COUNT(vehicle.vehicleid) 
						FROM 		vehicle 
						INNER JOIN 	devices ON devices.uid = vehicle.uid 
						INNER JOIN 	driver ON driver.driverid = vehicle.driverid 
						INNER JOIN 	unit ON devices.uid = unit.uid 
						INNER JOIN 	customer ON customer.customerno = vehicle.customerno 
						INNER JOIN 	ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid 
						INNER JOIN 	user ON vehicle.customerno = user.customerno     
						INNER JOIN 	simcard on simcard.id = devices.simcardid
						WHERE 		vehicle.customerno = custnoparam 
						AND 		user.userkey = userkeyparam 
						AND 		unit.trans_statusid NOT IN (10,22) 
						AND			vehicle.isdeleted = 0 
						AND 		driver.isdeleted = 0 
						AND 		devices.lastupdated <> '0000-00-00 00:00:00' 
                        AND 		(
										CASE 
											WHEN isWareHouse = 1 THEN vehicle.kind ='Warehouse'
											ELSE vehicle.kind !='Warehouse' 
										END
									)
						AND 		(vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                        AND			(FIND_IN_SET(vehicle.groupid, groupidparam) OR groupidparam IS NULL)
					   );
                        
	/* If pageSize is -1, it means we need to give all the records in a single page */
	IF (pageSize = -1) THEN
		SET pageSize = recordCount;
    END IF;
    
	SET fromRowNum = (pageIndex - 1) * pageSize + 1;
	SET toRowNum = (fromRowNum + pageSize) - 1;
	SET @rownum = 0;

	SELECT	*, recordCount
	FROM 	(SELECT  @rownum:=@rownum + 1 AS rownum, vehDetails.*
			 FROM	(SELECT 	vehicle.kind
								, vehicle.groupid
								, vehicle.groupid as veh_grpid
								, vehicle.vehicleid
								, vehicle.overspeed_limit
								, vehicle.extbatt
								, vehicle.vehicleno
								, vehicle.curspeed
								, vehicle.temp1_min
								, vehicle.temp1_max
								, vehicle.temp2_min
								, vehicle.temp2_max
								, vehicle.temp3_min
								, vehicle.temp3_max
								, vehicle.temp4_min
								, vehicle.temp4_max
								, vehicle.sequenceno
								, vehicle.stoppage_flag
								, devices.lastupdated
								, devices.ignition
								, devices.inbatt
								, devices.tamper
								, devices.powercut
								, devices.gsmstrength
								, devices.devicelat
								, devices.devicelong
                                , devices.directionchange
								, driver.drivername
								, driver.driverphone
								, unit.tempsen1
								, unit.tempsen2
								, unit.tempsen3
								, unit.tempsen4
								, unit.analog1
								, unit.analog2
								, unit.analog3
								, unit.analog4
								, unit.unitno
								, unit.digitalio
								, unit.acsensor
								, unit.is_ac_opp
                                , unit.is_freeze
								, customer.temp_sensors
								, customer.use_humidity
								, customer.use_geolocation
								, user.customerno as customer_no
								, simcardid
								, simcardno
								, (SELECT customname 
									FROM customfield 
									WHERE customerno=user.customerno 
									AND LTRIM(RTRIM(name)) = 'Digital'
									AND usecustom = 1) AS digital
								, ignitionalert.status AS igstatus
								, ignitionalert.ignchgtime
					FROM 		vehicle 
					INNER JOIN 	devices ON devices.uid = vehicle.uid 
					INNER JOIN 	driver ON driver.driverid = vehicle.driverid 
					INNER JOIN 	unit ON devices.uid = unit.uid 
					INNER JOIN 	customer ON customer.customerno = vehicle.customerno 
					INNER JOIN 	ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid 
					INNER JOIN 	user ON vehicle.customerno = user.customerno     
					INNER JOIN 	simcard on simcard.id = devices.simcardid
					WHERE 		vehicle.customerno = custnoparam 
					AND 		user.userkey = userkeyparam 
					AND 		unit.trans_statusid NOT IN (10,22) 
					AND			vehicle.isdeleted = 0 
					AND 		driver.isdeleted = 0 
					AND 		devices.lastupdated <> '0000-00-00 00:00:00'
					AND 		(
									CASE 
										WHEN isWareHouse = 1 THEN vehicle.kind = 'Warehouse'
										ELSE vehicle.kind != 'Warehouse' 
									END
								)
					AND 		(vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
					AND			(FIND_IN_SET(vehicle.groupid, groupidparam) OR groupidparam IS NULL)
					ORDER BY	CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END,
								vehicle.sequenceno ASC, 
								vehicle.vehicleno ASC
					) vehDetails
			) vehDetails
            
	WHERE		rownum BETWEEN fromRowNum AND toRowNum
    ORDER BY 	rownum;

END$$
DELIMITER ;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 367, NOW(), 'Mrudang Vora','Added Driver Name for Veh api');
 
UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 367;

