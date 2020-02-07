USE `speed`;
DROP procedure IF EXISTS `insert_payment_collection`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `insert_payment_collection`(
IN invoiceidParam INT,
IN customernoParam INT,
IN payment_modeParam tinyint,
IN payment_dateParam date,
IN cheque_noParam INT,
IN bank_nameParam VARCHAR(255),
IN bank_branchParam VARCHAR(50),
IN cheque_dateParam date,
IN cheque_statusParam INT,
IN paid_amtParam float,
IN statusParam INT,
IN collectedbyParam INT,
IN remarkParam VARCHAR(255),
IN teamidParam INT,
IN created_onParam datetime,
OUT isExecutedOutParam INT)

BEGIN
DECLARE invoice_amountVar FLOAT;
DECLARE totalPaid_AmountVar FLOAT;
DECLARE InvoiceidVar INT;
DECLARE ip_idVar INT;
DECLARE EXIT HANDLER FOR SQLEXCEPTION
       BEGIN
       /*
           ROLLBACK;
      GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
           @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
           SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
           SELECT @full_error; */
    END;



SET isExecutedOutParam = 0;

INSERT INTO `invoice_payment_mapping`(`invoiceid`
    , `customerno`
    , `pay_mode`
    , `paid_amt`
    , `paymentdate`
    , `cheque_no`
    , `cheque_date`
    , `cheque_status`
    , `bank_name`
    , `bank_branch`
    , `status`
    , `teamid`
    , `remark`
    , `created_by`
    , `created_on`) 
VALUES (invoiceidParam
    ,customernoParam
    ,payment_modeParam
    ,paid_amtParam
    ,payment_dateParam
    ,cheque_noParam
    ,cheque_dateParam
    ,cheque_statusParam
    ,bank_nameParam
    ,bank_branchParam
    ,statusParam
    ,collectedbyParam
    ,remarkParam
    ,teamidParam
    ,created_onParam);
        
 
SET isExecutedOutParam = 1; 

END$$

DELIMITER ;