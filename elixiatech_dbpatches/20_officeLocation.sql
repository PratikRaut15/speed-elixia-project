INSERT INTO `dbpatches` (`patchid`,`patchdate`, `appliedby`, `patchdesc`, `isapplied`) 
VALUES (20,'2019-03-25 17:30:00', 'Yash Kanakia', 'Attendance Office Location', '0');


DELIMITER $$
DROP procedure IF EXISTS `get_office_locations`$$
CREATE PROCEDURE `get_office_locations`(

    IN centerParam INT,
    OUT locationOut VARCHAR(100)
    )
BEGIN
    SET locationOut = '';
    SELECT 
        office_name
    INTO locationOut FROM
        `elixiaOfficeLocations`
    WHERE
       officeId =centerParam;
        
    
END$$

DELIMITER ;




UPDATE `dbpatches` 
SET `updatedOn`='2019-03-25 17:30:00',
`isapplied`=1 
WHERE `patchid`='20';
