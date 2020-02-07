-- Insert SQL here.


CREATE TABLE IF NOT EXISTS `feedback_master` (
`fid` int(11) NOT NULL primary key auto_increment,
  `feedback_question` varchar(250) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

CREATE TABLE IF NOT EXISTS `feedback_options` (
`oid` int(11) NOT NULL primary key auto_increment,
  `fid` int(11) NOT NULL,
  `options_value` varchar(250) NOT NULL,
  `customerno` int(11) NOT NULL,
  `entrytime` datetime NOT NULL,
  `addedby` int(11) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 21, NOW(), 'Ganesh','Feedback masters tables');



