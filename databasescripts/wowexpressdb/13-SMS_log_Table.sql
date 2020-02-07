
DROP table IF EXISTS `forgot_password_request`;
CREATE TABLE `forgot_password_request` (
  `fpassid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `otp` INT NOT NULL,
  `validupto` datetime DEFAULT NULL,
  `isused` tinyint(1) NOT NULL,
  `request_counter` tinyint(4) NOT NULL,
  `created_on` DATETIME,
  `updated_on` DATETIME,
  `isdeleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`fpassid`)
);

DROP TABLE IF EXISTS smslog;
CREATE TABLE `smslog` (
  `smsid` int(11) NOT NULL AUTO_INCREMENT,
  `mobileno` varchar(10) NOT NULL,
  `message` varchar(500) NOT NULL,
  `response` varchar(500) NOT NULL,
  `userid` int(11) NOT NULL,
  `issmssent` tinyint(1) DEFAULT '0',
  `inserted_datetime` datetime DEFAULT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`smsid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DELIMITER $$
DROP PROCEDURE IF EXISTS insert_smslog$$
CREATE PROCEDURE `insert_smslog`(
IN mobilenoparam VARCHAR(10)
, IN messageparam VARCHAR(250)
, IN responseparam VARCHAR(250)
, IN useridparam INT
, IN isSmsSentParam TINYINT(1)
, IN todaysdate DATETIME
, OUT smsid INT
)
BEGIN

INSERT INTO smslog (
		     mobileno
                    , message
                    , response
                    , userid
                    , issmssent
                    , inserted_datetime
                    ) 
		VALUES (
					mobilenoparam
					, messageparam
					, responseparam
                    , useridparam
                    , isSmsSentParam
					, todaysdate
                );
SET smsid = LAST_INSERT_ID();
END$$
DELIMITER ;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (13, NOW(), 'Ganesh','Wow SMS Logs');
