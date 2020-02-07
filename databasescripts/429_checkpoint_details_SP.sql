
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'429', '2016-11-14 17:11:01', 'Arvind Thakur', 'SP for get details of checkpoints', '0'
);

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_chkpoint_details`$$
CREATE PROCEDURE `get_chkpoint_details`(
    IN customernos INT
)
BEGIN
    IF(customernos = '' OR customernos = '0') THEN
		SET customernos = NULL;
	END IF;


    SELECT * FROM checkpoint AS chk WHERE chk.customerno=customernos and chk.isdeleted = 0;

END$$
DELIMITER ;


UPDATE 	dbpatches
SET 	patchdate = NOW()
	, isapplied =1
WHERE 	patchid = 429;
