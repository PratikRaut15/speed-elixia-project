INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'693', '2018-03-20 12:30:00', 'Yash Kanakia','Vehicle Trail', '0');


DELIMITER $$
DROP procedure IF EXISTS `fetch_checkpoint_logs`$$
CREATE PROCEDURE `fetch_checkpoint_logs`(
	IN customernoParam INT,
	IN vehicleIdParam INT,
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


		SET @STMT = CONCAT("SELECT cat.*,c.cname,u.realname,v.vehicleno FROM checkpointmanage_audit_trail cat LEFT JOIN `checkpoint` c on c.checkpointid = cat.checkpointid INNER JOIN user u on u.userid = cat.insertedBy INNER JOIN vehicle v on v.vehicleid = cat.vehicleid WHERE cat.customerno =", customernoParam, " AND cat.vehicleid =", vehicleIdParam," AND date(cat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY cat.insertedOn desc,cat.isdeleted ",limitCondition);
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S;

    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_fence_logs`$$
CREATE PROCEDURE `fetch_fence_logs`(
	IN customernoParam INT,
	IN vehicleIdParam INT,
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




		SET @STMT = CONCAT("SELECT fat.*,f.fencename,u.realname,v.vehicleno FROM fenceman_audit_trail fat LEFT JOIN `fence` f on f.fenceid = fat.fenceid INNER JOIN vehicle v on v.vehicleid = fat.vehicleid INNER JOIN user u on u.userid = fat.insertedBy WHERE fat.customerno =", customernoParam, " AND fat.vehicleid =", vehicleIdParam," AND date(fat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' AND f.fencename IS NOT NULL GROUP BY fat.fmid,fat.insertedOn,fat.isdeleted ORDER BY fat.insertedOn desc,fat.isdeleted  ",limitCondition);
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S;

    END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_vehicle_logs`$$
CREATE PROCEDURE `fetch_vehicle_logs`(
	IN customernoParam INT,
	IN vehicleIdParam INT,
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



			SET @STMT = CONCAT("SELECT vat.*,g.groupname,u.realname FROM vehicle_audit_trail vat LEFT JOIN `group` g on g.groupid = vat.groupid INNER JOIN user u on u.userid = vat.insertedBy WHERE vat.customerno =", customernoParam, " AND vat.vehicleid =", vehicleIdParam," AND date(vat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY insertedOn desc ",limitCondition);
	        PREPARE S FROM @STMT;
			EXECUTE S;
			DEALLOCATE PREPARE S;

	    END$$

DELIMITER ;

UPDATE  dbpatches
SET     patchdate = '2018-03-20 12:30:00'
        ,isapplied =1
WHERE   patchid = 693;
