INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'538', '2018-01-30 14:34:11', 'Shrikant Suryawanshi', 'RouteTat And Route Legacy Changes', '0'
);



ALTER TABLE `route` ADD `routeTat` INT NOT NULL DEFAULT '0' AFTER `routename`;




create table routeHistory(
  routeHistoryId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  routeid INT NOT NULL,
  routename VARCHAR(100),
  routeTat INT NOT NULL,
  customerno INT NOT NULL,
  userid INT NOT NULL,
  isdeleted TINYINT NOT NULL,
  timestamp DATETIME,
  devicekey VARCHAR(200),
  androidid VARCHAR(250),
  isregister DATETIME,
  insertedDatetime DATETIME
);



create table routemanHistory(
  routemanHistoryId  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  rmid INT ,
  routeid INT,
  checkpointid INT,
  timetaken VARCHAR(25),
  distance float,
  sequence INT,
  etaStatus VARCHAR(50),
  eta TIME DEFAULT NULL,
  etd TIME DEFAULT NULL,
  r_eta TIME DEFAULT NULL,
  r_etd TIME DEFAULT NULL,
  customerno int,
  userid int,
  isdeleted int DEFAULT 0,
  timestamp DATETIME,
  insertedDatetime DATETIME
);


create table vehicleroutemanHistory(
  histId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  vrmanid int,
  routeid int,
  vehicleid int ,
  customerno int,
  userid int,
  isdeleted int,
  timestamp DATETIME,
  insertedDatetime DATETIME
);



DELIMITER $$
DROP TRIGGER IF EXISTS before_route_update$$
CREATE TRIGGER before_route_update BEFORE UPDATE ON route
FOR EACH ROW BEGIN
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');


     IF (NEW.`routename` <> OLD.`routename` OR NEW.`routeTat` <> OLD.`routeTat` OR NEW.`isdeleted` <> OLD.`isdeleted`) THEN

        INSERT INTO routeHistory
        (
            routeid,
            routename,
            routeTat,
            customerno,
            userid,
            isdeleted,
            timestamp,
            devicekey,
            androidid,
            isregister,
            insertedDatetime
        )
        VALUES
        (
            OLD.routeid,
            OLD.routename,
            OLD.routeTat,
            OLD.customerno,
            OLD.userid,
            OLD.isdeleted,
            OLD.timestamp,
            OLD.devicekey,
            OLD.androidid,
            OLD.isregister,
            @istDateTime
        );

     END IF;



END$$
DELIMITER ;



DELIMITER $$
DROP TRIGGER IF EXISTS before_routeman_delete$$
CREATE TRIGGER before_routeman_delete BEFORE DELETE ON routeman
FOR EACH ROW BEGIN
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');

    INSERT INTO routemanHistory
        (
            rmid,
            routeid,
            checkpointid,
            timetaken,
            distance,
            sequence,
            etaStatus,
            eta,
            etd,
            r_eta,
            r_etd,
            customerno,
            userid,
            isdeleted,
            timestamp,
            insertedDatetime
        )
    VALUES
        (
            OLD.rmid,
            OLD.routeid,
            OLD.checkpointid,
            OLD.timetaken,
            OLD.distance,
            OLD.sequence,
            OLD.etaStatus,
            OLD.eta,
            OLD.etd,
            OLD.r_eta,
            OLD.r_etd,
            OLD.customerno,
            OLD.userid,
            OLD.isdeleted,
            OLD.timestamp,
            @istDateTime
        );

END$$
DELIMITER ;




DELIMITER $$
DROP TRIGGER IF EXISTS before_vehiclerouteman_delete$$
CREATE TRIGGER before_vehiclerouteman_delete BEFORE DELETE ON vehiclerouteman
FOR EACH ROW BEGIN
    SET @serverTime = now();
    SET @istDateTime = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');

    INSERT INTO vehicleroutemanHistory
        (
            vrmanid,
            routeid,
            vehicleid,
            customerno,
            userid,
            isdeleted,
            timestamp,
            insertedDatetime
        )
    VALUES
        (
            OLD.vrmanid,
            OLD.routeid,
            OLD.vehicleid,
            OLD.customerno,
            OLD.userid,
            OLD.isdeleted,
            OLD.timestamp,
            @istDateTime
        );

END$$
DELIMITER ;



UPDATE dbpatches SET isapplied = 1, patchdate = '2018-01-30 15:00:00' where patchid = 538;
