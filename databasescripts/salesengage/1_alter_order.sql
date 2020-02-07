-- Insert SQL here.

ALTER TABLE `orders` ADD `lostnotes` TEXT DEFAULT NULL AFTER `totalamount`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 1, NOW(), 'Ganesh','alter Order table');
