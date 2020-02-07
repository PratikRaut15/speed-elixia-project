-- Insert SQL here.

create table device_type(
typeid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
type varchar(35) NOT NULL,
value varchar(10) NOT NULL
);

insert into device_type values(null,'Basic',0);
insert into device_type values(null,'AC',1);
insert into device_type values(null,'Door',2);
insert into device_type values(null,'Genset',4);
insert into device_type values(null,'Temperature 1',8);
insert into device_type values(null,'Temperature 2',16);

ALTER TABLE customer ADD use_panic tinyint(1) NOT NULL AFTER use_delivery;

ALTER TABLE unit ADD is_panic tinyint(1) NOT NULL AFTER analog4_sen;
ALTER TABLE unit ADD is_buzzer tinyint(1) NOT NULL AFTER is_panic;

ALTER TABLE unit ADD type_value varchar(10) NOT NULL AFTER trans_statusid;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 166, NOW(), 'Shreekanth','Device Type');
