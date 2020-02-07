
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'442', '2016-12-27 18:00:07', 'Shrikant Suryawanshi', 'Capture Stoppage Reason', '0'
);


Create table vehicleStoppageReason(
    sid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    vehicleid int NOT NULL,
    starttime datetime NOT NULL,
    endtime datetime NOT NULL,
    lat decimal(11,8),
    lng decimal(11,8),
    reason varchar(100) NOT NULL,
    customerno int NOT NULL,
    created_by int NOT NULL,
    created_on datetime NOT NULL,
    updated_by int NOT NULL,
    updated_on datetime NOT NULL,
    isdeleted tinyint(1) DEFAULT 0
);

ALTER TABLE `vehicleStoppageReason` CHANGE `reason` `reasonid` INT NOT NULL;

CREATE TABLE IF NOT EXISTS `stoppageReason` (
    reasonid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    reason VARCHAR(250) NOT NULL,
    customerno INT NOT NULL,
    created_by INT NOT NULL,
    created_on DATETIME,
    updated_by INT NOT NULL,
    updated_on DATETIME,
    isdeleted TINYINT(1) DEFAULT 0
);

INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Delay From DC/DA Not Available/Loading Delay',328,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Delay -Fresh Produce',328,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Delay -For Buns',328,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Dock Non availability',328,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Delay From Store',328,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Vehicle Not Available/Driver Not Available',0,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Vehicle In Maintenance',0,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Route Breakdown',0,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Octroi/Form Not Available',0,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('In Transit Delay',0,'2016-12-27 11:50:00');
INSERT INTO stoppageReason(reason,customerno,created_on) VALUES ('Reschedule',0,'2016-12-27 11:50:00');


ALTER TABLE `unit` ADD `unitcost` INT NOT NULL DEFAULT '0' AFTER `get_conversion`;


UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 442;
