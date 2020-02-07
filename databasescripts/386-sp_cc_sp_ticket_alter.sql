ALTER TABLE `sp_ticket` ADD `send_mail_cc` VARCHAR(255) NOT NULL AFTER `send_mail_to`;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 386;
