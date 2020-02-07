-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `master_orders_dummy` (
`id` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `customerno` int(11) NOT NULL,
  `sales_type` varchar(35) NOT NULL,
  `ref_number` varchar(35) NOT NULL,
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
  `production_details` text NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `slot` int(11) NOT NULL,
  `lat` varchar(20) NOT NULL,
  `longi` varchar(20) NOT NULL,
  `accuracy` varchar(20) NOT NULL,
  `fenceid` varchar(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `master_shipment_dummy` (
`shipmentid` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `provider` varchar(50) NOT NULL,
  `tracking_number` varchar(20) NOT NULL,
  `transporter_name` varchar(50) NOT NULL,
  `lr_no` varchar(25) NOT NULL,
  `lr_date` datetime NOT NULL,
  `shipping_status` varchar(25) NOT NULL,
  `cancel_reason` varchar(11) NOT NULL,
  `eta` datetime NOT NULL,
  `service` varchar(25) NOT NULL,
  `price` varchar(11) NOT NULL,
  `return_cod_lebel` varchar(50) NOT NULL,
  `shipping_lebel` varchar(50) NOT NULL,
  `route` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `master_shipping_address_dummy` (
`add_id` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `order_id` varchar(25) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `address` varchar(150) NOT NULL,
  `city` varchar(40) NOT NULL,
  `state` varchar(40) NOT NULL,
  `country` varchar(30) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `shipcode` varchar(25) NOT NULL,
  `landmark` varchar(150) NOT NULL,
  `area` varchar(50) NOT NULL,
  `address_main` varchar(250) NOT NULL,
  `flag` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


ALTER TABLE `master_orders_dummy`
 ADD PRIMARY KEY (`id`);


ALTER TABLE `master_shipment_dummy`
 ADD PRIMARY KEY (`shipmentid`);


ALTER TABLE `master_shipping_address_dummy`
 ADD PRIMARY KEY (`add_id`);



ALTER TABLE `master_orders_dummy`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `master_shipment_dummy`
MODIFY `shipmentid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

ALTER TABLE `master_shipping_address_dummy`
MODIFY `add_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 5, NOW(), 'Shrikanth Suryawanshi','Delivery localbaniya dummy tables');
