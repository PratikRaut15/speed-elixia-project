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
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'588', '2018-08-01 17:30:00', 'Yash Kanakia', 'Ledger Generation', '0'
);

DROP procedure IF EXISTS `get_opening_balance`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_opening_balance`(
IN ledgeridParam INT,
IN fromDateParam date)
BEGIN
DECLARE invoiceAmount,pendingAmount INT;
DECLARE totalPendingAmount INT;

SELECT sum(inv_amt) INTO  invoiceAmount from invoice
where inv_date >= '2018-04-01' AND ledgerid=ledgeridParam;


SELECT sum(pending_amt) INTO  pendingAmount from invoice
where inv_date <= '2018-03-31' AND ledgerid=ledgeridParam;

SET totalPendingAmount=invoiceAmount+pendingAmount;


SELECT (totalPendingAmount - sum(CASE WHEN ipm.`paymentdate`<fromDateParam THEN ipm.`paid_amt` ELSE 0 END)) as Opening_Balance
FROM invoice_payment_mapping ipm
INNER JOIN invoice i on i.invoiceid= ipm.invoiceid 
WHERE i.ledgerid = ledgeridParam;
END$$

DELIMITER ;

