INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'694', '2018-03-20 12:30:00', 'Yash Kanakia','User Trail', '0');


DELIMITER $$
DROP procedure IF EXISTS `fetch_user_logs`$$
CREATE PROCEDURE `fetch_user_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
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



      SET @STMT = CONCAT("SELECT DISTINCT uat.*,r.role,u.realname as inserted_by FROM user_audit_trail uat  INNER JOIN role r on r.id = uat.roleid INNER JOIN user u on u.userid = uat.insertedBy WHERE uat.customerno =", customernoParam, " AND uat.userid =", userIdParam," AND date(uat.insertedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY uat.insertedOn desc ",limitCondition);
      PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S;

      END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetch_stoppage_alerts_logs`$$
CREATE  PROCEDURE `fetch_stoppage_alerts_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
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

      SET limitCondition = '';
        
      IF (limitParam <> -1) THEN
        SET limitCondition = CONCAT(' LIMIT ', limitParam);
      END IF;



      SET @STMT = CONCAT("SELECT vuat.*,v.vehicleno,u.realname FROM vehicleusermapping_audit_trail vuat INNER JOIN `vehicle` v on v.vehicleid = vuat.vehicleid INNER JOIN user u on u.userid = vuat.created_by WHERE vuat.customerno =", customernoParam, " AND vuat.userid =", userIdParam," AND date(vuat.created_on) BETWEEN '",startdateParam,"' AND '",enddateParam,"' GROUP BY vuat.isdeleted,vuat.created_on ORDER BY vuat.created_on desc ",limitCondition);
      PREPARE S FROM @STMT;
      EXECUTE S;
      DEALLOCATE PREPARE S;

      END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_groupman_logs`$$
CREATE PROCEDURE `fetch_groupman_logs`(
  IN customernoParam INT,
  IN userIdParam INT,
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



    SET @STMT = CONCAT("SELECT DISTINCT gm.*,g.groupname,u.realname FROM groupman_audit_trail gm LEFT JOIN `group` g on g.groupid = gm.groupid INNER JOIN user u on u.userid = gm.createdBy  WHERE gm.customerno =", customernoParam, " AND gm.userid =", userIdParam," AND date(gm.createdOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY gm.createdOn desc ",limitCondition);
        PREPARE S FROM @STMT;
    EXECUTE S;
    DEALLOCATE PREPARE S;

    END$$

DELIMITER ;



UPDATE  dbpatches
SET     patchdate = '2018-03-20 12:30:00'
        ,isapplied =1
WHERE   patchid = 694;
