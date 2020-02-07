-- Insert SQL here.
CREATE TABLE IF NOT EXISTS `commercial_details`(
  `cdid` int(11) primary key auto_increment,
  `comdetails` varchar(500) NOT NULL,
  `customerno` int(11) not null,
  `entrytime` datetime not null,
  `added_by` int(11) not null,
  `isdeleted` tinyint(1) default 0
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 318, NOW(), 'ganesh','create new table for commercial_details');
