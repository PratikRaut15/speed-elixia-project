INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'692', '2018-03-20 12:30:00', 'Yash Kanakia','Device Status', '0');


DELIMITER $$
DROP procedure IF EXISTS `get_all_customer_device_info`$$
CREATE PROCEDURE `get_all_customer_device_info`(
IN teamIdParam INT)
	BEGIN

	DECLARE relManParam INT;

	SET relManParam = NULL;

	IF (teamIdParam <> 0 OR teamIdParam <> '') THEN
		SELECT rid 
		INTO relManParam
		FROM team
	    WHERE teamid = teamIdParam;
	ELSE
	SET relManParam = NULL;
	END IF;


	SELECT 
	    COUNT(d.deviceid) AS total,
	    COUNT(d1.deviceid) AS activeCount,
	    COUNT(d3.deviceid) AS expiredCount,
	    COUNT(d4.deviceid) AS expiredCount2,
	    c.customerno,
	    c.customercompany,
	    t.name as crm_name
	FROM
	    devices d
	        INNER JOIN
	    unit ON unit.uid = d.uid
	      /*   AND unit.trans_statusid NOT IN (10 , 22)
	       LEFT OUTER JOIN
	    simcard ON simcard.id = d.simcardid */
	        LEFT OUTER JOIN
	    vehicle ON vehicle.vehicleid = unit.vehicleid
	       /*  AND vehicle.isdeleted = 0  */
	        INNER JOIN
	    trans_status ON trans_status.id = unit.trans_statusid
	        LEFT OUTER JOIN
	    devices d1 ON d1.deviceid = d.deviceid
	        AND d1.expirydate > (DATE_ADD(CURDATE(), INTERVAL 15 DAY)) /*ACTIVE*/
	        LEFT OUTER JOIN
	    devices d3 ON d3.deviceid = d.deviceid
	        AND d3.expirydate < (CURDATE())/*EXPIRED*/
	        LEFT OUTER JOIN
	    devices d4 ON d4.deviceid = d.deviceid
	        AND d4.expirydate BETWEEN CURDATE() AND (DATE_ADD(CURDATE(), INTERVAL 15 DAY))  /*EXPIRING IN 15 DAYS*/
	        INNER JOIN
	    customer c ON c.customerno = unit.customerno
	        AND c.renewal NOT IN (- 1 , - 2)
	        INNER JOIN
	    team t ON t.rid = c.rel_manager
	WHERE
	    (c.rel_manager = relManParam
	        OR relManParam IS NULL)
	GROUP BY c.customerno
	ORDER BY expiredCount desc,c.customerno; 
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_toBe_expired_device_info`$$
CREATE PROCEDURE `get_toBe_expired_device_info`(
IN customernoParam INT)
BEGIN
SELECT 
    unit.unitno,
    vehicle.vehicleno,
    trans_status.status,
    tsim.status as sim_status,
    d.start_date,
    d.end_date,
    d.expirydate,
    d.invoiceno,
    d.device_invoiceno
FROM
    devices d
        INNER JOIN
    unit ON unit.uid = d.uid
        AND unit.trans_statusid NOT IN (10 , 22)
        INNER JOIN
    simcard ON simcard.id = d.simcardid
        INNER JOIN
    vehicle ON vehicle.vehicleid = unit.vehicleid
        AND vehicle.isdeleted = 0
        INNER JOIN
    trans_status ON trans_status.id = unit.trans_statusid
     INNER JOIN
    trans_status tsim ON tsim.id = simcard.trans_statusid
        INNER JOIN
    customer c ON c.customerno = d.customerno
WHERE
    d.expirydate BETWEEN CURDATE() AND (DATE_ADD(CURDATE(), INTERVAL 15 DAY))
        AND d.customerno = customernoParam;
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `get_expired_device_info`$$
CREATE  PROCEDURE `get_expired_device_info`(
IN customernoParam INT)
BEGIN


	SELECT unit.unitno,vehicle.vehicleno,trans_status.status,tsim.status as sim_status,d.start_date,d.end_date,d.expirydate,d.invoiceno,d.device_invoiceno
	FROM devices d
	INNER JOIN unit ON unit.uid = d.uid AND unit.trans_statusid NOT IN(10,22)
	INNER  JOIN simcard ON simcard.id = d.simcardid
	INNER  JOIN vehicle ON vehicle.vehicleid = unit.vehicleid AND vehicle.isdeleted=0
	INNER JOIN trans_status ON trans_status.id = unit.trans_statusid
    INNER JOIN trans_status tsim ON tsim.id = simcard.trans_statusid
	INNER JOIN customer c on c.customerno = d.customerno 
	WHERE d.expirydate < (CURDATE()) AND d.customerno = customernoParam;
END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `get_active_device_info`$$
CREATE PROCEDURE `get_active_device_info`(
IN customernoParam INT)
BEGIN



	SELECT unit.unitno,vehicle.vehicleno,trans_status.status,
    tsim.status as sim_status,d.start_date,d.end_date,d.expirydate,d.invoiceno,d.device_invoiceno
	FROM devices d
	INNER JOIN unit ON unit.uid = d.uid AND unit.trans_statusid NOT IN(10,22)
	INNER  JOIN simcard ON simcard.id = d.simcardid
	INNER  JOIN vehicle ON vehicle.vehicleid = unit.vehicleid AND vehicle.isdeleted=0
	INNER JOIN trans_status ON trans_status.id = unit.trans_statusid
    INNER JOIN
    trans_status tsim ON tsim.id = simcard.trans_statusid
	INNER JOIN customer c on c.customerno = d.customerno 
	WHERE d.expirydate > (DATE_ADD(CURDATE(), INTERVAL 15 DAY))  AND d.customerno = customernoParam;
END$$

DELIMITER ;




UPDATE  dbpatches
SET     patchdate = '2018-03-20 12:30:00'
        ,isapplied =1
WHERE   patchid = 692;

