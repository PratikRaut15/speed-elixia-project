-- Insert SQL here.
alter table client_address add column location_name varchar(55) default null after landmark;
alter table client_address add column city_name varchar(55) default null after location_name;
ALTER TABLE `client_address` CHANGE `isdeleted` `isdeleted` TINYINT(1)  DEFAULT '0';
ALTER TABLE `client` ADD INDEX(`mobile`);
alter table `client` add column temp_password varchar(105) default null after password; 
create table temp_city_loc(
clid int(11) AUTO_INCREMENT PRIMARY key,
customerno int(11),
location_name varchar(55),
city_name varchar(55),
addedby_app int(11) not null,    
entrytime datetime not null,    
isdeleted tinyint(1) default 0
);
alter table client_address add column pincode varchar(10) default null after landmark;
-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES ( 25, NOW(), 'Akhil','Api based changes');



