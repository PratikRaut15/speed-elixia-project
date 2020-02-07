INSERT INTO `uat_speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'610', '2018-09-11 13:00:00', 'Yash Kanakia', 'Sales Summary Report ', '0'
);
USE `uat_speed`;
DROP procedure IF EXISTS `fetch_sales_summary_report`;

DELIMITER $$
USE `uat_speed`$$
CREATE PROCEDURE `fetch_sales_summary_report`(
IN customernoParam INT,
IN supervisorParam INT,
IN sales_repParam INT,
IN start_dateParam date,
IN end_dateParam date
)
BEGIN
IF (supervisorParam = '' OR supervisorParam = 0) THEN
    SET supervisorParam = NULL;
END IF;

IF (sales_repParam = '' OR sales_repParam = 0) THEN
    SET sales_repParam = NULL;
END IF;

SELECT count(so.soid) as count,date(so.orderdate) as date,us.realname as supervisor,u.realname as sr_name,s.styleno from speed_sales.secondary_order so
LEFT JOIN speed_sales.secondary_order_productlist sop on sop.soid = so.soid and sop.customerno = customernoParam and sop.isdeleted = 0
INNER JOIN speed_sales.style s on s.styleid = sop.skuid and s.customerno = customernoParam and s.isdeleted = 0
INNER JOIN speed.user u on u.userid = so.srid and u.customerno = customernoParam AND (u.userid = sales_repParam OR sales_repParam IS NULL) AND (u.heirarchy_id = supervisorParam OR supervisorParam IS NULL)
LEFT JOIN speed.user us on us.userid = u.heirarchy_id and us.customerno = customernoParam AND (us.userid = supervisorParam OR supervisorParam IS NULL)
where so.customerno=customernoParam
AND so.isdeleted=0 AND date(so.orderdate) BETWEEN start_dateParam AND end_dateParam
GROUP BY date(so.orderdate),so.soid;
END$$

DELIMITER ;




UPDATE  dbpatches
SET     updatedOn = NOW(),isapplied = 1
WHERE   patchid = 610;
