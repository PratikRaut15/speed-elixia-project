INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'571', '2018-06-29 12:27:00', 'Sanjeet Shukla', 'Future Routes', '0'
);

CREATE TABLE `futureRoutes` (
 `frId` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`customerNo` int(10) NOT NULL,
 `vehicleId` int(10) NOT NULL,
`routeId` int(10) NOT NULL,
`frSequence` int(10) NOT NULL,
 `createdBy` int(10) NOT NULL,
 `createdOn` datetime NOT NULL,
 `updatedBy` int(11) NOT NULL,
 `updatedOn` datetime NOT NULL,
 `isDeleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `futureRoutes` ADD `nextRouteId` INT NOT NULL AFTER `routeId`;

UPDATE dbpatches SET isapplied=1 WHERE patchid = 571;
