

CREATE TABLE `attendance`(
  atid int(11) primary key auto_increment,
  `userid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `onoff` tinyint(2) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(2) NOT NULL DEFAULT '0'
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 19, NOW(), 'Ganesh','secondary sales attendance table');

