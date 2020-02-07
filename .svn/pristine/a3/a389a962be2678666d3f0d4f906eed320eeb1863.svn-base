

ALTER TABLE `sp_ticket` CHANGE `timeslot` `timeslot` INT(11) NOT NULL;

update sp_ticket set ticket_type=4 where ticket_type='Sales';
update sp_ticket set ticket_type=1 where ticket_type='Accounts';
update sp_ticket set ticket_type=2 where ticket_type='Operation';
update sp_ticket set ticket_type=6 where ticket_type='Software';
update sp_ticket set ticket_type=5 where ticket_type='Support';

update sp_ticket set priority=1 where priority='High';
update sp_ticket set priority=2 where priority='Moderate';
update sp_ticket set priority=3 where priority='Low';

ALTER TABLE `sp_ticket` CHANGE `ticket_type` `ticket_type` INT(11) NOT NULL, CHANGE `priority` `priority` INT(11) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (307, NOW(), 'Ganesh','SP Ticket table changes');




