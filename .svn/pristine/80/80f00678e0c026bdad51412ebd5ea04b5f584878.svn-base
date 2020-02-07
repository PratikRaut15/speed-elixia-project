/*
    Name			-	check_vehicle_user_mapping
    Description 	-	To check the user is mapped to any vehicle
    Parameters		-	useridparam, OUT groupidOut,  OUT isUserMappedToVehicle
    Module			-	Vehicle Tracking System
    Sub-Modules 	- 	Driver Mapping APP
    Sample Call		-	CALL check_vehicle_user_mapping('1515',@groupidOut,@isUserMappedToVehicle);
						SELECT @groupidOut,@isUserMappedToVehicle;
    Created by		-	Mrudang
    Created on		- 	6 Nov, 2015
    Change details 	-	
    1) 	Updated by	- 	Mrudang
        Updated	on	- 	2 Jan, 2016
        Reason		-	groupidOut not checked for -1.

    2) 	Updated by	- 	Arvind
        Updated	on	- 	21 Dec, 2019
        Reason		-	groupid filter added 
*/

DELIMITER $$
DROP PROCEDURE IF EXISTS check_vehicle_user_mapping$$
CREATE PROCEDURE `check_vehicle_user_mapping`(
    IN useridparam INT
    , IN groupidparam INT
    , OUT groupidOut VARCHAR(250)
    , OUT isUserMappedToVehicle TINYINT(1)
)
BEGIN
    
    SET isUserMappedToVehicle = 0;
    SET groupidOut = '-1';

    IF (groupidparam = 0) THEN
        SET groupidparam = NULL;
    END IF;

    /* Check whether any existing user has the group */
    SELECT  GROUP_CONCAT(groupid)
    INTO    groupidOut
    FROM    groupman
    WHERE   userid = useridparam
    AND     (groupid = groupidparam OR groupidparam IS NULL)
    AND     isdeleted = 0;

    IF (groupidOut IS NOT NULL AND groupidOut != '-1') THEN
        SET isUserMappedToVehicle = 1;
    END IF;
END$$
DELIMITER ;
