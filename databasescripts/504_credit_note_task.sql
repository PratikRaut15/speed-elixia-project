INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('504', '2017-05-16 11:29:00', 'Arvind Thakur', 'Invoice Task', '0');

DROP TABLE IF EXISTS `proforma_invoice`;

CREATE TABLE IF NOT EXISTS `proforma_invoice` (
`pi_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `invoiceno` varchar(40) NOT NULL,
  `customerno` int(11) NOT NULL,
  `ledgerid` int(11) NOT NULL,
  `inv_date` date NOT NULL,
  `invtype` TINYINT(2),
  `inv_amt` float NOT NULL,
  `tax` varchar(40) NOT NULL,
  `tax_amt` float NOT NULL,
  `payment_due_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `comment` varchar(50) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `product_id` int(11) DEFAULT '0',
  `is_taxed` TINYINT(1) DEFAULT '0',
  `approved` TINYINT(1) DEFAULT '0',
  `finaldate` DATE
);


DROP TABLE IF EXISTS `credit_note`;
 
CREATE TABLE `credit_note` (
  `invoiceid` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceno` varchar(40) NOT NULL,
  `customerno` int(11) NOT NULL,
  `ledgerid` int(11) NOT NULL,
  `inv_date` date NOT NULL,
  `inv_amt` float NOT NULL,
  `tax` varchar(40) NOT NULL,
  `tax_amt` float NOT NULL,
  `paymentdate` date NOT NULL,
  `tds_amt` float NOT NULL,
  `inv_expiry` date NOT NULL,
  `comment` varchar(50) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `product_id` int(11) DEFAULT '0',
  `approved` TINYINT(1) DEFAULT '0',
  PRIMARY KEY (`invoiceid`));


ALTER TABLE `cash_memo`
ADD COLUMN `ledgerid` INT(11);

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 504;
