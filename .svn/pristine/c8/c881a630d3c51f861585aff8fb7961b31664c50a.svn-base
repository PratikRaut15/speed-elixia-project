DELIMITER $$
DROP PROCEDURE IF EXISTS `get_opening_balance`$$
CREATE  PROCEDURE `get_opening_balance`(
IN ledgeridParam INT,
IN fromDateParam date)
BEGIN
DECLARE invoiceAmount,pendingAmount INT;
DECLARE totalPendingAmount INT;

SELECT sum(inv_amt) INTO  invoiceAmount from invoice
where inv_date >= '2018-04-01' AND inv_date < fromDateParam AND ledgerid=ledgeridParam;


SELECT sum(pending_amt) INTO  pendingAmount from invoice
where inv_date <= '2018-03-31' AND ledgerid=ledgeridParam;

SET totalPendingAmount=invoiceAmount+pendingAmount;


SELECT (totalPendingAmount - sum(CASE WHEN ipm.`paymentdate`<fromDateParam THEN ipm.`paid_amt` ELSE 0 END)) as Opening_Balance
FROM invoice_payment_mapping ipm
INNER JOIN invoice i on i.invoiceid= ipm.invoiceid
WHERE i.ledgerid = ledgeridParam;


END$$

DELIMITER ;
