-- Insert SQL here.

ALTER TABLE `new_sales` CHANGE `contactno` `contactno` VARCHAR(50) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 231, NOW(), 'Ganesh Papde','alter contact number to varchar');
