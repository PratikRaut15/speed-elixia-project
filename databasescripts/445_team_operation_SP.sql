-- 
-- INSERT INTO `speed`.`dbpatches` (
-- `patchid` ,
-- `patchdate` ,
-- `appliedby` ,
-- `patchdesc` ,
-- `isapplied`
-- )
-- VALUES (
-- '445', '2016-01-02 12:50:00', 'Arvind Thakur', 'Operations - 5 Rs team changes', '0'
-- );


-- Register Device SP
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
            INSERT INTO trans_history_new( 
                `newunitid`
                ,`newvehicleid`
                ,`newsimcardid`
                ,`transtypeid`
                ,`bucketstatusid`
                , `remark`
                ,`teamid`
                ,`createdby`
                ,`createdon`)
            VALUES (unitidParam
                ,vehicleidVar
                ,simcardidParam
                ,1
                ,1
                ,commentsParam
                ,eteamidParam
                ,lteamidParam
                ,todaysdateParam);

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


-- Remove bad unit and sim

DELIMITER $$
DROP PROCEDURE IF EXISTS `remove_unit_sim`$$
CREATE PROCEDURE `remove_unit_sim`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT
    ,IN unitidParam INT
    ,IN eteamidParam INT
    ,IN commentsParam VARCHAR(50)
    ,IN lteamidParam INT
    ,OUT isexecutedOut INT
    ,OUT usernameOut varchar(50)
    ,OUT realnameOut varchar(50)
    ,OUT emailOut varchar(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnumverOut VARCHAR(11)
    ,OUT simnumberOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(40))

    BEGIN
    DECLARE simcardidVar INT(11);
    DECLARE unitnoVar VARCHAR(11);
    DECLARE vehicleidVar INT(11);
    DECLARE groupidVar INT(11);
    DECLARE vehiclenoVar VARCHAR(40);
    DECLARE thidVar INT(11);
    DECLARE simcardnoVar VARCHAR(50);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
          /*  ROLLBACK;
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;


    SELECT simcardid INTO simcardidVar FROM devices WHERE uid =  unitidParam;

    SELECT simcardno INTO simcardnoVar FROM simcard WHERE id = simcardidVar;

      SELECT unitno INTO unitnoVar FROM unit WHERE uid = unitidParam;
    

    SELECT vehicleid,groupid INTO vehicleidVar,groupidVar FROM vehicle WHERE uid =  unitidParam;
    
    SELECT vehicleno INTO vehiclenoVar FROM vehicle WHERE vehicleid =  vehicleidVar;

    START TRANSACTION;
    BEGIN

    UPDATE unit SET trans_statusid= 20,teamid= eteamidParam , comments =commentsParam WHERE uid= unitidParam;    

    UPDATE simcard SET trans_statusid= 21,teamid=eteamidParam, comments = commentsParam WHERE id= simcardidVar;

    
--  Daily report both unit remove bad
    DELETE FROM dailyreport WHERE customerno = customernoParam  AND uid= unitidParam;

    INSERT INTO trans_history_new(`oldunitid`
            ,`oldvehicleid`
            ,`oldsimcardid`
            ,`transtypeid`
            ,`bucketstatusid`
            , `remark`
            ,`teamid`
            ,`createdby`
            ,`createdon`)
    VALUES (unitidParam
            ,vehicleidVar
            ,simcardidVar
            ,2
            ,1
            ,commentsParam
            ,eteamidParam
            ,lteamidParam
            ,todaysdateParam);

--  Customerno - Make it 1
    UPDATE unit SET customerno=1,userid=0, comments = commentsParam WHERE uid= unitidParam;
    

    UPDATE devices 
    SET customerno=1
    , expirydate='0000-00-00'
    , device_invoiceno = ''
    , inv_generatedate = '0000-00-00 00:00:00'
    ,po_no=''
    , po_date='0000-00-00'
    , invoiceno=''
    , installdate='0000-00-00' 
    WHERE uid=unitidParam;
    

--  unset lease on old device
    UPDATE unit SET onlease=0 WHERE uid = unitidParam;
    
--  Populate Vehicles

    UPDATE vehicle SET customerno=1 WHERE uid = unitidParam;
    
    UPDATE driver SET customerno=1 WHERE vehicleid= vehicleidVar;

    UPDATE eventalerts SET customerno=1 WHERE vehicleid= vehicleidVar;

    UPDATE ignitionalert SET customerno=1 WHERE vehicleid= vehicleidVar;

    UPDATE acalerts SET customerno=1 WHERE vehicleid= vehicleidVar;

    UPDATE checkpointmanage SET customerno=1, isdeleted=1 WHERE vehicleid= vehicleidVar;

    UPDATE fenceman SET customerno=1, isdeleted=1 WHERE vehicleid= vehicleidVar;

    UPDATE groupman SET customerno=1, isdeleted=1 WHERE vehicleid= vehicleidVar;

    UPDATE reportman SET customerno=1 WHERE uid= unitidParam;
    SET isexecutedOut=1;
    END;
    COMMIT;
    
    SELECT `name` INTO elixirOut FROM team WHERE teamid =  eteamidParam;

    SELECT c.username,c.realname,c.email INTO usernameOut,realnameOut,emailOut from `user` c 
LEFT OUTER JOIN groupman p on p.groupid = groupidVar  
LEFT OUTER JOIN groupman on c.userid <> groupman.userid 
WHERE c.customerno = customernoParam  
AND c.email <> ''and c.isdeleted=0 
AND (c.groupid=groupidVar  OR c.groupid ='0') 
AND (c.`role` = 'Administrator' OR c.`role` = 'Master') group by c.userid LIMIT 1;

SET vehiclenoOut=vehiclenoVar;
SET unitnumverOut=unitnoVar;
SET simnumberOut=simcardnoVar;


END$$
DELIMITER ;


-- Replace Simcard

DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_sim`$$
CREATE PROCEDURE `replace_sim`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT
    ,IN unitidParam INT
    ,IN eteamidParam INT
    ,IN newsimidParam INT
    ,IN commentParam VARCHAR(50)
    ,IN lteamidParam INT
    ,OUT isexecutedOut INT
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
    DECLARE vehicleidVar INT;
    DECLARE str1Var VARCHAR(40);
    DECLARE str2Var VARCHAR(50);
    DECLARE str3Var VARCHAR(40);
    DECLARE vehiclenoVar VARCHAR(50);
    DECLARE groupidVar INT(11);
    DECLARE thidVar INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;

    SELECT  simcardid
            ,deviceid 
    INTO    oldsimcardidVar
            ,simdeviceidVar 
    FROM    devices 
    WHERE   uid = unitidParam;

    SELECT  simcardno 
    INTO    oldsimcardnoVar 
    FROM    simcard 
    WHERE   id = oldsimcardidVar;
    

    SELECT simcardno 
    INTO    newsimcardnoVar 
    FROM    simcard 
    WHERE   id = newsimidParam ;

    SELECT vehicleid 
    INTO    vehicleidVar 
    FROM    vehicle 
    WHERE   uid = unitidParam;
    
	-- select vehicleid;
    SELECT  vehicleno
            ,groupid 
    INTO    vehiclenoVar
            ,groupidVar 
    FROM    vehicle 
    WHERE   vehicleid = vehicleidVar;

    START TRANSACTION;
    BEGIN
        UPDATE  unit 
        SET     trans_statusid = 5
                , comments = commentParam 
        WHERE   uid=unitidParam;

    --      New Sim Card
        UPDATE  devices 
        SET     simcardid=newsimidParam 
        WHERE   simcardid=oldsimcardidVar 
        AND     deviceid=simdeviceidVar;

        UPDATE  simcard 
        SET     customerno=customernoParam
                ,trans_statusid=13
                ,teamid=0
                , comments = commentParam 
        WHERE   id=newsimidParam;

        UPDATE  simcard 
        SET     customerno=1
                ,trans_statusid=21
                ,teamid=eteamidParam
                , comments =commentParam 
        WHERE   id=oldsimcardidVar;

        INSERT INTO trans_history_new( 
            `oldunitid`
            ,`oldvehicleid`
            ,`oldsimcardid`
            ,`newsimcardid`
            ,`transtypeid`
            ,`bucketstatusid`
            , `remark`
            ,`teamid`
            ,`createdby`
            ,`createdon`
            )
        VALUES (unitidParam
            ,vehicleidVar
            ,oldsimcardidVar
            ,newsimidParam
            ,3
            ,1
            ,commentParam
            ,eteamidParam
            ,lteamidParam
            ,todaysdateParam);

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
    LEFT OUTER JOIN groupman p on p.groupid = groupidVar 
    LEFT OUTER JOIN groupman on c.userid <> groupman.userid 
    WHERE c.customerno = customernoParam AND c.email <> '' AND c.isdeleted=0 AND (c.groupid=groupidVar OR c.groupid ='0' ) AND (c.`role` = 'Administrator' OR c.`role` = 'Master') GROUP BY c.userid LIMIT 1;
    
    SET vehiclenoOut=vehiclenoVar;
    SET oldsimcardnoOut=oldsimcardnoVar;
    SET newsimcardnoOut=newsimcardnoVar;

END$$
DELIMITER ;


-- Replace Device

DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_device`$$
CREATE PROCEDURE `replace_device`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT
    ,IN oldunitidParam INT
    ,IN eteamidParam INT
    ,IN newunitidParam INT
    ,IN commentParam VARCHAR(100)
    ,IN lteamidParam INT
    ,OUT isexecutedOut TINYINT
    ,OUT usernameOut VARCHAR(100)
    ,OUT realnameOut VARCHAR(100)
    ,OUT emailOut VARCHAR(100)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT oldunitOut INT
    ,OUT newunitOut INT
    ,OUT simcardOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(100)
    ,OUT errormsgOut VARCHAR(50)
)
BEGIN
    DECLARE thidParam INT;
    DECLARE pnoVar INT;
    DECLARE pdateVar DATE;
    DECLARE deviceinvoicenoVar INT;
    DECLARE invoicegeneratedateVar DATE;
    DECLARE expirydateVar DATE;
    DECLARE invoicenoVar INT;
    DECLARE oldsimcardidVar INT;
    DECLARE installdateVar DATE;
    DECLARE groupidVar INT;
    DECLARE oldvehiclenoVar VARCHAR(40);
    DECLARE newvehicleidVar INT;
    DECLARE newunitnoVar INT;
    DECLARE oldunitnoVar INT;
    DECLARE oldcustomernoVar INT;
    DECLARE oldvehicleidVar INT;
    DECLARE vehiclenoStringVar VARCHAR(20);
    DECLARE newcustomernoVar INT;
    DECLARE simcardnoVar VARCHAR(50);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;
    
    SELECT  devices.po_no
            ,devices.po_date
            ,devices.device_invoiceno
            , devices.inv_generatedate
            , devices.expirydate
            , devices.invoiceno
            , devices.simcardid
            , devices.installdate
            , vehicle.groupid
            , vehicle.vehicleno 
    INTO    pnoVar
            ,pdateVar
            ,deviceinvoicenoVar
            ,invoicegeneratedateVar
            ,expirydateVar
            ,invoicenoVar
            ,oldsimcardidVar
            ,installdateVar
            ,groupidVar
            ,oldvehiclenoVar 
    FROM    devices 
    INNER   JOIN vehicle ON vehicle.uid = devices.uid 
    WHERE   devices.uid =oldunitidParam;
    
    SELECT  v.vehicleid 
    INTO    newvehicleidVar 
    FROM    vehicle v
    INNER JOIN unit ON unit.vehicleid=v.vehicleid
    WHERE   unit.uid=newunitidParam;
    
    SELECT  unitno 
    INTO    newunitnoVar 
    FROM    unit 
    WHERE   uid =newunitidParam;

    SELECT  unitno
            ,customerno
    INTO    oldunitnoVar
            ,oldcustomernoVar
    FROM    unit 
    WHERE   uid=oldunitidParam;

    
    SELECT  CONCAT('V',oldunitnoVar) 
    INTO    vehiclenoStringVar;

    SELECT  v.vehicleid 
    INTO    oldvehicleidVar 
    FROM    vehicle v 
    INNER JOIN unit ON unit.vehicleid=v.vehicleid
    WHERE   unit.uid = oldunitidParam;

    SELECT  customerno 
    INTO    newcustomernoVar 
    FROM    unit 
    WHERE   uid = oldunitidParam;
    
    SELECT  simcardno 
    INTO    simcardnoVar 
    FROM    simcard 
    WHERE   id = oldsimcardidVar;
    
    START TRANSACTION;

    IF oldvehicleidVar <> 0 AND newvehicleidVar <> 0 THEN
            UPDATE 	unit 
            SET 	customerno=customernoParam
                    , trans_statusid = 5
                    , teamid=0
                    ,comments=commentParam 
            where 	uid=newunitidParam;

            UPDATE  simcard 
            SET     trans_statusid=13
                    ,comments =commentParam 
            WHERE   id=oldsimcardidVar;

            UPDATE  devices 
            SET     po_no = pnoVar
                    , po_date=pdateVar
                    , device_invoiceno=deviceinvoicenoVar
                    ,inv_generatedate=invoicegeneratedateVar
                    , customerno=customernoParam
                    , simcardid=oldsimcardidVar
                    , expirydate = STR_TO_DATE(expirydateVar, '%Y-%m-%d')
                    , invoiceno =invoicenoVar
                    , installdate =installdateVar 
            WHERE   uid=newunitidParam;

            -- Populate Vehicles    

            -- old groupid set for new unit 
    --      TODO
            UPDATE  vehicle 
            SET     customerno=customernoParam
                    ,vehicleno=oldvehiclenoVar
                    ,groupid=oldgroupid 
            WHERE   uid =newunitidParam;

            UPDATE  driver 
            SET     customerno=customernoParam 
            WHERE   vehicleid=newvehicleidVar;

            UPDATE  eventalerts 
            SET     customerno=customernoParam 
            WHERE   vehicleid=newvehicleidVar;


            UPDATE  ignitionalert 
            SET     customerno=customernoParam 
            WHERE   vehicleid=vehicleId;

            UPDATE  acalerts 
            SET     customerno=customernoParam 
            WHERE   vehicleid=newvehicleidVar;

            SET newunitOut=newunitnoVar;

    --      set lease on new device
            UPDATE  unit 
            SET     onlease=1 
            WHERE   uid =newunitidParam;

    --      unset lease on old device
            UPDATE  unit 
            SET     onlease=0 
            WHERE   uid =oldunitidParam;

    --      Remove Old Device    
            UPDATE  unit 
            SET     customerno=1
                    , userid=0
                    ,  trans_statusid = 20
                    ,teamid=eteamidParam
                    , comments = commentParam 
            WHERE   uid=oldunitidParam;

            UPDATE  devices 
            SET     customerno=1
                    , simcardid = 0
                    , device_invoiceno = ''
                    , inv_generatedate='0000-00-00 00:00:00'
                    ,po_no=''
                    , po_date='00000-00-00'
                    , expirydate= STR_TO_DATE('0000-00-00', '%Y-%m-%d')
                    , invoiceno=''
                    , installdate='0000-00-00' 
            WHERE   uid=oldunitidParam;

    --      Delete from (checkpointmanage / fenceman)
            UPDATE  checkpointmanage 
            SET     isdeleted=1 
            WHERE   vehicleid=oldvehicleidVar AND customerno=oldcustomernoVar;

            UPDATE  fenceman 
            SET     isdeleted=1 
            WHERE vehicleid=oldvehicleidVar AND customerno=oldcustomernoVar;

    --  Populate Vehicles

            UPDATE  vehicle 
            SET     customerno=1
                    ,groupid=0
                    , vehicleno = vehiclenoStringVar 
            WHERE   uid =oldunitidParam;

            UPDATE  driver 
            SET     customerno=1 
            WHERE   vehicleid=oldvehicleidVar;

            UPDATE  eventalerts 
            SET     customerno=1 
            WHERE   vehicleid=oldvehicleidVar;

            UPDATE  ignitionalert 
            SET     customerno=1 
            WHERE   vehicleid=oldvehicleidVar;

            UPDATE  acalerts 
            SET     customerno=1 
            WHERE   vehicleid=oldvehicleidVar;

    --      Daily report update replace unit for customer 
            UPDATE  dailyreport 
            SET     uid = newunitidParam
                    , vehicleid = newvehicleidVar
                    , first_odometer=0
                    , last_odometer=0
                    , max_odometer=0 
            WHERE   uid = oldunitidParam AND customerno=customernoParam;

                INSERT INTO trans_history_new( 
                    `oldunitid`
                    ,`newunitid`
                    ,`oldvehicleid`
                    ,`oldsimcardid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    )
                VALUES (oldunitidParam
                    ,newunitidParam
                    ,oldvehicleidVar
                    ,oldsimcardidVar
                    ,4
                    ,1
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam);

            SET isexecutedOut=1;

            ELSE
                SET isexecutedOut=0;
                SET errormsgOut='Vehicle Not Found';
  
        END IF;
    COMMIT;

    SELECT  `name` 
    INTO    elixirOut 
    FROM    team 
    WHERE   teamid = eteamidParam;

    SELECT username
            ,realname
            ,email 
    INTO    usernameOut
            ,realnameOut
            ,emailOut
    FROM    `user` 
    LEFT OUTER JOIN groupman p on p.groupid = groupidVar 
    LEFT OUTER JOIN groupman ON `user`.userid <> groupman.userid 
    WHERE   `user`.customerno = customernoParam 
    AND     `user`.email <> '' 
    AND     `user`.isdeleted=0 
    AND     (`user`.groupid= groupidVar OR `user`.groupid ='0') 
    AND     (`user`.role='Administrator' OR `user`.role = 'Master') 
    GROUP BY `user`.userid;
        
    SET vehiclenoOut=oldvehiclenoVar;
    SET simcardOut=simcardnoVar;
    SET oldunitOut=oldunitnoVar;
   
END$$
DELIMITER ;


-- Replace both unit and sim

DELIMITER $$
DROP PROCEDURE IF EXISTS `replace_both`$$
CREATE PROCEDURE `replace_both`(
     IN todaysdateParam DATETIME
    ,IN customernoParam INT
    ,IN oldunitidParam INT
    ,IN eteamidParam INT
    ,IN newunitidParam INT
    ,IN newsimidParam INT
    ,IN commentsParam VARCHAR(50)
    ,IN lteamidParam INT
    ,OUT isexecutedOut INT
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
    DECLARE thidVar INT;
    DECLARE newsimcardnoVar VARCHAR(50);
    DECLARE devinvoicenoVar VARCHAR(50);
    DECLARE invGenerateDateVar DATE;
    DECLARE expiryDateVar DATE;
    DECLARE invoiceNoVar VARCHAR(50);
    DECLARE oldsimcardidVar INT(11);
    DECLARE installDateVar DATE;
    DECLARE pnoVar VARCHAR(50);
    DECLARE pdateVar DATE;
    DECLARE oldgroupidVar INT(11);
    DECLARE oldvehicleidVar INT(11);
    DECLARE oldunitnoVar VARCHAR(11);
    DECLARE oldcustomernoVar INT(11);
    DECLARE olddriveridVar INT(11);
    DECLARE oldvehiclenoVar VARCHAR(40);
    DECLARE vehiclenoUpdateVar VARCHAR(20);
    DECLARE simcardnumberVar VARCHAR(50);
    DECLARE newdriveridVar INT(11);
    DECLARE newvehicleidVar INT(11);
    DECLARE unitnumberVar VARCHAR(11);
    DECLARE newcustomernoVar INT(11);
    DECLARE str1Var VARCHAR(40);
    DECLARE str2Var VARCHAR(50);
    DECLARE str3Var VARCHAR(40);
    DECLARE str4Var VARCHAR(40);
    DECLARE str5Var VARCHAR(40);
    DECLARE str6Var VARCHAR(40);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
            SET isexecutedOut = 0;
        END;

    SELECT simcardno INTO newsimcardnoVar FROM simcard WHERE id =newsimidParam;

    SELECT  devices.device_invoiceno
            , devices.inv_generatedate
            , devices.expirydate
            , devices.invoiceno
            , devices.simcardid
            , devices.installdate
            , devices.po_no
            , devices.po_date
            , vehicle.groupid
    INTO    devinvoicenoVar
            ,invGenerateDateVar
            ,expiryDateVar
            ,invoiceNoVar
            ,oldsimcardidVar
            ,installDateVar
            ,pnoVar
            ,pdateVar
            ,oldgroupidVar
    FROM    devices 
    INNER JOIN  vehicle ON vehicle.uid = devices.uid 
    WHERE   devices.uid =  oldunitidParam;

    SELECT  vehicleid
            ,driverid 
    INTO    newvehicleidVar
            ,newdriveridVar 
    FROM    vehicle 
    WHERE   uid =  newunitidParam;

    SELECT  unitno
            ,customerno 
    INTO    unitnumberVar
            ,newcustomernoVar 
    FROM    unit 
    WHERE   uid =  newunitidParam;

    SELECT  unitno
            ,customerno
    INTO    oldunitnoVar
            ,oldcustomernoVar
    FROM    unit 
    WHERE   uid =  oldunitidParam;

    SELECT  v.vehicleid
            ,v.driverid 
    INTO    oldvehicleidVar
            ,olddriveridVar 
    FROM    vehicle v
    INNER JOIN unit ON unit.vehicleid = v.vehicleid
    WHERE   unit.uid =  oldunitidParam;

    SELECT  vehicleno
    INTO    oldvehiclenoVar
    FROM    vehicle 
    WHERE   vehicleid =  oldvehicleidVar;

    SELECT  CONCAT('V', oldunitnoVar) 
    INTO    vehiclenoUpdateVar;

    SELECT  simcardno 
    INTO    simcardnumberVar 
    FROM    simcard 
    WHERE   id = oldsimcardidVar;


    START TRANSACTION;
    BEGIN
        IF oldvehicleidVar <> 0 THEN

            UPDATE  unit 
            SET     customerno=customernoParam
                    ,trans_statusid = 5
                    ,teamid=0
                    ,comments=commentsParam
            WHERE   uid= newunitidParam;

        --  Populate Devices
            UPDATE  devices 
            SET     po_no=pnoVar
                    , po_date=pdateVar
                    , device_invoiceno=devinvoicenoVar
                    , inv_generatedate = invGenerateDateVar
                    , customerno=customernoParam
                    , simcardid=oldsimcardidVar
                    , expirydate =expiryDateVar
                    , invoiceno =invoiceNoVar
                    , installdate=installDateVar
            WHERE   uid=newunitidParam;


        --  Populate Vehicles    

        --  Old groupid set for new unit 
            UPDATE  vehicle 
            SET     customerno=customernoParam
                    ,groupid = oldgroupidVar 
            WHERE   uid = newunitidParam;

        --  Set lease on new device
            UPDATE  unit 
            SET     onlease=1 
            WHERE   uid =  newunitidParam;

        --  unset lease on old device
            UPDATE  unit 
            SET     onlease=0 
            WHERE   uid = oldunitidParam;

            UPDATE  eventalerts 
            SET     customerno=customernoParam 
            WHERE   vehicleid=newvehicleidVar;

            UPDATE  ignitionalert 
            SET     customerno= customernoParam  
            WHERE   vehicleid= newvehicleidVar;

            UPDATE  acalerts 
            SET     customerno= customernoParam  
            WHERE   vehicleid= newvehicleidVar;



        --  Remove Old Device    
            UPDATE  unit 
            SET     customerno=1
                    , userid=0
                    , trans_statusid = 20
                    ,teamid= eteamidParam 
                    , comments =  commentsParam  
            WHERE   uid= oldunitidParam;


            UPDATE  devices 
            SET     customerno=1
                    , simcardid = 0
                    , device_invoiceno = ''
                    , inv_generatedate = '0000-00-00 00:00:00'
                    , expirydate='0000-00-00'
                    ,invoiceno=''
                    , po_no=''
                    , po_date='0000-00-00'
                    , installdate='0000-00-00' 
            WHERE   uid=oldunitidParam;


        --  Delete from (checkpointmanage / fenceman)
            UPDATE  checkpointmanage 
            SET     isdeleted=1 
            WHERE   vehicleid=oldvehicleidVar 
            AND     customerno= oldcustomernoVar;


            UPDATE  fenceman 
            SET     isdeleted=1 
            WHERE   vehicleid=oldvehicleidVar 
            AND     customerno= oldcustomernoVar;

        --  Populate Vehicles

            UPDATE  vehicle 
            SET     customerno=customernoParam
                    ,vehicleno=oldvehiclenoVar 
            WHERE   uid =newunitidParam;

        --  groupid set for zero 
            UPDATE  vehicle 
            SET     customerno=1
                    ,groupid=0
                    ,vehicleno=vehiclenoUpdateVar 
            WHERE   uid =oldunitidParam;

        --  Populate Drivers
            UPDATE  vehicle 
            SET     driverid=olddriveridVar 
            WHERE   uid =newunitidParam;

            UPDATE  vehicle 
            SET     driverid=newdriveridVar 
            WHERE   uid =oldunitidParam;


            UPDATE  driver 
            SET     vehicleid=newvehicleidVar 
            WHERE   driverid =olddriveridVar;

            UPDATE  driver 
            SET     vehicleid=oldvehicleidVar 
            WHERE   driverid=newdriveridVar;

            UPDATE  eventalerts 
            SET     customerno=1 
            WHERE   vehicleid= oldvehicleidVar;

            UPDATE  ignitionalert 
            SET     customerno=1 
            WHERE   vehicleid= oldvehicleidVar;

            UPDATE  acalerts 
            SET     customerno=1 
            WHERE   vehicleid= oldvehicleidVar;


        --  New Sim Card
            UPDATE  devices 
            SET     simcardid=newsimidParam 
            WHERE   simcardid=oldsimcardidVar;

            UPDATE  simcard 
            SET     customerno=customernoParam
                    , trans_statusid=13
                    ,teamid=0
                    ,comments=commentsParam 
            WHERE   id=newsimidParam;

            UPDATE  simcard 
            SET     customerno=1
                    , trans_statusid=21
                    ,teamid=eteamidParam
                    ,comments=commentsParam 
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
                    ,`createdon`
                    )
                VALUES (oldunitidParam
                    ,newunitidParam
                    ,oldvehicleidVar
                    ,oldsimcardidVar
                    ,newsimidParam
                    ,5
                    ,1
                    ,commentsParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam);

        --  Replace daily reprt  
            UPDATE  dailyreport 
            SET     uid =newunitidParam
                    ,vehicleid =newvehicleidVar
                    ,first_odometer=0
                    ,last_odometer=0
                    ,max_odometer=0 
            WHERE   uid =oldunitidParam 
            AND     customerno= customernoParam;
            
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
    AND     (c.groupid=oldgroupidVar OR c.groupid ='0' ) 
    AND     (c.role = 'Administrator' OR c.role = 'Master') group by c.userid LIMIT 1;
	
    SET vehiclenoOut=oldvehiclenoVar;
    SET oldunitOut=oldunitnoVar;
    SET oldsimOut=simcardnumberVar;
    SET newunitOut=unitnumberVar;
    SET newsimOut=newsimcardnoVar;

END$$
DELIMITER ;


-- Re-install device

DELIMITER $$
DROP PROCEDURE IF EXISTS `re_install_device`$$
CREATE PROCEDURE `re_install_device`(
    IN todaysdateParam DATETIME
    ,IN unitidParam VARCHAR(11)
    ,IN eteamidParam INT(11)
    ,IN newvehiclenoParam VARCHAR(40)
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
        INTO    oldvehicleidVar
                ,customernoVar 
                ,oldvehiclenoOut
        FROM    vehicle 
        WHERE   uid=unitidParam and isdeleted=0;
        
        SELECT  simcardid
        INTO    oldsimcardidVar
        FROM    devices
        WHERE   uid = unitidParam;

    START TRANSACTION;
    BEGIN
        
        
        INSERT  INTO vehicle(`vehicleno`,`uid`,`customerno`) 
        VALUES  (newvehiclenoParam,unitidParam,customernoVar);

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
                ,`createdon`
                )
            VALUES(unitidParam
                ,oldvehicleidVar
                ,newvehicleidVar
                ,oldsimcardidVar
                ,6
                ,1
                ,'Re Install'
                ,eteamidParam
                ,lteamidParam
                ,todaysdateParam);
        
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
    LEFT OUTER JOIN groupman p ON p.groupid = " . groupidVar . " 
    LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
    WHERE c.customerno = " . customernoParam . " 
        AND c.email <> '' 
        AND (c.groupid=groupidVar or c.groupid ='0' ) 
        AND (c.role = 'Administrator' OR c.role = 'Master')
    GROUP BY c.userid;

    SET newvehiclenoOut=newvehiclenoParam;

END$$
DELIMITER $$


-- Repair device

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
                ,`createdon`
                )
            VALUES (unitidParam
                ,vehicleidVar
                ,simcardidParam
                ,7
                ,1
                ,commentsParam
                ,eteamidParam
                ,lteamidParam
                ,todaysdateParam);

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
    LEFT OUTER JOIN groupman p ON p.groupid = " . groupidVar . " 
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


-- create new table `trans_history_new`
DROP TABLE IF EXISTS `trans_history_new`;
CREATE TABLE IF NOT EXISTS `trans_history_new` (
  `transid` int(11) PRIMARY KEY AUTO_INCREMENT,
  `oldunitid` int(11) DEFAULT 0,
  `newunitid` int(11) DEFAULT 0,
  `oldvehicleid` int(11) DEFAULT 0,
  `newvehicleid` int(11) DEFAULT 0,
  `oldsimcardid` int(11) DEFAULT 0,
  `newsimcardid` int(11) DEFAULT 0,
  `transtypeid` int(11),
  `remark` VARCHAR(200),
  `teamid` int(11),
  `createdby` int(11),
  `createdon` datetime);


DROP TABLE IF EXISTS `trans_type`;
CREATE TABLE IF NOT EXISTS `trans_type`(
`id` INT(11) PRIMARY KEY,
`type` VARCHAR(40));


INSERT INTO trans_type(`id`,`type`) VALUES(1,'Register Device');

INSERT INTO trans_type(`id`,`type`) VALUES(2,'Remove Both');

INSERT INTO trans_type(`id`,`type`) VALUES(3,'Replace Sim');

INSERT INTO trans_type(`id`,`type`) VALUES(4,'Replace Unit');

INSERT INTO trans_type(`id`,`type`) VALUES(5,'Replace Both');

INSERT INTO trans_type(`id`,`type`) VALUES(6,'Reinstall');

INSERT INTO trans_type(`id`,`type`) VALUES(7,'Repair');

ALTER TABLE `trans_history_new` ADD `bucketstatusid` INT NOT NULL COMMENT '1-Successful, 2-Unsuccessful, 3-Reschedule, 4-Cancel' AFTER `transtypeid`;

DROP TABLE IF EXISTS `bucket_status`;
CREATE TABLE IF NOT EXISTS `bucket_status`(
`id` INT(11) PRIMARY KEY,
`type` VARCHAR(40));

INSERT INTO bucket_status(`id`,`type`) VALUES(1,'Successful');

INSERT INTO bucket_status(`id`,`type`) VALUES(2,'Unsuccessful');

INSERT INTO bucket_status(`id`,`type`) VALUES(3,'Reschedule');

INSERT INTO bucket_status(`id`,`type`) VALUES(4,'Cancel');
 

-- CREATE TABLE IF NOT EXISTS `servicecall_new` LIKE `servicecall`;
-- 
-- INSERT INTO `servicecall_new` SELECT * FROM `servicecall`;

-- ALTER TABLE `servicecall` ENGINE = INNODB;
-- 
 UPDATE 	dbpatches 
 SET 	patchdate = NOW()
 	, isapplied =1 
 WHERE 	patchid = 445;