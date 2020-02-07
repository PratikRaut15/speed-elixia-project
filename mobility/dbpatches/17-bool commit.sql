ALTER TABLE `discount` CHANGE `isupgrade` `isupgrade` BOOL NOT NULL ;

ALTER TABLE `discount` CHANGE `is_mass` `is_mass` BOOL NOT NULL ;

ALTER TABLE `discount` CHANGE `isdeleted` `isdeleted` BOOL NOT NULL ;


ALTER TABLE `discount` CHANGE `ispercent` `ispercent` BOOL NOT NULL ;


 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 17, NOW(), 'vishwanath','bool');