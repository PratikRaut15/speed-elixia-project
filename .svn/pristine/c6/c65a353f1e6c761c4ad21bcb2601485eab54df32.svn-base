
CREATE TABLE IF NOT EXISTS `map_initialise` (
`id` int(11) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `startlat` varchar(25) DEFAULT NULL,
  `startlong` varchar(25) DEFAULT NULL,
  `customerno` int(11) DEFAULT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

ALTER TABLE `map_initialise`
 ADD PRIMARY KEY (`id`);


INSERT INTO `map_initialise` (`id`, `location`, `startlat`, `startlong`, `customerno`, `isdeleted`) VALUES
(1, 'kurla, Mumbai', '19.03590687086149', '72.94649211215824', 151, 0),
(2, 'Azadpur Delhi', '28.7102951', '77.17524479999997', 168, 0);

ALTER TABLE `map_initialise`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
 

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 25, NOW(), 'Shrikant Suryawanshi','lat long table for delivery map initialise');


