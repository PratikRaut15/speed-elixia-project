CREATE TABLE IF NOT EXISTS `pickupboy` (
`pid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `pickupboyphoto` longtext NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

ALTER TABLE `pickupboy`
 ADD PRIMARY KEY (`pid`);
 
 ALTER TABLE `pickupboy`
MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT;



INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 9, NOW(), 'Shrikant Suryawanshi','User Changes for photo');
