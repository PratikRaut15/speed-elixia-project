SET @patchId = 731;
SET @patchDate = '2019-11-08 16:20:00';
SET @patchOwner = 'Arvind Thakur';
SET @patchDescription = 'check vehicle user mapping SP issue resolved';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');


DELIMITER $$
DROP PROCEDURE IF EXISTS check_vehicle_user_mapping$$
CREATE PROCEDURE `check_vehicle_user_mapping`(
    IN useridparam INT
    , OUT groupidparam VARCHAR(250)
    , OUT isUserMappedToVehicle TINYINT(1)
)
BEGIN
    
    SET isUserMappedToVehicle = 0;
    SET groupidparam = '-1';

    /* Check whether any existing user has the group */
    SELECT  GROUP_CONCAT(groupid)
    INTO    groupidparam
    FROM    groupman
    WHERE   userid = useridparam
    AND     isdeleted = 0;

    IF (groupidparam IS NOT NULL AND groupidparam != '-1') THEN
        SET isUserMappedToVehicle = 1;
    END IF;
END$$
DELIMITER ;



UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
