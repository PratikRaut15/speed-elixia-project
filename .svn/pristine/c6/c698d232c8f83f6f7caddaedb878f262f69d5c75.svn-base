-- Insert SQL here.
create database mobility;

use mobility;

CREATE TABLE `dbpatches` (
 `patchid` int(11) NOT NULL,
 `patchdate` datetime NOT NULL,
 `appliedby` varchar(20) NOT NULL,
 `patchdesc` varchar(255) NOT NULL,
 PRIMARY KEY (`patchid`)
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 1, NOW(), 'Akhil VL','mobility database creation');
