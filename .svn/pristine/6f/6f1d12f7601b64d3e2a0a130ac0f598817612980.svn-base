-- Insert SQL here.

-- Successful. Add the Patch to the Applied Patches table.

CREATE TABLE IF NOT EXISTS `exceptional_services` (
`exid` int(11) NOT NULL primary key auto_increment,
`customerno` int(11) NOT NULL,
`trackieid` int(11) NOT NULL,
`serviceid` int(11) NOT NULL,
`entrytime` datetime NOT NULL,
`addedby` int(11) NOT NULL,
`updatedtime` datetime DEFAULT NULL,
`updated_by` int(11) DEFAULT NULL,
`isdeleted` tinyint(1) DEFAULT '0'
);

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 34, NOW(), 'Ganesh','create exceptional services');
