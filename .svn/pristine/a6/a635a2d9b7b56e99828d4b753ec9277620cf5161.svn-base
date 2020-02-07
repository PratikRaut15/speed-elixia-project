INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'558', '2018-05-02 13:30:00', 'Yash Kanakia', 'Docket Enhancement and CRM Analysis', '0'
);

DELIMITER $$
DROP procedure IF EXISTS `updateTicket`$$
CREATE PROCEDURE `updateTicket`(
  IN ticketidParam INT,
    IN emailListParam VARCHAR(255),
    IN ccListParam VARCHAR(255),
    IN allotToParam INT,
    IN statusParam INT,
    IN priorityParam INT,
    IN ecdParam DATETIME,
    IN additionalChargeParam TINYINT,
    IN createdByParam INT,
    IN createdOnParam DATETIME
  ,OUT ticketIdOut INT
    ,OUT is_executedOut INT
    ,OUT tickettypenameOut VARCHAR (20)
    ,OUT customercompanyOut VARCHAR (20)
    ,OUT prioritynameOut VARCHAR (20)
    ,OUT allottoemailOut VARCHAR (50)
    ,OUT createbyOut VARCHAR (50)
  ,OUT emaidIdOut VARCHAR(255)
    ,OUT ccemailIdOut VARCHAR(255)
    ,OUT statusOut VARCHAR(50)
)
BEGIN
  DECLARE customerNoVar INT;
    DECLARE descVar VARCHAR(255);
    DECLARE allotToVar INT;
    DECLARE createdTypeVar INT;
    DECLARE userIdVar INT;
    
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        
        /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/
        
    END;
    START TRANSACTION;
    UPDATE sp_ticket 
        SET send_mail_to = emailListParam,
      send_mail_cc = ccListParam,
            priority   = priorityParam,
            eclosedate   = ecdParam
    WHERE ticketid = ticketidParam;
        
    SELECT customerid into customerNoVar
        FROM sp_ticket 
        WHERE ticketid = ticketidParam LIMIT 1;
        
        SELECT description,allot_to,created_type,userid 
        INTO descVar,allotToVar,createdTypeVar,userIdVar
        FROM sp_ticket_details 
        WHERE ticketid = ticketidParam 
        ORDER BY uid DESC 
        LIMIT 1;
    
        INSERT INTO sp_ticket_details (ticketid, description, allot_from, created_type, allot_to, status, add_charge, create_by, create_on_time, userid) 
        VALUES (ticketidParam,descVar,allotToVar,createdTypeVar, allotToParam, statusParam, additionalChargeParam, createdByParam, createdOnParam, userIdVar);
        SET ticketIdOut  = ticketIdParam;

      SELECT  priority
       INTO    prioritynameOut
       FROM    sp_priority
       WHERE   prid = priorityParam
       AND     isdeleted = 0 ;
    


      SELECT  email
      INTO    allottoemailOut
      FROM    team
      WHERE   teamid = allottoParam;

      SELECT  customercompany
      INTO    customercompanyOut
      FROM    customer
      WHERE   customerno = customerNoVar;

      SELECT  name
      INTO    createbyOut
      FROM    team
      WHERE   teamid = createdByParam;

      SELECT tickettype
      INTO tickettypenameOut
      from sp_tickettype
      where typeid = (SELECT ticket_type
      from sp_ticket
      where ticketid = ticketIdParam LIMIT 1);

    SELECT GROUP_CONCAT(email_id SEPARATOR ',')
    INTO emaidIdOut
    FROM report_email_list
    WHERE FIND_IN_SET(eid,emailListParam);

    SET is_executedOut = 1;
    SELECT GROUP_CONCAT(email_id SEPARATOR ',')
    INTO ccemailIdOut
    FROM report_email_list
    WHERE FIND_IN_SET(eid,ccListParam);
        
    SELECT status INTO statusOut
    FROM ticket_status 
    where id =  statusParam;
    COMMIT;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_tickets`;
CREATE  PROCEDURE `fetch_tickets`(
  IN docketIdParam INT,
  IN ticketIdParam INT
)
BEGIN
  if(docketIdParam=0) THEN 
    select st.ticketid
          ,st.title
          ,st.ticket_type
          ,sttype.tickettype
          ,sp.priority as prname
          ,st.sub_ticket_issue
          ,st.docketid
          ,st.customerid
          ,(SELECT customercompany from customer where customerno=st.customerid) as customercompany
          ,st.priority
          ,DATE_FORMAT(st.create_on_date ,'%d-%m-%Y') AS create_on_date
          ,st.create_by
          ,st.created_type
          ,st.uid
          ,st.send_mail_to
          ,st.create_platform 
          ,t.name AS allot_to
          ,std.description
          ,std.status
          ,(SELECT team.name from team where team.teamid = std.allot_to) AS allot_to
          ,ts.status as ticketStatus
          ,DATE_FORMAT(st.eclosedate,'%d-%m-%Y') AS eclosedate
          ,DATE_FORMAT(st.estimateddate,'%d-%m-%Y') AS estimateddate
          ,std.uid
          ,(SELECT e.prodName from elixiaOneProductMaster e where e.prodId = st.prodId) AS prod_id
          ,st.send_mail_cc
          ,std.add_charge
          ,c.charge_id
          ,c.description AS chargeDescription
          ,c.amount AS chargeAmount
        FROM sp_ticket as st 
        INNER JOIN  sp_ticket_details std ON std.ticketid = st.ticketid 
		AND std.uid = (SELECT MAX(uid) from sp_ticket_details sds where sds.ticketid=st.ticketid)
        LEFT JOIN   team t ON t.teamid = std.allot_to
        LEFT JOIN   sp_tickettype as sttype on sttype.typeid = st.ticket_type 
        LEFT JOIN   sp_priority as sp on sp.prid = st.priority 
        LEFT JOIN ticket_status ts ON ts.id = std.status
        LEFT JOIN additional_charges c ON c.ticketid = ticketIdParam
        WHERE (st.ticketid = ticketIdParam OR ticketIdParam IS NULL)
        GROUP BY    std.ticketid
        order by    st.ticketid ASC;
  ELSE 
    select st.ticketid
          ,st.title
          ,st.ticket_type
		  ,st.docketid
          ,sttype.tickettype
          ,sp.priority as prname
          ,st.sub_ticket_issue
          ,st.customerid
          ,(SELECT customercompany from customer where customerno=st.customerid) as customercompany
          ,st.priority
          ,DATE_FORMAT(st.create_on_date ,'%d-%m-%Y') AS create_on_date
          ,DATE_FORMAT(st.estimateddate,'%d-%m-%Y') AS estimateddate
          ,st.send_mail_cc
          ,st.create_by
          ,st.created_type
          ,st.uid
		  ,st.send_mail_to
          ,st.create_platform 
          ,t.name AS allot_to
		  ,std.description
          ,std.status
          ,(SELECT team.name from team where team.teamid = std.allot_to) AS allot_to
          ,ts.status as ticketStatus
          ,DATE_FORMAT(st.eclosedate,'%d-%m-%Y') AS eclosedate
		  ,std.uid
          ,(SELECT e.prodName from elixiaOneProductMaster e where e.prodId = st.prodId) AS prod_id
          ,DATE_FORMAT(st.estimateddate,'%d-%m-%Y') AS estimateddate
          ,std.add_charge
          ,std.add_charge
          ,c.charge_id
          ,c.description AS chargeDescription
          ,c.amount AS chargeAmount
        FROM sp_ticket as st 
        INNER JOIN  sp_ticket_details std ON std.ticketid = st.ticketid 
		AND std.uid = (SELECT MAX(uid) from sp_ticket_details sds where sds.ticketid=st.ticketid)
        LEFT JOIN   team t ON t.teamid = std.allot_to
        LEFT JOIN   sp_tickettype as sttype on sttype.typeid = st.ticket_type 
        LEFT JOIN   sp_priority as sp on sp.prid = st.priority 
        LEFT JOIN ticket_status ts ON ts.id = std.status
        LEFT JOIN additional_charges c ON c.ticketid = st.ticketid
        WHERE (st.docketid = docketIdParam OR docketIdParam IS NULL)
        GROUP BY    std.ticketid
        order by    st.ticketid ASC;
    END IF;
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `update_ticket_team`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `update_ticket_team`(
    IN customerNoParam INT
    ,IN priorityParam INT
    ,IN sendMailParam INT
    ,IN emailIdsParam VARCHAR(100)
    ,IN ccemailidsParam VARCHAR(100)
    ,IN ecloseDateParam DATE
    ,IN addCountParam INT
    ,IN ticketIdParam INT
    ,IN ticketDescParam VARCHAR(255)
    ,IN allotFromParam INT
    ,IN allotToParam INT
    ,IN ticketStatusParam INT
    ,IN createdByParam INT
    ,IN createdOnTimeParam DATETIME
    ,IN createdTypeParam INT
    ,IN prodIdParam INT
    ,OUT ticketIdOut INT 
    ,OUT is_executedOut INT
    ,OUT tickettypenameOut VARCHAR (20)
    ,OUT customercompanyOut VARCHAR (20)
    ,OUT prioritynameOut VARCHAR (20)
    ,OUT allottoemailOut VARCHAR (50)
    ,OUT createbyOut VARCHAR (50)
	,OUT emaidIdOut VARCHAR(255)
    ,OUT ccemailIdOut VARCHAR(255)
)
BEGIN
  DECLARE typeidParam INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
       
     ROLLBACK;
     
       GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
       
    END;
    START TRANSACTION;
    
        
        IF (customerNoParam = 0) THEN
            SET customerNoParam = NULL;
             
        END IF;  
       
        IF (customerNoParam IS NOT NULL ) THEN
            update elixiatech.sp_ticket t
            set t.priority=priorityParam , 
            t.send_mail_status=sendMailParam ,
            t.customerid=customerNoParam ,
            t.eclosedate=ecloseDateParam ,
            t.eclosedate_chng_count = t.eclosedate_chng_count + addCountParam,
            t.send_mail_to = emailIdsParam,
            t.prodId = prodIdParam
            where t.ticketid=ticketIdParam;
            IF (createdTypeParam=0) THEN
                INSERT INTO elixiatech.`sp_ticket_details`(`ticketid`, `description`, `allot_from`, `allot_to`, `status`, `create_by`, `create_on_time`) 
                    VALUES (ticketIdParam, ticketDescParam, allotFromParam ,allotToParam, ticketStatusParam,createdByParam,createdOnTimeParam);
            ELSE
                INSERT INTO elixiatech.`sp_ticket_details`(`ticketid` ,`description`,`allot_from`,`allot_to`,`status`,`create_by`,`create_on_time`,`created_type`)
                    VALUES (ticketIdParam, ticketDescParam,allotFromParam ,allotToParam, ticketStatusParam,createdByParam,createdOnTimeParam,createdTypeParam);
            END IF;
      SET is_executedOut = 1;
        END IF;     

    SET ticketIdOut  = ticketIdParam;
    
  SELECT  priority
   INTO    prioritynameOut
   FROM    sp_priority
   WHERE   prid = priorityParam
   AND     isdeleted = 0 ; 
     
     
     
  SELECT  email
  INTO    allottoemailOut
  FROM    team
  WHERE   teamid = allottoParam;
    
  SELECT  customercompany
  INTO    customercompanyOut
  FROM    customer
  WHERE   customerno = customerNoParam;

    SELECT  name
  INTO    createbyOut
  FROM    team
  WHERE   teamid = createdByParam;
            
  SELECT tickettype 
  INTO tickettypenameOut
  from sp_tickettype 
  where typeid = (SELECT ticket_type
  from sp_ticket
  where ticketid = ticketIdParam LIMIT 1);
  
		SELECT GROUP_CONCAT(email_id SEPARATOR ',')
		INTO emaidIdOut
		FROM report_email_list
		WHERE FIND_IN_SET(eid,emailIdsParam);
        
        
		SELECT GROUP_CONCAT(email_id SEPARATOR ',')
		INTO ccemailIdOut
		FROM report_email_list
		WHERE FIND_IN_SET(eid,ccemailidsParam);
    COMMIT;
   
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `insert_e_bucket`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `insert_e_bucket`(
    IN reschedule_dateParam date
    ,IN customernoParam INT(11)
    ,IN unitidParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN teamidParam INT(11)
    ,IN priorityParam INT(4)
    ,IN puproseidParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN todayParam datetime
    ,IN vehicleidParam INT(11)
    ,IN docketidParam INT(11)
    ,IN prevBucketParam INT
    ,OUT bucketid INT
)
BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
       /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
    END;
INSERT INTO speed.bucket (
         `apt_date` ,`customerno`,`created_by`, `priority`, `vehicleid`, `location`,
        `timeslotid`, `purposeid`, `details`, `coordinatorid`, `create_timestamp`, `status`,`docketid`,`unitid`,`simcardid`,`prevBucketId`)
VALUES (reschedule_dateParam, customernoParam, teamidParam, priorityParam,vehicleidParam , locationParam,
        timeslotParam,puproseidParam,detailsParam,coordinatorParam,todayParam,0,docketidParam,unitidParam,simcardidParam,prevBucketParam);
SET bucketid = LAST_INSERT_ID();
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `edit_bucket`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `edit_bucket`(
     IN apt_dateParam date
    ,IN coordinatorParam INT(11)
    ,IN priorityidParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN vehiclenoParam VARCHAR(15)
    ,IN timeslotParam INT(4)
    ,IN purposeidParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN sstatusParam INT(4)
    ,IN todayParam datetime
    ,IN creasonParam VARCHAR(50)
    ,IN add_chargeParam INT
    ,IN reschedule_dateParam date
    ,IN bucketidParam INT(11)
    )
BEGIN
DECLARE statusCheck INT;
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
      /*  
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        
        */
    END;

    SELECT apt_date,priority,location,timeslotid,purposeid,details,vehicleno,coordinatorid,status,cancellation_reason,reschedule_date,reschedule_timestamp,cancelled_timestamp
    INTO @aptdate,@priority,@location,@timeslotid,@purposeid,@details,@vehicleno,@coordinatorid,@status,@creason,@reschedule_date,@reschedule_timestamp,@cancelled_timestamp 
    FROM speed.bucket
    WHERE bucketid = bucketidParam;
   
 SET statusCheck = sstatusParam;  
    
IF(statusCheck=0)    
THEN
UPDATE speed.`bucket` 
SET `apt_date`=apt_dateParam,
`priority`=priorityidParam,
`location`=locationParam,
`timeslotid`=timeslotParam,
`purposeid`=purposeidParam,
`details`=detailsParam,
`coordinatorid`=coordinatorParam,
`status`=sstatusParam,
`vehicleno`=vehiclenoParam,
`reschedule_date`=@reschedule_date,
`reschedule_timestamp`=@reschedule_timestamp,
`cancellation_reason`=@creason,
`cancelled_timestamp`=@cancelled_timestamp,
`add_charge`=add_chargeParam
WHERE `bucketid`=bucketidParam;
END IF;

IF(statusCheck=1)    
THEN
UPDATE speed.`bucket` 
SET 
`reschedule_date`=reschedule_dateParam,
`reschedule_timestamp`=todayParam,
`status`=sstatusParam,
`apt_date`= @aptdate,
`priority`=@priority,
`location`=@location,
`timeslotid`=@timeslotid,
`purposeid`=@purposeid,
`details`=@details,
`coordinatorid`=@coordinatorid,
`cancellation_reason`=@creason,
`cancelled_timestamp`=@cancelled_timestamp
WHERE `bucketid`=bucketidParam;
END IF;            

IF(statusCheck=5)    
THEN
UPDATE speed.`bucket` 
SET 
`cancellation_reason`=creasonParam,
`cancelled_timestamp`=todayParam,
`status`=sstatusParam,
`apt_date`= @aptdate,
`priority`=@priority,
`location`=@location,
`timeslotid`=@timeslotid,
`purposeid`=@purposeid,
`details`=@details,
`coordinatorid`=@coordinatorid,
`reschedule_date`=@reschedule_date,
`reschedule_timestamp`=@reschedule_timestamp
WHERE `bucketid`=bucketidParam;
END IF;  

END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `pullBucketHistory`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `pullBucketHistory`(
	IN bucketidParam INT
    )
BEGIN
	DECLARE bucketListVar VARCHAR(255);
    DECLARE currentBucketIdVar INT;
    DECLARE prevBucketIdVar INT;
    SET currentBucketIdVar = bucketIdParam;
    SELECT prevBucketId into prevBucketIdVar FROM speed.bucket WHERE bucketid = currentBucketIdVar;
    SET bucketListVar = '';
    WHILE (prevBucketIdVar)>0 DO
		BEGIN
        IF(bucketListVar = '') THEN
			SET bucketListVar = prevBucketIdVar;
		ELSE
			SET bucketListVar = CONCAT(bucketListVar,',',prevBucketIdVar);
        END IF;
			SET currentBucketIdVar = prevBucketIdVar;
			SELECT prevBucketId into prevBucketIdVar FROM speed.bucket WHERE bucketid = currentBucketIdVar;
            
        END;
    END WHILE;
    SELECT bucketid, apt_date, remarks FROM  speed.bucket WHERE FIND_IN_SET(bucketid,bucketListVar);
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `get_buckets`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `get_buckets`(
IN docketIdParam INT)
BEGIN
  SELECT u.unitno,b.unitid,b.simcardid,s.simcardno,b.status as statusID, b.fe_id,b.bucketid, b.customerno,b.apt_date, c.customercompany, b.priority, v.vehicleno, b.location,b.details, b.purposeid,b.add_charge,cp.person_name, cp.cp_phone1, t.name, b.vehicleno as vehno, b.vehicleid, sp.timeslot,ac.description,ac.amount,(SELECT name from team where teamid = b.fe_id) as feName,b.prevBucketId,
		(CASE 
        WHEN b.status=0
        THEN 'Open'
        WHEN b.status=1 
        THEN 'Rescheduled' 
        WHEN b.status=2
        THEN 'Successful'
        WHEN b.status=3
        THEN 'Unsuccessful'
        WHEN b.status=4
        THEN 'FE Assigned' 
        WHEN b.status=5 
        THEN 'Cancelled'
        WHEN b.status=6
        THEN 'Incomplete'
		END)as status
                FROM speed.bucket b
                INNER JOIN speed.customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN speed.vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN speed.contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN speed.team t ON t.teamid = b.fe_id
                LEFT OUTER JOIN speed.sp_timeslot sp ON sp.tsid = b.timeslotid                                        
                LEFT OUTER JOIN speed.unit u ON u.uid = b.unitid
                LEFT OUTER JOIN speed.simcard s ON s.id = b.simcardid
				LEFT OUTER JOIN elixiatech.additional_charges ac ON ac.bucketid = b.bucketid
                WHERE docketid=docketIdParam 
        ORDER BY b.bucketid ASC;
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `register_device`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `register_device`(
    IN todaysdateParam DATETIME
    ,IN unitidParam INT(11)
    ,IN utypeParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN ponoParam INT(11)
    ,IN podateParam DATE
    ,IN expirydateParam DATE
    ,IN installdateParam DATE
    ,IN invoicenoParam VARCHAR(50)
    ,IN vehiclenoParam VARCHAR(40)
    ,IN kindParam VARCHAR(40)
    ,IN leaseParam TINYINT(2)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,IN docketidParam INT
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT unitnumberOut VARCHAR(16)
    ,OUT simcardnoOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT errormsgOut VARCHAR(100)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
           
            SET isexecutedOut = 0;
	END;
    BEGIN    
        DECLARE cstypeVar INT DEFAULT 13;
        DECLARE warrantyVar DATETIME DEFAULT DATE_ADD(CURRENT_DATE, INTERVAL 365 DAY);
        DECLARE vehicleidVar INT(11) DEFAULT 0;
        DECLARE useridVar INT(11);
        DECLARE panicVar TINYINT(1);
        DECLARE buzzerVar TINYINT(4);
        DECLARE mobiliserVar TINYINT(1);

        IF utypeParam = 23 THEN
            SET cstypeVar = 24;
        END IF;

        IF utypeParam = 22 THEN
            SET cstypeVar = 25;
        END IF;

        IF(vehiclenoParam = '' OR vehiclenoParam = '0') THEN
            SET vehiclenoParam = NULL;
        END IF;
        
        IF unitidParam <> '' OR unitidParam <> '0' THEN

            SELECT  unitno
                    ,is_panic
                    ,is_buzzer
                    ,is_mobiliser 
            INTO    unitnumberOut
                    ,panicVar
                    ,buzzerVar
                    ,mobiliserVar 
            FROM    unit 
            WHERE   uid = unitidParam
            ORDER BY uid DESC 
            LIMIT   1;

            SELECT  vehicleid 
            INTO    vehicleidVar  
            FROM    vehicle 
            WHERE   uid = unitidParam 
            AND     isdeleted = 0 
            ORDER BY vehicleid DESC 
            LIMIT   1;

        END IF;
        
        IF simcardidParam <> '' OR simcardidParam <> 0 THEN

            SELECT  simcardno 
            INTO    simcardnoOut 
            FROM    simcard 
            WHERE   id = simcardidParam
            ORDER BY id DESC 
            LIMIT   1;

        END IF;

        SELECT  userid 
        INTO    useridVar 
        FROM    `user` 
        WHERE   isdeleted=0 
        AND     customerno=customernoParam
        ORDER BY userid DESC 
        LIMIT   1;
        
        SET     isexecutedOut = 0;

        IF statusParam = 2 THEN
    
            IF vehicleidVar IS NOT NULL AND vehicleidVar <> 0 THEN

                START TRANSACTION;	 
                BEGIN

                    UPDATE  unit 
                    SET     customerno=customernoParam
                            , trans_statusid = utypeParam
                            ,teamid=0
                            , comments = commentParam 
                    WHERE   uid=unitidParam;

                    UPDATE  simcard 
                    SET     customerno=customernoParam
                            ,trans_statusid = cstypeVar
                            ,teamid=0
                            ,comments = commentParam 
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

                    IF  vehiclenoParam IS NULL OR vehiclenoParam='' THEN

                        UPDATE  vehicle 
                        SET     customerno=customernoParam 
                                ,kind = kindParam
                        WHERE   uid = unitidParam;

                    ELSE

                        UPDATE  vehicle 
                        SET     customerno=customernoParam
                                ,vehicleno=vehiclenoParam
                                ,kind = kindParam
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

                    
                    IF leaseParam = 1 THEN
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

                    
                    INSERT INTO trans_history_new(`bucketid`
                        ,`newunitid`
                        ,`newvehicleid`
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
                        ,vehicleidVar
                        ,simcardidParam
                        ,'1'
                        ,'1'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                
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
                    SELECT  customerno
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
                    FROM    vehiclewise_alert 
                    WHERE   userid = useridVar 
                    AND customerno= customernoParam 
                    LIMIT   1;                             
				
                    UPDATE  bucket 
                    SET     `status` = statusParam
                            ,`task_completion_timestamp` = todaysdateParam 
                    WHERE   bucketid= bucketidParam ;
                
                    SET isexecutedOut = 1;
                END;
                COMMIT; 
            
            ELSE
                SET errormsgOut = 'Vehicle Not Mapped';
                SET isexecutedOut = 0;
            END IF;
   
            SELECT      `name` INTO elixirOut 
            FROM        team 
            WHERE       teamid = eteamidParam
            ORDER BY    teamid DESC 
            LIMIT       1;

            SELECT      username
                        ,realname
                        ,email 
            INTO        usernameOut
                        ,realnameOut
                        ,emailOut 
            FROM        `user` 
            INNER JOIN  groupman ON groupman.userid  <> `user`.userid 
            WHERE       `user`.customerno = customernoParam 
            AND         `user`.email <> '' 
            AND         `user`.isdeleted = 0
            AND         (`user`.role = 'Administrator' OR `user`.role = 'Master') 
            ORDER BY    `user`.userid DESC
            LIMIT       1;

        ELSEIF statusParam = 3 THEN
    
            START TRANSACTION;	 
            BEGIN

                UPDATE  bucket 
                SET     status= statusParam 
                        , is_problem_of = unsuccessProblemParam
                        , remarks= commentParam
                        ,task_completion_timestamp = todaysdateParam 
                where   bucketid=bucketidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`newunitid`
                        ,`newvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'1'
                        ,'2'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;
            END;
            COMMIT;

        ELSEIF statusParam = 6 THEN
    
            START TRANSACTION;	 
            BEGIN

                UPDATE  bucket  
                SET     status= statusParam 
                        ,reschedule_date=incompleteDateParam
                        ,reschedule_timestamp = todaysdateParam 
                        ,remarks = commentParam
                where   bucketid=bucketidParam;

                INSERT INTO bucket (`apt_date` 
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        , `status`
                        ,`docketid`)
                SELECT    incompleteDateParam
                        , customernoParam
                        , lteamidParam
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , todaysdateParam
                        , '0'
                        ,docketidParam
                FROM    bucket
                WHERE   bucketid = bucketidParam
                LIMIT   1;


                INSERT INTO trans_history_new(`bucketid`
                        ,`newunitid`
                        ,`newvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'1'
                        ,'5'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;
            END;
            COMMIT;

        ELSEIF statusParam = 1 THEN
    
            START TRANSACTION;	 
            BEGIN

                UPDATE  bucket 
                SET     status = statusParam 
                        ,reschedule_date = rescheduleDateParam
                        ,reschedule_timestamp = todaysdateParam 
                        ,remarks = commentParam
                WHERE   bucketid = bucketidParam;

                INSERT INTO bucket (`apt_date` 
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        , `status`
                        ,`docketid`)
                SELECT    rescheduleDateParam
                        , customernoParam
                        , lteamidParam
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        ,0
						,docketidParam
                FROM    `bucket`
                WHERE   `bucketid`=bucketidParam
                LIMIT   1;

                INSERT INTO trans_history_new(`bucketid`
                        ,`newunitid`
                        ,`newvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,1
                        ,3
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET isexecutedOut = 1;

            END;
            COMMIT;

        ELSEIF statusParam = 5 THEN
    
            START TRANSACTION;	 
            BEGIN

                UPDATE  bucket 
                SET     status = statusParam
                        , cancelled_timestamp = todaysdateParam
                        , cancellation_reason = commentParam 
                WHERE   bucketid = bucketidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,1
                        ,4
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;
            END;
            COMMIT;

        END IF;
	
    END;
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `remove_unit_sim`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `remove_unit_sim`(
    IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN unitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(50)
    ,IN docketidParam INT
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnumverOut VARCHAR(16)
    ,OUT simnumberOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150))
BEGIN
    DECLARE simcardidVar INT(11);
    DECLARE unitnoVar VARCHAR(11);
    DECLARE vehicleidVar INT(11);
    DECLARE groupidVar INT(11);
    DECLARE vehiclenoVar VARCHAR(40);
    DECLARE simcardnoVar VARCHAR(50);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
          
            SET isexecutedOut = 0;
        END;

    SELECT	simcardid 
    INTO 	simcardidVar 
    FROM 	devices 
    WHERE 	uid = unitidParam
    ORDER BY    deviceid DESC
    LIMIT 	1;

    SELECT	simcardno 
    INTO	simcardnoVar 
    FROM 	simcard 
    WHERE 	id = simcardidVar
    ORDER BY    id DESC
    LIMIT 	1;

    SELECT 	unitno 
    INTO 	unitnoVar 
    FROM 	unit 
    WHERE 	uid = unitidParam
    ORDER BY    uid DESC
    LIMIT 	1;

    SELECT 	vehicleid
                ,vehicleno
                ,groupid 
    INTO 	vehicleidVar
                ,vehiclenoVar
                ,groupidVar 
    FROM 	vehicle 
    WHERE 	uid = unitidParam
    ORDER BY    vehicleid DESC
    LIMIT 	1;

    IF statusParam = 2 THEN

        START TRANSACTION;
        BEGIN

            UPDATE  unit 
            SET     trans_statusid = 20
                    ,teamid = eteamidParam
                    ,comments = commentParam 
            WHERE 	uid= unitidParam;    

            UPDATE  simcard 
            SET     trans_statusid= 21
                    ,teamid=eteamidParam
                    , comments = commentParam 
            WHERE   id= simcardidVar;


        
            DELETE FROM dailyreport 
            WHERE       customerno = customernoParam  
            AND         uid= unitidParam;

            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
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
                    ,unitidParam
                    ,vehicleidVar
                    ,simcardidVar
                    ,'2'
                    ,'1'
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    , customernoParam);

        
            UPDATE  unit 
            SET     customerno=1
                    ,userid=0
                    , comments = commentParam 
            WHERE   uid= unitidParam;

            UPDATE  devices 
            SET     customerno=1
                    , expirydate='0000-00-00'
                    , device_invoiceno = ''
                    , inv_generatedate = '0000-00-00 00:00:00'
                    ,po_no=''
                    , po_date='0000-00-00'
                    , invoiceno=''
                    , installdate='0000-00-00' 
            WHERE   uid=unitidParam;


        
            UPDATE  unit 
            SET     onlease=0 
            WHERE   uid = unitidParam;

        

            UPDATE  vehicle 
            SET     customerno=1 
            WHERE   uid = unitidParam;

            UPDATE  driver
            SET     customerno = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  eventalerts 
            SET     customerno = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  ignitionalert 
            SET     customerno = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  acalerts 
            SET     customerno = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  checkpointmanage
            SET     customerno = 1
                    ,isdeleted = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  fenceman 
            SET     customerno = 1
                    ,isdeleted = 1 
            WHERE   vehicleid = vehicleidVar;

            UPDATE  groupman 
            SET     customerno = 1
                    ,isdeleted = 1
            WHERE   vehicleid = vehicleidVar;

            UPDATE  reportman 
            SET     customerno = 1 
            WHERE   uid = unitidParam;
            
            UPDATE  bucket 
            SET     `status` = statusParam
                    ,`task_completion_timestamp` = todaysdateParam 
            WHERE   bucketid = bucketidParam ;
            
            SET isexecutedOut=1;

        END;
        COMMIT;

        SELECT  `name` 
        INTO    elixirOut 
        FROM    team 
        WHERE   teamid =  eteamidParam
        LIMIT   1;

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
        AND             c.isdeleted = 0 
        AND             (c.groupid=groupidVar  OR c.groupid ='0') 
        AND             (c.`role` = 'Administrator' OR c.`role` = 'Master') 
        ORDER BY        c.userid DESC
        LIMIT           1;

        SET vehiclenoOut=vehiclenoVar;
        SET unitnumverOut=unitnoVar;
        SET simnumberOut=simcardnoVar;

    ELSEIF statusParam = 3 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE 	bucket 
            SET 	`status`=statusParam
                        ,`is_problem_of` = unsuccessProblemParam
                        ,`remarks` = commentParam
                        ,`task_completion_timestamp` = todaysdateParam 
            where 	`bucketid`=bucketidParam;
            
            INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        ,`remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
            VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,2
                        ,2
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = simcardidVar;

            SET isexecutedOut=1;

        END;
        COMMIT;
        
    ELSEIF statusParam = 6 THEN
        START TRANSACTION;
        BEGIN
        
            UPDATE  bucket  
            SET     status= statusParam 
                    ,reschedule_date=incompleteDateParam
                    ,reschedule_timestamp = todaysdateParam 
                    ,remarks = commentParam
            where   bucketid=bucketidParam;
            
            INSERT INTO bucket (`apt_date`
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , `create_timestamp`
                    , `status`
                    ,`docketid`)
                    
            SELECT  incompleteDateParam
                    , `customerno`
                    , `created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , todaysdateParam
                    , 0
                    ,docketidParam
            FROM    `bucket`
            WHERE   `bucketid`= bucketidParam
            ORDER BY bucketid DESC
            LIMIT   1;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    ,`remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,vehicleidVar
                    ,2
                    ,5
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            SET isexecutedOut=1;
        END;
        COMMIT;
        
    ELSEIF statusParam = 1 THEN
        START TRANSACTION;
        BEGIN
        
            UPDATE  `bucket` 
            SET     `status` = statusParam 
                    ,`reschedule_date` = rescheduleDateParam
                    ,`reschedule_timestamp` = todaysdateParam 
                    ,remarks = commentParam
            WHERE   `bucketid` = bucketidParam;
            
            INSERT INTO bucket (`apt_date`
                    ,`customerno`
                    ,`created_by`
                    ,`priority`
                    ,`vehicleid`
                    ,`location`
                    ,`timeslotid`
                    ,`purposeid`
                    ,`details`
                    ,`coordinatorid`
                    , `create_timestamp`
                    ,`status`
                    ,`docketid`)
            SELECT	rescheduleDateParam
                    ,`customerno`
                    ,`created_by`
                    ,`priority`
                    ,`vehicleid`
                    ,`location`
                    ,`timeslotid`
                    ,`purposeid`
                    ,`details`
                    ,`coordinatorid`
                    ,todaysdateParam
                    ,0
                    ,docketidParam
            FROM    `bucket`
            WHERE   `bucketid`=bucketidParam
            ORDER BY bucketid DESC
            LIMIT   1;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    ,`remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,vehicleidVar
                    ,'2'
                    ,'3'
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            SET isexecutedOut=1;
        END;
        COMMIT;
        
    ELSEIF statusParam = 5 THEN
        START TRANSACTION;
        BEGIN
        
            UPDATE  `bucket` 
            SET     `status` = statusParam
                    ,`cancelled_timestamp` = todaysdateParam
                    ,`cancellation_reason` = commentParam 
            WHERE   `bucketid` = bucketidParam;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,vehicleidVar
                    ,2
                    ,4
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = simcardidVar;
               
            SET isexecutedOut=1;
        END;
        COMMIT;

    END IF;
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `re_install_device`;

DELIMITER $$
USE `elixiatech`$$
CREATE  PROCEDURE `re_install_device`(
    IN todaysdateParam DATETIME
    ,IN unitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newvehiclenoParam VARCHAR(40)
    ,IN kindParam VARCHAR(40)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,IN docketidParam INT
    ,OUT isexecutedOut TINYINT(2)
    ,OUT newvehiclenoOut VARCHAR(40)
    ,OUT oldvehiclenoOut VARCHAR(40)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT errormsgOut VARCHAR(100))
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
        WHERE   uid=unitidParam 
        AND     isdeleted=0
        ORDER BY vehicleid DESC
        LIMIT   1;
        
        SELECT  simcardid
        INTO    oldsimcardidVar
        FROM    devices
        WHERE   uid = unitidParam
        ORDER BY deviceid DESC
        LIMIT   1;

    IF statusParam = 2 THEN

        IF oldvehicleidVar IS NOT NULL AND oldvehicleidVar <> 0 THEN
            START TRANSACTION;
            BEGIN

                INSERT INTO vehicle(`vehicleno`
                    ,`uid`
                    ,`customerno`
                    ,`driverid`
                    ,`kind`) 
                VALUES  (newvehiclenoParam
                    ,unitidParam
                    ,customernoVar
                    ,driveridVar
                    ,kindParam);

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
                WHERE   vehicleid = oldvehicleidVar 
                AND     isdeleted = 0;

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
                WHERE   vehicleid=oldvehicleidVar 
                AND     isdeleted=0;

                UPDATE  fenceman 
                SET     vehicleid=newvehicleidVar
                        ,customerno=customernoVar
                WHERE   vehicleid=oldvehicleidVar 
                AND     isdeleted=0;

                UPDATE  vehicle 
                SET     isdeleted=1
                        ,uid=0 
                WHERE   vehicleid=oldvehicleidVar;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`newvehicleid`
                        ,`oldsimcardid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES(bucketidParam
                        ,unitidParam
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

                UPDATE 	bucket 
                SET 	`status` = 2
                        ,`task_completion_timestamp` = todaysdateParam 
                WHERE	bucketid= bucketidParam ;

                SET     isexecutedOut = 1;
            END;
            COMMIT;

        ELSE
            SET isexecutedOut = 0;
            SET errormsgOut = 'Old vehicle not found';
        END IF;

        SELECT  `name` 
        INTO    elixirOut 
        FROM    team 
        WHERE   teamid = eteamidParam
        LIMIT   1;

        SELECT          c.username
                        ,c.realname
                        ,c.email 
        INTO            usernameOut
                        ,realnameOut
                        ,emailOut 
        FROM            `user` c 
        LEFT OUTER JOIN groupman p ON p.groupid = groupidVar 
        LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
        WHERE           c.customerno =  customernoVar 
        AND             c.email <> '' 
        AND             (c.groupid=groupidVar OR c.groupid ='0' ) 
        AND             (c.role = 'Administrator' OR c.role = 'Master')
        ORDER BY        c.`userid` DESC
        LIMIT           1;

        SET newvehiclenoOut=newvehiclenoParam;
        
    ELSEIF statusParam = 3 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE 	`bucket` 
            SET 	`status` = statusParam
                        ,`is_problem_of` = unsuccessProblemParam
                        ,`remarks` = commentParam
                        ,`task_completion_timestamp` = todaysdateParam 
            where 	`bucketid`=bucketidParam;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,oldvehicleidVar
                    ,6
                    ,2
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoVar);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = oldsimcardidVar;
			
            SET     isexecutedOut = 1;
        END;
        COMMIT;
        
    ELSEIF statusParam = 6 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE  `bucket`  
            SET     `status` = statusParam 
                    ,`reschedule_date` = incompleteDateParam
                    ,`reschedule_timestamp` = todaysdateParam
                    ,remarks = commentParam
            where   `bucketid` = bucketidParam;
            
            INSERT INTO bucket (`apt_date`
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , `create_timestamp`
                    , `status`
                    ,`docketid`)
            SELECT  incompleteDateParam
                    ,`customerno`
                    ,`created_by`
                    ,`priority`
                    ,`vehicleid`
                    ,`location`
                    ,`timeslotid`
                    ,`purposeid`
                    ,`details`
                    ,`coordinatorid`
                    ,todaysdateParam
                    , 0
                    ,docketidParam
            FROM    `bucket`
            WHERE   `bucketid` = bucketidParam
            ORDER BY `bucketid` DESC
            LIMIT   1;
        
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,oldvehicleidVar
                    ,6
                    ,5
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoVar);

            SET     isexecutedOut = 1;
        END;
        COMMIT;
        
    ELSEIF statusParam = 1 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE  `bucket` 
            SET     `status` = statusParam 
                    ,`reschedule_date` = rescheduleDateParam
                    ,`reschedule_timestamp` = todaysdateParam 
                    ,remarks = commentParam
            WHERE   `bucketid` = bucketidParam;
            
            INSERT INTO bucket (`apt_date` 
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , `create_timestamp`
                    , `status`
                    ,`docketid`)
            SELECT rescheduleDateParam
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    , todaysdateParam
                    , 0
                    ,docketidParam
            FROM    `bucket`
            WHERE   `bucketid` = bucketidParam
            ORDER BY `bucketid` DESC
            LIMIT   1;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,oldvehicleidVar
                    ,6
                    ,3
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoVar);

            SET     isexecutedOut = 1;
        END;
        COMMIT;
        
    ELSEIF statusParam = 5 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE  `bucket` 
            SET     `status` = statusParam
                    ,`cancelled_timestamp` = todaysdateParam
                    ,`cancellation_reason` = commentParam 
            WHERE   `bucketid` = bucketidParam;
            
            INSERT INTO trans_history_new(`bucketid`
                    ,`oldunitid`
                    ,`oldvehicleid`
                    ,`transtypeid`
                    ,`bucketstatusid`
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,unitidParam
                    ,oldvehicleidVar
                    ,6
                    ,4
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoVar);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = oldsimcardidVar;
            
            SET     isexecutedOut = 1;
        END;
        COMMIT;
        
    END IF;

END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `repair`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `repair`(
    IN todaysdateParam DATETIME
    ,IN unitidParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,IN docketidParam INT
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnoOut VARCHAR(16)
    ,OUT simnumberOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
             
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
          
            SET isexecutedOut = 0;
	END;
    BEGIN    
        DECLARE unitnoVar VARCHAR(11);
        DECLARE vehicleidVar INT(11);
        DECLARE vehiclenoVar VARCHAR(40);
        DECLARE groupidVar INT(11);

        SELECT      unitno 
        INTO        unitnoVar 
        FROM        unit 
        WHERE       uid =unitidParam
        ORDER BY    uid DESC
        LIMIT       1;
		
        SELECT      vehicleid
                    ,vehicleno
                    ,groupid 
        INTO        vehicleidVar 
                    ,vehiclenoVar
                    ,groupidVar 
        FROM        vehicle 
        WHERE       uid =unitidParam
        ORDER BY    vehicleid DESC
        LIMIT       1;

        IF statusParam = 2 THEN

            START TRANSACTION;
            BEGIN

                UPDATE  unit 
                SET     trans_statusid= 5
                                , comments = commentParam 
                WHERE   uid= unitidParam;

                UPDATE  simcard 
                SET     trans_statusid= 13
                                ,comments =commentParam 
                WHERE   id=simcardidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
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
                        ,unitidParam
                        ,vehicleidVar
                        ,simcardidParam
                        ,7
                        ,1
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE 	bucket 
                SET 	`status` = statusParam
                        ,`task_completion_timestamp` = todaysdateParam 
                WHERE	bucketid= bucketidParam ;

                SET isexecutedOut = 1;

            END;
            COMMIT;

        

            SELECT      simcardno 
            INTO        simnumberOut 
            FROM        simcard 
            WHERE       id = simcardidParam
            ORDER BY    id DESC
            LIMIT       1;

        
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
            WHERE           c.customerno =  customernoParam  
            AND             c.email <> '' 
            AND             (c.groupid=groupidVar or c.groupid ='0' ) 
            AND             (c.role = 'Administrator' OR c.role = 'Master')
            ORDER BY        c.userid DESC
            LIMIT           1;

            SET vehiclenoOut=vehiclenoVar;
            SET unitnoOut=unitnoVar;

        ELSEIF statusParam = 3 THEN

            START TRANSACTION;
            BEGIN

                UPDATE 	bucket 
                SET 	`status`=statusParam
                        ,`is_problem_of` = unsuccessProblemParam
                        ,`remarks`= commentParam
                        ,`task_completion_timestamp` = todaysdateParam 
                where 	`bucketid`=bucketidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES ( bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'2'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = unitidParam;

                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = simcardidParam;

                SET     isexecutedOut = 1;

            END;
            COMMIT;

        ELSEIF statusParam = 6 THEN

            START TRANSACTION;
            BEGIN
     
                UPDATE	bucket 
                SET 	`status` = statusParam
                        ,`reschedule_date` = incompleteDateParam
                        ,`reschedule_timestamp` = todaysdateParam 
                        ,remarks = commentParam
                where 	bucketid = bucketidParam;

                INSERT INTO bucket(`apt_date`
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        , `status`
                        ,`docketid`)
                SELECT  incompleteDateParam
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , todaysdateParam
                        , 0
                        ,docketidParam
                FROM    `bucket`
                WHERE   `bucketid`=bucketidParam
                ORDER BY    bucketid DESC
                LIMIT       1;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'5'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;

            END;
            COMMIT;
            
        ELSEIF statusParam = 1 THEN

            START TRANSACTION;
            BEGIN

                UPDATE  bucket 
                SET     `status` = statusParam 
                        ,reschedule_date = rescheduleDateParam
                        ,reschedule_timestamp = todaysdateParam 
                        ,remarks = commentParam
                WHERE   bucketid = bucketidParam;
           
                INSERT INTO bucket (`apt_date`
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , `create_timestamp`
                        , `status`
                        ,`docketid`)
                SELECT 	rescheduleDateParam
                        ,`customerno`
                        ,`created_by`
                        , `priority`
                        , `vehicleid`
                        , `location`
                        , `timeslotid`
                        , `purposeid`
                        , `details`
                        , `coordinatorid`
                        , todaysdateParam
                        , 0
                        ,docketidParam
                FROM 	`bucket`
                WHERE	`bucketid`=bucketidParam
                ORDER BY    bucketid DESC
                LIMIT       1;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'3'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                SET     isexecutedOut = 1;

            END;
            COMMIT;   
            
        ELSEIF statusParam = 5 THEN

            START TRANSACTION;
            BEGIN

                UPDATE  bucket 
                SET     status = statusParam
                        , cancelled_timestamp = todaysdateParam
                        , cancellation_reason = commentParam 
                WHERE   bucketid = bucketidParam;

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
                        ,`oldvehicleid`
                        ,`transtypeid`
                        ,`bucketstatusid`
                        , `remark`
                        ,`teamid`
                        ,`createdby`
                        ,`createdon`
                        ,`customerno`)
                VALUES (bucketidParam
                        ,unitidParam
                        ,vehicleidVar
                        ,'7'
                        ,'4'
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = unitidParam;

                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = simcardidParam;

                SET     isexecutedOut = 1;

            END;
            COMMIT;

        END IF;

    END;
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `get_customer_count`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `get_customer_count`(
IN teamidParam INT)
BEGIN
DECLARE rel_mgr INT;
SELECT rid INTO rel_mgr from team where teamid = teamidParam;
select e.prodName,count(customerno) as total
from speed.customer c,elixiatech.elixiaOneProductMaster e 
where c.use_tracking = 1 AND e.prodId = 1 AND c.customerno IN (SELECT customerno  FROM customer where  rel_manager = rel_mgr)
UNION
select e.prodName,count(customerno) as total
from speed.customer c,elixiatech.elixiaOneProductMaster e 
where c.use_maintenance = 1 AND e.prodId = 3 AND c.customerno IN (SELECT customerno  FROM customer where  rel_manager = rel_mgr)
UNION
select e.prodName,count(customerno) as total
from speed.customer c,elixiatech.elixiaOneProductMaster e 
where c.use_trace = 1 AND e.prodId = 2 AND c.customerno IN (SELECT customerno  FROM customer where  rel_manager = rel_mgr)
UNION ALL
select e.prodName,count(customerno) as total
from speed.customer c,elixiatech.elixiaOneProductMaster e 
where c.use_elixiadoc = 1 AND e.prodId = 4 AND c.customerno IN (SELECT customerno  FROM customer where  rel_manager = rel_mgr)
UNION ALL
select e.prodName,count(customerno) as total
from speed.customer c,elixiatech.elixiaOneProductMaster e 
where c.use_secondary_sales = 1 AND e.prodId = 5 AND c.customerno IN (SELECT customerno  FROM customer where  rel_manager = rel_mgr)
UNION ALL
select e.prodName,count(customerno) as total
from speed.customer c,elixiatech.elixiaOneProductMaster e 
where c.use_erp = 1 AND e.prodId = 11 AND c.customerno IN (SELECT customerno  FROM customer where  rel_manager = rel_mgr)
UNION ALL
select e.prodName,count(customerno) as total
from speed.customer c,elixiatech.elixiaOneProductMaster e 
where c.use_warehouse = 1 AND e.prodId = 12 AND c.customerno IN (SELECT customerno  FROM customer where  rel_manager = rel_mgr)
Group by  e.prodId;

END$$

DELIMITER ;



USE `elixiatech`;
DROP procedure IF EXISTS `get_bucket_count`;

DELIMITER $$
USE `elixiatech`$$
CREATE  PROCEDURE `get_bucket_count`(
IN teamidParam INT)
BEGIN
DECLARE rel_mgr INT;
SELECT rid INTO rel_mgr from team where teamid = teamidParam;
Select s.type as status,COUNT(b.bucketid) as Total from speed.bucket b,speed.bucket_status s
where b.status=s.id AND b.customerno IN (SELECT customerno  FROM customer where  rel_manager = rel_mgr)
GROUP BY b.status
ORDER by b.status asc;
  
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `get_ticket_count`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `get_ticket_count`(
IN teamidParam INT)
BEGIN
DECLARE rel_mgr INT;
SELECT rid INTO rel_mgr from team where teamid = teamidParam;
 SELECT DISTINCT s.status,COUNT(t.ticketid) as Total
from sp_ticket t
LEFT JOIN sp_ticket_details td 
	ON (td.uid = (SELECT MAX(uid) from sp_ticket_details sds where sds.ticketid=t.ticketid))
LEFT JOIN ticket_status s 
	ON s.id = td.status
WHERE t.customerid IN (SELECT customerno  FROM customer where  rel_manager = rel_mgr)
GROUP BY s.status
ORDER BY s.status desc;

  
END$$

DELIMITER ;

USE `elixiatech`;
DROP procedure IF EXISTS `insert_additional_charges`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `insert_additional_charges`(
     IN ticketidParam INT
	,IN bucketidParam INT
    ,IN descriptionParam VARCHAR(255)
    ,IN amountParam FLOAT
    ,IN createdbyParam INT
    ,IN createdonParam datetime
    )
BEGIN
DECLARE t_id INT;
DECLARE b_id INT;
DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
       /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
        
        END;
		

	   IF(ticketidParam=0)                                         /*OPERATION RELATED CHARGES*/
       THEN
       SELECT COUNT(bucketid) INTO b_id from additional_charges where bucketid=bucketidParam;
			IF(b_id=0)
				THEN
					INSERT INTO additional_charges (`bucketid`, `description`,`amount`,`created_by`,`created_on`)
					VALUES (bucketidParam,descriptionParam,amountParam,createdbyParam,createdonParam);
			ELSE	
					UPDATE additional_charges 
					SET
					`description`=descriptionParam,
					`amount`=amountParam,
					`created_by`=createdbyParam,
					`created_on`=createdonParam
                    where `bucketid`=bucketidParam;

			END IF;	
       END IF;
       
      
      
      IF(bucketidParam=0)                            /*SOFTWARE RELATED CHARGES*/
		THEN
		SELECT COUNT(ticketid) INTO t_id from additional_charges where ticketid=ticketidParam;
       
              IF(t_id=0) THEN
					INSERT INTO additional_charges (`ticketid`, `description`,`amount`,`created_by`,`created_on`)
					VALUES (ticketidParam,descriptionParam,amountParam,createdbyParam,createdonParam);
			  ELSE
					UPDATE additional_charges 
					SET
					`description`=descriptionParam,
					`amount`=amountParam,
					`created_by`=createdbyParam,
					`created_on`=createdonParam
					where `ticketid`=ticketidParam;

			END IF;	
        END IF;
	

       
       

END$$

USE `elixiatech`;
DROP procedure IF EXISTS `getBucketAnalysis`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `getBucketAnalysis`(
  IN dateParam1 DATE,
    IN dateParam2 DATE,
    IN teamidParam INT,
    IN statusidParam INT,
    IN purposeidParam INT
)
BEGIN
  SELECT COUNT(b.bucketid) AS count,(CASE 
        WHEN b.status=0
        THEN 'Open'
        WHEN b.status=1 
        THEN 'Rescheduled' 
        WHEN b.status=2
        THEN 'Successful'
        WHEN b.status=3
        THEN 'Unsuccessful'
        WHEN b.status=4
        THEN 'FE Assigned' 
        WHEN b.status=5 
        THEN 'Cancelled'
        WHEN b.status=6
        THEN 'Incomplete'
    END)as status,
       (CASE 
       WHEN b.purposeid = 1
       THEN 'Installation'
       WHEN b.purposeid = 2
       THEN 'Repair'
       WHEN b.purposeid = 3
       THEN 'Removal'
       WHEN b.purposeid = 4
       THEN 'Replacement'
       WHEN b.purposeid = 5
       THEN 'Reinstall'
       END) as purpose, FLOOR((DAYOFMONTH(b.create_timestamp) - 1) / 7) + 1 AS week,DATE_FORMAT(b.create_timestamp,'%M') as `month`,
       (Select t.name from team t where t.teamid = b.created_by) as CRM
  FROM  speed.bucket b
  WHERE date(b.create_timestamp) BETWEEN dateParam1 AND dateParam2 AND (b.created_by=teamidParam OR teamidParam=0)
    AND (b.status = statusidParam OR statusidParam = 0)
    AND (b.purposeid = purposeidParam OR purposeidParam = 0)
    
  GROUP BY  b.status,b.purposeid,month,week
  ORDER BY b.create_timestamp;
END$$

DELIMITER ;




USE `elixiatech`;
DROP procedure IF EXISTS `getTicketAnalysis`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `getTicketAnalysis`(
  IN dateParam1 DATE,
    IN dateParam2 DATE,
    IN teamidParam INT,
    IN statusidParam INT
)
BEGIN
  SELECT COUNT(st.ticketid) AS count,FLOOR((DAYOFMONTH(st.create_on_date) - 1) / 7) + 1 AS week,DATE_FORMAT(st.create_on_date,'%M') as `month`
    ,stt.tickettype as type,ts.status,(Select t.name from team t where t.teamid = st.create_by) as CRM
  FROM  sp_ticket st
  INNER JOIN  sp_ticket_details std ON std.ticketid = st.ticketid 
      AND std.uid = (SELECT MAX(uid) from sp_ticket_details sds where sds.ticketid=st.ticketid)
    LEFT JOIN sp_tickettype stt ON stt.typeid = st.ticket_type
    LEFT JOIN ticket_status ts ON ts.id = std.status
  
  WHERE date(st.create_on_date) BETWEEN dateParam1 AND dateParam2 
     AND (st.create_by=teamidParam OR teamidParam=0)
    AND (std.status = statusidParam OR statusidParam = 0)
  GROUP BY  ts.status,stt.typeid,month,week
  ORDER BY st.create_on_date;
END$$

DELIMITER ;



UPDATE team 
SET email = 'rupali.elixiatech@gmail.com'
WHERE teamid = 106;

UPDATE team 
SET email = 'sshrikanth@elixiatech.com'
WHERE teamid = 37;


UPDATE team
set email = 'arvind.elixiatech@gmail.com',is_deleted = 0,role_id = 3,department_id=1,role='Service'
where teamid = 107



DROP TABLE IF EXISTS `additional_charges`;
CREATE TABLE `additional_charges` (
  `charge_id` int(11) NOT NULL,
  `ticketid` int(11) DEFAULT '0',
  `bucketid` int(11) DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`charge_id`)
);

ALTER TABLE speed.bucket
ADD COLUMN add_charge INT,
ADD COLUMN old_bucketid INT;

ALTER TABLE sp_ticket_details
ADD COLUMN add_charge INT;

INSERT into elixiaOneProductMaster
VALUES('12','Elixia Monitor',0,0);

ALTER TABLE speed.bucket
ADD COLUMN prevBucketId INT;


