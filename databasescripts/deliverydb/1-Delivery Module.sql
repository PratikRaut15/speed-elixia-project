-- Insert SQL here.


CREATE TABLE `master_billing_address` (
  `add_id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `address` varchar(150) NOT NULL,
  `city` varchar(40) NOT NULL,
  `state` varchar(40) NOT NULL,
  `country` varchar(30) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `phone` varchar(15) NOT NULL,
  PRIMARY KEY (`add_id`)
) ENGINE=MyISAM ;





CREATE TABLE `master_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `amount` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

CREATE TABLE  `master_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(150) NOT NULL,
  `orderid` varchar(64) NOT NULL,
  `route` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  ;

CREATE TABLE `master_item` (
  `itemid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `product_id` varchar(64) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `product_price` varchar(11) NOT NULL,
  `discount_total` varchar(11) NOT NULL,
  `quantity` varchar(11) NOT NULL,
  `sub_total` varchar(11) NOT NULL,
  `taxes_total` varchar(11) NOT NULL,
  `total` varchar(11) NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM ;


CREATE TABLE `master_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(25) NOT NULL,
  `customerno` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `currency` varchar(15) NOT NULL,
  `financial_status` varchar(25) NOT NULL,
  `item_count` varchar(11) NOT NULL,
  `sub_total` varchar(11) NOT NULL,
  `taxes_total` varchar(11) NOT NULL,
  `discount_total` varchar(11) NOT NULL,
  `total` varchar(11) NOT NULL,
  `status` varchar(25) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  ;

CREATE TABLE `master_paymentdetails` (
  `paymentdetail_id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` varchar(30) NOT NULL,
  `type` varchar(25) NOT NULL,
  `verification_code` varchar(64) NOT NULL,
  `verified` varchar(10) NOT NULL,
  `gateway` varchar(50) NOT NULL,
  `name_on_card` varchar(30) NOT NULL,
  `card_number` varchar(20) NOT NULL,
  `bank_ref_number` varchar(30) NOT NULL,
  PRIMARY KEY (`paymentdetail_id`)
) ENGINE=MyISAM ;

CREATE TABLE `master_paymentmethod` (
  `paymentid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `gateway` varchar(150) NOT NULL,
  PRIMARY KEY (`paymentid`)
) ENGINE=MyISAM ;


CREATE TABLE `master_reason` (
  `reasonid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `reason` text NOT NULL,
  `isdeleted` tinyint(4) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`reasonid`)
) ENGINE=MyISAM  ;


CREATE TABLE `master_shipment` (
  `shipmentid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `tracking_number` varchar(20) NOT NULL,
  `shipping_status` varchar(25) NOT NULL,
  `service` varchar(25) NOT NULL,
  `price` varchar(11) NOT NULL,
  `return_cod_lebel` varchar(50) NOT NULL,
  `shipping_lebel` varchar(50) NOT NULL,
  `route` varchar(50) NOT NULL,
  PRIMARY KEY (`shipmentid`)
) ENGINE=MyISAM ;


CREATE TABLE `master_shipping_address` (
  `add_id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `address` varchar(150) NOT NULL,
  `city` varchar(40) NOT NULL,
  `state` varchar(40) NOT NULL,
  `country` varchar(30) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `phone` varchar(15) NOT NULL,
  PRIMARY KEY (`add_id`)
) ENGINE=MyISAM ;

CREATE TABLE `master_status` (
  `statusid` int(11) NOT NULL AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `statusname` varchar(150) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`statusid`)
) ENGINE=MyISAM ;


CREATE TABLE `master_taxes` (
  `taxid` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `amount` varchar(11) NOT NULL,
  PRIMARY KEY (`taxid`)
) ENGINE=MyISAM ;


CREATE TABLE IF NOT EXISTS `dbpatches` (
  `patchid` int(11) NOT NULL,
  `patchdate` datetime NOT NULL,
  `appliedby` varchar(20) NOT NULL,
  `patchdesc` varchar(255) NOT NULL,
  PRIMARY KEY (`patchid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 1, NOW(), 'Shrikanth Suryawanshi','Delivery Module');
