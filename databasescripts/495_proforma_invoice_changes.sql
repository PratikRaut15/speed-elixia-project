INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('495', '2017-04-19 17:29:00', 'Arvind Thakur', 'proforma invoice changes', '0');



create table cash_memo(
    `cmid` INT(11) PRIMARY KEY AUTO_INCREMENT,
    `cash_memo_no` varchar(40),
    `customerno` INT(11), 
    `cm_date` date,
    `cm_amount` float,
    `status` TINYINT(2),
    `pending_amt` float,
    `paid_amount` float,	
    `paymentdate` date,
    `isdeleted` TINYINT(2) DEFAULT 0,
    `address` VARCHAR(250)
    );

CREATE TABLE IF NOT EXISTS `proforma_invoice` (
`pi_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `invoiceno` varchar(40) NOT NULL,
  `customerno` int(11) NOT NULL,
  `ledgerid` int(11) NOT NULL,
  `clientname` varchar(40) NOT NULL,
  `inv_date` date NOT NULL,
  `inv_amt` float NOT NULL,
  `status` varchar(40) NOT NULL,
  `pending_amt` float NOT NULL,
  `tax` varchar(40) NOT NULL,
  `tax_amt` float NOT NULL,
  `pay_mode` varchar(40) NOT NULL,
  `paid_amt` float NOT NULL,
  `paymentdate` date NOT NULL,
  `tds_amt` float NOT NULL,
  `unpaid_amt` float NOT NULL,
  `inv_expiry` date NOT NULL,
  `comment` varchar(50) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `product_id` int(11) DEFAULT '0',
  `is_taxed` TINYINT(1) DEFAULT '0'
);


create table `product`(
	`id` INT(11) PRIMARY KEY,
	`name` varchar(50));

INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('1', 'Elixia Speed  - Device');
INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('2', 'Elixia Speed - Subscription');
INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('3', 'Elixia Enterprise');
INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('4', 'Elixia Fleet');
INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('5', 'Elixia Monitor - Device');
INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('6', 'Elixia Monitor - Subscription');
INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('7', 'Elixia Docs');
INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('8', 'Elixia Trace - Device');
INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('9', 'Elixia Trace - Subscription');
INSERT INTO `speed`.`product` (`id`, `name`) VALUES ('10', 'Elixia Sales');

alter table `invoice`
add column `product_id` INT(11) DEFAULT 0;

alter table `otherinvoices`
add column `product_id` INT(11) DEFAULT 0;

alter table `cash_memo`
add column `product_id` INT(11) DEFAULT 0;


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 495;