/*
    Name			-	insert_groups_for_user
    Description 	-	
    Parameters		-	
    Module			-	Speed
    Sub-Modules 	- 	Maintenance
    Sample Call		-	call insert_groups_for_user(5799, 5, 0, 0, '64', '2016-09-09 13:23:23', @isInserted);
    Created by		-	Mrudang Vora
    Created on		- 	08 Sep, 2016
    Change details 	-	
    1) 	Updated by	- 	
		Updated	on	- 	
		Reason		-	
	2) 
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_groups_for_user`$$
CREATE PROCEDURE `insert_groups_for_user`(
	IN useridParam INT
    , IN districtidParam INT
    , IN cityidParam INT
    , IN groupidParam INT
    , IN custnoParam INT
    , IN todaysdate DATETIME
    , OUT isInserted TINYINT(1) 
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE, 
		@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
		SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
		SELECT @full_error;
        */
        SET isInserted = 0;
	END;
    
	IF cityidParam = 0 OR cityidParam = '' THEN
		SET cityidParam = NULL;
    END IF;
    
	IF groupidParam = 0 OR groupidParam = '' THEN
		SET groupidParam = NULL;
    END IF;
    
	START TRANSACTION;
		UPDATE 	groupman
        SET 	isdeleted = 1
        WHERE 	userid = useridParam
        AND 	customerno = custnoParam;
        
		INSERT INTO groupman (groupid, userid, customerno, `timestamp`, vehicleid, isdeleted)
		SELECT  	g.groupid, useridParam, custnoParam, todaysdate, 0, 0
		FROM 		`group` AS g
		INNER JOIN 	city AS c on g.cityid = c.cityid
		INNER JOIN 	district AS d on c.districtid = d.districtid
		WHERE 		d.districtid = districtidParam
		AND 		(cityidParam IS NULL OR c.cityid = cityidParam)
        AND 		(groupidParam IS NULL OR g.groupid = groupidParam)
		AND			d.isdeleted = 0
        AND 		d.customerno = custnoParam
		AND			c.isdeleted = 0
		AND 		c.customerno = custnoParam
        AND			g.isdeleted = 0
		AND 		g.customerno = custnoParam;
        
		SET isInserted = 1;
	COMMIT;
END$$
DELIMITER ;








