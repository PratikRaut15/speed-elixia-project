INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'665', '2018-02-12 14:30:00', 'Yash Kanakia','Login History', '0');

DELIMITER $$
DROP procedure IF EXISTS `get_login_trend`$$
CREATE PROCEDURE `get_login_trend`(
IN customernoParam INT)
BEGIN
	  
	  SELECT DATE_FORMAT(created_on,'%h %p') as hour, DAYNAME(created_on) as day,count(*) as total,customerno
      FROM login_history_details
      WHERE customerno = customernoParam
      GROUP BY MINUTE(created_on), WEEKDAY(created_on)
      ORDER BY total desc,HOUR(created_on), WEEKDAY(created_on)
      LIMIT 1;

END$$

DELIMITER ;


USE `speed`;
DROP procedure IF EXISTS `get_login_history`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `get_login_history`(
IN customernoParam INT,
IN dateParam date)
BEGIN

SELECT lhd.created_on,u.username from login_history_details lhd
INNER JOIN user u on u.userid = lhd.created_by
where date(lhd.created_on) = dateParam AND lhd.customerno = customernoParam;

END$$

DELIMITER ;



UPDATE  dbpatches
SET     patchdate = '2018-02-12 14:30:00'
        ,isapplied =1
WHERE   patchid = 665;

