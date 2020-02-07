-- Insert SQL here.

ALTER TABLE `fence` ADD `isdeleted` BOOL NOT NULL ;
ALTER TABLE `fenceman` ADD `isdeleted` BOOL NOT NULL ;
ALTER TABLE `geofence` ADD `isdeleted` BOOL NOT NULL ;
ALTER TABLE `checkpoint` ADD `isdeleted` BOOL NOT NULL ;
ALTER TABLE `checkpointmanage` ADD `isdeleted` BOOL NOT NULL ;
ALTER TABLE `article` ADD `isdeleted` BOOL NOT NULL ;
ALTER TABLE `articlemanage` ADD `isdeleted` BOOL NOT NULL ;
ALTER TABLE `ecodeman` ADD `isdeleted` BOOL NOT NULL ;
ALTER TABLE `elixiacode` ADD `isdeleted` BOOL NOT NULL ;

ALTER TABLE `geofence` ADD `userid` INT( 11 ) NOT NULL ;
ALTER TABLE `unit` ADD `userid` INT( 11 ) NOT NULL ;

ALTER TABLE `user` ADD `modifiedby` INT( 11 ) NOT NULL ,
ADD `isdeleted` BOOL NOT NULL ;

ALTER TABLE `unit` DROP `isdeleted` ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 4, NOW(), 'Sanket Sheth','Extra Fields');
