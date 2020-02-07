INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`)
VALUES ('722', '2019-07-22 17:32:00', 'Arvind Thakur','temperature alert report', '0');

DELIMITER $$
DROP PROCEDURE IF EXISTS `getTempAlertHistory`$$
CREATE PROCEDURE `getTempAlertHistory`(
    IN startdate DATETIME
    , IN enddate DATETIME
    , IN customernoParam INT(11)
    , IN vehicleidParam INT(11)
    , IN typeParam TINYINT(2)
    , IN intervalParam TINYINT(4)
)
BEGIN

    DECLARE useridVar INT;

    SELECT  cq.userid
    INTO    useridVar
    FROM    `comqueue` cq
    INNER JOIN `user` u ON u.userid = cq.userid AND u.tempinterval = intervalParam
    WHERE   cq.vehicleid = vehicleidParam
    AND     cq.customerno = customernoParam
    AND     cq.`type` = typeParam
    AND     cq.status = 1
    AND     cq.timeadded BETWEEN startdate AND enddate
    LIMIT   1;

    IF (useridVar IS NOT NULL) THEN

        SELECT  v.vehicleno
                , (CASE WHEN (cq.tempsensor = 1 AND un.n1 > 0) THEN (SELECT `name` FROM nomens WHERE nid = un.n1)
                        WHEN (cq.tempsensor = 1 AND un.n1 = 0) THEN 'Temperature 1'
                        WHEN (cq.tempsensor = 2 AND un.n2 > 0) THEN (SELECT `name` FROM nomens WHERE nid = un.n2)
                        WHEN (cq.tempsensor = 2 AND un.n2 = 0) THEN 'Temperature 2'
                        WHEN (cq.tempsensor = 3 AND un.n3 > 0) THEN (SELECT `name` FROM nomens WHERE nid = un.n3)
                        WHEN (cq.tempsensor = 3 AND un.n3 = 0) THEN 'Temperature 3'
                        WHEN (cq.tempsensor = 4 AND un.n4 > 0) THEN (SELECT `name` FROM nomens WHERE nid = un.n4)
                        WHEN (cq.tempsensor = 4 AND un.n4 = 0) THEN 'Temperature 4'
                    END) AS sensorName
                , COUNT(cq.cqid) AS `count`
        FROM    `comqueue` cq
        INNER JOIN `user` u ON u.userid = cq.userid AND u.tempinterval = intervalParam
        INNER JOIN vehicle v ON v.vehicleid = cq.vehicleid
        INNER JOIN unit un ON un.vehicleid = v.vehicleid
        WHERE   cq.vehicleid = vehicleidParam
        AND     cq.customerno = customernoParam
        AND     cq.userid = useridVar
        AND     cq.`type` = typeParam
        AND     cq.status = 1
        AND     cq.timeadded BETWEEN startdate AND enddate
        GROUP BY cq.vehicleid;

    END IF;


END$$
DELIMITER $$

UPDATE  dbpatches
SET     updatedOn = DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE)
        ,isapplied = 1
WHERE   patchid = 722;