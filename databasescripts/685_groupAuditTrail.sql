INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'685', '2018-03-12 11:30:00', 'Yash Kanakia','Group Audit Trail', '0');


DELIMITER $$
DROP procedure IF EXISTS `fetch_group_logs`$$
CREATE  PROCEDURE `fetch_group_logs`(
	IN customernoParam INT,
	IN groupIdParam INT,
	IN startdateParam date,
	IN enddateParam date,
	IN limitParam INT
	)
BEGIN

 	DECLARE limitCondition VARCHAR(10);
	
    SET limitCondition = CONCAT(' LIMIT ', limitParam);
    


		SET @STMT = CONCAT("SELECT gh.*,u.realname,c.name as branch_region FROM group_history gh INNER JOIN user u on u.userid = gh.userid LEFT JOIN city c on c.cityid = gh.cityid WHERE gh.customerno =", customernoParam, " AND gh.groupid =", groupIdParam," AND date(gh.createdOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY gh.createdOn desc ",limitCondition);
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S; 
			
    END$$
DELIMITER ;

ALTER TABLE groupman
 ADD COLUMN createdBy INT,
 ADD COLUMN createdOn datetime;


UPDATE  dbpatches
SET     patchdate = '2018-03-12 11:30:00'
        ,isapplied =1
WHERE   patchid = 685;



