INSERT INTO `dbpatches` (`patchid`, `updatedOn`, `appliedby`, `patchdesc`) 
VALUES (652, NOW(), 'Manasvi Thakur','Added new SP to check If nomenclature exist');

DELIMITER $$
DROP PROCEDURE IF EXISTS `check_if_exist_nomens`$$
CREATE PROCEDURE `check_if_exist_nomens`(
	IN nomensParam VARCHAR(50)
  ,IN customernoParam INT
	)
BEGIN
	DECLARE varNomensid INT;
  DECLARE countNomensVar INT;
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
   
          SELECT count(nid) as countNomensVar
          FROM nomens  
          WHERE name = nomensParam 
          AND customerno = customernoParam;
	END;
	COMMIT;

END$$
DELIMITER ;

UPDATE dbpatches SET isapplied=1 WHERE patchid = 652;