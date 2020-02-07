INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('505', '2017-05-23 17:29:00','Arvind Thakur','Proforma and Invoice Table changed', '0');


ALTER TABLE `devices`
ADD COLUMN `start_date` DATE,
ADD COLUMN `end_date` DATE;

ALTER TABLE `proforma_invoice` MODIFY `tax` TINYINT(2);

UPDATE  `proforma_invoice`
SET     `tax` = 1
WHERE   `tax` LIKE '%st%';

UPDATE  `proforma_invoice`
SET     `tax` = 2
WHERE   `tax` LIKE '%vat%';

UPDATE  `proforma_invoice`
SET     `tax` = 3
WHERE   `tax` LIKE '%cst%';

ALTER TABLE `proforma_invoice` 
DROP COLUMN `finaldate`,
DROP COLUMN `comment`,
DROP COLUMN `approved`,
ADD COLUMN `timestamp` DATETIME,
ADD COLUMN `quantity` INT(11);

ALTER TABLE `ledger`
ADD COLUMN `invoice_hold` TINYINT DEFAULT 0;

ALTER TABLE `vehicle`
ADD COLUMN `invoice_hold` TINYINT DEFAULT 0;

alter table `invoice` 
drop column `clientname`,
add column `customername` varchar(255),
add column `start_date` DATE,
add column `end_date` DATE;

UPDATE `invoice` i
JOIN   `invoice` j ON j.tax = i.tax AND j.tax = 1
SET    i.product_id = 1;

UPDATE `invoice` i
JOIN   `invoice` j ON j.tax = i.tax AND j.tax = 2
SET    i.product_id = 2;


UPDATE `invoice` i
JOIN   `invoice` j ON j.tax = i.tax AND j.tax = 3
SET    i.product_id = 3;


update  `invoice` i
JOIN    `customer` c ON c.customerno = i.customerno 
AND     i.customerno IN (select lc.customerno 
                        from    ledger_cust_mapping lc
                        where   lc.isdeleted=0
                        group by lc.customerno
                        having count(lc.ledgerid) = 1)
JOIN   `ledger_cust_mapping` j ON j.customerno = i.customerno AND j.isdeleted = 0
SET    i.ledgerid = j.ledgerid;


UPDATE `invoice` i
JOIN   `ledger` j ON j.ledgername LIKE concat('%',i.customername,'%')
JOIN   `ledger_cust_mapping` lc ON lc.ledgerid = j.ledgerid AND lc.isdeleted = 0
SET    i.ledgerid = j.ledgerid
WHERE  i.ledgerid = 0;


UPDATE  `invoice` 
SET     tax = 0 
WHERE   tax='NA';

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 505;