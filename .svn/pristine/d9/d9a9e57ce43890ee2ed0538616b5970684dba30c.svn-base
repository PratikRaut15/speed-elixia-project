-- Insert SQL here.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'378', 'NOW()', 'Shrikant Suryawanshi', 'Telephonic Alert', '0'
);



ALTER TABLE user add mess_telephone tinyint NOT NULL after mess_sms;
ALTER TABLE user add speed_telephone tinyint NOT NULL after speed_sms;
ALTER TABLE user add power_telephone tinyint NOT NULL after power_sms;
ALTER TABLE user add tamper_telephone tinyint NOT NULL after tamper_sms;
ALTER TABLE user add chk_telephone tinyint NOT NULL after chk_sms;
ALTER TABLE user add ac_telephone tinyint NOT NULL after ac_sms;
ALTER TABLE user add ignition_telephone tinyint NOT NULL after ignition_sms;
ALTER TABLE user add aci_telephone tinyint NOT NULL after aci_sms ;
ALTER TABLE user add temp_telephone tinyint NOT NULL after temp_sms;
ALTER TABLE user add panic_telephone tinyint NOT NULL after panic_sms;
ALTER TABLE user add immob_telephone tinyint NOT NULL after immob_sms;
ALTER TABLE user add door_telephone tinyint NOT NULL after door_email;
ALTER TABLE user add harsh_break_telephone tinyint NOT NULL after harsh_break_mail;
ALTER TABLE user add high_acce_telephone tinyint NOT NULL after high_acce_mail;
ALTER TABLE user add sharp_turn_telephone tinyint NOT NULL after sharp_turn_mail;
ALTER TABLE user add towing_telephone tinyint NOT NULL after towing_mail;
ALTER TABLE user add fuel_alert_telephone tinyint NOT NULL after towing_telephone;


ALTER TABLE stoppage_alerts add is_chk_telephone tinyint NOT NULL after is_chk_email;
ALTER TABLE stoppage_alerts add is_trans_telephone tinyint NOT NULL after is_trans_email;


ALTER TABLE trip_exception_alerts add send_telephone tinyint NOT NULL after send_email;

ALTER TABLE customer add total_tel_alert int NOT NULL after smsleft; 
ALTER TABLE customer add tel_alertleft int NOT NULL after total_tel_alert; 


ALTER TABLE vehicle add tel_count int NOT NULL after sms_lock; 
ALTER TABLE vehicle add tel_lock int NOT NULL after tel_count;




-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
		, isapplied =1 
WHERE 	patchid = 378;

