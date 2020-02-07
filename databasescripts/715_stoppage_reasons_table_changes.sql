/**
# Author: Ranjeet Kasture
# Date created: 01-06-2019
# Date pushed to UAT: 01-06-2019
# Description:
# Created new stoppage reason as 'Others' in stoppageReason table
# Added new column as remarks in vehiclestoppagereason table
# 
#
***/

/* Create dbpatch */
    INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`, `isapplied`, `updatedOn`) VALUES ('715', '2019-06-01 18:00:00', 'Ranjeet K', 'table changes for stoppage reasons others', '0', '2019-06-01 18:00:00');

INSERT INTO `stoppageReason` (`reason`, `customerno`, `created_by`, `created_on`, `updated_by`, `updated_on`, `isdeleted`) VALUES ('Others', '0', '0', '2019-05-31 17:30:00', '0', '0', '0');

ALTER TABLE `vehiclestoppagereason` ADD `remarks` TEXT NOT NULL AFTER `reasonid`;


/* Updating dbpatche 715 */
    UPDATE  dbpatches
    SET     updatedOn = DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE),isapplied =1
    WHERE   patchid = 715;

