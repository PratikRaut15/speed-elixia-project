INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'603', '2018-08-27 15:34:11', 'Yash Kanakia', 'Team-Budgeting', '0'
);

DROP TABLE IF EXISTS `renewal_period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renewal_period` (
  `renewal_id` int(11) NOT NULL AUTO_INCREMENT,
  `period` varchar(100) DEFAULT NULL,
  `renewal` int(11) DEFAULT NULL,
  PRIMARY KEY (`renewal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
LOCK TABLES `renewal_period` WRITE;
/*!40000 ALTER TABLE `renewal_period` DISABLE KEYS */;
INSERT INTO `renewal_period` VALUES (1,'Not Assigned',0),(2,'Monthly',1),(3,'Quarterly',3),(4,'Six Monthly',6),(5,'Yearly',12),(6,'Demo',-1),(7,'Closed',-2),(8,'Lease',-3);
/*!40000 ALTER TABLE `renewal_period` ENABLE KEYS */;
UNLOCK TABLES;



USE `speed`;
DROP procedure IF EXISTS `fetch_max_invoiceid_budgeting`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `fetch_max_invoiceid_budgeting`()
BEGIN
select DISTINCT max(i.invoiceid) as invoiceid,i.customerno,l.ledgerid,l.ledgername
from ledger_veh_mapping lvm
INNER JOIN ledger l on l.ledgerid = lvm.ledgerid
INNER JOIN customer c on c.customerno = lvm.customerno AND c.renewal NOT IN (-1,-2)
INNER JOIN invoice i on i.ledgerid = lvm.ledgerid AND i.invoiceno LIKE 'ESS%' AND i.inv_amt>0 
where lvm.isdeleted = 0 AND lvm.customerno NOT IN (1,444)
GROUP BY lvm.ledgerid,i.customerno
ORDER BY i.customerno;
END$$

DELIMITER ;


USE `speed`;
DROP procedure IF EXISTS `fetch_customers_budgeting`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `fetch_customers_budgeting`(
IN invoiceidParam INT)
BEGIN
SELECT lvm.ledgerid,l.ledgername,i.customerno,c.customercompany,c.renewal as renewal_number,rp.period as renewal,t.name,
c.customercompany,c.unitprice,count(DISTINCT(u.uid)) as devices_count,c.lease_price,c.unit_msp,c.warehouse_msp,i.start_date,i.end_date,invoiceidParam
FROM ledger_veh_mapping lvm 
INNER JOIN invoice i on i.ledgerid = lvm.ledgerid AND i.invoiceno LIKE 'ESS%' AND i.inv_amt>0 
INNER JOIN ledger l on l.ledgerid = lvm.ledgerid
INNER JOIN customer c on c.customerno = i.customerno AND c.renewal NOT IN (-1,-2)
INNER JOIN ledger_cust_mapping lcm on lcm.customerno = lvm.customerno AND lcm.isdeleted = 0
INNER JOIN renewal_period rp on rp.renewal = c.renewal
INNER JOIN team t on t.rid = c.rel_manager
INNER JOIN vehicle v ON v.vehicleid = lvm.vehicleid AND v.invoice_hold = 0
INNER JOIN unit u ON u.vehicleid = v.vehicleid AND u.trans_statusid IN (5,6)
INNER JOIN devices d ON d.uid = u.uid
INNER JOIN simcard s ON s.id = d.simcardid AND s.trans_statusid IN (13,14)
WHERE i.invoiceid = invoiceidParam AND lvm.isdeleted = 0 AND i.customerno NOT IN (1,444)
GROUP BY lvm.ledgerid,i.customerno
ORDER BY lvm.customerno;
END$$

DELIMITER ;



USE `speed`;
DROP procedure IF EXISTS `fixed_budgeting_amount`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `fixed_budgeting_amount`( 
IN customernoParam INT,
IN ledgeridParam INT,
IN invoiceidParam INT,
IN startDateParam date,
IN endDateParam date
)
BEGIN
DECLARE taxable_amt INT;
DECLARE actual_renewal INT;
DECLARE final_amt INT;
select (i.inv_amt-(i.cgst+i.sgst+i.igst)),TIMESTAMPDIFF(MONTH, startDateParam, endDateParam)+1 INTO taxable_amt, actual_renewal
from invoice i 
where i.customerno = customernoParam AND i.ledgerid=ledgeridParam AND i.invoiceid = invoiceidParam
GROUP by i.ledgerid;
SET final_amt=(taxable_amt/actual_renewal);
SELECT final_amt;
END$$

DELIMITER ;







