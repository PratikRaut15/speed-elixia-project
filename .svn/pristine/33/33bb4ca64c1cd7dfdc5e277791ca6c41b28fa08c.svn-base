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
SET   `paid_amt`=new_payment_amountParam,
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