-- Insert SQL here.

CREATE TABLE IF NOT EXISTS `parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_name` text NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `part_name`, `customerno`, `userid`, `timestamp`, `isdeleted`) VALUES
(1, 'Air filters', 0, 0, '2014-02-04 18:58:28', 0),
(2, 'Anti freeze', 0, 0, '2014-02-04 18:58:45', 0),
(3, 'Brake disc', 0, 0, '2014-02-04 18:59:19', 0),
(4, 'Brake pads', 0, 0, '2014-02-04 18:59:25', 0),
(5, 'Distributor caps', 0, 0, '2014-02-04 18:59:53', 0),
(6, 'Engine oil', 0, 0, '2014-02-04 19:00:02', 0),
(7, 'Fuel filters', 0, 0, '2014-02-04 19:00:38', 0),
(8, 'Glow plugs', 0, 0, '2014-02-04 19:00:45', 0),
(9, 'Grease', 0, 0, '2014-02-04 19:01:09', 0),
(10, 'Oil filters', 0, 0, '2014-02-04 19:01:19', 0),
(11, 'Other filters', 0, 0, '2014-02-04 19:01:49', 0),
(12, 'Other fluids', 0, 0, '2014-02-04 19:01:57', 0),
(13, 'Part a', 0, 0, '2014-02-04 19:02:16', 0),
(14, 'Pollen filters', 0, 0, '2014-02-04 19:02:24', 0),
(15, 'Rotor arms', 0, 0, '2014-02-04 19:02:43', 0),
(16, 'Spark plugs', 0, 0, '2014-02-04 19:02:50', 0),
(17, 'Steering fluid', 0, 0, '2014-02-04 19:03:11', 0),
(18, 'Suspension fluid', 0, 0, '2014-02-04 19:03:18', 0),
(19, 'Timing belts', 0, 0, '2014-02-04 19:03:39', 0),
(20, 'Transmission oil', 0, 0, '2014-02-04 19:03:47', 0),
(21, 'Wiper blades', 0, 0, '2014-02-04 19:04:06', 0);


CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` text NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `task_name`, `customerno`, `userid`, `timestamp`, `isdeleted`) VALUES
(1, 'Car wash', 0, 0, '2014-02-04 18:46:19', 0),
(2, 'Check all lights', 0, 0, '2014-02-04 18:46:43', 0),
(3, 'Check and flush engine coolant', 0, 0, '2014-02-04 18:47:08', 0),
(4, 'Check and replace if rubber boots are cracked', 0, 0, '2014-02-04 18:47:24', 0),
(5, 'Check, clean or replace battery terminals', 0, 0, '2014-02-04 18:47:52', 0),
(6, 'Check level and refill brake fluid', 0, 0, '2014-02-04 18:48:00', 0),
(7, 'Check level and refill power steering fluid', 0, 0, '2014-02-04 18:48:24', 0),
(8, 'Check level and refill transmission fluid', 0, 0, '2014-02-04 18:48:32', 0),
(9, 'Check or refill windshield washer fluid', 0, 0, '2014-02-04 18:48:59', 0),
(10, 'Check or replace air filter', 0, 0, '2014-02-04 18:49:08', 0),
(11, 'Check or replace fuel filter', 0, 0, '2014-02-04 18:49:39', 0),
(12, 'Check or replace oil filter', 0, 0, '2014-02-04 18:49:47', 0),
(13, 'Check or replace spark plugs', 0, 0, '2014-02-04 18:50:10', 0),
(14, 'Check or replace the engine oil', 0, 0, '2014-02-04 18:50:18', 0),
(15, 'Grease and lubricate components', 0, 0, '2014-02-04 18:50:41', 0),
(16, 'Inspect or replace brake pads', 0, 0, '2014-02-04 18:50:49', 0),
(17, 'Inspect or replace timing belt and other belts', 0, 0, '2014-02-04 18:51:15', 0),
(18, 'Inspect or replace windshield wipers', 0, 0, '2014-02-04 18:51:23', 0),
(19, 'Inspect tyres for pressure and wear', 0, 0, '2014-02-04 18:51:46', 0),
(20, 'Lubricate locks, latches, hinges', 0, 0, '2014-02-04 18:51:55', 0),
(21, 'Read fault codes from the engine control unit', 0, 0, '2014-02-04 18:52:19', 0),
(22, 'Test electronics', 0, 0, '2014-02-04 18:52:27', 0),
(23, 'Tighten chassis nuts and bolts', 0, 0, '2014-02-04 18:52:51', 0),
(24, 'Top up battery fluid', 0, 0, '2014-02-04 18:52:59', 0),
(25, 'Tune the engine', 0, 0, '2014-02-04 18:53:26', 0),
(26, 'Tyre balancing', 0, 0, '2014-02-04 18:53:34', 0),
(27, 'Tyre rotation', 0, 0, '2014-02-04 18:53:59', 0),
(28, 'Wheel alignment', 0, 0, '2014-02-04 18:54:06', 0);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 49, NOW(), 'Ajay Tripathi','parts and task table');
