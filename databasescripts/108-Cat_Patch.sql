-- Insert SQL here.

ALTER TABLE `user` ADD `approval_cat_filter` INT NOT NULL ;
ALTER TABLE `user` CHANGE `approval_cat_filter` `approval_cat_filter` INT( 11 ) NOT NULL DEFAULT '-1';
UPDATE user SET `approval_cat_filter` = -1;

ALTER TABLE `maintenance` ADD `ofasno` VARCHAR( 50 ) NOT NULL ;
ALTER TABLE `maintenance_history` ADD `ofasno` VARCHAR( 50 ) NOT NULL ;

INSERT INTO `maintenance_status` (`id`, `name`) VALUES
(7, 'Pending for Quotation Approval'),
(8, 'Quotation Approved'),
(9, 'Quotation Rejected'),
(10, 'Pending for Final Payment Approval'),
(11, 'Pending for Quotation Cancellation'),
(12, 'Quotation Cancelled'),
(13, 'Approved for Final Payment'),
(14, 'Closed');

ALTER TABLE `maintenance` ADD `payment_approval_date` DATETIME NOT NULL ;
ALTER TABLE `maintenance_history` ADD `payment_approval_date` DATETIME NOT NULL ;

ALTER TABLE `maintenance` ADD `payment_submission_date` DATETIME NOT NULL ;
ALTER TABLE `maintenance_history` ADD `payment_submission_date` DATETIME NOT NULL ;

ALTER TABLE `maintenance` CHANGE `timestamp` `timestamp` DATETIME NOT NULL ;
ALTER TABLE `maintenance_history` CHANGE `timestamp` `timestamp` DATETIME NOT NULL ;

ALTER TABLE `dealer` ADD `cityid` INT( 11 ) NOT NULL ;

ALTER TABLE `user` DROP `approval_cat_filter` ;

CREATE TABLE `accessory` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 50 ) NOT NULL ,
`max_amount` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`isdeleted` TINYINT( 1 ) NOT NULL ,
`timestamp` DATETIME NOT NULL
) ENGINE = MYISAM ;

INSERT INTO `accessory` (`id`, `name`, `max_amount`, `customerno`, `userid`, `isdeleted`, `timestamp`) VALUES (NULL, 'Mat', '500', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'High quality Sun film', '500', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Seat Covers', '1000', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Audio System', '5000', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Air-freshener', '2000', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Remote-locking security system', '7000', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Steering Grip lock', '500', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Gear Lock', '400', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Headlight bulb Upgrade', '2000', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Mirror Lock', '1000', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Fire Extinguisher', '4500', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Tyre / Puncture repair kit', '3000', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Fog lights', '2000', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Extra fuses', '1500', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Alloy Wheels', '12000', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Car Cover', '2500', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Child Seat', '1300', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Parking Sensors', '1500', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'High quality cleaning cloth', '750', '0', '', '', '0000-00-00 00:00:00'), (NULL, 'Branded wax polish', '550', '0', '', '', '0000-00-00 00:00:00');

ALTER TABLE `accident` DROP `vehicle_in_time` ,
DROP `vehicle_out_time` ,
DROP `meter_reading` ,
DROP `approval_notes` ;

ALTER TABLE `accident_history` DROP `vehicle_in_time` ,
DROP `vehicle_out_time` ,
DROP `meter_reading` ,
DROP `approval_notes` ;

ALTER TABLE `accident` ADD `approval_notes` VARCHAR( 50 ) NOT NULL ;

ALTER TABLE `accident_history` ADD `approval_notes` VARCHAR( 50 ) NOT NULL ;

DROP TABLE `maintenance_map` ;

CREATE TABLE `accessory_map` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`maintenanceid` INT( 11 ) NOT NULL ,
`accessoryid` INT( 11 ) NOT NULL ,
`cost` INT( 11 ) NOT NULL ,
`customerno` INT( 11 ) NOT NULL ,
`timestamp` DATETIME NOT NULL ,
`userid` INT( 11 ) NOT NULL ,
`isdeleted` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `eventalerts` ADD `ac_count` INT( 11 ) NOT NULL ,
ADD `ac_time` DATETIME NOT NULL ;

ALTER TABLE `accident` ADD `ofasnumber` INT NOT NULL ;
ALTER TABLE `accident_history` ADD `ofasnumber` INT NOT NULL ;

ALTER TABLE `maintenance` ADD `payment_approval_note` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `maintenance_history` ADD `payment_approval_note` VARCHAR( 255 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 108, NOW(), 'Sanket Sheth','Cat Patch');
