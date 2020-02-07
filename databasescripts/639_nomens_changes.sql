INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('639', '2018-11-30 16:30:00', 'Manasvi Thakur','Nomenclature changes', '0');

ALTER TABLE `nomens`  ADD `created_on` DATETIME NOT NULL  
AFTER `isdeleted`,  ADD `created_by` INT(11) NOT NULL  
AFTER `created_on`,  ADD `updated_on` DATETIME NOT NULL  
AFTER `created_by`,  ADD `updated_by` INT(11) NOT NULL  
AFTER `updated_on`;


/*To get Nomens*/
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



/*To insert Nomens*/

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_nomens`$$
CREATE PROCEDURE `insert_nomens`(
	IN nomensParam VARCHAR(50)
  ,IN customernoParam INT
	,IN createdOnParam DATETIME
	,IN createdByParam INT
	,IN updatedOnParam DATETIME
	,IN updatedByParam INT
	)
BEGIN
	DECLARE varNomensid INT;

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
   BEGIN
	IF(customernoParam = '' OR customernoParam = 0) THEN
		SET customernoParam = NULL;
	END IF;
   
   INSERT INTO nomens(	
              name
   						,customerno
   						,isdeleted
   						,created_on
   						,created_by
   						,updated_on
   						,updated_by
   					) 
  			 VALUES(	
               nomensParam
  			 			,customernoParam
  			 			,0
  			 			,createdOnParam
  			 			,createdByParam
  			 			,updatedOnParam
  			 			,updatedByParam
  			 		);

	END;
	COMMIT;

   SET varNomensid = LAST_INSERT_ID();
   select varNomensid;
END$$
DELIMITER ;

/*To update nomens*/
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


UPDATE  dbpatches
SET     patchdate = '2018-11-30 16:30:00'
        ,isapplied =1
WHERE   patchid = 639;