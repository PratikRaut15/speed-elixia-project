
ALTER TABLE `address` ADD `stateid` INT(11) NOT NULL AFTER `cityid`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (2, NOW(), 'Mrudang Vora','Add State Id in address');
