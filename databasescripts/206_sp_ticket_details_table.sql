-- Insert SQL here.


CREATE TABLE IF NOT EXISTS `sp_ticket_details` (
`uid` int(11) NOT NULL,
  `ticketid` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `allot_to` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_by` int(11) NOT NULL,
  `create_on_time` datetime NOT NULL
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 206, NOW(), 'Ganesh','Support tables details');
