/*
    Name			-	insert_nomens
    Description 	-	to Insert Nomenclature
    Parameters		-	nomens name, customerno,createdon,createdby,updatedby,updatedon, 
    Module			-	Nomenclature Masters
    Created by		-	Manasvi Thakur
    Created on		- 	30 Nov, 2018
*/
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

 CALL insert_nomens('test_nomens','74',date('Y-m-d h:i:s'),'263',date('Y-m-d h:i:s'),'263');