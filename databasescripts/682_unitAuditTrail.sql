INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'682', '2018-03-12 11:30:00', 'Yash Kanakia','Unit Audit Trail', '0');


ALTER TABLE unit
 ADD COLUMN updatedBy INT,
 ADD COLUMN updatedOn datetime;

UPDATE unit
SET
updatedBy =298,
updatedOn =NOW()
WHERE customerno = 64;



DROP TABLE IF EXISTS `unit_audit_trail`;
CREATE TABLE `unit_audit_trail` (
  `unit_auditTrailId` INT AUTO_INCREMENT,
  `uid` int(11) NOT NULL ,
  `unitno` varchar(16) NOT NULL,
  `repairtat` date NOT NULL,
  `customerno` int(11) NOT NULL,
  `vehicleid` int(11) NOT NULL,
  `analog1` varchar(5) NOT NULL,
  `analog2` varchar(5) NOT NULL,
  `analog3` varchar(5) NOT NULL,
  `analog4` varchar(5) NOT NULL,
  `digitalio` int(4) NOT NULL DEFAULT '0',
  `door_digitalio` int(11) NOT NULL,
  `isDoorExt` tinyint(1) NOT NULL,
  `extra_digital` tinyint(1) NOT NULL,
  `digitalioupdated` datetime NOT NULL,
  `door_digitalioupdated` datetime NOT NULL,
  `extra_digitalioupdated` datetime NOT NULL,
  `extra2_digitalioupdated` datetime NOT NULL,
  `command` varchar(50) NOT NULL,
  `setcom` tinyint(1) NOT NULL,
  `commandkey` varchar(2) NOT NULL,
  `commandkeyval` varchar(30) NOT NULL,
  `acsensor` tinyint(1) NOT NULL,
  `is_ac_opp` tinyint(1) NOT NULL,
  `gensetsensor` tinyint(1) NOT NULL,
  `is_genset_opp` tinyint(1) NOT NULL,
  `transmitterno` varchar(20) NOT NULL,
  `doorsensor` tinyint(1) DEFAULT '0',
  `is_door_opp` tinyint(1) DEFAULT '0',
  `fuelsensor` int(11) NOT NULL DEFAULT '0',
  `tempsen1` int(11) NOT NULL,
  `tempsen2` int(11) NOT NULL,
  `tempsen3` int(11) NOT NULL,
  `tempsen4` int(11) NOT NULL,
  `n1` int(3) NOT NULL,
  `n2` int(3) NOT NULL,
  `n3` int(3) NOT NULL,
  `n4` int(3) NOT NULL,
  `humidity` int(3) NOT NULL DEFAULT '0',
  `is_panic` tinyint(1) NOT NULL,
  `is_buzzer` tinyint(1) NOT NULL,
  `is_mobiliser` tinyint(1) NOT NULL,
  `is_twowaycom` tinyint(1) NOT NULL,
  `is_portable` tinyint(1) NOT NULL,
  `mobiliser_flag` tinyint(1) NOT NULL,
  `is_freeze` tinyint(1) NOT NULL DEFAULT '0',
  `unitprice` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `msgkey` int(11) NOT NULL,
  `trans_statusid` int(11) NOT NULL,
  `type_value` varchar(10) NOT NULL,
  `temp1_intv` datetime NOT NULL,
  `temp2_intv` datetime NOT NULL,
  `temp3_intv` datetime NOT NULL,
  `temp4_intv` datetime NOT NULL,
  `teamid` int(11) NOT NULL,
  `remark` int(4) NOT NULL,
  `alterremark` text NOT NULL,
  `issue_type` tinyint(1) NOT NULL DEFAULT '0',
  `comments` varchar(50) NOT NULL,
  `comments_repair` varchar(50) NOT NULL,
  `issue` varchar(100) NOT NULL,
  `onlease` tinyint(1) NOT NULL,
  `isRequiredThirdParty` int(11) NOT NULL,
  `bc1_inactive` tinyint(4) NOT NULL,
  `bc2_inactive` tinyint(4) NOT NULL,
  `bc3_inactive` tinyint(4) NOT NULL,
  `bc4_inactive` tinyint(4) NOT NULL,
  `get_conversion` tinyint(1) DEFAULT '0',
  `unitcost` int(11) NOT NULL DEFAULT '0',
  `is_toggle_panic` tinyint(4) NOT NULL DEFAULT '0',
  `sellingprice` int(11) NOT NULL,
  `consignee_id` int(11) DEFAULT NULL,
  `hasDeliverySwitch` tinyint(1) NOT NULL DEFAULT '0',
  `unit_location_box_number` int(11) DEFAULT '0',
  `updatedBy` int(11) DEFAULT NULL,
  `updatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`unit_auditTrailId`),
  KEY `vehicleid` (`vehicleid`),
  KEY `index_customerno` (`customerno`)
) ;



DELIMITER $$
DROP TRIGGER IF EXISTS before_unit_update $$
CREATE TRIGGER `before_unit_update` BEFORE UPDATE ON unit FOR EACH ROW BEGIN
BEGIN
	INSERT INTO unit_audit_trail
	SET
	uid = OLD.uid, 
	unitno = OLD.unitno,
	repairtat = OLD.repairtat, 
	customerno  = OLD.customerno,
	vehicleid  = OLD.vehicleid,
	analog1 = OLD.analog1,
	analog2 = OLD.analog2,
	analog3 = OLD.analog3,
	analog4 = OLD.analog4,
	digitalio = OLD.digitalio,
	door_digitalio  = OLD.door_digitalio,
	isDoorExt = OLD.isDoorExt,
	extra_digital = OLD.extra_digital,
	digitalioupdated = OLD.digitalioupdated,
	door_digitalioupdated = OLD.door_digitalioupdated,
	extra_digitalioupdated = OLD.extra_digitalioupdated,
	extra2_digitalioupdated = OLD.extra2_digitalioupdated,
	command = OLD.command,
	setcom = OLD.setcom,
	commandkey  = OLD.commandkey,
	commandkeyval = OLD.commandkeyval,
	acsensor = OLD.acsensor,
	is_ac_opp = OLD.is_ac_opp,
	gensetsensor = OLD.gensetsensor,
	is_genset_opp = OLD.is_genset_opp,
	transmitterno  = OLD.transmitterno,
	doorsensor = OLD.doorsensor,
	is_door_opp = OLD.is_door_opp,
	fuelsensor  = OLD.fuelsensor,
	tempsen1  = OLD.tempsen1,
	tempsen2  = OLD.tempsen2,
	tempsen3  = OLD.tempsen3,
	tempsen4  = OLD.tempsen4,
	n1  = OLD.n1,
	n2 = OLD.n2 ,
	n3 = OLD.n3,
	n4 = OLD.n4,
	humidity = OLD.humidity,
	is_panic = OLD.is_panic,
	is_buzzer = OLD.is_buzzer,
	is_mobiliser = OLD.is_mobiliser,
	is_twowaycom = OLD.is_twowaycom,
	is_portable = OLD.is_portable,
	mobiliser_flag = OLD.mobiliser_flag,
	is_freeze = OLD.is_freeze,
	unitprice  = OLD.unitprice,
	userid  = OLD.userid,
	msgkey  = OLD.msgkey,
	trans_statusid  = OLD.trans_statusid,
	type_value = OLD.type_value,
	temp1_intv = OLD.temp1_intv,
	temp2_intv = OLD.temp2_intv,
	temp3_intv = OLD.temp3_intv,
	temp4_intv = OLD.temp4_intv,
	teamid  = OLD.teamid,
	remark = OLD.remark,
	alterremark = OLD.alterremark, 
	issue_type = OLD.issue_type,
	comments = OLD.comments,
	comments_repair = OLD.comments_repair,
	issue = OLD.issue,
	onlease = OLD.onlease,
	isRequiredThirdParty = OLD.isRequiredThirdParty ,
	bc1_inactive = OLD.bc1_inactive,
	bc2_inactive = OLD.bc2_inactive,
	bc3_inactive = OLD.bc3_inactive,
	bc4_inactive = OLD.bc4_inactive,
	get_conversion = OLD.get_conversion,
	unitcost  = OLD.unitcost,
	is_toggle_panic = OLD.is_toggle_panic,
	sellingprice  = OLD.sellingprice,
	consignee_id  = OLD.consignee_id,
	hasDeliverySwitch = OLD.hasDeliverySwitch,
	unit_location_box_number  = OLD.unit_location_box_number,
	updatedBy  = OLD.updatedBy,
	updatedOn = OLD.updatedOn;
END;
END $$
DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `fetch_unit_logs`$$
CREATE PROCEDURE `fetch_unit_logs`(
	IN customernoParam INT,
	IN unitIdParam INT,
	IN startdateParam date,
	IN enddateParam date,
	IN limitParam INT
	)
BEGIN

 	DECLARE limitCondition VARCHAR(10);
	
    SET limitCondition = CONCAT(' LIMIT ', limitParam);
    


		SET @STMT = CONCAT("SELECT un.*,u.realname,v.vehicleno FROM unit_audit_trail un INNER JOIN user u on u.userid = un.updatedBy LEFT JOIN vehicle v on v.vehicleid=un.vehicleid WHERE un.customerno =", customernoParam, " AND un.uid =", unitIdParam," AND date(un.updatedOn) BETWEEN '",startdateParam,"' AND '",enddateParam,"' ORDER BY un.updatedOn desc ",limitCondition);
        PREPARE S FROM @STMT;
		EXECUTE S;
		DEALLOCATE PREPARE S; 
			
    END$$
DELIMITER ;




UPDATE  dbpatches
SET     patchdate = '2018-03-12 11:30:00'
        ,isapplied =1
WHERE   patchid = 682;