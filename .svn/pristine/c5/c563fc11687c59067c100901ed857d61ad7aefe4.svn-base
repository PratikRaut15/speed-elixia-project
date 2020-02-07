USE `speed`;
DROP procedure IF EXISTS `insert_credit_note`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `insert_credit_note`(
IN customernoParam INT,
IN ledgeridParam INT,
IN invamountParam float,
IN invoicenoParam VARCHAR(50),
IN creditamountParam float,
IN reasonParam VARCHAR(255),
IN statusParam INT,
IN invdateParam date,
IN requesteddateParam datetime,
IN approveddateParam datetime,
IN createdbyParam int,
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

IF (requesteddateParam = 0) THEN
    SET requesteddateParam = NULL;
END IF;
IF (approveddateParam = 0) THEN
    SET approveddateParam = NULL;
END IF;

SET isExecutedOutParam = 0;

INSERT INTO `credit_note`(
     `customerno`
    ,`ledgerid`
    ,`invoiceno`
    ,`invoice_amount` 
    ,`credit_amount` 
    , `reason`
    , `status`
    , `invoice_date`
    , `requested_date`
    , `approved_date`
    , `created_by`) 
VALUES (customernoParam
    ,ledgeridParam
    ,invoicenoParam
    ,invamountParam
    ,creditamountParam
    ,reasonParam
    ,statusParam
    ,invdateParam
    ,requesteddateParam
    ,approveddateParam
    ,createdbyParam);
    
 
SET isExecutedOutParam = 1; 

END$$

DELIMITER ;