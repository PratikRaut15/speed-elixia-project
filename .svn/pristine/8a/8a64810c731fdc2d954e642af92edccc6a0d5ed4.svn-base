/*
    Name		-	replace_device
    Description 	-	replace unit
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	
    Created by		-	Arvind
    Created on		- 	09 March,2017
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_device`$$
CREATE PROCEDURE `replace_device`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN oldvehicleidParam INT(11)    
    ,IN oldunitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newunitidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT oldunitOut VARCHAR(16)
    ,OUT newunitOut VARCHAR(16)
    ,OUT simcardOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT errormsgOut VARCHAR(100)
)

BEGIN
    DECLARE oldsimcardidVar INT;
    DECLARE groupidVar INT;
    DECLARE oldvehiclenoVar VARCHAR(40);
    DECLARE newunitnoVar VARCHAR(16);
    DECLARE oldunitnoVar VARCHAR(16);
    DECLARE simcardnoVar VARCHAR(50);
    DECLARE onleaseVar TINYINT(2);
    DECLARE newvehicleidVar INT(11);
    DECLARE newdeviceidVar bigint(11);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error; */
        SET isexecutedOut = 0;
    END;
    
    SELECT      devices.simcardid
    INTO        oldsimcardidVar
    FROM        devices 
    WHERE       devices.uid =oldunitidParam
    ORDER BY    deviceid DESC
    LIMIT       1;
    
    SELECT      vehicleid
    INTO        newvehicleidVar
    FROM        vehicle
    WHERE       uid = newunitidParam
    AND         isdeleted = 0
    ORDER BY    vehicleid DESC
    LIMIT       1;
    
    SELECT      v.groupid
                ,vehicleno
    INTO        groupidVar
                ,oldvehiclenoVar
    FROM        vehicle v
    WHERE       v.vehicleid=oldvehicleidParam
    AND         v.isdeleted = 0
    ORDER BY    v.vehicleid DESC
    LIMIT       1;
    
    SELECT      unitno 
    INTO        newunitnoVar 
    FROM        unit 
    WHERE       uid =newunitidParam
    ORDER BY    uid DESC
    LIMIT       1;

    SELECT      unitno
    INTO        oldunitnoVar
    FROM        unit 
    WHERE       uid=oldunitidParam
    ORDER BY    uid DESC
    LIMIT       1;

    SELECT      onlease 
    INTO        onleaseVar
    FROM        unit 
    WHERE       uid = oldunitidParam
    ORDER BY    uid DESC
    LIMIT       1;
    
    SELECT      simcardno 
    INTO        simcardnoVar 
    FROM        simcard 
    WHERE       id = oldsimcardidVar
    ORDER BY    id DESC
    LIMIT       1;
    
    SELECT	deviceid 
    INTO 	newdeviceidVar 
    FROM 	devices 
    WHERE       uid=newunitidParam
    ORDER BY    deviceid DESC
    LIMIT       1;
    
    START TRANSACTION;
    BEGIN

        IF oldvehicleidParam <> 0 THEN

            UPDATE  unit 
            SET     customerno=customernoParam
                    , trans_statusid = 5
                    , teamid=0
                    , vehicleid = oldvehicleidParam
                    ,onlease = onleaseVar
            where   uid=newunitidParam;

            UPDATE  simcard 
            SET     trans_statusid=13
            WHERE   id=oldsimcardidVar;

            UPDATE  devices 
            SET     uid = newunitidParam
            WHERE   uid=oldunitidParam;

            UPDATE  devices
            SET     uid = oldunitidParam
            WHERE   deviceid = newdeviceidVar;   

--          old groupid set for new unit 
--          TODO
            UPDATE  vehicle 
            SET     uid = 0
            WHERE   uid = newunitidParam;

            UPDATE  vehicle 
            SET     uid = newunitidParam
            WHERE   vehicleid = oldvehicleidParam AND customerno = customernoParam;

    --      Remove Old Unit    
            UPDATE  unit 
            SET     customerno=1
                    , userid=0
                    ,  trans_statusid = 20
                    ,teamid=eteamidParam
                    ,comments = commentParam
                    ,onlease = 0
                    , vehicleid = newvehicleidVar
            WHERE   uid=oldunitidParam;

    --      Daily report update replace unit for customer 
            UPDATE  dailyreport 
            SET     uid = newunitidParam
                    , first_odometer=0
                    , last_odometer=0
                    , max_odometer=0 
            WHERE   vehicleid = oldvehicleidParam 
            AND     customerno=customernoParam;

            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`newunitid`
                    ,`oldvehicleid`
                    ,`oldsimcardid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,oldunitidParam
                    ,newunitidParam
                    ,oldvehicleidParam
                    ,oldsimcardidVar
                    ,4
                    ,1
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            UPDATE  vehicle
            SET     uid = oldunitidParam
                    ,customerno = 1
            WHERE   vehicleid = newvehicleidVar;

            UPDATE  dailyreport 
            SET     uid = oldunitidParam
                    ,customerno = 1
            WHERE   vehicleid = newvehicleidVar;
			
            UPDATE  bucket 
            SET     `status` = 2
                    ,`task_completion_timestamp` = todaysdateParam 
            WHERE   bucketid = bucketidParam ;
            
            SET isexecutedOut=1;

        ELSE

            SET isexecutedOut=0;
            SET errormsgOut='Vehicle Not Found';

        END IF;

    END;
    COMMIT;

    SELECT      `name` 
    INTO        elixirOut 
    FROM        team 
    WHERE       teamid = eteamidParam
    ORDER BY    teamid DESC
    LIMIT       1;

    SELECT          username
                    ,realname
                    ,email 
    INTO            usernameOut
                    ,realnameOut
                    ,emailOut
    FROM            `user` 
    LEFT OUTER JOIN groupman p ON p.groupid = groupidVar 
    LEFT OUTER JOIN groupman ON `user`.userid <> groupman.userid 
    WHERE           `user`.customerno = customernoParam 
    AND             `user`.email <> '' 
    AND             `user`.isdeleted=0 
    AND             (`user`.groupid= groupidVar OR `user`.groupid ='0') 
    AND             (`user`.role='Administrator' OR `user`.role = 'Master') 
    ORDER BY        `user`.userid DESC
    LIMIT           1;
        
    SET vehiclenoOut = oldvehiclenoVar;
    SET simcardOut = simcardnoVar;
    SET oldunitOut = oldunitnoVar;
    SET newunitOut=newunitnoVar;
   
END$$
DELIMITER ;