-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 09, 2014 at 06:24 PM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `speed`

--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--


DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `notid` int(11) NOT NULL AUTO_INCREMENT,
  `notification` text NOT NULL,
  `type` int(11) NOT NULL,
  `isnotified` tinyint(1) NOT NULL,
  `customerno` int(11) NOT NULL,
  PRIMARY KEY (`notid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;


--
-- Dumping data for table `notifications`
--


 INSERT INTO `notifications` (`notid`, `notification`, `type`, `isnotified`, `customerno`) VALUES

(1, '<br/><table cellpadding="0" style="border-collapse:collapse;"><tr><td style="border:none;\r\n   outline:none;"><div class="support"></div></td> <td style="border:none;\r\n   outline:none;">Need Support?<br/>25137470 / 71</td></tr></table>', 1, 0, 0),

(2, '<br/><table cellpadding="0" style="border-collapse:collapse;"><tr><td style="border:none;\r\n   outline:none;"><div class="android"></div></td> <td style="border:none;\r\n   outline:none;">Launch of Android App on January 26</td></tr></table>', 1, 0, 0),

(3, '<br/><font color="#000000">Manage your Field Work Force, call us on 25137470 / 71.</font>', 1, 0, 0),

(4, '<br/><font color="#000000">For Load Sensor installation, call us on 25137470 / 71. </font>', 1, 0, 0),

(5, '<br/><table cellpadding="0" style="border-collapse:collapse;"><tr><td style="border:none;\r\n   outline:none;"><a href="https://twitter.com/ElixiaTech" target="_blank"><div class="twitter"></div></a></td> <td style="border:none;\r\n   outline:none;">Follow us on Twitter</td></tr></table>', 1, 0, 0),

(6, '<br/><table cellpadding="0" style="border-collapse:collapse;"><tr><td style="border:none;\r\n   outline:none;"><a href=" https://www.facebook.com/pages/Elixia-Tech-Solutions-Pvt-Ltd/172635942858104" target="_blank"><div class="facebook"></div></a></td> <td style="border:none;\r\n   outline:none;">Like us on Facebook</td></tr></table>', 1, 0, 0),

(7, '<br/><table cellpadding="0" style="border-collapse:collapse;"><tr><td style="border:none;\r\n   outline:none;"><a href=" http://www.linkedin.com/company/2778306?trk=tyah&trkInfo=tas%3Aelixia%20tech%20solutions" target="_blank"><div class="linkedin"></div></a></td> <td style="border:none;\r\n   outline:none;">Connect with us on LinkedIn</td></tr></table>', 1, 0, 0),

(8, '<br/><font color="#000000">This system is trusted by</font>', 1, 0, 0),

(9, '<br/><font color="#000000">For Genset Sensor installation, call us on 25137470 / 71. </font>', 1, 0, 0),

(10, '<br/><font color="#000000">For AC Sensor installation, call us on 25137470 / 71. </font>', 1, 0, 0),

(11, '<br/><font color="#00000">For Temperature Sensor installation, call us on 25137470 / 71. </font>', 1, 0, 0),

(13, '<font color="#000000">Realtime Data: Click on Location to open map</font>', 2, 0, 0),

(14, '<font color="#000000">Realtime Data: Double Click on Vehicle No. to change</font>', 2, 0, 0),

(15, '<font color="#000000">Contract Information:<br/><a href="http://www.elixiaspeed.com/modules/user/accinfo.php?id=2" target="_blank">Click Here</a></font>', 3, 0, 0),

(16, '<font color="#000000">See all vehicles on map,<br/><a href="http://www.elixiaspeed.com/modules/map/map.php" target="_blank">Click Here</a></font>', 3, 0, 0),

(17, '<font color="#000000">Change Password:<br/><a href="http://www.elixiaspeed.com/modules/user/accinfo.php" target="_blank">Click Here</a></font>', 3, 0, 0),

(18, '<font color="#000000">Modify Account Details:<br/><a href="http://www.elixiaspeed.com/modules/user/accinfo.php" target="_blank">Click Here</a></font>', 3, 0, 0),

(19, '<font color="#000000">Set Alerts:<br/><a href="http://www.elixiaspeed.com/modules/user/accinfo.php?id=3" target="_blank">Click Here</a></font>', 3, 0, 0),

(20, '<font color="#000000">Vehicle Route History Report:<br/><a href="http://www.elixiaspeed.com/modules/reports/reports.php" target="_blank">Click Here</a></font>', 3, 0, 0),

(21, '<font color="#000000">Travel History Report:<br/><a href="http://www.elixiaspeed.com/modules/reports/reports.php?id=2" target="_blank">Click Here</a></font>', 3, 0, 0),

(22, '<font color="#000000">Vehicle Report:<br/><a href="http://www.elixiaspeed.com/modules/reports/reports.php?id=3" target="_blank">Click Here</a></font>', 3, 0, 0),

(23, '<font color="#000000">Graphical Report:<br/><a href="http://www.elixiaspeed.com/modules/reports/reports.php?id=5" target="_blank">Click Here</a></font>', 3, 0, 0),

(24, '<font color="#000000">Checkpoint Report:<br/><a href="http://www.elixiaspeed.com/modules/reports/reports.php?id=6" target="_blank">Click Here</a></font>', 3, 0, 0),

(25, '<font color="#000000">Genset / AC Sensor Report:<br/><a href="http://www.elixiaspeed.com/modules/reports/reports.php?id=7" target="_blank">Click Here</a></font>', 3, 0, 0),

(26, '<font color="#000000">Trip Report:<br/><a href="http://www.elixiaspeed.com/modules/reports/reports.php?id=8" target="_blank">Click Here</a></font>', 3, 0, 0),

(27, '<font color="#000000">Create Routes:<br/><a href="http://www.elixiaspeed.com/modules/route/route.php" target="_blank">Click Here</a></font>', 3, 0, 0),

(28, '<font color="#000000">Create Vehicle Groups:<br/><a href="http://www.elixiaspeed.com/modules/group/group.php?id=2" target="_blank">Click Here</a></font>', 3, 0, 0),

(29, '<font color="#000000">Manage Vehicles:<br/><a href="http://www.elixiaspeed.com/modules/vehicle/vehicle.php?id=2" target="_blank">Click Here</a></font>', 3, 0, 0),

(30, '<font color="#000000">Manage Drivers:<br/><a href="http://www.elixiaspeed.com/modules/driver/driver.php?id=2" target="_blank">Click Here</a></font>', 3, 0, 0),

(31, '<font color="#000000">Add a New User:<br/><a href="http://www.elixiaspeed.com/modules/account/users.php?id=1" target="_blank">Click Here</a></font>', 3, 0, 0),

(32, '<font color="#000000">Manage Users:<br/><a href="http://www.elixiatech.com/speed/modules/account/users.php?id=2" target="_blank">Click Here</a></font>', 3, 0, 0),
(33, '<br/>Know more about us: <a href="http://www.elixiatech.com" target="_blank">www.elixiatech.com</a>', 1, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 41, NOW(), 'Ajay Tripathi','New Notification Table');
