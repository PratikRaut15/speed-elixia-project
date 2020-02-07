ALTER TABLE `customer`  ADD `lease_duration` INT NOT NULL ,  ADD `lease_price` INT NOT NULL ;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 380;
