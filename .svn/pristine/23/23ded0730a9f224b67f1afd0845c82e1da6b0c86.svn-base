DROP TABLE IF EXISTS smslog;
CREATE TABLE `smslog` (
  `smsid` int(11) NOT NULL AUTO_INCREMENT,
  `mobileno` varchar(10) NOT NULL,
  `message` varchar(500) NOT NULL,
  `response` varchar(500) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
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
, IN vehicleidparam INT
, IN useridparam INT
, IN customernoparam INT
, IN isSmsSentParam TINYINT(1)
, IN todaysdate DATETIME
, OUT smsid INT
)
BEGIN

INSERT INTO smslog (
					mobileno
                    , message
                    , response
                    , vehicleid
                    , userid
                    , customerno
                    , issmssent
                    , inserted_datetime
                    ) 
		VALUES (
					mobilenoparam
					, messageparam
					, responseparam
					, vehicleidparam
                    , useridparam
					, customernoparam
                    , isSmsSentParam
					, todaysdate
                );
SET smsid = LAST_INSERT_ID();
END$$
DELIMITER ;

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (324, NOW(), 'Mrudang Vora','Speed SMS Logs');
