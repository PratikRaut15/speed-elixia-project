
ALTER TABLE `sp_ticket` CHANGE `vehicleid` `vehicleid` VARCHAR(255) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 384;
