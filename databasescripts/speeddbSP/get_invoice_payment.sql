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