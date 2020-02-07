/*
        Name            -   get_rtddashboard_details
        Description     -   To get vehicles / warehouses for API
        Parameters      -   pageIndex - Any integer is accepted
                            , pageSize - If it is -1, SP would return all the records
                            , custnoparam - Customer number
                            , userkeyparam
                            , isWareHouse - 0 : Vehicle, 1 :Warehouse
                            , searchstring - Blank would return all results, else filter the result accordingly
                            , groupidparam - numeric string '1' . It may be comma-seperated in case of viewer role '1,2,3'
                            , loginType - 1:Normal User , 2 : clientCode , 3: Custom Role User
        Module          -   VTS / Warehouse Mobile App
        Sub-Modules     -
        Sample Call     -   CALL get_rtddashboard_details(1, 10, 116, 0, '', '', 1413506292, 0);
        Created by      -   Shrikant
        Created on      -   08 Sept, 2018


*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_rtddashboard_details`$$
CREATE PROCEDURE `get_rtddashboard_details`(
     IN pageIndex INT
     , IN pageSize INT
     , IN custnoparam INT
     , IN isWareHouse TINYINT
     , IN searchstring VARCHAR(40)
     , IN groupidparam VARCHAR(250)
     , IN userkeyparam BIGINT
     , IN isRequiredThirdPartyParam INT
     , IN loginTypeParam INT
     , IN ecodeIdParam INT
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
    IF loginTypeParam = 0 THEN
        SET loginTypeParam = 1;
    END IF;
    IF ecodeIdParam = '0' ||  ecodeIdParam = '' THEN
        SET ecodeIdParam = NULL;
    END IF;

    IF (userkeyparam IS NOT NULL) then
        SELECT roleid,userid INTO roleIdparam ,useridparam
        FROM user where user.userkey = userkeyparam
        AND customerno = custnoparam;
    END IF;


    IF (loginTypeParam  = 2) THEN
        SET recordCount =  (
            SELECT  COUNT(vehicle.vehicleid)
            FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            INNER JOIN customer ON customer.customerno = ecodeman.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = ecodeman.vehicleid
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = vehicle.customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
            WHERE (ecodeman.customerno =custnoparam )
            AND ecodeman.ecodeid=ecodeIdParam AND unit.trans_statusid NOT IN (10,22)
            AND ecodeman.isdeleted=0
            ORDER BY
                CASE WHEN vehicle.sequenceno = 0 THEN
                    1
                ELSE
                    0
                END ASC,
            ecodeman.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC
        );


        IF (pageSize = -1) THEN
            SET pageSize = recordCount;
        END IF;

        SET fromRowNum = (pageIndex - 1) * pageSize + 1;
        SET toRowNum = (fromRowNum + pageSize) - 1;
        SET @rownum = 0;



        SELECT  *, recordCount
        FROM  (SELECT  @rownum:=@rownum + 1 AS rownum, vehDetails.*
            FROM (SELECT
                vehicle.sequenceno
                , vehicle.ignition_wirecut
                , vehicle.vehicleid
                , vehicle.curspeed
                , vehicle.customerno
                , vehicle.overspeed_limit
                , vehicle.stoppage_transit_time
                , driver.drivername
                , vehicle.temp1_min
                , vehicle.temp1_max
                , vehicle.temp2_min
                , vehicle.temp2_max
                , vehicle.temp3_min
                , vehicle.temp3_max
                , vehicle.temp4_min
                , vehicle.temp4_max
                , devices.devicelat
                , devices.devicelong
                , vehicle.groupid
                , vehicle.extbatt
                , devices.ignition
                , devices.status
                , unit.is_buzzer
                , unit.is_freeze
                , vehicle.stoppage_flag
                , devices.directionchange
                , customer.use_geolocation
                , unit.acsensor
                , vehicle.vehicleno
                , unit.unitno
                , devices.tamper
                , devices.powercut
                , devices.inbatt
                , unit.analog1
                , unit.analog2
                , unit.analog3
                , unit.analog4
                , unit.get_conversion
                , unit.digitalioupdated
                , unit.digitalio
                , unit.is_door_opp
                , devices.gsmstrength
                , devices.registeredon
                , driver.driverid
                , driver.driverphone
                , vehicle.kind
                , vehicle.average
                , vehicle.fuelcapacity
                , vehicle.fuel_balance
                , unit.extra_digital
                , customer.use_extradigital
                , unit.extra_digitalioupdated
                , unit.tempsen1
                , unit.tempsen2
                , unit.tempsen3
                , unit.tempsen4
                , unit.humidity
                , unit.is_mobiliser
                , unit.mobiliser_flag
                , unit.command
                , devices.deviceid
                , devices.lastupdated
                , unit.is_ac_opp
                , unit.msgkey
                , ignitionalert.status as igstatus
                , ignitionalert.ignchgtime
                , g1.gensetno as genset1
                , g2.gensetno as genset2
                , t1.transmitterno as transmitter1
                , t2.transmitterno as transmitter2
                , unit.setcom
                , vehicle.temp1_mute
                , vehicle.temp2_mute
                , vehicle.temp3_mute
                , vehicle.temp4_mute
                , unit.extra2_digitalioupdated
                , unit.door_digitalioupdated
                , checkpoint.cname
                , vehicle.chkpoint_status
                , devices.gpsfixed
                , vehicle.checkpoint_timestamp
                , vehicle.routeDirection
                , vehicle.checkpointId
                , unit.door_digitalio
                , unit.isDoorExt
            FROM ecodeman
            INNER JOIN vehicle ON vehicle.vehicleid = ecodeman.vehicleid
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            INNER JOIN customer ON customer.customerno = ecodeman.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = ecodeman.vehicleid
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = vehicle.customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
            WHERE (ecodeman.customerno =custnoparam )
            AND ecodeman.ecodeid=ecodeIdParam AND unit.trans_statusid NOT IN (10,22)
            AND ecodeman.isdeleted=0
            ORDER BY
                CASE WHEN vehicle.sequenceno = 0 THEN
                    1
                ELSE
                    0
                END ASC,
            ecodeman.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC
            ) vehDetails
        ) vehDetails
        WHERE   rownum BETWEEN fromRowNum AND toRowNum
        ORDER BY  rownum;

    ELSEIF(loginTypeParam = 3)THEN

        SET recordCount =  (
            SELECT    COUNT(vehicle.vehicleid)
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = useridparam
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = vehicle.customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
            WHERE (vehicle.customerno = custnoparam)
            AND unit.trans_statusid NOT IN (10,22)
            AND vehicle.isdeleted=0
            AND vehmap.isdeleted = 0
            AND driver.isdeleted = 0
            AND     (
                  CASE
                      WHEN isWareHouse = 1 THEN vehicle.kind ='Warehouse'
                      ELSE vehicle.kind !='Warehouse'
                  END
              )
            AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
            AND     (FIND_IN_SET(vehmap.groupid, groupidparam) OR groupidparam IS NULL)
            AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
            ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC
            );


        IF (pageSize = -1) THEN
            SET pageSize = recordCount;
        END IF;

        SET fromRowNum = (pageIndex - 1) * pageSize + 1;
        SET toRowNum = (fromRowNum + pageSize) - 1;
        SET @rownum = 0;

        SELECT  *, recordCount
        FROM  (SELECT  @rownum:=@rownum + 1 AS rownum, vehDetails.*
            FROM (SELECT
               vehicle.sequenceno
                , vehicle.ignition_wirecut
                , vehicle.vehicleid
                , vehicle.curspeed
                , vehicle.customerno
                , vehicle.overspeed_limit
                , vehicle.stoppage_transit_time
                , driver.drivername
                , vehicle.temp1_min
                , vehicle.temp1_max
                , vehicle.temp2_min
                , vehicle.temp2_max
                , vehicle.temp3_min
                , vehicle.temp3_max
                , vehicle.temp4_min
                , vehicle.temp4_max
                , devices.devicelat
                , devices.devicelong
                , vehicle.groupid
                , vehicle.extbatt
                , devices.ignition
                , devices.status
                , unit.is_buzzer
                , unit.is_freeze
                , vehicle.stoppage_flag
                , devices.directionchange
                , customer.use_geolocation
                , unit.acsensor
                , vehicle.vehicleno
                , unit.unitno
                , devices.tamper
                , devices.powercut
                , devices.inbatt
                , unit.analog1
                , unit.analog2
                , unit.analog3
                , unit.analog4
                , unit.get_conversion
                , unit.digitalioupdated
                , unit.digitalio
                , unit.is_door_opp
                , devices.gsmstrength
                , devices.registeredon
                , driver.driverid
                , driver.driverphone
                , vehicle.kind
                , vehicle.average
                , vehicle.fuelcapacity
                , vehicle.fuel_balance
                , unit.extra_digital
                , customer.use_extradigital
                , unit.extra_digitalioupdated
                , unit.tempsen1
                , unit.tempsen2
                , unit.tempsen3
                , unit.tempsen4
                , unit.humidity
                , unit.is_mobiliser
                , unit.mobiliser_flag
                , unit.command
                , devices.deviceid
                , devices.lastupdated
                , unit.is_ac_opp
                , unit.msgkey
                , ignitionalert.status as igstatus
                , ignitionalert.ignchgtime
                , g1.gensetno as genset1
                , g2.gensetno as genset2
                , t1.transmitterno as transmitter1
                , t2.transmitterno as transmitter2
                , unit.setcom
                , vehicle.temp1_mute
                , vehicle.temp2_mute
                , vehicle.temp3_mute
                , vehicle.temp4_mute
                , unit.extra2_digitalioupdated
                , unit.door_digitalioupdated
                , checkpoint.cname
                , vehicle.chkpoint_status
                , devices.gpsfixed
                , vehicle.checkpoint_timestamp
                , vehicle.routeDirection
                , vehicle.checkpointId
                , unit.door_digitalio
                , unit.isDoorExt
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            INNER JOIN vehicleusermapping as vehmap ON vehmap.vehicleid = vehicle.vehicleid and vehmap.userid = useridparam
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = vehicle.customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
            WHERE (vehicle.customerno = custnoparam)
            AND unit.trans_statusid NOT IN (10,22)
            AND vehicle.isdeleted=0
            AND vehmap.isdeleted = 0
            AND driver.isdeleted = 0
            AND     (
                  CASE
                      WHEN isWareHouse = 1 THEN vehicle.kind ='Warehouse'
                      ELSE vehicle.kind !='Warehouse'
                  END
              )
            AND     (vehicle.vehicleno LIKE CONCAT('%', searchstring ,'%') OR searchstring IS NULL)
            AND     (FIND_IN_SET(vehmap.groupid, groupidparam) OR groupidparam IS NULL)
            AND     (unit.isRequiredThirdParty = isRequiredThirdPartyParam OR isRequiredThirdPartyParam IS NULL)
            ORDER BY  CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC
            ) vehDetails
        ) vehDetails
        WHERE   rownum BETWEEN fromRowNum AND toRowNum
        ORDER BY  rownum;


    ELSEIF(loginTypeParam = 1) THEN

        SET recordCount =  (
            SELECT   COUNT(vehicle.vehicleid)
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = vehicle.customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
            WHERE (vehicle.customerno = custnoparam)
            AND unit.trans_statusid NOT IN (10,22)
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
            ORDER BY CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC
        );


        IF (pageSize = -1) THEN
            SET pageSize = recordCount;
            END IF;

        SET fromRowNum = (pageIndex - 1) * pageSize + 1;
        SET toRowNum = (fromRowNum + pageSize) - 1;
        SET @rownum = 0;

        SELECT  *, recordCount
        FROM  (SELECT  @rownum:=@rownum + 1 AS rownum, vehDetails.*
            FROM (SELECT
                vehicle.sequenceno
                , vehicle.ignition_wirecut
                , vehicle.vehicleid
                , vehicle.curspeed
                , vehicle.customerno
                , vehicle.overspeed_limit
                , vehicle.stoppage_transit_time
                , driver.drivername
                , vehicle.temp1_min
                , vehicle.temp1_max
                , vehicle.temp2_min
                , vehicle.temp2_max
                , vehicle.temp3_min
                , vehicle.temp3_max
                , vehicle.temp4_min
                , vehicle.temp4_max
                , devices.devicelat
                , devices.devicelong
                , vehicle.groupid
                , vehicle.extbatt
                , devices.ignition
                , devices.status
                , unit.is_buzzer
                , unit.is_freeze
                , vehicle.stoppage_flag
                , devices.directionchange
                , customer.use_geolocation
                , unit.acsensor
                , vehicle.vehicleno
                , unit.unitno
                , devices.tamper
                , devices.powercut
                , devices.inbatt
                , unit.analog1
                , unit.analog2
                , unit.analog3
                , unit.analog4
                , unit.get_conversion
                , unit.digitalioupdated
                , unit.digitalio
                , unit.is_door_opp
                , devices.gsmstrength
                , devices.registeredon
                , driver.driverid
                , driver.driverphone
                , vehicle.kind
                , vehicle.average
                , vehicle.fuelcapacity
                , vehicle.fuel_balance
                , unit.extra_digital
                , customer.use_extradigital
                , unit.extra_digitalioupdated
                , unit.tempsen1
                , unit.tempsen2
                , unit.tempsen3
                , unit.tempsen4
                , unit.humidity
                , unit.is_mobiliser
                , unit.mobiliser_flag
                , unit.command
                , devices.deviceid
                , devices.lastupdated
                , unit.is_ac_opp
                , unit.msgkey
                , ignitionalert.status as igstatus
                , ignitionalert.ignchgtime
                , g1.gensetno as genset1
                , g2.gensetno as genset2
                , t1.transmitterno as transmitter1
                , t2.transmitterno as transmitter2
                , unit.setcom
                , vehicle.temp1_mute
                , vehicle.temp2_mute
                , vehicle.temp3_mute
                , vehicle.temp4_mute
                , unit.extra2_digitalioupdated
                , unit.door_digitalioupdated
                , checkpoint.cname
                , vehicle.chkpoint_status
                , devices.gpsfixed
                , vehicle.checkpoint_timestamp
                , vehicle.routeDirection
                , vehicle.checkpointId
                , unit.door_digitalio
                , unit.isDoorExt
            FROM vehicle
            INNER JOIN devices ON devices.uid = vehicle.uid
            INNER JOIN driver ON driver.driverid = vehicle.driverid
            INNER JOIN unit ON devices.uid = unit.uid
            INNER JOIN customer ON customer.customerno = vehicle.customerno
            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
            LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = vehicle.customerno
            LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid
            LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid
            LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
            LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
            WHERE (vehicle.customerno = custnoparam)
            AND unit.trans_statusid NOT IN (10,22)
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
            ORDER BY CASE WHEN vehicle.sequenceno = 0 THEN 1 ELSE 0 END ASC, vehicle.customerno, vehicle.sequenceno ASC, devices.lastupdated DESC
            ) vehDetails
        ) vehDetails
        WHERE   rownum BETWEEN fromRowNum AND toRowNum
        ORDER BY  rownum;

    END IF;



END$$
DELIMITER ;


CALL get_rtddashboard_details(1, 10, 116, 0, '', '', 1413506292, 0,1,0);
