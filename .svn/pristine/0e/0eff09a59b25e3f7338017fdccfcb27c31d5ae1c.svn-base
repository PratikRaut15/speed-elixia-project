

CREATE TABLE IF NOT EXISTS `probityformdata` (
`batchid` int(11) primary key auto_increment,
  `vehicleid` int(11) NOT NULL,
  `vehicleno` varchar(40) NOT NULL,
  `unitno` varchar(11) NOT NULL,
  `customerno` varchar(11) NOT NULL,
  `batchno` varchar(20) NOT NULL,
  `workkey` varchar(10) NOT NULL,
  `starttime` varchar(35) NOT NULL,
  `pmid` int(11) NOT NULL,
  `addedon` datetime NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);



CREATE TABLE IF NOT EXISTS `probitydata_testing` (
`ptid` int(11) primary key auto_increment,
  `vehicleno` varchar(40) NOT NULL,
  `latitude` varchar(11) NOT NULL,
  `longitude` varchar(11) NOT NULL,
  `workkey` varchar(100) NOT NULL,
  `unitno` varchar(30) NOT NULL,
   `direction` varchar(200) NOT NULL,
   `distance` varchar(100) NOT NULL,
   `velocity` varchar(100) NOT NULL,
   `date_time` varchar(100) NOT NULL,
    `batchno` varchar(100) NOT NULL,	
  `manufacturer` varchar(200) NOT NULL,
`customerno` varchar(20) NOT NULL
);


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (358, NOW(), 'Ganesh','Probity data upload changes done ');
