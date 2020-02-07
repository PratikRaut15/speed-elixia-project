-- Insert SQL here.

ALTER TABLE `discount_specific` ADD `isdeleted` TINYINT(1) NOT NULL DEFAULT '0' ;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 20, NOW(), 'Ganesh','Discount specific add isdeleted');



