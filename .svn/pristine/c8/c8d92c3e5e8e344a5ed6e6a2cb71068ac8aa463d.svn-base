/**
# Author: Ranjeet Kasture
# Date created: 28-05-2019
# Date pushed to UAT: 28-05-2019
# Description:
# Table creation and data creation for vehicle common status on RTD page in status column 
# Created vehicle_common_status_master
# 
#
***/

/* Create dbpatch */
    INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`, `isapplied`, `updatedOn`) VALUES ('714', '2019-05-28 13:25:00', 'Ranjeet K', 'Table creation and data creation for vehicle common status on RTD page in status column', '0', '2019-05-28 13:26:00');


/* RTD vehicle status columns changes */

/* Create table  vehicle_common_status_master */
    CREATE TABLE `vehicle_common_status_master` ( `id` INT NOT NULL AUTO_INCREMENT ,  `status` VARCHAR(555) NOT NULL COMMENT 'These are different statuses which are defined by users. Common statuses will be \'Available\' and \'Under Maintenance\' which will have customerno as 0' ,  `color_code` VARCHAR(255) NOT NULL COMMENT 'color code defined by user to display respective color on web page' ,  `customerno` INT NOT NULL COMMENT 'References to customerno from customer table' ,  `createdBy` INT NOT NULL COMMENT 'References to userid from user table ' ,  `createdOn` DATETIME NOT NULL ,  `updatedBy` INT NOT NULL COMMENT 'This column refers to userid from user table' ,  `updatedOn` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,  `isDeleted` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '\'0\' => \'not deleted\', \'1\'=>\'deleted\'' ,    PRIMARY KEY  (`id`)) ENGINE = InnoDB;

/* Add indexes on vehicle_common_status_master table */    
	ALTER TABLE `vehicle_common_status_master` ADD INDEX( `customerno`, `isDeleted`);

/* Adding column as vehicle_status in vehicle table */
   ALTER TABLE `vehicle` ADD `vehicle_status` INT NOT NULL COMMENT 'References to id from \'vehicle_common_status_master\' table. While showing statues common status will be also shown through adding where clause with customerno =0 from vehicle_common_status_master table' AFTER `vehicleType`;

/* Creating records in  vehicle_common_status_master */
  INSERT INTO `vehicle_common_status_master` (`status`, `color_code`, `customerno`, `isDeleted`) VALUES ('Available', '#008000', '0', '0');
  INSERT INTO `vehicle_common_status_master` (`status`, `color_code`, `customerno`, `isDeleted`) VALUES ('Under Maintenance', '#FF0000', '0', '0');
 
/* Creating records in  vehicle_common_status_master for Mahalakshmi Enterprises */
INSERT INTO `vehicle_common_status_master` (`status`, `color_code`, `customerno`, `createdOn`, `updatedOn`, `isDeleted`) VALUES ('Loading in the process', '#0000FF' , '546', '2019-05-28 13:10:47', '2019-05-28 13:10:47', '0');
INSERT INTO `vehicle_common_status_master` (`status`, `color_code`, `customerno`, `createdOn`, `updatedOn`, `isDeleted`) VALUES ('unloading in the process', '#FFFF00', '546', '2019-05-28 13:10:47', '2019-05-28 13:10:47', '0');
INSERT INTO `vehicle_common_status_master` (`status`, `color_code`, `customerno`, `createdOn`, `updatedOn`, `isDeleted`) VALUES ('waiting for unloading', '#008080' ,'546', '2019-05-28 13:10:47', '2019-05-28 13:10:47', '0');
INSERT INTO `vehicle_common_status_master` (`status`, `color_code`, `customerno`, `createdOn`, `updatedOn`, `isDeleted`) VALUES ('waiting for return load', '#800080', '546', '2019-05-28 13:10:47', '2019-05-28 13:10:47', '0');




/* Update all vehciles with default vehcile status as 1 (Available) */
update vehicle set vehicle_status = 1;

/* Alter table vehicle for default vehicle status id as 1 'Available' */
ALTER TABLE `vehicle` CHANGE `vehicle_status` `vehicle_status` INT(11) NOT NULL DEFAULT '1' COMMENT 'References to id from \'vehicle_common_status_master\' table. While showing statues common status will be also shown through adding where clause with customerno =0 from vehicle_common_status_master table';

/* Updating dbpatche 714 */
    UPDATE  dbpatches
    SET     patchdate = '2019-05-28 13:26:00',isapplied =1
    WHERE   patchid = 714;

