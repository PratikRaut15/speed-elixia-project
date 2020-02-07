INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES (655, '2019-01-23 11:01:00', 'Arvind Thakur','Update min max range of vehicle temperature of Sahara customer(353)');


update vehicle
set 	temp1_min=0
        ,temp1_max=24
where customerno IN (353) 
AND (temp1_min <> 0 OR temp1_max <> 24);

update vehicle
set 	temp2_min=0
        ,temp2_max=24
where customerno IN (353) 
AND (temp2_min <> 0 OR temp2_max <> 24);

update vehicle
set 	temp3_min=0
        ,temp3_max=24
where customerno IN (353) 
AND (temp3_min <> 0 OR temp3_max <> 24);

update vehicle
set 	temp4_min=0
        ,temp4_max=24
where customerno IN (353) 
AND (temp4_min <> 0 OR temp4_max <> 24);

UPDATE dbpatches SET 
updatedOn = '2019-01-23 11:01:00'
,isapplied = 1 WHERE patchid = 655;