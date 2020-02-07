USE `elixiatech`;
DROP procedure IF EXISTS `scheduleInvoice`;
DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `scheduleInvoice`(
	IN customerNoParam INT,
    IN ledgerNoParam INT,
    IN contractTypeParam TINYINT,
    IN productIdParam INT,
    IN remarksParam VARCHAR(255),
    IN invEidParam DATETIME,
    IN invAmtParam FLOAT,
    IN invDescParam VARCHAR(255),
    IN amountParam FLOAT,
    IN amcAmountParam FLOAT,
    IN cycleParam INT,
    IN todayParam DATETIME,
    IN createdByParam INT
)
BEGIN
	DECLARE isexecutedOut INT;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			/*ROLLBACK;
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
			@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
			SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
			SELECT @full_error;  
			SET isexecutedOut = 0;*/
		END;
       
		START TRANSACTION;
		BEGIN
        INSERT INTO `elixiatech`.`invoice_reminders`
			(`customerno`,
			`ledgerno`,
			`contract_type`,
			`remarks`,
			`productId`,
			`amount`,
			`amc_amount`,
			`expectedInvDate`,
			`reminder_date`,
			`cycle`,
			`inv_amt`,
			`inv_desc`,
			`timestamp`,
			`createdBy`)
			VALUES
			(customerNoParam,
			ledgerNoParam,
			contractTypeParam,
			remarksParam,
			productIdParam,
			invAmtParam,
			amcAmountParam,
			invEidParam,
			invEidParam,
			cycleParam,
			invAmtParam,
			invDescParam,
			todayParam,
			createdByParam);

		END;
		COMMIT;
END$$
DELIMITER ;