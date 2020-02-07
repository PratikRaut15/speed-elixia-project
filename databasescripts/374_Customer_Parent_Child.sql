-- Insert SQL here.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'374', '2016-03-22 11:38:30', 'Shrikant Suryawanshi', 'Customer Parent Child Relationship For Vehicles', '0'
);



create table vehiclerelation(
vrid int NOT NULL PRIMARY KEY AUTO_INCREMENT,
vehicleid int NOT NULL,
parent int NOT NULL,
child int NOT NULL,
isdeleted int  Default '0'
);


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehiclewarehouse_details`$$
CREATE PROCEDURE `get_vehiclewarehouse_details`(
   IN pageIndex INT
   , IN pageSize INT   
   , IN custnoparam INT
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
						INNER JOIN 	simcard on simcard.id = devices.simcardid
                        			LEFT JOIN vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child=custnoparam
						WHERE 		(vehicle.customerno = custnoparam OR vehicle.customerno = vr.parent) 
						
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
								, vehicle.customerno as customer_no
								, simcardid
								, simcardno
								, (SELECT customname 
									FROM customfield 
									WHERE customerno=vehicle.customerno 
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
					     
					INNER JOIN 	simcard on simcard.id = devices.simcardid
                    LEFT JOIN   vehiclerelation as vr on vr.vehicleid  = vehicle.vehicleid and vr.child=custnoparam
					WHERE 		(vehicle.customerno = custnoparam OR vehicle.customerno = vr.parent) 
					
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

END $$
DELIMITER ;




INSERT INTO `speed`.`vehiclerelation` (
`vrid` ,
`vehicleid` ,
`parent` ,
`child` ,
`isdeleted`
)
VALUES (
NULL , '5999', '116', '132', '0'
);

INSERT INTO `speed`.`vehiclerelation` (
`vrid` ,
`vehicleid` ,
`parent` ,
`child` ,
`isdeleted`
)
VALUES (
NULL , '6005', '116', '132', '0'
);
INSERT INTO `speed`.`vehiclerelation` (
`vrid` ,
`vehicleid` ,
`parent` ,
`child` ,
`isdeleted`
)
VALUES (
NULL , '6006', '116', '132', '0'
);
INSERT INTO `speed`.`vehiclerelation` (
`vrid` ,
`vehicleid` ,
`parent` ,
`child` ,
`isdeleted`
)
VALUES (
NULL , '6012', '116', '132', '0'
);



-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 374;

