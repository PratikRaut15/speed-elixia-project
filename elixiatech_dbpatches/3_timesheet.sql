INSERT INTO `dbpatches`
VALUES(3,'2018-09-25 17:30:00','Yash Kanakia','timesheet',0);

DROP TABLE IF EXISTS `tasks`;
    CREATE TABLE `tasks`(
        `taskId` INT AUTO_INCREMENT,
        `taskName` VARCHAR(100),
        `taskDesc` VARCHAR(255),
        `productId` INT,
        `customerNo` INT,
        `developerId` INT ,
        `testerId` INT ,
        `migratorId` INT,
        `statusId` INT,
        `estimatedTime` FLOAT(4,2),
        `estimatedDate` DATE,
        `actualDate` DATE,
        `createdBy` INT,
        `createdOn` DATETIME,
        `updatedBy` INT,
        `updatedOn` DATETIME,
        `isdeleted` TINYINT DEFAULT 0,
         PRIMARY KEY(`taskId`)
    );
DROP TABLE IF EXISTS `taskhistory`;
    CREATE TABLE `taskhistory`(
        `taskhistoryId` INT AUTO_INCREMENT,
        `taskId` INT ,
        `taskName` VARCHAR(100),
        `taskDesc` VARCHAR(255),
        `productId` INT,
        `customerNo` INT,
        `developerId` INT ,
        `testerId` INT ,
        `migratorId` INT,
        `statusId` INT,
        `estimatedTime` FLOAT(4,2),
        `estimatedDate` DATE,
        `actualDate` DATE,
        `createdBy` INT,
        `createdOn` DATETIME,
        `updatedBy` INT,
        `updatedOn` DATETIME,
        `isdeleted` TINYINT DEFAULT 0,
        PRIMARY KEY(`taskhistoryId`),
        FOREIGN KEY (`taskId`) REFERENCES `tasks`(`taskId`)
    );
DROP TABLE IF EXISTS `timesheet`;
    CREATE TABLE `timesheet`(
        `tsId` INT AUTO_INCREMENT,
        `teamId` INT,
        `taskId` INT,
        `time` FLOAT(4,2),
        `date` DATETIME,
        `createdOn` DATETIME,
        `updatedBy` INT,
        `updatedOn` DATETIME,
        PRIMARY KEY(`tsId`)
    );

DROP TABLE IF EXISTS `teamroles`;
    CREATE TABLE `teamroles` (
    `roleId` INT(11) NOT NULL AUTO_INCREMENT,
    `roleName` VARCHAR(100) DEFAULT NULL,
    `createdBy` INT(11) DEFAULT NULL,
    `createdOn` DATETIME DEFAULT NULL,
    `updatedBy` INT(11) DEFAULT NULL,
    `updatedOn` DATETIME DEFAULT NULL,
    PRIMARY KEY (`roleId`)
    )  ENGINE=INNODB AUTO_INCREMENT=5;
    INSERT INTO `teamroles` VALUES (1,'Developer',NULL,NULL,NULL,NULL),(2,'Tester',NULL,NULL,NULL,NULL),(3,'Team Head',NULL,NULL,NULL,NULL),(4,'Migrator',NULL,NULL,NULL,NULL);

DROP TABLE IF EXISTS `taskteam`;
  CREATE TABLE `taskteam` (
  `taskteamId` int(11) NOT NULL AUTO_INCREMENT,
  `teamId` int(11) DEFAULT NULL,
  `roleId` int(11) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `createdOn` datetime DEFAULT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`taskteamId`));
DROP TABLE IF EXISTS `taskstatus`;
  CREATE TABLE `taskstatus` (
    `statusId` int(11) NOT NULL AUTO_INCREMENT,
    `statusName` varchar(50) DEFAULT NULL,
    `createdBy` int(11) DEFAULT NULL,
    `createdOn` datetime DEFAULT NULL,
    `updatedBy` int(11) DEFAULT NULL,
    `updatedOn` datetime DEFAULT NULL,
    PRIMARY KEY (`statusId`)
    ) ENGINE=InnoDB AUTO_INCREMENT=15;
    INSERT INTO `taskstatus` VALUES (1,'Created',NULL,NULL,NULL,NULL),(2,'Assigned',NULL,NULL,NULL,NULL),(3,'In Progress',NULL,NULL,NULL,NULL),(4,'Awaiting UAT Migration',NULL,NULL,NULL,NULL),(5,'Moved to UAT',NULL,NULL,NULL,NULL),(6,'Awaiting testing on UAT',NULL,NULL,NULL,NULL),(7,'UAT Unsuccessful',NULL,NULL,NULL,NULL),(8,'UAT Successful',NULL,NULL,NULL,NULL),(9,'Awaiting Live Migration',NULL,NULL,NULL,NULL),(10,'Moved to Live',NULL,NULL,NULL,NULL),(11,'Awaiting testing on Live',NULL,NULL,NULL,NULL),(12,'Live Successful',NULL,NULL,NULL,NULL),(13,'Live Unsuccessful',NULL,NULL,NULL,NULL),(14,'Completed',NULL,NULL,NULL,NULL);



DELIMITER $$
DROP procedure IF EXISTS `insert_task_team`$$
CREATE PROCEDURE `insert_task_team`(
    IN taskNameParam VARCHAR(100),
    IN taskDescParam VARCHAR(255),
    IN productIdParam INT(11),
    IN customerNoParam INT(11),
    IN developerIdParam INT(11),
    IN testerIdParam INT(11),
    IN statusIdParam INT(11),
    IN estimatedTimeParam FLOAT(4,2),
    IN estimatedDataParam DATE,
    IN createdByParam INT(11),
    IN createdOnParam DATETIME,
    OUT lastInsertId TINYINT(2)
    )
BEGIN
        DECLARE tempActualDateParam DATE;
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
            IF (developerIdParam = 0 && statusIdParam <> 14) THEN
            SET statusIdParam = 1;

            ELSEIF (developerIdParam > 0 && statusIdParam < 2 && statusIdParam <> 14) THEN
            SET statusIdParam = 2;
            END IF;

            IF(statusIdParam = 14)THEN
            SET tempActualDateParam = DATE(createdOnParam);
            ELSE
            SET tempActualDateParam = NULL;
            END IF;

               INSERT into tasks (taskName,taskDesc,productId,customerNo,developerId,testerId,statusId,estimatedTime,
                estimatedDate,actualDate,createdBy,createdOn)
               VALUES(taskNameParam,taskDescParam,productIdParam,customerNoParam,developerIdParam,testerIdParam,statusIdParam,estimatedTimeParam,
                estimatedDataParam,tempActualDateParam,createdByParam,createdOnParam);

              SET lastInsertId = LAST_INSERT_ID();
           END;
           COMMIT;
    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `update_task_team`$$
CREATE PROCEDURE `update_task_team`(
    IN taskIdParam INT,
    IN taskNameParam VARCHAR (100),
    IN taskDescParam VARCHAR(255),
    IN prodIdParam INT,
    IN statusIdParam INT,
    IN customerNoParam INT,
    IN developerIdParam INT,
    IN testerIdParam INT,
    IN migratorIdParam INT,
    IN estTimeParam FLOAT(4,2),
    IN estDateParam DATE,
    IN updatedByParam INT,
    IN updatedOnParam DATETIME,
    OUT isUpdated INT
    )
BEGIN
        DECLARE tempActualDateParam DATE;
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            /*ROLLBACK;
            GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
            @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
            SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
            SELECT @full_error;
            SET isUpdated = 0;*/
        END;
        SET isUpdated = 0;
        START TRANSACTION;
       BEGIN

        IF (developerIdParam = 0 && statusIdParam <>14) THEN
        SET statusIdParam = 1;
        END IF;

        IF(statusIdParam = 14)THEN
            SET tempActualDateParam = DATE(updatedOnParam);
            ELSE
            SET tempActualDateParam = NULL;
        END IF;

            UPDATE `tasks`
                SET
                `taskName` = taskNameParam,
                `taskDesc` = taskDescParam,
                `productId` = prodIdParam,
                `customerNo` = customerNoParam,
                `developerId` = developerIdParam,
                `testerId` = testerIdParam,
                `migratorId` = migratorIdParam,
                `statusId` =statusIdParam ,
                `estimatedTime` = estTimeParam,
                `estimatedDate`=estDateParam,
                `actualDate`=tempActualDateParam,
                `updatedBy` = updatedByParam,
                `updatedOn` = updatedOnParam
            WHERE `taskId` = taskIdParam;

          SET isUpdated = 1;
       END;
       COMMIT;
    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_tasks`$$
CREATE PROCEDURE `fetch_tasks`(
    IN teamIdParam INT,
    IN taskIdParam INT
)
BEGIN
    IF (teamIdParam = '' OR teamIdParam = 0) THEN
        SET teamIdParam = NULL;
    END IF;
    IF (taskIdParam = '' OR taskIdParam = 0) THEN
        SET taskIdParam = NULL;
    END IF;
    SELECT
        t.taskId,
        t.taskName,
        t.taskDesc,
        t.productId,
        e.prodName,
        t.customerNo,
        c.customercompany,
        t.developerId,
        t.testerId,
        t.migratorId,
        tm.name AS developerName,
        tmt.name AS testerName,
        t.statusId,
        ts.statusName,
        t.estimatedTime,
        t.estimatedDate,
        t.createdBy,
        t.createdOn
    FROM
        tasks t
            INNER JOIN
        elixiaOneProductMaster e ON e.prodId = t.productId
            INNER JOIN
        customer c ON c.customerno = t.customerno
            LEFT JOIN
        team tm ON tm.teamid = t.developerId
            LEFT JOIN
        team tmt ON tmt.teamid = t.testerId
            INNER JOIN
        taskstatus ts ON ts.statusID = t.statusId
    WHERE   t.isdeleted = 0  AND t.statusId <> 14 AND
            (
                (t.developerId = teamIdParam OR teamIdParam IS NULL)
                OR
                ((t.testerId = teamIdParam OR teamIdParam IS NULL) AND (t.statusId= 6 OR t.statusId=11))
                OR
                ((t.migratorId = teamIdParam OR teamIdParam IS NULL) AND (t.statusId= 4 OR t.statusId=9))
            )
            AND (t.taskId = taskIdParam
            OR taskIdParam IS NULL)
    ORDER BY t.statusId;
END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetch_task_teamList`$$
CREATE PROCEDURE `fetch_task_teamList`(
    IN termParam VARCHAR(20)
)
BEGIN
  SELECT `teamid`,`name`
  FROM team
  WHERE  (`name` LIKE CONCAT('%',termParam,'%'))
  ORDER BY `name`;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_team_task_roles`$$
CREATE PROCEDURE `fetch_team_task_roles`(
    )
BEGIN
      SELECT roleId,roleName
      FROM teamroles
      ORDER BY roleId;
    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `insert_task_team_memeber`$$
CREATE PROCEDURE `insert_task_team_memeber`(
    IN roleidParam INT(11),
    IN teamidParam INT(11),
    IN createdByParam INT(11),
    IN createdOnParam DATETIME,
    OUT isInserted TINYINT(2)
    )
BEGIN
        DECLARE EXIT HANDLER FOR SQLEXCEPTION
           BEGIN
               ROLLBACK;
                /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
               @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
               SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
               SELECT @full_error;  */
               SET isInserted = 0;
           END;
            SET isInserted = 0;

           START TRANSACTION;
           BEGIN
               INSERT into taskteam (teamId,roleId,createdBy,createdOn)
               VALUES(teamidParam,roleidParam,createdByParam,createdOnParam);

              SET isInserted = 1;
           END;
           COMMIT;
    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_developers`$$
CREATE PROCEDURE `fetch_developers`()
BEGIN

        SELECT t.teamid,t.name
        from team t
        INNER JOIN taskteam tt on tt.teamid =t.teamid AND roleId =1;
    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_testers`$$
CREATE PROCEDURE `fetch_testers`()
BEGIN

        SELECT t.teamid,t.name
        from team t
        INNER JOIN taskteam tt on tt.teamid =t.teamid AND roleId =2;
    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_task_status`$$
CREATE PROCEDURE `fetch_task_status`()
BEGIN

    SELECT statusId,statusName FROM taskstatus;

    END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `searchTask` $$
CREATE PROCEDURE `searchTask`(
    IN searchTermParam VARCHAR(50),
    IN teamIdParam INT
)
BEGIN
  SELECT taskId,taskName from tasks
    WHERE taskName LIKE CONCAT('%',searchTermParam,'%')
    AND
            (
                (developerId = teamIdParam OR teamIdParam IS NULL)
                OR
                ((testerId = teamIdParam OR teamIdParam IS NULL) AND (statusId= 6 OR statusId=11))
                OR
                ((migratorId = teamIdParam OR teamIdParam IS NULL) AND (statusId= 4 OR statusId=9))
            )
    AND isdeleted=0;
END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetch_task_members`$$
CREATE PROCEDURE `fetch_task_members`(
IN teamIdParam INT,
IN roleIdParam INT)
BEGIN
        IF teamIdParam = '' OR teamIdParam = 0 THEN
        SET teamIdParam = NULL;
        END IF;

        IF roleIdParam = '' OR roleIdParam = 0 THEN
        SET roleIdParam = NULL;
        END IF;

        SELECT
        t.teamId, tm.name, t.roleId, r.roleName
        FROM
        taskteam t
            INNER JOIN
        team tm ON tm.teamId = t.teamId
            INNER JOIN
        teamroles r ON r.roleId = t.roleId
        WHERE
        (t.teamId = teamIdParam
            OR teamIdParam IS NULL)
            AND (t.roleId = roleIdParam
            OR roleIdParam IS NULL);
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `insert_timesheet`$$
CREATE PROCEDURE `insert_timesheet`(
    IN developerIdParam INT,
    IN taskIdParam INT,
    IN estimatedTimeParam FLOAT(4,2),
    IN dateParam DATE,
    IN createdOnParam DATETIME,
    OUT lastInsertId TINYINT(2)
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

               INSERT into timesheet (teamId,taskId,time,date,createdOn)
               VALUES(developerIdParam,taskIdParam,estimatedTimeParam,dateParam,createdOnParam);

              SET lastInsertId = LAST_INSERT_ID();
           END;
           COMMIT;
    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_timesheet`$$
CREATE PROCEDURE `fetch_timesheet`(
IN teamIdParam INT
)
BEGIN
    IF (teamIdParam = '' OR teamIdParam = 0) THEN
        SET teamIdParam = NULL;
    END IF;

    SELECT
        ts.tsId,
        ts.taskId,
        t.taskName,
        tm.name as developerName,
        ts.time,
        ts.date
    FROM
        timesheet ts
            INNER JOIN
        tasks t ON t.taskId = ts.taskId
            INNER JOIN
        elixiaOneProductMaster e ON e.prodId = t.productId
            LEFT JOIN
        team tm ON tm.teamid = ts.teamId
    WHERE (ts.teamId = teamIdParam
            OR teamIdParam IS NULL)
    GROUP BY ts.tsId,ts.teamId;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_migrators`$$
CREATE PROCEDURE `fetch_migrators`()
BEGIN

        SELECT t.teamid,t.name
        from team t
        INNER JOIN taskteam tt on tt.teamid =t.teamid AND roleId =4;
    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `delete_tasks`$$
CREATE PROCEDURE `delete_tasks`(
    IN taskIdParam INT,
    OUT isExecuted INT
)
BEGIN

        DECLARE EXIT HANDLER FOR SQLEXCEPTION
           BEGIN
               ROLLBACK;
                /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
               @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
               SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
               SELECT @full_error;  */
           END;
            SET isExecuted = 0;

           START TRANSACTION;
           BEGIN
                UPDATE tasks
                SET isdeleted = 1
                WHERE taskId = taskIdParam;

              SET isExecuted = 1;
           END;
           COMMIT;
END$$

DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `after_task_insert`$$
CREATE TRIGGER `after_task_insert`
 AFTER INSERT ON `tasks`
 FOR EACH ROW BEGIN
    INSERT INTO taskhistory
    (
        taskId,
        taskName,
        taskDesc,
        productId,
        customerNo,
        developerId,
        testerId,
        migratorId,
        statusId,
        estimatedTime,
        estimatedDate,
        actualDate,
        createdBy,
        createdOn,
        updatedBy,
        updatedOn,
        isdeleted
    )VALUE(
        NEW.taskId,
        NEW.taskName,
        NEW.taskDesc,
        NEW.productId,
        NEW.customerNo,
        NEW.developerId,
        NEW.testerId,
        NEW.migratorId,
        NEW.statusId,
        NEW.estimatedTime,
        NEW.estimatedDate,
        NEW.actualDate,
        NEW.createdBy,
        NEW.createdOn,
        NEW.updatedBy,
        NEW.updatedOn,
        NEW.isdeleted
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `after_task_update`$$
CREATE TRIGGER `after_task_update`
 AFTER UPDATE ON `tasks`
 FOR EACH ROW BEGIN
    INSERT INTO taskhistory
    (
        taskId,
        taskName,
        taskDesc,
        productId,
        customerNo,
        developerId,
        testerId,
        migratorId,
        statusId,
        estimatedTime,
        estimatedDate,
        actualDate,
        createdBy,
        createdOn,
        updatedBy,
        updatedOn,
        isdeleted
    )VALUE(
        NEW.taskId,
        NEW.taskName,
        NEW.taskDesc,
        NEW.productId,
        NEW.customerNo,
        NEW.developerId,
        NEW.testerId,
        NEW.migratorId,
        NEW.statusId,
        NEW.estimatedTime,
        NEW.estimatedDate,
        NEW.actualDate,
        NEW.createdBy,
        NEW.createdOn,
        NEW.updatedBy,
        NEW.updatedOn,
        NEW.isdeleted
    );
END$$
DELIMITER ;
UPDATE `dbpatches` SET isapplied = 1 WHERE patchid = 3;
