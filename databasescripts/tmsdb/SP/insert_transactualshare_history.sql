DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_transactualshare_history`$$
CREATE PROCEDURE `insert_transactualshare_history`(
	OUT actualsharehistid INT
)
BEGIN
	INSERT INTO transporter_actualshare_history	(`actshareid`
												, `factoryid`
												, `zoneid`
												, `transporterid`
												, `shared_weight`
												, `total_weight`
												, `actualpercent`
												, `customerno`
												, `created_on`
												, `updated_on`
												, `created_by`
												, `updated_by`
												, `isdeleted`
												, `insertedDate`)
	SELECT	 `actshareid`
			, `factoryid`
			, `zoneid`
			, `transporterid`
			, `shared_weight`
			, `total_weight`
			, `actualpercent`
			, `customerno`
			, `created_on`
			, `updated_on`
			, `created_by`
			, `updated_by`
			, `isdeleted`
            , now()
	FROM 	`transporter_actualshare`;
    
    SET actualsharehistid = LAST_INSERT_ID();

END$$
DELIMITER ;
