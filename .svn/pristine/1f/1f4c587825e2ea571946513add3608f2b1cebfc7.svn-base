CREATE TABLE IF NOT EXISTS `probity` (
`batchid` int(11) primary key auto_increment,
  `vehicleid` varchar(15) NOT NULL,
  `customerno` varchar(11) NOT NULL,
  `batchno` varchar(20) NOT NULL,
  `workkey` varchar(10) NOT NULL,
  `starttime` varchar(35) NOT NULL,
  `dummybatch` varchar(15) NOT NULL,
  `pmid` int(11) NOT NULL,
  `addedon` datetime NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `last_fid` int(11) DEFAULT NULL
);

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (354, NOW(), 'Ganesh','for probity ui upload data entry insert table');
