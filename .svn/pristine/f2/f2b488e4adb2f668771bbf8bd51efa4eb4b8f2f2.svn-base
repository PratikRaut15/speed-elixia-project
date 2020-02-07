ALTER TABLE `fenceman` CHANGE `isdeleted` `isdeleted` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `checkpointmanage` CHANGE `isdeleted` `isdeleted` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `vehicle` CHANGE `extbatt` `extbatt` VARCHAR(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `vehicle` CHANGE `odometer` `odometer` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0';
ALTER TABLE `vehicle` CHANGE `lastupdated` `lastupdated` DATETIME NULL DEFAULT NULL;
ALTER TABLE `vehicle` CHANGE `curspeed` `curspeed` VARCHAR(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0';
ALTER TABLE `vehicle` CHANGE `driverid` `driverid` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `vehicle` CHANGE `customerno` `customerno` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `vehicle` CHANGE `uid` `uid` INT(11) NOT NULL DEFAULT '0', CHANGE `isdeleted` `isdeleted` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `userid` `userid` INT(11) NOT NULL DEFAULT '0', CHANGE `branchid` `branchid` INT(11) NOT NULL DEFAULT '0', CHANGE `modelid` `modelid` INT(11) NOT NULL DEFAULT '0', CHANGE `is_insured` `is_insured` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `status_id` `status_id` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `temp1_min` `temp1_min` DECIMAL(5,2) NOT NULL DEFAULT '0.0', CHANGE `temp1_max` `temp1_max` DECIMAL(5,2) NOT NULL DEFAULT '0.0', CHANGE `temp1_mute` `temp1_mute` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `temp2_mute` `temp2_mute` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `temp3_mute` `temp3_mute` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `temp4_mute` `temp4_mute` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `vehicle` CHANGE `manufacturing_month` `manufacturing_month` INT(5) NOT NULL DEFAULT '0', CHANGE `manufacturing_year` `manufacturing_year` INT(5) NOT NULL DEFAULT '0', CHANGE `purchase_date` `purchase_date` DATE NULL DEFAULT NULL, CHANGE `tax_date` `tax_date` DATE NULL DEFAULT NULL, CHANGE `permit_date` `permit_date` DATE NULL DEFAULT NULL, CHANGE `fitness_date` `fitness_date` DATE NULL DEFAULT NULL;

ALTER TABLE `vehicle` CHANGE `start_meter_reading` `start_meter_reading` INT(10) NOT NULL DEFAULT '0';

ALTER TABLE `vehicle` CHANGE `fueltype` `fueltype` VARCHAR(225) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `temp2_min` `temp2_min` DECIMAL(5,2) NOT NULL DEFAULT '0', CHANGE `temp2_max` `temp2_max` DECIMAL(5,2) NOT NULL DEFAULT '0', CHANGE `temp3_min` `temp3_min` DECIMAL(5,2) NOT NULL DEFAULT '0', CHANGE `temp3_max` `temp3_max` DECIMAL(5,2) NOT NULL DEFAULT '0', CHANGE `temp4_min` `temp4_min` DECIMAL(5,2) NOT NULL DEFAULT '0', CHANGE `temp4_max` `temp4_max` DECIMAL(5,2) NOT NULL DEFAULT '0', CHANGE `no_of_genset` `no_of_genset` INT(11) NULL DEFAULT '0', CHANGE `genset1` `genset1` INT(11) NULL DEFAULT '0', CHANGE `genset2` `genset2` INT(11) NULL DEFAULT '0', CHANGE `transmitter1` `transmitter1` INT(11) NULL DEFAULT '0', CHANGE `transmitter2` `transmitter2` INT(11) NULL DEFAULT '0';
ALTER TABLE `vehicle` CHANGE `puc_filename` `puc_filename` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `registration_filename` `registration_filename` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `insurance_filename` `insurance_filename` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `speed_gov_filename` `speed_gov_filename` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `fire_extinguisher_filename` `fire_extinguisher_filename` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `other_upload5` `other_upload5` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `other_upload6` `other_upload6` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `other_upload1` `other_upload1` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `other_upload2` `other_upload2` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `other_upload3` `other_upload3` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `other_upload4` `other_upload4` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `timestamp` `timestamp` DATETIME NULL, CHANGE `stoppage_odometer` `stoppage_odometer` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL, CHANGE `stoppage_transit_time` `stoppage_transit_time` DATETIME NULL, CHANGE `nodata_alert` `nodata_alert` INT(11) NOT NULL DEFAULT '0', CHANGE `stoppage_flag` `stoppage_flag` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `submission_date` `submission_date` DATETIME NULL, CHANGE `registration_date` `registration_date` DATETIME NULL, CHANGE `approval_date` `approval_date` DATETIME NULL;
ALTER TABLE `vehicle` CHANGE `description` `description` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `tel_count` `tel_count` INT(11) NOT NULL DEFAULT '0', CHANGE `tel_lock` `tel_lock` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `dailyreport` CHANGE `last_online_updated` `last_online_updated` DATETIME NULL, CHANGE `topspeed_time` `topspeed_time` DATETIME NULL, CHANGE `daily_date` `daily_date` DATE NULL;



ALTER TABLE `unit` CHANGE `repairtat` `repairtat` DATE NULL, CHANGE `digitalioupdated` `digitalioupdated` DATETIME NULL, CHANGE `door_digitalioupdated` `door_digitalioupdated` DATETIME NULL, CHANGE `extra_digitalioupdated` `extra_digitalioupdated` DATETIME NULL, CHANGE `extra2_digitalioupdated` `extra2_digitalioupdated` DATETIME NULL;
ALTER TABLE `eventalerts` CHANGE `ac_time` `ac_time` DATETIME NULL, CHANGE `door_time` `door_time` DATETIME NULL;

UPDATE eventalerts SET ac_time = NULL WHERE ac_time = '0000-00-00 00:00:00';
UPDATE eventalerts SET door_time = NULL WHERE door_time = '0000-00-00 00:00:00';

ALTER TABLE `dailyreport` CHANGE `first_lat` `first_lat` DECIMAL(9,6) NULL DEFAULT '0', CHANGE `first_long` `first_long` DECIMAL(9,6) NULL DEFAULT '0', CHANGE `end_lat` `end_lat` DECIMAL(9,6) NULL DEFAULT '0', CHANGE `end_long` `end_long` DECIMAL(9,6) NULL DEFAULT '0', CHANGE `night_first_lat` `night_first_lat` DECIMAL(9,6) NOT NULL DEFAULT '0', CHANGE `night_first_long` `night_first_long` DECIMAL(9,6) NOT NULL DEFAULT '0';
ALTER TABLE `dailyreport` CHANGE `offline_data_time` `offline_data_time` INT(11) NOT NULL DEFAULT '0';


UPDATE dailyreport SET last_online_updated = NULL WHERE last_online_updated= '0000-00-00 00:00:00';
UPDATE dailyreport SET topspeed_time = NULL WHERE topspeed_time= '0000-00-00 00:00:00';
UPDATE dailyreport SET daily_date = NULL WHERE daily_date= '0000-00-00';


UPDATE unit SET repairtat = NULL WHERE repairtat = '0000-00-00';
UPDATE unit SET digitalioupdated = NULL WHERE digitalioupdated = '0000-00-00 00:00:00';
UPDATE unit SET door_digitalioupdated = NULL WHERE door_digitalioupdated = '0000-00-00 00:00:00';
UPDATE unit SET extra_digitalioupdated = NULL WHERE extra_digitalioupdated = '0000-00-00 00:00:00';
UPDATE unit SET extra2_digitalioupdated = NULL WHERE extra2_digitalioupdated = '0000-00-00 00:00:00';






UPDATE vehicle SET lastupdated = NULL WHERE lastupdated = '0000-00-00 00:00:00';
UPDATE vehicle SET timestamp = NULL WHERE timestamp = '0000-00-00 00:00:00';
UPDATE vehicle SET stoppage_transit_time = NULL WHERE stoppage_transit_time = '0000-00-00 00:00:00';
UPDATE vehicle SET checkpoint_timestamp = NULL WHERE checkpoint_timestamp = '0000-00-00 00:00:00';
UPDATE vehicle SET expiry_date = NULL WHERE expiry_date = '0000-00-00';
UPDATE vehicle SET purchase_date = NULL WHERE purchase_date = '0000-00-00';
UPDATE vehicle SET tax_date = NULL WHERE tax_date = '0000-00-00';
UPDATE vehicle SET permit_date = NULL WHERE permit_date = '0000-00-00';
UPDATE vehicle SET fitness_date = NULL WHERE fitness_date = '0000-00-00';
UPDATE vehicle SET submission_date = NULL WHERE submission_date = '0000-00-00 00:00:00';
UPDATE vehicle SET registration_date = NULL WHERE registration_date = '0000-00-00 00:00:00';
UPDATE vehicle SET approval_date = NULL WHERE approval_date = '0000-00-00 00:00:00';


ALTER TABLE `dailyreport` CHANGE `trip_count` `trip_count` INT(11) NOT NULL DEFAULT '0';

UPDATE temp_mute SET mute_starttime = NULL WHERE mute_starttime = '0000-00-00 00:00:00';
UPDATE temp_mute SET mute_endtime = NULL WHERE mute_endtime = '0000-00-00 00:00:00';

UPDATE toggle_switch SET starttime = NULL WHERE starttime = '0000-00-00 00:00:00';
UPDATE toggle_switch SET endtime = NULL WHERE endtime = '0000-00-00 00:00:00';


UPDATE device_inactive SET start_time = NULL WHERE start_time= '0000-00-00 00:00:00';
UPDATE device_inactive SET end_time = NULL WHERE end_time= '0000-00-00 00:00:00';
UPDATE device_inactive SET timestamp = NULL WHERE timestamp= '0000-00-00 00:00:00';


ALTER TABLE `geotest` CHANGE `checkpointid` `checkpointid` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `temp_mute` CHANGE `mute_starttime` `mute_starttime` DATETIME NULL, CHANGE `mute_endtime` `mute_endtime` DATETIME NULL;
ALTER TABLE `toggle_switch` CHANGE `starttime` `starttime` DATETIME NULL, CHANGE `endtime` `endtime` DATETIME NULL;
ALTER TABLE `device_inactive` CHANGE `start_time` `start_time` DATETIME NULL, CHANGE `end_time` `end_time` DATETIME NULL, CHANGE `timestamp` `timestamp` DATETIME NULL;
ALTER TABLE `device_inactive` CHANGE `reason` `reason` INT(4) NOT NULL DEFAULT '0';
ALTER TABLE `device_inactive` CHANGE `start_lat` `start_lat` DECIMAL(9,6) NULL, CHANGE `start_long` `start_long` DECIMAL(9,6) NULL, CHANGE `end_lat` `end_lat` DECIMAL(9,6) NULL, CHANGE `end_long` `end_long` DECIMAL(9,6) NULL;





ALTER TABLE `devices` CHANGE `inv_generatedate` `inv_generatedate` DATE NULL;
ALTER TABLE `devices` CHANGE `installdate` `installdate` DATE NULL;
ALTER TABLE `devices` CHANGE `lastupdated` `lastupdated` DATETIME NULL DEFAULT NULL, CHANGE `registeredon` `registeredon` DATETIME NULL DEFAULT NULL, CHANGE `expirydate` `expirydate` DATE NULL DEFAULT NULL, CHANGE `po_date` `po_date` DATE NULL DEFAULT NULL, CHANGE `warrantyexpiry` `warrantyexpiry` DATE NULL DEFAULT NULL, CHANGE `inv_deferdate` `inv_deferdate` DATE NULL DEFAULT NULL, CHANGE `start_date` `start_date` DATE NULL DEFAULT NULL, CHANGE `end_date` `end_date` DATE NULL DEFAULT NULL;

UPDATE devices SET inv_generatedate = NULL WHERE inv_generatedate = '0000-00-00';
UPDATE devices SET installdate = NULL WHERE installdate = '0000-00-00';
UPDATE devices SET expirydate = NULL WHERE expirydate = '0000-00-00';
UPDATE devices SET po_date = NULL WHERE po_date = '0000-00-00';
UPDATE devices SET inv_deferdate = NULL WHERE inv_deferdate = '0000-00-00';
UPDATE devices SET start_date = NULL WHERE start_date = '0000-00-00';
UPDATE devices SET end_date = NULL WHERE end_date = '0000-00-00';
UPDATE devices SET warrantyexpiry = NULL WHERE warrantyexpiry = '0000-00-00';
UPDATE devices SET lastupdated = NULL WHERE lastupdated = '0000-00-00 00:00:00';
UPDATE devices SET registeredon = NULL WHERE registeredon = '0000-00-00 00:00:00';

UPDATE devices SET msgid = 0 WHERE msgid = '';

ALTER TABLE `devices` CHANGE `msgid` `msgid` INT(11) NOT NULL DEFAULT '0';



delete from comqueue WHERE timeadded NOT LIKE '%2019-03-16%';
delete from comqueue WHERE customerno = 1;

delete from checkpointmanage where isdeleted = 1;


13.234.139.139

OldSpeedUser

= CONCATINATE("UPDATE transporterShare SET share= , preference= WHERE transporterShareId =  ";)




UPDATE transporterShare SET isDeleted = 0 WHERE slabId IN (15,17,18);
UPDATE transporterActualShare SET isDeleted = 0 WHERE slabId IN (15,17,18);

=CONCATENATE("UPDATE transporterShare SET share='",J2,"' , preference='",K2,"'  WHERE transporterShareId ='",A2,"' AND updatedOn = '2019-07-10 14:30:00'  ; ")

=CONCATENATE("UPDATE transporterActualShare SET preference='",K2,"'  WHERE transporterActualShareId ='",A2,"' AND updatedOn = '2019-07-10 14:30:00'  ; ")

 INSERT INTO transporterShare (transporterId, polId,locationId, loadingLocation, containerTypeId, slabId, share, preference, customerNo, createdOn, updatedOn)VALUES(
 INSERT INTO transporterActualShare (transporterId, polId,locationId, loadingLocation, containerTypeId, slabId, share, preference, customerNo, createdOn, updatedOn)VALUES(

=CONCATENATE(K2,"',B2,'")

=CONCATENATE(M2,B2,",",D2,",",G2,",","'",F2,"',",H2,",",I2,",",K2,",",L2,",620,'2019-07-10 14:30:00','2019-07-10 14:30:00'")

SELECT *  FROM `transporterActualShare` WHERE `polId` = 1 AND `locationId` = 1 AND `containerTypeId` = 1 AND `slabId` = 13 and isDeleted = 0;


UPDATE vehicle SET stoppage_odometer = (odometer-200), stoppage_transit_time = lastupdated WHERE vehicleid = 14701;


Oct 2018

	http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1541010600

Nov 2018

	http://www.eliiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1543602600

Dec 2018

	http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1546281000

Jan 2019

	http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1548959400

Feb 2019

	http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1551378600

March 2019

	http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1554057000

	http://www.speed.elixiatech.com/modules/download/report.php?q=annexure-xls-378-489660462-1554057000

April 2019

	http://www.speed.elixiatech.com/modules/download/report.php?q=annexure-xls-378-489660462-1556649000

May 2019

	http://www.speed.elixiatech.com/modules/download/report.php?q=annexure-xls-378-489660462-1559327400

Jun 2019

	http://www.speed.elixiatech.com/modules/download/report.php?q=annexure-xls-378-489660462-1561919400


	UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 10799;
	UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 18621;


	8928785585

	571
	72 73 74 

	http://mahindrafs.elixiatech.com/modules/cron/cron_UpdateNightWeekend.php?date=2019-01-15


	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=99606&date=2019-06-29
	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=900698&date=2019-06-29
	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9804553&date=2019-06-27
	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9804173&date=2019-06-10
	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9804097&date=2019-06-10


	http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=99606&date=2019-06-29
	http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=900698&date=2019-06-29
	http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=9804553&date=2019-06-27
	http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=9804173&date=2019-06-10
	http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=9804097&date=2019-06-10


	http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=901426&date=2019-06-23


	http://mahindrafs.elixiatech.com/modules/cron/cron_UpdateNightWeekend.php?date=2019-06-10
	http://mahindrafs.elixiatech.com/modules/cron/cron_UpdateNightWeekend.php?date=2019-06-11
	http://mahindrafs.elixiatech.com/modules/cron/cron_UpdateNightWeekend.php?date=2019-06-28
	http://mahindrafs.elixiatech.com/modules/cron/cron_UpdateNightWeekend.php?date=2019-06-29
	http://mahindrafs.elixiatech.com/modules/cron/cron_UpdateNightWeekend.php?date=2019-06-30
	http://mahindrafs.elixiatech.com/modules/cron/cron_UpdateNightWeekend.php?date=2019-07-01

	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=99606&date=2019-06-01
	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9801515&date=2019-06-01
	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9801291&date=2019-06-01
	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1722010004313&date=2019-06-01
	http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9801293&date=2019-06-01



	
	
	http://mahindrafs.elixiatech.com/customer/64/Informatics/Jun2019_Informatics_Summarized_Report.xls
	http://mahindrafs.elixiatech.com/customer/64/Informatics/Jun2019_Informatics_Detailed_Report.xls

	speed.elixiatech.com/modules/cron/cron_unitBackup.php?limit=10

	SELECT *  FROM `checkpoint` WHERE `customerno` = 328 AND `isSms` = 0 AND `isEmail` = 0 and isdeleted = 0


	UPDATE checkpoint SET isSms = 1, isEmail = 1 WHERE `customerno` = 328 AND `isSms` = 0 AND `isEmail` = 0 and isdeleted = 0 ;

	el!365x!@

	http://localhost/speed/modules/api/V22/index.php?action=dashboard&userkey=937434123

	http://localhost/speed/modules/api/V22/index.php?action=pulldetails&userkey=937434123

	UPDATE users set isdeleted = 1 WHERE customerno = 64;

24.993616,55.066288

Todays Task -
Mahindra Telephonic Alerts Test  - Done
Save Sqlite Correction - Cron To Backup Comqueue Data 
Vora Transport - Offers API
	Database Creatation 
	Stored Procedures For Offers Listing

Extra - 
Idle Since Isssue Of TL Logistics
CronMail Issue on Mahindra Server


Vora Transport - Offers API
	API to get offers list.
	Get Offers


UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 18789;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 9903;

http://localhost/speed/modules/cron/saveSqlite.php?date=2019-00005&customerno=2

http://speed.elixiatech.com/modules/cron/saveSqlite.php?date=2019-06-14&customerno=2


http://uat-speed.elixiatech.com/modules/sales/api.php?action=getProductList&userkey=3121006208&srid=9411&franchiseId=1&pageIndex=1&searchString=&shopId=51923

http://localhost/speed/modules/sales/api.php?action=getProductList&userkey=3121006208&srid=9411&franchiseId=1&pageIndex=1&searchString=&shopId=51923


Create Table promoOffers(
	offerId INT PRIMARY KEY AUTO_INCREMENT,
	productId INT  NOT NULL,
	minQty INT  NOT NULL,
	offerStartDate DATE DEFAULT NULL,
	offerEndDate DATE DEFAULT NULL,
	promoProductId INT NOT NULL,
	promoQty INT NOT NULL,
	description VARCHAR(255), 
	customerNo INT NOT NULL,
	createdBy INT NOT NULL,
	createdOn DATETIME DEFAULT NULL,
	updatedBy INT NOT NULL,
	updatedOn DATETIME DEFAULT NULL,
	isDeleted TINYINT DEFAULT 0
);

INSERT into promoOffers(productId,minQty,offerStartDate,offerEndDate,promoProductId,promoQty,description,customerNo)VALUES(233,10,'2019-07-01','2019-07-25',234,2,'Welcome Offer',698);
INSERT into promoOffers(productId,minQty,offerStartDate,offerEndDate,promoProductId,promoQty,description,customerNo)VALUES(233,10,'2019-07-01','2019-07-25',235,2,'Welcome Offer',698);
INSERT into promoOffers(productId,minQty,offerStartDate,offerEndDate,promoProductId,promoQty,description,customerNo)VALUES(233,10,'2019-07-01','2019-07-15',237,2,'Welcome Offer',698);



{"categoryid":"1","skuid":"233","quantity":"10","status":"0","entrydate":"0","franchiseId":"1","macro":"1.0","packs":"10","micro":"100","skuTotalPrice":"100000.0"}

SELECT polId,locationId,containerTypeId,slabId,SUM(sharedLdoc) as shareLdoc, totalLdoc
FROM transporterActualShare
WHERE isDeleted = 0 
GROUP BY polId,locationId,containerTypeId,slabId


http://localhost/tollservice/service/toll/tollDetails.php

jsonreq:{"action":"getTollList","originLat":"19.0760","originLng":"72.8777","destinationLat":"20.9042","destinationLng":"74.7749"}


http://localhost/tollservice/service/toll/tollDetails.php?jsonreq={"action":"getTollList","originLat":"19.0760","originLng":"72.8777","destinationLat":"20.9042","destinationLng":"74.7749"}


https://github.com/phpv8/v8js/blob/php7/README.Linux.md

Jan 2018
http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1517423400

Feb 2018
http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1519842600

Mar 2018
http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1522521000

April 2018
http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1525113000

May 2018
http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1527791400

Jun 2018
http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1530383400

July 2018
http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1533061800

Aug 2018
http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1535740200

Sept 2018
http://www.elixiaspeed.com/modules/download/report.php?q=annexure-xls-378-489660462-1538332200

5MXOQ5XD5K

9833299745 preeti



UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 18601;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 19144;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 17755;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 1815;

UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 18937;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 18939;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 18935;

UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 18856;

UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 19007;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 19012;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 19014;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 19015;

UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 19166;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 18313;
UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 19088;

delete from comqueue WHERE customerno <> 64;
delete from vehicle WHERE customerno <> 64;
delete from unit WHERE customerno <> 64;
delete from devices WHERE customerno <> 64;
delete from driver WHERE customerno <> 64;
delete from simcard WHERE customerno <> 64;
delete from ignitionalert WHERE customerno <> 64;
delete from eventalerts WHERE customerno <> 64;
delete from dailyreport WHERE customerno <> 64;
delete from stoppage_alerts WHERE customerno <> 64;

delete from login_history WHERE customerno <> 64;
delete from login_history_details WHERE customerno <> 64;
delete from mdlzRealTimeDump WHERE customerno <> 64;
delete from realtimedata WHERE customerno <> 64;
delete from smslog WHERE customerno <> 64;
delete from device_inactive WHERE customerno <> 64;
delete from emailLog WHERE customerno <> 64;
delete from vehiclewise_alert WHERE customerno <> 64;
delete from comhistory WHERE customerno <> 64;


ALTER TABLE `speed`.`alertTempUserMapping` ADD INDEX `index_uid` (`uid`);
ALTER TABLE `speed`.`alertTempUserMapping` ADD INDEX `index_userid` (`userid`);
ALTER TABLE `speed`.`alertTempUserMapping` ADD INDEX `index_vehicleid` (`vehicleid`);


mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p speed --routines --triggers --events --ignore-table=speed.comqueue --ignore-table=speed.comhistory --ignore-table=speed.geotest --ignore-table=speed.smslog --ignore-table=speed.emaillog --ignore-table=speed.comqueue_bck --ignore-table=speed.comhistory_bck --ignore-table=speed.realtimedata | "C:\Program Files (x86)\GnuWin32\bin\gzip" > E:\Mrudang\DB-Backup\speed\28Sep2018.sql.gz

mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p speed --routines --triggers --events --ignore-table=speed.comqueue --ignore-table=speed.comhistory --ignore-table=speed.geotest --ignore-table=speed.smslog --ignore-table=speed.emaillog --ignore-table=speed.comqueue_bck --ignore-table=speed.comhistory_bck --ignore-table=speed.realtimedata > 25July2019.sql.gz


mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comqueue_bck_2  > comqueue_bck_2.sql
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comqueue_bck_1  > comqueue_bck_1.sql
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comqueue_bck  > comqueue_bck.sql
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comhistory_bck_2  > comhistory_bck_2.sql
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comhistory_bck_1  > comhistory_bck_1.sql
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comhistory_bck  > conhistory_bck.sql


mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comqueue_bck_2  > comqueue_bck_2.sql.gz
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comqueue_bck_1  > comqueue_bck_1.sql.gz
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comqueue_bck  > comqueue_bck.sql.gz
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comhistory_bck_2  > comhistory_bck_2.sql.gz
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comhistory_bck_1  > comhistory_bck_1.sql.gz
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed comhistory_bck  > comhistory_bck.sql.gz
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed login_history_details_bck_21Jul2019   > login_history_details_bck_21Jul2019.sql.gz
mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed login_history_bck   > login_history_bck.sql.gz


The storage engine for the table doesn't support r.


SELECT lv.`ledgerid` ,lv.`customerno` ,`customer`.`unit_msp` ,`customer`.`lease_price` ,count(lv.`customerno`) AS count1 ,SUM(`customer`.`unit_msp`) AS total ,SUM(`customer`.`lease_price`) AS total1 ,d.`end_date` ,l.`state_code` ,sgc.`state` ,customer.`renewal` 
FROM `ledger_veh_mapping` lv INNER JOIN `ledger` l ON l.`ledgerid` = lv.`ledgerid` 
INNER JOIN `state_gst_code` sgc ON sgc.codeid = l.`state_code` 
INNER JOIN `customer` ON `customer`.`customerno` = lv.`customerno` 
INNER JOIN `vehicle` ON `vehicle`.vehicleid = lv.vehicleid AND `vehicle`.invoice_hold = 0 
INNER JOIN `unit` u ON u.vehicleid = vehicle.vehicleid AND u.trans_statusid IN (5,6) 
INNER JOIN `devices` d ON d.uid = u.uid INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14) where lv.`ledgerid` = 54 
AND DATE_FORMAT(d.`end_date`,'%Y-%m') = '2019-07' AND lv.`isdeleted` = 0 AND vehicle.kind != 'Warehouse' group by lv.`customerno` 
UNION SELECT lv.`ledgerid` ,lv.`customerno` ,`customer`.`warehouse_msp` as unit_msp ,`customer`.`lease_price` ,count(lv.`customerno`) AS count1 ,SUM(`customer`.`warehouse_msp`) AS total ,SUM(`customer`.`lease_price`) AS total1 ,d.`end_date` ,l.`state_code` ,sgc.`state` ,customer.`renewal` 
FROM `ledger_veh_mapping` lv INNER JOIN `ledger` l ON l.`ledgerid` = lv.`ledgerid` 
INNER JOIN `state_gst_code` sgc ON sgc.codeid = l.`state_code` 
INNER JOIN `customer` ON `customer`.`customerno` = lv.`customerno` 
INNER JOIN `vehicle` ON `vehicle`.vehicleid = lv.vehicleid AND `vehicle`.invoice_hold = 0 
INNER JOIN `unit` u ON u.vehicleid = vehicle.vehicleid AND u.trans_statusid IN (5,6) 
INNER JOIN `devices` d ON d.uid = u.uid INNER JOIN `simcard` s ON s.id = d.simcardid AND s.trans_statusid IN (13,14) where lv.`ledgerid` = 54 
AND DATE_FORMAT(d.`end_date`,'%Y-%m') = '2019-07' AND lv.`isdeleted` = 0 AND vehicle.kind = 'Warehouse' group by lv.`customerno`


http://localhost/speed/modules/sales/api.php?action=pushretailer_order&userkey=3121006208&is_deadstock=0&reason=test&discount=10&skudata=[{"categoryid":"1","skuid":"233","quantity":"10","status":"0","entrydate":"0","franchiseId":"1","macro":"1.0","packs":"10","micro":"100","skuTotalPrice":"100000.0", "offers":{"offerId": "1","productId": "233","minQty": "10","promoProductId": "234","promoQty": "2","discount": "50","description": "Welcome Offer - Purchase min 10 Qty"}}]&shopid=51923&orderdate=2019-07-20%2011:09:38&distid=null&srid=9411&manual_cn=&deliverydatetime=2019-07-31%2011:09:38&driverid=&erpusertoken=3532535963&otId=&orderAmt=102000.0
http://uat-speed.elixiatech.com/modules/sales/api.php?action=pushretailer_order&userkey=3121006208&is_deadstock=0&reason=test&discount=10&skudata=[{"categoryid":"1","skuid":"233","quantity":"10","status":"0","entrydate":"0","franchiseId":"1","macro":"1.0","packs":"10","micro":"100","skuTotalPrice":"100000.0", "offers":{"offerId": "1","productId": "233","minQty": "10","promoProductId": "234","promoQty": "2","discount": "50","description": "Welcome Offer - Purchase min 10 Qty"}}]&shopid=51923&orderdate=2019-07-20%2011:09:38&distid=null&srid=9411&manual_cn=&deliverydatetime=2019-07-31%2011:09:38&driverid=&erpusertoken=3532535963&otId=&orderAmt=102000.0

http://localhost/speed/modules/sales/api.php?action=pushretailer_order&userkey=3121006208&is_deadstock=0&reason=test&discount=10&skudata=[{"categoryid":"1","skuid":"233","quantity":"10","status":"0","entrydate":"0","franchiseId":"1","macro":"1.0","packs":"10","micro":"100","skuTotalPrice":"100000.0", "offers":"{"offerId": "1","productId": "233","minQty": "10","promoProductId": "234","promoQty": "2","discount": "50","description": "Welcome Offer - Purchase min 10 Qty"}"}]&shopid=51923&orderdate=2019-07-20%2011:09:38&distid=null&srid=9411&manual_cn=&deliverydatetime=2019-07-31%2011:09:38&driverid=&erpusertoken=3532535963&otId=&orderAmt=102000.0

ALTER TABLE `promoOffers` ADD `discount` DECIMAL(5,2) NOT NULL AFTER `promoQty`; 

Task - 01-08-2019

Planned Task 
Promo Offer Accepetd In Sales Order
Calculate Macro and Micro Packaging in sales order - Priviosly Calculated in mobile app.

Adhoc 
MCGM Toggle Queries
UAT Movements

Planned Task -

SO Import Puma
SO Crud Puma



AND DATE_FORMAT(d.`end_date`,'%Y-%m-%d')


SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone 
FROM speed.`comqueue` LEFT OUTER JOIN speed.comhistory on comqueue.cqid = comhistory.comqid 
INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid 
LEFT OUTER JOIN user ON user.userid = comhistory.userid 
WHERE comqueue.customerno = 421 AND comqueue.vehicleid= 8134 AND comqueue.type= 8 
AND DATE_FORMAT(comqueue.timeadded,'%Y-%m-%d') between DATE(2019-08-02) AND DATE(2019-07-16) 
AND vehicle.groupid ='2341' ORDER BY comqueue.timeadded ASC 




SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone, DATE_FORMAT(comqueue.timeadded,'%Y-%m-%d') as rt 
FROM speed.`comqueue` 
LEFT OUTER JOIN speed.comhistory on comqueue.cqid = comhistory.comqid 
INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid 
LEFT OUTER JOIN user ON user.userid = comhistory.userid 
WHERE comqueue.customerno = 421 AND comqueue.vehicleid= 8134 AND comqueue.type= 8 

AND vehicle.groupid ='2341' ORDER BY comqueue.timeadded ASC


SELECT comqueue.type,comqueue.timeadded,comqueue.message,comqueue.processed,comhistory.comtype,user.email,user.phone 
FROM speed.`comqueue` 
LEFT OUTER JOIN speed.comhistory on comqueue.cqid = comhistory.comqid 
INNER JOIN vehicle on comqueue.vehicleid = vehicle.vehicleid 
LEFT OUTER JOIN user ON user.userid = comhistory.userid 
WHERE comqueue.customerno = 421 AND comqueue.vehicleid= 6400 AND comqueue.type= 8 
AND DATE_FORMAT(comqueue.timeadded,'%Y-%m-%d') between DATE(2019-08-02) AND DATE(2019-07-16) 
AND vehicle.groupid ='2341' ORDER BY comqueue.timeadded ASC 



SELECT u.vehicleid FROM unit u WHERE unitno IN(
'901366',
'9800669',
'9804785',
'9804138',
'9803992',
'907588',
'907496',
'9804144'
) 




7205,
7206,
7251,
7382,
8505,
8591,
8595,
18890,
8025,
902,
7975,
7251,
12513,
9077,
8591

select * from driver WHERE vehicleid IN(
7205,
7206,
7251,
7382,
8505,
8591,
8595,
18890,
8025,
902,
7975,
7251,
12513,
9077,
8591
) and isdeleted = 0;


select * from eventalerts WHERE vehicleid IN (
7205,
7206,
7251,
7382,
8505,
8591,
8595,
18890,
8025,
902,
7975,
7251,
12513,
9077,
8591
);

select * from ignitionalert WHERE vehicleid IN (
7205,
7206,
7251,
7382,
8505,
8591,
8595,
18890,
8025,
902,
7975,
7251,
12513,
9077,
8591
);

select * from vehiclewise_alert WHERE vehicleid INT (
7205,
7206,
7251,
7382,
8505,
8591,
8595,
18890,
8025,
902,
7975,
7251,
12513,
9077,
8591
);

select simcard.* from simcard
inner join devices on devices.simcardid = simcard.id
inner join unit on devices.uid = unit.uid where unit.vehicleid in(
7205,
7206,
7251,
7382,
8505,
8591,
8595,
18890,
8025,
902,
7975,
7251,
12513,
9077,
8591
    
)


Unit No 9804138 is Mapped To UP 73 N 8765 instaed off UP 73 N 8763


select * from driver WHERE vehicleid IN(
8590,7382,8505,7251,12513
) and isdeleted = 0;


select * from eventalerts WHERE vehicleid IN (
8591,7382,8505,7251,12513
);

select * from ignitionalert WHERE vehicleid IN (
8591,7382,8505,7251,12513
);

select * from vehiclewise_alert WHERE vehicleid INT (
8591,7382,8505,7251,12513
);

select simcard.* from simcard
inner join devices on devices.simcardid = simcard.id
inner join unit on devices.uid = unit.uid where unit.vehicleid in(
8591,7382,8505,7251,12513
)



MP 04 CQ 9071
GJ 14 AK 7570
UP 73 N 8763
UP 32 FY 8112
MP04CQ7844
MP65C2477
MP19CB9784
AP 10 AS 1030
GA 03 H 7072
KA 51 MG 5188
MP 04 CQ 7844
TS 04 EG 1819
UP 32 CW 2346
UP 73 N 8765

Note - Unit No 9804138 is Mapped To UP 73 N 8765 instaed off UP 73 N 8763


DELETE FROM unit WHERE customerno = 132 AND unitno IN (
905673,
905672,
901223,
905679,
907449,
906892
)


DELETE FROM unit WHERE customerno = 664 AND unitno IN (
904295,
1813010018211,
9804185,
1813010018104,
1741010015061,
9804042
)



Select u.uid,v.vehicleid,d.driverid 
FROM unit u 
INNER JOIN vehicle v on v.uid = u.uid
INNER driver d on d.driverid = v.driverid
WHERE u.customerno = 742
AND v.customerno = 742
AND v.isdeleted = 0
AND u.trans_statusid NOT IN(10,22)


INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(

=CONCATENATE(M2,B2,",",D2,",",G2,",","'",F2,"',",H2,",",I2,",",K2,",",L2,",620,'2019-07-10 14:30:00','2019-07-10 14:30:00'")

=CONCATENATE(E2,"742",B2)

INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,16352,14216,28907,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17093,14943,29705,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17094,14944,29706,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17095,14945,29707,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17096,14946,29708,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17097,14947,29709,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17098,14948,29710,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17099,14949,29711,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17100,14950,29712,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17101,14951,29713,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17102,14952,29714,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17103,14953,29715,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17104,14954,29716,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17105,14955,29717,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17106,14956,29718,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17107,14957,29719,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17108,14958,29720,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17109,14959,29721,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17110,14960,29722,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17111,14961,29723,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17112,14962,29724,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17113,14971,29725,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17114,14964,29726,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17115,14965,29727,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17116,14966,29728,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17117,14967,29729,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17118,14968,29730,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17119,14969,29731,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17120,14970,29732,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17121,14963,29733,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17122,14972,29734,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17123,14973,29735,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17124,14974,29736,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17125,14975,29737,'2019-08-05');
INSERT INTO dailyreport(customerno,vehicleid,uid,driverid,daily_date)VALUES(742,17126,14976,29738,'2019-08-05');


mysqldump -P 3306 -h 13.234.139.139 -u ElixiaOfficeUser -p  speed customer user vehicle unit driver devices simcard ignitionalert eventalerts dailyreport  > RTD.sql.gz



b. Transworld: Not receiving alerts because of timezone.

	- Alerts not generating for the devices which ported to PHP Listener.
	- Changing the alerts and cron mail code according to timezone condition - In Progress

c. Transworld: Complete vehicle list not available in Distance Analysis Report.
	
	Reason - only 10 out of 45 vehicles present in dailyreport table.
	- Temporaraly we have added the remaining 35 vehicles to dailyreport.

	Fix - We need to fix this in PHP Listener otherwise.

d. MP Group: Checkpoint and Route History Report Mismatch - Investigation.
	
	As discussed with Altaf all checkpoints working properly except "Tata Motors Out Gate" 
	We have increased the checkpoint radius to observe the checkpoint behaviour 


e. RJ Corp: Issue with Distance - In Progress



Hello Ashutosh,

Acvtive ldocs has assigned below vehicles in controltower

GJ14X6136
GJ14X6327
GJ14X4944
MH46AR3498
MH04JU3487
MH43Y3396


None of the assigned vehicle is present in speed portal, so we are uanble to show the Play button for tracking.


ALTER TABLE `vehicle` ADD `other_upload5` VARCHAR(250) NOT NULL AFTER `other_upload4`;
ALTER TABLE `vehicle` ADD `other_upload6` VARCHAR(250) NOT NULL AFTER `other_upload5`;

2019-08-06 13:53:26

DATE_ADD('" . $time . "', INTERVAL tz.timediff SECOND) as ctTimeZoneTimestamp

cq.`timeadded` >= DATE_ADD(DATE_ADD('" . $time . "', INTERVAL tz.timediff SECOND), INTERVAL -180 SECOND)


http://mahindrafs.elixiatech.com/customer/64/Informatics/July2019_Informatics_Summarized Report.xls
http://mahindrafs.elixiatech.com/customer/64/Informatics/July2019_Informatics_Detailed_Report.xls

customer user vehicle unit driver devices simcard ignitionalert eventalerts dailyreport

DROP TABLE customer;
DROP TABLE user;
DROP TABLE vehicle;
DROP TABLE unit;
DROP TABLE driver;
DROP TABLE devices;
DROP TABLE simcard;
DROP TABLE ignitionalert;
DROP TABLE eventalerts;

http://speed.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=895&unit=1721010003978&date=2019-08-07

http://localhost/speed/modules/cron/cronDuplicateTimeDeletion.php?customerno=895&unit=1721010003978&date=2019-08-07

http://speed.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=895&unit=1736010012258&date=2019-08-02



/* Email Notification */

/* SMS Notification */

/* Telephonic Notification */

/* Mobile App Notification */



b. Transworld: Not receiving alerts because of timezone. - Done
d. MP Group: Checkpoint and Route History Report Mismatch - Investigation. - Done
   - Changes done Please verify the checkpoint entries get captured or not.
e. RJ Corp: Issue with Distance - Done

Tasks for August 6:
a. JFK: Alert for Continuous Driving (180 mins) - Will make it configurable later - In Progress - Will Go Live by Tuesday First Half 
b. JFK: Alert for Continous Driving (150 km) - Will make it configurable later - In Progress - Will Go Live by Tuesday First Half


../../customer/63/unitno//sqlite/2019-08-09.sqlite

UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid IN (18382,18718);

We have checked the vehicle "" but we were unable to find any specific issue.
So please send the list of vehicles for which you are generating the stoppage report on daily basis.




https://www.youtube.com/watch?v=RLtyhwFtXQA

https://www.youtube.com/watch?v=C7TFgfY7JdE

https://www.youtube.com/watch?v=G8uL0lFFoN0

https://www.youtube.com/watch?v=q-JVfek1fc0

https://www.youtube.com/watch?v=i57Fr5CgN4k

https://www.youtube.com/watch?v=CaKoJ9rFo8c

https://www.youtube.com/watch?v=4yqu8YF29cU

https://www.youtube.com/watch?v=E-1xI85Zog8

https://www.youtube.com/watch?v=waN5-o1quks&vl=en

https://www.youtube.com/watch?v=ImtZ5yENzgE

https://www.youtube.com/watch?v=jnvu1GpylP0

https://www.youtube.com/watch?v=ZRAHW80PQSo

https://www.youtube.com/watch?v=ZRAHW80PQSo&list=PL8p2I9GklV45FoEJM8xdXyR8fbTVVQmP4

https://www.youtube.com/playlist?list=PL8p2I9GklV45FoEJM8xdXyR8fbTVVQmP4




SQS 

https://docs.aws.amazon.com/aws-sdk-php/v2/guide/service-sqs.html
https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/sqs-examples.html
https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/sqs-examples-send-receive-messages.html
https://betacloud.ai/blog/amazon-sqs-php-tutorial-use-simple-queue-service
https://medium.com/@tedicela/how-to-make-a-php-worker-for-aws-sqs-the-simple-way-af043842942e
https://docs.aws.amazon.com/sdk-for-javascript/v2/developer-guide/sqs-examples.html
https://medium.com/@drwtech/a-node-js-introduction-to-amazon-simple-queue-service-sqs-9c0edf866eca


SELECT g.sequence as sequenceno , vehicle.ignition_wirecut , vehicle.vehicleid , vehicle.curspeed , vehicle.customerno , vehicle.overspeed_limit , vehicle.stoppage_transit_time , driver.drivername , vehicle.temp1_min , vehicle.temp1_max , vehicle.temp2_min , vehicle.temp2_max , vehicle.temp3_min , vehicle.temp3_max , vehicle.temp4_min , vehicle.temp4_max , devices.devicelat , devices.devicelong , vehicle.groupid , g.groupname , vehicle.extbatt , devices.ignition , devices.status , unit.is_buzzer , unit.is_freeze , vehicle.stoppage_flag , devices.directionchange , customer.use_geolocation , unit.acsensor , vehicle.vehicleno , unit.unitno , devices.tamper , devices.powercut , devices.inbatt , unit.analog1 , unit.analog2 , unit.analog3 , unit.analog4 , unit.get_conversion , unit.digitalioupdated , unit.digitalio , unit.is_door_opp , devices.gsmstrength , devices.registeredon , driver.driverid , driver.driverphone , vehicle.kind , vehicle.average , vehicle.fuelcapacity , vehicle.fuel_balance , unit.extra_digital , customer.use_extradigital , unit.extra_digitalioupdated , unit.tempsen1 , unit.tempsen2 , unit.tempsen3 , unit.tempsen4 , unit.humidity , unit.is_mobiliser , unit.mobiliser_flag , unit.command , devices.deviceid , devices.lastupdated , unit.is_ac_opp , unit.msgkey , ignitionalert.status as igstatus , ignitionalert.ignchgtime , g1.gensetno as genset1 , g2.gensetno as genset2 , t1.transmitterno as transmitter1 , t2.transmitterno as transmitter2 , unit.setcom , vehicle.temp1_mute , vehicle.temp2_mute , vehicle.temp3_mute , vehicle.temp4_mute , unit.extra2_digitalioupdated , unit.door_digitalioupdated , checkpoint.cname , vehicle.chkpoint_status , devices.gpsfixed , vehicle.checkpoint_timestamp , vehicle.routeDirection , vehicle.checkpointId , unit.door_digitalio , unit.isDoorExt , first_odometer , last_odometer , max_odometer , vehicle_common_status_master.status , vehicle_common_status_master.color_code ,v_t.vehicleType FROM vehicle INNER JOIN devices ON devices.uid = vehicle.uid INNER JOIN driver ON driver.driverid = vehicle.driverid INNER JOIN unit ON vehicle.uid = unit.uid INNER JOIN speed.customer ON customer.customerno = vehicle.customerno AND vehicle.customerno = 2 INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid LEFT JOIN vehicle_common_status_master ON vehicle_common_status_master.id = vehicle.vehicle_status LEFT JOIN `group` g on g.groupid = vehicle.groupid and g.isdeleted = 0 and g.customerno = 2 LEFT JOIN dailyreport ON dailyreport.vehicleid = vehicle.vehicleid and dailyreport.customerno=2 and daily_date='2019-10-24' LEFT JOIN checkpoint ON checkpoint.checkpointid = vehicle.checkpointId and checkpoint.customerno = 2 LEFT JOIN genset g1 on vehicle.genset1 = g1.gensetid and g1.customerno = 2 LEFT JOIN genset g2 on vehicle.genset2 = g2.gensetid and g2.customerno = 2 LEFT JOIN transmitter t1 on vehicle.transmitter1 = t1.transmitterid and t1.customerno = 2 LEFT JOIN transmitter t2 on vehicle.transmitter2 = t2.transmitterid and t2.customerno = 2 LEFT JOIN vehicle_type AS v_t ON v_t.vehicleTypeId = vehicle.vehicleType WHERE (vehicle.customerno =2) AND unit.trans_statusid NOT IN (10,22) AND vehicle.isdeleted=0 and vehicle.kind <> 'Warehouse' ORDER BY CASE WHEN (g.sequence is null) THEN 0 ELSE g.sequence END DESC,vehicle.sequenceno,vehicle.customerno,devices.lastupdated DESC

93222 39782



http://mahindrafs.elixiatech.com/modules/cron/crondailyreport_by_limit.php?customerno=64&date=2019-10-10
el!365X!@
eli365x!@


UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid IN (18789,18767,18585,18591);


uat-erp.elixiatech.com
uat-clientdb.elixiatech.com
uat-erp.elixiatech.com
Mobile App Apk Coming Soon
uat-wms.elixiatech.com
uat-cts.elixiatech.com
NA
speed.elixiatech.com
https://play.google.com/store/apps/details?id=com.elixia.elixiaspeed
https://appstoreconnect.apple.com/WebObjects/iTunesConnect.woa/ra/ng/app/889049615
uat-books.elixiatech.com
uat-inventory.elixiatech.com



http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1719010002024&date=2019-10-04

http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1719010002024&date=2019-10-04





http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9802090&date=2019-10-25
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9802090&date=2019-10-26
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9802090&date=2019-10-27
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=9802090&date=2019-10-28


http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1722010004313&date=2019-10-25
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1722010004313&date=2019-10-26
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1722010004313&date=2019-10-27
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1722010004313&date=2019-10-28

http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1726010006111&date=2019-10-25
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1726010006111&date=2019-10-26
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1726010006111&date=2019-10-27
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1726010006111&date=2019-10-28


http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=9802090&date=2019-10-25
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=9802090&date=2019-10-26
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=9802090&date=2019-10-27
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=9802090&date=2019-10-28


http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1722010004313&date=2019-10-25
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1722010004313&date=2019-10-26
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1722010004313&date=2019-10-27
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1722010004313&date=2019-10-28

http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1726010006111&date=2019-10-25
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1726010006111&date=2019-10-26
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1726010006111&date=2019-10-27
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1726010006111&date=2019-10-28




http://mahindrafs.elixiatech.com/modules/cron/cron_UpdateNightWeekend.php?date=2019-10-27





http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1725010004910&date=2019-10-25
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1725010004910&date=2019-10-26
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1725010004910&date=2019-10-27
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1725010004910&date=2019-10-28


http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1725010004910&date=2019-10-25
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1725010004910&date=2019-10-26
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1725010004910&date=2019-10-27
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1725010004910&date=2019-10-28


UPDATE userReportMapping SET reportTime = 16, isActivated = 1 WHERE customerno = 756 and reportId = 3;

http://mahindrafs.elixiatech.com/customer/64/Informatics/Oct2019_Informatics_Detailed_Report.xls
http://mahindrafs.elixiatech.com/customer/64/Informatics/Oct2019_Informatics_Summarized_Report.xls


http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1725010004910&date=2019-10-25

http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1725010004910&date=2019-10-25



/* For Bally */

Vehicle Type

Truck
Car 
Bus


Alerts Type

Temperature
Humidity
Ignition
Over Speeding
Digital Sensor
Door Sensor
Checkpoint Exception
Power Cut 
Tamper
Checkpoint Alert
Fence Conflict
Stoppage Alerts 




Report
dashboard
 devices



 1 - AC Sensor
 2 - Checkpoint 
 3 - Fence
 4 - Ignition 
 5 - Overspeed
 6 - Powercut
 7 - Tamper 
 8 - Temperature Sensor
 15 - Panic

 http://speed.elixiatech.com/modules/api/V23/index.php?action=pullalerthistory&jsonreq={"userkey":"235903379", "vehicleId":"", "reportDate":"12-11-2019", "reportType":"-1", "groupid":"0"}

 UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE customerno = 928;

Drop Table vehicle;
Drop Table unit; 
Drop Table devices;
Drop Table simcard;
Drop Table dailyreport;
Drop Table driver;
Drop Table eventalerts;
Drop Table vehiclewise_alert;



Geocoding:

Update all customers use_geolocation = 0  - Done
Check whether location.php is used directly instead of geocoder.php - Done
Improve geotest query efficiency
Reduce geotest hits:
Make location string as blank in VTS api - Done
Don’t send location request for inactive vehicles - Done
Remove location from Route history except from first and last point - Done
Remove location from Integration Platform (gpsproviders) - Done
 

Distance Matrix:

On demand call of API instead of every 60 sec in RKFoodlands ERP
RTD details page – calculate distance between phone and vehicle              - Nothing could be done here
Control Tower (UPL - Get ETA for vehicles)                                                            - Need to check
Speed Delex report  (ETA between origin and destination hubs)                  - Nothing could be done here
Radar Trips                                                                                                                          - Need to check
 

Direction API:

Don’t call the API if lat long are same every 10 sec. @Rupali: Please check whether this is done.






 UPDATE customer SET use_geolocation = 0 WHERE use_geolocation =  1;



 SELECT vehicle.fuel_min_volt, vehicle.fuel_max_volt, vehicle.fuelcapacity, vehicle.max_voltage, vehicle.fuelMaxVoltCapacity, unit.fuelsensor, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.temp3_min, vehicle.temp3_max, vehicle.temp4_min, vehicle.temp4_max, unit.unitno, unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4, unit.get_conversion, vehicle.overspeed_limit, devices.deviceid, vehicle.vehicleno 
 FROM unit 
 INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid 
 INNER JOIN devices on devices.uid = unit.uid 
 WHERE unit.customerno = 711 AND unit.vehicleid = 13621 AND vehicle.isdeleted = 0 



 Vora Offers 
 Puma Bugs 
 Speed GeoCoder Issue
 Speed API Issues
 All Cargo Freeze ALerts
 Mahindra Pending Telephonic Alerts

 

 Team Changes 
 1) Unit Search 


Double Genset On RTD - Prtik
Freeze and Unfreeze


/* No Need To Pull Location For Integration Platform, Will use the lat, longs for the same */
//$json_p[$x]['location'] = $this->get_location_bylatlong($row['devicelat'], $row['devicelong'], $row['customer_no'], $row['use_geolocation']);
$json_p[$x]['location'] = '';


Task For - 16-Nov-2019

Amit 
1)Vehicle mapping with user.
2)Vehicle>> Master>>”Approved By” column is not getting updated. 
3)Print out option for approved vehicle is not working.
4)Transaction notes are getting saved multiple times.
5)In Approval>> Vehicle for single vehicle approval is not redirecting to original page.
6)Transaction upload file is not getting saved.
7)Hide buttons for branch managers in vehicle master -> edit vehicle 

Arvind
1) Active /Inactive Vehicle List Report Changes 3 Hours
2) Compliance Dashboard Bug Task 3 Hours
3) Compliance for in transit trip and add TAT planned time as per


Shrikant
1) Vora Enterprises 
2) Speed Map Changes
3) UPL Investigation



Task For - 16-Nov-2019
Arvind
1) Active /Inactive Vehicle List Report Changes 3 Hours Done
2) Compliance Dashboard Bug Task 3 Hours Done
3) Compliance for in transit trip and add TAT planned time as per route master  2 Hours Pending
     Reason for pending - code is completed but vts api is giving warning so compliance not updated at controltower side


Shrikant
1) Vora Enterprises - Done (Movement and Document Pending)
2) Speed Map Changes - Done (Movement Pending)
3) UPL Investigation - Pending as Shikha is doing ERP tasks
4) UPL Timesheet Issue - Adhoc (Query raised by Om for Vora Transport)



Task Details - 

Amit - 18-Nov-2019
1) Vehicle mapping with user. - Done
2) Vehicle>> Master>>”Approved By” column is not getting updated. - Done
3) Print out option for approved vehicle is not working. - WIP
4) Transaction notes are getting saved multiple times. - WIP
5) In Approval>> Vehicle for single vehicle approval is not redirecting to original page. - Done
6) Transaction upload file is not getting saved. - WIP
7) Hide buttons for branch managers in vehicle master -> edit vehicle - Done


Task Details - 18-Nov-2019


Amit -
KT of Team tasks from Sanket at HO.
8. For branch users
1.    Excel download option enables all vehicle data if “ALL BRANCH” is selected.- Done
For Regional users,
1.    Regional users can edit vehicles which are having “Send for approval status” although edit right is disable.
2.    Excel download option enables all vehicle data if “ALL BRANCH” is selected. – Done


Arvind - 
1) Allanason demo support Done
2) trace slowness issue resolved Partially Completed


Shrikant
1) Vora Enterprises Web APP and Movement Of API Development - Development Complete And QA Movement Also Done.(Unit testing - Pending)


Shrikant 
1) Vora Unit Test
2) UPL  Issues


/*

SSCT 
 */

Start - Mongo DB - sudo service mongod start

test.find({date : "2019-10-06"}).pretty();

db.test.find( { customerno: { $gt: 550 } } )


1) Purchase Order,  Vendor Invoice, GRN with PDF for increase in Inventory.
2) Invoice in Books to be transposed in ERP for operations / delivery person to access ERP and send Invoice with delivery. LR status update from ERP to Books.
3) 3-way Masters sync between Books, ERP and Inventory.
4) 3 level offers to apply - SKU-level, Group-level (Bucket offer) and order-level.


1) Travel History Report Correction
2) Restructre The Reports






speed.elixiatech.com/modules/api/vts/api.php
action=getTravelHistory
jsonreq={"userkey":"833dd2fd9ca0e3c00a2bcc94147fe6a739d42063","vehicleNo":"KA52A5079","SDate":"2019-10-07","EDate":"2019-10-07","STime":"00:00:00","ETime":"23:59:59"}



SELECT devicehistory.deviceid, devicehistory.devicelat,
devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated >= '2019-10-10 00:00:00' AND devicehistory.lastupdated <= '2019-10-10 23:59:59'
ORDER BY devicehistory.lastupdated DESC Limit 0,1



SELECT vehiclehistory.vehicleno,devicehistory.deviceid, devicehistory.devicelat,
devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated >= '2019-10-10 00:00:00' AND devicehistory.lastupdated <= '2019-10-10 23:59:59' group by devicehistory.lastupdated
ORDER BY devicehistory.lastupdated ASC



CREATE TABLE `ct`.`customer` ( `customerId` INT NOT NULL AUTO_INCREMENT , `customerNo` INT NOT NULL , `customerCompany` VARCHAR(150) NOT NULL , `customerName` VARCHAR(50) NOT NULL , `createdOn` DATETIME NOT NULL , `isActive` TINYINT NOT NULL , `timezone` INT NOT NULL , PRIMARY KEY (`customerId`)) ENGINE = InnoDB;
CREATE TABLE `ct`.`customerProductMapping` ( `mapId` INT NOT NULL , `customerId` INT NOT NULL , `productId` INT NOT NULL , `createdOn` DATETIME NOT NULL , `createdBy` INT NOT NULL , `updatedOn` DATETIME NOT NULL , `updatedBy` INT NOT NULL , `isActive` TINYINT NOT NULL , `isDeleted` TINYINT NOT NULL ) ENGINE = InnoDB;
CREATE TABLE `user` ( `userId` INT NOT NULL AUTO_INCREMENT , `realName` VARCHAR(50) NOT NULL , `userName` VARCHAR(50) NOT NULL , `roleId` INT NOT NULL , `emailId` INT NOT NULL , `mobile` INT NOT NULL , `token` INT NOT NULL , `customerId` INT NOT NULL , `createdBy` INT NOT NULL , `createdOn` INT NOT NULL , `updatedBy` INT NOT NULL , `updatedOn` INT NOT NULL , `isActive` INT NOT NULL , `isDeleted` INT NOT NULL , PRIMARY KEY (`userId`)) ENGINE = InnoDB;


create table customer(
	customerId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	customerNo INT NOT NULL,
	customerCompany VARCHAR(150),
)

Invoicing


SCCT
RK Foodland
VTS API
ComQue
Login Hist
Com His
UPL
CEAT
VORA



25-Nov-2019 

1) Ceat Live Movement
	1) Take list of files along with db patches from arvind which is required for ceat
	2) Backup data and files from control tower live server
	3) Apply db patches which is applied on uat but not applied on live  server and required for ceat functionality
	4) Take backup of files from uat server to move on live server
	5) Move files from uat backup to live server
	6) Cross verify the functionality is working fine 
	7) Live Debugging if anything goes wrong

2) Speed live movement for Allanason.
3) Integration level support for vora offers if required
4) SSCT Meeting.

26-Nov-2019

1) UPL controltower share calculation mismatch issue
	1) Scenario 1 - Check calculation of share on cancellation of allotted ldoc
	2) Scenario 2 - Check calculation of share on ldoc allocation to another transporter in merge ldoc
2) SSCT - Report Correction

27-Nov-2019 

1) SSCT - Reports
	1) Completion of travel history report (Ref Speed Report)
	2) Location Report (Ref Speed Report)


28-Nov-2019

1) SSCT - Reports
	1) Temperature compliance report (Ref Speed Report)
2) RK Foodland route import with checkpoint and eta

29-Nov-2019

1) SSCT Report
	1) Route History Report (Ref Speed Report)
2) RK Foodland route import with checkpoint and eta

30-Nov-2019

1) Login history backup
2) Com history backup
3) SSCT - Report
	1) Stoppage Report (Ref Speed Report)





{
	"userkey": "b7d1aad3d7502ad80713194cf2544d5294aa4c2d",
	"clientId": "49",
	"shopId": "11637",
	"orderDate": "05-10-2019",
	"deliveryDateTime": "1-10-2019",
	"orderAmt": "25295",
	"skuData": [{
			"categoryid": "51",
			"skuid": "183",
			"quantity": "2",
			"entrydate": "10-1-2019",
			"franchiseId": "1",
			"macro": "1",
			"packs": "2",
			"micro": "20",
			"skuTotalPrice": "20000"
		},
		{
			"categoryid": "55",
			"skuid": "186",
			"quantity": "3",
			"entrydate": "10-1-2019",
			"franchiseId": "2",
			"macro": "1.5",
			"packs": "3",
			"micro": "15",
			"skuTotalPrice": "5295",
			"offers": {
				"offerId": "1",
				"productId": "183",
				"minQty": "10",
				"promoProductId": "184",
				"promoQty": "2",
				"discount": "50",
				"description": "Welcome Offer - Purchase min 10 Qty"
			}
		},
		{
			"categoryid": "55",
			"skuid": "187",
			"quantity": "3",
			"entrydate": "10-1-2019",
			"franchiseId": "2",
			"macro": "1.5",
			"packs": "3",
			"micro": "15",
			"skuTotalPrice": "5295"
		}
	],
   "bucketOffers": {
				object of selected bucket offer
			},
	"couponCode" : ""
}

UPDATE vehicle set overspeed_limit = 91 where customerno IN (64,756);

UPDATE vehicle SET overspeed_limit = 40, kind= 'Bus' where customerno IN (958,
914,
876,
916,
921,
999,
924,
915,
912,
1001,
997,
957,
955,
949,
920,
983,
962,
960,
948,
975,
956,
922,
973,
923,
974,
911,
982,
998,
913,
925,
996,
970,
918,
926,
971,
972,
919,
917,
910,
911)



1) Parent And Product Customer and user setting
2) Data Diff between speed/trace/Radar
3) Project Timelines
4) Architechrural review
5) Marvel App functionality
6) Git Merge
7) ref document shared by sagar


https://docs.google.com/spreadsheets/d/1z_P1ZjI40RRdUkHCEEEgvHylb-4pxfGNJlaU_1oa2Gw/edit?usp=sharing

10000
43746

Route Name
Station
Vehicle No.
Store Name
Date
ETA Time


Phase 1 - (2 to 3 Weeks) - Need Weekly Plan 02-Dec-2019
1) Database with dummy entries
2) Server and Gateway Setup
3) Data Dump Service and Service To Create Tripwise Data
4) Login and authentication and autorization
5) Transactions with dynamic validation
6) Reports And Analytics
7) Dashboard


this key is only used speed for poc of road snapping


select * from lDoc where ldocId IN(
19289,
19287,
19285,
19283,
19281,
19278
)


Phase 1 - (3 Weeks)
1) Database with dummy entries
2) Server and Gateway Setup
3) Data Dump Service and Service To Create Tripwise Data
4) Login and authentication and autorization
5) Transactions with dynamic validation
6) Reports And Analytics
7) Dashboard


Week 1 - 04-Dec-2019 - 07-Dec-2019

1) Database with dummy entries
	- Parent database for SSCT
	- SSCT Database - Masters with dummy entry
	- SSCT Database - Customer setting and User Setting
2) Server and Gateway Setup
5) Transactions with dynamic validation (Dispatch without validation - create and listing)
	- Create Trip 
	- Listing

Week 2 - 09-Dec-2019 - 13-Dec-2019

4) Login and authentication and authorization
	- User login with authentication 	
6) Reports And Analytics (API On Basis Of Mongo)
	- Travel History
	- Location History (Speed and Radar)
	- Route History (Speed and Radar)
	- Stoppage History
	- Temperature Compliance Report
5) Transactions with dynamic validation
	- Transaction with CRUD and validation
	- Excel import for dispatch

Week 3 - 16-Dec-2019 - 21-Dec-2019

6) Reports And Analytics (Web Integration)
	- Travel History
	- Location History (Speed and Radar)
	- Route History (Speed and Radar)
	- Stoppage History
	- Temperature Compliance Report
7) Dashboard
    - Dashboard API
    - Web integration
3) Data Dump Service and Service To Create Tripwise Data
	- Dump data from speed listener to SSCT
	- Dump data from trace listener to SSCT
	- Dump data from radar API to SSCT
	- Dump data from Integration Platform (thirdparty) to SSCT
	- Create middleware service for trip vehicles and update the data in mysql database
	- Create middleware service for trip vehicles and create tripwise cassandra database
6) Reports And Analytics
	- Replace the mongo with cassandra in reports api.


http://mahindrafs.elixiatech.com/modules/cron/crondailyreport_by_limit.php?customerno=64&date=2019-11-08
http://mahindrafs.elixiatech.com/modules/cron/crondailyreport_by_limit.php?customerno=64&date=2019-11-09
http://mahindrafs.elixiatech.com/modules/cron/crondailyreport_by_limit.php?customerno=64&date=2019-11-10
http://mahindrafs.elixiatech.com/modules/cron/crondailyreport_by_limit_new.php?customerno=64&date=2019-11-09


SELECT devicehistory.deviceid, devicehistory.devicelat, devicehistory.devicelong, devicehistory.ignition, devicehistory.status, devicehistory.lastupdated, vehiclehistory.odometer ,vehiclehistory.curspeed 
FROM devicehistory INNER JOIN vehiclehistory ON vehiclehistory.vehiclehistoryid = devicehistory.id 
WHERE vehiclehistory.vehicleid=20872 
AND COALESCE(devicehistory.gpsfixed, NULL) ='A' 
AND devicehistory.lastupdated >= '2019-11-27 00:00' AND devicehistory.lastupdated <= '2019-11-27 23:54' 
ORDER BY devicehistory.lastupdated DESC Limit 0,1


http://speed.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=132&unit=1725010004910&date=2019-10-25

http://speed.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=132&unit=1725010004910&date=2019-10-25


MDLZ Pdf changes
SCCT Parent DB and Product Setting
UPL debug
Mahindra Informatics
Mahindra Distance Analysis

http://mahindrafs.elixiatech.com/modules/cron/crondailyreport_by_limit.php?customerno=64&date=2019-11-06


http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1822020032880&date=2019-11-02

http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1822020032880&date=2019-11-02

15,16,17,18 Nov 2019
AS 01 DN 6455
RJ 21 UB 2056
TN 68 F 8906
OR 05 AS 7882
RJ 27 UA 6942


01,02,03,04 Nov 2019
AS 01 DN 6455
TN 68 F 8906
OR 05 AS 7882
MH 15 DC 8143
AP 07 BW 2112


https://mahindrafs.elixiatech.com/customer/64/Informatics/Nov2019_Informatics_Summarized_Report.xls
https://mahindrafs.elixiatech.com/customer/64/Informatics/Nov2019_Informatics_Detailed_Report.xls


SELECT polId,locationId,containerTypeId,slabId,SUM(sharedLdoc) as shareLdoc, totalLdoc
FROM transporterActualShare
WHERE isDeleted = 0 
AND SUM(sharedLdoc) <> totalLdoc
GROUP BY polId,locationId,containerTypeId,slabId




INSERT INTO transporterActualShare2 (transporterId, polId,locationId,loadingLocation, containerTypeId, slabId, sharedLdoc)
SELECT pltm.transporterId,l.polId,p.locationId,lm.locationName,l.containerType as oc,
case WHEN(l.containerType = 3) THEN 2 ELSE l.containerType END  as containerType,
l.slabId,count(pltm.ldocId) as sharedLdoc
from proposedLdocTransporterMapping pltm
INNER JOIN lDoc l ON l.ldocId = pltm.ldocId
INNER JOIN portOfLoading pol ON pol.portOfLoadingId = l.polId
INNER JOIN salesOrder so on so.ldocId = l.ldocId
LEFT JOIN plant p on so.plant = p.plantId
LEFT JOIN locationMaster lm  on lm.locationId = p.locationId
WHERE DATE(pltm.createdOn) BETWEEN "2019-12-01" AND "2019-12-05"
AND pltm.isDeleted= 0
AND l.isDeleted = 0
AND pol.isDeleted = 0
AND so.isDeleted = 0
AND p.locationId <> 0
AND l.slabId <> 0
Group By l.polId,p.locationId,lm.locationName,case WHEN(l.containerType = 3) THEN 2 ELSE l.containerType END,l.slabId,pltm.transporterId
ORDER BY l.polId,p.locationId,lm.locationName,case WHEN(l.containerType = 3) THEN 2 ELSE l.containerType END,l.slabId, pltm.transporterId;





/* Update sharedldoc, totalldoc, actualshare to 0*/
UPDATE transporterActualShare SET sharedLdoc = 0, totalLdoc = 0, actualShare = 0  WHERE isDeleted = 0;


/* UPDATE shared ldoc of alloted transporters*/
UPDATE transporterActualShare  SET sharedLdoc=
=CONCATENATE(K2,H2, " WHERE transporterId=",A2," AND polId=",C2," AND locationId=",D2," AND containerTypeId=",F2," AND slabId=",G2," and isDeleted = 0;")

/* UPDATE total ldoc of conbination */
UPDATE transporterActualShare  SET totalLdoc=

=CONCATENATE(M2,J2, " WHERE polId=",C2," AND locationId=",D2," AND containerTypeId=",F2," AND slabId=",G2," and isDeleted = 0;")

/*Calculate the actualShare*/
UPDATE transporterActualShare  SET actualShare = (sharedLdoc / totalLdoc ) * 100 , updatedOn = '2019-12-05 19:20:00' AND isDeleted = 0 ;



SELECT pltm.transporterId,l.polId,p.locationId,lm.locationName,l.containerType,l.slabId,count(pltm.ldocId) as sharedLdoc
from proposedLdocTransporterMapping pltm
INNER JOIN lDoc l ON l.ldocId = pltm.ldocId
INNER JOIN portOfLoading pol ON pol.portOfLoadingId = l.polId
INNER JOIN salesOrder so on so.ldocId = l.ldocId
LEFT JOIN plant p on so.plant = p.plantId
LEFT JOIN locationMaster lm  on lm.locationId = p.locationId
WHERE DATE(pltm.createdOn) BETWEEN "2019-12-01" AND "2019-12-05"
AND pltm.isDeleted= 0
AND l.isDeleted = 0
AND pol.isDeleted = 0
AND so.isDeleted = 0
AND p.locationId <> 0
AND l.slabId <> 0

SELECT * from proposedLdocTransporterMapping WHERE hasAccepted IN (0,1) and isDeleted = 0;


localhost/speed/modules/api/vts/api.php?action=getTravelHistory&jsonreq={"userkey":"833dd2fd9ca0e3c00a2bcc94147fe6a739d42063","vehicleNo":"KA52A5079","SDate":"2019-10-09","EDate":"2019-10-09","STime":"00:00:00","ETime":"23:59:59"}


http://speed.elixiatech.com/location.php?lat="20.10494407213445"&long="75.6903076171875";

UPDATE devices SET simcardId = 6108 WHERE uid = 7222 and customerno = 1057;
UPDATE devices SET simcardId = 6109 WHERE uid = 7223 and customerno = 1057;
UPDATE devices SET simcardId = 6110 WHERE uid = 7224 and customerno = 1057;
UPDATE devices SET simcardId = 6111 WHERE uid =  7225 and customerno = 1057;



SELECT 
v.vehicleid, vehicleno, u.userid, u.role, u.username, u.email ,g.groupid, g.code AS branchcode, g.groupname AS branchname,c.cityid AS regionid , c.code AS regioncode, c.name AS regionname, d.districtid as zoneid,
 d.code AS zonecode, d.name AS zonename ,
 (SELECT username FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) AS regionalUserSAP , 
 (SELECT username FROM user WHERE userid = (SELECT heirarchy_id FROM user WHERE userid = u.heirarchy_id and user.isdeleted=0) and user.isdeleted=0) AS zonalUserSAP 
 FROM `vehicle` v INNER JOIN unit un on un.uid = v.uid 
 INNER JOIN devices de on un.uid = de.uid 
 INNER JOIN `group` g ON g.groupid = v.groupid and g.customerno =64 and g.isdeleted = 0 
 INNER JOIN city c ON c.cityid = g.cityid and c.isdeleted = 0 
 INNER JOIN district d ON d.districtid = c.districtid and d.isdeleted=0 
 INNER JOIN state s ON s.stateid = d.stateid and s.isdeleted= 0 
 INNER JOIN nation n ON n.nationid = s.nationid and n.isdeleted=0 
 LEFT JOIN vehicleusermapping vum on v.vehicleid = vum.vehicleid and vum.customerno =64 and vum.isdeleted = 0 AND vum.userid IN (SELECT userid from user where roleid in(37) ) 
 LEFT join user u on u.userid = vum.userid and u.customerno = 64 and u.isdeleted = 0 and u.roleid in(37) 
 WHERE v.customerno = 64 and un.customerno = 64 and de.customerno = 64 and v.isdeleted=0 
 ORDER BY `vehicleno`, coalesce(u.roleid, 0), branchname, regionname, zonename ASC


 UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid = 10832;
 UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid IN(19146,19142,19056,19143,19083,19140);

 742;

 dailyreport 

 

 Searching 67450 files for "AIzaSyAokJCJZpSDa56PatYdoVBUCg-S0mGJAJM"

/opt/lampp/htdocs/speed/modules/map/direction.php:
    7: $file_to_send = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&key=AIzaSyAokJCJZpSDa56PatYdoVBUCg-S0mGJAJM";
    8: //$file_to_send = "https://maps.googleapis.com/maps/api/directions/json?origin=19.0813075,72.8987418&destination=18.5247663,73.7927557&key=AIzaSyAokJCJZpSDa56PatYdoVBUCg-S0mGJAJM";

/opt/lampp/htdocs/speed/modules/mapdashboard/direction.php:
    7: $file_to_send = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&key=AIzaSyAokJCJZpSDa56PatYdoVBUCg-S0mGJAJM";
    8: //$file_to_send = "https://maps.googleapis.com/maps/api/directions/json?origin=19.0813075,72.8987418&destination=18.5247663,73.7927557&key=AIzaSyAokJCJZpSDa56PatYdoVBUCg-S0mGJAJM";

/opt/lampp/htdocs/speed/scripts/radar.js:
  110: var API_KEY = 'AIzaSyAokJCJZpSDa56PatYdoVBUCg-S0mGJAJM';

5 matches across 3 files


1) ETA will be blank as Elixia stopped the ETA calculation service
2) Alerts are not delivered to stores
    a) Checkpoint In/Out Issue
    b) If vehicle not stopped more than 5 min at store
3) Alerts are not generated to checkpoint owners on entering checkpoint
    - Need to implement the functionality (Required 1 day of development)
4) Alerts sent to wrong emails
   - Alerts are sent to next store in route sequence as vehicle lefts from current store.
5) Expected Vs Actual Email count not matched
   - Actual mail count will not be matchhed with Expected count on reference to point 1 and 2.




select c.customerno, realname, c.customercompany, u.phone, u.email  from user u
   INNER JOIN customer c on c.customerno = u.customerno
   WHERE u.role = 'Administrator'
   AND u.isdeleted = 0
   AND c.use_radar = 1 


Accord WaterTech (744) - Require Back dated Data
1) Vehicle No-MH 43 Y 5177 Unit No-1843020059103 Period -1st April 19 To 31st May 19 
   - Data is available from 03-May2019 to 31-May-2019. Vehicle is inactive in month of April 2019.

2) Vehicle No-MH 43 Y 5179 Unit No-1843020058873 Period -1st April 19 To 31st May 19
  - Data is available from 1st April 19 To 31st May 19


  http://speed.elixiatech.com/modules/api/V23/index.php?action=pullroutehistory&userkey=544167161&vehicleid=21155&sdate=26-12-2019&stime=00:00&edate=26-12-2019&etime=23:59&reporttype=0&overspeed=30&holdtime=30&isClientCode=0



Buzzer -
http://speed.elixiatech.com/modules/api/V22/index.php?action=pushbuzzer&userkey=235903379&status=1&vehicleid=661


Immobilizer 
1) Start Vehicle
http://speed.elixiatech.com/modules/api/V22/index.php?action=pushmobiliser&userkey=235903379&status=0&vehicleid=661
2) Stop Vehicle
http://speed.elixiatech.com/modules/api/V22/index.php?action=pushmobiliser&userkey=235903379&status=0&vehicleid=661

User Login History
http://speed.elixiatech.com/modules/api/V22/index.php?action=updateLoginHistory&jsonreq={"userkey":"235903379","pageMasterId":"46","loginType":"1"}

Pull Account Switch Details
http://speed.elixiatech.com/modules/api/V23/index.php?action=pullaccountswitchdetails&jsonreq={"userkey":"235903379"}

Ram 
Sita 
Ravan

el!365x!@ 

http://speed.elixiatech.com/modules/api/V23/index.php?action=pullvehicledetails&userkey=544167161&vehicleid=13290&phone=android&version=V22



http://speed.elixiatech.com/modules/api/V22/index.php?action=getTempNonCompReport&jsonreq={"userkey":"9af25744e80d27d24a740df621ded69e91069124","vehicleid":"7618","vehicleNo":"MH 02 CE 7256","SDate":"2019-10-10","EDate":"2019-10-10","STime":"00:00","ETime":"23:59","interval":"120","customParams":""}




6944

http://www.speed.elixiatech.com/modules/download/report.php?q=stoppage-pdf-328-193659508-1577730600&v=19215
localhost/speed/modules/download/report.php?q=stoppage-pdf-328-193659508-1577730600&v=19215

1988
http://www.speed.elixiatech.com/modules/download/report.php?q=stoppage-pdf-328-193659508-1577730600&v=9055
localhost/speed/modules/download/report.php?q=stoppage-pdf-328-193659508-1577730600&v=9055


Reports API -
Travel History - Done
Stoggage History - Done
Location History - Done
Temperature Compliance - Done
Route History - WIP


Reports api would be delpoy on dev server by 7-Jan-2020, after that we will start reports integration in web portal.

8356986837 - 



http://speed.elixiatech.com/modules/api/V23/index.php?action=clientcode&clientcode=106561


http://speed.elixiatech.com/modules/api/V23/index.php?action=pulldetails&userkey=106561&pageIndex=1&pageSize=-1&phone=android&version=V22&groupid=0&searchstring=&isClientCode=1


http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1734010010372&date=2019-12-08
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1722010004172&date=2019-12-21
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1822020032880&date=2019-12-08
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=906791&date=2019-12-27
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=901426&date=2019-12-31



http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1734010010372&date=2019-12-09
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1722010004172&date=2019-12-22
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=1822020032880&date=2019-12-09
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=906791&date=2019-12-28
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=901426&date=2020-01-01





http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1734010010372&date=2019-12-08
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1722010004172&date=2019-12-21
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1822020032880&date=2019-12-08
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=906791&date=2019-12-27







http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=1822020032880&date=2019-11-02


http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=901426&date=2019-12-31
http://mahindrafs.elixiatech.com/modules/cron/cronDuplicateTimeDeletion.php?customerno=64&unit=901426&date=2020-01-01
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=901426&date=2019-12-31
http://mahindrafs.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=64&unit=901426&date=2020-01-01

http://mahindrafs.elixiatech.com/modules/cron/cron_UpdateNightWeekend.php?date=2019-10-27



http://mahindrafs.elixiatech.com/customer/64/Informatics/Dec2019_Informatics_Summarized_Report.xls
http://mahindrafs.elixiatech.com/customer/64/Informatics/Dec2019_Informatics_Detailed_Report.xls

UPDATE vehiclewise_alert SET customerno = 64 where vehicleid = 6950;


http://mahindrafs.elixiatech.com/modules/cron/crondailyreport_by_limit.php?customerno=64&date=2019-11-01



URL - http://speed.elixiatech.com/modules/team/login.php

Username & Pass - dipinderk

UserName & Pass - arnold


https://dzone.com/articles/8-simple-steps-to-install-mongodb-with-authenticat-1


UPDATE mysql.proc SET DEFINER = 'root@localhost';
SELECT name,type,definer FROM information_schema.routines;


ALTER TABLE `vehicle` ADD `staticTemp1` TINYINT NOT NULL DEFAULT '0' AFTER `vehicle_status`, 
ADD `staticTemp2` TINYINT NOT NULL DEFAULT '0' AFTER `staticTemp1`, 
ADD `staticTemp3` TINYINT NOT NULL DEFAULT '0' AFTER `staticTemp2`, 
ADD `staticTemp4` TINYINT NOT NULL DEFAULT '0' AFTER `staticTemp3`;

Location history 
Input {
	"userToken":"4545uy87867465", // jwt token
	"tripId":"1234", // primary key from trip table
	"vehicleNo":"MH 01 AB 1234", // vehicle alloted to trip
	"startDateTime":"2019-12-10 10:30:00", // report start time
	"endDateTime":"2019-12-10 19:00:00", // report end time
	"reportInterval":"15", // report interval ini min
	"trackingMode":"1" // tracking mode : 1-Speed, 2-Radar
}



Please look into below pending points:

1> RTD details Api
 Vehicle direction parameter -  Please check parameter - "devdir" (device derection)


2> Map Api
 Vehicle direction parameter -  Please check parameter - "devdir" (device derection)
Checkpoints array - http://speed.elixiatech.com/modules/api/V23/index.php?action=pullCheckPoints&jsonreq={"userkey":"544167161","isClientCode":"0"}

3> Group wise data correctness
Please consume group api - 
http://speed.elixiatech.com/modules/api/V23/index.php?action=pullusergroup&userkey=544167161
http://speed.elixiatech.com/modules/api/V23/index.php?action=pullusergroup&userkey=304744369


4> Route summary ETD - Development is already done by Arvind and moved to UAT will share the details once its live.

@Arvind - Please test the funcationality of Route summary ETD on uat and share the details with Bajrang once its live.

Please refer the below mentiond document for pagewise api call. Use V23 instead of V22 as api version.

https://docs.google.com/document/d/1yoaSN-1hAYKk7NC8v0bBbFfizpBmxmPaYiV-u4_F1rU/edit?usp=sharing

Example 
Doc URL - http://speed.elixiatech.com/modules/api/V22/index.php?action=pullusergroup&userkey=544167161

Calling Url - http://speed.elixiatech.com/modules/api/V23/index.php?action=pullusergroup&userkey=544167161


speed.elixiatech.com/modules/api/vts/api.php?action=getVehicleRouteSummary&jsonreq={"userkey":"15bba9b7e1c061a49774b052cdddb2008b9be7a9","vehicleid":"6438"}





SELECT 
	t.name AS personName,
	a.createdOn,a.createdOn,
	a.createdOn,a.createdOn,
	SUBSTRING_INDEX(GROUP_CONCAT(CAST(createdOn AS CHAR) ORDER BY createdOn), ',', 1 ) as minCheckInTime,
	SUBSTRING_INDEX(GROUP_CONCAT(CAST(createdOn AS CHAR) ORDER BY createdOn desc), ',', 1 ) as maxCheckOutTime,
	SEC_TO_TIME(SUM( TIME_TO_SEC(TIMEDIFF(a.createdOn, a.createdOn)) ) ) as diffTime,
	TIMEDIFF(SUBSTRING_INDEX(GROUP_CONCAT(CAST(createdOn AS CHAR) ORDER BY createdOn desc), ',', 1 ), SUBSTRING_INDEX(GROUP_CONCAT(CAST(createdOn AS CHAR) ORDER BY createdOn), ',', 1 )) as diffTime1,
	CASE
        WHEN a.checkValue = 1 THEN 'CHECKED IN'
        WHEN a.checkValue = 0 THEN 'CHECKED OUT'
        WHEN a.checkValue = 3 THEN 'BUSY'
    END AS checkValue,
    a.attendanceId
FROM team t
LEFT OUTER JOIN elixiaOfficeAttendance a ON a.teamid = t.teamid AND DATE(a.createdOn) = '2020-01-14'
WHERE t.teamid = 141
AND t.is_deleted = 0
GROUP BY t.teamid
ORDER BY t.name ASC;









Fatal error: Uncaught Database_Exception: A database error occured (): in /var/www/html/speed/lib/system/DatabaseManager.php:80 Stack trace: #0 /var/www/html/speed/lib/bo/ComQueueManager.php(603): DatabaseManager->executeQuery('SELECT comqueue...') #1 /var/www/html/speed/modules/realtimedata/rtd_functions.php(2323): ComQueueManager->getalerthistleftdiv('10539', '132') #2 /var/www/html/speed/modules/realtimedata/vehicle_histdata_ajax.php(9): get_vehicle_histdata_leftdiv('10539') #3 {main} thrown in /var/www/html/speed/lib/system/DatabaseManager.php on line 80





Fatal error: Uncaught Database_Exception: A database error occured (): in /var/www/html/speed/lib/system/DatabaseManager.php:80 Stack trace: #0 /var/www/html/speed/lib/bo/ComQueueManager.php(603): DatabaseManager->executeQuery('SELECT comqueue...') #1 /var/www/html/speed/modules/realtimedata/rtd_functions.php(2323): ComQueueManager->getalerthistleftdiv('10539', '132') #2 /var/www/html/speed/modules/realtimedata/vehicle_histdata_ajax.php(9): get_vehicle_histdata_leftdiv('10539') #3 {main} thrown in /var/www/html/speed/lib/system/DatabaseManager.php on line 80


Google Maps JavaScript API error: ClientBillingNotEnabledMapError
https://developers.google.com/maps/documentation/javascript/error-messages#client-billing-not-enabled-map-error

ClientBillingNotEnabledMapError

el!365x!@


UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE vehicleid IN(18759,12847,18096,16252);

get_vehicle_with_driver
add_vehicle
modvehicle

UPDATE vehicle SET stoppage_odometer = (odometer - 200), stoppage_transit_time = lastupdated WHERE customerno IN(126,110);


ALTER TABLE `vehicle` CHANGE `staticTemp1` `isStaticTemp1` TINYINT(4) NOT NULL DEFAULT '0', CHANGE `staticTemp2` `isStaticTemp2` TINYINT(4) NOT NULL DEFAULT '0', CHANGE `staticTemp3` `isStaticTemp3` TINYINT(4) NOT NULL DEFAULT '0', CHANGE `staticTemp4` `isStaticTemp4` TINYINT(4) NOT NULL DEFAULT '0';






UPDATE tripdetails set vehicleno='MH 46 BB 5166' ,vehicleid='18124' ,triplogno='TEST001' ,tripstatusid ='10' ,odometer ='26190628' ,devicelat ='18.884678' ,devicelong ='73.173265' ,statusdate ='2020-01-17 22:00:00' ,routename ='Factory To TemBhurni' ,budgetedkms='270' ,budgetedhrs='6' ,consignor='Millennium' ,consignee='Balaji Formalin' ,consignorid='567' ,consigneeid='577' ,billingparty='Bala' ,mintemp='0.00' ,maxtemp='0.00' ,drivername='Sachin Test' ,drivermobile1='9096867705' ,drivermobile2='9096867705' ,remark='On way Trip' ,perdaykm='0' ,is_tripend='1' ,etarrivaldate='2020-01-18' ,materialtype='1' ,updatedtime='2020-01-22 16:53:07', updated_by='10838', isdeleted =0, returnYard = WHERE tripid=46886




This course includes-

    HTML, HTML5, CSS3, JavaScript, jQuery for front-end development
    Node.js, Meteor.js, Angular, PHP, Ruby on Rails for back-end development
    MySQL, MongoDB, PostgreSQL, CouchDB, Apache Cassandra for Database understanding
    Other essential technologies like Memcached, Apache Solr, Apache Lucene and Redis
    GIT, Subversion, Task Runners, Debuggers for version control and debugging



    
    http://dev-scctgateway.elixia.tech/masters/


   Books - https://www.getpostman.com/collections/bc641771c88849e767eb
   Speed - https://www.getpostman.com/collections/663c278e618c0d39c383




/* gme-elixiatechsolution */
/opt/lampp/htdocs/speed/scripts/radar.js:
/opt/lampp/htdocs/speed/modules/team/route_team.php:
/opt/lampp/htdocs/speed/modules/routing/assign_function.php:
/opt/lampp/htdocs/speed/modules/pickupwow/pickup_functions.php:
/opt/lampp/htdocs/speed/modules/pickupwow/pickup_ajax.php:
/opt/lampp/htdocs/speed/modules/pickup/pickup_functions.php:
/opt/lampp/htdocs/speed/modules/pickup/pickup_ajax.php:
/opt/lampp/htdocs/speed/modules/panels/forjs_bck.php:  
/opt/lampp/htdocs/speed/modules/panels/forjs.php: 
/opt/lampp/htdocs/speed/modules/mapdashboard/routeMap.php:
/opt/lampp/htdocs/speed/modules/map/routeMap.php:
/opt/lampp/htdocs/speed/modules/cron/files/calculatedist.php:
/opt/lampp/htdocs/speed/modules/cron/cron_baseLocation.php:
/opt/lampp/htdocs/speed/modules/busroute/busrouteFunctions.php:
/opt/lampp/htdocs/speed/modules/api/vtsuser/areamaster.php:
/opt/lampp/htdocs/speed/modules/api/sec_dashboard/class/class.api.php:
/opt/lampp/htdocs/speed/location_slow.php:
/opt/lampp/htdocs/speed/location.php:
/opt/lampp/htdocs/speed/location.php:
/opt/lampp/htdocs/speed/lib/system/utilities.php:
/opt/lampp/htdocs/speed/lib/bo/GeoCoder.php:
/opt/lampp/htdocs/speed/heatmap.html:
/opt/lampp/htdocs/speed/demo/poojahospital/patients.php:
/opt/lampp/htdocs/speed/demo/poojahospital/class/clsPatients.php:
/opt/lampp/htdocs/speed/deliveryapi/function/get_location.php:
/opt/lampp/htdocs/speed/deliveryapi/delivery_v3.php:
/opt/lampp/htdocs/speed/deliveryapi/delivery_v2.php:




/*JJV_YuyiXZ6YjiWZ2WA6kjkpPrk=*/
/opt/lampp/htdocs/speed/deliveryapi/delivery_v2.php:
/opt/lampp/htdocs/speed/deliveryapi/delivery_v3.php:
/opt/lampp/htdocs/speed/deliveryapi/function/get_location.php:
/opt/lampp/htdocs/speed/demo/poojahospital/class/clsPatients.php:
/opt/lampp/htdocs/speed/lib/bo/GeoCoder.php:
/opt/lampp/htdocs/speed/lib/system/utilities.php:
/opt/lampp/htdocs/speed/location.php:
/opt/lampp/htdocs/speed/location.php:
/opt/lampp/htdocs/speed/location_slow.php:
/opt/lampp/htdocs/speed/modules/api/sec_dashboard/class/class.api.php:
/opt/lampp/htdocs/speed/modules/api/vtsuser/areamaster.php:
/opt/lampp/htdocs/speed/modules/busroute/busrouteFunctions.php:
/opt/lampp/htdocs/speed/modules/cron/cron_baseLocation.php:
/opt/lampp/htdocs/speed/modules/cron/files/calculatedist.php:
/opt/lampp/htdocs/speed/modules/pickup/pickup_ajax.php:
/opt/lampp/htdocs/speed/modules/pickup/pickup_functions.php:
/opt/lampp/htdocs/speed/modules/pickupwow/pickup_ajax.php:
/opt/lampp/htdocs/speed/modules/pickupwow/pickup_functions.php:
/opt/lampp/htdocs/speed/modules/routing/assign_function.php:
/opt/lampp/htdocs/speed/modules/team/route_team.php:



/*AIzaSyAIiCaa3qdm8IRMLfX_QWjDgILxthR0WsI*/
speed/modules/cron/cronfencecheck.php
speed/modules/cron/cronfreezalert.php
$googleapi = "<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyAIiCaa3qdm8IRMLfX_QWjDgILxthR0WsI'></script>"; - commented
trace/modules/cron/cron_all.php


/*AIzaSyAokJCJZpSDa56PatYdoVBUCg-S0mGJAJM*/
speed/modules/map/direction.php
speed/modules/mapdashboard/direction.php
speed/scripts/radar.js


/opt/lampp/htdocs/speed/modules/account/pages/user_history.php:
/opt/lampp/htdocs/speed/modules/account/pages/viewDeletedUsers.php:
/opt/lampp/htdocs/speed/modules/account/pages/viewusers.php:
/opt/lampp/htdocs/speed/modules/group/pages/group_history.php:
/opt/lampp/htdocs/speed/modules/group/pages/viewgroups.php:
/opt/lampp/htdocs/speed/modules/team/acc_customers.php:
/opt/lampp/htdocs/speed/modules/team/allotrm.php:
/opt/lampp/htdocs/speed/modules/team/attendanceLogs.php:
/opt/lampp/htdocs/speed/modules/team/budgeting.php:
/opt/lampp/htdocs/speed/modules/team/crm_analysis.php:
/opt/lampp/htdocs/speed/modules/team/crm_orders.php:
/opt/lampp/htdocs/speed/modules/team/device_status.php:
/opt/lampp/htdocs/speed/modules/team/device_status_details.php:
/opt/lampp/htdocs/speed/modules/team/deviceLocation.php:
/opt/lampp/htdocs/speed/modules/team/edit_distCustDetails.php:
/opt/lampp/htdocs/speed/modules/team/edit_docket.php:
/opt/lampp/htdocs/speed/modules/team/frozenPipelineList.php:
/opt/lampp/htdocs/speed/modules/team/invoice_links.php:
/opt/lampp/htdocs/speed/modules/team/invoice_payment_grid.php:
/opt/lampp/htdocs/speed/modules/team/invoice_payment_grid_old.php:
/opt/lampp/htdocs/speed/modules/team/invoiceList.php:
/opt/lampp/htdocs/speed/modules/team/item_master.php:
/opt/lampp/htdocs/speed/modules/team/item_master_details.php:
/opt/lampp/htdocs/speed/modules/team/jobApplications.php:
/opt/lampp/htdocs/speed/modules/team/ledger_list_verification.php:
/opt/lampp/htdocs/speed/modules/team/ledgerList.php:
/opt/lampp/htdocs/speed/modules/team/login_history_grid.php:
/opt/lampp/htdocs/speed/modules/team/modify_pipeline.php:
/opt/lampp/htdocs/speed/modules/team/modifycustomer.php:
/opt/lampp/htdocs/speed/modules/team/modifycustomer.php.06.06.2018:
/opt/lampp/htdocs/speed/modules/team/mydockets.php:
/opt/lampp/htdocs/speed/modules/team/payment_collection.php:
/opt/lampp/htdocs/speed/modules/team/pipelineList.php:
/opt/lampp/htdocs/speed/modules/team/prospectiveCustomers.php:
/opt/lampp/htdocs/speed/modules/team/queryAnalyzer_new.php:
/opt/lampp/htdocs/speed/modules/team/sales_pipeline.php:
/opt/lampp/htdocs/speed/modules/team/salesPipelineDashboard.php:
/opt/lampp/htdocs/speed/modules/team/school.php:
/opt/lampp/htdocs/speed/modules/team/team.php:
/opt/lampp/htdocs/speed/modules/team/unmapped_vehicle.php:
/opt/lampp/htdocs/speed/modules/team/viewCareers.php:
/opt/lampp/htdocs/speed/modules/unit/pages/unit_history.php:
/opt/lampp/htdocs/speed/modules/vehicle/pages/vehicle_logs.php:
/opt/lampp/htdocs/speed/modules/vehicle/pages/viewDeletedVehicles.php:
/opt/lampp/htdocs/speed/modules/vehicle/pages/viewvehicles.php:










































Active device

 - 10,22

 Unit 5,6

 Sim 13,14


Not In -1,-2


count 

Select d.deviceid, d.end_date
from devices d
Inner join unit u on u.uid = d.uid 
Inner join vehicle v on v.uid = u.uid 
Inner join simcard s on s.id = d.simcardid 
Inner join customer c on c.customerno = d.customerno 
WHERE v.isdeleted = 0
and u.trans_statusid IN (5,6)
and s.trans_statusid IN (13,14)
and c.renewal not in (-1,-2)
group by d.end_date




Select count(d.deviceid) as NoOfDevices, DATE_FORMAT(d.end_date, "%M %Y") as EndDate
from devices d
Inner join unit u on u.uid = d.uid 
Inner join vehicle v on v.uid = u.uid 
Inner join simcard s on s.id = d.simcardid 
Inner join customer c on c.customerno = d.customerno 
WHERE v.isdeleted = 0
and u.trans_statusid IN (5,6)
and s.trans_statusid IN (13,14)
and c.renewal not in (-1,-2)
Order by d.end_date DESC
group by DATE_FORMAT(d.end_date, "%M %Y")
Order by d.end_date DESC

Map Keys Used By Bajrang

- AIzaSyAokJCJZpSDa56PatYdoVBUCg-S0mGJAJM - Web and App
- AIzaSyAIiCaa3qdm8IRMLfX_QWjDgILxthR0WsI - Web and App (Commented in Web)



AIzaSyCIdi3tTTXB0Gtkj0pKEdQbijxZNJF2psU - Speed Web Key


Task will be completed by 20-Feb-2020

- Google Hits - In Progress
- Documetation For Reports Logic
- Service To Build Reports 
- Reorts Api Which Pulls The Data From Pre-Build Reports
- Fallback Mechanism For Reports
- Documetation and Design For Optimization Engine 
- Black Box for Route Optimization Engine - To Process Historic Data
- Route Optimization Engine Development
- Reports Web Integration





Mdlz-tms

	URL - upl-controltower.elixiatech.com

	Elixir Credentials
	UserName - elixir620
	Password - el!365x!@

	Transporter Login
	UserName - 405642_N
	Password - 405642

	UPDATE user SET password = sha1(123456) where customerNo = 620 and roleId in (3,4,5);

UPDATE unit set isDoorExt= 1 WHERE customerno = 1049 and unitno IN (
'1933020122439',
'1944020135370',
'1946020136985',
'1951020138072',
'1946020136969',
'1951020138171',
'1946020137132',
'1934020125687',
'1933020123155',
'1934020125513',
'1951020138122',
'1946020137470'
)


'1933020122439',
'1944020135370',
'1946020136985',
'1951020138072',
'1946020136969',
'1951020138171',
'1946020137132',
'1934020125687',
'1933020123155',
'1934020125513',
'1951020138122',
'1946020137470'





http://speed.elixiatech.com/location.php?lat=19.0895595&long=73.9067281



https://maps.google.com/maps/api/geocode/json?latlng=19.0895595,73.9067281&sensor=false&key=AIzaSyCIdi3tTTXB0Gtkj0pKEdQbijxZNJF2psU{ "error_message" : "API keys with referer restrictions cannot be used with this API.", "results" : [], "status" : "REQUEST_DENIED" } 



Array
(
    [destination_addresses] => Array
        (
        )

    [error_message] => This API project is not authorized to use this API.
    [origin_addresses] => Array
        (
        )

    [rows] => Array
        (
        )

    [status] => REQUEST_DENIED
)

Array
(
    [min] => -1
    [km] => -1
)
