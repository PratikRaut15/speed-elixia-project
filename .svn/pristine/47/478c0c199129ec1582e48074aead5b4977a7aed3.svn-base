-- Insert SQL here.


ALTER TABLE unit add extra_digital tinyint(1) NOT NULL AFTER digitalio;
ALTER TABLE unit add extra_digitalioupdated datetime NOT NULL AFTER digitalioupdated;
ALTER TABLE customer add use_extradigital tinyint(1) NOT NULL AFTER use_fuel_sensor;

INSERT INTO `customtype` (`id`, `name`) VALUES (NULL, 'Digital-1'), (NULL, 'Digital-2');
INSERT INTO `customtype` (`id`, `name`) VALUES (NULL, 'Digital-3'), (NULL, 'Digital-4');



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 240, NOW(), 'Shrikant Suryawanshi','Extra Digital');
