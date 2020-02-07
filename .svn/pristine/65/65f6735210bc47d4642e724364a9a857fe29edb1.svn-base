INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'562', '2018-05-025 14:00:00', 'Yash Kanakia', 'Invoices and Ledger', '0'
);

USE `speed`;
DROP procedure IF EXISTS `getLedgerDetails`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `getLedgerDetails`(
    IN ledgeridParam INT,
    IN fromDateParam DATE,
    IN toDateParam DATE
)
BEGIN
    SELECT       p.inv_amt
                ,p.invoiceno
                ,p.start_date
                ,p.end_date
                ,p.quantity
                ,l.ledgername
                ,l.gst_no
                ,p.inv_date
                ,c.customerno
                ,CONCAT(l.address1, '', l.address2,'',l.address3) AS address
                ,sum(p.inv_amt) as total_inv_amt
             
        FROM    `invoice` p 
        LEFT OUTER JOIN ledger l ON l.ledgerid = p.ledgerid
        LEFT OUTER JOIN customer c ON c.customerno = p.customerno
        LEFT OUTER JOIN invoice_payment ip ON ip.invoiceid = p.invoiceid
        where p.inv_date BETWEEN fromDateParam AND toDateParam
        AND l.ledgerid = ledgeridParam
        AND p.invoiceno NOT LIKE '%can%'
        GROUP BY p.invoiceid,c.customerno
        ORDER BY inv_date ASC ;
    END$$

DELIMITER ;


USE `speed`;
DROP procedure IF EXISTS `get_ledger`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `get_ledger`(
    IN ledgeridparam INT
    , IN ledgernameparam VARCHAR(100)
)
BEGIN
DECLARE custno INT;
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
            ,l.state_code
            ,l.email
            ,l.phone
            ,l.pan_no
            ,l.gst_no
            ,l.cst_no
            ,l.st_no
            ,l.vat_no
            ,l.createdby
            ,l.createdon
            ,l.updatedby
            ,l.updatedon
            ,sgc.`state`
            ,(SELECT GROUP_CONCAT(DISTINCT c.customercompany SEPARATOR ', ')) as customercompany
    FROM    ledger AS l
    INNER JOIN state_gst_code sgc ON sgc.codeid = l.state_code
    INNER JOIN ledger_cust_mapping lcm ON lcm.ledgerid = l.ledgerid
    INNER JOIN customer c ON  lcm.customerno = c.customerno 
    AND     lcm.isdeleted = 0
    GROUP BY l.ledgerid,lcm.customerno;
    ORDER BY l.ledgerid DESC;

END$$

DELIMITER ;


USE `speed`;
DROP procedure IF EXISTS `insert_ledger`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `insert_ledger`( 
    IN ledgernameParam VARCHAR(100)
    , IN address1Param VARCHAR(100)
    , IN address2Param VARCHAR(100)
    , IN address3Param VARCHAR(100)
    , IN stateParam VARCHAR(100)
    , IN emailParam VARCHAR(40)
    , IN phoneParam VARCHAR(20)
    , IN pan_noParam VARCHAR(30)
    , IN gst_noParam VARCHAR(30)
    , IN createdbyParam INT(11)
    , IN createdonParam DATETIME
    , IN updatedbyParam INT(11)
    , IN updatedonParam DATETIME
    , OUT isexecutedOut TINYINT(2)
    , OUT lastinsertidOut  INT(11)
)
BEGIN
    

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
			
            SET isexecutedOut = 0;
	END;
    BEGIN  

        SET isexecutedOut = 0;

        START TRANSACTION;	 
        BEGIN
            INSERT INTO ledger(ledgername
                , address1 
                , address2 
                , address3
                , state_code
                , email 
                , phone 
                , pan_no 
                , gst_no
                , createdby 
                , createdon 
                , updatedby 
                , updatedon) 
            VALUES(ledgernameParam 
                , address1Param 
                , address2Param 
                , address3Param
                , stateParam
                , emailParam 
                , phoneParam 
                , pan_noParam 
                , gst_noParam
                , createdbyParam 
                , createdonParam 
                , updatedbyParam 
                , updatedonParam);

            SELECT  LAST_INSERT_ID()
            INTO    lastinsertidOut; 

            SET isexecutedOut = 1;

        END;
        COMMIT; 
    
    END;
    
END$$

DELIMITER ;


USE `speed`;
DROP procedure IF EXISTS `update_ledger`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `update_ledger`
    ( 
      IN ledgeridParam INT
    , IN ledgernameParam VARCHAR(100)
    , IN address1Param VARCHAR(100)
    , IN address2Param VARCHAR(100)
    , IN address3Param VARCHAR(100)
    , IN stateParam VARCHAR(100)
    , IN emailParam VARCHAR(40)
    , IN phoneParam VARCHAR(20)
    , IN pan_noParam VARCHAR(30)
    , IN gst_noParam VARCHAR(30)
    , IN updatedbyParam INT
    , IN updatedonParam DATETIME
    , OUT isexecutedOut TINYINT(2)
)
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
            ROLLBACK;
           
            SET isexecutedOut = 0;
	END;
    BEGIN  

        SET isexecutedOut = 0;

        START TRANSACTION;	 
        BEGIN

            UPDATE  ledger 
            SET     ledgername = ledgernameParam
                    , address1 = address1Param 
                    , address2 = address2Param
                    , address3 = address3Param
                    , state_code = stateParam
                    , email = emailParam
                    , phone = phoneParam 
                    , pan_no = pan_noParam
                    , gst_no = gst_noParam
                    , updatedby = updatedbyParam
                    , updatedon = updatedonParam
            WHERE   ledgerid = ledgeridParam 
            AND     isdeleted = 0;

            SET     isexecutedOut = 1;
            
        END;
        COMMIT; 
    
    END;
                
END$$

DELIMITER ;



USE `speed`;
DROP procedure IF EXISTS `unmappedVehicle`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `unmappedVehicle`(
IN customernoParam INT
)
BEGIN
select count(v.vehicleid) as count,v.customerno,c.customercompany FROM vehicle v
INNER JOIN unit u ON u.vehicleid = v.vehicleid AND u.unitno NOT LIKE 'D%'  
INNER JOIN devices d ON d.uid = u.uid
INNER JOIN customer c ON v.customerno = c.customerno
INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)
WHERE v.customerno = customernoParam AND v.isdeleted=0 AND
v.vehicleid NOT IN (SELECT l.vehicleid
FROM    ledger_veh_mapping as l
INNER JOIN vehicle as v ON l.vehicleid = v.vehicleid
INNER JOIN unit as u ON u.vehicleid = v.vehicleid
INNER JOIN devices as d ON d.uid = u.uid
INNER JOIN customer c ON v.customerno = c.customerno
INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)
WHERE   l.customerno = customernoParam AND l.isdeleted = 0)
HAVING count(v.vehicleid)>0;
END$$

DELIMITER ;



USE `speed`;
DROP procedure IF EXISTS `getLedgerPaymentDetails`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `getLedgerPaymentDetails`(
    IN ledgeridParam INT,
    IN fromDateParam DATE,
    IN toDateParam DATE
)
BEGIN
      SELECT     ipm.paid_amt
                ,ipm.tds_amt
                ,(ipm.paid_amt+ipm.tds_amt) as total_paid_amt
                ,(SELECT pm.mode from payment_mode pm where pm.pm_id = ipm.pay_mode) as pay_mode
                ,ipm.invoiceno
                ,ipm.paymentdate
                ,c.customerno
                ,l.ledgername
                ,l.gst_no
                ,CONCAT(l.address1, '', l.address2,'',l.address3) AS address
                ,ipm.cheque_no
        FROM    `invoice` p 
        LEFT OUTER JOIN ledger l ON l.ledgerid = p.ledgerid
        LEFT OUTER JOIN customer c ON c.customerno = p.customerno
        LEFT OUTER JOIN invoice_payment_mapping ipm ON ipm.invoiceid = p.invoiceid
        INNER JOIN payment_mode pm ON pm.pm_id = ipm.pay_mode
        where ipm.paymentdate BETWEEN fromDateParam AND toDateParam
        AND l.ledgerid = ledgeridParam
        AND p.invoiceno NOT LIKE '%can%'
        GROUP BY ipm.ip_id,c.customerno
        ORDER BY paymentdate ASC;
    END$$

DELIMITER ;





USE `speed`;
DROP procedure IF EXISTS `get_hardware_invoices`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_hardware_invoices`(
IN ledgeridParam INT)
BEGIN
                    SELECT  lv.`ledgerid`
                            ,lv.`customerno`
                            ,`customer`.`unitprice`
                            ,count(lv.`customerno`) AS count1
                            ,SUM(`customer`.`unitprice`) AS total
                            ,l.`state_code`
                            ,sgc.`state`
                            FROM    `ledger_veh_mapping` lv
                            INNER JOIN `ledger` l ON l.`ledgerid` = lv.`ledgerid`
                            INNER JOIN `state_gst_code` sgc ON sgc.codeid = l.`state_code`
                            INNER JOIN `customer` ON `customer`.`customerno` = lv.`customerno`
                            INNER JOIN `vehicle` ON `vehicle`.vehicleid = lv.vehicleid AND `vehicle`.invoice_hold = 0
                            INNER JOIN `unit` u ON u.vehicleid = vehicle.vehicleid AND u.trans_statusid IN (5,6)
                            INNER JOIN `devices` d ON d.uid = u.uid
                            WHERE lv.`ledgerid` =ledgeridParam
                            AND lv.`isdeleted` = 0
                            AND d.device_invoiceno = ''
                            group by lv.`customerno`;
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `get_all_ledgers`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_all_ledgers`()
BEGIN
select ledgerid
from ledger_cust_mapping ;
    
END$$

DELIMITER ;


