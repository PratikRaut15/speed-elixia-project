INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'689', '2018-03-14 11:30:00', 'Yash Kanakia','Unit Audit Trail', '0');

DROP TRIGGER IF EXISTS before_unit_update;
DELIMITER $$
DROP TRIGGER IF EXISTS after_unit_update $$
CREATE TRIGGER `after_unit_update` AFTER UPDATE ON unit FOR EACH ROW BEGIN
BEGIN
	INSERT INTO unit_audit_trail
	SET
	uid = NEW.uid, 
	unitno = NEW.unitno,
	repairtat = NEW.repairtat, 
	customerno  = NEW.customerno,
	vehicleid  = NEW.vehicleid,
	analog1 = NEW.analog1,
	analog2 = NEW.analog2,
	analog3 = NEW.analog3,
	analog4 = NEW.analog4,
	digitalio = NEW.digitalio,
	door_digitalio  = NEW.door_digitalio,
	isDoorExt = NEW.isDoorExt,
	extra_digital = NEW.extra_digital,
	digitalioupdated = NEW.digitalioupdated,
	door_digitalioupdated = NEW.door_digitalioupdated,
	extra_digitalioupdated = NEW.extra_digitalioupdated,
	extra2_digitalioupdated = NEW.extra2_digitalioupdated,
	command = NEW.command,
	setcom = NEW.setcom,
	commandkey  = NEW.commandkey,
	commandkeyval = NEW.commandkeyval,
	acsensor = NEW.acsensor,
	is_ac_opp = NEW.is_ac_opp,
	gensetsensor = NEW.gensetsensor,
	is_genset_opp = NEW.is_genset_opp,
	transmitterno  = NEW.transmitterno,
	doorsensor = NEW.doorsensor,
	is_door_opp = NEW.is_door_opp,
	fuelsensor  = NEW.fuelsensor,
	tempsen1  = NEW.tempsen1,
	tempsen2  = NEW.tempsen2,
	tempsen3  = NEW.tempsen3,
	tempsen4  = NEW.tempsen4,
	n1  = NEW.n1,
	n2 = NEW.n2 ,
	n3 = NEW.n3,
	n4 = NEW.n4,
	humidity = NEW.humidity,
	is_panic = NEW.is_panic,
	is_buzzer = NEW.is_buzzer,
	is_mobiliser = NEW.is_mobiliser,
	is_twowaycom = NEW.is_twowaycom,
	is_portable = NEW.is_portable,
	mobiliser_flag = NEW.mobiliser_flag,
	is_freeze = NEW.is_freeze,
	unitprice  = NEW.unitprice,
	userid  = NEW.userid,
	msgkey  = NEW.msgkey,
	trans_statusid  = NEW.trans_statusid,
	type_value = NEW.type_value,
	temp1_intv = NEW.temp1_intv,
	temp2_intv = NEW.temp2_intv,
	temp3_intv = NEW.temp3_intv,
	temp4_intv = NEW.temp4_intv,
	teamid  = NEW.teamid,
	remark = NEW.remark,
	alterremark = NEW.alterremark, 
	issue_type = NEW.issue_type,
	comments = NEW.comments,
	comments_repair = NEW.comments_repair,
	issue = NEW.issue,
	onlease = NEW.onlease,
	isRequiredThirdParty = NEW.isRequiredThirdParty ,
	bc1_inactive = NEW.bc1_inactive,
	bc2_inactive = NEW.bc2_inactive,
	bc3_inactive = NEW.bc3_inactive,
	bc4_inactive = NEW.bc4_inactive,
	get_conversion = NEW.get_conversion,
	unitcost  = NEW.unitcost,
	is_toggle_panic = NEW.is_toggle_panic,
	sellingprice  = NEW.sellingprice,
	consignee_id  = NEW.consignee_id,
	hasDeliverySwitch = NEW.hasDeliverySwitch,
	unit_location_box_number  = NEW.unit_location_box_number,
	updatedBy  = NEW.updatedBy,
	updatedOn = NEW.updatedOn;
END;
END $$
DELIMITER ;


UPDATE  dbpatches
SET     patchdate = '2018-03-14 11:30:00'
        ,isapplied =1
WHERE   patchid = 689;
