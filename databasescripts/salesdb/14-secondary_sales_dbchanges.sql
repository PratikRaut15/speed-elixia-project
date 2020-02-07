-- Insert SQL here.


CREATE TABLE IF NOT EXISTS `primary_order_productlist` (
            `poid` int(11) NOT NULL,
              `stockid` int(11) NOT NULL,
              `categoryid` int(11) NOT NULL,
              `skuid` int(11) NOT NULL,
              `quantity` int(11) NOT NULL,
              `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
              PRIMARY KEY (`poid`)
            );
 
RENAME TABLE stock TO primary_order;
 
ALTER TABLE `primary_order_productlist` CHANGE `stockid` `prid` INT( 11 ) NOT NULL;
 
ALTER TABLE `primary_order` CHANGE `stockid` `prid` INT( 11 ) NOT NULL AUTO_INCREMENT;
 
ALTER TABLE `primary_order` DROP `srid` ,
DROP `styleid` ,
DROP `quantity` ,
DROP `cartons` ,
DROP `entrytime` ,
DROP `addedby` ,
DROP `updatedtime` ,
DROP `updated_by` ;
 
ALTER TABLE `primary_order` CHANGE `stockdate` `entrydate` DATETIME NOT NULL;
 
ALTER TABLE `primary_order_productlist` CHANGE `poid` `poid` INT( 11 ) NOT NULL AUTO_INCREMENT;
 
ALTER TABLE `primary_order_productlist` DROP PRIMARY KEY, ADD PRIMARY KEY(`poid`);
 
ALTER TABLE `primary_order` DROP `status`;
 
ALTER TABLE `primary_order_productlist` ADD `status` TINYINT NOT NULL;
 
ALTER TABLE `primary_order_productlist` ADD `entrydate` DATETIME NOT NULL;
 
ALTER TABLE `primary_order_productlist` ADD `customerno` INT NOT NULL;
 
ALTER TABLE `primary_order` ADD `addedby` INT NOT NULL;
 
ALTER TABLE `primary_order` ADD `updated_by` INT NOT NULL ,
ADD `updated_time` DATETIME NOT NULL;
 
ALTER TABLE `shop` ADD `sequence_no` INT NOT NULL;
 
RENAME TABLE orders TO secondary_order ;
 
            CREATE TABLE IF NOT EXISTS `secondary_order_productlist` (
            `sopid` int(11) NOT NULL,
              `orderid` int(11) NOT NULL,
             `categoryid` int(11) NOT NULL,
              `skuid` int(11) NOT NULL,
              `quantity` int(11) NOT NULL,
              `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
              PRIMARY KEY (`sopid`)
            );
 
ALTER TABLE `secondary_order_productlist` CHANGE `orderid` `soid` INT( 11 ) NOT NULL;
 
ALTER TABLE `secondary_order_productlist` ADD `status` TINYINT NOT NULL ,
ADD `entrydate` DATETIME NOT NULL ,
ADD `customerno` INT NOT NULL;
 
ALTER TABLE `secondary_order` CHANGE `orderid` `soid` INT( 11 ) NOT NULL AUTO_INCREMENT;
 
ALTER TABLE `secondary_order`
  DROP `salesid`,
  DROP `distributorid`,
  DROP `areaid`,
  DROP `styleid`,
  DROP `quantity`,
  DROP `status`;
 
ALTER TABLE `secondary_order`
  DROP `catid`;
 
ALTER TABLE `secondary_order`
  DROP `orderdate`;
 
ALTER TABLE `secondary_order` ADD `is_asm` TINYINT NOT NULL;
 
ALTER TABLE `secondary_order` CHANGE `entrytime` `entrydate` DATETIME NOT NULL;
 
ALTER TABLE `secondary_order` ADD `discount` INT NOT NULL ,
ADD `reason` VARCHAR( 30 ) NOT NULL;
 
ALTER TABLE `secondary_order` ADD `is_deadstock` TINYINT NOT NULL;



-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 14, NOW(), 'Ganesh','secondary sales db changes for api /web');




