SET @patchId = 741;
SET @patchDate = '2019-12-23 14:11:00';
SET @patchOwner = 'Arvind Thakur';
SET @patchDescription = 'sort data by lastupdated change';

INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

DELIMITER $$
DROP PROCEDURE `get_vehiclewarehouse_details`$$
CREATE PROCEDURE `get_vehiclewarehouse_details`(
     IN pageIndex INT
     , IN pageSize INT
     , IN custnoparam INT
     , IN isWareHouse TINYINT
     , IN searchstring VARCHAR(40)
     , IN groupidparam VARCHAR(250)
     , IN userkeyparam BIGINT
     , IN isRequiredThirdPartyParam INT 
     , IN `consigneeidParam` INT
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
    IF consigneeidParam = 0 THEN
        SET consigneeidParam = NULL;
    END IF;

    IF (userkeyparam IS NOT NULL) then
        SELECT  roleid
                ,userid 
        INTO    roleIdparam 
                ,useridparam
        FROM    `user` 
        where   `user`.userkey = userkeyparam
        AND     customerno = custnoparam;
    END IF;

    IF(consigneeidParam IS NOT NULL) THEN
    /* consignee query starts here */
        SET recordCount = ( SELECT  COUNT(vehicle.vehicleid)
                            FROM    vehicle
                            INNER JOIN tripdetails ON tripdetails.vehicleid = vehicle.vehicleid /*AND tripdetails.is_tripend = 0 AND tripdetails.tripstatusid NOT IN(9,10) AND tripdetails.consigneeid= consigneeidParam AND tripdetails.isdeleted=0 AND tripdetails.tripstatusid=4*/
                            INNER JOIN  devices ON devices.uid = vehicle.uid
                            INNER JOIN  driver ON driver.driverid = vehicle.driverid
                            INNER JOIN  unit ON devices.uid = unit.uid
                            INNER JOIN  customer ON customer.customerno = vehicle.customerno
                            INNER JOIN  ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                            LEFT OUTER JOIN   simcard on simcard.id = devices.simcardid
                            /*INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = useridparam */
                            WHERE   vehicle.customerno = custnoparam
                            AND     unit.trans_statusid NOT IN (10,22)
                            AND     vehicle.isdeleted = 0
                            AND     driver.isdeleted = 0
                            AND     tripdetails.tripstatusid=4 
                            AND     tripdetails.is_tripend = 0 
                            AND     tripdetails.tripstatusid NOT IN(9,10) 
                            AND     tripdetails.`consigneeid`= consigneeidParam  
                            AND     tripdetails.isdeleted=0 
                            AND     (CASE
                                        WHEN isWareHouse = 1 THEN vehicle.kind ='Warehouse'
                                        ELSE vehicle.kind !='Warehouse'
                                    END)
                            AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                            /*AND     (FIND_IN_SET(vehmap.groupid, groupidparam) OR groupidparam IS NULL)*/
                            AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
                            /*AND     vehmap.isdeleted = 0*/
                              /* GROUP BY vehicle.vehicleid */
                             );


        IF (pageSize = -1) THEN
            SET pageSize = recordCount;
        END IF;

        SET fromRowNum = (pageIndex - 1) * pageSize + 1;
        SET toRowNum = (fromRowNum + pageSize) - 1;
        SET @rownum = 0;

        SELECT  *
                , recordCount
        FROM    (SELECT @rownum:=@rownum + 1 AS rownum
                        , vehDetails.*
                FROM    (SELECT vehicle.kind
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
                                ,vehicle.routeDirection
                                , simcardid
                                , simcardno
                                , ( SELECT  customname
                                    FROM    customfield
                                    WHERE   customerno = vehicle.customerno
                                    AND     LTRIM(RTRIM(name)) = 'Digital'
                                    AND     usecustom = 1) AS digital
                                , ignitionalert.status AS igstatus
                                , ignitionalert.ignchgtime
                                , vehicle.stoppage_transit_time
                        FROM    vehicle
                        INNER JOIN tripdetails ON tripdetails.vehicleid = vehicle.vehicleid /*AND tripdetails.is_tripend = 0 AND tripdetails.tripstatusid NOT IN(9,10) AND tripdetails.consigneeid= consigneeidParam AND tripdetails.isdeleted=0 AND tripdetails.tripstatusid=4*/
                        INNER JOIN  devices ON devices.uid = vehicle.uid
                        INNER JOIN  driver ON driver.driverid = vehicle.driverid
                        INNER JOIN  unit ON devices.uid = unit.uid
                        INNER JOIN  customer ON customer.customerno = vehicle.customerno
                        INNER JOIN  ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                        LEFT OUTER JOIN   simcard on simcard.id = devices.simcardid
                        /*INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = useridparam*/
                        WHERE   vehicle.customerno = custnoparam
                        AND     unit.trans_statusid NOT IN (10,22)
                        AND     vehicle.isdeleted = 0
                        AND     driver.isdeleted = 0
                        AND     tripdetails.tripstatusid=4 
                        AND     tripdetails.is_tripend = 0 
                        AND     tripdetails.tripstatusid NOT IN(9,10) 
                        AND     tripdetails.`consigneeid`= consigneeidParam 
                        AND     tripdetails.isdeleted=0 
                        AND     (CASE
                                    WHEN isWareHouse = 1 THEN vehicle.kind = 'Warehouse'
                                    ELSE vehicle.kind != 'Warehouse'
                                END)
                        AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                        /*AND     (FIND_IN_SET(vehmap.groupid, groupidparam) OR groupidparam IS NULL) */
                        AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
                        /*AND     vehmap.isdeleted = 0 */
                        GROUP BY vehicle.vehicleid
                        ORDER BY  vehicle.sequenceno = 0,vehicle.sequenceno ASC,
                                    devices.lastupdated DESC) vehDetails
                ) vehDetails

        WHERE   rownum BETWEEN fromRowNum AND toRowNum
        ORDER BY  rownum; 
    /* consignee query ends here */
       /*select userid from `user` limit 1; */
    ELSE

        IF  EXISTS (SELECT  vu.userid 
                    FROM    vehicleusermapping as vu 
                    WHERE   vu.userid = useridparam 
                    AND     vu.customerno = custnoparam 
                    AND     vu.isdeleted = 0) OR (roleIdparam = 43) THEN
        
            SET recordCount =  (SELECT  COUNT(vehicle.vehicleid)
                                FROM    vehicle
                                INNER JOIN  devices ON devices.uid = vehicle.uid
                                INNER JOIN  driver ON driver.driverid = vehicle.driverid
                                INNER JOIN  unit ON devices.uid = unit.uid
                                INNER JOIN  customer ON customer.customerno = vehicle.customerno
                                INNER JOIN  ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                                LEFT OUTER JOIN   simcard on simcard.id = devices.simcardid
                                INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = useridparam
                                WHERE   vehicle.customerno = custnoparam
                                AND     unit.trans_statusid NOT IN (10,22)
                                AND     vehicle.isdeleted = 0
                                AND     driver.isdeleted = 0
                                AND     (CASE
                                            WHEN isWareHouse = 1 THEN vehicle.kind ='Warehouse'
                                            ELSE vehicle.kind !='Warehouse'
                                        END)
                                AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                                AND     (FIND_IN_SET(vehmap.groupid, groupidparam) OR groupidparam IS NULL)
                                AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
                                AND     vehmap.isdeleted = 0);


            IF (pageSize = -1) THEN
                SET pageSize = recordCount;
            END IF;

            SET fromRowNum = (pageIndex - 1) * pageSize + 1;
            SET toRowNum = (fromRowNum + pageSize) - 1;
            SET @rownum = 0;

            SELECT  *
                    , recordCount
            FROM    (SELECT @rownum:=@rownum + 1 AS rownum
                            , vehDetails.*
                     FROM   (SELECT vehicle.kind
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
                                    , vehicle.routeDirection
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
                            FROM    vehicle
                            INNER JOIN  devices ON devices.uid = vehicle.uid
                            INNER JOIN  driver ON driver.driverid = vehicle.driverid
                            INNER JOIN  unit ON devices.uid = unit.uid
                            INNER JOIN  customer ON customer.customerno = vehicle.customerno
                            INNER JOIN  ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                            LEFT OUTER JOIN   simcard on simcard.id = devices.simcardid
                            INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = useridparam
                            WHERE   vehicle.customerno = custnoparam
                            AND     unit.trans_statusid NOT IN (10,22)
                            AND     vehicle.isdeleted = 0
                            AND     driver.isdeleted = 0
                            AND     (CASE
                                        WHEN isWareHouse = 1 THEN vehicle.kind = 'Warehouse'
                                        ELSE vehicle.kind != 'Warehouse'
                                    END)
                            AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                            AND     (FIND_IN_SET(vehmap.groupid, groupidparam) OR groupidparam IS NULL)
                            AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
                            AND     vehmap.isdeleted = 0
                            ORDER BY  vehicle.sequenceno = 0,vehicle.sequenceno ASC,
                                    devices.lastupdated DESC) vehDetails
                    ) vehDetails

            WHERE   rownum BETWEEN fromRowNum AND toRowNum
            ORDER BY    rownum;

        ELSE

            SET recordCount = ( SELECT  COUNT(vehicle.vehicleid)
                                FROM    vehicle
                                INNER JOIN  devices ON devices.uid = vehicle.uid
                                INNER JOIN  driver ON driver.driverid = vehicle.driverid
                                INNER JOIN  unit ON devices.uid = unit.uid
                                INNER JOIN  customer ON customer.customerno = vehicle.customerno
                                INNER JOIN  ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                                LEFT OUTER JOIN   simcard on simcard.id = devices.simcardid
                                WHERE   vehicle.customerno = custnoparam
                                AND     unit.trans_statusid NOT IN (10,22)
                                AND     vehicle.isdeleted = 0
                                AND     driver.isdeleted = 0
                                AND     (CASE
                                            WHEN isWareHouse = 1 THEN vehicle.kind ='Warehouse'
                                            ELSE vehicle.kind !='Warehouse'
                                        END)
                                AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                                AND     (FIND_IN_SET(vehicle.groupid, groupidparam) OR groupidparam IS NULL)
                                AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL));


            IF (pageSize = -1) THEN
                SET pageSize = recordCount;
            END IF;

            SET fromRowNum = (pageIndex - 1) * pageSize + 1;
            SET toRowNum = (fromRowNum + pageSize) - 1;
            SET @rownum = 0;

            SELECT  *
                    , recordCount
            FROM    (SELECT @rownum:=@rownum + 1 AS rownum
                            , vehDetails.*
                     FROM   (SELECT vehicle.kind
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
                                    , vehicle.routeDirection
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
                            FROM    vehicle
                            INNER JOIN  devices ON devices.uid = vehicle.uid
                            INNER JOIN  driver ON driver.driverid = vehicle.driverid
                            INNER JOIN  unit ON devices.uid = unit.uid
                            INNER JOIN  customer ON customer.customerno = vehicle.customerno
                            INNER JOIN  ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                            LEFT OUTER JOIN   simcard on simcard.id = devices.simcardid
                            WHERE   vehicle.customerno = custnoparam
                            AND     unit.trans_statusid NOT IN (10,22)
                            AND     vehicle.isdeleted = 0
                            AND     driver.isdeleted = 0
                            AND     (CASE
                                        WHEN isWareHouse = 1 THEN vehicle.kind = 'Warehouse'
                                        ELSE vehicle.kind != 'Warehouse'
                                    END)
                            AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
                            AND     (FIND_IN_SET(vehicle.groupid, groupidparam) OR groupidparam IS NULL)
                            AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
                            ORDER BY  vehicle.sequenceno = 0,vehicle.sequenceno ASC,
                                        devices.lastupdated DESC) vehDetails
                    ) vehDetails
            WHERE   rownum BETWEEN fromRowNum AND toRowNum
            ORDER BY rownum;

        END IF;
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
                    ORDER BY  vehicle.sequenceno = 0,vehicle.sequenceno ASC,
                                        devices.lastupdated DESC) vehDetails
            ) vehDetails
        WHERE   rownum BETWEEN fromRowNum AND toRowNum
        ORDER BY  rownum;



END$$
DELIMITER ;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
