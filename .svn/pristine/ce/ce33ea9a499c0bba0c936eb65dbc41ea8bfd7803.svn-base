-- Insert SQL here.
/*delivery status*/
alter table master_orders add column is_delivered tinyint(1) default 0;

/*slot master*/
create table slot_master(
slot_id int(11) primary key auto_increment,
customerno int(11),
slotname varchar(105) default null,
customer_slot_id int(11),
start_time time,
end_time time,
created_by int(11) default null,
updated_time timestamp default now()
);

/*insert in slots*/
insert into slot_master(customerno, customer_slot_id, start_time, end_time) values(151, 1, '7:00', '9:30');
insert into slot_master(customerno, customer_slot_id, start_time, end_time) values(151, 2, '9:30', '12:00');
insert into slot_master(customerno, customer_slot_id, start_time, end_time) values(151, 3, '12:00', '2:30');
insert into slot_master(customerno, customer_slot_id, start_time, end_time) values(151, 4, '4:00', '6:30');
insert into slot_master(customerno, customer_slot_id, start_time, end_time) values(151, 5, '6:30', '9:00');
insert into slot_master(customerno, customer_slot_id, start_time, end_time) values(151, 6, '9:00', '11:30');


-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 9, NOW(), 'Akhil VL','slot master and alter master_orders');
