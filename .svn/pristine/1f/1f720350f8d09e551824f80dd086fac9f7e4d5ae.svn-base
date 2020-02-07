ALTER TABLE `sp_ticket_details` ADD `allot_from` INT(11) NOT NULL AFTER `description`;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 382 


