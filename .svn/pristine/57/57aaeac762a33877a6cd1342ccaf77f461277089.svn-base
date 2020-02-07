-- Insert SQL here.

create table maintenance_parts(
m_id int(11)  NOT NULL AUTO_INCREMENT PRIMARY KEY,
mid int(11)  NOT NULL,
partid int(11)  NOT NULL, 
qty int(11)  NOT NULL,
amount int(11) NOT NULL,
total int(11) NOT NULL,
flag tinyint(3) NOT NULL
);

create table maintenance_tasks(
m_id int(11)  NOT NULL AUTO_INCREMENT PRIMARY KEY,
mid int(11)  NOT NULL,
partid int(11)  NOT NULL, 
qty int(11)  NOT NULL,
amount int(11) NOT NULL,
total int(11) NOT NULL,
flag tinyint(3) NOT NULL
);



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 172, NOW(), 'Shrikanth Suryawanshi','Maintenance');
