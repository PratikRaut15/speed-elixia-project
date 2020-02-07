-- Insert SQL here.


INSERT INTO `master_status` (`statusid`, `customerno`, `userid`, `statusname`, `isdeleted`, `timestamp`) VALUES
(1, 0, 0, 'Initiated', 0, '2014-11-10 19:03:50'),
(2, 0, 0, 'Out For Delivery', 0, '2014-11-10 19:04:14'),
(3, 0, 0, 'In-Transit', 0, '2014-11-10 00:00:00'),
(4, 0, 0, 'Pending', 0, '2014-11-10 19:05:04'),
(5, 0, 0, 'Delivered', 0, '2014-11-10 19:05:32'),
(6, 0, 0, 'Closed', 0, '2014-11-10 19:05:57');


INSERT INTO `master_reason` (`reasonid`, `customerno`, `userid`, `reason`, `isdeleted`, `timestamp`) VALUES
(1, 0, 0, 'No one at home', 0, '2014-11-03 13:08:25'),
(2, 0, 0, 'Package Damaged', 0, '2014-11-05 13:09:10'),
(3, 0, 0, 'Incorrect Address', 0, '2014-11-06 13:09:32');


ALTER TABLE `master_shipment` ADD `cancel_reason` VARCHAR( 11 ) NOT NULL AFTER `shipping_status`;


ALTER TABLE `master_shipment` ADD `eta` DATETIME NOT NULL AFTER `cancel_reason` ;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 2, NOW(), 'Shrikanth Suryawanshi','Delivery Status');
