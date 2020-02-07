-- Insert SQL here.

ALTER TABLE `unit` ADD `unitprice` INT(11) NOT NULL AFTER `mobiliser_flag`;
ALTER TABLE `unit` ADD `monthlysub_cost` INT(20) NOT NULL AFTER `unitprice`;
ALTER TABLE `customer` ADD `unitprice` INT(20) NOT NULL AFTER `rel_manager`, ADD `unit_msp` INT(20) NOT NULL AFTER `unitprice`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 242, NOW(), 'Ganesh Papde','alter unit and customer table save unit price /monthly subscription price');
