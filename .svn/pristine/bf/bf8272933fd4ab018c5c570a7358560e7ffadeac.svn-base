
ALTER TABLE `vehicle` ADD `sequenceno` INT(11) NOT NULL DEFAULT '0';

DELIMITER $$
DROP PROCEDURE IF EXISTS update_sequenceno$$
CREATE PROCEDURE update_sequenceno( 
	IN vehicleid_param INT (11),
	IN sequenceno_param INT(11)
)
BEGIN
update vehicle set sequenceno= sequenceno_param where vehicleid=vehicleid_param;
END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (322, NOW(), 'Ganesh Papde','Vehicle sequecing change');
