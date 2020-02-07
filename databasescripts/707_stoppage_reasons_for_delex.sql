/**
# Author: Ranjeet Kasture
# Date created: 08-05-2019
# Date pushed to UAT: 08-05-2019
# Description:
# stoppage reasons created for Delex - 132
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
    '707', '2019-05-08 17:00:00',
    'Ranjeet K','stoppage reasons created for Delex - 132','0');

/* stoppage resaons created for delex - 132 in  stoppageReason table*/
    INSERT INTO `stoppageReason` (`reason`, `customerno`, `isdeleted`) VALUES ('Due to Vehicle Break Down', '132', '0');
	INSERT INTO `stoppageReason` (`reason`, `customerno`, `isdeleted`) VALUES ('Traffic Jam', '132', '0');
	INSERT INTO `stoppageReason` (`reason`, `customerno`, `isdeleted`) VALUES ('Met With Accident', '132', '0');
	INSERT INTO `stoppageReason` (`reason`, `customerno`, `isdeleted`) VALUES ('No Entry', '132', '0');
	INSERT INTO `stoppageReason` (`reason`, `customerno`, `isdeleted`) VALUES ('Due to the driver issue', '132', '0');


/* Updating dbpatche 706 */
    UPDATE  dbpatches
    SET     patchdate = '2019-05-08 17:00:00'
            ,isapplied =1
    WHERE   patchid = 707;

