USE `speed`;
DROP procedure IF EXISTS `get_invoice_payment_old`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `get_invoice_payment_old`(
    IN invoiceidParam INT
    )
BEGIN
SELECT i.invoiceid,i.ledgerid,i.customerno,i.tds_amt,ip.payment_amt,ip.payment,i.paymentdate,ip.chequeno,ip.bank_name,ip.branch
from invoice i 
INNER JOIN invoice_payment ip on ip.invoiceid = i.invoiceid
WHERE i.invoiceid =  invoiceidParam;
END$$

DELIMITER ;