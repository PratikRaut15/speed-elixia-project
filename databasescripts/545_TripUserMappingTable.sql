INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc, isapplied) 
VALUES ('545', '2018-03-15 07:30:18', 'Sanjeet Shukla', 'Trip User Mapping Table', '0');


CREATE TABLE `tripusers` (
  `tripmapuserid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `customerno` int(10) NOT NULL,
  `tripid` int(11) NOT NULL,
  `addeduserid` int(11) NOT NULL,
  `createdby` int(10) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

UPDATE dbpatches SET isapplied=1 WHERE patchid = 545;
