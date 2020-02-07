-- Insert SQL here.

alter table vehicle add column rto_location varchar(105) default null;
alter table vehicle add column serial_number varchar(55) default null;
alter table vehicle add column expiry_date date default null;
alter table vehicle add column owner_name varchar(105) default null;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 275, NOW(), 'Akhil','4 new columns in vehicle');
