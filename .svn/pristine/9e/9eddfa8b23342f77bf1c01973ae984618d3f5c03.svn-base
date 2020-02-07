INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'631', '2018-11-01 17:07:00', 'Arvind Thakur', 'set min max temperature range for TMS transporter customers', '0'
);


update vehicle
set temp1_min = 0
	,temp2_min = 0
    ,temp3_min = 0
    ,temp4_min = 0
    ,temp1_max = 24
	,temp2_max = 24
    ,temp3_max = 24
    ,temp4_max = 24
where customerno IN (674,644,524,643,523,206,353,613,632);



UPDATE  dbpatches
SET     updatedOn = '2018-11-01 17:15:00',isapplied = 1
WHERE   patchid = 631;
