INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('509', '2017-06-03 17:29:00','Arvind Thakur','change in getSmsStatus SP', '0');


DELIMITER $$
DROP PROCEDURE IF EXISTS `getSmsStatus`$$
CREATE PROCEDURE `getSmsStatus`(
    IN todaysdate datetime
    ,IN customernoParam INT(11)
    ,IN vehicleidParam INT(11)
    ,IN useridParam INT(11)
    ,IN mobilenoParam BIGINT(100)
    ,IN messageParam VARCHAR(150)
    ,IN cqidParam INT(11)
    ,OUT statusOut TINYINT(1)
     )
BEGIN
    DECLARE smsleftVar INT(11);
    DECLARE smslockUserVar TINYINT(1);
    DECLARE smslockVehicleVar TINYINT(1);
    DECLARE smsidVar INT(11) DEFAULT 0;
    DECLARE messageLengthVar INT(11) DEFAULT 0;
    SET statusOut=-5;

    IF (customernoParam != 0 AND (NOT(vehicleidParam=0 AND useridParam=0))) THEN
        SELECT  smsleft 
        INTO    smsleftVar 
        FROM    `customer` 
        WHERE   customerno=customernoParam;

        IF (vehicleidParam!=0) THEN
            SELECT  sms_lock 
            INTO    smslockVehicleVar 
            FROM    `vehicle` 
            WHERE   vehicleid=vehicleidParam;
        END IF;

        IF (useridParam!=0) THEN
            SELECT  sms_lock 
            INTO    smslockUserVar 
            FROM    `user` 
            WHERE   userid=useridParam;
        END IF;

        IF cqidParam = 0 THEN
            SELECT  `smsid` 
            INTO    smsidVar 
            FROM    `smslog` 
            WHERE   `inserted_datetime` > (todaysdate - INTERVAL 2 MINUTE)
            AND     `mobileno`= mobilenoParam 
            AND     `message`  = TRIM(messageParam)
            LIMIT 1;
        ELSE
            SELECT  `smsid` 
            INTO    smsidVar 
            FROM    `smslog` 
            WHERE   `inserted_datetime` > (todaysdate - INTERVAL 2 MINUTE) 
            AND     `mobileno`= mobilenoParam 
            AND     `cqid` = cqidParam
            LIMIT 1;
        END IF;

        SELECT  CHAR_LENGTH(messageParam)/160 
        INTO    messageLengthVar;

        IF smsidVar = 0 THEN
            IF smsleftVar > COALESCE(messageLengthVar, 0) THEN
                IF (COALESCE(smslockUserVar, 0) = 0) AND (COALESCE(smslockVehicleVar, 0) = 0) THEN
                    SET statusOut = 0;
                ELSEIF smslockVehicleVar = 1 THEN
                    SET statusOut = -1;
                ELSEIF smslockUserVar = 1 THEN
                   SET statusOut = -2;
                END IF;
            ELSE
                SET statusOut=-3;
            END IF;
        ELSE 
            SET statusOut=-4;
        END IF;
    END IF;
END$$
DELIMITER $$
 

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 509;