INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'414', '2016-09-26 12:11:01', 'Arvind Thakur', 'create status_change_log table', '0'
);


CREATE TABLE status_change_log(
    scl_id int(11) AUTO_INCREMENT,
    id bigint(11),
    old_status int(11),
    new_status int(11),
    type tinyint(2),
    updated_on datetime,
    updated_by int(11),
    PRIMARY KEY(scl_id));


UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 414;