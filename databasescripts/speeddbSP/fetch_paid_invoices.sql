USE `speed`;
DROP procedure IF EXISTS `fetch_paid_invoices`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `fetch_paid_invoices`(
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