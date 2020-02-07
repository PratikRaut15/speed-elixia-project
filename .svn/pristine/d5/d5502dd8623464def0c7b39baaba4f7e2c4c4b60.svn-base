/*
    Name			-	get_odometer_reading
    Description 	-	To get the first last and max odmeters of the particular vehicle
    Parameters		-	unitno
                        , custnoparam - Customer number 
    Module			-	VTS Mobile App
    Sub-Modules 	- 	
    Sample Call		-	CALL get_odometer_reading(904032, 132);
    Created by		-	Mrudang
    Created on		- 	01 Apr, 2016
    Change details 	-	
    1) 	Updated by	- 	Mrudang
		Updated	on	- 	15 June, 2016
        Reason		-	daily_date inclusion for correct distance
	1) 	Updated by	- 	Shrikant
		Updated	on	- 	24 July, 2017
        Reason		-	Increse Size Of unitnumber Param 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_odometer_reading`$$
CREATE PROCEDURE `get_odometer_reading`(
	IN unitnumber VARCHAR(16)
    , IN custno INT
    , IN todaysDate DATE
)
BEGIN
	DECLARE unitId INT DEFAULT NULL;
    SELECT 	uid INTO unitId
    FROM	unit
    WHERE 	LTRIM(RTRIM(unitno)) = unitnumber
    AND		customerno = custno;
    
	SELECT 	first_odometer
			, last_odometer
            , max_odometer 
    FROM 	dailyreport 
    WHERE 	(uid = unitId OR unitId IS NULL)
    AND 	customerno = custno
    AND		daily_date = todaysDate;

END$$
DELIMITER ;
