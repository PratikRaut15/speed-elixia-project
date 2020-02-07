/**
# Author: Ranjeet Kasture
# Date created: 22-04-2019
# Date pushed to UAT: 22-04-2019
# Description:
# 1.This is patch is used to create 'checkpointwise_stoppage_alerts' table for inserting checkpoint wise stoppage alerts entries
# 2.Before update trigger (checkpointmanage_BEFORE_UPDATE) is created on 'checkpointmanage' table to update and delete vehicle mapping in  'checkpointwise_stoppage_alerts' table
#
***/

/* Create dbpatch */
INSERT INTO `dbpatches` ( 
`
patchid`,
`patchdate
`, 
`appliedby`, 
`patchdesc`,
`isapplied`
) 
VALUES
( 
'702', '2019-04-22 17:00:00', 
'Ranjeet K','Queries for check point wise stoppage alerts','0');

/* Alter table user for chekpoint wise stoppage alerts settings(email,sms,telephone and mobile notification) */
ALTER TABLE `user`
ADD `checkpointwise_email_alert` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'For sending checkpoint wise stoppage alerts via email' AFTER `chk_mobilenotification`,
ADD `checkpointwise_sms_alert` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'For sending checkpoint wise stoppage alerts via sms' AFTER `checkpointwise_email_alert`,
ADD `checkpointwise_telephone_alert` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'For sending checkpoint wise stoppage alerts via telephone' AFTER `checkpointwise_sms_alert`,
ADD `checkpointwise_mobilenotification_alert` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'For sending checkpoint wise stoppage alerts via mobile notification' AFTER `checkpointwise_telephone_alert`;

/* Table creation for checkpointwise_stoppage_alerts */
CREATE TABLE `checkpointwise_stoppage_alerts`
(
   `id` int
(11) NOT NULL AUTO_INCREMENT,
   `checkPointId` int
(11) NOT NULL COMMENT 'This column refers to id from checkpoint table',
   `checkPointTypeId` int
(11) NOT NULL COMMENT 'This column refers to ctid from checkpoint_type table',
   `customerNo` int
(11) NOT NULL COMMENT 'This column refers to customerno from customer table',
   `vehicleId` int
(11) NOT NULL COMMENT 'This column refers to vehicleid from vehicle table',
   `stoppageTimeInMinutes` int
(11) NOT NULL,
   `createdOn` datetime NOT NULL,
   `createdBy` int
(11) NOT NULL,
   `updatedOn` datetime NOT NULL,
   `updatedBy` int
(11) NOT NULL,
   `isAlertSent` tinyint
(1) NOT NULL,
   PRIMARY KEY
(`id`),
   KEY `checkpointId`
(`checkPointId`,`checkPointTypeId`,`customerNo`,`vehicleId`,`isAlertSent`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/* Trigger on checkpointmanmge table afer update when isDeleted = 1 then deleted approriate record from checkpointwise_stoppage_alerts table else update the approriate record */

DROP TRIGGER IF EXISTS `checkpointmanage_BEFORE_UPDATE`;

DELIMITER $$
CREATE TRIGGER `checkpointmanage_BEFORE_UPDATE` BEFORE
UPDATE ON `checkpointmanage` FOR EACH ROW
BEGIN
    SET @checkPointType = (SELECT chktype
    FROM
    checkpoint
    WHERE checkpointid=OLD.checkpointid LIMIT 1);
IF NEW.isdeleted=1 THEN
BEGIN
    DELETE FROM checkpointwise_stoppage_alerts 
      WHERE checkPointId = NEW.checkpointid
        AND customerNo = NEW.customerno
        AND vehicleId = OLD.vehicleid
;
END;
ELSE
BEGIN
    UPDATE checkpointwise_stoppage_alerts SET vehicleId = NEW.vehicleid,
     checkPointTypeId = @checkPointType
     WHERE checkPointId = NEW.checkpointid
        AND customerNo = NEW.customerno
        AND vehicleId = OLD.vehicleid
;
END;
END
IF;  
END$$
DELIMITER ;



/* Updating dbpatche 702 */
UPDATE  dbpatches
SET     patchdate = '2019-04-22 17:00:00'
        ,isapplied =1
WHERE   patchid = 702;

