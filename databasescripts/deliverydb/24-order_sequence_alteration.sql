
alter table `order_route_sequence` add column time_taken int(11) default null after sequence; 

-- Successful. Add the Patch to the Applied Patches table.


INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES ( 24, NOW(), 'Akhil VL','Added time');


