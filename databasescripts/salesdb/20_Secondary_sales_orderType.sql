INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc)
VALUES ('20', '2018-05-28 11:00:00', 'Sanjeet Shukla', 'Created Order type table for secondry sales');



CREATE TABLE `orderType` (
  `otId` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `orderTypeTitle` VARCHAR(100) NOT NULL,
`customerNo` int(10) NOT NULL,
  `createdBy` int(10) NOT NULL,
  `createdOn` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `updatedOn` datetime NOT NULL,
  `isDeleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO orderType ( orderTypeTitle, customerNo, createdBy, createdOn) VALUES ('Normal', '193', '374', '2018-05-25 16:00:00');
INSERT INTO orderType ( orderTypeTitle, customerNo, createdBy, createdOn) VALUES ('Sample', '193', '374', '2018-05-25 16:00:00');
INSERT INTO orderType ( orderTypeTitle, customerNo, createdBy, createdOn) VALUES ('Factory Pickup', '193', '374', '2018-05-25 16:00:00');


ALTER TABLE `deadstock` ADD `ordertypeid` INT NOT NULL AFTER `quantity`;


ALTER TABLE `secondary_order` ADD `ordertypeid` INT NOT NULL AFTER `shopid`;


