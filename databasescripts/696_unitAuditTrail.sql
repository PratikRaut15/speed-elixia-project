INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'696', '2018-03-25 17:30:00', 'Yash Kanakia','Unit Audit Trail', '0');


USE `speed`;
DROP procedure IF EXISTS `fetch_unit_logs`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `fetch_unit_logs`(
	IN customernoParam INT,
	IN unitIdParam INT,
	IN startdateParam date,
	IN enddateParam date,
	IN limitParam INT
	)
BEGIN

 	DECLARE limitCondition VARCHAR(10);
	
        SET limitCondition = '';
        
	   	IF (limitParam <> -1) THEN
    		SET limitCondition = CONCAT(' LIMIT ', limitParam);
    	END IF;


    


		SET @STMT = CONCAT("SELECT un.*,u.realname,v.vehicleno FROM unit_audit_trail un INNER JOIN user u on u.userid = un.updatedBy LEFT JOIN vehicle v on v.vehicleid=un.vehicleid WHERE un.customerno =", customernoParam, " AND un.uid =", unitIdParam," AND date(un.updatedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY un.updatedOn desc ",limitCondition);
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S; 
			
    END$$

DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2018-03-25 17:30:00'
        ,isapplied =1
WHERE   patchid = 696;
