
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'481', '2017-03-14 14:49:00', 'Shrikant Suryawanshi', 'Route Fence', '0'
);


 
CREATE TABLE `routefence` (
  `fenceid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `customerno` int(11) NOT NULL,
  `fencename` varchar(100) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `routegeofence` (
  `geofenceid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `fenceid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `geolat` float NOT NULL,
  `geolong` float NOT NULL,
  `latfloor` int NOT NULL,
  `longfloor` int NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `routefenceman` (
  `fmid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `fenceid` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `conflictstatus` tinyint(1) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `routefenceman`
  ADD KEY `fenceid` (`fenceid`);



UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 481;
