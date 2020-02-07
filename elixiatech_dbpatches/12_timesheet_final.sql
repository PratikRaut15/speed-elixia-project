INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('12', '2019-01-31 14:22:00', 'Kartik Joshi', 'Time sheet enhancements', '0');

DELIMITER $$
DROP procedure IF EXISTS `fetch_timesheet_details`$$
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
    tm.taskId,
    t.taskDesc,
    tm.tMapId,
    t.taskName,
    tr.trId,
    TIME_FORMAT(SEC_TO_TIME(SUM(ts.time) / COUNT(ts.teamId)),
            '%H:%i') AS time,
    ts.date,
    (CASE
        WHEN l.date IS NOT NULL THEN 'Locked'
        ELSE 'Unlocked'
    END) AS status,
    l.date AS lockedDate,
    ts.teamId,
    te.name,
    (GROUP_CONCAT(DISTINCT (CASE WHEN tp.productId = 0 THEN 'All Products' 
									ELSE p.prodName END )
									)
						 )products,
    GROUP_CONCAT(DISTINCT (tp.productId)) productIds,
    (GROUP_CONCAT(DISTINCT (CASE WHEN tc.customerNo = 0 THEN 'All Customers' 
									ELSE c.customercompany END )
									)) customers,
    GROUP_CONCAT(DISTINCT (tc.customerNo)) customerIds
FROM
    timeSheet ts
        INNER JOIN
    teamRoles tr ON tr.teamId = ts.teamId
        INNER JOIN
    team te ON te.teamid = ts.teamId
        INNER JOIN
    taskMapping tm ON tm.tMapId = ts.tMapId
        AND tm.teamId = ts.teamId
        INNER JOIN
    task t ON t.taskId = tm.taskId
        LEFT JOIN
    taskProductMapping tp ON tp.pMapId = tm.pMapId
        LEFT JOIN
    taskCustomerMapping tc ON tc.cMapId = tm.cMapId
        LEFT JOIN
    customer c ON c.customerNo = tc.customerNo
        LEFT JOIN
    elixiaOneProductMaster p ON p.prodId = tp.productId
        LEFT OUTER JOIN
    locked_timesheets l ON l.teamId = ts.teamId
        AND l.date = ts.date
WHERE
    (ts.teamId = teamIdParam
        OR teamIdParam IS NULL)
        AND (ts.tsId = tsIdParam)
GROUP BY ts.tsId;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `searchTask`$$
CREATE PROCEDURE `searchTask`(
    IN searchTermParam VARCHAR(50),
    IN teamIdParam INT,
    IN trIdParam INT
)
BEGIN
    IF(teamIdParam = 0) THEN
        SET teamIdParam = NULL;
    END IF;
    SELECT 
        t.taskId,
        t.taskName,
        tm.tMapId,
        tp.pMapId,
        tc.cMapId,
        COALESCE(GROUP_CONCAT(DISTINCT (p.prodName)),
                'All Products') products,
        GROUP_CONCAT(DISTINCT (tp.productId)) productIds,
        COALESCE(GROUP_CONCAT(DISTINCT (c.customercompany)),
                'All Customers') customers,
        GROUP_CONCAT(DISTINCT (tc.customerNo)) customerIds
    FROM
        task t
            INNER JOIN
        taskTeam tt ON tt.taskId = t.taskId
            INNER JOIN
        taskMapping tm ON tm.taskId = t.taskId
            AND tm.teamId = teamIdParam AND tm.isClosed = 0
            INNER JOIN
        taskProductMapping tp ON tp.pMapId= tm.pMapId
            LEFT JOIN
        elixiaOneProductMaster p ON p.prodId = tp.productId
            INNER JOIN
        taskCustomerMapping tc ON tc.cMapId = tm.cMapId
            LEFT JOIN
        customer c ON c.customerno = tc.customerNo
    WHERE taskName LIKE CONCAT('%', searchTermParam, '%')
    GROUP BY t.taskId;
END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetch_timesheet`$$
CREATE  PROCEDURE `fetch_timesheet`(
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
        tm.taskId,
        t.taskName,
        TIME_FORMAT(  SEC_TO_TIME( SUM(ts.time)/count(ts.teamId) )  ,'%H:%i') AS time,
        ts.date,
        (CASE
            WHEN l.date IS NOT NULL THEN 'Locked'
            ELSE 'Unlocked'
        END) AS status,
        l.date AS lockedDate,
        dtm.dayCode as lockedDayType,
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
        team te ON te.teamid = ts.teamId
            INNER JOIN
        taskMapping tm ON tm.tMapId = ts.tMapId AND tm.teamId = ts.teamId
            INNER JOIN
        task t ON t.taskId = tm.taskId
            LEFT JOIN
        taskProductMapping tp ON tp.pMapId = tm.pMapId
            LEFT JOIN
        taskCustomerMapping tc ON tc.cMapId = tm.cMapId
            LEFT JOIN
        customer c ON c.customerNo = tc.customerNo
            LEFT JOIN
        elixiaOneProductMaster p ON p.prodId = tp.productId
            LEFT OUTER JOIN
        locked_timesheets l ON l.teamId = ts.teamId
            AND l.date = ts.date
            LEFT OUTER JOIN 
        day_type_master dtm ON dtm.dtId = l.dayType
    WHERE (ts.teamId = teamIdParam OR teamIdParam IS NULL) AND (ts.date = date(dateParam) OR dateParam IS NULL)
        GROUP BY ts.tsId;
END$$

DELIMITER ;



UPDATE  dbpatches
SET     isapplied = 1
WHERE   patchid = 12;

