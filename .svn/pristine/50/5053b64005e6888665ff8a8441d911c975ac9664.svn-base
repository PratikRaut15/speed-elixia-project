SET @patchId = 735;
SET @patchDate = '2019-12-11 14:27:00';
SET @patchOwner = 'Arvind Thakur';
SET @patchDescription = 'vehicle route summary api changes';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

-- This changes has done by ranjeet and is done on LIVE
-- This changes were not available in any patch so we have added this in patch.
alter table `route` 
add column isReverseRoute tinyint(1),
add column parentRouteId INT(11);
--------------------------------------------------------------------
-----------------------END------------------------------------------

ALTER TABLE `routeman`
ADD COLUMN kmFromLastCheckpoint INT DEFAULT NULL AFTER r_etd;


DROP procedure IF EXISTS `fetch_data_for_route_wise_report`;
DELIMITER $$
CREATE  PROCEDURE `fetch_data_for_route_wise_report`(
    IN `routeIdParam` INT
    , IN `vehicleidParam` INT
    , IN `customerNoParam` INT)
    COMMENT 'This routine is used to fetch the data for routewise report'
BEGIN

    DECLARE isReverseRouteVar TINYINT;
    DECLARE isReverseIdVar INT;

    IF (routeIdParam = 0) THEN
        SET routeIdParam = NULL;
    END IF;
    
    IF (vehicleidParam = 0) THEN
        SET vehicleidParam = NULL;
    END IF;

    SELECT  isReverseRoute 
    INTO    isReverseRouteVar
    FROM    route 
    WHERE   routeid = routeIdParam
    LIMIT   1; 

    IF (isReverseRouteVar != '1') THEN

        SELECT  isReverseRouteVar AS isReverseRoute
                ,a.routeid
                , a.routename
                , b.vehicleid
                , d.vehicleno
                , c.checkpointid
                , c.sequence
                , ch.cname
                , c.eta
                , c.etd
                , c.kmFromLastCheckpoint
                , a.routeTat
                , ch.cgeolat
                , ch.cgeolong
        FROM    route AS a
        LEFT JOIN vehiclerouteman AS b ON a.routeid = b.routeid
        LEFT JOIN routeman AS c ON a.routeid = c.routeid
        LEFT JOIN vehicle AS d ON d.vehicleid = b.vehicleid
        LEFT JOIN checkpoint AS ch ON c.checkpointid = ch.checkpointid    
        WHERE   a.isdeleted = 0 
        AND     (a.routeid = routeIdParam OR routeIdParam IS NULL)
        AND     (d.vehicleid = vehicleidParam OR vehicleidParam IS NULL)
        AND     b.isdeleted = 0
        AND     c.isdeleted = 0
        AND     d.isdeleted = 0
        AND     a.customerno = customerNoParam
        AND     d.customerno = customerNoParam
        ORDER BY a.routeid , b.vehicleid,c.sequence; 

    ELSE

        SELECT  parentRouteId 
        INTO    isReverseIdVar
        FROM    route 
        WHERE   routeid = routeIdParam
        LIMIT   1; 
        /*SET @fetchRouteId = (SELECT parentRouteId FROM route WHERE routeid=routeIdParam);	*/
        #SELECT  @fetchRouteId; 
        /* Variable set for checkpointData starts here */

        /* Variable set for checkpointData ends here */
        /* SELECT @checkPointDataBySequence; */
        SELECT  isReverseRouteVar AS isReverseRoute
                , a.routeid
                , a.routename
                , b.vehicleid
                , d.vehicleno
                , c.checkpointid
                , c.sequence
                , c.kmFromLastCheckpoint
                , ch.cname
                , c.eta
                , c.etd
                , a.routeTat
                , ch.cgeolat
                , ch.cgeolong
        FROM    route AS a
        LEFT JOIN vehiclerouteman AS b ON a.routeid = b.routeid
        LEFT JOIN routeman AS c ON a.routeid = c.routeid
        LEFT JOIN vehicle AS d ON d.vehicleid = b.vehicleid
        LEFT JOIN checkpoint AS ch ON c.checkpointid = ch.checkpointid    
        WHERE   a.isdeleted = 0 
        AND     (a.routeid = isReverseIdVar OR isReverseIdVar IS NULL)
        AND     (d.vehicleid = vehicleidParam OR vehicleidParam IS NULL)
        AND     b.isdeleted = 0
        AND     c.isdeleted = 0
        AND     d.isdeleted = 0
        AND     a.customerno = customerNoParam
        AND     d.customerno = customerNoParam
        ORDER BY a.routeid , b.vehicleid,c.sequence; 

    END IF;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_vehiclewarehouse_ecode_details`$$
CREATE PROCEDURE `get_vehiclewarehouse_ecode_details`(
     IN pageIndex INT
     , IN pageSize INT
     , IN custnoparam INT
     , IN isWareHouse TINYINT
     , IN searchstring VARCHAR(40)
     , IN groupidparam VARCHAR(250)
     , IN userkeyparam BIGINT
     , IN isRequiredThirdPartyParam INT
     , IN clientCodeParam INT

)
BEGIN
    DECLARE recordCount INT;
    DECLARE fromRowNum INT DEFAULT 1;
    DECLARE toRowNum INT DEFAULT 1;
    DECLARE roleIdparam INT;
    DECLARE useridparam INT;
        SET searchstring = LTRIM(RTRIM(searchstring));
        IF searchstring = '' THEN
        SET searchstring = NULL;
    END IF;
        IF groupidparam = '0' ||  groupidparam = '' THEN
        SET groupidparam = NULL;
        END IF;
    IF userkeyparam = '0' ||  userkeyparam = '' THEN
        SET userkeyparam = NULL;
        END IF;
    IF isRequiredThirdPartyParam = '0' THEN
        SET isRequiredThirdPartyParam = NULL;
        END IF;




        SET recordCount =  (SELECT    COUNT(vehicle.vehicleid)
                        FROM elixiacode
                        INNER JOIN ecodeman em on em.ecodeid = elixiacode.id
                        INNER JOIN  vehicle  on vehicle.vehicleid = em.vehicleid
                        INNER JOIN  devices ON devices.uid = vehicle.uid
                        INNER JOIN  driver ON driver.driverid = vehicle.driverid
                        INNER JOIN  unit ON devices.uid = unit.uid
                        INNER JOIN  customer ON customer.customerno = vehicle.customerno
                        INNER JOIN  ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                        LEFT OUTER JOIN   simcard on simcard.id = devices.simcardid
                        WHERE   vehicle.customerno = custnoparam
                        AND     elixiacode.ecode = clientCodeParam
                        AND     unit.trans_statusid NOT IN (10,22)
                        AND     vehicle.isdeleted = 0
                        AND     driver.isdeleted = 0
                        AND     (
                                CASE
                                    WHEN isWareHouse = 1 THEN vehicle.kind ='Warehouse'
                                    ELSE vehicle.kind !='Warehouse'
                                END
                                )
                        AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                        AND     (FIND_IN_SET(vehicle.groupid, groupidparam) OR groupidparam IS NULL)
                        AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
                         );


    IF (pageSize = -1) THEN
        SET pageSize = recordCount;
        END IF;

    SET fromRowNum = (pageIndex - 1) * pageSize + 1;
    SET toRowNum = (fromRowNum + pageSize) - 1;
    SET @rownum = 0;

    SELECT  *, recordCount
    FROM  (SELECT  @rownum:=@rownum + 1 AS rownum, vehDetails.*
             FROM (SELECT   vehicle.kind
                                , vehicle.groupid
                                , vehicle.groupid as veh_grpid
                                , vehicle.vehicleid
                                , vehicle.overspeed_limit
                                , vehicle.extbatt
                                , vehicle.vehicleno
                                , vehicle.curspeed
                                , vehicle.odometer
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
                                , vehicle.fueltype
                                , vehicle.fuel_balance
                                , vehicle.fuelcapacity
                                , vehicle.fuel_min_volt
                                , vehicle.fuel_max_volt
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
                                , unit.is_mobiliser
                                , unit.is_buzzer
                                , unit.mobiliser_flag
                                , unit.get_conversion
                                , unit.n1
                                , unit.n2
                                , unit.n3
                                , unit.n4
                                , unit.fuelsensor
                                , unit.humidity
                                , customer.temp_sensors
                                , customer.use_humidity
                                , customer.use_geolocation
                                , customer.customercompany
                                , customer.use_immobiliser
                                , customer.use_freeze
                                , customer.use_buzzer
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
                                , vehicle.stoppage_transit_time
                    FROM elixiacode
                    INNER JOIN ecodeman em on em.ecodeid = elixiacode.id
                    INNER JOIN  vehicle  on vehicle.vehicleid = em.vehicleid
                    INNER JOIN  devices ON devices.uid = vehicle.uid
                    INNER JOIN  driver ON driver.driverid = vehicle.driverid
                    INNER JOIN  unit ON devices.uid = unit.uid
                    INNER JOIN  customer ON customer.customerno = vehicle.customerno
                    INNER JOIN  ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                    LEFT OUTER JOIN   simcard on simcard.id = devices.simcardid
                    WHERE     vehicle.customerno = custnoparam
                    AND     elixiacode.ecode = clientCodeParam
                    AND     unit.trans_statusid NOT IN (10,22)
                    AND     vehicle.isdeleted = 0
                    AND     driver.isdeleted = 0
                    AND     (
                                    CASE
                                        WHEN isWareHouse = 1 THEN vehicle.kind = 'Warehouse'
                                        ELSE vehicle.kind != 'Warehouse'
                                    END
                                )
                    AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                    AND     (FIND_IN_SET(vehicle.groupid, groupidparam) OR groupidparam IS NULL)
                    AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
                    ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END,
                                vehicle.sequenceno ASC,
                                vehicle.vehicleno ASC
                    ) vehDetails
            ) vehDetails
        WHERE   rownum BETWEEN fromRowNum AND toRowNum
        ORDER BY  rownum;



END$$
DELIMITER ;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
