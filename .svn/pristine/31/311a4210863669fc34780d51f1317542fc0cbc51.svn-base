-- Insert SQL here.

create table localbanya_map(
mapid int(11) primary key auto_increment,
customerno int(11),
userid int(11),
entrytime datetime,
mapdate date,
vehicleid int(11) not null,
zoneid int(11) not null,
slotid int(5) not null
);

-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 204, NOW(), 'Akhil','Mappgin table for localbanya');
