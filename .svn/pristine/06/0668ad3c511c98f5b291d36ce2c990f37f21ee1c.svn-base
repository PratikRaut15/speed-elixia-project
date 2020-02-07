INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'683', '2018-03-12 11:30:00', 'Yash Kanakia','Unit Audit Trail', '0');

DELIMITER $$
DROP procedure IF EXISTS `fetch_user_logs`$$
CREATE  PROCEDURE `fetch_user_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
  IN startdateParam date,
  IN enddateParam date,
  IN limitParam INT
  )
BEGIN

   DECLARE limitCondition VARCHAR(10);
    
      SET limitCondition = CONCAT(' LIMIT ', limitParam);
      


      SET @STMT = CONCAT("SELECT uat.*,r.role,u.realname as inserted_by,g.groupname FROM user_audit_trail uat LEFT JOIN `group` g on g.groupid = uat.groupid INNER JOIN role r on r.id = uat.roleid INNER JOIN user u on u.userid = uat.insertedBy WHERE uat.customerno =", customernoParam, " AND uat.userid =", userIdParam," AND date(uat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY uat.insertedOn desc ",limitCondition);
	    PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S; 
        
      END$$
DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `fetch_stoppage_alerts_logs`$$
CREATE PROCEDURE `fetch_stoppage_alerts_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
  IN startdateParam date,
  IN enddateParam date,
  IN limitParam INT
  )
BEGIN

   DECLARE limitCondition VARCHAR(10);
    
      SET limitCondition = CONCAT(' LIMIT ', limitParam);
      


      SET @STMT = CONCAT("SELECT stat.*,u.realname FROM stoppage_alerts_audit_trail stat INNER JOIN user u on u.userid = stat.insertedBy  WHERE stat.customerno =", customernoParam, " AND stat.userid =", userIdParam," AND date(stat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY stat.insertedOn desc ",limitCondition);
      PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S; 
        
      END$$
DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `fetch_vehicle_usermapping_logs`$$
CREATE PROCEDURE `fetch_vehicle_usermapping_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
  IN startdateParam date,
  IN enddateParam date,
  IN limitParam INT
  )
BEGIN

   DECLARE limitCondition VARCHAR(10);
    
      SET limitCondition = CONCAT(' LIMIT ', limitParam);
      


      SET @STMT = CONCAT("SELECT vuat.*,v.vehicleno,u.realname FROM vehicleusermapping_audit_trail vuat INNER JOIN `vehicle` v on v.vehicleid = vuat.vehicleid INNER JOIN user u on u.userid = vuat.created_by WHERE vuat.customerno =", customernoParam, " AND vuat.userid =", userIdParam," AND date(vuat.created_on) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY created_on desc ",limitCondition);
          PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S; 
        
      END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS after_vehicleusermapping_insert $$
CREATE TRIGGER `after_vehicleusermapping_insert` AFTER INSERT ON  vehicleusermapping FOR EACH ROW 
BEGIN

  BEGIN

    INSERT INTO vehicleusermapping_audit_trail

     SET vehicleid = NEW.vehicleid,
  groupid = NEW.groupid, 
  userid = NEW.userid,
  customerno = NEW.customerno, 
  created_on  = NEW.created_on,
  created_by = NEW.created_by,
  isdeleted = NEW.isdeleted;
END;
END $$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetch_vehicle_usermapping_logs`$$
CREATE PROCEDURE `fetch_vehicle_usermapping_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
  IN startdateParam date,
  IN enddateParam date,
  IN limitParam INT
  )
BEGIN

   DECLARE limitCondition VARCHAR(10);
    
      SET limitCondition = CONCAT(' LIMIT ', limitParam);
      


      SET @STMT = CONCAT("SELECT vuat.*,v.vehicleno,u.realname FROM vehicleusermapping_audit_trail vuat INNER JOIN `vehicle` v on v.vehicleid = vuat.vehicleid INNER JOIN user u on u.userid = vuat.created_by  WHERE vuat.customerno =", customernoParam, " AND vuat.userid =", userIdParam," AND date(vuat.created_on) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY vuat.created_on desc ",limitCondition);
    PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S; 
        
      END$$
DELIMITER ;






UPDATE  dbpatches
SET     patchdate = '2018-03-12 11:30:00'
        ,isapplied =1
WHERE   patchid = 683;

