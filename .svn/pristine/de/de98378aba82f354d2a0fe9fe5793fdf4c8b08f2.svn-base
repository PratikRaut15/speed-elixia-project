INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'397', '2016-07-12 10:58:01', 'Arvind Thakur', 'create report_email_list table,added new columns to vehicle(hum_min,hum_max)', '0'
);

CREATE TABLE `report_email_list` (
  `eid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `customerno` int(11) NOT NULL,
  `email_id` varchar(105) NOT NULL
);

ALTER TABLE report_email_list
ADD COLUMN `created_on` datetime NOT NULL,
ADD COLUMN `updated_on` datetime NOT NULL,
ADD COLUMN `created_by` int NOT NULL,
ADD COLUMN `updated_by` int NOT NULL,
ADD COLUMN `isdeleted` tinyint DEFAULT '0' NOT NULL;


ALTER TABLE vehicle
ADD COLUMN `hum_min` INT(11) NULL DEFAULT 0,
ADD COLUMN `hum_max` INT(11) NULL DEFAULT 0;

ALTER TABLE vehiclewise_alert
ADD COLUMN `hum_active` tinyint(1) NULL DEFAULT 0,
ADD COLUMN `hum_starttime` time DEFAULT '00:00:00',
ADD COLUMN `hum_endtime` time DEFAULT '23:59:00';


ALTER TABLE user
ADD COLUMN `hum_sms` tinyint(1) NULL DEFAULT 0,
ADD COLUMN `hum_email` tinyint(1) NULL DEFAULT 0,
ADD COLUMN `hum_telephone` tinyint(4) NULL DEFAULT 0,
ADD COLUMN `hum_mobilenotification` tinyint(4) NULL DEFAULT 0,
ADD COLUMN `huminterval` varchar(10) NULL;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 397;
