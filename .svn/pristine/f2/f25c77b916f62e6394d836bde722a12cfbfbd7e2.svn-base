
-- Insert SQL here.

ALTER TABLE `new_sales` DROP `orderid`;
ALTER TABLE `new_sales` ADD `orderid` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 225, NOW(), 'Ganesh','alter newsales primary key or auto increment');
