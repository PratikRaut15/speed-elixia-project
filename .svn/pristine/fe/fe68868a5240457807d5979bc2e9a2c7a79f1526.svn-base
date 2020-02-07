USE `speed`;
DROP procedure IF EXISTS `update_payment_collection`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `update_payment_collection`(
IN invoiceidParam INT,
IN customernoParam INT,
IN payment_idParam INT,
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
IN updated_onParam datetime,
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
    IF (invoiceidParam = 0) THEN
            SET invoiceidParam = NULL;
        END IF;
    SET isExecutedOutParam = 0;

UPDATE invoice_payment_mapping
SET `paid_amt`=paid_amtParam,
    `customerno`=customernoParam,
    `paymentdate`=payment_dateParam,
    `cheque_no`=cheque_noParam,
    `cheque_date`=cheque_dateParam,
    `bank_name`=bank_nameParam,
    `bank_branch`=bank_branchParam,
    `cheque_status`=cheque_statusParam,
    `invoiceid`=invoiceidParam,
    `pay_mode`=payment_modeParam,
    `status`=statusParam,
    `teamid`=collectedbyParam,
    `remark`=remarkParam,
    `updated_by`=teamidParam,
    `updated_on`=updated_onParam
WHERE `ip_id`=payment_idParam;



SET isExecutedOutParam = 1;
END$$

DELIMITER ;