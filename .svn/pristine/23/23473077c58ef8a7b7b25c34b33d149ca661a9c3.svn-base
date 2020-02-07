SET @patchId = 740;
SET @patchDate = '2019-12-21 16:06:00';
SET @patchOwner = 'Arvind Thakur';
SET @patchDescription = 'group id filter in SP';

INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');


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


UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;
