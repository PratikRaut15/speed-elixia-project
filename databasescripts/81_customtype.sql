-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `customtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `customtype`
--

INSERT INTO `customtype` (`id`, `name`) VALUES
(1, 'Digital Connection'),
(2, 'Administrator'),
(3, 'Tracker'),
(4, 'Master'),
(5, 'State Head'),
(6, 'District Head'),
(7, 'City Head'),
(8, 'Branch Head'),
(9, 'Nation'),
(10, 'State'),
(11, 'District'),
(12, 'City'),
(13, 'Group');

INSERT INTO  `maintenance_status` (`id` ,`name`)VALUES (NULL ,  'Incomplete'
);

ALTER TABLE  `vehicle` ADD  `status_id` TINYINT( 1 ) NOT NULL;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 81, NOW(), 'Ajay Tripathi','custom type table and status');
