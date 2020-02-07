/*
    Name		-	replace_sim
    Description 	-	replace simcard
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
DROP PROCEDURE IF EXISTS `replace_sim`$$
CREATE PROCEDURE `replace_sim`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN oldvehicleidParam INT(11)     
    ,IN unitidParam INT(11)    
    ,IN eteamidParam INT(11)
    ,IN newsimidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(50)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut varchar(50)
    ,OUT realnameOut varchar(50)
    ,OUT emailOut varchar(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT oldsimcardnoOut VARCHAR(50)
    ,OUT newsimcardnoOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    )

BEGIN
    DECLARE oldsimcardidVar INT;
    DECLARE simdeviceidVar INT;
    DECLARE oldsimcardnoVar VARCHAR(50);
    DECLARE newsimcardnoVar VARCHAR(50);
    DECLARE vehiclenoVar VARCHAR(50);
    DECLARE groupidVar INT(11);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;

    SELECT      simcardid
                ,deviceid 
    INTO        oldsimcardidVar
                ,simdeviceidVar 
    FROM        devices 
    WHERE       uid = unitidParam
    ORDER BY    deviceid DESC
    LIMIT       1;

    SELECT      simcardno 
    INTO        oldsimcardnoVar 
    FROM        simcard 
    WHERE       id = oldsimcardidVar
    ORDER BY    id DESC
    LIMIT       1;
    
    SELECT      simcardno 
    INTO        newsimcardnoVar 
    FROM        simcard 
    WHERE       id = newsimidParam
    ORDER BY    id DESC
    LIMIT       1;

	-- select vehicleid;
    SELECT      vehicleno
                ,groupid 
    INTO        vehiclenoVar
                ,groupidVar 
    FROM        vehicle 
    WHERE       vehicleid = oldvehicleidParam
    AND         isdeleted = 0
    ORDER BY    vehicleid DESC
    LIMIT       1;

    START TRANSACTION;
    BEGIN

        UPDATE  unit 
        SET     trans_statusid = 5
        WHERE   uid = unitidParam;

    --  New Sim Card
        UPDATE  devices 
        SET     simcardid = newsimidParam 
        WHERE   simcardid = oldsimcardidVar 
        AND     deviceid = simdeviceidVar;

        UPDATE  simcard
        SET     customerno = customernoParam
                ,trans_statusid = 13
                ,teamid = 0
        WHERE   id = newsimidParam;

        UPDATE  simcard 
        SET     customerno = 1
                ,trans_statusid = 21
                ,teamid = eteamidParam
        WHERE   id = oldsimcardidVar;

        INSERT INTO trans_history_new(`bucketid` 
                ,`oldunitid`
                ,`oldvehicleid`
                ,`oldsimcardid`
                ,`newsimcardid`
                ,`transtypeid`
                ,`bucketstatusid`
                , `remark`
                ,`teamid`
                ,`createdby`
                ,`createdon`
                ,`customerno`)
        VALUES (bucketidParam
                ,unitidParam
                ,oldvehicleidParam
                ,oldsimcardidVar
                ,newsimidParam
                ,3
                ,1
                ,commentParam
                ,eteamidParam
                ,lteamidParam
                ,todaysdateParam
                ,customernoParam);
		
        UPDATE 	bucket 
        SET 	`status` = 2
                ,`task_completion_timestamp` = todaysdateParam 
        WHERE	bucketid= bucketidParam ;
                
        SET isexecutedOut = 1;

    END;
    COMMIT;

    SELECT      `name` 
    INTO        elixirOut 
    FROM        team 
    WHERE       teamid = eteamidParam
    ORDER BY    teamid DESC
    LIMIT       1;

    SELECT          c.username
                    ,c.realname
                    ,c.email 
    INTO            usernameOut
                    ,realnameOut
                    ,emailOut 
    FROM            `user` c 
    LEFT OUTER JOIN groupman p ON p.groupid = groupidVar 
    LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
    WHERE           c.customerno = customernoParam 
    AND             c.email <> '' 
    AND             c.isdeleted=0 
    AND             (c.groupid=groupidVar OR c.groupid ='0' ) 
    AND             (c.`role` = 'Administrator' OR c.`role` = 'Master')
    ORDER BY        c.userid DESC
    LIMIT           1;
    
    SET vehiclenoOut=vehiclenoVar;
    SET oldsimcardnoOut=oldsimcardnoVar;
    SET newsimcardnoOut=newsimcardnoVar;

END$$
DELIMITER ;