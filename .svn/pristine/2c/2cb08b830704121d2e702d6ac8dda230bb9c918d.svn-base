INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('506','2017-05-24 14:30:00','Arvind Thakur','Credit note data import','0');

UPDATE  `invoice`
SET     `tax` = '1'
WHERE   `tax` LIKE 'ST';

UPDATE  `invoice`
SET     `tax` = '2'
WHERE   `tax` LIKE 'VAT';

UPDATE  `invoice`
SET     `tax` = '3'
WHERE   `tax` LIKE 'CST';

ALTER TABLE `invoice`
MODIFY `tax` TINYINT(2);

UPDATE  `invoice`
SET     product_id = 1
WHERE   tax = 2;

UPDATE  `invoice`
SET     product_id = 2
WHERE   tax = 1;

ALTER TABLE `credit_note` MODIFY `tax` TINYINT(2);

ALTER TABLE `credit_note`
ADD COLUMN `status` varchar(40),
ADD COLUMN `start_date` DATE,
ADD COLUMN `end_date` DATE;

insert into credit_note(`invoiceno`
            ,`customerno`
            ,`ledgerid`
            ,`inv_date`
            ,`inv_amt`
            ,`status`
            ,`tax`
            ,`tax_amt`
            ,`tds_amt`
            ,`inv_expiry`
            ,`isdeleted`
            ,`product_id`
            ,`start_date`
            ,`end_date`) 
select      `invoiceno`
            ,`customerno`
            ,`ledgerid`
            ,`inv_date`
            ,`inv_amt`
            ,`status`
            ,`tax`
            ,`tax_amt`
            ,`tds_amt`
            ,`inv_expiry`
            ,`isdeleted`
            ,`product_id`
            ,`start_date`
            ,`end_date` 
from        invoice 
where       inv_amt < 0 
order by    inv_date asc;

DELETE FROM `invoice` WHERE `inv_amt` < 0;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 506;