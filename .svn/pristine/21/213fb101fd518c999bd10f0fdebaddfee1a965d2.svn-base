/*
    Name		-	delete_ledger
    Description 	-	Delete Ledger Details
    Parameters		-	ledgeridparam INT,
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL delete_ledger(1,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	03 May, 2016
        Reason		-	delete mapping with the vehicle while ledger gets deleted.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_ledger`$$
CREATE PROCEDURE `delete_ledger`( 
	IN ledgeridparam INT
	, IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
	UPDATE ledger SET 
    isdeleted = 1
    ,updatedby = updatedby
    ,updatedon = updatedon
    WHERE ledgerid = ledgeridparam
    ;
    /* DELETE LEDGER MAPPING WITH VEHICLE */
    CALL `delete_ledger_veh_mapping`(0,0,ledgeridparam,updatedby,updatedon);    
END$$
DELIMITER ;



/*
    Name		-	delete_ledger_cust_mapping`
    Description 	-	Delete Ledger Customerno mapping
    Parameters		-	ledgeridparam INT,updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL delete_ledger_cust_mapping`(1,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_ledger_cust_mapping`$$
CREATE PROCEDURE `delete_ledger_cust_mapping`( 
	IN ledgeridparam INT
	, IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
	UPDATE ledger_cust_mapping SET 
    isdeleted = 1
    ,updatedby = updatedby
    ,updatedon = updatedon
    WHERE ledgerid = ledgeridparam
    ;
END$$
DELIMITER ;



/*
    Name		-	delete_ledger_veh_mapping`
    Description 	-	Delete Ledger Vehicle mapping
    Parameters		-	ledger_veh_mapidparam INT,customernoparam INT,ledgeridparam INT,updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL delete_ledger_veh_mapping`(1,2,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_ledger_veh_mapping`$$
CREATE PROCEDURE `delete_ledger_veh_mapping`( 
    IN ledger_veh_mapidparam INT
    , IN customernoparam INT
    , IN ledgeridparam INT
    , IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN
    IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN
        SET ledger_veh_mapidparam = NULL;
    END IF;

    IF(customernoparam = '' OR customernoparam = '0') THEN
        SET customernoparam = NULL;
    END IF;

    IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
        SET ledgeridparam = NULL;
    END IF;

    UPDATE  ledger_veh_mapping as lv
    SET     lv.isdeleted = 1
            ,lv.updatedby = updatedby
            ,lv.updatedon = updatedon
    WHERE   (lv.ledger_veh_mapid  = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL)
    AND     (lv.customerno = customernoparam OR customernoparam IS NULL)
    AND     (lv.ledgerid = ledgeridparam OR ledgeridparam IS NULL);

END$$
DELIMITER ;


/*
    Name		-	delete_po
    Description 	-	Delete PO Details
    Parameters		-	poidparam INT,custparam INT
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL delete_po(1,2,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_po`$$
CREATE  PROCEDURE `delete_po`(
	IN poidparam INT
    , IN custparam INT
    , IN updatedby INT
    , IN updatedon DATETIME
	)
BEGIN
UPDATE po
SET isdeleted = 1
 ,updatedby = updatedby
 ,updatedon = updatedon
WHERE poid  =  poidparam
AND customerno = custparam
;
END$$
DELIMITER ;



/*
	**** NOTE: IT INVOLVES DELETE STATEMENTS AND CAN NOT BE REVERTED ****
    Name			-	delete_unit_details
    Description 	-	Delete unit data in all the required tables.
						SPECIFICALLY CREATED TO DELETE DUPLICATE UNITS
    Parameters		-	unitnoParam
						, custnoParam - Customer number in which this unit no is already present and needs to be deleted
    Module			-	Team
    Sub-Modules 	- 	
    Sample Call		-	CALL delete_unit_details('904414', 177, @isUnitDeleted);
						SELECT @isUnitDeleted;
    Created by		-	Mrudang Vora
    Created on		- 	20 July, 2016
    Change details 	-	
    1) 	Updated by	- 	
		Updated	on	- 	
		Reason		-	
	2) 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_unit_details`$$
CREATE PROCEDURE `delete_unit_details` (
	IN unitnoParam VARCHAR(11)
	, IN custnoParam INT
    , OUT isUnitDeleted  INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		/* 
			It would work for mysql version >= 5.6.4 and mariadb.
            Uncomment the following lines to check error if you have above mentioned versions.        
        */
		/*
		GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
		@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
		SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
		SELECT @full_error;
        */
		ROLLBACK;
        SET isUnitDeleted = 0;
	END;
	START TRANSACTION;
		/* UNIT TABLE OPERATIONS */
		SET @uidToBeDeleted = (SELECT uid FROM unit WHERE unitno = unitnoParam AND customerno = custnoParam LIMIT 1);
		DELETE FROM unit 			WHERE uid = @uidToBeDeleted;

		/* VEHICLE TABLE OPERATIONS */
        SET @vehicleidToBeDeleted = (SELECT vehicleid FROM vehicle WHERE uid = @uidToBeDeleted LIMIT 1);
        DELETE FROM vehicle 		WHERE vehicleid = @vehicleidToBeDeleted;

		/* DRIVER TABLE OPERATIONS */        
        DELETE FROM driver 			WHERE vehicleid = @vehicleidToBeDeleted;

		/* IGNITION ALERT TABLE OPERATIONS */
		DELETE FROM ignitionalert 	WHERE vehicleid = @vehicleidToBeDeleted;
       
		/* EVENT ALERT TABLE OPERATIONS */
		DELETE FROM eventalerts 	WHERE vehicleid = @vehicleidToBeDeleted;

		/* DEVICES TABLE OPERATIONS */
        SET @simcardidToBeDeleted = (SELECT simcardid FROM devices WHERE uid = @uidToBeDeleted LIMIT 1);
        
        DELETE FROM devices 		WHERE uid = @uidToBeDeleted;
		
		/* SIMCARD TABLE OPERATIONS */
		DELETE FROM simcard 		WHERE id = @simcardidToBeDeleted;

		/* DAILY REPORT OPERATIONS */
		DELETE FROM dailyreport		WHERE vehicleid = @vehicleidToBeDeleted;
		
        SET isUnitDeleted = 1;
    COMMIT;

END$$
DELIMITER ;

   
DELIMITER $$
DROP PROCEDURE IF EXISTS `editBucketCRM`$$
CREATE PROCEDURE `editBucketCRM`(
     IN statusParam TINYINT(2)
    ,IN customernoParam INT(11)
    ,IN vehicleidParam INT(11)
    ,IN createdbyParam INT(11)
    ,IN priorityidParam TINYINT(2)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam TINYINT(2)
    ,IN purposeidParam TINYINT(2)
    ,IN detailsParam VARCHAR(100)
    ,IN dataParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN aptdateParam DATETIME
    ,IN conameParam VARCHAR(50)
    ,IN cophoneParam INT(11)
    ,IN bucketidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,OUT isexecutedOut TINYINT(2))
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

    START TRANSACTION;	 
    BEGIN

        IF conameParam <> '' THEN

            INSERT INTO contactperson_details (`typeid` 
                    ,`person_name`
                    ,`cp_phone1`
                    , `customerno`
                    , `insertedby`
                    , `insertedon`)
            VALUES (3
                    ,conameParam
                    , cophoneParam
                    , customernoParam
                    , createdbyParam
                    , todaysdateParam);

            SELECT LAST_INSERT_ID() INTO coordinatorParam;

        END IF;

        IF statusParam = 0 THEN

            UPDATE  bucket 
            SET     apt_date = aptdateParam 
                    , coordinatorid = coordinatorParam
                    , priority = priorityidParam
                    , location = locationParam
                    , timeslotid = timeslotParam
                    , purposeid = purposeidParam
                    , details = detailsParam
                    , status = statusParam
                    , create_timestamp = todaysdateParam 
            where   bucketid = bucketidParam;

        END IF;

        IF statusParam = 5 THEN

            UPDATE  bucket 
            SET     status=statusParam
                    , cancelled_timestamp = todaysdateParam
                    , cancellation_reason = dataParam 
            where   bucketid=bucketidParam;

        END IF;

        IF statusParam = 1 THEN

            UPDATE  bucket 
            SET     status= statusParam
                    , reschedule_date= dataParam
                    , reschedule_timestamp = todaysdateParam 
            where   bucketid = bucketidParam;


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
                , `create_timestamp`, status)
            VALUES (dataParam,customernoParam
                ,createdbyParam
                , priorityidParam
                , vehicleidParam
                ,locationParam
                ,timeslotParam
                ,purposeidParam
                ,detailsParam
                ,coordinatorParam
                ,todaysdateParam
                ,0);

        END IF;
        
        SET isexecutedOut = 1;
    END;
    COMMIT;
    
END;
END$$
DELIMITER ; 


DELIMITER $$
DROP PROCEDURE IF EXISTS `editBucketOperation`$$
CREATE PROCEDURE `editBucketOperation`(
      IN statusParam INT(11)
     ,IN bucketidParam INT(11)
     ,IN dataParam VARCHAR(100)
     ,IN todaysdateParam DATETIME
     ,OUT isexecutedOut TINYINT(2))
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

    START TRANSACTION;	 
    BEGIN
        IF statusParam = 4 THEN

            UPDATE  bucket 
            SET     fe_id= dataParam
                    ,status=statusParam
                    ,fe_assigned_timestamp = todaysdateParam 
            where   bucketid= bucketidParam;

        END IF;

        IF statusParam = 5 THEN

            UPDATE  bucket 
            SET     status=statusParam 
                    ,cancelled_timestamp = todaysdateParam 
                    , cancellation_reason= dataParam 
            where   bucketid=bucketidParam;

        END IF;

        IF statusParam = 1 THEN

            UPDATE  bucket 
            SET     status =statusParam
                    , reschedule_date=dataParam
                    ,reschedule_timestamp = todaysdateParam 
            where   bucketid=bucketidParam;

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
                    , `status`)
            SELECT   dataParam
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
            FROM    bucket 
            WHERE   bucketid=bucketidParam
            ORDER BY `bucketid` DESC
            LIMIT   1;

        END IF;

        SET isexecutedOut = 1;

    END;
    COMMIT;

END;

END$$
DELIMITER ; 


/*
    Name		-	get_contact_person_details
    Description 	-	get Contact Person Details
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_all_customer();
    Created by		-	Arvind
    Created on		- 	12 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DROP PROCEDURE IF EXISTS `get_contact_person`;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_contact_person_owner`$$
CREATE PROCEDURE `get_contact_person_owner`(
        IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;
SELECT cp.person_name
        ,cp.cp_email1
        ,cp.cp_phone1
    FROM contactperson_details as cp
LEFT JOIN customer on customer.customerno=cp.customerno
WHERE customer.customerno=customernos AND cp.typeid=1 AND cp.isdeleted=0 AND (cp.person_name='' OR cp.cp_email1='' OR cp.cp_phone1='') 

ORDER BY cp.typeid
;     
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `get_contact_person_account`$$
CREATE PROCEDURE `get_contact_person_account`(
        IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    SELECT  cp.person_name
            ,cp.cp_email1
            ,cp.cp_phone1
    FROM    contactperson_details as cp
    LEFT JOIN customer on customer.customerno=cp.customerno
    WHERE   customer.customerno=customernos 
    AND     cp.typeid = 2 
    AND     cp.isdeleted = 0 
    AND     (cp.person_name='' OR cp.cp_email1='' OR cp.cp_phone1='') 
    ORDER BY cp.typeid
    ;     
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_contact_person_coordinator`$$
CREATE PROCEDURE `get_contact_person_coordinator`(
        IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    SELECT  cp.person_name
            ,cp.cp_email1
            ,cp.cp_phone1
    FROM    contactperson_details as cp
    LEFT JOIN customer on customer.customerno=cp.customerno
    WHERE   customer.customerno=customernos 
    AND     cp.typeid = 3 
    AND     cp.isdeleted = 0 
    AND     (cp.person_name='' OR cp.cp_email1='' OR cp.cp_phone1='') 
    ORDER BY cp.typeid;
     
END$$
DELIMITER ;

/*
    Name		-	get_customer_not_allotted_crm
    Description 	-	get Customer Not Alloted CRM Details
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_customer_not_allot_crm();
    Created by		-	Arvind
    Created on		- 	12 September, 2016
    Change details 	-	
    1) 	Updated by	- 	Sanket
	Updated	on	- 	25 OCT, 2016
        Reason		-	Removed Demo (-1) and Closed (-2) customers
    2) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_customer_not_allot_crm`$$
CREATE PROCEDURE `get_customer_not_allot_crm`()
BEGIN
   
    SELECT  c.customerno
            ,c.customername 
            ,c.customercompany
    FROM    `customer` as c
    WHERE   c.use_trace = 0 
    AND     (c.rel_manager IS NULL OR c.rel_manager = '' OR c.rel_manager=0);
     
END$$
DELIMITER ;




/*
    Name		-	get_expired_devices
    Description 	-	get Expired Devices of customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_expired_devices(3,'2016-09-16','2016-10-01');
    Created by		-	Arvind
    Created on		- 	16 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_expired_devices`$$
CREATE PROCEDURE `get_expired_devices`(
        IN customernos INT,
        IN today date
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    IF(today = '' OR today = '0') THEN
        SET today = NULL;
    END IF;

    SELECT      unit.unitno 
    FROM        vehicle 
    INNER JOIN  devices ON devices.uid = vehicle.uid 
    INNER JOIN  unit ON devices.uid = unit.uid 
    LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
    WHERE       vehicle.isdeleted= 0 
    AND         devices.expirydate < today 
    AND         unit.customerno NOT IN (-1,1) 
    AND         unit.customerno = customernos 
    AND         devices.expirydate !='1970-01-01' 
    AND         devices.expirydate!='0000-00-00' 
    AND         unit.trans_statusid NOT IN(22,23,24,10);
     
END$$
DELIMITER ;




/*
    Name		-	get_ledger
    Description 	-	get Ledger Details
    Parameters		-	ledgeridparam INT,ledgernameparam VARCHAR(100)
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_ledger(2,'fedex');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-
    1) 	Updated by	- 	Sahil
	    Updated	on	- 	30 April, 2016
        Reason		-	New SP.
    1)  Updated by  -   Shrikant
        Updated on  -   25 Jan 2017
        Reason      -   Change in search condition for ledgername - Check "=" instead if "LIKE".
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger`$$
CREATE PROCEDURE `get_ledger`( 
    IN ledgeridparam INT
    , IN ledgernameparam VARCHAR(100)
)
BEGIN
	IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
            SET ledgeridparam = NULL;
	END IF;

	IF(ledgernameparam = '' OR ledgernameparam = '0') THEN
            SET ledgernameparam = NULL;
	END IF;
    
        SELECT  l.ledgerid
                ,l.ledgername
                ,l.address1
                ,l.address2
                ,l.address3
                ,l.email
                ,l.phone
                ,l.pan_no
                ,l.cst_no
                ,l.st_no
                ,l.vat_no
                ,l.createdby
                ,l.createdon
                ,l.updatedby
                ,l.updatedon
        FROM    ledger AS l
        WHERE   (l.ledgerid  = ledgeridparam OR ledgeridparam IS NULL)
        AND     (l.ledgername LIKE CONCAT('%', ledgernameparam, '%') OR ledgernameparam IS NULL)
        AND     l.isdeleted = 0
        ORDER BY l.ledgerid DESC;
     
END$$
DELIMITER ;



/*
    Name		-	get_ledger_cust_mapping
    Description 	-	Map Ledger Details to Customers 
    Parameters		-	ledgeridparam INT,customernoparam INT,ledgernameparam VARCHAR(100)
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_ledger_cust_mapping(1,2,'fedex');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_cust_mapping`$$
CREATE PROCEDURE `get_ledger_cust_mapping`( 
    IN ledgeridparam INT
    ,IN customernoparam INT
    ,IN ledgernameparam VARCHAR(100)
)
BEGIN
	IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
            SET ledgeridparam = NULL;
	END IF;

	IF(ledgernameparam = '' OR ledgernameparam = '0') THEN
            SET ledgernameparam = NULL;
	END IF;
    
        IF(customernoparam = '' OR customernoparam = '0') THEN
            SET customernoparam = NULL;
	END IF;
    
        SELECT      l.ledgerid
                    ,l.ledgername
                    ,l.address1
                    ,l.address2
                    ,l.address3
                    ,l.email
                    ,l.phone
                    ,l.pan_no
                    ,l.cst_no
                    ,l.st_no
                    ,l.vat_no
                    ,lc.customerno
        FROM        ledger_cust_mapping AS lc
        INNER JOIN  ledger AS l ON l.ledgerid = lc.ledgerid
        WHERE       (lc.ledgerid  = ledgeridparam OR ledgeridparam IS NULL)
        AND         (lc.customerno  = customernoparam OR customernoparam IS NULL)
        AND         (l.ledgername LIKE CONCAT('%', ledgernameparam, '%') OR ledgernameparam IS NULL)
        AND         lc.isdeleted = 0; 
    
END$$
DELIMITER ;


/*
    Name		-	get_all_vehicleid_for_customer
    Description 	-	get All vehicleids for customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_all_vehicleid_for_customer('3');
    Created by		-	Arvind
    Created on		- 	12 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_all_vehicleid_for_customer`$$
CREATE PROCEDURE `get_all_vehicleid_for_customer`(
        IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    SELECT  v.vehicleid
            ,v.vehicleno
    FROM    vehicle as v
    WHERE   v.customerno=customernos 
    AND     v.isdeleted=0
    ORDER BY v.vehicleid;     

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_for_vehicle_id`$$
CREATE PROCEDURE `get_ledger_for_vehicle_id`(
    IN vehicleids INT
)
BEGIN

    IF(vehicleids = '' OR vehicleids = '0') THEN
        SET vehicleids = NULL;
    END IF;

    SELECT  l.ledgerid  
    FROM    `ledger_veh_mapping` as l
    WHERE   l.vehicleid=vehicleids 
    AND     l.isdeleted=0;
     
END$$
DELIMITER ;



/*
    Name		-	get_ledger_map_cust
    Description 	-	get Ledger Mapped with Customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_ledger_map_cust('3');
    Created by		-	Arvind
    Created on		- 	13 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_map_cust`$$
CREATE PROCEDURE `get_ledger_map_cust`(
    IN customernos INT
)
BEGIN

    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    SELECT  l.ledgerid 
    FROM    `ledger_cust_mapping` as l 
    WHERE   l.customerno=customernos 
    AND     `isdeleted` = 0;     

END$$
DELIMITER ;


/*
    Name		-	get_ledger_veh_mapping
    Description 	-	GET Mapped Ledger Details with vehicle 
    Parameters		-	ledger_veh_mapidparam INT,customernoparam INT,IN ledgeridparam INT,vehiclenoparam VARCHAR(20)
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	get_ledger_veh_mapping(1,2,4,'MH 02 AP 2514');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    Change details 	-	
    1) 	Updated by	- 	Mrudang Vora
		Updated	on	- 	07 June, 2016
		Reason		-	Team Ledger Vehicle Mapping Issue (Added Joins with device and unit)
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_veh_mapping`$$
CREATE PROCEDURE `get_ledger_veh_mapping`( 
    IN ledger_veh_mapidparam INT
    , IN customernoparam INT
    , IN ledgeridparam INT
    , IN vehiclenoparam VARCHAR(20)
)
BEGIN

    IF(ledger_veh_mapidparam = '' OR ledger_veh_mapidparam = '0') THEN
        SET ledger_veh_mapidparam = NULL;
    END IF;

    IF(customernoparam = '' OR customernoparam = '0') THEN
        SET customernoparam = NULL;
    END IF;

    IF(vehiclenoparam = '' OR vehiclenoparam = '0') THEN
        SET vehiclenoparam = NULL;
    END IF;

    IF(ledgeridparam = '' OR ledgeridparam = '0') THEN
        SET ledgeridparam = NULL;
    END IF;

    SELECT      l.ledger_veh_mapid
                ,l.ledgerid
                ,l.vehicleid
                ,l.customerno
                ,v.vehicleno
                ,l.createdby
                ,l.createdon
                ,l.updatedby
                ,l.updatedon
    FROM        ledger_veh_mapping as l
    INNER JOIN  vehicle as v ON l.vehicleid = v.vehicleid
    INNER JOIN  unit as u ON u.vehicleid = v.vehicleid
    INNER JOIN  devices as d ON d.uid = u.uid
    WHERE       (l.ledger_veh_mapid  = ledger_veh_mapidparam OR ledger_veh_mapidparam IS NULL)
    AND         (l.customerno = customernoparam OR customernoparam IS NULL)
    AND         (v.customerno = customernoparam OR customernoparam IS NULL)
    AND         (l.ledgerid = ledgeridparam OR ledgeridparam IS NULL)
    AND         (v.vehicleno LIKE CONCAT('%', vehiclenoparam, '%') OR vehiclenoparam IS NULL)
    AND         l.isdeleted = 0
    ORDER BY    v.vehicleno ASC;

END$$
DELIMITER ;


/*
    Name		-	get_low_sms_left_cust
    Description 	-	get Customer who have sms left less than 50
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_low_sms_left_cust();
    Created by		-	Arvind
    Created on		- 	16 September, 2016
    Change details 	-	
    1) 	Updated by	- 	Sanket
	Updated	on	- 	25 OCT, 2016
        Reason		-	Removed Demo (-1) and Closed (-2) customers
    2) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_low_sms_left_cust`$$
CREATE PROCEDURE `get_low_sms_left_cust`()
BEGIN
    
    SELECT  c.customerno
            ,c.customercompany
            ,c.smsleft
    FROM    customer AS c
    WHERE   c.customercompany <> 'Elixia Tech' 
    AND     c.use_trace = 0 
    AND     c.smsleft < 50
    ORDER BY c.customerno ASC
;     
END$$
DELIMITER ;



/*
    Name		-	get_pending_invoices
    Description 	-	get All pending invoices of customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_pending_invoices('3');
    Created by		-	Arvind
    Created on		- 	13 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_pending_invoices`$$
CREATE PROCEDURE `get_pending_invoices`(
        IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    SELECT  count(d.device_invoiceno) AS pending_invoices
    FROM    `devices` as d
    WHERE   d.customerno=customernos 
    AND     d.device_invoiceno='';
     
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_pending_renewal`$$
CREATE PROCEDURE `get_pending_renewal`(
    IN customernos INT(11)
    ,IN startdate date
    ,IN enddate date
)
BEGIN

    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    SELECT          vehicle.vehicleno 
    FROM            vehicle 
    INNER JOIN      devices ON devices.uid = vehicle.uid 
    INNER JOIN      driver ON driver.driverid = vehicle.driverid 
    INNER JOIN      unit ON devices.uid = unit.uid 
    LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
    WHERE           vehicle.isdeleted = 0 
    AND             (devices.expirydate BETWEEN startdate AND enddate) 
    AND             unit.customerno NOT IN (-1,1) 
    AND             unit.customerno = customernos 
    AND             devices.expirydate != '1970-01-01' 
    AND             devices.expirydate != '0000-00-00' 
    AND             unit.trans_statusid NOT IN(23,24,10)
    ORDER BY        vehicle.vehicleno;
     
END$$
DELIMITER ;


/*
    Name		-	get_po
    Description 	-	get PO Details
    Parameters		-	customernoparam INT,poidparam VARCHAR(11)
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_po(2,1);
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	28 April, 2016
        Reason		-	Changed poidparam to varchar in order to handle blank values.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_po`$$
CREATE PROCEDURE `get_po`( 
    IN customernoparam INT(11)
    ,IN poidparam VARCHAR(11)
)
BEGIN

    IF(customernoparam = '' OR customernoparam = '0') THEN
        SET customernoparam = NULL;
    END IF;

    IF(poidparam = '') THEN
        SET poidparam = NULL;
    END IF;

    SELECT  poid
            ,pono
            ,podate
            ,poamount
            ,poexpiry
            ,description
            ,customerno
            ,createdby
            ,createdon
            ,updatedby
            ,updatedon
    FROM    po
    WHERE   (customerno = customernoparam OR customernoparam IS NULL)
    AND     (poid = poidparam OR poidparam IS NULL)
    AND     isdeleted = 0;

END$$
DELIMITER ;


/*
    Name		-	get_sms_consume_frm_comq
    Description 	-	get sms consume by customer from comqueue table on todaysdateParam.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_sms_consume_frm_comq(3,'2016-09-16');
    Created by		-	Arvind
    Created on		- 	16 September, 2016
    Change details 	-	
    1) 	Updated by	- 	Arvind Thakur
	Updated	on	- 	2016-11-29
        Reason		-	Wrong DateFormat
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_comq`$$
CREATE PROCEDURE `get_sms_consume_frm_comq`(
    IN customernoParam INT(11)
    ,IN todaysdateParam DATE
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

    SELECT  COUNT(cq.cqhid) AS count1 
    FROM    `comhistory` as cq
    WHERE   cq.customerno = customernoParam 
    AND     cq.timesent LIKE todayVar 
    AND     cq.comtype = 1;

END$$
DELIMITER ;


/*
    Name		-	get_sms_consume_frm_smslog
    Description 	-	get sms consume by customer from smslog table on todaysdateParam.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_sms_consume_frm_smslog(3,'2016-09-16');
    Created by		-	Arvind
    Created on		- 	16 September, 2016
    Change details 	-	
    1) 	Updated by	-       Arvind Thakur
	Updated	on	- 	2016-11-29
        Reason		-	Wrong DateFormat
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_smslog`$$
CREATE PROCEDURE `get_sms_consume_frm_smslog`(
    IN customernoParam INT
    ,IN todaysdateParam DATE
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

    SELECT  COUNT(sm.smsid) AS count1 
    FROM    `smslog` AS sm
    WHERE   sm.customerno = customernoParam 
    AND     sm.inserted_datetime LIKE todayVar;

END$$
DELIMITER ;



/*
    Name		-	get_will_expire_devices
    Description 	-	get Devices that will expire in 15 days of customer
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL get_will_expire_devices(3,'2016-09-16','2016-10-01');
    Created by		-	Arvind
    Created on		- 	16 September, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_will_expire_devices`$$
CREATE PROCEDURE `get_will_expire_devices`(
    IN customernos INT
    ,IN today date
    ,IN enddate date
)
BEGIN

    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    IF(today = '' OR today = '0') THEN
        SET today = NULL;
    END IF;

    IF(enddate = '' OR enddate = '0') THEN
        SET enddate = NULL;
    END IF;

    SELECT      unit.unitno 
    FROM        vehicle 
    INNER JOIN  devices ON devices.uid = vehicle.uid 
    INNER JOIN  driver ON driver.driverid = vehicle.driverid 
    INNER JOIN  unit ON devices.uid = unit.uid 
    LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
    WHERE       vehicle.isdeleted = 0 
    AND         (devices.expirydate BETWEEN today AND enddate) 
    AND         unit.customerno = customernos 
    AND         unit.customerno NOT IN (-1,1) 
    AND         devices.expirydate != '1970-01-01' 
    AND         devices.expirydate != '0000-00-00' 
    AND         unit.trans_statusid NOT IN(23,24,10);
  
END$$
DELIMITER ;


/*
    Name			-	insert_duplicate_unit
    Description 	-	Inserts duplicate unit data in all the required tables.
						NEEDS TO BE USED WITH CARE
    Parameters		-	unitnoParam
						, oldCustnoParam - Customer number in which this unit no is already present
						, newCustnoParam - Customer number in which we need to add this unit no
    Module			-	Team
    Sub-Modules 	- 	
    Sample Call		-	CALL insert_duplicate_unit('904414', 250, 212, @isDuplicateUnitAdded);
						SELECT @isDuplicateUnitAdded;
    Created by		-	Mrudang Vora
    Created on		- 	20 July, 2016
    Change details 	-	
    1) 	Updated by	- 	
		Updated	on	- 	
		Reason		-	
	2) 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_duplicate_unit`$$
CREATE PROCEDURE `insert_duplicate_unit` (
	IN unitnoParam VARCHAR(11)
    , IN oldCustnoParam INT
	, IN newCustnoParam INT
    , OUT isDuplicateUnitAdded  INT
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
        SET isDuplicateUnitAdded = 0;
	END;
	START TRANSACTION;
		/* UNIT TABLE OPERATIONS */
		SET @oldUid = (SELECT uid FROM unit WHERE unitno = unitnoParam AND customerno = oldCustnoParam LIMIT 1);

		INSERT INTO `unit` (`unitno`, `repairtat`, `customerno`, `vehicleid`, `analog1`, `analog2`, `analog3`, `analog4`, `digitalio`, `extra_digital`, `digitalioupdated`, `door_digitalioupdated`, `extra_digitalioupdated`, `extra2_digitalioupdated`, `command`, `setcom`, `commandkey`, `commandkeyval`, `acsensor`, `is_ac_opp`, `gensetsensor`, `is_genset_opp`, `transmitterno`, `doorsensor`, `is_door_opp`, `fuelsensor`, `tempsen1`, `tempsen2`, `tempsen3`, `tempsen4`, `n1`, `n2`, `n3`, `n4`, `humidity`, `is_panic`, `is_buzzer`, `is_mobiliser`, `is_twowaycom`, `is_portable`, `mobiliser_flag`, `is_freeze`, `unitprice`, `userid`, `msgkey`, `trans_statusid`, `type_value`, `temp1_intv`, `temp2_intv`, `temp3_intv`, `temp4_intv`, `teamid`, `remark`, `alterremark`, `issue_type`, `comments`, `comments_repair`, `issue`, `onlease`, `isRequiredThirdParty`) 
		SELECT `unitno`, `repairtat`, `customerno`, `vehicleid`, `analog1`, `analog2`, `analog3`, `analog4`, `digitalio`, `extra_digital`, `digitalioupdated`, `door_digitalioupdated`, `extra_digitalioupdated`, `extra2_digitalioupdated`, `command`, `setcom`, `commandkey`, `commandkeyval`, `acsensor`, `is_ac_opp`, `gensetsensor`, `is_genset_opp`, `transmitterno`, `doorsensor`, `is_door_opp`, `fuelsensor`, `tempsen1`, `tempsen2`, `tempsen3`, `tempsen4`, `n1`, `n2`, `n3`, `n4`, `humidity`, `is_panic`, `is_buzzer`, `is_mobiliser`, `is_twowaycom`, `is_portable`, `mobiliser_flag`, `is_freeze`, `unitprice`, `userid`, `msgkey`, `trans_statusid`, `type_value`, `temp1_intv`, `temp2_intv`, `temp3_intv`, `temp4_intv`, `teamid`, `remark`, `alterremark`, `issue_type`, `comments`, `comments_repair`, `issue`, `onlease`, `isRequiredThirdParty`
		FROM 	unit
		WHERE 	uid = @oldUid;

		SET @newUid = LAST_INSERT_ID();

		UPDATE unit		SET customerno = newCustnoParam			WHERE uid = @newUid;
		UPDATE unit		SET vehicleid = 0 						WHERE uid = @newUid;

		/* VEHICLE TABLE OPERATIONS */
		INSERT INTO `vehicle` (`vehicleno`, `extbatt`, `odometer`, `lastupdated`, `curspeed`, `driverid`, `customerno`, `uid`, `isdeleted`, `kind`, `userid`, `groupid`, `branchid`, `overspeed_limit`, `average`, `modelid`, `manufacturing_month`, `manufacturing_year`, `purchase_date`, `start_meter_reading`, `fueltype`, `is_insured`, `status_id`, `temp1_min`, `temp1_max`, `temp1_mute`, `temp2_min`, `temp2_max`, `temp2_mute`, `temp3_min`, `temp3_max`, `temp3_mute`, `temp4_min`, `temp4_max`, `temp4_mute`, `no_of_genset`, `genset1`, `genset2`, `transmitter1`, `transmitter2`, `other_upload1`, `other_upload2`, `other_upload3`, `other_upload4`, `timestamp`, `stoppage_odometer`, `stoppage_transit_time`, `nodata_alert`, `stoppage_flag`, `submission_date`, `registration_date`, `approval_date`, `additional_amount`, `description`, `sms_count`, `sms_lock`, `tel_count`, `tel_lock`, `fuel_balance`, `fuelcapacity`, `max_voltage`, `fuel_min_volt`, `fuel_max_volt`, `notes`, `rto_location`, `serial_number`, `expiry_date`, `owner_name`, `invcustid`, `sequenceno`) 
		SELECT	`vehicleno`, `extbatt`, `odometer`, `lastupdated`, `curspeed`, `driverid`, `customerno`, `uid`, `isdeleted`, `kind`, `userid`, `groupid`, `branchid`, `overspeed_limit`, `average`, `modelid`, `manufacturing_month`, `manufacturing_year`, `purchase_date`, `start_meter_reading`, `fueltype`, `is_insured`, `status_id`, `temp1_min`, `temp1_max`, `temp1_mute`, `temp2_min`, `temp2_max`, `temp2_mute`, `temp3_min`, `temp3_max`, `temp3_mute`, `temp4_min`, `temp4_max`, `temp4_mute`, `no_of_genset`, `genset1`, `genset2`, `transmitter1`, `transmitter2`, `other_upload1`, `other_upload2`, `other_upload3`, `other_upload4`, `timestamp`, `stoppage_odometer`, `stoppage_transit_time`, `nodata_alert`, `stoppage_flag`, `submission_date`, `registration_date`, `approval_date`, `additional_amount`, `description`, `sms_count`, `sms_lock`, `tel_count`, `tel_lock`, `fuel_balance`, `fuelcapacity`, `max_voltage`, `fuel_min_volt`, `fuel_max_volt`, `notes`, `rto_location`, `serial_number`, `expiry_date`, `owner_name`, `invcustid`, `sequenceno`
		FROM 	vehicle
		WHERE 	uid = @oldUid
		AND 	isdeleted = 0;

		SET @newVehid = LAST_INSERT_ID();

		UPDATE vehicle 		SET customerno = newCustnoParam 	WHERE vehicleid = @newVehid;
		UPDATE vehicle 		SET uid = @newUid 					WHERE vehicleid = @newVehid;
		UPDATE vehicle 		SET driverid = 0					WHERE vehicleid = @newVehid;

		UPDATE unit			SET vehicleid = @newVehid 			WHERE uid = @newUid;

		/* DRIVER TABLE OPERATIONS */
		SET @oldVehid = (SELECT vehicleid FROM vehicle WHERE uid = @oldUid);
		INSERT INTO `driver` (`drivername`, `driverlicno`, `driverphone`, `customerno`, `vehicleid`, `isdeleted`, `userid`, `birthdate`, `age`, `bloodgroup`, `licence_validity`, `licence_issue_auth`, `local_address`, `local_contact`, `local_contact_mob`, `emergency_contact1`, `emergency_contact2`, `emergency_contact_no1`, `emergency_contact_no2`, `native_address`, `native_contact`, `native_contact_mob`, `native_emergency_contact1`, `native_emergency_contact2`, `native_emergency_contact_no1`, `native_emergency_contact_no2`, `previous_employer`, `service_period`, `service_contact_person`, `service_contact_no`, `upload`, `username`, `password`, `userkey`, `trip_email`, `trip_phone`, `istripstarted`) 
		SELECT	`drivername`, `driverlicno`, `driverphone`, `customerno`, `vehicleid`, `isdeleted`, `userid`, `birthdate`, `age`, `bloodgroup`, `licence_validity`, `licence_issue_auth`, `local_address`, `local_contact`, `local_contact_mob`, `emergency_contact1`, `emergency_contact2`, `emergency_contact_no1`, `emergency_contact_no2`, `native_address`, `native_contact`, `native_contact_mob`, `native_emergency_contact1`, `native_emergency_contact2`, `native_emergency_contact_no1`, `native_emergency_contact_no2`, `previous_employer`, `service_period`, `service_contact_person`, `service_contact_no`, `upload`, `username`, `password`, `userkey`, `trip_email`, `trip_phone`, `istripstarted`
		FROM 	driver
		WHERE 	vehicleid = @oldVehid
		AND		isdeleted = 0;

		SET @newDriverId = LAST_INSERT_ID();

		UPDATE driver 		SET customerno = newCustnoParam 	WHERE driverid = @newDriverId;
		UPDATE driver 		SET vehicleid = @newVehid 			WHERE driverid = @newDriverId;
		UPDATE vehicle 		SET driverid = @newDriverId			WHERE vehicleid = @newVehid;

		/* IGNITION ALERT TABLE OPERATIONS */
		INSERT INTO `ignitionalert` (`vehicleid`, `last_status`, `last_check`, `count`, `idleignon_count`, `running_count`, `status`, `customerno`, `ignchgtime`, `ignontime`, `prev_odometer_reading`, `prev_odometer_time`) 
		SELECT `vehicleid`, `last_status`, `last_check`, `count`, `idleignon_count`, `running_count`, `status`, `customerno`, `ignchgtime`, `ignontime`, `prev_odometer_reading`, `prev_odometer_time`
		FROM	ignitionalert
		WHERE 	vehicleid = @oldVehid;

		SET @newIgalertId = LAST_INSERT_ID();

		UPDATE ignitionalert 	SET customerno = newCustnoParam WHERE igalertid = @newIgalertId;
		UPDATE ignitionalert	SET vehicleid = @newVehid 		WHERE igalertid = @newIgalertId;

		/* EVENT ALERT TABLE OPERATIONS */
		INSERT INTO `eventalerts` (`vehicleid`, `overspeed`, `tamper`, `powercut`, `temp`, `temp2`, `temp3`, `temp4`, `ac`, `customerno`, `ac_count`, `ac_time`, `door`, `door_time`) 
		SELECT `vehicleid`, `overspeed`, `tamper`, `powercut`, `temp`, `temp2`, `temp3`, `temp4`, `ac`, `customerno`, `ac_count`, `ac_time`, `door`, `door_time`
		FROM	eventalerts
		WHERE 	vehicleid = @oldVehid;

		SET @newEaId = LAST_INSERT_ID();

		UPDATE eventalerts 		SET customerno = newCustnoParam WHERE eaid = @newEaId;
		UPDATE eventalerts 		SET vehicleid = @newVehid 		WHERE eaid = @newEaId;

		/* DEVICES TABLE OPERATIONS */
		INSERT INTO `devices` (`customerno`, `devicekey`, `devicelat`, `devicelong`, `baselat`, `baselng`, `installlat`, `installlng`, `lastupdated`, `registeredon`, `altitude`, `directionchange`, `inbatt`, `hwv`, `swv`, `msgid`, `uid`, `status`, `ignition`, `powercut`, `tamper`, `gpsfixed`, `online/offline`, `gsmstrength`, `gsmregister`, `gprsregister`, `aci_status`, `satv`, `device_invoiceno`, `inv_generatedate`, `installdate`, `expirydate`, `invoiceno`, `po_no`, `po_date`, `warrantyexpiry`, `simcardid`, `inv_device_priority`, `inv_deferdate`) 
		SELECT 	`customerno`, `devicekey`, `devicelat`, `devicelong`, `baselat`, `baselng`, `installlat`, `installlng`, `lastupdated`, `registeredon`, `altitude`, `directionchange`, `inbatt`, `hwv`, `swv`, `msgid`, `uid`, `status`, `ignition`, `powercut`, `tamper`, `gpsfixed`, `online/offline`, `gsmstrength`, `gsmregister`, `gprsregister`, `aci_status`, `satv`, `device_invoiceno`, `inv_generatedate`, `installdate`, `expirydate`, `invoiceno`, `po_no`, `po_date`, `warrantyexpiry`, `simcardid`, `inv_device_priority`, `inv_deferdate`
		FROM	devices
		WHERE 	uid = @oldUid;

		SET @newDeviceId = LAST_INSERT_ID();

		UPDATE devices 			SET customerno = newCustnoParam WHERE deviceid = @newDeviceId;
		UPDATE devices 			SET uid = @newUid 				WHERE deviceid = @newDeviceId;
		UPDATE devices			SET	simcardid = 0				WHERE deviceid = @newDeviceId;

		/* SIMCARD TABLE OPERATIONS */
		SET @oldsimcardId = (SELECT simcardid FROM devices WHERE uid = @oldUid);

		INSERT INTO `simcard` (`simcardno`, `vendorid`, `trans_statusid`, `customerno`, `teamid`, `comments`) 
		SELECT 	`simcardno`, `vendorid`, `trans_statusid`, `customerno`, `teamid`, `comments`
		FROM	simcard
		WHERE	id = @oldsimcardId;

		SET @newSimcardId = LAST_INSERT_ID();

		UPDATE simcard 			SET customerno = newCustnoParam WHERE id = @newSimcardId;
		UPDATE devices			SET	simcardid = @newSimcardId	WHERE deviceid = @newDeviceId;

		/* DAILY REPORT OPERATIONS */
		INSERT INTO `dailyreport` (`customerno`, `vehicleid`, `uid`, `harsh_break`, `sudden_acc`, `towing`, `flag_harsh_break`, `flag_sudden_acc`, `flag_towing`, `first_odometer`, `last_odometer`, `max_odometer`, `average_distance`, `total_tracking_days`, `overspeed`, `topspeed`, `topspeed_lat`, `topspeed_long`, `fenceconflict`, `acusage`, `runningtime`, `idleignitiontime`, `first_lat`, `first_long`, `end_lat`, `end_long`, `last_online_updated`, `offline_data_time`, `topspeed_time`, `night_first_odometer`, `weekend_first_odometer`, `daily_date`) 
		(SELECT 	`customerno`, `vehicleid`, `uid`, `harsh_break`, `sudden_acc`, `towing`, `flag_harsh_break`, `flag_sudden_acc`, `flag_towing`, `first_odometer`, `last_odometer`, `max_odometer`, `average_distance`, `total_tracking_days`, `overspeed`, `topspeed`, `topspeed_lat`, `topspeed_long`, `fenceconflict`, `acusage`, `runningtime`, `idleignitiontime`, `first_lat`, `first_long`, `end_lat`, `end_long`, `last_online_updated`, `offline_data_time`, `topspeed_time`, `night_first_odometer`, `weekend_first_odometer`, `daily_date`
		FROM	dailyreport
		WHERE	uid = @oldUid
		ORDER BY daily_date DESC
		LIMIT 1);


		SET @newDailyreportId = LAST_INSERT_ID();

		UPDATE dailyreport		SET customerno = newCustnoParam WHERE dailyreport_id = @newDailyreportId;
		UPDATE dailyreport		SET	uid = @newUid				WHERE dailyreport_id = @newDailyreportId;
		UPDATE dailyreport		SET	vehicleid = @newVehid		WHERE dailyreport_id = @newDailyreportId;
		
        SET isDuplicateUnitAdded = 1;
    COMMIT;

END$$
DELIMITER ;

/*
    Name			-	insert_groups_for_user
    Description 	-	
    Parameters		-	
    Module			-	Speed
    Sub-Modules 	- 	Maintenance
    Sample Call		-	call insert_groups_for_user(5799, 5, 0, 0, '64', '2016-09-09 13:23:23', @isInserted);
    Created by		-	Mrudang Vora
    Created on		- 	08 Sep, 2016
    Change details 	-	
    1) 	Updated by	- 	
		Updated	on	- 	
		Reason		-	
	2) 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_groups_for_user`$$
CREATE PROCEDURE `insert_groups_for_user`(
    IN useridParam INT
    , IN districtidParam INT
    , IN cityidParam INT
    , IN groupidParam INT
    , IN custnoParam INT
    , IN todaysdate DATETIME
    , OUT isInserted TINYINT(1) 
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error; */
        SET isInserted = 0;
    END;

    IF cityidParam = 0 OR cityidParam = '' THEN
        SET cityidParam = NULL;
    END IF;

    IF groupidParam = 0 OR groupidParam = '' THEN
        SET groupidParam = NULL;
    END IF;

    START TRANSACTION;

        UPDATE 	groupman
        SET 	isdeleted = 1
        WHERE 	userid = useridParam
        AND 	customerno = custnoParam;

        INSERT INTO groupman (groupid
                , userid
                , customerno
                , `timestamp`
                , vehicleid
                , isdeleted)
        SELECT  g.groupid
                , useridParam
                , custnoParam
                , todaysdate
                ,0
                ,0
        FROM    `group` AS g
        INNER JOIN 	city AS c on g.cityid = c.cityid
        INNER JOIN 	district AS d on c.districtid = d.districtid
        WHERE 		d.districtid = districtidParam
        AND 		(cityidParam IS NULL OR c.cityid = cityidParam)
        AND 		(groupidParam IS NULL OR g.groupid = groupidParam)
        AND			d.isdeleted = 0
        AND 		d.customerno = custnoParam
        AND			c.isdeleted = 0
        AND 		c.customerno = custnoParam
        AND			g.isdeleted = 0
        AND 		g.customerno = custnoParam;

        SET isInserted = 1;

    COMMIT;

END$$
DELIMITER ;


/*
    Name		-	insert_ledger
    Description 	-	Insert Ledger Details
    Parameters		-	ledgername VARCHAR(100),address1 VARCHAR(100),address2 VARCHAR(100),address3 VARCHAR(100),email 				VARCHAR(40),phone VARCHAR(20),pan_no VARCHAR(30),cst_no VARCHAR(30),st_no VARCHAR(30),vat_no 					VARCHAR(30),customernoparam INT,createdby INT,createdon DATETIME
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL insert_ledger				             ('test321','XYZ','PQR','MNPO','test@email.com','2222222222','pAN131','cST31','st3214','vat522',2,6,'2016-04-15 15:00:00',6,'2016-04-1515:21:00',@LASTINSERTID);
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	3 May, 2016
        Reason		-	new out param added to get lastinsertid.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger`$$
CREATE PROCEDURE `insert_ledger`( 
    IN ledgername VARCHAR(100)
    , IN address1 VARCHAR(100)
    , IN address2 VARCHAR(100)
    , IN address3 VARCHAR(100)
    , IN email VARCHAR(40)
    , IN phone VARCHAR(20)
    , IN pan_no VARCHAR(30)
    , IN cst_no VARCHAR(30)
    , IN st_no VARCHAR(30)
    , IN vat_no VARCHAR(30)
    , IN createdby INT
    , IN createdon DATETIME
    , IN updatedby INT
    , IN updatedon DATETIME
    , OUT lastinsertid  INT
)
BEGIN

    DECLARE lastinsertid INT;

    INSERT INTO ledger(ledgername
        , address1 
        , address2 
        , address3
        , email 
        , phone 
        , pan_no 
        , cst_no 
        , st_no 
        , vat_no 
        , createdby 
        , createdon 
        , updatedby 
        , updatedon) 
    VALUES(ledgername 
        , address1 
        , address2 
        , address3
        , email 
        , phone 
        , pan_no 
        , cst_no 
        , st_no 
        , vat_no 
        , createdby 
        , createdon 
        , updatedby 
        , updatedon);

    SET lastinsertid = LAST_INSERT_ID(); 
    
END$$
DELIMITER ;



/*
    Name		-	insert_ledger_cust_mapping
    Description 	-	insert Customerno Details to Ledger 
    Parameters		-	ledgeridparam INT,customernoparam INT,ledgernameparam VARCHAR(100),createdby INT,createdon DATETIME,updatedby 					INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL insert_ledger_cust_mapping(1,2,6,'2016-04-16 15:23:00',6,'2016-04-16 15:23:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger_cust_mapping`$$
CREATE PROCEDURE `insert_ledger_cust_mapping`(
    IN ledgeridParam INT(11)
    , IN customernoParam INT(11)
    , IN createdbyParam INT(11)
    , IN createdonParam DATETIME
    , IN updatedbyParam INT(11)
    , IN updatedonParam DATETIME)

BEGIN
    INSERT INTO ledger_cust_mapping(ledgerid
        ,customerno
        , createdby 
        , createdon 
        , updatedby 
        , updatedon) 
    VALUES(ledgeridParam
        ,customernoParam
        , createdbyParam 
        , createdonParam 
        , updatedbyParam 
        , updatedonParam);

END$$
DELIMITER ;


/*
    Name		-	insert_ledger_veh_mapping
    Description 	-	INSERT Ledger with vehicleid 
    Parameters		-	vehicleid INT,ledgerid INT,customernoparam INT,createdby INT ,createdon DATETIME,updatedby INT,updatedon 					DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	insert_ledger_veh_mapping(134,2,4,6,'2016-04-23 15:00:32',6,'2016-04-23 15:00:32');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_ledger_veh_mapping`$$
CREATE PROCEDURE `insert_ledger_veh_mapping`( 
    IN vehicleidParam INT(11)
    , IN ledgeridParam INT(11)
    , IN customernoParam INT(11)
    , IN createdbyParam INT(11)
    , IN createdonParam DATETIME
    , IN updatedbyParam INT(11)
    , IN updatedonParam DATETIME
)
BEGIN

    INSERT INTO ledger_veh_mapping(ledgerid
        ,vehicleid
        ,customerno
        , createdby 
        , createdon 
        , updatedby 
        , updatedon) 
    VALUES(ledgeridParam
        ,vehicleidParam
        ,customernoParam
        , createdbyParam 
        , createdonParam 
        , updatedbyParam 
        , updatedonParam);

END$$
DELIMITER ;





/*
    Name		-	insert_po
    Description 	-	Insert PO Details
    Parameters		-	pono VARCHAR(30),IN podate DATE,IN poamount INT,IN poexpiry DATE,IN description VARCHAR(50),customernoparam INT
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL insert_po('test321','2014-04-10',21,'2016-04-21 ','test description',2,6,'2016-04-15 15:21:00',6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_po`$$
CREATE PROCEDURE `insert_po`( 
    IN ponoParam VARCHAR(30)
    ,IN podateParam DATE
    ,IN poamountParam INT(11)
    ,IN poexpiryParam DATE
    ,IN descriptionParam VARCHAR(50)
    ,IN customernoParam INT(11)
    ,IN createdbyParam INT(11)
    ,IN createdonParam DATETIME
    ,IN updatedbyParam INT(11)
    ,IN updatedonParam DATETIME
)
BEGIN

    INSERT INTO po(pono
        ,podate
        ,poamount
        ,poexpiry
        ,description
        ,customerno
        ,createdby
        ,createdon
        ,updatedby
        ,updatedon) 
    VALUES(ponoParam
        ,podateParam
        ,poamountParam
        ,poexpiryParam
        ,descriptionParam
        ,customernoParam
        ,createdbyParam
        ,createdonParam
        ,updatedbyParam
        ,updatedonParam);

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `pullBucketList`$$
CREATE PROCEDURE `pullBucketList`(
     IN dateParam VARCHAR(100))
BEGIN
        
    SELECT  b.bucketid
            , b.customerno
            , c.customercompany
            , b.priority
            , v.vehicleno
            , v.uid
            , b.location
            , b.purposeid
            , cp.person_name
            , cp.cp_phone1
            , t.`name` as fe_name
            , b.vehicleno as vehno
            , b.vehicleid
            , b.details
            , sp.timeslot
            , b.created_by
            , te.`name` as created_by_name
            , b.apt_date
            , b.fe_id
            , b.status
    FROM    bucket b
    INNER JOIN customer c ON c.customerno = b.customerno
    LEFT OUTER JOIN vehicle v ON v.vehicleid = b.vehicleid
    LEFT OUTER JOIN contactperson_details cp ON cp.cpdetailid = b.coordinatorid
    LEFT OUTER JOIN team t ON t.teamid = b.fe_id
    LEFT OUTER JOIN team te ON te.teamid = b.created_by
    LEFT OUTER JOIN sp_timeslot sp ON sp.tsid = b.timeslotid                                        
    LEFT OUTER JOIN unit u ON u.uid = b.unitid
    LEFT OUTER JOIN simcard s ON s.id = b.simcardid
    WHERE   b.apt_date LIKE dateParam  
    ORDER BY sp.tsid;

END$$
DELIMITER ;  


DELIMITER $$
DROP PROCEDURE IF EXISTS `pullCoordinator`$$
CREATE PROCEDURE `pullCoordinator`(
     IN customernoParam INT(11)
     )
BEGIN

    SELECT  cpdetailid
            , person_name 
    FROM    contactperson_details 
    WHERE   customerno = customernoParam 
    AND     typeid = 3;

END$$
DELIMITER ; 


/*
    Name		-	pullReason
    Description 	-	pull all reason from `nc_reason` table
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
DROP PROCEDURE IF EXISTS `pullReason`$$
CREATE PROCEDURE `pullReason`()
BEGIN

    SELECT  reasonid
            ,reason 
    FROM    nc_reason 

END;
END$$
DELIMITER ;  


/*
    Name		-	pull_team
    Description 	-	Pull all teamid and name.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL pull_team()
    Created by		-	Arvind
    Created on		- 	23 Nov, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/


DELIMITER $$
DROP PROCEDURE IF EXISTS pull_team$$
CREATE PROCEDURE pull_team()

BEGIN

    SELECT  teamid
            ,`name` 
    FROM    team;

END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `push_command_server`$$
CREATE PROCEDURE `push_command_server`(
     IN commentParam VARCHAR(100)
    ,IN commandParam VARCHAR(50)
    ,IN uidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,OUT isexecutedOut TINYINT(2)
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

        START TRANSACTION;	 
        BEGIN

            UPDATE  unit 
            SET      `command`=commandParam
                    ,`setcom`=1
                    , comments = commentParam    
            WHERE   customerno = customernoParam 
            AND     uid = uidParam;

            INSERT INTO  push_command_log (
                    `unitid`
                   ,`customerno` 
                   ,`command`
                   , `comment`
                   , `createdby`
                   , `timestamp`)
            VALUES (uidParam
                    , customernoParam
                    , commandParam
                    , commentParam
                    , lteamidParam
                    , todaysdateParam);

            SET isexecutedOut = 1;

        END;
        COMMIT;

    END;
END$$
DELIMITER ;


/*
    Name		-	register_device
    Description 	-	register a device.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	
    Created by		-	Arvind
    Created on		- 	06 Dec,2016
    Change details 	-	
    1) 	Updated by	- 	Arvind
	Updated	on	- 	09 March,2017
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `register_device`$$
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
    ,IN leaseParam TINYINT(2)
    ,IN eteamidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
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
           /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;   */
            SET isexecutedOut = 0;
	END;
    BEGIN    
        DECLARE cstypeVar INT DEFAULT 13;
        DECLARE warrantyVar DATETIME DEFAULT DATE_ADD(CURRENT_DATE, INTERVAL 365 DAY);
        DECLARE vehicleidVar INT(11);
        DECLARE useridVar INT(11);
        DECLARE panicVar TINYINT(1);
        DECLARE buzzerVar TINYINT(4);
        DECLARE mobiliserVar TINYINT(1);

        SELECT  vehicleid INTO vehicleidVar  
        FROM    vehicle 
        WHERE   uid = unitidParam 
        AND     isdeleted = 0 
        ORDER BY vehicleid DESC 
        LIMIT   1;

        IF utypeParam = 23 THEN
            SET cstypeVar = 24;
        END IF;

        IF utypeParam = 22 THEN
            SET cstypeVar = 25;
        END IF;

        IF(vehiclenoParam = '' OR vehiclenoParam = '0') THEN
            SET vehiclenoParam = NULL;
        END IF;

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

        SELECT  simcardno 
        INTO    simcardnoOut 
        FROM    simcard 
        WHERE   id = simcardidParam
        ORDER BY id DESC 
        LIMIT   1;

        SELECT  userid 
        INTO    useridVar 
        FROM    `user` 
        WHERE   isdeleted=0 
        AND     customerno=customernoParam
        ORDER BY userid DESC 
        LIMIT   1;

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
    --      status : Unsuccessful
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
    --      status : Incomplete
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
                        , `status`)
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
    --      status : Reschedule
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
                        , status)
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
    --      status : Cancel
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



/*
    Name		-	re_install_device
    Description 	-	Reinstall a device.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	
    Created by		-	Arvind
    Created on		- 	06 Dec,2016
    Change details 	-	
    1) 	Updated by	- 	Arvind
	Updated	on	- 	09 March,2017
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `re_install_device`$$
CREATE PROCEDURE `re_install_device`(
    IN todaysdateParam DATETIME
    ,IN unitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newvehiclenoParam VARCHAR(40)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
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
                    ,`driverid`) 
                VALUES  (newvehiclenoParam
                    ,unitidParam
                    ,customernoVar
                    ,driveridVar);

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
                    , `status`)
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
                    , `status`)
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
DELIMITER $$

/*
    Name		-	remove_unit_sim
    Description 	-	remove bad device.
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
DROP PROCEDURE IF EXISTS `remove_unit_sim`$$
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
          /*  ROLLBACK;
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error; */
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


        --  Daily report both unit remove bad
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

        --  Customerno - Make it 1
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


        --  unset lease on old device
            UPDATE  unit 
            SET     onlease=0 
            WHERE   uid = unitidParam;

        --  Populate Vehicles

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
                    , `status`)
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
                    ,`status`)
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



/*
    Name		-	repair
    Description 	-	repair a device.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	
    Created by		-	Arvind
    Created on		- 	24 Feb,2017
    Change details 	-	
    1) 	Updated by	- 	Arvind
	Updated	on	- 	09 March,2017
        Reason		-	
*/


DELIMITER $$
DROP PROCEDURE IF EXISTS `repair`$$
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
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;  */  
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

        --  Send Email

            SELECT      simcardno 
            INTO        simnumberOut 
            FROM        simcard 
            WHERE       id = simcardidParam
            ORDER BY    id DESC
            LIMIT       1;

        --  $team = lteamidParam;
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
     --         incompleteReasonParam
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
                        , `status`)
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
                        , `status`)
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


/*
    Name		-	
    Description 	-	replace unit and sim.
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
DROP PROCEDURE IF EXISTS `replace_both`$$
CREATE PROCEDURE `replace_both`(
     IN todaysdateParam DATETIME
    ,IN customernoParam INT(11)
    ,IN oldvehicleidParam INT(11)         
    ,IN oldunitidParam INT(11)
    ,IN eteamidParam INT(11)
    ,IN newunitidParam INT(11)
    ,IN newsimidParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN statusParam TINYINT(2)
    ,IN unsuccessProblemParam TINYINT(2)
    ,IN incompleteDateParam DATETIME
    ,IN rescheduleDateParam DATETIME
    ,IN bucketidParam INT(11)
    ,IN commentParam VARCHAR(100)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT usernameOut varchar(50)
    ,OUT realnameOut varchar(50)
    ,OUT emailOut varchar(50)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT oldunitOut VARCHAR(16)
    ,OUT oldsimOut VARCHAR(50)
    ,OUT newunitOut VARCHAR(16)
    ,OUT newsimOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT errormsgOut VARCHAR(100) 
    )

BEGIN
    DECLARE newsimcardnoVar VARCHAR(50);
    DECLARE oldsimcardidVar INT(11);
    DECLARE oldvehicleidVar INT(11);
    DECLARE groupidVar INT(11);
    DECLARE oldunitnoVar VARCHAR(11);
    DECLARE oldvehiclenoVar VARCHAR(40);
    DECLARE simcardnumberVar VARCHAR(50);
    DECLARE newunitnoVar VARCHAR(11);
    DECLARE onleaseVar TINYINT(2);   
    DECLARE newdeviceidVar BIGINT(11);
    DECLARE newvehicleidVar INT(11);
    DECLARE vehicleStringVar VARCHAR(20);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error; */
        SET isexecutedOut = 0;
    END;

    SELECT      simcardno 
    INTO        newsimcardnoVar 
    FROM        simcard 
    WHERE       id =newsimidParam
    ORDER BY    id DESC
    LIMIT       1;

    SELECT      devices.simcardid
    INTO        oldsimcardidVar
    FROM        devices 
    WHERE       devices.uid =oldunitidParam
    ORDER BY    deviceid DESC
    LIMIT       1;
    
    SELECT      unitno 
    INTO        newunitnoVar 
    FROM        unit 
    WHERE       uid =newunitidParam
    ORDER BY    uid DESC
    LIMIT       1;

    SELECT      unitno
                ,onlease
    INTO        oldunitnoVar
                ,onleaseVar
    FROM        unit 
    WHERE       uid =  oldunitidParam
    ORDER BY    uid DESC
    LIMIT       1;
    
    SELECT      vehicleid
                ,vehicleno
                , groupid
    INTO        oldvehicleidVar
                ,oldvehiclenoVar
                ,groupidVar
    FROM        vehicle 
    WHERE       vehicleid =  oldvehicleidParam
    AND         isdeleted = 0
    ORDER BY    vehicleid DESC
    LIMIT       1;

    SELECT  simcardno 
    INTO    simcardnumberVar 
    FROM    simcard 
    WHERE   id = oldsimcardidVar
    ORDER BY id DESC
    LIMIT   1;

    SELECT	deviceid
    INTO 	newdeviceidVar
    FROM	devices
    WHERE	uid = newunitidParam
    LIMIT	1;
    
    SELECT 	vehicleid
    INTO	newvehicleidVar
    FROM 	vehicle
    WHERE 	uid = newunitidParam
    ORDER BY    vehicleid DESC
    LIMIT	1;
    
    SELECT 	concat('V',oldunitnoVar)
    INTO	vehicleStringVar;

    IF statusParam = 2 THEN

        START TRANSACTION;
        BEGIN
            IF oldvehicleidParam <> 0 THEN
            --  Remove Old Device    
                UPDATE 	unit 
                SET 	customerno=customernoParam
                        , trans_statusid = 5
                        , teamid=0
                        , vehicleid = oldvehicleidParam
                        ,onlease = onleaseVar
                where 	uid=newunitidParam;
                
                UPDATE  unit 
                SET      customerno=1
                        ,userid=0
                        ,trans_statusid = 20
                        ,teamid=eteamidParam
                        ,comments = commentParam
                        ,onlease = 0
                        ,vehicleid = newvehicleidVar
                WHERE   uid=oldunitidParam;
                
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

                INSERT INTO trans_history_new(`bucketid`
                        ,`oldunitid`
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
                        ,`customerno`)
                    VALUES (bucketidParam
                        ,oldunitidParam
                        ,newunitidParam
                        ,oldvehicleidParam
                        ,oldsimcardidVar
                        ,newsimidParam
                        ,5
                        ,1
                        ,commentParam
                        ,eteamidParam
                        ,lteamidParam
                        ,todaysdateParam
                        ,customernoParam);

            --  Replace daily reprt  
                UPDATE  dailyreport 
                SET     uid = newunitidParam
                        , first_odometer=0
                        , last_odometer=0
                        , max_odometer=0 
                WHERE   vehicleid = oldvehicleidParam 
                AND     customerno=customernoParam;

                UPDATE	devices
                SET 	uid = oldunitidParam
                        ,simcardid = oldsimcardidVar
                        ,customerno = 1
                WHERE	deviceid = newdeviceidVar;

                UPDATE	vehicle
                SET	uid = oldunitidParam
                        ,customerno = 1
                        ,vehicleno = vehicleStringVar
                WHERE	vehicleid = newvehicleidVar;
				
                UPDATE 	bucket 
                SET 	`status` = 2
                        ,`task_completion_timestamp` = todaysdateParam 
                WHERE	bucketid= bucketidParam ;
            
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
        WHERE   teamid = eteamidParam
        ORDER BY teamid DESC
        LIMIT   1;

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
        AND     (c.role = 'Administrator' OR c.role = 'Master')
        ORDER BY c.userid DESC
        LIMIT 1;

        SET vehiclenoOut=oldvehiclenoVar;
        SET oldunitOut=oldunitnoVar;
        SET oldsimOut=simcardnumberVar;
        SET newunitOut=newunitnoVar;
        SET newsimOut=newsimcardnoVar;

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
                    , `remark`
                    ,`teamid`
                    ,`createdby`
                    ,`createdon`
                    ,`customerno`)
            VALUES (bucketidParam
                    ,oldunitidParam
                    ,oldvehicleidVar
                    ,5
                    ,2
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

                UPDATE  unit 
                SET     trans_statusid= 5 
                where   uid = oldunitidParam;

                UPDATE  simcard 
                SET     trans_statusid = 13 
                where   id = oldsimcardidVar;
                
            SET isexecutedOut = 1;
        END;
        COMMIT;
    ELSEIF statusParam = 6 THEN
    
        START TRANSACTION;
        BEGIN
        
            UPDATE  bucket  
            SET     status= statusParam 
                    ,reschedule_date = incompleteDateParam
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
                    , `status`)
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
                    ,oldunitidParam
                    ,oldvehicleidVar
                    ,5
                    ,5
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    , customernoParam);
                    
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
                    , `status`)
            SELECT  rescheduleDateParam
                    ,`customerno`
                    ,`created_by`
                    , `priority`
                    , `vehicleid`
                    , `location`
                    , `timeslotid`
                    , `purposeid`
                    , `details`
                    , `coordinatorid`
                    ,todaysdateParam
                    ,0
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
                    ,oldunitidParam
                    ,oldvehicleidVar
                    ,5
                    ,3
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
                    ,oldunitidParam
                    ,oldvehicleidVar
                    ,5
                    ,4
                    ,commentParam
                    ,eteamidParam
                    ,lteamidParam
                    ,todaysdateParam
                    ,customernoParam);

            UPDATE  unit 
            SET     trans_statusid= 5 
            where   uid = oldunitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 13 
            where   id = oldsimcardidVar;
            
            SET isexecutedOut = 1;
        END;
        COMMIT;
    END IF;

END$$
DELIMITER ;




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
    DECLARE newunitnoVar INT;
    DECLARE oldunitnoVar INT;
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

/*
    Name		-	sim_of_teamid
    Description 	-	details of simcard assigneed to team member.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL sim_of_teamid(5);
    Created by		-	Arvind
    Created on		- 	23 Nov, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS sim_of_teamid$$
CREATE PROCEDURE sim_of_teamid(
	IN teamidparam INT
	)
BEGIN

    SELECT  simcard.id as simid
            ,simcard.simcardno 
    FROM    simcard 
    INNER JOIN trans_status ON trans_status.id = simcard.trans_statusid 
    WHERE   trans_statusid IN (19,21) 
    AND     simcard.teamid = teamidparam;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `suspect_unit`$$
CREATE PROCEDURE `suspect_unit`(
     IN commentParam VARCHAR(100)
    ,IN unitidParam INT(11)
    ,IN simcardidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN aptdateParam DATE
    ,IN conameParam VARCHAR(30)
    ,IN cophoneParam VARCHAR(15)
    ,IN priorityParam INT(4)
    ,IN locationParam VARCHAR(50)
    ,IN timeslotParam INT(4)
    ,IN purposeParam INT(4)
    ,IN detailsParam VARCHAR(100)
    ,IN coordinatorParam INT(11)
    ,IN lteamidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,OUT isexecutedOut TINYINT(2)
    ,OUT vehiclenoOut VARCHAR(40)
    ,OUT unitnoOut VARCHAR(16)
    ,OUT simcardnoOut VARCHAR(50)
    ,OUT usernameOut VARCHAR(50)
    ,OUT realnameOut VARCHAR(50)
    ,OUT emailOut VARCHAR(50)
    ,OUT elixirOut VARCHAR(150)
    ,OUT msgOut VARCHAR(100))
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
    DECLARE simcardnoVar VARCHAR(50);
    DECLARE unitnoVar VARCHAR(11);
    DECLARE vehicleidVar INT(11);
    DECLARE vehiclenoVar VARCHAR(40);
    DECLARE groupidVar INT(11);
    DECLARE concatstrVar VARCHAR(100);

    SELECT  simcardno 
    INTO    simcardnoVar 
    FROM    simcard 
    WHERE   id = simcardidParam
    LIMIT   1;
    
    SELECT  unitno 
    INTO    unitnoVar 
    FROM    unit 
    WHERE   uid = unitidParam
    LIMIT   1;

    SELECT  v.vehicleid 
    INTO    vehicleidVar 
    FROM    vehicle v
    INNER JOIN unit ON unit.uid = v.uid
    WHERE   v.uid = unitidParam
    LIMIT   1;
    
    SELECT  CONCAT('Suspected Unit #', unitnoVar ,' and Suspected Sim #', simcardnoVar)
    INTO    concatstrVar;

    IF vehicleidVar IS NOT NULL AND vehicleidVar > 0 THEN

        START TRANSACTION;	 
        BEGIN

            UPDATE  unit 
            SET     trans_statusid = 6
                    ,comments = commentParam 
            WHERE   uid = unitidParam;

            UPDATE  simcard 
            SET     trans_statusid = 14
                    , comments = commentParam 
            WHERE   id = simcardidParam;
            
            INSERT INTO trans_history (`customerno`
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
                    ,unitidParam
                    ,lteamidParam
                    ,0
                    ,todaysdateParam
                    ,6
                    ,'Suspected'
                    ,simcardnoVar
                    ,''
                    ,''
                    ,commentParam
                    ,vehicleidVar);

            INSERT INTO trans_history (`customerno`
                        ,`simcard_id`
                        ,`teamid`
                        ,`type`
                        ,`trans_time`
                        ,`statusid`
                        ,`transaction`
                        ,`simcardno`
                        ,`invoiceno`
                        ,`expirydate`
                        ,`comments`
                        ,`vehicleid`)
            VALUES (customernoParam
                        ,simcardidParam
                        ,lteamidParam
                        ,1
                        ,todaysdateParam
                        , 14
                        ,'Suspected'
                        ,''
                        ,''
                        ,''
                        ,commentParam
                        ,vehicleidVar);

            INSERT INTO trans_history (`customerno`
                        ,`unitid`
                        ,`teamid`
                        ,`type`
                        ,`trans_time`
                        ,`statusid`
                        ,`transaction`
                        ,`simcardno`
                        ,`invoiceno`
                        ,`expirydate`
                        ,`comments`
                        ,`vehicleid`)
            VALUES (customernoParam
                        ,0
                        ,lteamidParam
                        ,2
                        ,todaysdateParam
                        ,0
                        ,concatstrVar
                        , ''
                        , ''
                        , ''
                        ,commentParam
                        ,vehicleidVar);

            IF conameParam <> '' THEN

                INSERT INTO contactperson_details (`typeid`
                        ,`person_name`
                        ,`cp_phone1`
                        , `customerno`
                        , `insertedby`
                        , `insertedon`)
                VALUES (3
                        ,conameParam
                        ,cophoneParam
                        ,customernoParam
                        ,lteamidParam
                        ,todaysdateParam);

                SELECT  LAST_INSERT_ID() 
                INTO    coordinatorParam;

            END IF;

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
                ,`create_timestamp`
                , status)
            VALUES (aptdateParam
                , customernoParam
                ,lteamidParam
                ,priorityParam
                ,vehicleidVar
                ,locationParam
                ,timeslotParam
                ,purposeParam
                ,detailsParam
                ,coordinatorParam
                ,todaysdateParam
                ,0);

            SET isexecutedOut = 1;
            SET msgOut = 'Suspect Successfully';

        END;
        COMMIT; 

    ELSE
        
        SET isexecutedOut = 0;
        SET msgOut = 'Vehicle not present';

    END IF;
    
    SELECT  vehicleno
            ,groupid 
    INTO    vehiclenoVar
            ,groupidVar 
    FROM    vehicle 
    WHERE   vehicleid = vehicleidVar
    LIMIT   1;

    SELECT  `name` 
    INTO    elixirOut 
    FROM    team 
    WHERE   teamid = lteamidParam
    LIMIT   1;

    SELECT  c.username
            ,c.realname
            ,c.email
    INTO    usernameOut
            ,realnameOut
            ,emailOut
    FROM    `user` c 
    LEFT OUTER JOIN groupman p ON p.groupid =groupidVar 
    LEFT OUTER JOIN groupman ON c.userid <> groupman.userid 
    WHERE   c.customerno = customernoParam 
    AND     c.email <> ''
    AND     c.isdeleted = 0 
    AND     (c.groupid= groupidVar OR c.groupid = 0) 
    AND     (c.`role` = 'Administrator' OR c.role = 'Master')
    GROUP BY c.userid 
    LIMIT   1;

    SET vehiclenoOut = vehiclenoVar;
    SET unitnoOut = unitnoVar;
    SET simcardnoOut = simcardnoVar;

   END;
END$$
DELIMITER ;  






/*
    Name		-	authenticate_for_team_login
    Description 	-	authenticate team login.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	
    Created by		-	Arvind
    Created on		- 	24 Nov, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

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


/*
    Name		-	team_cron_archive_knowledgebase_email
    Description 	-	archive all emails that are send for knowledgebase share 
    Parameters		-	emailidparam INT,cnoparam INT
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_cron_archive_knowledgebase_email(800,260);
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_cron_archive_knowledgebase_email`$$
CREATE PROCEDURE `team_cron_archive_knowledgebase_email`(
    IN emailidparam INT
    ,IN cnoparam INT 
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;
    BEGIN

        INSERT INTO knowledgebase_emaillog_history(kb_emailid
                ,kbsid
                ,kb_to
                ,kb_from
                ,kb_subject
                ,kb_message
                ,islater
                ,laterdatetime
                ,issent
                ,customerno
                ,createdby
                ,createdon
                ,updatedby
                ,updatedon
                ,isdeleted)
        SELECT  ke.kb_emailid
                ,ke.kbsid
                ,ke.kb_to
                ,ke.kb_from
                ,ke.kb_subject
                ,ke.kb_message
                ,ke.islater
                ,ke.laterdatetime
                ,ke.issent
                ,ke.customerno
                ,ke.createdby
                ,ke.createdon
                ,ke.updatedby
                ,ke.updatedon
                ,ke.isdeleted
        FROM    knowledgebase_emaillog ke
        WHERE   ke.kb_emailid = emailidparam
        AND     ke.customerno = cnoparam;

        CALL    team_delete_knowledgebase_emaillog(cnoparam,emailidparam);
    
    END;
    COMMIT;
    
END$$
DELIMITER ;



/*
    Name		-	team_cron_get_knowledgebase_email
    Description 	-	To get all emails to send for knowledgebase share
    Parameters		-	current datetime format:2015-01-19 14:30
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_cron_get_knowledgebase_email(curdatetime);
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_cron_get_knowledgebase_email`$$
CREATE PROCEDURE `team_cron_get_knowledgebase_email`(
    IN curdatetime datetime)
BEGIN

    SELECT  *
    FROM    (SELECT *,
                    CASE 
                    WHEN islater = 1 THEN
                      `laterdatetime`
                    ELSE
                     `createdon` 
                    END
            AS      readtime   
            FROM    `knowledgebase_emaillog`) as ke
    WHERE   DATE_FORMAT(ke.readtime,'%Y-%m-%d %H:%i') = curdatetime 
    AND     ke.isdeleted = 0 
    AND     ke.issent = 0;

END$$
DELIMITER ;


/*
    Name		-	team_cron_getsent_knowledgebase_email
    Description 	-	Get  all sent emails from knowledgebase_emaillog table
    Parameters		-	No
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_cron_getsent_knowledgebase_email();
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_cron_getsent_knowledgebase_email`$$
CREATE PROCEDURE `team_cron_getsent_knowledgebase_email`()
BEGIN

    SELECT  * 
    FROM    knowledgebase_emaillog 
    WHERE   issent = 1;

END$$
DELIMITER ;

/*
    Name		-	team_cron_update_knowledgebase_email
    Description 	-	update all emails send for knowledgebase share to 1 for issent
    Parameters		-	emailidparam INT,issentparam tinyint
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_cron_update_knowledgebase_email(800,1);
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_cron_update_knowledgebase_email`$$
CREATE PROCEDURE `team_cron_update_knowledgebase_email`(
    IN emailidparam INT
    ,IN issentparam tinyint)
BEGIN

    UPDATE  knowledgebase_emaillog 
    SET     issent = issentparam 
    WHERE   kb_emailid = emailidparam;

END$$
DELIMITER ;




/*
    Name		-	team_delete_knowledgebase_emaillog
    Description 	-	Delete all emails that are send from knowledgebase_emaillog table
    Parameters		-	emailidparam INT,custnoparam INT
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL team_delete_knowledgebase_emaillog(260,800);
    Created by		-	Sahil
    Created on		- 	19 Jan, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	19 Jan, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `team_delete_knowledgebase_emaillog`$$
CREATE PROCEDURE `team_delete_knowledgebase_emaillog`(
    IN custnoparam INT
    ,IN emailidparam INT 
)
BEGIN

    DELETE FROM knowledgebase_emaillog 
    WHERE       customerno = custnoparam
    AND         kb_emailid = emailidparam;

END$$
DELIMITER ;



/*
    Name		-	unit_of_teamid
    Description 	-	details of unit assigneed to team member.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL unit_of_teamid(5);
    Created by		-	Arvind
    Created on		- 	23 Nov, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS unit_of_teamid$$
CREATE PROCEDURE unit_of_teamid(
    IN teamidparam INT)
BEGIN

    SELECT  unit.unitno
            ,unit.uid 
    FROM    unit 
    INNER JOIN trans_status ON trans_status.id = unit.trans_statusid 
    WHERE   trans_statusid IN (18,20) 
    AND     unit.teamid = teamidparam;

END$$
DELIMITER ;


/*
    Name		-	unit_of_cust
    Description 	-	details of unit assigneed to team member.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL unit_of_cust(5);
    Created by		-	Arvind
    Created on		- 	23 Nov, 2016
    Change details 	-	
    1) 	Updated by	- 	
	Updated	on	- 	
        Reason		-	
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS unit_sim_veh_of_cust$$
CREATE PROCEDURE unit_sim_veh_of_cust(
	IN customernoParam INT(11)
	)
BEGIN
    SELECT      unit.uid
                ,unit.unitno
                ,simcard.id
                ,simcard.simcardno
                ,vehicle.vehicleid
                ,vehicle.vehicleno 
    FROM        vehicle
    INNER JOIN  unit ON unit.uid=vehicle.uid
    INNER JOIN  devices ON devices.uid=unit.uid
    LEFT OUTER JOIN simcard ON simcard.id=devices.simcardid
    WHERE       vehicle.customerno=customernoParam 
    AND         isdeleted = 0;

END$$
DELIMITER ;




/*
    Name		-	update_ledger
    Description 	-	Update Ledger Details
    Parameters		-	ledgername VARCHAR(100),address1 VARCHAR(100),address2 VARCHAR(100),address3 VARCHAR(100),email 				VARCHAR(40),phone VARCHAR(20),pan_no VARCHAR(30),cst_no VARCHAR(30),st_no VARCHAR(30),vat_no 					VARCHAR(30),customernoparam INT
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL update_ledger('test321','XYZ','PQR','MNPO','test@email.com','2222222222','pAN131','cST31','st3214','vat522',2,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_ledger`$$
CREATE PROCEDURE `update_ledger`( 
	IN ledgeridparam INT
    , IN ledgername VARCHAR(100)
    , IN address1 VARCHAR(100)
    , IN address2 VARCHAR(100)
    , IN address3 VARCHAR(100)
    , IN email VARCHAR(40)
    , IN phone VARCHAR(20)
    , IN pan_no VARCHAR(30)
    , IN cst_no VARCHAR(30)
    , IN st_no VARCHAR(30)
    , IN vat_no VARCHAR(30)
    , IN updatedby INT
    , IN updatedon DATETIME
)
BEGIN

    UPDATE  ledger 
    SET     ledgername = ledgername
            , address1 = address1 
            , address2 = address2
            , address3 = address3
            , email = email
            , phone = phone 
            , pan_no = pan_no
            , cst_no = cst_no
            , st_no = st_no
            , vat_no = vat_no
            , updatedby = updatedby
            , updatedon = updatedon
    WHERE   ledgerid = ledgeridparam 
    AND     isdeleted = 0;

END$$
DELIMITER ;



/*
    Name		-	update_po
    Description 	-	Update PO Details
    Parameters		-	pono VARCHAR(30),IN podate DATE,IN poamount INT,IN poexpiry DATE,IN description VARCHAR(50),customernoparam INT
				updatedby INT,updatedon DATETIME
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL update_po('test','2014-04-10',21,'2016-04-21','test description',2,6,'2016-04-15 15:21:00');
    Created by		-	Sahil
    Created on		- 	16 April, 2016
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	16 April, 2016
        Reason		-	New SP.
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_po`$$
CREATE PROCEDURE `update_po`( 
IN poidparam INT
    ,IN pono VARCHAR(30)
    ,IN podate DATE
    ,IN poamount INT
    ,IN poexpiry DATE
    ,IN description VARCHAR(50)
    ,IN customernoparam INT
    ,IN updatedby INT
    ,IN updatedon DATETIME
)

BEGIN
    UPDATE  po
    SET     pono = pono
            ,podate = podate
            ,poamount = poamount
            ,poexpiry = poexpiry
            ,description = description
            ,updatedby = updatedby
            ,updatedon = updatedon
    WHERE   customerno = customernoparam
    AND     poid = poidparam
    AND     isdeleted = 0;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_detail_consume_yesterday`$$
CREATE PROCEDURE `get_sms_detail_consume_yesterday`(
    IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
        SET customernos = NULL;
    END IF;

    SELECT  * 
    FROM    ((  select      `user`.phone
                            ,comqueue.message
                            ,comhistory.timesent 
                from        comhistory
                INNER JOIN  comqueue on comqueue.cqid = comhistory.comqid
                INNER JOIN  `user` on `user`.userid = comhistory.userid
                WHERE       comhistory.customerno = customernos 
                AND         DATE(comhistory.timesent) = subdate(CURRENT_DATE,1)
             )
             UNION ALL 
             (  SELECT   mobileno
                        ,message
                        ,inserted_datetime 
                from    smslog 
                WHERE   customerno = customernos 
                AND     DATE(inserted_datetime) = subdate(CURRENT_DATE,1)
             )
            ) results
     ORDER BY timesent ASC;
     
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_category`$$
CREATE PROCEDURE `insert_category`(
    IN category VARCHAR(50)
    ,IN teamid INT
    ,IN todaydate DATETIME
    ,OUT categoryid INT
)

BEGIN
    INSERT INTO category(category
        ,created_by
        ,created_on) 
    VALUES(category
        ,teamid
        ,todaydate);

    SET categoryid = LAST_INSERT_ID();

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_category`$$
CREATE PROCEDURE `update_category`(
    IN categoryidParam INT
    ,IN categoryParam VARCHAR(50)
    ,IN teamid INT
    ,IN todaysdate DATETIME
)

BEGIN

    UPDATE  `category`
    SET     `category` = categoryParam
            , `updated_by` = teamid
            , `updated_on` = todaysdate
    WHERE   `categoryid` = categoryidParam
    AND     `isdeleted` = 0;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_category`$$
CREATE PROCEDURE `delete_category`(
    IN categoryidParam INT
    ,IN teamid INT
    ,IN todaysdate DATETIME)

BEGIN

    UPDATE  `category`
    SET     `isdeleted` = 1
            , `updated_by` = teamid
            , `updated_on` = todaysdate
    WHERE   `categoryid` = categoryidParam
    AND     `isdeleted` = 0;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_category`$$
CREATE PROCEDURE `get_category`(
  IN categoryidParam INT
)

BEGIN

    IF(categoryidParam = '' OR categoryidParam = 0) THEN
        SET categoryidParam = categoryidParam = NULL;
    END IF;

    SELECT  categoryid
            ,category
            ,created_by
            ,created_on
            ,updated_by
            ,updated_on
    FROM    category
    WHERE   (categoryid = categoryidParam OR categoryidParam IS NULL)
    AND     isdeleted = 0
    ORDER BY categoryid ASC;


END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_bank_statement`$$
CREATE PROCEDURE `insert_bank_statement`(
    IN transaction_datetime DATETIME
    ,IN details VARCHAR(50)
    , IN remarks VARCHAR(50)
    , IN transaction_type TINYINT
    , IN categoryid INT
    , IN amount DECIMAL(10,2)
    , IN teamid INT
    ,IN todaysdate DATETIME
    ,OUT statementid INT
)

BEGIN
    INSERT INTO bank_statement(transaction_datetime
        ,details
        ,remarks
        ,transaction_type
        ,categoryid
        ,amount
        ,created_by
        ,created_on)
    VALUES(transaction_datetime
        ,details
        ,remarks
        ,transaction_type
        ,categoryid
        ,amount
        ,teamid
        ,todaysdate);

    SET statementid = LAST_INSERT_ID();

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_bank_statement`$$
CREATE PROCEDURE `update_bank_statement`(
    IN statementidParam INT
    , IN transaction_datetimeParam DATETIME
    , IN detailsParam VARCHAR(50)
    , IN remarksParam VARCHAR(50)
    , IN transaction_typeParam TINYINT
    , IN categoryidParam INT
    , IN amountParam DECIMAL(10,2)
    , IN teamid INT
    , IN todaysdate DATETIME
)

BEGIN

    UPDATE  bank_statement
    SET     transaction_datetime = transaction_datetimeParam
            ,details = detailsParam
            ,remarks = remarksParam
            ,transaction_type = transaction_typeParam
            ,categoryid = categoryidParam
            ,amount = amountParam
            ,updated_by = teamid
            ,updated_on= todaysdate
    WHERE   statementid = statementidParam
    AND     isdeleted = 0;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_bank_statement`$$
CREATE PROCEDURE `delete_bank_statement`(
    IN statementidParam INT
    , IN teamid INT
    , IN todaysdate DATETIME
)

BEGIN

    UPDATE  bank_statement
    SET     isdeleted = 1
            ,updated_by = teamid
            ,updated_on= todaysdate
    WHERE   statementid = statementidParam
    AND     isdeleted = 0;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE  IF EXISTS`get_bank_statement`$$
CREATE PROCEDURE `get_bank_statement`(
    IN statementidParam INT
    , IN transaction_datetime_from date
    , IN transaction_datetime_to date
    , IN transaction_typeParam INT
    , IN categoryidParam INT
)

BEGIN

    IF(statementidParam = '' OR statementidParam = 0) THEN
        SET statementidParam =  NULL;
    END IF;

    IF(transaction_typeParam = '' OR transaction_typeParam = 0) THEN
        SET transaction_typeParam  = NULL;
    END IF;

    IF(categoryidParam = '' OR categoryidParam = 0) THEN
        SET categoryidParam =  NULL;
    END IF;

    IF(transaction_datetime_from = '' OR transaction_datetime_from = '0000-00-00') THEN
        SET transaction_datetime_from =  NULL;
    END IF;

    IF(transaction_datetime_to = '' OR transaction_datetime_to = '0000-00-00') THEN
        SET transaction_datetime_to =  NULL;
    END IF;

    SELECT  statementid
            ,transaction_datetime
            ,details
            ,remarks
            ,transaction_type
            ,bank_statement.categoryid
            ,category
            ,amount
            ,enteredInTally
            ,bank_statement.created_by
            ,bank_statement.created_on
            ,bank_statement.updated_by
            ,bank_statement.updated_on
    FROM    bank_statement
    INNER JOIN category on category.categoryid = bank_statement.categoryid
    WHERE   (statementid = statementidParam OR statementidParam IS NULL)
    AND     (transaction_type = transaction_typeParam OR transaction_typeParam IS NULL)
    AND     (bank_statement.categoryid = categoryidParam OR categoryidParam IS NULL)
    AND     (DATE(transaction_datetime) BETWEEN transaction_datetime_from AND transaction_datetime_to OR transaction_datetime_from IS NULL AND transaction_datetime_to IS NULL )
    AND     bank_statement.isdeleted = 0
    AND     category.isdeleted = 0
    ORDER BY statementid DESC;


END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `add_bank_statement_to_tally`$$
CREATE PROCEDURE `add_bank_statement_to_tally`(
    IN statementidParam INT
    , IN teamid INT
    , IN todaysdate DATETIME
)

BEGIN

    UPDATE  bank_statement
    SET     enteredInTally = 1
            ,updated_by = teamid
            ,updated_on= todaysdate
    WHERE   statementid = statementidParam
    AND     isdeleted = 0;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS new_install_request$$
CREATE PROCEDURE new_install_request(
        IN todaysdateParam DATETIME
        ,IN aptDateParam DATE
        ,IN priorityParam INT(4)
        ,IN locationParam VARCHAR(50)
        ,IN timeslotParam INT(4)
        ,IN detailsParam VARCHAR(100)
        ,IN coordinatorParam INT(11)
        ,IN conameParam VARCHAR(30)
        ,IN cophoneParam VARCHAR(15)
        ,IN installCountParam TINYINT(2)
        ,IN customernoParam INT(11)
        ,IN lteamidParam INT(11)
        ,OUT isexecutedOut TINYINT(2))
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

        DECLARE counterVar INT UNSIGNED DEFAULT 0;
        
        START TRANSACTION;	 
        BEGIN

            IF conameParam <> '' AND conameParam IS NOT NULL THEN

                INSERT INTO contactperson_details (`typeid`
                        ,`person_name`
                        ,`cp_phone1`
                        , `customerno`
                        , `insertedby`
                        , `insertedon`)
                VALUES (3
                        ,conameParam
                        ,cophoneParam
                        ,customernoParam
                        ,lteamidParam
                        ,todaysdateParam);

                SELECT  LAST_INSERT_ID() 
                INTO    coordinatorParam;

            END IF;

            WHILE counterVar < installCountParam DO

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
                    , status)
                VALUES (aptDateParam
                    ,customernoParam
                    ,lteamidParam
                    ,priorityParam
                    ,0
                    ,locationParam
                    ,timeslotParam
                    ,1
                    ,detailsParam
                    ,coordinatorParam
                    ,todaysdateParam
                    ,0);

                set counterVar = counterVar + 1;

            END WHILE;

            SET isexecutedOut = 1;
        
        END;
        COMMIT;
END;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS addTicket$$
CREATE PROCEDURE addTicket(
    IN titleParam VARCHAR(250)
    ,IN customernoParam INT(11)
    ,IN descParam VARCHAR(255)
    ,IN tickettypeParam INT(11)
    ,IN allottoParam INT(11)
    ,IN raiseondateParam DATETIME
    ,IN expecteddateParam DATE
    ,IN mailStatusParam TINYINT(2)
    ,IN ticketmailidParam VARCHAR(255)
    ,IN ccemailidParam VARCHAR(255)
    ,IN priorityParam INT(11)
    ,IN todaysdateParam DATETIME
    ,IN createdbyParam INT(11)
    ,IN lteamidParam INT(11)
    ,OUT isexecutedOut TINYINT(2)
    ,OUT ticketidOut INT(11)
    ,OUT tickettypenameOut VARCHAR(100)
    ,OUT prioritynameOut VARCHAR(100)
    ,OUT allottoemailOut VARCHAR(50)
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

        IF tickettypeParam <> '' THEN

            SELECT  tickettype 
            INTO    tickettypenameOut
            FROM    sp_tickettype 
            WHERE   typeid = tickettypeParam 
            AND     isdeleted = 0 ;

        END IF;

        IF priorityParam <> '' THEN

            SELECT  priority 
            INTO    prioritynameOut
            FROM    sp_priority 
            WHERE   prid = priorityParam 
            AND     isdeleted = 0 ;

        END IF;

        SELECT  email 
        INTO    allottoemailOut
        FROM    team 
        WHERE   teamid = allottoParam;

        START TRANSACTION;	 
        BEGIN

            INSERT INTO `sp_ticket`(`title`
                    ,`ticket_type`
                    ,`customerid` 
                    ,`eclosedate`
                    ,`send_mail_status`
                    ,`send_mail_to`
                    ,`send_mail_cc`
                    ,`priority`
                    ,`raised_on_date`
                    ,`create_on_date`
                    ,`create_by`)
            VALUES (titleParam
                    ,tickettypeParam
                    ,customernoParam
                    ,expecteddateParam
                    ,mailStatusParam
                    ,ticketmailidParam
                    ,ccemailidParam
                    ,priorityParam
                    ,raiseondateParam
                    ,todaysdateParam
                    ,createdbyParam);

            SELECT  LAST_INSERT_ID() 
            INTO    ticketidOut;

            INSERT INTO `sp_ticket_details`(`ticketid`
                ,`description`
                ,`allot_from`
                ,`allot_to`
                ,`status`
                ,`create_by`
                ,`create_on_time`
                ,`send_mail_status`)
            VALUES (ticketidOut
                ,descParam
                ,lteamidParam
                ,allottoParam
                ,0
                ,createdbyParam
                ,todaysdateParam
                ,mailStatusParam);

            SET isexecutedOut = 1;
        
        END;
        COMMIT; 
    END;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS editTicket$$
CREATE PROCEDURE editTicket(
    IN ticketidParam INT(11)
    ,IN customernoParam INT(11)
    ,IN ticketallotParam INT(11)
    ,IN ticketdescParam VARCHAR(255)
    ,IN ticketstatusParam TINYINT(1)
    ,IN createdbyParam INT(11)
    ,IN expecteddateParam DATE
    ,IN tickettypeParam INT(11)
    ,IN sendemailstatusParam TINYINT(1)
    ,IN toemailidParam VARCHAR(255)
    ,IN ccemailidParam VARCHAR(255)
    ,IN priorityidParam INT(11)
    ,IN todaysdateParam DATETIME
    ,IN noteParam VARCHAR(255)
    ,IN createdtypeParam TINYINT(1)
    ,OUT isexecutedOut TINYINT(1)
    ,OUT mailsendtoOut VARCHAR(255)
    ,OUT createbynameOut VARCHAR(150)
    ,OUT allottonameOut VARCHAR(150)
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

        SELECT  `email` 
                ,`name`
        INTO    mailsendtoOut
                ,createbynameOut
        FROM    `team` 
        WHERE   teamid = createdbyParam;

        SELECT `name`
        INTO    allottonameOut
        FROM    `team`
        WHERE   teamid = ticketallotParam;

        SET isexecutedOut = 0;

        START TRANSACTION;
        BEGIN

            INSERT INTO `sp_note`(`ticketid` ,
                        `note`,
                        `create_by`,
                        `sendemailto`,
                        `create_on_date`)
            VALUES (ticketidParam
                        ,noteParam
                        ,createdbyParam
                        ,mailsendtoOut
                        ,todaysdateParam);

            if createdtypeParam = 0 THEN

                INSERT INTO `sp_ticket_details`(`ticketid`,
                        `description`,
                        `allot_from`,
                        `allot_to`,
                        `status`,
                        `create_by`,
                        `create_on_time`,
                        `eclosedate`)
                VALUES (ticketidParam
                        ,ticketdescParam
                        ,createdbyParam
                        ,ticketallotParam
                        ,ticketstatusParam
                        ,createdbyParam
                        ,todaysdateParam
                        ,expecteddateParam);

            ELSE

                INSERT INTO `sp_ticket_details`(
                        `ticketid` ,
                        `description`,
                        `allot_from`,
                        `allot_to`,
                        `status`,
                        `create_by`,
                        `create_on_time`,
                        `eclosedate`,
                        `created_type`)
                VALUES (ticketidParam
                        ,ticketdescParam
                        ,createdbyParam
                        ,ticketallotParam
                        ,ticketstatusParam
                        ,createdbyParam
                        ,todaysdateParam
                        ,expecteddateParam
                        ,1);
            END IF; 

            SET isexecutedOut = 1;
        
        END;
        COMMIT; 

    END;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullemail$$
CREATE PROCEDURE pullemail(
    IN searchstringParam VARCHAR(50)
    ,IN customernoParam INT(11)
)

BEGIN

    SELECT  `eid`
            ,`email_id` 
    FROM    `report_email_list`
    WHERE   `customerno` IN (customernoParam,0) 
    AND     `email_id` LIKE searchstringParam;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullMyTicket$$
CREATE PROCEDURE pullMyTicket(
    IN teamidParam INT(11)
)

BEGIN

    select  *
    from    (select (CASE   WHEN stde.status=1 THEN 'Inprogress' 
                            WHEN stde.status= 2 THEN 'Closed' 
                            WHEN stde.status= 3 THEN 'Pipeline' 
                            WHEN stde.status= 4 THEN 'On Hold' 
                            WHEN stde.status= 5 THEN 'Waiting for Client' 
                            WHEN stde.status= 6 THEN 'Resolved' 
                            ELSE 'Open' END)as ticketstatus
                    , stde.uid
                    ,st.ticketid
                    , st.title
                    ,st.ticket_type
                    ,sttype.tickettype
                    ,st.sub_ticket_issue
                    ,st.customerid
                    ,st.eclosedate
                    , st.priority
                    ,sp.priority as prname
                    ,st.create_on_date
                    , st.create_by
                    , stde.status
                    ,stde.allot_to 
                    ,stde.description
            from    sp_ticket_details stde 
            left join   sp_ticket as st on st.ticketid = stde.ticketid 
            left join   sp_tickettype as sttype on sttype.typeid = st.ticket_type 
            left join   sp_priority as sp on sp.prid = st.priority   
            order by    stde.uid desc ) as main 
    group by    main.ticketid 
    having      main.allot_to = teamidParam 
    AND         main.status IN (0,1,3) 
    order by    main.eclosedate asc, main.priority asc, main.ticketid asc;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullTicketPriority$$
CREATE PROCEDURE pullTicketPriority()

BEGIN

    SELECT  prid
            ,priority 
    FROM    sp_priority
    WHERE   isdeleted = 0;

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullTicketStatus$$
CREATE PROCEDURE pullTicketStatus()

BEGIN

    SELECT  id
            ,status 
    FROM    ticket_status
    WHERE   isdeleted = 0 AND id <> '7';

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS pullTicketType$$
CREATE PROCEDURE pullTicketType()

BEGIN

    SELECT  typeid
            ,tickettype 
    FROM    sp_tickettype
    WHERE   isdeleted = 0;

END$$
DELIMITER ;
