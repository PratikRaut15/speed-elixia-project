/*1. Updated By   - Sanjeet Shukla
     Updated On   - 20-02-2019
     Reason       - changed query from select * => select s.isDistributor,u.*, a.*, customer.*
*/       

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

CALL speed.authenticate_for_login('vora.enterprises','7c4a8d09ca3762af61e59520943dc26494f8941b','2019-02-19 15:53:16',@usertype,@userkeyparam,@userauthtype);
select @usertype,@userkeyparam,@userauthtype;