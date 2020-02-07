-- Insert SQL here.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'404', '2016-08-24 18:11:01', 'Ganesh', 'alter user for gcm notification ', '0'
);


ALTER TABLE `user` 
CHANGE `mess_mobilenotification` `mess_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `speed_mobilenotification` `speed_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `power_mobilenotification` `power_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `tamper_mobilenotification` `tamper_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1',
CHANGE `chk_mobilenotification` `chk_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1',
CHANGE `ac_mobilenotification` `ac_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1',
CHANGE `ignition_mobilenotification` `ignition_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `aci_mobilenotification` `aci_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1',
CHANGE `temp_mobilenotification` `temp_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1',
CHANGE `panic_mobilenotification` `panic_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `immob_mobilenotification` `immob_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `door_mobilenotification` `door_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `harsh_break_mobilenotification` `harsh_break_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `high_acce_mobilenotification` `high_acce_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `sharp_turn_mobilenotification` `sharp_turn_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `towing_mobilenotification` `towing_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `fuel_alert_mobilenotification` `fuel_alert_mobilenotification` TINYINT(4) NOT NULL DEFAULT '1', 
CHANGE `notification_status` `notification_status` TINYINT(1) NOT NULL DEFAULT '1', 
CHANGE `hum_mobilenotification` `hum_mobilenotification` TINYINT(4) NULL DEFAULT '1';

UPDATE `speed`.`user` SET 
`mess_mobilenotification` = '1', 
`speed_mobilenotification` = '1', 
`power_mobilenotification` = '1', 
`tamper_mobilenotification` = '1', 
`chk_mobilenotification` = '1', 
`ac_mobilenotification` = '1', 
`ignition_mobilenotification` = '1',
 `aci_mobilenotification` = '1', 
`temp_mobilenotification` = '1', 
`panic_mobilenotification` = '1',
`immob_mobilenotification` = '1',
`door_mobilenotification` = '1', 
`harsh_break_mobilenotification` = '1', 
`high_acce_mobilenotification` = '1', 
`sharp_turn_mobilenotification` = '1', 
`towing_mobilenotification` = '1', 
`fuel_alert_mobilenotification` = '1', 
`notification_status` = '1',
`hum_mobilenotification` = '1' ;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 404;
