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