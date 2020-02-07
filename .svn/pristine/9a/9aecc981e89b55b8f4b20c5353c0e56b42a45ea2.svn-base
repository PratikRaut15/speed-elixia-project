
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'449', '2017-01-14 19:09:40', 'Shrikant Suryawanshi', '2 Way User Authentication', '0'
);


ALTER TABLE `customer` ADD `multiauth` TINYINT NOT NULL DEFAULT '0' AFTER `lease_price`;
ALTER TABLE `customer` ADD `isoffline` TINYINT(1) NOT NULL AFTER `multiauth`;


CREATE TABLE `multiauth_request` (
  `fpassid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `otp` int(11) NOT NULL,
  `validupto` datetime DEFAULT NULL,
  `isused` tinyint(1) NOT NULL,
  `request_counter` tinyint(4) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `multiauth_request`
  ADD PRIMARY KEY (`fpassid`),
  ADD KEY `index_userid` (`userid`);


  ALTER TABLE `multiauth_request`
  MODIFY `fpassid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


DELIMITER $$
DROP PROCEDURE IF EXISTS multiauth_request$$
CREATE PROCEDURE `multiauth_request`(
  IN userparam INT,
  IN todaysdate DATETIME
)
BEGIN
  DECLARE otpparam INT;
  DECLARE otpvalidupto DATETIME;
    DECLARE useremail VARCHAR(50);
    DECLARE userphone VARCHAR(15);
    DECLARE custno INT;
  DECLARE useridparam INT;

  SELECT  userid, COALESCE(email, username, ''), COALESCE(phone, ''), customerno
    INTO  useridparam, useremail, userphone, custno
    FROM  user
    WHERE   (userid=userparam)
    AND   isdeleted = 0;

  IF (useridparam IS NOT NULL)THEN
    CALL insert_multiauth_request(useridparam, todaysdate, @otpparam, @otpvaliduptoparam);
    SELECT @otpparam, @otpvaliduptoparam INTO otpparam, otpvalidupto;

        SELECT  useridparam
        , otpparam
        , otpvalidupto
                , useremail
                , userphone
                , custno;

  END IF;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS insert_multiauth_request$$
CREATE PROCEDURE `insert_multiauth_request`(
  IN useridparam INT,
  IN todaysdate DATETIME,
  OUT otpparam INT,
  OUT otpvaliduptoparam DATETIME
)
BEGIN
  DECLARE request_counterparam INT;
  DECLARE username_param varchar(50);
  DECLARE phone_param varchar(15);
    DECLARE minotp INT DEFAULT 100000;
    DECLARE maxotp INT DEFAULT 999999;

  IF EXISTS ( SELECT  userid
        FROM  multiauth_request
        WHERE   userid = useridparam
        AND   isused = 0
        AND   isdeleted = 0
        AND   validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR)
        LIMIT 1) THEN
    BEGIN
      SELECT  otp,request_counter, validupto
      INTO  otpparam, request_counterparam, otpvaliduptoparam
      FROM  multiauth_request
      WHERE   userid = useridparam
      AND   isused = 0
      AND   isdeleted = 0
      AND   validupto between todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR) LIMIT 1;

      IF(request_counterparam = 3) THEN
        SET otpparam = -1;
      ELSE
        UPDATE  multiauth_request
        SET   request_counter = request_counterparam + 1
            , updated_on = todaysdate
        WHERE   userid = useridparam
        AND   isused = 0
        AND   isdeleted = 0
        AND   validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR);
      END IF;
    END;
  ELSE
    BEGIN
      SELECT  phone, username
      INTO  phone_param, username_param
      FROM  user
      WHERE   userid = useridparam
      AND   isdeleted = 0;

            SET otpparam = FLOOR(RAND() * (maxotp - minotp + 1)) + minotp;
            SET otpvaliduptoparam = DATE_ADD(todaysdate,INTERVAL 24 HOUR);

      INSERT INTO multiauth_request   (
              userid
            , username
            , phone
            , otp
            , validupto
            , isused
            , request_counter
                        , created_on
                        , updated_on
            )
          VALUES  (
             useridparam
            , username_param
            , phone_param
            , otpparam
            , otpvaliduptoparam
            , 0
            , 1
                        , todaysdate
                        , todaysdate
          );
    END;
  END IF;

END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS validate_Otp_2WayAuthentication$$
CREATE PROCEDURE `validate_Otp_2WayAuthentication`(
IN `useridParam` INT,
IN `otpParam` INT,
IN `todaysdate` DATETIME,
OUT `validStatus` INT
)
BEGIN
  DECLARE tempUserId INT;

  IF (useridParam IS NOT NULL AND otpParam IS NOT NULL) THEN
    SELECT  userid
        INTO  tempUserId
        FROM  multiauth_request
        WHERE userid = useridParam
        AND   isused = 0
    AND   isdeleted = 0
        AND   request_counter <= 3
        AND   validupto BETWEEN todaysdate AND DATE_ADD(todaysdate,INTERVAL 24 HOUR);

        IF (tempUserId IS NOT NULL ) THEN
      BEGIN
        UPDATE  multiauth_request
        SET   isused = 1
            , updated_on = todaysdate
        WHERE   userid = tempUserId
        AND     otp = otpParam
        AND   isdeleted = 0
        AND   isused = 0;
      END;

      SET validStatus = 1;

    ELSE
      SET validStatus = 0;
        END IF;

  ELSE
    SET validStatus = 0;
  END IF;

END$$
DELIMITER ;





DELIMITER $$
DROP PROCEDURE IF EXISTS `authenticate_for_login`$$
CREATE PROCEDURE `authenticate_for_login`(
IN `usernameparam` VARCHAR(50),
IN `passparam` VARCHAR(150),
IN `todaydt` DATETIME,
OUT `usertype` INT,
OUT `userkeyparam` INT,
OUT `userauthtype` INT
)
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
      AND   c.isoffline = 0
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

      SELECT    *
      FROM    user
      INNER JOIN  customer ON user.customerno = customer.customerno
      INNER JOIN  android_version
      WHERE     userid = useridparam AND isdeleted =0 AND customer.isoffline = 0;
    END;
  END IF;

END$$
DELIMITER ;



UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 449;
