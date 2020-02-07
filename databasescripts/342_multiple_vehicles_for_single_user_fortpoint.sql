
DELIMITER $$
DROP PROCEDURE IF EXISTS check_vehicle_user_mapping$$
CREATE PROCEDURE `check_vehicle_user_mapping`(
	IN useridparam INT
    , OUT groupidparam INT
    , OUT isUserMappedToVehicle TINYINT(1)
)
BEGIN
			SET isUserMappedToVehicle = 0;
            SET groupidparam = -1;
            
			/* Check whether any existing user has the group */
            SELECT	groupid
            INTO	groupidparam
			FROM	groupman
			WHERE 	userid = useridparam
			AND 	isdeleted = 0;
            
            IF (groupidparam IS NOT NULL AND groupidparam != -1) THEN
				SET isUserMappedToVehicle = 1;
			END IF;
END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 342, NOW(), 'Mrudang Vora','multiple vehicles for single user fortpoint');

