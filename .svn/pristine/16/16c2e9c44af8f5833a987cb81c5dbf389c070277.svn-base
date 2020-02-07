INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('17', '2018-02-11 17:30:00', 'Yash Kanakia', 'Attendance Dashboard', '0');

DELIMITER $$
DROP procedure IF EXISTS `get_attendance_busy_status`$$
CREATE PROCEDURE `get_attendance_busy_status`(

    IN teamIdParam INT,
    IN datetimeParam date,
    OUT isExistOut INT,
    OUT latestTimeOut time
    )
BEGIN

    DECLARE busyDate datetime;
    DECLARE checkInDate datetime;
    DECLARE checkOutDate datetime;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
       
       /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error; */

        
    END;
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
                
    SET isExistOut = 0;
    
    IF(busyDate='' && checkInDate='' && checkOutDate='')THEN
    SET isExistOut = 0; /* NOT ENTRY FOR TODAY */ 
    SET latestTimeOut = '00:00:00';
    ELSE
        IF(busyDate ='')THEN
         SET busyDate = '0000-00-00';
        END IF;

        IF(checkInDate='')THEN
         SET checkInDate = '0000-00-00';
        END IF;
        
        IF(checkOutDate='')THEN
         SET checkOutDate = '0000-00-00';
        END IF;

        
        IF(busyDate>checkInDate && busyDate>checkOutDate) THEN
            SET isExistOut = 1;  /* BUSY */ 
            SET latestTimeOut = time(busyDate);
        ELSEIF(checkInDate >busyDate && checkInDate>checkOutDate)THEN
            SET isExistOut = 2;  /* CHECKED IN */ 
            SET latestTimeOut = time(checkInDate);
        ELSE
            SET isExistOut = 3;  /* CHECKED OUT */ 
            SET latestTimeOut = time(checkOutDate);
        END IF;
        
      
        SELECT isExistOut,latestTimeOut;
    END IF;
END$$

DELIMITER ;



DROP TABLE IF EXISTS `elixiaOfficeMembers`;
CREATE TABLE `elixiaOfficeMembers`(
eomId INT PRIMARY KEY AUTO_INCREMENT,
officeId INT DEFAULT 0,
teamId INT,
phoneExtension VARCHAR(10));

INSERT INTO elixiaOfficeMembers (officeId,teamId)
SELECT 1,teamid from team where is_deleted = 0 AND department_id <> 1;
INSERT INTO elixiaOfficeMembers (officeId,teamId)
SELECT 2,teamid from team where is_deleted = 0 AND department_id = 1;

DELIMITER $$
DROP TRIGGER IF EXISTS insert_elixiaOffice_Member $$
CREATE TRIGGER `insert_elixiaOffice_Member` AFTER INSERT ON `team`
FOR EACH ROW BEGIN
BEGIN
    IF (NEW.department_id=1) THEN
    INSERT INTO elixiaOfficeMembers
    SET teamId = NEW.teamid,
        officeId = 2;
    ELSE 
    INSERT INTO elixiaOfficeMembers
    SET teamId = NEW.teamid,
        officeId = 1;
    END IF;
END;
END $$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_elixia_office_members`$$
CREATE PROCEDURE `get_elixia_office_members`(
    IN officeIdParam INT
    )
BEGIN
SELECT eom.*,t.name FROM elixiaOfficeMembers eom
    INNER JOIN team t on t.teamid = eom.teamId AND t.company_roleId NOT IN(14)
    WHERE eom.officeId=officeIdParam
    ORDER BY t.company_roleId ;
END$$

DELIMITER ;



UPDATE  dbpatches
SET     patchdate = '2018-02-11 17:30:00',isapplied = 1
WHERE   patchid = 17;
