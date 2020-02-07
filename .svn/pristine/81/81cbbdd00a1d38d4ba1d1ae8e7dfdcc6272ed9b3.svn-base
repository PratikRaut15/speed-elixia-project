-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `driver_alerts` (
`dalertid` int(15) NOT NULL,
  `userid` int(15) NOT NULL,
  `driverid` int(15) NOT NULL,
  `customerno` int(25) NOT NULL,
  `alertstatus` tinyint(1) NOT NULL DEFAULT '0',
  `sendby_email` varchar(250) NOT NULL,
  `sendby_sms` varchar(250) NOT NULL,
  `send_before` int(15) NOT NULL
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (265, NOW(), 'Ganesh','Driver alerts');
