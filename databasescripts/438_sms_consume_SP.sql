
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'438', '2016-12-16 17:11:01', 'Arvind Thakur', 'SP related to SMS Consume cron', '0'
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

INSERT INTO smslog (mobileno
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
    ,IN vehsmslockParam INT(11)
    ,IN usersmslockParam INT(11)
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
        IF (smscountUserVar < usersmslockParam OR  smslockUserVar=0) THEN
            IF (smscountVehicleVar IS NOT NULL) THEN
                IF (smscountVehicleVar < vehsmslockParam OR smslockVehicleVar=0) THEN
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
    ,IN smscountParam INT(11)
    ,IN usersmslockParam INT(11)
     )

BEGIN
    DECLARE smscountVar INT(11);

    UPDATE `user` SET sms_count=sms_count + smscountParam WHERE userid=useridParam AND isdeleted=0;

    SELECT `sms_count` INTO smscountVar FROM `user` WHERE userid=useridParam;

    SET usersmslockParam = usersmslockParam - 1;

    IF smscountVar > usersmslockParam THEN
        UPDATE `user` SET sms_lock=1 WHERE userid=useridParam;

       INSERT INTO `smslocklog` (
       `customerno`
       ,`userid`
       ,`vehicleid`
       ,`new_lock_status`
       ,`createdby`
       ,`createdon`
       )VALUES (customernoParam
        ,useridParam
        ,0
        ,1
        ,0
        ,todaysdateParam);
    END IF;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_vehicle_smslock`$$
CREATE PROCEDURE `update_vehicle_smslock`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN vehicleidParam INT(11)
    ,IN smscountParam INT(11)
    ,IN vehsmslockParam INT(11)
     )

BEGIN
    DECLARE smscountVar INT(11);

    UPDATE `vehicle` SET sms_count=sms_count + smscountParam WHERE vehicleid=vehicleidParam AND isdeleted=0;

    SELECT `sms_count` INTO smscountVar FROM `vehicle` WHERE vehicleid=vehicleidParam;

    SET vehsmslockParam = vehsmslockParam - 1;

    IF smscountVar > vehsmslockParam THEN

        UPDATE `vehicle` SET sms_lock=1 WHERE vehicleid=vehicleidParam;

        INSERT INTO `smslocklog` (
       `customerno`
       ,`userid`
       ,`vehicleid`
       ,`new_lock_status`
       ,`createdby`
       ,`createdon`
       )VALUES (customernoParam
        ,0
        ,vehicleidParam
        ,1
        ,0
        ,todaysdateParam);
    END IF;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `resetSMSCount`$$
CREATE PROCEDURE `resetSMSCount`()
BEGIN

    UPDATE `vehicle` SET sms_count=0;

    UPDATE `user` SET sms_count=0;

END$$
DELIMITER ;


DROP TABLE `smslocklog`;


CREATE TABLE IF NOT EXISTS `smslocklog` (
 `logid` int(11)  primary key auto_increment,
 `customerno` int(11),
 `userid` int(11),
 `vehicleid` int(11),
 `new_lock_status` tinyint(2),
 `createdby` int(11),
 `createdon` datetime ,
 `updatedby` int(11),
 `updatedon` datetime ,
 `isdeleted` tinyint(1) DEFAULT '0'
);

INSERT INTO `modules`(`modulename`, `created_by`,`created_on`, `isdeleted`) VALUES ('Expense Management',491,'2016-12-12 07:31:41',0);

INSERT INTO `modules`(`modulename`, `created_by`,`created_on`, `isdeleted`) VALUES ('Sales Engage Management',491,'2016-12-13 17:02:41',0);

INSERT INTO `modules`(`modulename`, `created_by`,`created_on`, `isdeleted`) VALUES ('Client Code Management',491,'2016-12-13 17:02:41',0);


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_comq`$$
CREATE PROCEDURE `get_sms_consume_frm_comq`(
    IN customernoParam INT,
    IN todaysdateParam DATE
)
BEGIN
    DECLARE todayVar VARCHAR(20);
    IF(customernoParam = '' OR customernoParam = '0') THEN
		SET customernoParam = NULL;
	END IF;
    IF(todaysdateParam = '' OR todaysdateParam = '0') THEN
            SET todaysdateParam = NULL;
    ELSE
            SELECT CONCAT(todaysdateParam,'%') INTO todayVar;
    END IF;
SELECT COUNT(cq.cqhid) AS count1 FROM `comhistory` as cq
    WHERE cq.customerno=customernoParam AND cq.timesent LIKE todayVar AND cq.comtype=1;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_smslog`$$
CREATE PROCEDURE `get_sms_consume_frm_smslog`(
    IN customernoParam INT,
    IN todaysdateParam DATE
)
BEGIN
    DECLARE todayVar VARCHAR(20);
    IF(customernoParam = '' OR customernoParam = '0') THEN
        SET customernoParam = NULL;
    END IF;

    IF(todaysdateParam = '' OR todaysdateParam = '0') THEN
        SET todaysdateParam = NULL;
    ELSE
        SELECT CONCAT(todaysdateParam,'%') INTO todayVar;
    END IF;

    SELECT COUNT(sm.smsid) AS count1 FROM `smslog` AS sm
    WHERE sm.customerno=customernoParam AND sm.inserted_datetime LIKE todayVar;

END$$
DELIMITER ;



UPDATE 	dbpatches
SET 	patchdate = NOW()
	, isapplied =1
WHERE 	patchid = 438;
