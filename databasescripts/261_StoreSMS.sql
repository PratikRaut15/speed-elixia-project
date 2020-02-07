-- Insert SQL here.

create table storesms(
id int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
customerno int(11) NOT NULL,
checkpointid int(11) NOT NULL,
cname varchar(100)  NOT NULL,
vehicleno varchar(50) NOT NULL,
phone varchar(20) NOT NULL,
message varchar(250) NOT NULL
);

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 261, NOW(), 'Shrikant Suryawanshi','Store SMS IN DATABASE');
