INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'596', '2018-03-16 16:20:00', 'Yash Kanakia', 'Customer Sanctity and Troubleshooting', '0'
);


USE `speed`;
DROP procedure IF EXISTS `get_ledger_cust_mapping`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_ledger_cust_mapping`( 
    IN ledgeridparam INT
    ,IN customernoparam INT
    , IN ledgernameparam VARCHAR(100)
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
    
SELECT 	l.ledgerid
		,l.ledgername
        ,l.address1
        ,l.address2
        ,l.address3
        ,l.email
        ,l.phone
        ,l.pan_no
        ,l.cst_no
        ,l.gst_no
        ,l.st_no
        ,l.vat_no
        ,lc.customerno
FROM ledger_cust_mapping AS lc
INNER JOIN ledger AS l ON l.ledgerid = lc.ledgerid
WHERE (lc.ledgerid  = ledgeridparam OR ledgeridparam IS NULL)
AND (lc.customerno  = customernoparam OR customernoparam IS NULL)
AND (l.ledgername LIKE CONCAT('%', ledgernameparam, '%') OR ledgernameparam IS NULL)
AND lc.isdeleted = 0
;     
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `get_customer_device_info`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_customer_device_info`(
IN customernoParam INT)
BEGIN
DECLARE timediffVar VARCHAR(50);

SELECT t.timediff 
INTO timediffVar
FROM customer c
INNER JOIN timezone t on t.tid = c.timezone
WHERE c.customerno = customernoParam;

SET @serverTime = now();
SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');


SELECT count(d1.deviceid) as activeCount, count(d2.deviceid) as inactiveCount,
count(d3.deviceid) as expiredCount,count(d4.deviceid) as expiredCount2
FROM devices d
INNER JOIN unit ON unit.uid = d.uid AND unit.trans_statusid NOT IN(10,22)
LEFT OUTER JOIN simcard ON simcard.id = d.simcardid
LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid AND vehicle.isdeleted=0
INNER JOIN trans_status ON trans_status.id = unit.trans_statusid
LEFT OUTER JOIN devices d1 ON d1.deviceid = d.deviceid AND d1.lastupdated>=(@istDateTime +INTERVAL timediffVar SECOND - INTERVAL 1 HOUR)
LEFT OUTER JOIN devices d2 ON d2.deviceid = d.deviceid AND d2.lastupdated<( @istDateTime +INTERVAL timediffVar SECOND - INTERVAL 1 HOUR)
LEFT OUTER JOIN devices d3 ON d3.deviceid = d.deviceid AND d3.end_date<(CURDATE())
LEFT OUTER JOIN devices d4 ON d4.deviceid = d.deviceid AND d4.end_date<=(DATE_ADD(CURDATE(), INTERVAL 15 DAY ))
WHERE unit.customerno =customernoParam;
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `get_all_units_customer`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `get_all_units_customer`(
IN customernoParam INT
)
BEGIN
SELECT unitno from unit where customerno = customernoParam;
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `get_unit_details`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `get_unit_details`(
IN customernoParam INT,
IN unitnoParam VARCHAR(16))
BEGIN

DECLARE timediffVar VARCHAR(50);
DECLARE lastupdatedVar,gpsActiveVar datetime;
DECLARE data_transimission_delay VARCHAR(5);
DECLARE powercutVar,tamperedVar tinyint;
DECLARE devicelatVar,devicelongVar DECIMAL(9,6);

SELECT t.timediff 
INTO timediffVar
FROM customer c
INNER JOIN timezone t on c.timezone = t.tid
WHERE c.customerno = customernoParam;

SET @serverTime = now();
SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');

SELECT (d.lastupdated)+ INTERVAL timediffVar SECOND,d1.lastupdated,d.powercut,d.tamper,d.devicelat,d.devicelong
INTO lastupdatedVar,gpsActiveVar,powercutVar,tamperedVar,devicelatVar,devicelongVar
FROM devices d
INNER JOIN unit ON unit.uid = d.uid AND unit.trans_statusid NOT IN(10,22)
LEFT OUTER JOIN simcard ON simcard.id = d.simcardid
LEFT OUTER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid AND vehicle.isdeleted=0
INNER JOIN trans_status ON trans_status.id = unit.trans_statusid
LEFT OUTER JOIN devices d1 ON d1.deviceid = d.deviceid AND d1.lastupdated>=(@istDateTime +INTERVAL timediffVar SECOND - INTERVAL 1 HOUR)
WHERE unit.customerno =customernoParam AND unit.unitno=unitnoParam;

IF(lastupdatedVar <= @istDateTime - INTERVAL 5 MINUTE)
THEN
SET data_transimission_delay = 'yes';
ELSE
SET data_transimission_delay ='no';
END IF;
SELECT data_transimission_delay,gpsActiveVar,powercutVar,tamperedVar,devicelatVar,devicelongVar;
END$$

DELIMITER ;

