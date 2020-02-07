/**
# Author: Ranjeet Kasture
# Date created: 07-05-2019
# Date pushed to UAT: 07-05-2019
# Description:
# master table created for route type
#
#
***/

/* Create dbpatch */
    INSERT INTO `dbpatches` (
    `patchid`,
    `patchdate`,
    `appliedby`,
    `patchdesc`,
    `isapplied`
    )
    VALUES
    (
    '706', '2019-05-07 17:00:00',
    'Ranjeet K','master created for route type','0');

/* New table created as 'route_type_master' */
   CREATE TABLE `route_type_master` (
	   `id` int(11) NOT NULL AUTO_INCREMENT,
	   `routeTypeId` int(11) NOT NULL COMMENT 'This column is introduced as route types are defined for apt infra and those are ''loaded'' and ''empty'', which are hard coded as 1 and 0 respectively. Now to remove hard coded values and to avoid further error same hard coded values are kept in this column.',
	   `customerNo` int(11) NOT NULL COMMENT 'This column refers to id of customer table',
	   `routeTypeName` varchar(555) NOT NULL,
	   `createdBy` int(11) NOT NULL COMMENT 'This column refers to userid from user table',
	   `createdOn` datetime DEFAULT NULL,
	   `updatedBy` int(11) NOT NULL COMMENT 'This column refers to userid from user table',
	   `updatedOn` datetime NOT NULL,
	   `isDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - not deleted, 1 - deleted',
	   PRIMARY KEY (`id`),
	   UNIQUE KEY `routeTypeId` (`routeTypeId`),
	   KEY `customerNo` (`customerNo`,`createdBy`,`isDeleted`)
	 ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/* Record creation in  route_type_master*/
    insert into route_type_master (routeTypeId,customerNo,routeTypeName) VALUES
    ("0","563","Empty"),
    ("1","563","Loaded"),
    ("2","132","Line Haul"),
    ("3","132","Feeder Haul");



/* Updating dbpatche 706 */
    UPDATE  dbpatches
    SET     patchdate = '2019-05-07 17:00:00'
            ,isapplied =1
    WHERE   patchid = 706;

