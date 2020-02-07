-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `new_sales` (
`orderid` int(11) NOT NULL,
  `orderdate` date NOT NULL,
  `teamid` int(11) NOT NULL,
  `customerno` int(11) NOT NULL,
  `deviceqty` int(11) NOT NULL,
  `devicetype` tinyint(1) NOT NULL,
  `contactno` int(11) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `digitalioupdated` datetime NOT NULL,
  `acsensor` tinyint(1) NOT NULL,
  `is_ac_opp` tinyint(1) NOT NULL,
  `doorsensor` tinyint(1) NOT NULL DEFAULT '0',
  `is_door_opp` tinyint(1) NOT NULL DEFAULT '0',
  `analog1_sen` tinyint(1) NOT NULL,
  `analog2_sen` tinyint(1) NOT NULL,
  `analog3_sen` tinyint(1) NOT NULL,
  `analog4_sen` tinyint(1) NOT NULL,
  `is_panic` tinyint(1) NOT NULL,
  `is_buzzer` tinyint(1) NOT NULL,
  `is_mobiliser` tinyint(1) NOT NULL,
  `type_value` varchar(10) NOT NULL
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
 VALUES ( 218, NOW(), 'Ganesh','New sales order');
