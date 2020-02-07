-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `cash_received` (
`uid` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `given_by` int(11) NOT NULL,
  `received_by` int(11) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0'
); 

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 189, NOW(), 'Ganesh Papde','Petty cash - cash received');
