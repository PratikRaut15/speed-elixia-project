-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;



--
-- Dumping data for table `status`
--



INSERT INTO `status` (`id`, `status`) VALUES
(1, 'acsensor'),
(2, 'checkpoint'),
(3, 'fence'),
(4, 'ignition'),
(5, 'overspeed'),
(6, 'powercut'),
(7, 'tamper'),
(8, 'tempsensor');

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 30, NOW(), 'Ajay Tripathi','Status Table With Data');