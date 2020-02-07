INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('635', '2018-11-14 11:30:00', 'Yash Kanakia', 'Add Customer,User and Purchase Unit', '0');

DELIMITER $$
DROP procedure IF EXISTS `get_customer_list`$$
CREATE PROCEDURE `get_customer_list`(
)
BEGIN
SELECT
    c.renewal,
    c.totalsms,
    c.customerno,
    c.customername,
    c.smsleft,
    c.customercompany,
    c.lease_duration,
    c.lease_price,
    c.renewal,
    c.unit_msp,
    COUNT(unit.unitno) AS cunit,
    c.isoffline,
    rel.manager_name
FROM
    customer c
        LEFT OUTER JOIN
    unit ON c.customerno = unit.customerno
        AND unit.trans_statusid NOT IN (10)
        LEFT OUTER JOIN
    relationship_manager rel ON rel.rid = c.rel_manager
WHERE
    c.renewal NOT IN (- 1 , - 2)
        AND c.use_trace = 0
GROUP BY c.customerno ;

END$$

DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `get_timezone`$$
CREATE PROCEDURE `get_timezone`(
)
BEGIN
SELECT * from timezone;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `insert_customer`$$
CREATE  PROCEDURE `insert_customer`(
IN customernameParam VARCHAR(100),
IN customercompanyParam VARCHAR(200),
IN dateaddedParam date,
IN totalsmsParam INT,
IN smsleftParam INT,
IN total_alertParam INT,
IN alertleftParam INT,
IN teamidParam INT,
IN loadingParam INT,
IN locationParam INT,
IN trackingParam INT,
IN maitenanceParam INT,
IN tempSensorsParam INT,
IN portableParam INT,
IN hierarchyParam INT,
IN advanceAlertsParam INT,
IN acSensorParam INT,
IN gensetSensorParam INT,
IN fuelSensorParam INT,
IN doorSensorParam INT,
IN routeParam INT,
IN panicParam INT,
IN buzzerParam INT,
IN immobilizerParam INT,
IN mobilityParam INT,
IN deliveryParam INT,
IN salesParam INT,
IN erpParam INT,
IN warehouseParam INT,
IN timezoneParam INT,
IN createdTimeParam datetime,
OUT isexecutedOut INT,
OUT customerIdOut INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
           ROLLBACK;
             /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error;  */
           SET isexecutedOut = 0;
       END;

        SET isexecutedOut = 0;

       START TRANSACTION;
       BEGIN
            INSERT INTO customer (`customername` ,`customercompany` , `dateadded` , `totalsms`,`smsleft`, `total_tel_alert`,`tel_alertleft`, `teamid`,`use_msgkey`,
            `use_geolocation`,`use_tracking`,`use_maintenance`,`temp_sensors`,`use_portable`,
            `use_hierarchy`, `use_advanced_alert`, `use_ac_sensor`, `use_genset_sensor`,
            `use_fuel_sensor`, `use_door_sensor`, `use_routing`,
            `use_panic`, `use_buzzer`, `use_immobiliser`,`use_mobility`, `use_delivery`,`use_sales`,`use_erp`,`use_warehouse`, `timezone`,`createdtime`)
            VALUES (customernameParam, customercompanyParam, dateaddedParam, totalsmsParam, totalsmsParam, total_alertParam, total_alertParam, teamidParam, loadingParam,
             locationParam, trackingParam, maitenanceParam,tempSensorsParam, portableParam, hierarchyParam,advanceAlertsParam,acSensorParam,gensetSensorParam,
             fuelSensorParam,doorSensorParam,routeParam,panicParam,buzzerParam,immobilizerParam,mobilityParam,deliveryParam,salesParam,erpParam,warehouseParam,timezoneParam,createdTimeParam);

            SET customerIdOut = LAST_INSERT_ID();

            INSERT INTO trans_history (
            `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
            VALUES (customerIdOut, 0, teamidParam, 2, createdTimeParam, 0, CONCAT("SMS Added:",totalsmsParam,"Total SMS:",totalsmsParam));

            INSERT INTO trans_history (
            `customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`)
            VALUES (customerIdOut, 0, teamidParam, 2, createdTimeParam, 0, CONCAT("Telephonic Alerts Added :",total_alertParam,"Total SMS:",total_alertParam));

            SET isExecutedOut = 1;
       END;
       COMMIT;
END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `insert_contactperson_details`$$
CREATE  PROCEDURE `insert_contactperson_details`(
IN customernameParam VARCHAR(100),
IN userEmailParam VARCHAR(100),
IN userPhoneParam VARCHAR(20),
IN customernoParam INT,
OUT isexecutedOut INT,
OUT contactPersonIdOut INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
           ROLLBACK;
             /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error;  */
           SET isexecutedOut = 0;
       END;

        SET isexecutedOut = 0;

       START TRANSACTION;
       BEGIN
            INSERT INTO contactperson_details (`typeid`, `person_name`, `cp_email1`, `cp_phone1`, `customerno`, `isdeleted`)
            VALUES (1,customernameParam,userEmailParam,userPhoneParam,customernoParam,0);

          SET isExecutedOut = 1;
          SET contactPersonIdOut = LAST_INSERT_ID();
       END;
       COMMIT;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_userkey`$$
CREATE PROCEDURE `fetch_userkey`(
IN userkeyParam VARCHAR(100),
OUT isExistOut INT)
BEGIN
    DECLARE userkey_temp VARCHAR(100);
    SET isExistOut =0;

    SELECT userkey INTO userkey_temp from user where userkey=userkeyParam;

    IF(userkey_temp IS NOT NULL) THEN
        SET isExistOut =1;
    END IF;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `insert_user`$$
CREATE PROCEDURE `insert_user`(
       IN customeridParam INT(11)
      ,IN primaryusernameParam VARCHAR(100)
      ,IN primaryuserloginParam VARCHAR(50)
      ,IN primaryuserpasswordParam VARCHAR(150)
      ,IN userkey1Param VARCHAR(150)
      ,IN userkey2Param VARCHAR(150)
      ,IN ctrakingParam TINYINT(4)
      ,IN cmaintenanceParam INT(5)
      ,IN cheirarchyParam TINYINT(1)
      ,IN moduleidParam INT(11)
      ,IN todaydatetimeParam datetime
      ,OUT isExecutedOut INT
      ,OUT userIdOut INT

)
BEGIN
DECLARE lastInsertID INT;
DECLARE lastelixirId INT;
DECLARE EXIT HANDLER FOR SQLEXCEPTION

SET lastInsertId = 0;
SET lastelixirId = 0;
SET isExecutedOut=0;
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */

    END;

#USER FOR CUSTOMER
IF(cmaintenanceParam = 1 AND cheirarchyParam = 1 AND ctrakingParam = 1) THEN
INSERT into speed.user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);
INSERT into elixiatech.user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);

SET lastInsertId = LAST_INSERT_ID();
SET userIdOut = LAST_INSERT_ID();
INSERT INTO speed.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);
/*INSERT INTO elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);*/


ELSEIF(cmaintenanceParam = 1 && cheirarchyParam = 1 && ctrakingParam!=1) THEN
INSERT into speed.user (customerno, realname, username, password, role, roleid,email, userkey)
VALUES (customeridParam,primaryusernameParam,primaryuserloginParam, sha1(primaryuserpasswordParam),'Master', 1,primaryuserloginParam, userkey1Param);
INSERT into elixiatech.user (customerno, realname, username, password, role, roleid,email, userkey)
VALUES (customeridParam,primaryusernameParam,primaryuserloginParam, sha1(primaryuserpasswordParam),'Master', 1,primaryuserloginParam, userkey1Param);

SET lastInsertId = LAST_INSERT_ID();
SET userIdOut = LAST_INSERT_ID();
INSERT INTO speed.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);
/*INSERT INTO elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastInsertId,1,1,1,customeridParam,lastInsertId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);*/


ELSE
INSERT into speed.user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);
INSERT into elixiatech.user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES(customeridParam,primaryusernameParam,primaryuserloginParam,sha1(primaryuserpasswordParam),'Administrator',5,primaryuserloginParam,userkey1Param);
SET userIdOut = LAST_INSERT_ID();
END IF;

#USER FOR ELIXIA
INSERT into speed.user (customerno, realname, username, password, role, userkey)
VALUES (customeridParam, 'Elixir', concat("elixir",customeridParam), sha1('el!365x!@'),'elixir', userkey2Param);

INSERT into elixiatech.user (customerno, realname, username, password, role, userkey)
VALUES (customeridParam, 'Elixir', concat("elixir",customeridParam), sha1('el!365x!@'),'elixir', userkey2Param);
SET lastelixirId = LAST_INSERT_ID();

IF(cmaintenanceParam = 1 AND cheirarchyParam = 1 AND ctrakingParam = 1) THEN
INSERT INTO speed.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);

/*INSERT INTO elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);*/
END IF;

IF(cmaintenanceParam = 1 && cheirarchyParam = 1 && ctrakingParam!=1) THEN
INSERT INTO speed.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);

/*INSERT INTO elixiatech.usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT `menuid`,lastelixirId,1,1,1,customeridParam,lastelixirId,todaydatetimeParam,1 FROM menu_master where moduleid=moduleidParam AND isdeleted=0 AND (customerno=0 OR customerno=customeridParam);*/
END IF;
SET isExecutedOut=1;
END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `purchase_unit`$$
CREATE PROCEDURE `purchase_unit`(
     IN unitnoParam varchar(16)
    ,IN commentParam varchar(50)
    ,IN acsParam TINYINT(1)
    ,IN acoppParam TINYINT(1)
    ,IN gssParam TINYINT(1)
    ,IN gssoppParam TINYINT(1)
    ,IN dosParam TINYINT(1)
    ,IN dooroppParam TINYINT(1)
    ,IN todayParam DATETIME
    ,IN transmitnoParam varchar(20)
    ,IN devicetypeParam TINYINT(2)
    ,IN fsParam TINYINT(2)
    ,IN fuelanalogParam INT(11)
    ,IN tempsenParam TINYINT(2)
    ,IN analog1Param INT(11)
    ,IN analog2Param INT(11)
    ,IN analog3Param INT(11)
    ,IN analog4Param INT(11)
    ,IN humidityParam INT(11)
    ,IN typevalueParam INT(11)
    ,IN panicParam TINYINT(1)
    ,IN buzzerParam TINYINT(1)
    ,IN immobilizerParam TINYINT(1)
    ,IN twowaycomParam TINYINT(1)
    ,IN portableParam TINYINT(1)
    ,IN acesensorParam TINYINT(1)
    ,IN acdigitaloppParam TINYINT(1)
    ,IN chalaannoParam varchar(20)
    ,IN lteamidParam INT(11)
    ,IN chalaandateParam DATE
    ,IN vendornoParam varchar(20)
    ,IN vendordateParam DATE
    ,IN device_loctnParam INT(11)
    ,OUT isexecutedOut TINYINT(2)
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
           SET isexecutedOut = 0;
       END;

    BEGIN

        DECLARE unitidVar INT(11);
        DECLARE devicekeyVar BIGINT;
        DECLARE expiryVar date;
        DECLARE vehiclenoVar VARCHAR(40);
        DECLARE vehicleidVar INT(11);
        DECLARE driverlicnoVar VARCHAR(40);
        DECLARE driveridVar INT(11);


        SELECT  CONCAT('V',unitnoParam)
        INTO    vehiclenoVar;

        SELECT  FLOOR(RAND() * 100000000) + 1000000000
        INTO    devicekeyVar;

        WHILE(SELECT devicekey FROM `devices` WHERE devicekey = devicekeyVar) DO
                SELECT  FLOOR(RAND() * 100000000) + 1000000000
                INTO    devicekeyVar;
        END WHILE;

        SELECT  date(DATE_ADD(todayParam, INTERVAL 1 YEAR))
        INTO    expiryVar;

        SELECT  CONCAT('LIC',unitnoParam)
        INTO    driverlicnoVar;

        SET     isexecutedOut = 0;

        START TRANSACTION;
        BEGIN

            IF devicetypeParam = 1 THEN

                INSERT INTO unit(`customerno`
                    ,`unitno`
                    ,`trans_statusid`
                    ,`comments`
                    ,`get_conversion`
                    ,`unit_location_box_number`)
                VALUES (1
                    ,unitnoParam
                    ,1
                    ,commentParam
                    ,1
                    ,device_loctnParam);

            ELSE

                INSERT INTO unit(`customerno`
                    ,`unitno`
                    ,`acsensor`
                    ,`is_ac_opp`
                    ,`gensetsensor`
                    ,`is_genset_opp`
                    ,`doorsensor`
                    ,`is_door_opp`
                    ,`trans_statusid`
                    , `comments`
                    ,`digitalioupdated`
                    ,`transmitterno`
                    ,`get_conversion`
                    ,`unit_location_box_number`
                    ,`humidity`)
            VALUES (1
                    ,unitnoParam
                    ,acsParam
                    ,acoppParam
                    ,gssParam
                    ,gssoppParam
                    ,dosParam
                    ,dooroppParam
                    ,1
                    ,commentParam
                    ,todayParam
                    ,transmitnoParam
                    ,1
                    ,device_loctnParam
                    ,humidityParam);
            END IF;

            SELECT  LAST_INSERT_ID()
            INTO    unitidVar;


            UPDATE  unit
            SET     digitalioupdated = NOW()
                    ,door_digitalioupdated = NOW()
                    ,extra_digitalioupdated = NOW()
                    ,extra2_digitalioupdated = NOW()
            WHERE   uid = unitidVar;


            IF devicetypeParam = 2 AND (fsParam = 1 AND fuelanalogParam <> 0) THEN

                UPDATE  unit
                SET     fuelsensor = fuelanalogParam
                WHERE   unitno = unitnoParam;

            END IF;


            IF devicetypeParam = 2 AND tempsenParam = 2 AND analog1Param <> 0 AND analog2Param <> 0 THEN

                UPDATE  unit
                SET     tempsen1 = analog1Param
                        ,tempsen2 = analog2Param
                WHERE   unitno = unitnoParam;

            ELSEIF devicetypeParam = 2 AND tempsenParam = 1 AND analog1Param <> 0 THEN

                UPDATE  unit
                SET     tempsen1 = analog1Param
                WHERE   unitno = unitnoParam;

            ELSEIF devicetypeParam = 2 AND tempsenParam = 3 AND analog1Param <> 0 AND analog2Param <> 0 AND analog3Param <> 0 THEN

                UPDATE  unit
                SET     tempsen1 = analog1Param
                        , tempsen2 = analog2Param
                        , tempsen3 = analog3Param
                WHERE   unitno = unitnoParam;

            ELSEIF devicetypeParam = 2 AND tempsenParam = 4 AND analog1Param <> 0 AND analog2Param <> 0 AND analog3Param <> 0 AND analog4Param <> 0 THEN

                UPDATE  unit
                SET     tempsen1 = analog1Param
                        ,tempsen2 = analog2Param
                        ,tempsen3 = analog3Param
                        ,tempsen4 = analog4Param
                WHERE   unitno = unitnoParam;

            END IF;



            UPDATE  unit
            SET     type_value = typevalueParam
            WHERE   uid = unitidVar;


            IF devicetypeParam = 2 AND panicParam = 1 THEN

                UPDATE  unit
                SET     is_panic = 1
                WHERE   unitno = unitnoParam ;

                UPDATE  customer
                SET     use_panic = 1
                WHERE   customerno = 1;

            END IF;


            IF devicetypeParam = 2 AND buzzerParam = 1 THEN

                UPDATE  unit
                SET     is_buzzer = 1
                WHERE   unitno = unitnoParam;

                UPDATE  customer
                SET     use_buzzer = 1
                WHERE   customerno = 1;

            END IF;


            IF devicetypeParam = 2 AND immobilizerParam = 1 THEN

                UPDATE  unit
                SET     is_mobiliser = 1
                WHERE   unitno = unitnoParam;

                UPDATE  customer
                SET     use_immobiliser = 1
                WHERE   customerno = 1;

            END IF;


            IF devicetypeParam = 2 AND twowaycomParam = 1 THEN

                UPDATE  unit
                SET     is_twowaycom = 1
                WHERE   unitno = unitnoParam;

            END IF;


            IF devicetypeParam = 2 AND portableParam = 1 THEN

                UPDATE  unit
                SET     is_portable = 1
                WHERE   unitno = unitnoParam;

            END IF;


            INSERT INTO `devices`(`customerno`
                    , `devicekey`
                    , `devicelat`
                    , `devicelong`
                    , `baselat`
                    , `baselng`
                    , `installlat`
                    , `installlng`
                    , `lastupdated`
                    , `registeredon`
                    , `altitude`
                    , `directionchange`
                    , `inbatt`
                    , `hwv`
                    , `swv`
                    , `msgid`
                    , `uid`
                    , `status`
                    , `ignition`
                    , `powercut`
                    , `tamper`
                    , `gpsfixed`
                    , `online/offline`
                    , `gsmstrength`
                    , `gsmregister`
                    , `gprsregister`
                    , `aci_status`
                    , `satv`
                    , `device_invoiceno`
                    , `inv_generatedate`
                    , `installdate`
                    , `expirydate`
                    , `invoiceno`
                    , `po_no`
                    , `po_date`
                    , `warrantyexpiry`
                    , `simcardid`
                    , `inv_device_priority`
                    , `inv_deferdate`
                    , `start_date`
                    , `end_date`)
            VALUES (1
                    ,devicekeyVar
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,todayParam
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,unitidVar
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,expiryVar
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,''
                    ,'');










            INSERT INTO vehicle (vehicleno
                    , customerno
                    , uid
                    , kind)
            VALUES  ( vehiclenoVar
                    , '1'
                    , unitidVar
                    , 'Truck');


            SELECT  LAST_INSERT_ID()
            INTO    vehicleidVar;


            UPDATE  unit
            SET     vehicleid = vehicleidVar
            WHERE   uid = unitidVar;


            INSERT INTO driver (drivername
                    ,driverlicno
                    ,customerno
                    ,vehicleid)
            VALUES ('Not Allocated',driverlicnoVar,1,vehicleidVar);

            SELECT  LAST_INSERT_ID()
            INTO    driveridVar;


            UPDATE  vehicle
            SET     driverid = driveridVar
            WHERE   vehicleid = vehicleidVar;



            INSERT INTO eventalerts (vehicleid
                    ,overspeed
                    , tamper
                    , powercut
                    , temp
                    , ac
                    , customerno)
            VALUES (vehicleidVar
                    ,0
                    ,0
                    ,0
                    ,0
                    ,0
                    ,1);



            INSERT INTO ignitionalert (vehicleid
                    ,last_status
                    ,last_check
                    ,`count`
                    ,`status`
                    ,customerno)
                    VALUES (vehicleidVar
                    ,0
                    ,0
                    ,0
                    ,0
                    ,1);


            IF acesensorParam = 1 AND acdigitaloppParam <> 0 THEN

                INSERT INTO acalerts (last_ignition
                    , customerno
                    , vehicleid
                    , aci_status)
                VALUES (0
                    ,1
                    ,vehicleidVar
                    ,0);

            END IF;

            INSERT INTO trans_history (`customerno`
                ,`unitid`
                ,`teamid`
                , `type`
                , `trans_time`
                , `statusid`
                , `transaction`
                , `simcardno`
                , `invoiceno`
                , `expirydate`
                , `comments`)
            VALUES (1
                ,unitidVar
                ,lteamidParam
                , 0
                ,todayParam
                , 1
                , 'New Purchase'
                ,''
                ,''
                ,''
                ,commentParam);

            IF chalaannoParam <> '' THEN

                INSERT INTO chalaan (uid
                    , chalaan_no
                    , chalaan_date
                    , vendor_invno
                    , vendor_invdate
                    , insertedby
                    , insertedon)
                VALUES(unitidVar
                    ,chalaannoParam
                    ,chalaandateParam
                    ,vendornoParam
                    ,vendordateParam
                    ,lteamidParam
                    ,todayParam);

            END IF;

            SET isexecutedOut = 1;

        END;
        COMMIT;

    END;
END$$

DELIMITER ;




UPDATE  dbpatches
SET     isapplied = 1
WHERE   patchid = 635;

