SET @patchId = 751;
SET @patchDate = '2020-02-04 12:29:00';
SET @patchOwner = 'Amit Tiwari';
SET @patchDescription = 'create table for invoices';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

CREATE TABLE `invoice_approval` (
  `iv_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceno` varchar(40) NOT NULL,
  `customerno` int(11) NOT NULL,
  `ledgerid` int(11) NOT NULL,
  `inv_date` datetime DEFAULT NULL,
  `inv_amt` float NOT NULL,
  `status` varchar(40) NOT NULL,
  `pending_amt` float DEFAULT NULL,
  `tax` tinyint(2) DEFAULT NULL,
  `tax_amt` float DEFAULT NULL,
  `pay_mode` varchar(40) DEFAULT NULL,
  `paid_amt` float DEFAULT NULL,
  `subscription_price` float DEFAULT NULL,
  `paymentdate` date DEFAULT NULL,
  `tds_amt` float DEFAULT NULL,
  `unpaid_amt` float DEFAULT NULL,
  `inv_expiry` datetime NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT NULL,
  `product_id` int(11) DEFAULT '0',
  `customername` varchar(255) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `is_mail_sent` tinyint(1) DEFAULT '1',
  `quantity` int(11) DEFAULT NULL,
  `cgst` int(11) DEFAULT '0',
  `sgst` int(11) DEFAULT '0',
  `igst` int(11) DEFAULT '0',
  `total_amount` float DEFAULT NULL,
  `tax_amount` float DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `lease` varchar(50) DEFAULT NULL,
  `misc` varchar(255) DEFAULT NULL,
  `proforma_id` int(11) DEFAULT NULL,
  `is_baddebt` tinyint(1) DEFAULT NULL,
  `isapproved` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`iv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19440 DEFAULT CHARSET=latin1

ALTER TABLE `invoice`  ADD `total_amount` FLOAT NULL DEFAULT NULL  AFTER `igst`;
ALTER TABLE `invoice`  ADD `tax_amount` FLOAT NULL DEFAULT NULL  AFTER `total_amount`;
ALTER TABLE `invoice`  ADD `state` varchar(100) NULL DEFAULT NULL  AFTER `tax_amount`;
ALTER TABLE `invoice`  ADD `lease` varchar(50) NULL DEFAULT NULL  AFTER `state`;


ALTER TABLE `customer`  ADD `invoiceHoldStatus` tinyint(4) DEFAULT '0'  AFTER `use_crm`;
ALTER TABLE `customer`  ADD `extended_usage` int(11) NULL DEFAULT NULL  AFTER `invoiceHoldStatus`;
ALTER TABLE `customer`  ADD `generate_invoice_month` enum('next','previous','same') NULL DEFAULT NULL  AFTER `extended_usage`;
ALTER TABLE `customer`  ADD `currentMonthInv` tinyint(4) DEFAULT '0'  AFTER `generate_invoice_month`;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;