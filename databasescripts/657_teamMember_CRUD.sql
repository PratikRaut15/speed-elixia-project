INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('657', '2019-01-25 19:30:00', 'Yash Kanakia','Team Member CRUD', '0');

ALTER TABLE team
ADD COLUMN company_roleId INT;
ALTER TABLE team CHANGE COLUMN company_roleId company_roleId INT AFTER role;

DELIMITER $$
DROP procedure IF EXISTS `insert_team_member`$$
CREATE PROCEDURE `insert_team_member`(
IN nameParam VARCHAR(100),
IN phoneParam VARCHAR(20),
IN emailParam VARCHAR(100),
IN roleParam VARCHAR(100),
IN departmentParam INT,
IN companyRoleParam INT,
IN memberTypeParam tinyINT,
IN loginIdParam VARCHAR(100),
IN passwordParam VARCHAR(100),
OUT isExecutedOut INT,
OUT teamIdOut INT
)
BEGIN
DECLARE nameExists VARCHAR(100);
DECLARE usernameExists VARCHAR(100);
DECLARE EXIT HANDLER FOR SQLEXCEPTION

    BEGIN

        ROLLBACK;
        

       /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

        SELECT @full_error; */

        
    END;

SELECT 
    name
INTO nameExists FROM
    team
WHERE
    name = nameParam AND phone = phoneParam
        AND email = emailParam;

IF nameExists IS NULL THEN

	SELECT username INTO usernameExists FROM team WHERE username = loginIdParam;

	IF usernameExists IS NULL THEN 
		
		INSERT INTO team (`name` ,`phone` ,`email` ,`role` ,`member_type`,`username` ,`password`,`department_id`,`company_roleId`,`is_deleted`)
		VALUES (nameParam,phoneParam,emailParam,roleParam,memberTypeParam,loginIdParam,passwordParam,departmentParam,companyRoleParam,0);
		
        INSERT INTO elixiatech.team(`name` ,`phone` ,`email` ,`role` ,`member_type`,`username` ,`password`,`department_id`,`company_roleId`,`is_deleted`)
		VALUES (nameParam,phoneParam,emailParam,roleParam,memberTypeParam,loginIdParam,passwordParam,departmentParam,companyRoleParam,0);

		SET isExecutedOut = 1;
		SET teamIdOut = LAST_INSERT_ID(); /*TEAM MEMBER INSERTED*/
	ELSE
		SET isExecutedOut = 0;
        SET teamIdOut = 0;   /*IF USERNAME IS ALREADY TAKEN*/
	END IF;
ELSE
	SET isExecutedOut = 2;  /*IF USER IS ALREADY CREATED*/
	SET teamIdOut = 0;
END IF;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_team_list`$$
CREATE PROCEDURE `fetch_team_list`(
IN teamIdParam INT)
BEGIN
	
  IF (teamIdParam = 0 OR teamIdParam IS NULL) THEN
	SET teamIdParam = NULL;
  END IF;
  SELECT t.teamid,t.name,t.phone,t.email,t.role,d.department_id,t.company_roleId,t.username,d.department,CASE WHEN t.member_type = 1 THEN 'Elixir' ELSE 'Non Elixir' END as member FROM team t 
  INNER JOIN elixiatech.department d on d.department_id = t.department_id
  WHERE t.is_deleted = 0 AND t.teamid = teamIdParam OR teamIdParam IS NULL
  ORDER BY name asc;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `update_team_member`$$
CREATE PROCEDURE `update_team_member`(
IN teamIdParam INT,
IN nameParam VARCHAR(100),
IN phoneParam VARCHAR(20),
IN emailParam VARCHAR(100),
IN roleParam VARCHAR(100),
IN departmentParam INT,
IN companyRoleParam INT,
IN memberTypeParam tinyINT,
OUT isExecutedOut INT
)
BEGIN
DECLARE usernameExists VARCHAR(100);
DECLARE EXIT HANDLER FOR SQLEXCEPTION

    BEGIN

        ROLLBACK;
        

      /*  GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

        SELECT @full_error; */

        
    END;



SET isExecutedOut = 0;
		
UPDATE team 
SET 
    `name` = nameParam,
    `phone` = phoneParam,
    `email` = emailParam,
    `role` = roleParam,
    `member_type` = memberTypeParam,
    `department_id` = departmentParam,
    `company_roleId` = companyRoleParam
WHERE
    teamid = teamIdParam;
        
		UPDATE elixiatech.team 
SET 
    `name` = nameParam,
    `phone` = phoneParam,
    `email` = emailParam,
    `role` = roleParam,
    `member_type` = memberTypeParam,
    `department_id` = departmentParam,
    `company_roleId` = companyRoleParam
WHERE
    teamid = teamIdParam;

		SET isExecutedOut = 1;/*TEAM MEMBER INSERTED*/

END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `update_team_account_settings`$$
CREATE PROCEDURE `update_team_account_settings`(
IN teamIdParam INT,
IN loginIdParam VARCHAR(100),
IN passwordParam VARCHAR(100),
OUT isExecutedOut INT
)
BEGIN
DECLARE usernameExists VARCHAR(100);
DECLARE newPasswordParam VARCHAR(100);
DECLARE EXIT HANDLER FOR SQLEXCEPTION

    BEGIN

        ROLLBACK;
        

      /*  GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,

        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;

        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);

        SELECT @full_error; */

        
    END;
    IF (passwordParam='') THEN
		SELECT `password` INTO newPasswordParam from team where teamid=teamIdParam;
        SET passwordParam = newPasswordParam;
	END IF;
    
SELECT username INTO usernameExists FROM team WHERE username = loginIdParam AND teamid <> teamIdParam;

	IF usernameExists IS NULL THEN 
		
UPDATE team 
SET 
    `username` = loginIdParam,
    `password` = passwordParam
WHERE
    teamid = teamIdParam;
        
UPDATE elixiatech.team 
SET 
    `username` = loginIdParam,
    `password` = passwordParam
WHERE
    teamid = teamIdParam;

		SET isExecutedOut = 1;/*TEAM MEMBER INSERTED*/
ELSE
		SET isExecutedOut = 0;/*USERNAME EXISTS*/
END IF;
END$$

DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2019-01-25 19:30:00'
        ,isapplied =1
WHERE   patchid = 657;