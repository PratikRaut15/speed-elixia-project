-- Insert SQL here.


CREATE TABLE IF NOT EXISTS `sp_ticket` (
`ticketid` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `ticket_type` varchar(20) NOT NULL,
  `sub_ticket_issue` varchar(100) NOT NULL,
  `customerid` int(11) NOT NULL,
  `eclosedate` date NOT NULL,
  `send_mail_status` tinyint(1) NOT NULL DEFAULT '0',
  `send_mail_to` varchar(20) NOT NULL,
  `priority` varchar(50) NOT NULL,
  `create_on_date` datetime NOT NULL,
  `create_by` int(11) NOT NULL
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 205, NOW(), 'Ganesh','Support tables');
