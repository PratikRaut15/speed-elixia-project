
-- Insert SQL here.

ALTER TABLE customer ADD use_tracking TINYINT NOT NULL DEFAULT 1 AFTER use_geolocation;
ALTER TABLE customer ADD maintenance_limit INT(5) NOT NULL DEFAULT 0 AFTER use_maintenance;


create table loan(
loanid INT(11) AUTO_INCREMENT PRIMARY KEY,
vehicleid INT(11),
marginamt INT(11),
loanamt INT(11),
financier varchar(100),
emiamt FLOAT(11) DEFAULT 0.00,
tennure INT(11),
start_date DATETIME,
end_date DATETIME
);

ALTER TABLE description ADD invoiceamt INT(10) NOT NULL AFTER invoicedate;

ALTER TABLE vehicle add manufacturing_month INT(5) NOT NULL AFTER modelid;

ALTER TABLE insurance ADD idv INT(10) NOT NULL AFTER claim_place;

ALTER TABLE insurance ADD ncb INT(10) NOT NULL AFTER idv;

ALTER TABLE maintenance ADD emailalert VARCHAR(4) NOT NULL AFTER payment_approval_note;

ALTER TABLE maintenance_history ADD emailalert VARCHAR(4) NOT NULL AFTER payment_approval_note;

ALTER TABLE accident_history ADD emailalert VARCHAR(4) NOT NULL AFTER ofasnumber;

ALTER TABLE accident ADD emailalert VARCHAR(4) NOT NULL AFTER ofasnumber;

ALTER TABLE fuelstorrage ADD amount float(11) DEFAULT 0.00 NOT NULL  AFTER vehicleid;
ALTER TABLE fuelstorrage ADD rate float(11) NOT NULL DEFAULT 0.00  AFTER amount;
ALTER TABLE fuelstorrage ADD refno varchar(25) NOT NULL  AFTER rate;
ALTER TABLE fuelstorrage ADD openingkm varchar(15) NOT NULL AFTER refno;
ALTER TABLE fuelstorrage ADD dealerid varchar(15) NOT NULL AFTER openingkm;



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 150, NOW(), 'Shrikanth Suryawanshi','Maintenance Modularization');
