-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `sp_note` (
`noteid` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `note` varchar(255) NOT NULL,
  `create_by` int(11) NOT NULL,
  `create_on_date` datetime NOT NULL
);


-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 208, NOW(), 'Ganesh','Support note add');
