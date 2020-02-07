
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'460', '2017-02-13 15:20:40', 'Arvind Thakur', 'checking cqid in smslog for previous 2min before sending sms', '0'
);

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
    DECLARE smscountUserVar INT(11); 
    DECLARE smslockUserVar TINYINT(1);
    DECLARE smscountVehicleVar INT(11) DEFAULT NULL;
    DECLARE smslockVehicleVar TINYINT(1);
    DECLARE smsidVar INT(11) DEFAULT 0;
        
    IF (customernoParam=0) THEN
        SET customernoParam=NULL;
    END IF;
    
    IF (vehicleidParam=0) THEN
        SET vehicleidParam=NULL;
    END IF;
    
    IF (useridParam=0) THEN
        SET useridParam=NULL;
    END IF;
    
        SELECT  smsleft 
        INTO    smsleftVar 
        from    `customer` 
        where   customerno=customernoParam;
   
        SELECT  sms_count
                ,sms_lock 
        INTO    smscountUserVar
                ,smslockUserVar 
        from    `user` 
        where   userid=useridParam;
   
    IF (vehicleidParam IS NOT NULL) THEN
        SELECT  sms_count
                ,sms_lock 
        INTO    smscountVehicleVar
                ,smslockVehicleVar 
        from    `vehicle` 
        where   vehicleid=vehicleidParam;
    ELSEIF vehicleidParam=0 THEN
        SET smscountVehicleVar=NULL;
	ELSE 
		SET smscountVehicleVar=NULL;
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

    IF smsidVar = 0 THEN
        IF (smsleftVar > 0) THEN
            IF smslockUserVar = 0 THEN
                IF smscountVehicleVar IS NOT NULL THEN
                    IF smslockVehicleVar = 0 THEN
                        SET statusOut=0;
                    ELSE
                        SET statusOut=-1;
                    END IF;
                ELSE
                    SET statusOut=0;
                END IF;
            ELSE
                SET statusOut=-2;
            END IF;
        ELSE
            SET statusOut=-3;
        END IF;
    ELSE 
        SET statusOut=-4;
    END IF;

END$$
DELIMITER $$


-- 
-- call getSmsStatus('3','2126','1388','8691066906','Dear Rinky, MH 04 ES 6147 has been allotted for your pickup. Driver Name: Not Allocated (8888888888)','5',@status);
-- select @status;

UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 460;