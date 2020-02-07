/**
# Author: Ranjeet Kasture
# Date created: 22-05-2019
# Date pushed to UAT: 22-05-2019
# Description:
# Stored procedure written for fetching data for route wise tracking report - report number - 54, 
# This report is used by Delex - 132
# 
#
***/

/* Create dbpatch */
    INSERT INTO `dbpatches` ( 
    `patchid`,
    `patchdate`, 
    `appliedby`, 
    `patchdesc`,
    `isapplied`
    ) 
    VALUES
    ( 
    '712', '2019-05-22 17:00:00', 
    'Ranjeet K','Stored procedure written for fetching data for route wise tracking report - report number - 54','0');

DROP procedure IF EXISTS `fetch_data_for_route_wise_report`;

DELIMITER $$

CREATE  PROCEDURE `fetch_data_for_route_wise_report`(IN `routeIdParam` INT, IN `customerNoParam` INT)
    COMMENT 'This routine is used to fetch the data for routewise report'
BEGIN
	SELECT 
    a.routeid,
    a.routename,
    b.vehicleid,
    d.vehicleno,
    c.checkpointid,
    c.sequence,
    ch.cname,
    c.eta,
    c.etd,
    a.routeTat
FROM
    route AS a
        LEFT JOIN
    vehiclerouteman AS b ON a.routeid = b.routeid
        LEFT JOIN
    routeman AS c ON a.routeid = c.routeid
        LEFT JOIN
    vehicle AS d ON d.vehicleid = b.vehicleid
		left join
    checkpoint AS ch ON c.checkpointid = ch.checkpointid    
WHERE
    a.isdeleted = 0 AND a.routeid = routeIdParam
        AND b.isdeleted = 0
        AND c.isdeleted = 0
        AND d.isdeleted = 0
        AND a.customerno = customerNoParam
        AND d.customerno = customerNoParam
ORDER BY a.routeid , b.vehicleid,c.sequence;
END$$

DELIMITER ;

/* Updating dbpatche 706 */
    UPDATE  dbpatches
    SET     patchdate = '2019-05-08 17:00:00',isapplied =1
    WHERE   patchid = 712;

