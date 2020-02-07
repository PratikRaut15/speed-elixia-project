-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `service_category` (
`cid` int(11) NOT NULL primary key auto_increment,
`category_name` varchar(55) NOT NULL,
`customerno` int(11) NOT NULL,
`entrytime` datetime NOT NULL,
`addedby` int(11) NOT NULL,
`updatedtime` datetime default NULL,
`updated_by` int(11) default NULL,
`isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 24, NOW(), 'Ganesh','Create service category');



