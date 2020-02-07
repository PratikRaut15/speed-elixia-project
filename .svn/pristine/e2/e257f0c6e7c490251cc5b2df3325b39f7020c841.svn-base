
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'430', '2016-11-23 17:11:01', 'Arvind Thakur', 'SP for register Device', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS unit_of_teamid$$
CREATE PROCEDURE unit_of_teamid(
	IN teamidparam INT
	)
BEGIN
SELECT unit.unitno, unit.uid FROM unit 
    INNER JOIN trans_status ON trans_status.id = unit.trans_statusid 
    WHERE trans_statusid IN (18,20) AND unit.teamid=teamidparam;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS sim_of_teamid$$
CREATE PROCEDURE sim_of_teamid(
	IN teamidparam INT
	)
BEGIN
SELECT simcard.id as simid, simcard.simcardno FROM simcard 
INNER JOIN trans_status ON trans_status.id = simcard.trans_statusid 
WHERE trans_statusid IN (19,21) AND simcard.teamid=teamidparam;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pull_team$$
CREATE PROCEDURE pull_team()

BEGIN
SELECT teamid,name FROM team;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `register_device`$$
CREATE PROCEDURE `register_device`(
    IN todaysdateParam DATETIME
    ,IN commentsParam VARCHAR(100)
    ,IN unitidParam INT
    ,IN utypeParam INT
    ,IN simcardidParam INT
    ,IN customernoParam INT
    ,IN ponoParam INT
    ,IN podateParam DATE
    ,IN expirydateParam DATE
    ,IN installdateParam DATE
    ,IN invoicenoParam VARCHAR(50)
    ,IN vehiclenoParam VARCHAR(50)
    ,IN leaseParam TINYINT(1)
    ,IN eteamidParam INT
    ,IN lteamidParam INT
    ,OUT isexecutedOut TINYINT
    ,OUT usernameOut VARCHAR(100)
    ,OUT realnameOut VARCHAR(100)
    ,OUT emailOut VARCHAR(100)
    ,OUT unitnumberOut INT
    ,OUT simcardnoOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(100)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
           /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;   */
            SET isexecutedOut = 0;
	END;
    BEGIN    
    DECLARE cstypeVar INT DEFAULT 13;
    DECLARE warrantyVar DATETIME DEFAULT DATE_ADD(CURRENT_DATE, INTERVAL 365 DAY);
    DECLARE lasttransVar INT DEFAULT 0;
    DECLARE vehicleidVar INT(11);
    DECLARE useridVar INT(11);
    DECLARE panicVar TINYINT(1);
    DECLARE buzzerVar TINYINT(4);
    DECLARE mobiliserVar TINYINT(1);
    DECLARE str1Var text;
    DECLARE str2Var text;

    IF utypeParam = 23 THEN
        SET cstypeVar = 24;
    END IF;
    
    IF utypeParam = 22 THEN
        SET cstypeVar = 25;
    END IF;

    IF(vehiclenoParam = '' OR vehiclenoParam = '0') THEN
	SET vehiclenoParam = NULL;
    END IF;

    SELECT  vehicleid INTO vehicleidVar  
    FROM    vehicle 
    WHERE   uid = unitidParam;

    SELECT  unitno
            ,is_panic
            ,is_buzzer
            ,is_mobiliser 
    INTO    unitnumberOut
            ,panicVar
            ,buzzerVar
            ,mobiliserVar 
    FROM    unit 
    WHERE   uid = unitidParam;
        
    SELECT  simcardno 
    INTO    simcardnoOut 
    FROM    simcard 
    WHERE   id = simcardidParam;

    SELECT  CONCAT('Registered - Installed on:',DATE_FORMAT(STR_TO_DATE(installdateParam,'%Y-%m-%d'),'%d/%m/%Y')) 
    INTO    str1Var;

    SELECT  CONCAT('Registered Unit #',unitnumberOut,' with Sim #',simcardnoOut) 
    INTO    str2Var;
	
    SELECT  userid 
    INTO    useridVar 
    FROM    `user` 
    WHERE   isdeleted=0 
    AND     customerno=customernoParam LIMIT 1;

    START TRANSACTION;	 
    BEGIN
        UPDATE  unit 
        SET     customerno=customernoParam
                , trans_statusid = utypeParam
                ,teamid=0
                , comments = commentsParam 
        WHERE   uid=unitidParam;

        UPDATE  simcard 
        SET     customerno=customernoParam
                ,trans_statusid = cstypeVar
                ,teamid=0
                ,comments = commentsParam 
        WHERE   id=simcardidParam;
		
        IF simcardidParam <> 0 THEN
            UPDATE  devices 
            SET     simcardid=0 
            WHERE   simcardid=simcardidParam;
	END IF;

        UPDATE  devices 
        SET     customerno=customernoParam
                ,simcardid=simcardidParam
                ,expirydate=expirydateParam
                ,installdate=installdateParam
                ,invoiceno=invoicenoParam
                ,po_no=ponoParam
                ,po_date=podateParam
                ,warrantyexpiry=warrantyVar 
        WHERE   uid=unitidParam;

        IF  vehiclenoParam = NULL OR vehiclenoParam='' THEN
            UPDATE  vehicle 
            SET     customerno=customernoParam 
            WHERE   uid = unitidParam;
        ELSE
            UPDATE  vehicle 
            SET     customerno=customernoParam
                    ,vehicleno=vehiclenoParam
                    ,stoppage_transit_time = todaysdateParam 
            WHERE   uid = unitidParam;
        END IF;
	
        UPDATE  driver 
        SET     customerno= customernoParam 
        WHERE   vehicleid= vehicleidVar;

        UPDATE  eventalerts 
        SET     customerno= customernoParam 
        WHERE   vehicleid=vehicleidVar;

        UPDATE  ignitionalert 
        SET     customerno= customernoParam 
        WHERE   vehicleid= vehicleidVar;

        UPDATE  acalerts 
        SET     customerno= customernoParam 
        WHERE   vehicleid= vehicleidVar;

        --     for lease
        IF leaseParam=1 THEN
            UPDATE  unit 
            SET     onlease=leaseParam 
            WHERE   uid =unitidParam;
        END IF;

        IF panicVar = 1 THEN
            UPDATE  customer 
            SET     use_panic=1 
            WHERE   customerno=customernoParam;
        END IF;

        IF buzzerVar = 1 THEN
            UPDATE  customer 
            SET     use_buzzer=1 
            WHERE   customerno=customernoParam;
        END IF;

        IF mobiliserVar = 1 THEN
            UPDATE  customer 
            SET     use_immobiliser=1 
            WHERE   customerno=customernoParam;
        END IF;
		
        IF simcardnoOut != '' then
            INSERT INTO trans_history(`customerno` 
                ,`unitid`
                ,`teamid`
                , `type`
                , `trans_time`
                , `statusid`
                , `transaction`
                , `simcardno`
                , `invoiceno`
                , `expirydate`
                , `comments`
                , `vehicleid`)
            VALUES (customernoParam
                , unitidParam
                , lteamidParam
                , 0
                , todaysdateParam
                , utypeParam
                , str1Var
                , simcardnoOut
                , invoicenoParam
                , expirydateParam
                , commentsParam
                , vehicleidVar);
			
            INSERT INTO trans_history(`customerno` 
                ,`unitid`
                ,`teamid`
                , `type`
                , `trans_time`
                , `statusid`
                , `transaction`
                , `simcardno`
                , `invoiceno`
                , `expirydate`
                , `comments`
                , `vehicleid`)
            VALUES (customernoParam
                , 0
                , lteamidParam
                , 2
                , todaysdateParam
                , 0
                , str2Var
                , ''
                , ''
                , ''
                , commentsParam
                , vehicleidVar);

            SELECT  LAST_INSERT_ID() 
            INTO    lasttransVar;

            INSERT INTO trans_history(`customerno` 
                ,`simcard_id`
                ,`teamid`
                , `type`
                , `trans_time`
                , `statusid`
                , `transaction`
                , `simcardno`
                , `invoiceno`
                , `expirydate`
                , `comments`
                , `vehicleid`)
            VALUES (customernoParam
                , simcardidParam
                , lteamidParam
                , 1
                , todaysdateParam
                , cstypeVar
                , 'Registered'
                ,simcardnoOut
                ,''
                ,''
                , commentsParam
                , vehicleidVar);
        END IF;

--  daily report insert / vehiclewise_alert
    INSERT INTO dailyreport(`customerno`
        , `vehicleid`
        , `uid`
        ,`last_online_updated`)
    VALUES (customernoParam
        ,vehicleidVar
        ,unitidParam
        ,todaysdateParam);
    
    INSERT INTO vehiclewise_alert (`customerno`
        , `userid`
        , `vehicleid`
        ,`temp_active`
        ,`ignition_active`
        ,`speed_active`
        ,`ac_active`
        ,`powerc_active`
        ,`tamper_active`
        ,`harsh_break_active`
        ,`high_acce_active`
        ,`panic_active`
        ,`door_active`)
    SELECT customerno
        ,userid
        ,vehicleid
        ,temp_active
        ,ignition_active
        ,speed_active
        ,ac_active
        ,powerc_active
        ,tamper_active
        ,harsh_break_active
        ,high_acce_active
        ,panic_active
        ,door_active 
        FROM vehiclewise_alert 
        WHERE userid = useridVar AND customerno= customernoParam limit 1;                             

    IF utypeParam = 23 THEN
--      Service Call
        INSERT INTO servicecall (`uid`
            ,`simcardid`
            ,`vehicleno`
            , `thid`
            , `teamid`
            , `type`)
        VALUES (unitidParam 
            , simcardidParam
            , vehiclenoParam
            , lasttransVar
            ,eteamidParam
            , 0);

    ELSE IF utypeParam = 22 THEN
--      Service Call
        INSERT INTO servicecall (`uid` 
            ,`simcardid`
            ,`vehicleno`
            , `thid`
            , `teamid`
            , `type`)
        VALUES (unitidParam
            , simcardidParam
            , vehiclenoParam
            , lasttransVar
            , eteamidParam
            , 7);
    ELSE
--      Service Call
        INSERT INTO servicecall (`uid` 
            ,`simcardid`
            ,`vehicleno`
            , `thid`
            , `teamid`
            , `type`)
        VALUES (unitidParam
            , simcardidParam
            , vehiclenoParam
            , lasttransVar
            , eteamidParam
            ,1);
    END IF;
    END IF;
    SET isexecutedOut = 1;
END;
COMMIT;    
    SELECT  `name` INTO elixirOut 
    FROM    team 
    WHERE   teamid = eteamidParam;

    SELECT  username
            ,realname
            ,email 
    INTO    usernameOut
            ,realnameOut
            ,emailOut 
    FROM    `user` 
    INNER JOIN groupman ON groupman.userid  <> `user`.userid 
    WHERE   `user`.customerno = customernoParam 
    AND     `user`.email <> '' 
    AND     (`user`.role = 'Administrator' 
    OR      `user`.role = 'Master') LIMIT 1;
	
END;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS authenticate_for_team_login$$
CREATE PROCEDURE authenticate_for_team_login(
	 IN usernameparam VARCHAR(50)
	,IN passparam VARCHAR(150)
	,OUT userkeyparam VARCHAR(150)
        ,OUT teamidparam INT
)
BEGIN
        DECLARE userkeydata VARCHAR(150);
	DECLARE teamiddata INT;
  
	SELECT  teamid,userkey
	INTO	teamiddata,userkeydata
	FROM    team
	WHERE   username = usernameparam
	AND 	`password` = passparam;
	
	IF (teamiddata IS NULL)THEN 
            BEGIN
		SET userkeyparam='Empty';
            END;
        ELSE
            BEGIN
                IF (userkeydata IS NULL OR userkeydata='') THEN 
                    BEGIN 
                        UPDATE team SET userkey=FLOOR(1+RAND()*10000) WHERE teamid=teamiddata;
                        SELECT userkey INTO userkeydata FROM team where teamid=teamiddata;
                        SET userkeyparam = userkeydata;
                        SET teamidparam=teamiddata;
                    END;
                ELSE
                    BEGIN
                        SELECT userkey INTO userkeydata FROM team where teamid=teamiddata;
                        SET userkeyparam = userkeydata;
                        SET teamidparam=teamiddata;
                    END;
                END IF;
            END;
        END IF;

END$$
DELIMITER ;

UPDATE 	dbpatches
SET 	patchdate = NOW()
	, isapplied =1
WHERE 	patchid = 430;