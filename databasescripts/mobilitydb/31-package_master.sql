-- Insert SQL here.
CREATE TABLE IF NOT EXISTS `package_master` (
	`pckgid` int(11) NOT NULL  primary key auto_increment,
	`package_code` varchar(100) NOT NULL,
	`amount` float(10,2) NOT NULL,
	`validity` date NOT NULL,
	`customerno` int(11) NOT NULL,
	`entrytime` datetime NOT NULL,
	`addedby` int(11) NOT NULL,
	updatedtime datetime default null,
	updated_by int(11) default null,
	  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

ALTER TABLE `client` ADD `pckgid` INT(11) default null;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 31, NOW(), 'Ganesh','Package Master');
