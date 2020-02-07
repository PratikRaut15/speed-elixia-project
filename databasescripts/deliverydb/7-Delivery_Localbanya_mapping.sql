-- Insert SQL here.

create table order_route_sequence(
sequence_id int(11) primary key auto_increment,
vehicle_id int(11) not null,
order_id int(11) not null,
sequence int(5) not null,
update_time datetime,
updated_by int(11)
);

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 7, NOW(), 'Akhil VL','route sequence saving table');
