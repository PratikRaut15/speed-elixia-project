-- Insert SQL here.


CREATE TABLE IF NOT EXISTS `smslog` (
`smsid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`mobileno` varchar(10) NOT NULL,
`message` varchar(250) NOT NULL,
`orderid` int(11) NOT NULL,
`activityid` int(11) DEFAULT NULL,
`customerno` int(11) NOT NULL,
`issmssent` tinyint(1) default 0,
 `inserted_datetime` datetime 
) 



CREATE TABLE IF NOT EXISTS `emaillog` (
`emailid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`to_emailid` varchar(50) NOT NULL,
`subject` varchar(250) NOT NULL,
`messagebody` TEXT NOT NULL,
`orderid` int(11) NOT NULL,
`activityid` int(11) DEFAULT NULL,
`customerno` int(11) NOT NULL,
`isemailsent` tinyint(1) default 0,
 `inserted_datetime` datetime 
) 


DELIMITER $$
DROP PROCEDURE IF EXISTS insert_smslog$$

CREATE PROCEDURE insert_smslog( 
IN mobileno INT,
IN message varchar(250), 
IN orderid INT,
IN activityid INT,
IN customerno INT,
IN issmssent tinyint(1),
IN inserted_datetime datetime
)

BEGIN

INSERT INTO smslog (mobileno,message,orderid,activityid,customerno,issmssent,inserted_datetime) VALUES (mobileno, message,orderid,activityid,customerno, issmssent,inserted_datetime);

END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS insert_emaillog$$

CREATE PROCEDURE insert_emaillog( 
IN to_emailid varchar(50),
IN subject varchar(250), 
IN messagebody TEXT,
IN orderid INT,
IN activityid INT,
IN customerno INT,
IN isemailsent tinyint(1),
IN inserted_datetime datetime
)

BEGIN

INSERT INTO emaillog (to_emailid,subject,messagebody,orderid,activityid,customerno,isemailsent,inserted_datetime) VALUES (to_emailid,subject,messagebody,orderid,activityid,customerno,isemailsent,inserted_datetime);

END$$
DELIMITER ;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 3, NOW(), 'Ganesh','Sms and email log');
