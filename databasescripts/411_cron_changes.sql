INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'411', '2016-09-13 15:12:01', 'Arvind Thakur', 'Cron chnages SP', '0');

/*
    Name		-	get_all_customer  
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_all_customer`$$
CREATE PROCEDURE `get_all_customer`()
BEGIN
    
SELECT     c.customerno
        ,c.customername
        ,c.customercompany
        ,c.customerTypeId
        ,c.smsleft
FROM customer AS c
WHERE c.customercompany <> 'Elixia Tech' AND c.use_tracking=1 AND c.renewal NOT IN (-1,-2)
ORDER BY c.customerno ASC
; 
END$$
DELIMITER ;


/*
    Name		-	get_customer_not_allotted_crm
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_customer_not_allot_crm`$$
CREATE PROCEDURE `get_customer_not_allot_crm`()
BEGIN
   
SELECT c.customerno
    ,c.customername 
    ,c.customercompany
    FROM `customer` as c
WHERE c.use_trace=0 AND (c.rel_manager IS NULL OR c.rel_manager = '' OR c.rel_manager=0) 
;     
END$$
DELIMITER ;


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
SELECT cp.person_name
        ,cp.cp_email1
        ,cp.cp_phone1
    FROM contactperson_details as cp
LEFT JOIN customer on customer.customerno=cp.customerno
WHERE customer.customerno=customernos AND cp.typeid=2 AND cp.isdeleted=0 AND (cp.person_name='' OR cp.cp_email1='' OR cp.cp_phone1='') 

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
SELECT cp.person_name
        ,cp.cp_email1
        ,cp.cp_phone1
    FROM contactperson_details as cp
LEFT JOIN customer on customer.customerno=cp.customerno
WHERE customer.customerno=customernos AND cp.typeid=3 AND cp.isdeleted=0 AND (cp.person_name='' OR cp.cp_email1='' OR cp.cp_phone1='') 

ORDER BY cp.typeid
;     
END$$
DELIMITER ;

/*
    Name		-	get_all_vehicleid_for_customer	
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
SELECT v.vehicleid
    ,v.vehicleno
    FROM vehicle as v
WHERE v.customerno=customernos AND v.isdeleted=0
ORDER BY v.vehicleid
;     
END$$
DELIMITER ;

/*
    Name		-	get_expired_devices
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
SELECT unit.unitno 
    FROM vehicle 
    INNER JOIN devices ON devices.uid = vehicle.uid 
    INNER JOIN unit ON devices.uid = unit.uid 
    LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
    WHERE vehicle.isdeleted= 0 AND devices.expirydate < today AND unit.customerno NOT IN (-1,1) AND unit.customerno=customernos AND devices.expirydate !='1970-01-01' AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(22,23,24,10)
;     
END$$
DELIMITER ;

/*
    Name		-	get_all_vehicleid_for_customer
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_ledger_for_vehicle_id`$$
CREATE PROCEDURE `get_ledger_for_vehicle_id`(
        IN vehicleids INT
)
BEGIN
    IF(vehicleids = '' OR vehicleids = '0') THEN
		SET vehicleids = NULL;
	END IF;

SELECT l.ledgerid  
    FROM `ledger_veh_mapping` as l
    WHERE l.vehicleid=vehicleids 
    AND l.isdeleted=0
;     
END$$
DELIMITER ;

/*
    Name		-	get_ledger_map_cust	
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
SELECT l.ledgerid 
    FROM `ledger_cust_mapping` as l 
    WHERE l.customerno=customernos AND `isdeleted` = 0
;     
END$$
DELIMITER ;

/*
    Name		-	get_low_sms_left_cust
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_low_sms_left_cust`$$
CREATE PROCEDURE `get_low_sms_left_cust`()
BEGIN
    
SELECT 	c.customerno
        ,c.customercompany
        ,c.smsleft
    FROM customer AS c
    WHERE c.customercompany <> 'Elixia Tech' AND c.use_trace = 0 AND c.smsleft < 50
    ORDER BY c.customerno ASC
;     
END$$
DELIMITER ;

/*
    Name		get_pending_invoices	
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

SELECT count(d.device_invoiceno) AS pending_invoices
    FROM `devices` as d
    WHERE d.customerno=customernos AND d.device_invoiceno=''
;     
END$$
DELIMITER ;

/*
    Name		-	get_pending_renewal
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_pending_renewal`$$
CREATE PROCEDURE `get_pending_renewal`(
        IN customernos INT,
        IN startdate date,
        IN enddate date
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;

SELECT vehicle.vehicleno FROM vehicle 
INNER JOIN devices ON devices.uid = vehicle.uid 
INNER JOIN driver ON driver.driverid = vehicle.driverid 
INNER JOIN unit ON devices.uid = unit.uid 
LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
WHERE vehicle.isdeleted= 0 AND (devices.expirydate BETWEEN startdate AND enddate) AND unit.customerno NOT IN (-1,1) AND unit.customerno=customernos AND devices.expirydate !='1970-01-01' AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(23,24,10)
ORDER BY vehicle.vehicleno
;     
END$$
DELIMITER ;



/*
    Name		-	get_sms_consume_frm_comq	
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_comq`$$
CREATE PROCEDURE `get_sms_consume_frm_comq`(
    IN customernos INT,
    IN yesterday DATE
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;
    IF(yesterday = '' OR yesterday = '0') THEN
            SET yesterday = NULL;
    END IF;
SELECT COUNT(*) FROM `comqueue` as cq
    WHERE cq.customerno=customernos AND DATE(cq.timeadded)=yesterday
;
END$$
DELIMITER ;



/*
    Name		-	get_sms_consume_frm_smslog
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sms_consume_frm_smslog`$$
CREATE PROCEDURE `get_sms_consume_frm_smslog`(
    IN customernos INT,
    IN yesterday DATE
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;
    IF(yesterday = '' OR yesterday = '0') THEN
            SET yesterday = NULL;
    END IF;
SELECT COUNT(*) FROM `smslog` as sm 
    WHERE sm.customerno=customernos AND DATE(sm.inserted_datetime)=yesterday
;
END$$
DELIMITER ;


/*
    Name		-	get_will_expire_devices	
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_will_expire_devices`$$
CREATE PROCEDURE `get_will_expire_devices`(
        IN customernos INT,
        IN today date,
        IN enddate date
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
SELECT unit.unitno FROM vehicle 
           INNER JOIN devices ON devices.uid = vehicle.uid INNER JOIN driver ON driver.driverid = vehicle.driverid 
           INNER JOIN unit ON devices.uid = unit.uid LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
           WHERE vehicle.isdeleted= 0 AND (devices.expirydate BETWEEN today AND enddate) AND unit.customerno=customernos AND unit.customerno NOT IN (-1,1) AND devices.expirydate !='1970-01-01' AND devices.expirydate!='0000-00-00' AND unit.trans_statusid NOT IN(23,24,10)
;  
END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 411;
