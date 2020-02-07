
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'468', '2017-02-24 12:00:00', 'Arvind Thakur', 'Changes in Re_install SP and repair SP', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `re_install_device`$$
CREATE PROCEDURE `re_install_device`(
    IN todaysdateParam DATETIME
    ,IN unitidParam VARCHAR(11)
    ,IN eteamidParam INT(11)
    ,IN newvehiclenoParam VARCHAR(40)
    ,IN commentParam VARCHAR(40)
    ,IN lteamidParam INT(11)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT newvehiclenoOut VARCHAR(40)
    ,OUT oldvehiclenoOut VARCHAR(40)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(100))

    BEGIN
    DECLARE oldvehicleidVar INT(11);
    DECLARE newvehicleidVar INT(11);
    DECLARE customernoVar INT(11);
    DECLARE oldsimcardidVar INT(11);
    DECLARE groupidVar INT(11);
    DECLARE driveridVar INT(11);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */ 
            SET isexecutedOut = 0;
        END;
        
        SELECT  vehicleid
                ,customerno 
                ,vehicleno
                ,groupid
                ,driverid
        INTO    oldvehicleidVar
                ,customernoVar 
                ,oldvehiclenoOut
                ,groupidVar
                ,driveridVar
        FROM    vehicle 
        WHERE   uid=unitidParam and isdeleted=0;
        
        SELECT  simcardid
        INTO    oldsimcardidVar
        FROM    devices
        WHERE   uid = unitidParam;

    START TRANSACTION;
    BEGIN
        
        
        INSERT  INTO vehicle(`vehicleno`,`uid`,`customerno`,`driverid`) 
        VALUES  (newvehiclenoParam,unitidParam,customernoVar,driveridVar);

        SELECT  LAST_INSERT_ID() 
        INTO    newvehicleidVar;

        UPDATE  unit 
        SET     vehicleid = newvehicleidVar
                ,teamid = eteamidParam
                ,trans_statusid = 5
        WHERE   vehicleid = oldvehicleidVar;

        UPDATE  driver 
        SET     vehicleid = newvehicleidVar
                ,customerno = customernoVar
        WHERE   vehicleid = oldvehicleidVar AND isdeleted = 0;

        UPDATE  eventalerts 
        SET     vehicleid=newvehicleidVar
                ,customerno=customernoVar
        WHERE   vehicleid=oldvehicleidVar;

        UPDATE  ignitionalert 
        SET     vehicleid=newvehicleidVar
                ,customerno=customernoVar
        WHERE   vehicleid=oldvehicleidVar;

        UPDATE  acalerts 
        SET     vehicleid=newvehicleidVar
                ,customerno=customernoVar
        WHERE   vehicleid=oldvehicleidVar;

        UPDATE  checkpointmanage 
        SET     vehicleid=newvehicleidVar
                ,customerno=customernoVar
        WHERE   vehicleid=oldvehicleidVar AND isdeleted=0;

        UPDATE  fenceman 
        SET     vehicleid=newvehicleidVar
                ,customerno=customernoVar
        WHERE   vehicleid=oldvehicleidVar AND isdeleted=0;
        
        UPDATE  vehicle 
        SET     isdeleted=1
                ,uid=0 
        WHERE   vehicleid=oldvehicleidVar;

        INSERT INTO trans_history_new( 
                `oldunitid`
                ,`oldvehicleid`
                ,`newvehicleid`
                ,`oldsimcardid`
                ,`transtypeid`
                ,`bucketstatusid`
                , `remark`
                ,`teamid`
                ,`createdby`
                ,`createdon`,`customerno`
                )
            VALUES(unitidParam
                ,oldvehicleidVar
                ,newvehicleidVar
                ,oldsimcardidVar
                ,6
                ,1
                ,commentParam
                ,eteamidParam
                ,lteamidParam
                ,todaysdateParam
                , customernoVar);
        
        SET isexecutedOut = 1;
    END;
    COMMIT;

    SELECT  `name` 
    INTO    elixirOut 
    FROM    team 
    WHERE   teamid = eteamidParam;
    
    SELECT  c.username
            ,c.realname
            ,c.email 
    INTO    usernameOut
            ,realnameOut
            ,emailOut 
    FROM    `user` c 
    LEFT OUTER JOIN groupman p ON p.groupid = groupidVar 
    LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
    WHERE c.customerno =  customernoVar 
        AND c.email <> '' 
        AND (c.groupid=groupidVar or c.groupid ='0' ) 
        AND (c.role = 'Administrator' OR c.role = 'Master')
    GROUP BY c.userid LIMIT 1;

    SET newvehiclenoOut=newvehiclenoParam;

END$$
DELIMITER $$



DELIMITER $$
DROP PROCEDURE IF EXISTS `repair`$$
CREATE PROCEDURE `repair`(
    IN todaysdateParam DATETIME
    ,IN commentsParam VARCHAR(100)
    ,IN unitidParam INT
    ,IN simcardidParam INT
    ,IN eteamidParam INT
    ,IN lteamidParam INT
    ,IN customernoParam INT
    ,OUT isexecutedOut TINYINT
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnoOut VARCHAR(11)
    ,OUT simnumberOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(100)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;  */  
            SET isexecutedOut = 0;
	END;
    BEGIN    

    DECLARE simcardnoVar VARCHAR(50);
    DECLARE unitnoVar VARCHAR(11);
    DECLARE vehicleidVar INT(11);
    DECLARE thidVar INT(11);
    DECLARE vehiclenoVar VARCHAR(40);
    DECLARE groupidVar INT(11);


    SELECT  simcardno 
    INTO    simcardnoVar 
    FROM    simcard 
    WHERE   id = simcardidParam;

    SELECT  unitno 
    INTO    unitnoVar 
    FROM    unit 
    WHERE   uid =unitidParam;
    
    SELECT  vehicleid 
    INTO    vehicleidVar 
    FROM    vehicle 
    WHERE   uid =unitidParam;

    SELECT  vehicleno,groupid 
    INTO    vehiclenoVar,groupidVar 
    FROM    vehicle 
    WHERE   vehicleid = vehicleidVar;

    START TRANSACTION;
    BEGIN

        UPDATE  unit 
        SET     trans_statusid= 5
                , comments = commentsParam 
        WHERE   uid= unitidParam;

        UPDATE  simcard 
        SET     trans_statusid= 13
                ,comments =commentsParam 
        WHERE   id=simcardidParam;

        INSERT INTO trans_history_new( 
                `oldunitid`
                ,`oldvehicleid`
                ,`oldsimcardid`
                ,`transtypeid`
                ,`bucketstatusid`
                , `remark`
                ,`teamid`
                ,`createdby`
                ,`createdon`,`customerno`
                )
            VALUES (unitidParam
                ,vehicleidVar
                ,simcardidParam
                ,7
                ,1
                ,commentsParam
                ,eteamidParam
                ,lteamidParam
                ,todaysdateParam, customernoParam);

        SET isexecutedOut = 1;
    END;
    COMMIT;

--  Send Email

    SELECT  simcardno 
    INTO    simnumberOut 
    FROM    simcard 
    WHERE   id = simcardidParam;
    
--  $team = lteamidParam;
    SELECT  `name` 
    INTO    elixirOut 
    FROM    team 
    WHERE   teamid = eteamidParam;
    
    SELECT  c.username
            ,c.realname
            ,c.email 
    INTO    usernameOut
            ,realnameOut
            ,emailOut 
    FROM    `user` c 
    LEFT OUTER JOIN groupman p ON p.groupid = groupidVar 
    LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
    WHERE c.customerno = " . customernoParam . " 
        AND c.email <> '' 
        AND (c.groupid=groupidVar or c.groupid ='0' ) 
        AND (c.role = 'Administrator' OR c.role = 'Master')
    GROUP BY c.userid;
        
    SET vehiclenoOut=vehiclenoVar;
    SET unitnoOut=unitnoVar;

    END;
END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2017-02-24 12:00:00'
        ,isapplied =1
WHERE   patchid = 468;