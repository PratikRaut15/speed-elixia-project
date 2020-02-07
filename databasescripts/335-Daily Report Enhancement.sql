-- Insert SQL here.

ALTER TABLE `dailyreport` ADD `topspeed_time` DATETIME NOT NULL ,
ADD `night_first_odometer` BIGINT( 15 ) NOT NULL ,
ADD `weekend_first_odometer` BIGINT( 15 ) NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 335, NOW(), 'Sanket Sheth','Daily Report Enhancement');
