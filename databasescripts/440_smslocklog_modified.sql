
CREATE TABLE IF NOT EXISTS `sales_stage` (
  `stageid` int(11) NOT NULL primary key auto_increment,
  `stage_name` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_source` (
  `sourceid` int(11) NOT NULL primary key auto_increment,
  `source_name` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `sales_product` (
  `productid` int(11) NOT NULL primary key auto_increment,
  `product_name` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_industry_type` (
  `industryid` int(11) NOT NULL primary key auto_increment,
  `industry_type` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS `sales_mode` (
  `modeid` int(11) NOT NULL primary key auto_increment,
  `mode` varchar(30) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_pipeline` (
  `pipelineid` int(11) NOT NULL primary key auto_increment,
  `pipeline_date` date NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `sourceid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `industryid` int(11) NOT NULL,
  `modeid` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `location` varchar(200) NOT NULL,
  `remarks` TEXT NOT NULL,
  `stageid` int(11) NOT NULL,
  `qtnno` varchar(20) NOT NULL,
  `qtndate` date NOT NULL,
  `pono` varchar(20) NOT NULL,
  `podate` date NOT NULL,
  `no_of_devices` int(11) NOT NULL,
  `device_type` varchar(50) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_contact` (
  `contactid` int(11) NOT NULL primary key auto_increment,
  `pipelineid` int(11) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(20) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


CREATE TABLE IF NOT EXISTS `sales_pipeline_history` (
  `pipeline_history_id` int(11) NOT NULL primary key auto_increment,
  `pipelineid` int(11) NOT NULL,
  `pipeline_date` date NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `sourceid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `industryid` int(11) NOT NULL,
  `modeid` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `location` varchar(200) NOT NULL,
  `remarks` TEXT NOT NULL,
  `stageid` int(11) NOT NULL,
  `qtnno` varchar(20) NOT NULL,
  `qtndate` date NOT NULL,
  `pono` varchar(20) NOT NULL,
  `podate` date NOT NULL,
  `no_of_devices` int(11) NOT NULL,
  `device_type` varchar(50) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);




CREATE TABLE IF NOT EXISTS `sales_reminder` (
  `reminderid` int(11) NOT NULL primary key auto_increment,
  `reminder_datetime` datetime NOT NULL,
  `content` TEXT NOT NULL,
  `pipelineid` int(11) NOT NULL,
  `contactid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `teamid_creator` int(11) NOT NULL
);


INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'440', '2016-12-16 17:11:01', 'Arvind Thakur', 'SP related to SMS Consume cron', '0'
);

DROP TABLE IF EXISTS `smslocklog`;

CREATE TABLE IF NOT EXISTS `smslocklog` (
 `logid` int(11)  primary key auto_increment,
 `customerno` int(11),
 `userid` int(11),
 `vehicleid` int(11),
 `createdby` int(11),
 `createdon` datetime ,
 `updatedby` int(11),
 `updatedon` datetime ,
 `isdeleted` tinyint(1) DEFAULT '0',
 `issmssent` TINYINT(2) DEFAULT '0',
 `ismailsent` TINYINT(2) DEFAULT '0'
);

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
    

    IF smscountVar >= usersmslockParam THEN
    BEGIN
        UPDATE `user` SET sms_lock=1 WHERE userid=useridParam;
        
       INSERT INTO `smslocklog` (
       `customerno`
       ,`userid`
       ,`vehicleid`
       ,`createdby`
       ,`createdon`
       )VALUES (customernoParam
        ,useridParam
        ,0
        ,0
        ,todaysdateParam);
	END;
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
    

    IF smscountVar >= vehsmslockParam THEN
    BEGIN
        UPDATE `vehicle` SET sms_lock=1 WHERE vehicleid=vehicleidParam;
        
        INSERT INTO `smslocklog` (
       `customerno`
       ,`userid`
       ,`vehicleid`
       ,`createdby`
       ,`createdon`
       )VALUES (customernoParam
        ,0
        ,vehicleidParam
        ,0
        ,todaysdateParam);
    END;
    END IF;
    
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

END$$
DELIMITER $$

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 440;