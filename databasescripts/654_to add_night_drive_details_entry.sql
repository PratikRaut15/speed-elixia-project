INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES (654, '2019-01-22 18:40:00', 'Manasvi Thakur','Added SP-get_sms_consume_frm_smsmlog_for_team to get team SMSM log count as well');

INSERT INTO `night_drive_details` (`start_time`, `end_time`, `threshold_distance`, `customerno`, `createdBy`, `createdOn`, `updatedBy`, `updatedOn`, `isDeleted`) VALUES
('21:30:00', '07:00:00', 500, 415, 0, '2019-01-22 18:40:00', 0, '0000-00-00 00:00:00', 0);


UPDATE dbpatches SET 
updatedOn = '2019-01-22 18:40:00'
,isapplied = 1 WHERE patchid = 654;