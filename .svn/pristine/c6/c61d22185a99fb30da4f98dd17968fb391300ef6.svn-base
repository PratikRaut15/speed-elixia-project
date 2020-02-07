INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('507', '2017-05-31 17:29:00','Arvind Thakur','Automated proforma invoice generation', '0');

ALTER TABLE `proforma_invoice`
ADD COLUMN `is_processed` TINYINT(1) DEFAULT 1;

UPDATE  `devices` d
INNER JOIN `customer` c ON c.customerno = d.customerno 
INNER JOIN `unit` u ON u.uid = d.uid
INNER JOIN `vehicle` ON `vehicle`.vehicleid = u.vehicleid 
INNER JOIN `simcard` ON `simcard`.id = d.simcardid 
SET     d.`end_date` = '2017-05-31' 
WHERE   u.trans_statusid IN (5,6) 
AND     `vehicle`.invoice_hold = 0 
AND     `simcard`.trans_statusid IN (13,14) 
AND     c.renewal = 1 
AND     c.customerno NOT IN (1,212,59,64,116,141,164,204,250,306,386,185);


UPDATE  `devices` d
INNER JOIN `customer` c ON c.customerno = d.customerno 
INNER JOIN `unit` u ON u.uid = d.uid
INNER JOIN `vehicle` ON `vehicle`.vehicleid = u.vehicleid 
INNER JOIN `simcard` ON `simcard`.id = d.simcardid 
SET     d.`end_date` = '2017-04-30' 
WHERE   u.trans_statusid IN (5,6) 
AND     `vehicle`.invoice_hold = 0 
AND     `simcard`.trans_statusid IN (13,14) 
AND     c.renewal = 1 
AND     c.customerno = 185;


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 507;
