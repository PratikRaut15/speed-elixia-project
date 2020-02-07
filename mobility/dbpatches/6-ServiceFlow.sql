
DROP table `serviceflow` ;
CREATE TABLE IF NOT EXISTS `serviceflow` (
  `serviceflowid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `serviceflow`
--

INSERT INTO `serviceflow` (`serviceflowid`, `name`) VALUES
(1, 'VIEWED'),
(2, 'DEPARTED'),
(3, 'STARTED'),
(4, 'ENDED'),
(5, 'CLOSED'),
(6, 'WRAPPING'),
(7, 'DELAYED'),
(8, 'CHANGING'),
(9, 'CANCELLED'),
(10, 'PANIC');

UPDATE devices SET version = 'v2', curversion = 'v2';


ALTER TABLE `notifications` ADD `status` INT NOT NULL AFTER `notifid` ;



 INSERT INTO `dbpatches` (  `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 6, NOW(), 'vishu','Some Fancy Patch');