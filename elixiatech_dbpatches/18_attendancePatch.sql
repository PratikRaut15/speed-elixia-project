INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('18', '2018-02-12 18:30:00', 'Yash Kanakia', 'Attendance Dashboard', '0');

DELIMITER $$
DROP procedure IF EXISTS `get_attendance_busy_status`$$
CREATE PROCEDURE `get_attendance_busy_status`(

    IN teamIdParam INT,
    IN datetimeParam date,
    OUT isExistOut INT
    )
BEGIN
    DECLARE busyDate datetime;
    DECLARE checkInDate datetime;
    DECLARE checkOutDate datetime;
    SET isExistOut = 0;

	SELECT 
    createdOn
    INTO busyDate FROM
        elixiaOfficeAttendance
    WHERE
        checkValue = 3 AND teamId = teamIdParam
            AND DATE(createdOn) = datetimeParam
            AND attendanceId = (SELECT 
                MAX(attendanceId)
            FROM
                elixiaOfficeAttendance
            WHERE
                checkValue = 3 AND teamId = teamIdParam);

    SELECT 
        createdOn
    INTO checkOutDate FROM
        elixiaOfficeAttendance
    WHERE
        checkValue = 0 AND teamId = teamIdParam
            AND DATE(createdOn) = datetimeParam
            AND attendanceId = (SELECT 
                MAX(attendanceId)
            FROM
                elixiaOfficeAttendance
            WHERE
                checkValue = 0 AND teamId = teamIdParam);
                
    SELECT 
        createdOn
    INTO checkInDate FROM
        elixiaOfficeAttendance
    WHERE
        checkValue = 1 AND teamId = teamIdParam
            AND DATE(createdOn) = datetimeParam
            AND attendanceId = (SELECT 
                MAX(attendanceId)
            FROM
                elixiaOfficeAttendance
            WHERE
                checkValue = 1 AND teamId = teamIdParam);            
                

    IF(busyDate IS NULL && checkInDate IS NULL && checkOutDate IS NULL)THEN
    SET isExistOut = 0; /* NOT ENTRY FOR TODAY */ 

    ELSE
        IF(busyDate IS NULL)THEN
         SET busyDate = '0000-00-00';
        END IF;

        IF(checkInDate IS NULL)THEN
         SET checkInDate = '0000-00-00';
        END IF;
        
        IF(checkOutDate IS NULL)THEN
         SET checkOutDate = '0000-00-00';
        END IF;


        IF(busyDate>checkInDate && busyDate>checkOutDate) THEN
            SET isExistOut = 1;  /* BUSY */ 
        ELSEIF(checkInDate >busyDate && checkInDate>checkOutDate)THEN
            SET isExistOut = 2;  /* CHECKED IN */ 
		ELSE
			SET isExistOut = 3;  /* CHECKED OUT */ 
        END IF;
		
      
        
    END IF;
    
END$$

DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2018-02-12 18:30:00',isapplied = 1
WHERE   patchid = 18;
