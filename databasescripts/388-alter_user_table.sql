
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'388', 'NOW()', 'ganesh', 'Add user mobile notification changes', '0'
);




ALTER TABLE `user` ADD `notification_status` TINYINT(1) NOT NULL DEFAULT '0' AFTER `delivery_vehicleid`;

ALTER TABLE user add mess_mobilenotification tinyint NOT NULL after mess_telephone;
ALTER TABLE user add speed_mobilenotification tinyint NOT NULL after speed_telephone;
ALTER TABLE user add power_mobilenotification tinyint NOT NULL after power_telephone;
ALTER TABLE user add tamper_mobilenotification tinyint NOT NULL after tamper_telephone;
ALTER TABLE user add chk_mobilenotification tinyint NOT NULL after chk_telephone;
ALTER TABLE user add ac_mobilenotification tinyint NOT NULL after ac_telephone;
ALTER TABLE user add ignition_mobilenotification tinyint NOT NULL after ignition_telephone;
ALTER TABLE user add aci_mobilenotification tinyint NOT NULL after aci_telephone ;
ALTER TABLE user add temp_mobilenotification tinyint NOT NULL after temp_telephone;
ALTER TABLE user add panic_mobilenotification tinyint NOT NULL after panic_telephone;
ALTER TABLE user add immob_mobilenotification tinyint NOT NULL after immob_telephone;
ALTER TABLE user add door_mobilenotification tinyint NOT NULL after door_telephone;
ALTER TABLE user add harsh_break_mobilenotification tinyint NOT NULL after harsh_break_telephone;
ALTER TABLE user add high_acce_mobilenotification tinyint NOT NULL after high_acce_telephone;
ALTER TABLE user add sharp_turn_mobilenotification tinyint NOT NULL after sharp_turn_telephone;
ALTER TABLE user add towing_mobilenotification tinyint NOT NULL after towing_telephone;
ALTER TABLE user add fuel_alert_mobilenotification tinyint NOT NULL after fuel_alert_telephone;

ALTER TABLE stoppage_alerts add is_chk_mobilenotification tinyint NOT NULL after is_chk_telephone;
ALTER TABLE stoppage_alerts add is_trans_mobilenotification tinyint NOT NULL after is_trans_telephone;

ALTER TABLE trip_exception_alerts add send_mobilenotification tinyint NOT NULL after send_telephone;



-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 388;
