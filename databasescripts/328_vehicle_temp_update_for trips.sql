DROP TABLE IF EXISTS vehicle_history;
CREATE TABLE vehicle_history (
 vehicle_histid int(11) NOT NULL AUTO_INCREMENT,
  vehicleid int(11) NOT NULL,
  vehicleno varchar(40) NOT NULL,
  extbatt varchar(5) NOT NULL,
  odometer varchar(15) NOT NULL,
  lastupdated datetime NOT NULL,
  curspeed varchar(5) NOT NULL,
  driverid int(11) NOT NULL,
  customerno int(11) NOT NULL,
  uid int(11) NOT NULL,
  isdeleted tinyint(1) NOT NULL,
  kind varchar(11) NOT NULL DEFAULT 'Car',
  userid int(11) NOT NULL,
  groupid int(11) NOT NULL DEFAULT '0',
  branchid int(11) NOT NULL,
  overspeed_limit smallint(3) NOT NULL DEFAULT '80',
  average smallint(2) NOT NULL DEFAULT '0',
  modelid int(11) NOT NULL,
  manufacturing_month int(5) NOT NULL,
  manufacturing_year int(5) NOT NULL,
  purchase_date date NOT NULL,
  start_meter_reading int(10) NOT NULL,
  fueltype varchar(225) NOT NULL,
  is_insured tinyint(1) NOT NULL,
  status_id tinyint(1) NOT NULL,
  temp1_min int(11) NOT NULL,
  temp1_max int(11) NOT NULL,
  temp2_min int(11) NOT NULL,
  temp2_max int(11) NOT NULL,
  temp3_min int(11) NOT NULL,
  temp3_max int(11) NOT NULL,
  temp4_min int(11) NOT NULL,
  temp4_max int(11) NOT NULL,
  other_upload1 varchar(250) NOT NULL,
  other_upload2 varchar(250) NOT NULL,
  other_upload3 varchar(250) NOT NULL,
  timestamp datetime NOT NULL,
  stoppage_odometer int(11) NOT NULL,
  stoppage_transit_time datetime NOT NULL,
  nodata_alert int(11) NOT NULL,
  stoppage_flag tinyint(1) NOT NULL,
  submission_date datetime NOT NULL,
  registration_date datetime NOT NULL,
  approval_date datetime NOT NULL,
  additional_amount int(11) NOT NULL,
  description varchar(40) NOT NULL,
  sms_count int(11) NOT NULL,
  sms_lock tinyint(1) NOT NULL DEFAULT '0',
  fuel_balance float(5,2) DEFAULT '0.00',
  fuelcapacity int(4) NOT NULL,
  max_voltage int(8) DEFAULT NULL,
  fuel_min_volt float(10,2) DEFAULT '0.00',
  fuel_max_volt float(10,2) DEFAULT '0.00',
  notes varchar(255) NOT NULL,
  rto_location varchar(105) DEFAULT NULL,
  serial_number varchar(55) DEFAULT NULL,
  expiry_date date DEFAULT NULL,
  owner_name varchar(105) DEFAULT NULL,
  invcustid int(11) NOT NULL,
  sequenceno int(11) NOT NULL DEFAULT '0',
  insertdate DATETIME,
  PRIMARY KEY (vehicle_histid),
  KEY uid (uid)
);


DELIMITER $$
DROP TRIGGER IF EXISTS before_vehicle_update$$
CREATE TRIGGER before_vehicle_update BEFORE UPDATE ON vehicle
 FOR EACH ROW BEGIN
	IF (NEW.temp1_min <>  OLD.temp1_min 
		OR NEW.temp2_min <>  OLD.temp2_min
        OR NEW.temp3_min <>  OLD.temp3_min
        OR NEW.temp4_min <>  OLD.temp4_min
        OR NEW.temp1_max <>  OLD.temp1_max
        OR NEW.temp2_max <>  OLD.temp2_max
        OR NEW.temp3_max <>  OLD.temp3_max
        OR NEW.temp4_max <>  OLD.temp4_max
        ) THEN
		BEGIN
        INSERT INTO vehicle_history
									(
									vehicleid,
									vehicleno,
									extbatt,
									odometer,
									lastupdated,
									curspeed,
									driverid,
									customerno,
									uid,
									isdeleted,
									kind,
									userid,
									groupid,
									branchid,
									overspeed_limit,
									average,
									modelid,
									manufacturing_month,
									manufacturing_year,
									purchase_date,
									start_meter_reading,
									fueltype,
									is_insured,
									status_id,
									temp1_min,
									temp1_max,
									temp2_min,
									temp2_max,
									temp3_min,
									temp3_max,
									temp4_min,
									temp4_max,
									other_upload1,
									other_upload2,
									other_upload3,
									timestamp,
									stoppage_odometer,
									stoppage_transit_time,
									nodata_alert,
									stoppage_flag,
									submission_date,
									registration_date,
									approval_date,
									additional_amount,
									description,
									sms_count,
									sms_lock,
									fuel_balance,
									fuelcapacity,
									max_voltage,
									fuel_min_volt,
									fuel_max_volt,
									notes,
									rto_location,
									serial_number,
									expiry_date,
									owner_name,
									invcustid,
									sequenceno
									, insertdate)
			VALUES 	(
									OLD.vehicleid
									, OLD.vehicleno
									, OLD.extbatt
									, OLD.odometer
									, OLD.lastupdated
									, OLD.curspeed
									, OLD.driverid
									, OLD.customerno
									, OLD.uid
									, OLD.isdeleted
									, OLD.kind
									, OLD.userid
									, OLD.groupid
									, OLD.branchid
									, OLD.overspeed_limit
									, OLD.average
									, OLD.modelid
									, OLD.manufacturing_month
									, OLD.manufacturing_year
									, OLD.purchase_date
									, OLD.start_meter_reading
									, OLD.fueltype
									, OLD.is_insured
									, OLD.status_id
									, OLD.temp1_min
									, OLD.temp1_max
									, OLD.temp2_min
									, OLD.temp2_max
									, OLD.temp3_min
									, OLD.temp3_max
									, OLD.temp4_min
									, OLD.temp4_max
									, OLD.other_upload1
									, OLD.other_upload2
									, OLD.other_upload3
									, OLD.timestamp
									, OLD.stoppage_odometer
									, OLD.stoppage_transit_time
									, OLD.nodata_alert
									, OLD.stoppage_flag
									, OLD.submission_date
									, OLD.registration_date
									, OLD.approval_date
									, OLD.additional_amount
									, OLD.description
									, OLD.sms_count
									, OLD.sms_lock
									, OLD.fuel_balance
									, OLD.fuelcapacity
									, OLD.max_voltage
									, OLD.fuel_min_volt
									, OLD.fuel_max_volt
									, OLD.notes
									, OLD.rto_location
									, OLD.serial_number
									, OLD.expiry_date
									, OLD.owner_name
									, OLD.invcustid
									, OLD.sequenceno
									, NOW()
					);
		END;
	END IF;
END$$
DELIMITER ;
INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (328, NOW(), 'Mrudang','Update temperatures in vehicle on trip insert update');
