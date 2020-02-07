INSERT INTO `speed`.`role` (`id`, `role`) VALUES (NULL, 'ASM');
INSERT INTO `speed`.`role` (`id`, `role`) VALUES (NULL, 'sales_representative');

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (294, NOW(), 'Ganesh','alter role table');
