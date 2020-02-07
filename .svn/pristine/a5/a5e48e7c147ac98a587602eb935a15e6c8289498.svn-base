ALTER TABLE `sp_ticket` ADD `estimateddate` DATE NOT NULL AFTER `sub_ticket_issue`, ADD `timeslot` VARCHAR(100) NOT NULL AFTER `estimateddate`, ADD `vehicleid` INT(11) NOT NULL AFTER `timeslot`;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (306, NOW(), 'Ganesh','SP ticket table alter');
