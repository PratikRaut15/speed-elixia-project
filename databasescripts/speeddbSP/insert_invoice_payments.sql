USE `speed`;
DROP procedure IF EXISTS `insert_invoice_payments`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `insert_invoice_payments`(
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