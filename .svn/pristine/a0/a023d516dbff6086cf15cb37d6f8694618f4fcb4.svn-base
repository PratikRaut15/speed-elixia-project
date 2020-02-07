INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'551', '2018-03-16 16:20:00', 'Manasvi Thakur', 'Speed : To get db patch number', '0'
);

/*Add createdon , updatedon columns in group table*/
ALTER TABLE `group`  ADD `createdOn` DATETIME NOT NULL ,  ADD `updatedOn` DATETIME NOT NULL ;

/*Create new table group_history*/
CREATE TABLE IF NOT EXISTS `group_history` (
`grouphistid` int(11) NOT NULL,
`groupid` int(11) NOT NULL,
  `groupname` varchar(100) NOT NULL,
  `cityid` int(11) NOT NULL,
  `code` varchar(225) NOT NULL,
  `address` text NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL,
  `createdOn` datetime NOT NULL,
  `updatedOn` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

ALTER TABLE `group_history`
 ADD PRIMARY KEY (`grouphistid`), ADD KEY `index_customerno` (`customerno`);

ALTER TABLE `group_history`
MODIFY `grouphistid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
DELIMITER $$
/*--------------End group_history query------------------*/

/*Add createdon , updatedon columns in city table*/
ALTER TABLE `city`  ADD `createdOn` DATETIME NOT NULL ,  ADD `updatedOn` DATETIME NOT NULL ;

/*Create new table city_history*/
CREATE TABLE IF NOT EXISTS `city_history` (
`cityhistid` int(11) NOT NULL,
`cityid` int(11) NOT NULL,
  `districtid` int(11) NOT NULL,
  `code` varchar(225) NOT NULL,
  `address` text NOT NULL,
  `name` varchar(225) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


ALTER TABLE `city_history`
 ADD PRIMARY KEY (`cityhistid`);

ALTER TABLE `city_history`
MODIFY `cityhistid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
DELIMITER $$
/*-----------End city_history query--------------*/



/*Add createdon , updatedon columns in district table*/
ALTER TABLE `district`  ADD `createdOn` DATETIME NOT NULL ,  ADD `updatedOn` DATETIME NOT NULL ;

/*Create new table zone_history*/
CREATE TABLE IF NOT EXISTS `district_history` (
`districthistid` int(11) NOT NULL,
`districtid` int(11) NOT NULL,
  `stateid` int(11) NOT NULL,
  `code` varchar(225) NOT NULL,
  `address` text NOT NULL,
  `name` varchar(225) NOT NULL,
  `customerno` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


ALTER TABLE `district`
 ADD PRIMARY KEY (`districtid`);

ALTER TABLE `district`
MODIFY `districtid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
DELIMITER $$
/*-------------End district_history query----------------*/



UPDATE  `dbpatches`
SET `isapplied` =1
WHERE   patchid = 551;
