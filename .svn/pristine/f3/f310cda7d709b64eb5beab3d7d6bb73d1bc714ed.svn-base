
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'437', '2016-12-16 12:11:01', 'Arvind Thakur', 'SP related to SMS lock in vehicle and user', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS insert_smslog$$
CREATE PROCEDURE `insert_smslog`(
IN mobilenoparam VARCHAR(10)
, IN messageparam VARCHAR(250)
, IN responseparam VARCHAR(250)
, IN vehicleidparam INT
, IN useridparam INT
, IN customernoparam INT
, IN isSmsSentParam TINYINT(1)
, IN todaysdate DATETIME
, IN moduleidparam TINYINT(1)
, IN cqidParam INT(11)
, OUT smsid INT
)
BEGIN

INSERT INTO smslog (
                    mobileno
                    , message
                    , response
                    , vehicleid
                    , userid
                    , moduleid	
                    , customerno
                    , issmssent
                    , inserted_datetime
                    , cqid
                    ) 
		VALUES (
                    mobilenoparam
                    , messageparam
                    , responseparam
                    , vehicleidparam
                    , useridparam
                    , moduleidparam
                    , customernoparam
                    , isSmsSentParam
                    , todaysdate
                    , cqidParam
                );
SET smsid = LAST_INSERT_ID();
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `getSmsStatus`$$
CREATE PROCEDURE `getSmsStatus`(
    IN customernoParam INT(11)
    ,IN vehicleidParam INT(11)
    ,IN useridParam INT(11)
    ,OUT statusOut TINYINT(1)
     )

BEGIN
    DECLARE smsleftVar INT(11);
    DECLARE smscountUserVar INT(11); 
    DECLARE smslockUserVar TINYINT(1);
    DECLARE smscountVehicleVar INT(11) DEFAULT NULL;
    DECLARE smslockVehicleVar TINYINT(1);
    
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

    IF (smsleftVar > 0) THEN
        IF (smscountUserVar < 10 OR  smslockUserVar=0) THEN
            IF (smscountVehicleVar IS NOT NULL) THEN
                IF (smscountVehicleVar < 3 OR smslockVehicleVar=0) THEN
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

END$$
DELIMITER $$


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_user_smslock`$$
CREATE PROCEDURE `update_user_smslock`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN useridParam INT(11)
    ,IN vehicleidParam INT(11)
    ,IN smscountParam INT(11)
     )

BEGIN
    DECLARE smscountVar INT(11);
    
    UPDATE `user` SET sms_count=sms_count + smscountParam WHERE userid=useridParam AND isdeleted=0;
    
    SELECT `sms_count` INTO smscountVar FROM `user` WHERE userid=useridParam;
    
    IF smscountVar > 9 THEN
        UPDATE `user` SET sms_lock=1 WHERE userid=useridParam;

        INSERT INTO `⁠⁠⁠⁠smslocklog⁠⁠⁠⁠`(`customerno⁠⁠⁠⁠`
            , `⁠⁠⁠⁠userid`
            , `⁠⁠⁠⁠teamid`
            , `⁠⁠⁠⁠vehicleid`
            , `⁠⁠⁠⁠new_lock_status`
            , `⁠⁠⁠⁠timestamp`) 
        VALUES (customernoParam
            , useridParam
            , 0
            , vehicleidParam
            , 1
            , todaysdateParam);
    END IF;
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_vehicle_smslock`$$
CREATE PROCEDURE `update_vehicle_smslock`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN useridParam INT(11)
    ,IN vehicleidParam INT(11)
    ,IN smscountParam INT(11)
     )

BEGIN
    DECLARE smscountVar INT(11);
    
    UPDATE `vehicle` SET sms_count=sms_count + smscountParam WHERE vehicleid=vehicleidParam AND isdeleted=0;
    
    SELECT `sms_count` INTO smscountVar FROM `vehicle` WHERE vehicleid=vehicleidParam;
    
    IF smscountVar > 2 THEN
    
        UPDATE `vehicle` SET sms_lock=1 WHERE vehicleid=vehicleidParam;
        
        INSERT INTO `⁠⁠⁠⁠smslocklog⁠⁠⁠⁠`(`customerno⁠⁠⁠⁠`
            , `⁠⁠⁠⁠userid`
            , `⁠⁠⁠⁠teamid`
            , `⁠⁠⁠⁠vehicleid`
            , `⁠⁠⁠⁠new_lock_status`
            , `⁠⁠⁠⁠timestamp`) 
        VALUES (customernoParam
            ,useridParam
            ,0
            ,vehicleidParam
            ,1
            ,todaysdateParam);
    END IF;
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `resetSMSCount`$$
CREATE PROCEDURE `resetSMSCount`()
BEGIN
    
    UPDATE `vehicle` SET sms_count=0 WHERE sms_lock=0 AND isdeleted=0;
    
    UPDATE `user` SET sms_count=0 WHERE sms_lock=0 AND isdeleted=0;
    
END$$
DELIMITER ; 


CREATE TABLE IF NOT EXISTS ﻿⁠⁠⁠⁠smslocklog﻿⁠⁠⁠⁠ (
 ﻿⁠⁠⁠⁠logid﻿⁠⁠⁠⁠ int(11) NOT NULL primary key auto_increment,
 ﻿⁠⁠⁠⁠customerno﻿⁠⁠⁠⁠ int(11) NOT NULL,
 ﻿⁠⁠⁠⁠userid﻿⁠⁠⁠⁠ int(11) NOT NULL,
 ﻿⁠⁠⁠⁠teamid﻿⁠⁠⁠⁠ int(11) NOT NULL,
 ﻿⁠⁠⁠⁠vehicleid﻿⁠⁠⁠⁠ int(11) NOT NULL,
 ﻿⁠⁠⁠⁠new_lock_status﻿⁠⁠⁠⁠ tinyint(2) NOT NULL,
 ﻿⁠⁠⁠⁠timestamp﻿⁠⁠⁠⁠ datetime NOT NULL
);

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 437;