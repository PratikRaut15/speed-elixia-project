ALTER TABLE `insurance`  ADD `customerno` INT(11) NOT NULL ;

ALTER TABLE `description`  ADD `customerno` INT(11) NOT NULL  AFTER `vehicleid`;

----SET customerno in description----------
update description as d  
INNER JOIN vehicle as v ON v.vehicleid=d.vehicleid
SET d.customerno=v.customerno;

----SET customerno in insurance--------
update insurance as i  
INNER JOIN vehicle as v ON v.vehicleid=d.vehicleid
SET i.customerno=v.customerno;

---- SET is_insured=1 in vehicle--------------
update vehicle as v  
INNER JOIN insurance as i ON i.vehicleid=v.vehicleid
SET v.is_insured=1;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (295, NOW(), 'Sahil Gandhi','alter in INSURANCE & description');
