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