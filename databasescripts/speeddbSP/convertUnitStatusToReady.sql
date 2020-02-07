DELIMITER $$
DROP PROCEDURE IF EXISTS `convertUnitStatusToReady`$$
CREATE PROCEDURE `convertUnitStatusToReady`(
    IN unitnoParam VARCHAR(16)
    , IN vehiclenoParam VARCHAR(40)
    , IN simcardnoParam VARCHAR(50)
    , IN customernoParam INT
    , IN todaysDateParam DATETIME
    , OUT messageOut VARCHAR(250))
BEGIN
    
    DECLARE newVehicleIdVar INT;
    DECLARE oldVehicleIdVar VARCHAR(250);
    DECLARE newDriverIdVar INT;
    DECLARE simcardidVar INT;
    DECLARE uidVar INT;
    DECLARE deviceidVar INT;

    ROLLBACK;
    BEGIN
    /*
      GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
      @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
      SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
      SELECT @full_error;
      */
    END;

    IF(customernoParam = 0) THEN
        SET customernoParam = NULL;
    END IF;

    IF(unitnoParam = '') THEN
        SET unitnoParam = NULL;
    END IF;

    IF(vehiclenoParam = '') THEN
        SET vehiclenoParam = CONCAT('V',TRIM(unitnoParam));
    END IF;

    SELECT  uid 
    INTO    uidVar
    FROM    unit
    WHERE   unitno = TRIM(unitnoParam)
    LIMIT   1;

    SELECT  id
    INTO    simcardidVar
    FROM    simcard
    WHERE   simcardno = TRIM(simcardnoParam)
    LIMIT   1;

    SELECT  deviceid 
    INTO    deviceidVar
    FROM    devices
    WHERE   uid = uidVar
    LIMIT   1;

    SELECT  GROUP_CONCAT(vehicleid)
    INTO    oldVehicleIdVar
    FROM    vehicle
    WHERE   uid = uidVar;

    SET messageOut = '';

    IF (uidVar IS NOT NULL AND simcardidVar IS NOT NULL AND deviceidVar IS NOT NULL) THEN

        START TRANSACTION;
        BEGIN

            IF (oldVehicleIdVar IS NOT NULL) THEN

                UPDATE  vehicle
                SET     uid = 0
                WHERE   FIND_IN_SET(vehicleid,oldVehicleIdVar);

            END IF;

            INSERT INTO `vehicle`(`vehicleno`
                , `customerno`
                , `uid`
                , `isdeleted`
                , `createdBy`
                , `createdOn`) 
            VALUES (vehiclenoParam
                , customernoParam
                , uidVar
                , 0
                , 4
                , todaysDateParam);


            SET newVehicleIdVar = LAST_INSERT_ID();

            UPDATE  unit 
            SET     trans_statusid = 2
                    , vehicleid = newVehicleIdVar
                    , customerno = customernoParam
            WHERE   uid = uidVar;

            UPDATE  devices
            SET     simcardid = simcardidVar
                    , customerno = customernoParam
            WHERE   deviceid = deviceidVar;

            UPDATE  simcard
            SET     customerno = customernoParam
                    ,trans_statusid = 11
            WHERE   id = simcardidVar;

            INSERT INTO `driver`(`drivername`
                , `customerno`
                , `vehicleid`
                , `isdeleted`) 
            VALUES ('Not Allocated'
                , customernoParam
                , newVehicleIdVar
                , 0);

            SET newDriverIdVar = LAST_INSERT_ID();

            UPDATE  vehicle
            SET     driverid = newDriverIdVar
            WHERE   vehicleid = newVehicleIdVar;

            INSERT INTO `ignitionalert`(`vehicleid`
                , `customerno`
                , `createdBy`
                , `createdOn`) 
            VALUES (newVehicleIdVar
                , customernoParam
                , 4
                , todaysDateParam);

            INSERT INTO `eventalerts`(`vehicleid`
                , `customerno`
                , `createdBy`
                , `createdOn`) 
            VALUES (newVehicleIdVar
                , customernoParam
                , 4
                , todaysDateParam);

            SET messageOut = 'Successfully Converted.';

        END;
        COMMIT;

    ELSE

        IF (uidVar IS NULL) THEN
            SET messageOut = CONCAT(messageOut,'Unit not found.');
        END IF;

        IF (simcardidVar IS NULL) THEN
            SET messageOut = CONCAT(messageOut,'Simcard not found.');
        END IF;

        IF (deviceidVar IS NULL) THEN
            SET messageOut = CONCAT(messageOut,'Device not found.');
        END IF;

    END IF;

END$$
DELIMITER ;
