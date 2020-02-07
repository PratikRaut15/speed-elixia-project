
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'469', '2017-02-24 12:00:00', 'Arvind Thakur', 'Changes in Re_install SP and repair SP', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_both`$$
CREATE PROCEDURE `replace_both`(
     IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN oldvehicleidParam INT(11)         
    ,IN oldunitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newunitidParam INT(11)
    ,IN newsimidParam INT(11)
    ,IN commentsParam VARCHAR(50)
    ,IN lteamidParam INT(11)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut varchar(50)
    ,OUT realnameOut varchar(50)
    ,OUT emailOut varchar(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT oldunitOut VARCHAR(11)
    ,OUT oldsimOut VARCHAR(50)
    ,OUT newunitOut VARCHAR(11)
    ,OUT newsimOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT errormsgOut VARCHAR(50) 
    )
BEGIN
    DECLARE newsimcardnoVar VARCHAR(50);
    DECLARE oldsimcardidVar INT(11);
    DECLARE groupidVar INT(11);
    DECLARE oldunitnoVar VARCHAR(11);
    DECLARE oldvehiclenoVar VARCHAR(40);
    DECLARE simcardnumberVar VARCHAR(50);
    DECLARE newunitnoVar VARCHAR(11);
    DECLARE onleaseVar TINYINT(2);
	DECLARE newdeviceid BIGINT(11);
    DECLARE	newvehicleid INT(11);
    DECLARE	vehicleStringVar VARCHAR(20);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;

    SELECT 	simcardno 
	INTO 	newsimcardnoVar 
	FROM 	simcard 
	WHERE 	id =newsimidParam;

    SELECT  devices.simcardid
    INTO    oldsimcardidVar
    FROM    devices 
    WHERE   devices.uid =oldunitidParam;
    
    SELECT  unitno 
    INTO    newunitnoVar 
    FROM    unit 
    WHERE   uid =newunitidParam;

    SELECT  unitno
    INTO    oldunitnoVar
    FROM    unit 
    WHERE   uid =  oldunitidParam;

    SELECT  onlease 
    INTO    onleaseVar
    FROM    unit 
    WHERE   uid = oldunitidParam;
    
    SELECT  vehicleno, groupid
    INTO    oldvehiclenoVar, groupidVar
    FROM    vehicle 
    WHERE   vehicleid =  oldvehicleidParam;

    SELECT  simcardno 
    INTO    simcardnumberVar 
    FROM    simcard 
    WHERE   id = oldsimcardidVar;

	SELECT	deviceid
	INTO 	newdeviceid
    FROM	devices
    WHERE	uid = newunitidParam
	LIMIT	1;
    
    SELECT 	vehicleid
    INTO	newvehicleid
    FROM 	vehicle
    WHERE 	uid = newunitidParam
    ORDER BY vehicleid DESC
    LIMIT	1;
    
    SELECT 	concat('V',oldunitnoVar)
	INTO	vehicleStringVar;
    
    START TRANSACTION;
    BEGIN
        IF oldvehicleidParam <> 0 AND newvehicleid IS NOT NULL THEN
        --  Remove Old Device    
            UPDATE 	unit 
            SET 	customerno=customernoParam
                    , trans_statusid = 5
                    , teamid=0
                    , vehicleid = oldvehicleidParam
                    ,onlease = onleaseVar
            where 	uid=newunitidParam;

            UPDATE  simcard 
            SET     trans_statusid=13
            WHERE   id=oldsimcardidVar;

            UPDATE  devices 
            SET     uid = 0
            WHERE   uid=newunitidParam;

            UPDATE  devices 
            SET     uid = newunitidParam
            WHERE   uid=oldunitidParam;

        --  Populate Vehicles

            UPDATE  vehicle 
            SET     uid=0
            WHERE   uid=newunitidParam;
    
            UPDATE  vehicle 
            SET     uid=newunitidParam
            WHERE   vehicleid = oldvehicleidParam AND customerno=customernoParam;

            SET newunitOut=newunitnoVar;

        --  New Sim Card
            UPDATE  devices 
            SET     simcardid=newsimidParam 
            WHERE   simcardid=oldsimcardidVar;

			UPDATE  simcard
			SET     customerno=customernoParam
					,trans_statusid=13
					,teamid=0
			WHERE   id=newsimidParam;

			UPDATE  simcard 
			SET     customerno=1
					,trans_statusid=21
					,teamid=eteamidParam
			WHERE   id=oldsimcardidVar;

            INSERT INTO trans_history_new( 
                    `oldunitid`
                    ,`newunitid`
                    ,`oldvehicleid`
                    ,`oldsimcardid`
                    ,`newsimcardid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`,`customerno`
                    )
                VALUES (oldunitidParam
                    ,newunitidParam
                    ,oldvehicleidParam
                    ,oldsimcardidVar
                    ,newsimidParam
                    ,5
                    ,1
                    ,commentsParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam, customernoParam);

        --  Replace daily reprt  
            UPDATE  dailyreport 
            SET     uid = newunitidParam
                    , first_odometer=0
                    , last_odometer=0
                    , max_odometer=0 
            WHERE   vehicleid = oldvehicleidParam AND customerno=customernoParam;
            
            UPDATE	devices
            SET 	uid = oldunitidParam
					,simcardid = oldsimcardidVar
                    ,customerno = 1
            WHERE	deviceid = newdeviceid;
            
            UPDATE	vehicle
            SET		uid = oldunitidParam
					,customerno = 1
                    ,vehicleno = vehicleStringVar
            WHERE	vehicleid = newvehicleid;
            
            SET isexecutedOut = 1;

        ELSE
            SET isexecutedOut = 0;
            SET errormsgOut= 'Vehicle Not found';
        END IF;
    END;
    COMMIT;

    SELECT  `name` 
    INTO    elixirOut 
    FROM    team 
    WHERE   teamid = eteamidParam ;

    SELECT  c.username
            ,c.realname
            ,c.email 
    INTO    usernameOut
            ,realnameOut
            ,emailOut
    FROM    `user` c 
    LEFT OUTER JOIN groupman p on p.groupid =  1 
    LEFT OUTER JOIN groupman on c.userid <> groupman.userid 
    WHERE   c.customerno = customernoParam
    AND     c.email <> ''
    AND     c.isdeleted=0 
    AND     (c.groupid=groupidVar OR c.groupid ='0' ) 
    AND     (c.role = 'Administrator' OR c.role = 'Master') group by c.userid LIMIT 1;
	
    SET vehiclenoOut=oldvehiclenoVar;
    SET oldunitOut=oldunitnoVar;
    SET oldsimOut=simcardnumberVar;
    SET newunitOut=newunitnoVar;
    SET newsimOut=newsimcardnoVar;

END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2017-03-06 16:00:00'
        ,patchdesc = 'replace_both SP modified'
        ,isapplied =1
WHERE   patchid = 469;
