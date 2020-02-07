-- Insert SQL here.
create table inactive_device(
track_id int(11) primary key auto_increment,
total_inactive int(11),
entry_time datetime
);



-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 168, NOW(), 'Akhil','inactive device track table');
