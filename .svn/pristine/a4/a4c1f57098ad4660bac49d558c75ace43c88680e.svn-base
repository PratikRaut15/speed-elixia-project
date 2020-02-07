INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('6', '2018-10-26 18:06:00', 'Kartik Joshi', 'Time sheet base', '0');

DELIMITER $$
DROP TABLE IF EXISTS `task`$$
CREATE TABLE `task`(
    `taskId` INT AUTO_INCREMENT PRIMARY KEY,
    `taskName` VARCHAR(255),
    `taskDesc` VARCHAR(255),
    `statusId` INT,
    `estimatedTime` BIGINT,
    `productMappingId` INT,
    `customerMappingId` INT,
    `departmentId` INT,
    `createdBy` INT,
    `createdOn` DATETIME,
    `updatedBy` INT,
    `updatedOn` DATETIME,
    `isDeleted` TINYINT DEFAULT 0
)$$
DELIMITER ;
DELIMITER $$
DROP TABLE IF EXISTS `teamRoles`$$
CREATE TABLE `teamRoles`(
    `trId` INT AUTO_INCREMENT PRIMARY KEY,
    `departmentId` INT,
    `roleId` INT,
    `teamId` INT,
    `createdBy` INT,
    `createdOn` DATETIME,
    `updatedBy` INT,
    `updatedOn` DATETIME,
    `isDeleted` TINYINT DEFAULT 0
)$$
DELIMITER ;
DELIMITER $$
DROP TABLE IF EXISTS `taskTeam`$$
CREATE TABLE `taskTeam`(
    `ttId` INT AUTO_INCREMENT PRIMARY KEY,
    `taskId` INT,
    `trId` VARCHAR(255),
    `createdBy` INT,
    `createdOn` DATETIME,
    `updatedBy` INT,
    `updatedOn` DATETIME,
    `isDeleted` TINYINT DEFAULT 0
)$$
DELIMITER ;
DELIMITER $$
DROP TABLE IF EXISTS `timeSheet`$$
CREATE TABLE `timeSheet`(
    `tsId` INT AUTO_INCREMENT PRIMARY KEY,
    `taskId` INT,
    `time` BIGINT,
    `date` DATE,
    `teamId` VARCHAR(255),
    `createdBy` INT,
    `createdOn` DATETIME
)$$
DELIMITER ;
DELIMITER $$
DROP TABLE IF EXISTS `taskProductMapping`$$
CREATE TABLE `taskProductMapping`(
    `tpId` INT AUTO_INCREMENT PRIMARY KEY,
    `taskId` INT,
    `productId` INT
)$$
DELIMITER ;
DELIMITER $$
DROP TABLE IF EXISTS `taskCustomerMapping`$$
CREATE TABLE `taskCustomerMapping`(
    `tcId` INT AUTO_INCREMENT PRIMARY KEY,
    `taskId` INT,
    `customerNo` INT
)$$
DELIMITER ;
DELIMITER $$
DROP TABLE IF EXISTS `taskSubRoles`$$
CREATE TABLE `taskSubRoles`(
    `tsrId` INT AUTO_INCREMENT PRIMARY KEY,
    `departmentId` INT,
    `roleId` INT,
    `roleName` VARCHAR(255)
)$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_task_teamList`;$$
CREATE PROCEDURE `fetch_task_teamList`(
    IN departmentIdParam INT,
    IN teamIdParam INT
)
BEGIN
    IF departmentIdParam = 0 THEN
        SET departmentIdparam = NULL;
    END IF;
    IF teamIdParam = 0 THEN
        SET teamIdParam = NULL;
    END IF;
    select 
        d.department,r.roleName,t.name,tr.roleId,tr.teamId,tr.trId,t.teamid
    from teamRoles tr
    left join department d ON d.department_id = tr.departmentId
    left outer join team t ON t.teamid = tr.teamId 
    left join taskSubRoles r ON r.roleId = tr.roleId
    WHERE (tr.departmentId = departmentIdParam OR departmentIdParam IS NULL) AND (tr.teamId = teamIdParam OR teamIdParam IS NULL);
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `insert_task`$$
CREATE PROCEDURE `insert_task`(
    IN taskNameParam VARCHAR(100),
    IN taskDescParam VARCHAR(255),
    IN statusIdParam INT(11),
    IN estimatedTimeParam BIGINT,
    IN departmentIdParam INT,
    IN createdByParam INT(11),
    IN createdOnParam DATETIME,
    OUT lastInsertId INT
    )
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
           ROLLBACK;
            /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error;  */
           SET lastInsertId = 0;
       END;
        SET lastInsertId = 0;

       START TRANSACTION;
       BEGIN
           INSERT into task (taskName,taskDesc,statusId,estimatedTime,departmentId,createdBy,createdOn)
           VALUES(taskNameParam,taskDescParam,statusIdParam,estimatedTimeParam,departmentIdParam,createdByParam,createdOnParam);

          SET lastInsertId = LAST_INSERT_ID();
       END;
       COMMIT;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_tasks`$$
CREATE PROCEDURE `fetch_tasks`(
    IN teamIdParam INT,
    IN taskIdParam INT,
    IN departmentIdParam INT
)
BEGIN
    IF (teamIdParam = 0) THEN 
        SET teamIdParam = null;
    END IF;
    IF (taskIdParam = 0) THEN 
        SET taskIdParam = null;
    END IF;
    IF (departmentIdParam = 0) THEN 
        SET departmentIdParam = null;
    END IF;
    select distinct(t.taskId),t.taskName,ts.statusName,
        COALESCE(GROUP_CONCAT(DISTINCT(p.prodName)),'All Products') products,
        GROUP_CONCAT(DISTINCT(tp.productId)) productIds,
        COALESCE(GROUP_CONCAT(DISTINCT(c.customercompany)),'All Customers') customers,
        GROUP_CONCAT(DISTINCT(tc.customerNo)) customerIds
    from task t
    inner join taskTeam tt ON tt.taskId = t.taskId 
    inner join teamRoles tr ON tt.trId = tr.trId 
                AND (tr.teamId       = teamIdParam       OR teamIdParam IS NULL) 
                AND (tr.departmentId = departmentIdParam OR departmentIdParam IS NULL)
    inner join taskstatus ts ON ts.statusId = t.statusId AND t.statusId <> 13
    left join taskProductMapping tp ON tp.taskId = t.taskId
    left join taskCustomerMapping tc ON tc.taskId = t.taskId
    left join customer c ON c.customerNo = tc.customerNo
    left join elixiaOneProductMaster p ON p.prodId = tp.productId
     WHERE (t.taskId = taskIdParam OR taskIdParam IS NULL) AND (t.departmentId = departmentIdParam OR departmentIdParam IS NULL)
    group by t.taskId;
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetch_team_task_roles`$$
CREATE PROCEDURE `fetch_team_task_roles`(
        IN departmentIdParam INT
    )
BEGIN
    IF(departmentIdParam = 0) THEN
        SET departmentIdParam = NULL;
    END IF;
      SELECT roleId,roleName,departmentId
      FROM taskSubRoles
      WHERE (departmentId = departmentIdparam OR departmentIdParam IS NULL)
      ORDER BY roleId;
    END$$

DELIMITER ;

DELIMITER $$
DROP TABLE IF EXISTS `locked_timesheets`$$
CREATE TABLE `locked_timesheets`(
    ltId INT AUTO_INCREMENT PRIMARY KEY,
    teamId INT,
    date DATE,
    dayType INT,
    duration INT,
    createdBy INT,
    createdOn DATETIME,
    updatedBy INT,
    updatedOn DATETIME
)$$
DELIMITER ;

DELIMITER $$
DROP TABLE IF EXISTS `day_type_master`$$
CREATE TABLE `day_type_master`(
    dtId INT AUTO_INCREMENT PRIMARY KEY,
    dayCode VARCHAR(5),
    dayType VARCHAR(100),
    hours INT
)$$
DELIMITER ;
INSERT INTO `day_type_master` (`dayCode`,`dayType`,`hours`) VALUES ('LE','Leave',0);
INSERT INTO `day_type_master` (`dayCode`,`dayType`,`hours`) VALUES ('HO','Holiday',0);
INSERT INTO `day_type_master` (`dayCode`,`dayType`,`hours`) VALUES ('HA','Half day',4);
INSERT INTO `day_type_master` (`dayCode`,`dayType`,`hours`) VALUES ('FU','Full day',8);
INSERT INTO `day_type_master` (`dayCode`,`dayType`,`hours`) VALUES ('OV','Overtime',12);

INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('1','1','Developer');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('1','2','Tester');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('1','3','Migrator');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('1','4','Team Head');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('5','5','CRM');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('2','6','Asst. Manager');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('2','7','Operations Manager');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('3','8','Accountant');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('3','9','Asst. Accountant');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('4','10','Sales Representative');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('4','11','Sales Head');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('6','12','Feild Engineer');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('7','13','CEO');
INSERT INTO `taskSubRoles` (`departmentId`,`roleId`,`roleName`) VALUES ('6','14','MD');

INSERT INTO `taskstatus`(`statusId`,`statusName`,`createdBy`,`createdOn`) VALUES(0,'Created',112,NOW());

DELIMITER $$
DROP PROCEDURE IF EXISTS `fetch_timesheet_details`$$
CREATE PROCEDURE `fetch_timesheet_details`(
    IN tsIdParam INT,
    IN teamIdParam INT
)
BEGIN
    IF (teamIdParam = 0) THEN 
        SET teamIdParam = null;
    END IF;

    SELECT 
        ts.tsId,
        ts.taskId,
        t.taskDesc,
        t.taskName,
        (SUM(ts.time / 3600)/count(ts.teamId)) AS time,
        ts.date,
        (CASE
            WHEN l.date IS NOT NULL THEN 'Locked'
            ELSE 'Unlocked'
        END) AS status,
        l.date AS lockedDate,
        ts.teamId,
        te.name,
        COALESCE(GROUP_CONCAT(DISTINCT (p.prodName)),
                'All Products') products,
        GROUP_CONCAT(DISTINCT (tp.productId)) productIds,
        COALESCE(GROUP_CONCAT(DISTINCT (c.customercompany)),
                'All Customers') customers,
        GROUP_CONCAT(DISTINCT (tc.customerNo)) customerIds
    FROM
        timeSheet ts
            INNER JOIN
        task t ON t.taskId = ts.taskId
            INNER JOIN
        team te ON te.teamid = ts.teamId
            LEFT JOIN
        taskProductMapping tp ON tp.taskId = t.taskId
            LEFT JOIN
        taskCustomerMapping tc ON tc.taskId = t.taskId
            LEFT JOIN
        customer c ON c.customerNo = tc.customerNo
            LEFT JOIN
        elixiaOneProductMaster p ON p.prodId = tp.productId
            LEFT OUTER JOIN
        locked_timesheets l ON l.teamId = ts.teamId
            AND l.date = ts.date
    WHERE (ts.teamId = teamIdParam OR teamIdParam IS NULL) AND (ts.tsId = tsIdParam)
        GROUP BY ts.tsId;
END;

DELIMITER $$
DROP procedure IF EXISTS `searchTask`$$
CREATE PROCEDURE `searchTask`(
    IN searchTermParam VARCHAR(50),
    IN teamIdParam INT
)
BEGIN
    IF(teamIdParam = 0) THEN
        SET teamIdParam = NULL;
    END IF;
    SELECT 
        t.taskId,
        t.taskName,
        GROUP_CONCAT(DISTINCT (tp.productId)) productIds,
        COALESCE(GROUP_CONCAT(DISTINCT (p.prodName)),
                'All Products') productNames,
        GROUP_CONCAT(DISTINCT (tc.customerNo)) customerNos,
            COALESCE(GROUP_CONCAT(DISTINCT(c.customercompany)),
                'All Customers') customerNames
    FROM
        task t
            INNER JOIN
        taskTeam tt ON tt.taskId = t.taskId
            INNER JOIN
        teamRoles tr ON tt.trId = tr.trId
            AND (tr.teamId = teamIdParam
            OR teamIdParam IS NULL)
            INNER JOIN
        taskProductMapping tp ON tp.taskId = t.taskId
            LEFT JOIN
        elixiaOneProductMaster p ON p.prodId = tp.productId
            INNER JOIN
        taskCustomerMapping tc ON tc.taskId = t.taskId
            LEFT JOIN
        customer c ON c.customerno = tc.customerNo
    WHERE

        taskName LIKE CONCAT('%', searchTermParam, '%')
    GROUP BY t.taskId;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_timesheet`$$
CREATE PROCEDURE `fetch_timesheet`(
    IN teamIdParam INT,
    IN dateParam DATE
)
BEGIN
    IF (teamIdParam = 0) THEN 
        SET teamIdParam = null;
    END IF;
    IF (dateParam = 0) THEN 
        SET dateParam = null;
    END IF;
    SELECT 
        ts.tsId,
        ts.taskId,
        t.taskName,
        (SUM(ts.time / 3600)/count(ts.teamId)) AS time,
        ts.date,
        (CASE
            WHEN l.date IS NOT NULL THEN 'Locked'
            ELSE 'Unlocked'
        END) AS status,
        l.date AS lockedDate,
        ts.teamId,
        te.name,
        COALESCE(GROUP_CONCAT(DISTINCT (p.prodName)),
                'All Products') products,
        GROUP_CONCAT(DISTINCT (tp.productId)) productIds,
        COALESCE(GROUP_CONCAT(DISTINCT (c.customercompany)),
                'All Customers') customers,
        GROUP_CONCAT(DISTINCT (tc.customerNo)) customerIds
    FROM
        timeSheet ts
            INNER JOIN
        task t ON t.taskId = ts.taskId
            INNER JOIN
        team te ON te.teamid = ts.teamId
            LEFT JOIN
        taskProductMapping tp ON tp.taskId = t.taskId
            LEFT JOIN
        taskCustomerMapping tc ON tc.taskId = t.taskId
            LEFT JOIN
        customer c ON c.customerNo = tc.customerNo
            LEFT JOIN
        elixiaOneProductMaster p ON p.prodId = tp.productId
            LEFT OUTER JOIN
        locked_timesheets l ON l.teamId = ts.teamId
            AND l.date = ts.date
    WHERE (ts.teamId = teamIdParam OR teamIdParam IS NULL) AND (ts.date = date(dateParam) OR dateParam IS NULL)
        GROUP BY ts.tsId;
END$$

DELIMITER ;


UPDATE  dbpatches
SET     isapplied = 1
WHERE   patchid = 6;

