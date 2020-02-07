-- Insert SQL here.

ALTER TABLE `client` CHANGE `add1` `add1` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 13, NOW(), 'Sanket Sheth','Address_Client');