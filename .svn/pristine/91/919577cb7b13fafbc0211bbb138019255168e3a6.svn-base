INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'542', '2018-03-03 12:45:00', 'Yash Kanakia', 'TEAM_CUSTOMER_AND_BUCKET', '0'
);


/*
    Name          - insert_cordinator
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `insert_cordinator`$$
CREATE PROCEDURE `insert_cordinator`(
	 IN conameParam VARCHAR(30)
	,IN cophoneParam VARCHAR(15)
	,IN customernoParam INT(11)
	,IN teamidParam INT(11)
	,IN todayParam datetime
    ,OUT lastInsertId INT
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
SET lastInsertId = 0;
INSERT INTO contactperson_details (`typeid`, `person_name`, `cp_phone1`, `customerno`,`insertedby`,`insertedon`) 
VALUES (3,conameParam,cophoneParam,customernoParam,teamidParam,todayParam);

INSERT INTO uat_elixiatech.contactperson_details (`typeid`, `person_name`, `cp_phone1`, `customerno`,`insertedby`,`insertedon`) 
VALUES (3,conameParam,cophoneParam,customernoParam,teamidParam,todayParam);
SET lastInsertId = LAST_INSERT_ID();
END$$

DELIMITER ;





/*
    Name          - insert_bucket
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `insert_bucket`$$
CREATE PROCEDURE `insert_bucket`(
    IN apt_dateParam date
    ,IN customernoParam INT(11)
    ,IN teamidParam INT(11)
    ,IN priorityParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN todayParam datetime
    ,IN vehnoParam VARCHAR(10)
   
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

INSERT INTO bucket (
        `apt_date` ,`customerno`,`created_by`, `priority`, `vehicleid`, `location`,
        `timeslotid`, `purposeid`, `details`, `coordinatorid`, `create_timestamp`, `status`, `vehicleno`)
VALUES (apt_dateParam, customernoParam, teamidParam, priorityParam, 0, locationParam,
        timeslotParam,1,detailsParam,coordinatorParam,todayParam,0,vehnoParam);


INSERT INTO uat_elixiatech.bucket (
        `apt_date` ,`customerno`,`created_by`, `priority`, `vehicleid`, `location`,
        `timeslotid`, `purposeid`, `details`, `coordinatorid`, `create_timestamp`, `status`, `vehicleno`)
VALUES (apt_dateParam, customernoParam, teamidParam, priorityParam, 0, locationParam,
        timeslotParam,1,detailsParam,coordinatorParam,todayParam,0,vehnoParam);


END$$

DELIMITER ;




/*
    Name          - edit_bucket
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `edit_bucket`$$
CREATE PROCEDURE `edit_bucket`(
	 IN apt_dateParam date
     ,IN coordinatorParam INT(11)
    ,IN priorityidParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam INT(4)
    ,IN purposeidParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN sstatusParam INT(4)
    ,IN todayParam datetime
    ,IN creasonParam VARCHAR(50)
    ,IN reschedule_dateParam date
    ,IN bucketidParam INT(11)
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

	SELECT apt_date,priority,location,timeslotid,purposeid,details,coordinatorid,status,cancellation_reason,reschedule_date,cancelled_timestamp
    INTO @aptdate,@priority,@location,@timeslotid,@purposeid,@details,@coordinatorid,@status,@creason,@reschedule_date,@cancelled_timestamp 
    FROM bucket
    WHERE bucketid = bucketidParam;
    
UPDATE `bucket` 
SET `apt_date`=COALESCE(apt_dateParam,@aptdate),
`priority`=COALESCE(priorityidParam,@priority),
`location`=COALESCE(locationParam,@location),
`timeslotid`=COALESCE(timeslotParam,@timeslotid),
`purposeid`=COALESCE(purposeidParam,@purposeid),
`details`=COALESCE(detailsParam,@details),
`coordinatorid`=COALESCE(coordinatorParam,@coordinatorid),
`reschedule_date`=COALESCE(reschedule_dateParam,@location),
`status`=COALESCE(sstatusParam,@status),
`reschedule_timestamp`=COALESCE(todayParam,@reschedule_date),
`cancelled_timestamp`=COALESCE(todayParam,@cancelled_timestamp),
`cancellation_reason`=COALESCE(creasonParam,@creason)
WHERE `bucketid`=bucketidParam;

UPDATE uat_elixiatech.`bucket` 
SET `apt_date`=COALESCE(apt_dateParam,@aptdate),
`priority`=COALESCE(priorityidParam,@priority),
`location`=COALESCE(locationParam,@location),
`timeslotid`=COALESCE(timeslotParam,@timeslotid),
`purposeid`=COALESCE(purposeidParam,@purposeid),
`details`=COALESCE(detailsParam,@details),
`coordinatorid`=COALESCE(coordinatorParam,@coordinatorid),
`reschedule_date`=COALESCE(reschedule_dateParam,@location),
`status`=COALESCE(sstatusParam,@status),
`reschedule_timestamp`=COALESCE(todayParam,@reschedule_date),
`cancelled_timestamp`=COALESCE(todayParam,@cancelled_timestamp),
`cancellation_reason`=COALESCE(creasonParam,@creason)
WHERE `bucketid`=bucketidParam;


            



END$$

DELIMITER ;




/*
    Name          - get_user_list
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_user_list`$$

CREATE PROCEDURE `get_user_list`(
IN customeridParam INT(11)
)
BEGIN 

select * from user 
WHERE customerno = customeridParam AND isdeleted=0 
order by userid desc;

END$$

DELIMITER ;


/*
    Name          - get_customer_list
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_customer_list`$$
CREATE PROCEDURE `get_customer_list`(
)
BEGIN 
SELECT  c.renewal
                        , c.totalsms
                        , c.customerno
                        , c.customername
                        , c.smsleft
                        , c.customercompany
                        ,c.lease_duration
                        ,c.lease_price
                        ,c.renewal
                        ,c.unit_msp
                        ,count(unit.unitno) AS cunit 
                        ,c.isoffline
                FROM    customer c
                LEFT OUTER JOIN unit ON c.customerno=unit.customerno AND unit.trans_statusid not in (10) 
                WHERE   c.renewal NOT IN (-1,-2) AND c.use_trace = 0
                GROUP BY c.customerno;

END$$

DELIMITER ;


/*
    Name          - get_bucket_list
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_bucket_list`$$
CREATE PROCEDURE `get_bucket_list`(
	IN fromdateParam date
   ,IN todateParam date
)
BEGIN 
SELECT u.unitno, s.simcardno, b.bucketid, b.customerno, c.customercompany, b.priority, v.vehicleno, b.location, b.purposeid, cp.person_name, cp.cp_phone1, t.name, b.vehicleno as vehno, b.vehicleid, sp.timeslot
                FROM bucket b
                INNER JOIN customer c ON c.customerno = b.customerno
                LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
                LEFT OUTER JOIN contactperson_details cp ON cp.cpdetailid = b.coordinatorid
                LEFT OUTER JOIN team t ON t.teamid = b.fe_id
                LEFT OUTER JOIN sp_timeslot sp ON sp.tsid = b.timeslotid                                        
                LEFT OUTER JOIN unit u ON u.uid = b.unitid
                LEFT OUTER JOIN simcard s ON s.id = b.simcardid
                WHERE b.apt_date BETWEEN fromdateParam AND todateParam AND b.status IN (0,4) ORDER BY sp.tsid;

END$$

DELIMITER ;


/*
    Name          - get_customer_additional_details
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_customer_additional_details`$$
CREATE PROCEDURE `get_customer_additional_details`(
	IN custnoParam INT(11)
    )
BEGIN 
SELECT *,cm.person_typeid,cm.person_type FROM  contactperson_details cd
            INNER JOIN contactperson_type_master as cm ON cd.typeid = cm.person_typeid
            WHERE customerno = custnoParam AND isdeleted = 0;
            

END$$

DELIMITER ;


/*
    Name          - get_pending_amount
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_pending_amount`$$
CREATE PROCEDURE `get_pending_amount`(
	IN custnoParam INT(11)
    )
BEGIN 
 SELECT  sum(pending_amt) as pending_amount
						   FROM  invoice
                          WHERE customerno = custnoParam;

                                                  
END$$

DELIMITER ;


/*
    Name          - get_credit_amount
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_credit_amount`$$
CREATE PROCEDURE `get_credit_amount`(
	IN custnoParam INT(11)
    )
BEGIN 
SELECT     sum(inv_amt) as cred_amt 
                        FROM       credit_note 
                        WHERE       status LIKE 'pending' 
                        AND         customerno = custnoParam;

                            


                           

END$$

DELIMITER ;


/*
    Name          - get_sim_count
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_sim_count`$$
CREATE PROCEDURE `get_sim_count`(
	IN custnoParam INT(11)
    )
BEGIN 
SELECT devices.deviceid,devices.customerno,devices.uid,count(simcardid) AS sim FROM devices
                     INNER JOIN simcard ON devices.simcardid = simcard.id AND simcard.trans_statusid IN (13,14,24,25)
                     WHERE devices.customerno = custnoParam AND simcard.vendorid <> 4;
                            


                           
END$$

DELIMITER ;


/*
    Name          - get_total_bucket
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_total_bucket`$$
CREATE PROCEDURE `get_total_bucket`(
	IN aptdateParam date
    )
BEGIN 
SELECT count(*) as totalcount FROM bucket b
                WHERE b.apt_date = aptdateParam AND b.status IN (0,4);


                           

END$$

DELIMITER ;


/*
    Name          - get_cordinator
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_cordinator`$$


CREATE PROCEDURE `get_cordinator`(
        IN customeridParam INT(11)
)
BEGIN
SELECT cpdetailid,person_name
    FROM contactperson_details
    WHERE customerno =customeridParam AND typeid = 3;

END$$

DELIMITER ;


/*
    Name          - get_timeslot
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_timeslot`$$
CREATE PROCEDURE `get_timeslot`(
       
)
BEGIN

SELECT tsid, timeslot FROM sp_timeslot;

END$$

DELIMITER ;



/*
    Name          - get_timezone
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_timezone`$$
CREATE PROCEDURE `get_timezone`(
    )
BEGIN 
SELECT * from timezone;
                            


END$$

DELIMITER ;



/*
    Name          - get_industry
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_industry`$$
CREATE PROCEDURE `get_industry`(
    )
BEGIN 
SELECT 	industryid,industry_type from sales_industry_type where isdeleted=0;
                            

END$$

DELIMITER ;


/*
    Name          - get_sales_person
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_sales_person`$$
CREATE PROCEDURE `get_sales_person`(
    )
BEGIN 
SELECT 	teamid,name from team where role='Sales';                       

END$$

DELIMITER ;


/*
    Name          - edit_customer
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_customer_details`$$
CREATE PROCEDURE `get_customer_details`(
	IN customeridParam INT(11)
    )
BEGIN 
SELECT * from customer  where customerno = customeridParam LIMIT 1;

            

END$$

DELIMITER ;


/*
    Name          - get_device_list
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_device_list`$$
CREATE PROCEDURE `get_device_list`(
	IN customeridParam INT(11)
)
BEGIN
SELECT devices.start_date, devices.end_date, devices.device_invoiceno, devices.invoiceno, unit.uid, devices.customerno, devices.expirydate, unit.unitno, trans_status.status, devices.lastupdated, devices.registeredon,
                vehicle.vehicleno,vehicle.vehicleid,vehicle.sms_lock, unit.command, unit.setcom, devices.simcardid,unit.extra_digital,t1.transmitterno as transmitter1,t2.transmitterno as transmitter2
                FROM devices
                INNER JOIN unit ON unit.uid = devices.uid
                LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid
                LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
                LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid
                LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid
                INNER JOIN trans_status ON trans_status.id = unit.trans_statusid WHERE unit.customerno = customeridParam
                ORDER BY unit.trans_statusid ASC, simcard.trans_statusid DESC, devices.expirydate DESC;
END$$

DELIMITER ;


/*
    Name          - get_sim_details
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_sim_details`$$
CREATE PROCEDURE `get_sim_details`(
simcardidParam INT(11)
)
BEGIN

SELECT * FROM simcard 
INNER JOIN trans_status ON trans_status.id = simcard.trans_statusid 
WHERE simcard.id = simcardidParam;

END$$

DELIMITER ;


/*
    Name          - get_login_history
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_login_history`$$
CREATE PROCEDURE `get_login_history`(
	IN customeridParam INT(11)
	,IN dateParam date

)
BEGIN

select lh.created_by,user.realname,user.username, customer.customerno, customer.customercompany, lh.type,
    SUM(CASE WHEN page_master.is_web = 1 THEN 1 ELSE 0 END) as androidcount,    
    SUM(CASE WHEN page_master.is_web = 0 THEN 1 ELSE 0 END) as webcount,
    SUM(CASE WHEN page_master.is_web = 2 THEN 1 ELSE 0 END) as ioscount 
    from login_history_details as lh
    inner join user on user.userid = lh.created_by
    INNER JOIN page_master on page_master.page_master_id=lh.page_master_id
    inner join customer on customer.customerno = lh.customerno
    Where lh.customerno= customeridParam AND created_on LIKE CONCAT('%',dateParam,'%') group by lh.created_by order by lh.customerno ASC ;



END$$

DELIMITER ;

/*
    Name          - get_notes
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_notes`$$
CREATE PROCEDURE `get_notes`(
	IN customeridParam INT(11)


)
BEGIN
select * from customer_notes WHERE customerno = customeridParam 
AND isdeleted=0 order by entrytime desc;



END$$

DELIMITER ;


/*
    Name          - add_customer_notes
    Description   -
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `add_customer_notes`$$
CREATE PROCEDURE `add_customer_notes`(
	IN customer_notesParam VARCHAR(500)
   ,IN customeridParam INT(11)
   ,IN todayParam datetime
   ,IN teamidParam INT(11)

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

INSERT INTO `customer_notes` (
                `details`,
                `customerno` ,
                `entrytime`,
                `added_by`
                )VALUES (customer_notesParam,customeridParam,todayParam,teamidParam);

INSERT INTO uat_elixiatech.`customer_notes` (
                `details`,
                `customerno` ,
                `entrytime`,
                `added_by`
                )VALUES (customer_notesParam,customeridParam,todayParam,teamidParam);

END$$

DELIMITER ;



/*
    Name          - get_bucket_details
    Description   - 
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_bucket_details`$$
CREATE PROCEDURE `get_bucket_details`(
IN bucketidParam INT(11)
)
BEGIN
SELECT b.bucketid, b.apt_date, b.customerno, c.customercompany, b.priority, v.vehicleno, b.location, sp.timeslot, b.purposeid, b.details, cp.person_name, cp.cp_phone1, t.name, b.created_by, b.vehicleid, b.coordinatorid, b.timeslotid, b.vehicleno as vehno, b.vehicleid
FROM `bucket` b
INNER JOIN customer c ON c.customerno = b.customerno
LEFT OUTER JOIN sp_timeslot sp ON sp.tsid = b.timeslotid    
LEFT OUTER JOIN contactperson_details cp ON cp.cpdetailid = b.coordinatorid    
LEFT OUTER JOIN team t ON t.teamid = b.created_by    
LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
where b.bucketid=bucketidParam;

END$$

DELIMITER ;



/*
    Name          - get_bucket_details
    Description   - 
    Parameters    -

    Module    - TEAM
    Sample Call   -

    Created by    - Yash Kanakia
    Created on    - 03-03-2018
    Change details  -
    1)  Updated by  -       
        Updated on  -  
        Reason     - 
*/
DELIMITER $$
DROP procedure IF EXISTS `get_contact_typemaster`$$


CREATE PROCEDURE `get_contact_typemaster`(
    
    )
BEGIN 
SELECT * FROM contactperson_type_master;
            

END$$

DELIMITER ;


UPDATE dbpatches SET isapplied = 1, patchdate = '2018-03-03 12:45:00' where patchid = 542;