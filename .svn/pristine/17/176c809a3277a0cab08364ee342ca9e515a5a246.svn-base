-- Insert SQL here.
CREATE TABLE IF NOT EXISTS `city_master` (
 `cityid` int(11) NOT NULL primary key auto_increment,
  `cityname` varchar(105) NOT NULL,
  `customerno` int(15) NOT NULL,
  `entrytime` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
updatedtime datetime default null,
updated_by int(11) default null,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 2, NOW(), 'Ganesh','City Master');
