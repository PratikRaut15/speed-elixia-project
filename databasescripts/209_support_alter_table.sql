-- Insert SQL here.

ALTER TABLE `sp_ticket`DROP `ticketid`;
ALTER TABLE `sp_ticket` ADD `ticketid` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE `sp_note` DROP `noteid`;
ALTER TABLE `sp_note` ADD `noteid` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE `sp_ticket_details` DROP `uid`;
ALTER TABLE `sp_ticket_details` ADD `uid` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 209, NOW(), 'Ganesh','Support tables primary changes');
