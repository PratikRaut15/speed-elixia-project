/*
    Name			-	update_nomens
    Description 	-	to Update Nomenclature
    Parameters		-	nomens name, customerno,updatedby,updatedon, 
    Module			-	Nomenclature Masters
    Created by		-	Manasvi Thakur
    Created on		- 	30 Nov, 2018
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `update_nomens`$$
CREATE PROCEDURE `update_nomens`(
	 IN nomenIdParam INT
	,IN nomensParam VARCHAR(50)
	,IN customernoParam INT
	,IN isdeletedParam INT
	,IN updatedOnParam DATETIME
	,IN updatedByParam INT
	)
BEGIN
	DECLARE varNomensName VARCHAR(50);
        ROLLBACK;
        BEGIN
		/*
      GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
      @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
      SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
      SELECT @full_error;
      */
    END;
        START TRANSACTION;

	IF(customernoParam = '' OR customernoParam = 0) THEN
		SET customernoParam = NULL;
	END IF;
   SET varNomensName = NULL;

	   SELECT name INTO varNomensName 
	   FROM   nomens 
	   WHERE (customerno = customernoParam OR customernoParam IS NULL)
	   AND isdeleted = 0 AND nid = nomenIdParam;
   
		IF(varNomensName != nomensParam && isdeletedParam = 0) THEN
	   		UPDATE nomens 
	   		SET name 		= nomensParam
		   		,isdeleted 	= isdeletedParam
		   		,updated_on	= updatedOnParam
		   		,updated_by = updatedByParam
	   		WHERE customerno = customernoParam
	   		AND nid = nomenIdParam
	   		AND isdeleted = 0 ;
		END IF;

		IF(isdeletedParam = 1) THEN
	   		UPDATE nomens 
	   		SET isdeleted 	= isdeletedParam
		   		,updated_on	= updatedOnParam
		   		,updated_by = updatedByParam
	   		WHERE customerno = customernoParam
	   		AND nid = nomenIdParam
	   		AND isdeleted = 0 ;
		END IF;
		COMMIT;

END$$
DELIMITER ;