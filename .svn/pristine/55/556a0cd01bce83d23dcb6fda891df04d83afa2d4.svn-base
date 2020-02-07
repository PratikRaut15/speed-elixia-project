INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'434', NOW(), 'Shrikant Suryawanshi', 'Cron For Realtime Data PDF/XLS Report', '0'
);


INSERT INTO `speed`.`reportMaster` (
`reportId` ,
`reportName` ,
`isdeleted`
)
VALUES (
NULL , 'Realtime Data Report', '0'
);


CREATE TABLE realtimedata(
  rid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  vehicleid INT NOT NULL,
  uid INT NOT NULL,
  groupid INT NOT NULL,
  driverid INT NOT NULL,
  lastupdated VARCHAR(50) NOT NULL,
  status VARCHAR(50) NOT NULL,
  location VARCHAR(250) NOT NULL,
  speed INT NOT NULL,
  distance DECIMAL(6,2) NOT NULL,
  power TINYINT NOT NULL,
  ac_status VARCHAR(50) NOT NULL ,
  door_status VARCHAR(50) NOT NULL ,
  temperature1 VARCHAR(20) NOT NULL ,
  temperature2 VARCHAR(20) NOT NULL ,
  temperature3 VARCHAR(20) NOT NULL ,
  temperature4 VARCHAR(20) NOT NULL ,
  genset1 VARCHAR(50) NOT NULL,
  genset2 VARCHAR(50) NOT NULL,
  humidity VARCHAR(20) NOT NULL,
  is_buzzer TINYINT NOT NULL,
  is_mobiliser TINYINT NOT NULL,
  is_freeze TINYINT NOT NULL,
  customerno INT NOT NULL,
  created_by INT NOT NULL DEFAULT 0,
  created_on DATETIME NOT NULL,
  updated_by INT NOT NULL DEFAULT 0,
  updated_on DATETIME NOT NULL,
  isdeleted TINYINT(1) DEFAULT 0
);


DELIMITER $$
DROP PROCEDURE IF EXISTS `insertRealtimeData`$$
CREATE PROCEDURE `insertRealtimeData`(
  IN vehicleid INT,
  IN uid INT,
  IN groupid INT,
  IN driverid INT,
  IN lastupdated VARCHAR(50),
  IN statusParam VARCHAR(50),
  IN location VARCHAR(250),
  IN speed INT,
  IN distance DECIMAl(6,2),
  IN power TINYINT,
  IN ac_status VARCHAR(50),
  IN door_status VARCHAR(50),
  IN temperature1 VARCHAR(20),
  IN temperature2 VARCHAR(20),
  IN temperature3 VARCHAR(20),
  IN temperature4 VARCHAR(20),
  IN genset1 VARCHAR(50),
  IN genset2 VARCHAR(50),
  IN humidity VARCHAR(20),
  IN is_buzzer TINYINT,
  IN is_mobiliser TINYINT,
  IN is_freeze TINYINT,
  IN custno INT,
  IN userid INT,
  IN todaysdate DATETIME
)
BEGIN

  INSERT INTO realtimedata(
    vehicleid
    ,uid
    ,groupid
    ,driverid
    ,lastupdated
    ,`status`
    ,location
    ,speed
    ,distance
    ,power
    ,ac_status
    ,door_status
    ,temperature1
    ,temperature2
    ,temperature3
    ,temperature4
    ,genset1
    ,genset2
    ,humidity
    ,is_buzzer
    ,is_mobiliser
    ,is_freeze
    ,customerno
    ,created_by
    ,created_on
  ) VALUES (
    vehicleid
    ,uid
    ,groupid
    ,driverid
    ,lastupdated
    ,statusParam
    ,location
    ,speed
    ,distance
    ,power
    ,ac_status
    ,door_status
    ,temperature1
    ,temperature2
    ,temperature3
    ,temperature4
    ,genset1
    ,genset2
    ,humidity
    ,is_buzzer
    ,is_mobiliser
    ,is_freeze
    ,custno
    ,userid
    ,todaysdate

  );
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `get_RealtimeData`$$
CREATE PROCEDURE `get_RealtimeData`(
  IN custno INT,
  IN userid INT,
  IN todaysdate DATE
)
BEGIN

  SELECT vehicleno
    ,unitno
    ,groupname
    ,drivername
  ,driverphone
    ,rtd.lastupdated
    ,`status`
    ,rtd.location
    ,rtd.speed
    ,rtd.distance
    ,rtd.power
    ,rtd.ac_status
    ,rtd.door_status
    ,rtd.temperature1
    ,rtd.temperature2
    ,rtd.temperature3
    ,rtd.temperature4
    ,rtd.genset1
    ,rtd.genset2
    ,rtd.humidity
    ,rtd.is_buzzer
    ,rtd.is_mobiliser
    ,rtd.is_freeze
    ,rtd.customerno
    ,rtd.created_by
    ,rtd.created_on

  FROM realtimedata rtd
  INNER JOIN vehicle on vehicle.vehicleid = rtd.vehicleid
  INNER JOIN unit on unit.uid = rtd.uid
  INNER JOIN driver on driver.driverid = rtd.driverid
  LEFT OUTER JOIN `group` on `group`.groupid = rtd.groupid
  WHERE rtd.customerno = custno
  AND rtd.created_by = userid
  AND date(rtd.created_on) = todaysdate
  AND rtd.isdeleted = 0
  Order by vehicle.kind ASC, vehicle.vehicleno ASC;
END$$
DELIMITER ;


UPDATE  dbpatches
SET   patchdate = NOW()
  , isapplied =1
WHERE   patchid = 434;
