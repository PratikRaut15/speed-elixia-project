/*
    Name		-	get_users_forparentrole
    Description 	-	to Get users of the roleid or parentid for heirarchy 
    Parameters		-	roleidparam, moduleidparam, custno,isHigherUser
    Module		-	Vehicle Maintenance
    Sub-Modules 	- 	Heirarchy roles
    Sample Call		-	call get_users_forparentrole(23,2,118,1);
    Created by		-	Sahil
    Created on		- 	17 Feb, 2015
    Change details 	-	
    1) 	Updated by	- 	Sahil
	Updated	on	- 	17 Feb, 2015
        Reason		-	new created
*/

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

