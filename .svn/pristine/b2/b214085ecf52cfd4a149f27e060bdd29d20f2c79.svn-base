INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('15', '2018-02-05 12:30:00', 'Yash Kanakia', 'Attendance App', '0');

DROP TABLE IF EXISTS `elixiaOfficeLocations`;
CREATE TABLE `elixiaOfficeLocations`(
officeId INT PRIMARY KEY AUTO_INCREMENT,
office_name VARCHAR(100),
lattitude VARCHAR(100),
longitude VARCHAR(100));

DROP TABLE IF EXISTS `elixiaOfficeAttendance`;
CREATE TABLE elixiaOfficeAttendance(
attendanceId INT PRIMARY KEY AUTO_INCREMENT,
teamId INT,
checkValue tinyInt comment '1 is used for CheckIn and 0 for checkout',
createdOn datetime);

INSERT INTO elixiaOfficeLocations (`office_name`,`lattitude`,`longitude`)
VALUES('Head Office','19.0813201','72.8937332'),('Development Center','19.07736','72.89934');

DELIMITER $$
DROP procedure IF EXISTS `insert_team_attendance`$$
CREATE PROCEDURE `insert_team_attendance`(
	IN teamIdParam INT,
    IN checkValueParam tinyInt,
    IN locationParam VARCHAR(100),
    IN createdOnParam  datetime,
    OUT isExecutedOut INT
)
BEGIN

 BEGIN

        /*ROLLBACK;
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;*/

    END;

			SET isExecutedOut = 0;

            BEGIN

                INSERT INTO `elixiaOfficeAttendance`(
					teamId,
                    checkValue,
                    location,
					createdOn
                )VALUES(
				   teamIdParam, 	
                   checkValueParam,
                   locationParam,
                   createdOnParam
                );



			SET isExecutedOut = 1;

            END;



	COMMIT;

END$$

DELIMITER ;





DELIMITER $$
DROP procedure IF EXISTS `get_office_locations`$$
CREATE PROCEDURE `get_office_locations`(

    IN latiudeParam VARCHAR(100),
    IN longitudeParam VARCHAR(100),
    OUT isExistOut INT,
	OUT locationOut VARCHAR(100)
    )
BEGIN

	SET isExistOut = 0;
	SELECT 
	    office_name
	INTO locationOut FROM
	    `elixiaOfficeLocations`
	WHERE
	    lattitude LIKE CONCAT(latiudeParam, '%')
	        AND longitude LIKE CONCAT(longitudeParam, '%');
		
		IF(locationOut IS NOT NULL) THEN
		SET isExistOut = 1;
		END IF;
END$$

DELIMITER ;


ALTER TABLE `elixiaOfficeAttendance`
ADD COLUMN location VARCHAR(100);

ALTER TABLE `elixiaOfficeAttendance`
MODIFY COLUMN location VARCHAR(100) AFTER checkValue;



UPDATE  dbpatches
SET     patchdate = '2018-02-05 12:30:00',isapplied = 1
WHERE   patchid = 15;
