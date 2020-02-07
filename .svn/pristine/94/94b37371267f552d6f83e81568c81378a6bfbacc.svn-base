/*
    Name			-	get_nomens
    Description 	-	to Get list of Nomenclatures
    Parameters		-	customerno, nomensid(optional)
    Module			-	Nomenclature Masters
    Created by		-	Manasvi Thakur
    Created on		- 	30 Nov, 2018
*/
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_nomens`$$
CREATE PROCEDURE `get_nomens`(
	IN customernoParam INT
	,IN nidParam INT
	)
BEGIN
  ROLLBACK;
  BEGIN
    /*
      GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
      @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
      SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
      SELECT @full_error;
      */
    END;
    IF(customernoParam = '' OR customernoParam = 0) THEN
		SET customernoParam = NULL;
	END IF;

   IF(nidParam != 0)THEN
	   SELECT *
	   FROM nomens 
	   WHERE (customerno = customernoParam OR customernoParam IS NULL)
	   AND nid = nidParam
	   AND isdeleted = 0;
	END IF;
	
   IF(nidParam = 0)THEN
	   SELECT *
	   FROM nomens 
	   WHERE (customerno = customernoParam OR customernoParam IS NULL)
	   AND isdeleted = 0;
	END IF;

END$$
DELIMITER ;
