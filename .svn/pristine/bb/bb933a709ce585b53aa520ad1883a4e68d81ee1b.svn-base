-- Successful. Add the Patch to the Applied Patches table.

INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'418', '2016-10-06 16:11:01', 'Arvind Thakur', 'Add new column cqid to smslog table and sms_count,email_count to dailyreport table', '0'
);


-- Insert SQL here.

ALTER TABLE smslog ADD COLUMN cqid INT(11);

ALTER TABLE dailyreport 
ADD COLUMN sms_count INT(11) DEFAULT 0,
ADD COLUMN email_count INT(11) DEFAULT 0;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE 	dbpatches 
SET 	patchdate = NOW()
	, isapplied =1 
WHERE 	patchid = 418;
