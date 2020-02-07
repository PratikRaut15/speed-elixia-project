INSERT INTO `dbpatches` (
`patchid`,
`patchdate`,
`appliedby`,
`patchdesc`,
`isapplied`
)
VALUES
(
'714', '2019-05-27 11:45:00',
'Manasvi Thakur','enable reports annd alerts for 212','0');

update userReportMapping SET isdeleted = 0 WHERE customerno = 212;


update user SET
`mess_mobilenotification`=0,`speed_mobilenotification`=0,`power_mobilenotification`=0,`tamper_mobilenotification`=0,
`chk_mobilenotification`=0,`ac_mobilenotification`=0,`ignition_mobilenotification`=0,`aci_mobilenotification`=0,
`temp_mobilenotification`=0,`panic_mobilenotification`=0,`immob_mobilenotification`=0,`door_mobilenotification`=0,
`chgalert`, `tinyint(4)`, `NO`, ``, NULL, ``
`start_alert`=0,
`stop_alert`=0,
`fuel_alert_sms`=0,
`vehicle_movement_alert`=0
`harsh_break_mobilenotification`=0,`high_acce_mobilenotification`=0,
`sharp_turn_mobilenotification`=0,
`towing_mobilenotification`=0,
`fuel_alert_mobilenotification`=0,`hum_mobilenotification`=0,

`isTempInrangeAlertRequired`=0 where   customerno = 212 AND isdeleted = 0


UPDATE  dbpatches
SET     patchdate = '2019-05-27 11:45:00'
    ,isapplied =1
WHERE   patchid = 714;
