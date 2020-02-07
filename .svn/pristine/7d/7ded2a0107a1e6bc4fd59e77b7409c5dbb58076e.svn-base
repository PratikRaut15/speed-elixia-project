DELIMITER $$
DROP procedure IF EXISTS `update_credit_note`$$
CREATE  PROCEDURE `update_credit_note`(
IN creditnoteidParam INT,
IN customernoParam INT,
IN ledgeridParam INT,
IN invoicenoParam VARCHAR(50),
IN credit_amountParam float,
IN reasonParam VARCHAR(255),
IN statusParam VARCHAR(50),
IN requested_dateParam datetime,
IN approved_dateParam datetime,
IN updatedbyParam int,
IN updatedonParam datetime,
OUT isExecutedOutParam INT)
BEGIN

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
       /*
           ROLLBACK;
      GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error; */
    END;

IF (requested_dateParam = 0) THEN
    SET requested_dateParam = NULL;
END IF;
IF (approved_dateParam = 0) THEN
    SET approved_dateParam = NULL;
END IF;

SET isExecutedOutParam = 0;

UPDATE credit_note SET
      customerno=customernoParam,
      ledgerid=ledgeridParam,
      invoiceno=invoicenoParam, 
      credit_amount=credit_amountParam,
      reason=reasonParam, 
      status=statusParam, 
      requested_date=requested_dateParam,
      approved_date=approved_dateParam,
      updated_by=updatedbyParam,
      updated_on=updatedonParam
      Where credit_note_id=creditnoteidParam;    
 
SET isExecutedOutParam = 1; 

END$$

DELIMITER ;