INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'587', '2018-08-01 17:30:00', 'Yash Kanakia', 'Invoice Payment Mapping', '0'
);

USE `speed`;
DROP procedure IF EXISTS `get_payment_mode`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_payment_mode`()
BEGIN
SELECT * from payment_mode;

END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `fetch_ledger`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `fetch_ledger`(
    IN customernoParam VARCHAR(20)
)
BEGIN
SELECT DISTINCT l.ledgerid,l.ledgername
from ledger l
LEFT JOIN ledger_cust_mapping lcm ON lcm.ledgerid = l.ledgerid
LEFT JOIN customer c ON  lcm.customerno = c.customerno 
where c.customerno = customernoParam and l.ledgerid <> 0 AND l.isdeleted = 0
ORDER BY l.ledgerid; 
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `fetch_pending_invoices`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `fetch_pending_invoices`(
IN ledgerParam INT)
BEGIN
  SELECT i.invoiceid,invoiceno
  from invoice i
  WHERE ledgerid =  ledgerParam AND (i.inv_amt <> (CAST(i.paid_amt AS DECIMAL(8,2)))+CAST(i.tds_amt AS DECIMAL(8,2))+CAST(i.unpaid_amt AS DECIMAL(8,2)))AND
  i.invoiceid NOT IN(SELECT ip.invoiceid from invoice_payment_mapping ip where ip.invoiceid = i.invoiceid)
  UNION
  SELECT DISTINCT i.invoiceid,i.invoiceno
  from invoice_payment_mapping ip 
  INNER JOIN invoice i on i.invoiceid = ip.invoiceid
  WHERE i.ledgerid = ledgerParam AND i.inv_amt <> (SELECT (sum(ipm.paid_amt)+sum(ipm.tds_amt)+sum(ipm.bad_debts))+CAST(i.paid_amt AS DECIMAL(8,2))+CAST(i.tds_amt AS DECIMAL(8,2))+CAST(i.unpaid_amt AS DECIMAL(8,2)) from invoice_payment_mapping ipm where ipm.invoiceid=ip.invoiceid)
  GROUP BY invoiceid
  ORDER BY invoiceid;
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `get_invoice_payment`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_invoice_payment`(
    IN invoiceidParam INT
    )
BEGIN
SELECT ip.ip_id,ip.invoiceid,ip.customerno,ip.tds_amt,ip.paid_amt,ipm.cheque_no,ipm.bank_name,ipm.bank_branch,ipm.cheque_date,
(SELECT p.mode from payment_mode p where p.pm_id = ip.pay_mode) as payment_mode ,ip.paymentdate
from invoice_payment_mapping ip
INNER JOIN invoice_payment_mapping ipm on ipm.ip_id = ip.ip_id
WHERE ip.invoiceid =  invoiceidParam and ipm.ip_id =  ip.ip_id
GROUP BY ip.ip_id;
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `get_payment_sub_details`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_payment_sub_details`(
    IN invoiceidParam INT
    )
BEGIN
DECLARE InvoiceAmountParam float(10,2);
DECLARE TotalPaidAmountParam float(10,2);
DECLARE TotalPendingAmountParam float(10,2);
DECLARE TotalTdsAmountParam float(10,2);
DECLARE TotalUnpaid_AmountParam float(10,2);
DECLARE InvoiceidVar INT;

SELECT DISTINCT invoiceid INTO InvoiceidVar from invoice_payment_mapping 
where invoiceid = invoiceidParam;
IF(InvoiceidVar IS NULL) THEN
SELECT inv_amt ,paid_amt,(inv_amt-(paid_amt+tds_amt+unpaid_amt)),tds_amt,unpaid_amt
INTO InvoiceAmountParam,TotalPaidAmountParam,TotalPendingAmountParam,TotalTdsAmountParam,TotalUnpaid_AmountParam
from invoice
WHERE invoiceid =  invoiceidParam;
ELSE
SELECT ip.inv_amt ,sum(ip.paid_amt)+(i.paid_amt),(i.inv_amt-((sum(ip.paid_amt)+i.paid_amt)+(sum(ip.tds_amt)+i.tds_amt)+(sum(ip.bad_debts)+i.unpaid_amt))),(i.tds_amt)+sum(ip.tds_amt),(sum(ip.bad_debts)+i.unpaid_amt)
INTO InvoiceAmountParam,TotalPaidAmountParam,TotalPendingAmountParam,TotalTdsAmountParam,TotalUnpaid_AmountParam
from invoice_payment_mapping ip
INNER JOIN invoice i on i.invoiceid = ip.invoiceid
WHERE i.invoiceid =  invoiceidParam;
END IF;
SELECT InvoiceAmountParam,TotalPaidAmountParam,TotalPendingAmountParam,TotalTdsAmountParam,TotalUnpaid_AmountParam;
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `get_invoice_payment_old`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_invoice_payment_old`(
    IN invoiceidParam INT
    )
BEGIN
SELECT i.invoiceid,i.ledgerid,i.customerno,i.tds_amt,i.pay_mode,
i.paid_amt,i.paymentdate,ip.chequeno,ip.bank_name,ip.branch
from invoice i 
LEFT JOIN invoice_payment ip on ip.invoiceid=i.invoiceid
WHERE i.invoiceid =  invoiceidParam AND i.paid_amt>0;
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `fetch_paid_invoices`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `fetch_paid_invoices`(
IN ledgerParam INT)
BEGIN
SELECT i.invoiceid,invoiceno
  from invoice i
  WHERE ledgerid =  ledgerParam AND (i.inv_amt = (CAST(i.paid_amt AS DECIMAL(8,2)))+CAST(i.tds_amt AS DECIMAL(8,2))+CAST(i.unpaid_amt AS DECIMAL(8,2)))AND
  i.invoiceid NOT IN(SELECT ip.invoiceid from invoice_payment_mapping ip where ip.invoiceid = i.invoiceid)
  UNION
  SELECT DISTINCT i.invoiceid,i.invoiceno
  from invoice_payment_mapping ip 
  INNER JOIN invoice i on i.invoiceid = ip.invoiceid
  WHERE i.ledgerid = ledgerParam AND i.inv_amt = (SELECT (sum(ipm.paid_amt)+sum(ipm.tds_amt)+sum(ipm.bad_debts))+CAST(i.paid_amt AS DECIMAL(8,2))+CAST(i.tds_amt AS DECIMAL(8,2))+CAST(i.unpaid_amt AS DECIMAL(8,2)) from invoice_payment_mapping ipm where ipm.invoiceid=ip.invoiceid)
  GROUP BY invoiceid
  ORDER BY invoiceid;
END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `insert_invoice_payments`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `insert_invoice_payments`(
IN customernoParam INT,
IN invoiceidParam INT,
IN invoicenoParam VARCHAR(50),
IN payment_modeParam tinyint,
IN payment_dateParam date,
IN cheque_noParam INT,
IN bank_nameParam VARCHAR(255),
IN bank_branchParam VARCHAR(50),
IN cheque_dateParam date,
IN new_payment_amountParam float,
IN new_tdsParam float,
IN new_unpaid_amountParam float,
IN teamidParam INT,
IN created_onParam datetime,
OUT isExecutedOutParam INT)
BEGIN
DECLARE invoice_amountVar FLOAT;
DECLARE totalPaid_AmountVar FLOAT;
DECLARE InvoiceidVar INT;
DECLARE ip_idVar INT;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
       /*
           ROLLBACK;
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error; */
		END;



SET isExecutedOutParam = 0;

SELECT inv_amt INTO invoice_amountVar 
from `invoice` 
where invoiceid = invoiceidParam;


INSERT INTO `invoice_payment_mapping`(`invoiceid`
		,`invoiceno`			
		, `customerno`
        ,`inv_amt`
		, `pay_mode`
		, `paid_amt`
		, `paymentdate`
		, `tds_amt`
        ,`bad_debts`
		, `cheque_no`
		, `cheque_date`
		, `bank_name`
		, `bank_branch`
        ,`created_by`
		, `created_on`) 
VALUES (invoiceidParam
		,invoicenoParam
		,customernoParam
        ,invoice_amountVar
		,payment_modeParam
		,new_payment_amountParam
		,payment_dateParam
		,new_tdsParam
        ,new_unpaid_amountParam
		,cheque_noParam
		,cheque_dateParam
		,bank_nameParam
		,bank_branchParam
        ,teamidParam
		,created_onParam);
        
        SELECT  LAST_INSERT_ID()
		INTO    ip_idVar;
        
SELECT DISTINCT invoiceid INTO InvoiceidVar from invoice_payment_mapping 
where invoiceid = invoiceidParam;

IF(InvoiceidVar IS NULL) THEN
SELECT (i.paid_amt+i.tds_amt+i.unpaid_amt)
INTO totalPaid_AmountVar
from invoice i
where i.invoiceid=invoiceidParam;      
ELSE
SELECT (sum(ip.paid_amt)+sum(ip.tds_amt)+sum(ip.bad_debts))+(i.paid_amt+i.tds_amt+i.unpaid_amt)
INTO totalPaid_AmountVar
from invoice_payment_mapping ip
INNER JOIN invoice i on i.invoiceid = ip.invoiceid
where i.invoiceid=invoiceidParam; 
END IF;

IF(totalPaid_AmountVar<invoice_amountVar OR totalPaid_AmountVar>invoice_amountVar)THEN
UPDATE `invoice_payment_mapping`
SET `pending_amt`=(invoice_amountVar-totalPaid_AmountVar)
where ip_id=ip_idVar; 
UPDATE `invoice`
SET `status`='Pending',
	`pending_amt`=(invoice_amountVar-totalPaid_AmountVar)
where invoiceid=invoiceidParam; 
ELSE
UPDATE `invoice_payment_mapping`
SET `pending_amt`=(invoice_amountVar-totalPaid_AmountVar)
where ip_id=ip_idVar; 
UPDATE `invoice`
SET `status`='Paid',
	`pending_amt`=(invoice_amountVar-totalPaid_AmountVar)
where invoiceid=invoiceidParam; 
END IF;
 
SET isExecutedOutParam = 1; 

END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `get_payment_mapping`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_payment_mapping`(
IN invoicemapping_idParam INT)
BEGIN
  SELECT ip.invoiceno,ip.customerno,(SELECT customercompany from customer where customerno = ip.customerno)as customercompany,ip.inv_amt,ip.paid_amt,ip.paymentdate,ip.tds_amt,ip.bad_debts,ip.cheque_no,ip.pay_mode,
  ip.cheque_date,ip.bank_name,ip.bank_branch,(SELECT mode from payment_mode where ip.pay_mode = pm_id) as payment_mode 
  
  from invoice_payment_mapping ip
  WHERE ip_id = invoicemapping_idParam;

END$$

DELIMITER ;

USE `speed`;
DROP procedure IF EXISTS `update_invoice_payment`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `update_invoice_payment`(
IN invoice_payment_idParam INT,
IN payment_dateParam date,
IN cheque_noParam INT,
IN bank_nameParam VARCHAR(255),
IN bank_branchParam VARCHAR(50),
IN cheque_dateParam date,
IN new_payment_amountParam float,
IN new_tdsParam float,
IN new_unpaid_amountParam float,
IN cheque_statusParam INT,
IN teamidParam INT,
IN updated_onParam datetime,
OUT isExecutedOutParam INT)
BEGIN
DECLARE invoice_amountVar FLOAT;
DECLARE totalPaid_AmountVar FLOAT;
DECLARE invoiceidVar INT;
		DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
       /*
           ROLLBACK;
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error; */
		END;
SET isExecutedOutParam = 0;
UPDATE invoice_payment_mapping
SET  	`paid_amt`=new_payment_amountParam,
		`paymentdate`=payment_dateParam,
		`tds_amt`=new_tdsParam,
        `bad_debts`=new_unpaid_amountParam,
		`cheque_no`=cheque_noParam,
		`cheque_date`=cheque_dateParam,
		`bank_name`=bank_nameParam,
		`bank_branch`=bank_branchParam,
        `cheque_status`=cheque_statusParam,
        `updated_by`=teamidParam,
		`updated_on`=updated_onParam
WHERE `ip_id`=invoice_payment_idParam;

SELECT DISTINCT invoiceid INTO invoiceidVar from invoice_payment_mapping
where ip_id = invoice_payment_idParam; 


SELECT inv_amt INTO invoice_amountVar 
from `invoice` 
where invoiceid = invoiceidVar;



SELECT (sum(ip.paid_amt)+sum(ip.tds_amt)+sum(ip.bad_debts))+(i.paid_amt+i.tds_amt+i.unpaid_amt)
INTO totalPaid_AmountVar
from invoice_payment_mapping ip
INNER JOIN invoice i on i.invoiceid = ip.invoiceid
where i.invoiceid=invoiceidVar; 

IF(totalPaid_AmountVar<invoice_amountVar OR totalPaid_AmountVar>invoice_amountVar)THEN
UPDATE `invoice_payment_mapping`
SET `pending_amt`=(invoice_amountVar-totalPaid_AmountVar)
where ip_id=invoice_payment_idParam; 

UPDATE `invoice`
SET `status`='Pending',
	`pending_amt`=(invoice_amountVar-totalPaid_AmountVar)
where invoiceid=invoiceidVar; 
ELSE
UPDATE `invoice_payment_mapping`
SET `pending_amt`=(invoice_amountVar-totalPaid_AmountVar)
where ip_id=invoice_payment_idParam; 

UPDATE `invoice`
SET `status`='Paid',
	`pending_amt`=(invoice_amountVar-totalPaid_AmountVar)
where invoiceid=invoiceidVar; 
END IF;


SET isExecutedOutParam = 1;
END$$

DELIMITER ;

DROP TABLE IF EXISTS `payment_mode`;
CREATE TABLE `payment_mode` (
  `pm_id` tinyint(4) NOT NULL,
  `mode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`pm_id`)
);
INSERT INTO `payment_mode` VALUES (1,'Cheque'),(2,'Cash'),(3,'Online');

DROP TABLE IF EXISTS `invoice_payment_mapping`;
CREATE TABLE `invoice_payment_mapping` (
  `ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` int(11) DEFAULT NULL,
  `invoiceno` varchar(50) DEFAULT NULL,
  `customerno` int(11) DEFAULT NULL,
  `inv_amt` float(8,2) DEFAULT '0.00',
  `pending_amt` float(8,2) DEFAULT '0.00',
  `pay_mode` varchar(40) DEFAULT NULL,
  `paid_amt` float(8,2) DEFAULT '0.00',
  `paymentdate` date DEFAULT NULL,
  `tds_amt` float(8,2) DEFAULT '0.00',
  `cheque_no` int(6) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_branch` varchar(50) DEFAULT NULL,
  `bad_debts` float(8,2) DEFAULT '0.00',
  `cheque_status` tinyint(4) DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`ip_id`)
);