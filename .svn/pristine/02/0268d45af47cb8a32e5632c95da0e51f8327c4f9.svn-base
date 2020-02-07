INSERT INTO `dbpatches` (`patchid`,`patchdate`, `appliedby`, `patchdesc`, `isapplied`) 
VALUES (19,'2019-02-27 15:30:00', 'Yash Kanakia', 'Attendance Logs', '0');


DELIMITER $$
DROP procedure IF EXISTS `get_team_attendanceLogs`$$
CREATE PROCEDURE `get_team_attendanceLogs`(
IN datetimeParam date)
BEGIN
	SELECT 
    t.name,
    CASE
        WHEN eoa.checkValue = 1 THEN 'CHECKED IN'
        WHEN eoa.checkValue = 0 THEN 'CHECKED OUT'
        WHEN eoa.checkValue = 3 THEN 'BUSY'
    END AS checkValue,
    eoa.createdOn
FROM
    elixiaOfficeAttendance eoa
        INNER JOIN
    team t ON t.teamid = eoa.teamId
WHERE
    DATE(createdOn) = datetimeParam
ORDER BY t.teamId;
END$$

DELIMITER ;



UPDATE `dbpatches` 
SET `updatedOn`='2019-02-27 17:30:00',
`isapplied`=1 
WHERE `patchid`='19';
