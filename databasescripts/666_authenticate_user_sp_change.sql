INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'666', '2018-02-20 11:00:00', 'Sanjneet Shukla','authenticate_for_user sp change', '0');

ALTER TABLE `setting` ADD `isDistributor` TINYINT NOT NULL AFTER `use_location_summary`;
INSERT INTO setting (customerno, use_location_summary, isDistributor, use_vehicle_type, use_checkpoint_settings)
VALUES ('698', '0', '1', '0', '0');


update user set erpUserToken = '3532535963' where userid = '9031' and customerno = 698;
update user set erpUserToken = '1202974153' where userid = '9032' and customerno = 698;
update user set erpUserToken = '4006897603' where userid = '9033' and customerno = 698;
update user set erpUserToken = '3540863797' where userid = '9034' and customerno = 698;
update user set erpUserToken = '1388659014' where userid = '9035' and customerno = 698;
update user set erpUserToken = '615471108' where userid = '9036' and customerno = 698;
update user set erpUserToken = '3206145926' where userid = '9037' and customerno = 698;
update user set erpUserToken = '4168542203' where userid = '9038' and customerno = 698;


DELIMITER $$
DROP PROCEDURE IF EXISTS `authenticate_for_login`$$
CREATE  PROCEDURE `authenticate_for_login`(
  IN `usernameparam` VARCHAR(50), 
  IN `passparam` VARCHAR(150), 
  IN `todaydt` DATETIME, 
  OUT `usertype` INT, 
  OUT `userkeyparam` INT, 
  OUT `userauthtype` INT)
BEGIN
  DECLARE useridparam INT;
    DECLARE tempPwdParam VARCHAR(150);


  SET usertype = 0;
  SET userkeyparam = 0;
  SET userauthtype = 0;

  SELECT  userid,userkey,c.multiauth
  INTO  useridparam , userkeyparam,userauthtype
  FROM    user
  INNER JOIN customer AS c ON c.customerno = user.customerno
  WHERE   username = usernameparam
  AND   password = passparam
  AND   isdeleted = 0
  AND     c.isoffline = 0;

  IF (useridparam IS NULL)THEN
    BEGIN
      SELECT  f.userid, u.userkey, c.multiauth
            INTO  useridparam, userkeyparam, userauthtype
      FROM  forgot_password_request AS f
            INNER JOIN  user AS u ON u.userid = f.userid
      INNER JOIN customer AS c ON c.customerno = u.customerno
      WHERE   f.username = usernameparam
      AND   CAST(SHA1(f.otp) AS BINARY) = CAST(passparam AS BINARY)
      AND   f.isdeleted = 0
            AND   u.isdeleted = 0
      AND     c.isoffline = 0
      AND   f.isused = 0
      AND   f.request_counter <= 3
      AND   f.validupto BETWEEN todaydt AND DATE_ADD(todaydt, INTERVAL 24 HOUR);
    END;
    IF (useridparam IS NOT NULL) THEN
      BEGIN

        SET usertype = 1;
      END;
    END IF;
  END IF;

  IF (useridparam IS NOT NULL AND usertype = 0)THEN
    BEGIN

      SELECT  	  		s.isDistributor,u.*, a.*, customer.*
      FROM    			user as u
      INNER JOIN  		customer ON u.customerno = customer.customerno
      INNER JOIN  		android_version as a
      LEFT OUTER JOIN 	setting s on s.customerno = customer.customerno and s.isdeleted = 0
      WHERE     		u.userid = useridparam AND u.isdeleted =0 AND customer.isoffline = 0;
    END;
  END IF;

END$$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2018-02-20 11:00:00'
        ,isapplied =1
WHERE   patchid = 666;

