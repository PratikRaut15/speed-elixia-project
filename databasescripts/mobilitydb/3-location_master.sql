-- Insert SQL here.
CREATE TABLE IF NOT EXISTS `location_master` (
`locid` int(11) NOT NULL primary key auto_increment,
`cityid` int(11) NOT NULL,
`location`varchar(105) NOT NULL,
`customerno` int(15) NOT NULL,
`entrytime` datetime NOT NULL,
`addedby` int(11) NOT NULL,
updatedtime datetime default null,
updated_by int(11) default null,
`isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 3, NOW(), 'Ganesh','Location Master');


