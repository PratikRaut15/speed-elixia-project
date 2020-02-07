DELIMITER $$
DROP PROCEDURE IF EXISTS `get_odometer_reading`$$
CREATE PROCEDURE `get_odometer_reading` (
	IN unitnumber VARCHAR(11)
    , IN custno INT
)
BEGIN
	DECLARE unitId INT DEFAULT NULL;
    SELECT 	uid INTO unitId
    FROM	unit
    WHERE 	LTRIM(RTRIM(unitno)) = unitnumber;
    
	SELECT 	first_odometer
			, last_odometer
            , max_odometer 
    FROM 	dailyreport 
    WHERE 	(uid = unitId OR unitId IS NULL)
    AND 	customerno = custno;
END
$$ DELIMITER ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (288, NOW(), 'Mrudang','API-V5 Distance Calculation Changes');

