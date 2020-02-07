-- Insert SQL here.

ALTER TABLE `geotest` ADD `latfloor` INT( 11 ) NOT NULL ;
ALTER TABLE `geotest` ADD `longfloor` INT( 11 ) NOT NULL ;

UPDATE `geotest` SET `latfloor` = FLOOR(`lat`) ;
UPDATE `geotest` SET `longfloor` = FLOOR(`long`) ;

ALTER TABLE `geotest` ADD INDEX ( `latfloor` ) ;
ALTER TABLE `geotest` ADD INDEX ( `longfloor` ) ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 43, NOW(), 'Sanket Sheth','LatLongIndex');
