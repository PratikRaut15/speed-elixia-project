

CREATE TABLE IF NOT EXISTS `branch` (
  `branchid` int(11) NOT NULL AUTO_INCREMENT,
  `branchname` varchar(255) NOT NULL,
  `customerno` int(11) NOT NULL,
  PRIMARY KEY (`branchid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchid`, `branchname`) VALUES
(1, 'powai');



ALTER TABLE `user` ADD `branchid` INT NOT NULL AFTER `customerno` ;

ALTER TABLE `client` ADD `branchid` INT NOT NULL AFTER `clientid` ;

ALTER TABLE `servicecall` ADD `branchid` INT NOT NULL AFTER `customerno` ;
ALTER TABLE `trackee` ADD `branchid` INT NOT NULL AFTER `customerno` ;
ALTER TABLE `notifications` ADD `branchid` INT NOT NULL ;

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 11, NOW(), 'vishu','branch table ');