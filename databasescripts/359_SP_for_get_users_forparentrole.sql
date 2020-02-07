DELIMITER $$
DROP PROCEDURE IF EXISTS `get_users_forparentrole`$$
CREATE PROCEDURE `get_users_forparentrole`( 
    IN roleidparam INT
    , IN moduleidparam INT
    , IN custno INT
    , IN isHigherUser tinyint 
)
BEGIN
	DECLARE parentroleidparam VARCHAR(30);

	SELECT GROUP_CONCAT(T2.id SEPARATOR ',') INTO parentroleidparam
	FROM 	(
				SELECT
					@r AS _id,
					(SELECT @r := parentroleid FROM role WHERE id = _id) AS parent_id,
					@l := @l + 1 AS lvl
				FROM
					(SELECT @r := roleidparam, @l := 0) vars INNER JOIN role WHERE @r <> 0
			) T1
	INNER JOIN role T2	ON T1._id = T2.id
	WHERE 	T2.customerno = custno
	AND  	T2.moduleid = moduleidparam
	AND  	T2.isdeleted = 0 
	AND		CASE  
				WHEN isHigherUser = 1 THEN T1.lvl > 2
				ELSE T1.lvl = 2
			END
	ORDER BY T1.lvl DESC; 
    
    
    IF(parentroleidparam != 0) THEN
		BEGIN
			SELECT 	  userid
					, username
					, realname
					, email
			FROM 	user 
			WHERE 	FIND_IN_SET(roleid,parentroleidparam)
			AND 	TRIM(LOWER(realname)) != 'elixir'
			AND customerno = custno
			AND isdeleted =0;
		END;
    END IF;

END$$
DELIMITER ;

-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES (359, NOW(), 'Sahil','update sp get_users_forparentrole for heiararchy changes(trigon)');


