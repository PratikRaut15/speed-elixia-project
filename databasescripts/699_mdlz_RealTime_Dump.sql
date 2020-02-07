INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`)
VALUES ('699', '2019-03-30 18:13:00', 'Arvind Thakur','Mondelez Realtime Data Dump', '0');

DROP TABLE IF EXISTS `mdlzRealTimeDump`;
CREATE TABLE `mdlzRealTimeDump` (
    `mrtdId` INT PRIMARY KEY AUTO_INCREMENT
    , `shipmentno` VARCHAR(30) DEFAULT NULL
    , `vehicleid` INT NOT NULL
    , `vehicleno` VARCHAR(40) NOT NULL
    , `unitid` INT NOT NULL
    , `unitno` VARCHAR(16) NOT NULL
    , `groupid` INT
    , `groupname` VARCHAR(100)
    , `lat` DECIMAL(9,6)
    , `long` DECIMAL(9,6)
    , `temp1` DECIMAL(6,3)
    , `temp2` DECIMAL(6,3)
    , `kind` VARCHAR(11)
    , `customerno` INT NOT NULL
    , `lastupdated` DATETIME
    , `timestamp` DATETIME NOT NULL);


DELIMITER $$
DROP PROCEDURE IF EXISTS `mdlzDumpVehicleData`$$
CREATE PROCEDURE mdlzDumpVehicleData(
    IN customerListParam VARCHAR(50)
    , IN todaysdateParam DATETIME
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
       /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
    END;

    START TRANSACTION;

        IF (customerListParam <> '') THEN

            INSERT INTO mdlzRealTimeDump (`vehicleid`
                    , `vehicleno`
                    , `unitid`
                    , `unitno`
                    , `groupid`
                    , `groupname`
                    , `lat`
                    , `long`
                    , `temp1`
                    , `temp2`
                    , `kind`
                    , `customerno`
                    , `lastupdated`
                    , `timestamp`)
            SELECT  vehicle.vehicleid
                    , vehicle.vehicleno
                    , unit.uid
                    , unit.unitno
                    , `group`.groupid
                    , `group`.groupname
                    , devices.devicelat
                    , devices.devicelong
                    , CASE  unit.tempsen1
                            WHEN 1 THEN (IF(unit.get_conversion = 1 OR (customer.use_humidity = 1 AND vehicle.kind = 'Warehouse'),ROUND((unit.analog1/100),2),ROUND(((unit.analog1 - 1150) / 4.45), 2)))
                            WHEN 2 THEN (IF(unit.get_conversion = 1 OR (customer.use_humidity = 1 AND vehicle.kind = 'Warehouse'),ROUND((unit.analog2/100),2),ROUND(((unit.analog2 - 1150) / 4.45), 2))) 
                            WHEN 3 THEN (IF(unit.get_conversion = 1 OR (customer.use_humidity = 1 AND vehicle.kind = 'Warehouse'),ROUND((unit.analog3/100),2),ROUND(((unit.analog3 - 1150) / 4.45), 2))) 
                            WHEN 4 THEN (IF(unit.get_conversion = 1 OR (customer.use_humidity = 1 AND vehicle.kind = 'Warehouse'),ROUND((unit.analog4/100),2),ROUND(((unit.analog4 - 1150) / 4.45), 2))) 
                            ELSE NULL
                            END AS temp1
                    , CASE  unit.tempsen2
                            WHEN 1 THEN (IF(unit.get_conversion = 1 OR (customer.use_humidity = 1 AND vehicle.kind = 'Warehouse'),ROUND((unit.analog1/100),2),ROUND(((unit.analog1 - 1150) / 4.45), 2)))
                            WHEN 2 THEN (IF(unit.get_conversion = 1 OR (customer.use_humidity = 1 AND vehicle.kind = 'Warehouse'),ROUND((unit.analog2/100),2),ROUND(((unit.analog2 - 1150) / 4.45), 2))) 
                            WHEN 3 THEN (IF(unit.get_conversion = 1 OR (customer.use_humidity = 1 AND vehicle.kind = 'Warehouse'),ROUND((unit.analog3/100),2),ROUND(((unit.analog3 - 1150) / 4.45), 2))) 
                            WHEN 4 THEN (IF(unit.get_conversion = 1 OR (customer.use_humidity = 1 AND vehicle.kind = 'Warehouse'),ROUND((unit.analog4/100),2),ROUND(((unit.analog4 - 1150) / 4.45), 2))) 
                            ELSE NULL
                            END AS temp2
                    , vehicle.kind
                    , vehicle.customerno
                    , devices.lastupdated
                    , todaysdateParam
            FROM    vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON vehicle.uid = unit.uid
            INNER JOIN customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN `group` ON `group`.groupid = vehicle.groupid AND `group`.isdeleted = 0
            WHERE   FIND_IN_SET(vehicle.customerno, customerListParam)
            AND     unit.trans_statusid NOT IN (10,22) 
            AND     devices.lastupdated > DATE_SUB(todaysdateParam,INTERVAL 15 MINUTE)
            AND     vehicle.isdeleted = 0;

        END IF;
    COMMIT;
END$$
DELIMITER ;


UPDATE  dbpatches
SET     updatedOn = '2019-03-30 18:30:00'
        ,isapplied =1
WHERE   patchid = 699;