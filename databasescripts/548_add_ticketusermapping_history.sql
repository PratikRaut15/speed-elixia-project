INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'548', '2018-03-16 16:20:00', 'Manasvi Thakur', 'Speed : To get db patch number', '0'
);




/* Table structure for table `ticket_user_mapping*/


CREATE TABLE IF NOT EXISTS `ticket_user_mapping` (
`mapid` int(20) NOT NULL AUTO_INCREMENT,
  `ticketid` int(50) NOT NULL,
  `userid` int(200) NOT NULL,
  `username` varchar(500) NOT NULL,
  `is_deleted` int(20) NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (mapid)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


/* Triggers `ticket_user_mapping*/

DELIMITER $$
CREATE TRIGGER `before_delete_from_ticketusermapping` BEFORE DELETE ON `ticket_user_mapping`
 FOR EACH ROW BEGIN
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
 BEGIN
 	INSERT INTO ticket_user_mapping_history
 				(
 					mapid,
 					ticketid,
 					userid,
 					username,
 					is_deleted,
 					updatedOn
 				)
 				VALUES (
 					OLD.mapid,
 					OLD.ticketid,
 					OLD.userid,
 					OLD.username,
 					OLD.is_deleted,
 					@istDateTime
 					);
 	END;
END $$

DELIMITER ;



/*
 Create Table for table `ticket_user_mapping_history`
*/

CREATE TABLE IF NOT EXISTS `ticket_user_mapping_history` (
`id` int(20) NOT NULL AUTO_INCREMENT,
  `mapid` int(20) NOT NULL,
  `ticketid` int(50) NOT NULL,
  `userid` int(200) NOT NULL,
  `username` varchar(500) NOT NULL,
  `is_deleted` varchar(2) NOT NULL,
  `updatedOn` datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


DELIMITER $$
DROP PROCEDURE IF EXISTS delete_from_ticketusermapping$$
CREATE PROCEDURE delete_from_ticketusermapping(
IN ticketidIn INT(20))
BEGIN
    DELETE FROM ticket_user_mapping
    WHERE ticketid  =   ticketidIn;

END $$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS insert_into_ticketusermapping$$
CREATE PROCEDURE insert_into_ticketusermapping (
IN ticketid INT(50),
IN userid INT(200),
IN username VARCHAR(500)
)
BEGIN
    DECLARE  istDateTime INT;
    DECLARE  serverTime INT;
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
 ROLLBACK;
    /*
       GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
       @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
       SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
       SELECT @full_error;
*/
    INSERT INTO ticket_user_mapping
    (
        ticketid,
        userid,
        username,
        updatedOn
    )
    VALUES (
    ticketid,
    userid,
    username,
    @istDateTime
    );
END $$
DELIMITER ;


UPDATE  dbpatches
SET isapplied =1
WHERE   patchid = 548;
