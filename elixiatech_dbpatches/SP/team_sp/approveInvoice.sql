USE `elixiatech`;
DROP procedure IF EXISTS `approveInvoice`;
DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `approveInvoice`(
	IN `invoiceIdParam` INT, 
	IN `teamIdParam` INT, 
	IN `todayParam` DATETIME, 
	IN `nextInvoiceDate` DATETIME,
	IN `startDateParam` DATE,
	IN `endDateParam` DATE,
	OUT retInvIdOut INT)
BEGIN
	DECLARE isexecutedOut INT;
	DECLARE invTypeVar INT;
	DECLARE amtVar INT;
	DECLARE amcVar INT;
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
		UPDATE invoice_reminders 
	    SET approvedOn = todayParam,
			approvedBy = teamIdParam,
	        invoice_generated = 1,
	        start_date = startDateParam,
	        end_date = endDateParam
	    WHERE inv_rem_id = invoiceIdParam;
	    SELECT contract_type,amc_amount,amount into invTypeVar,amcVar,amtVar FROM invoice_reminders WHERE inv_rem_id = invoiceIdParam;
	    if(invTypeVar = 1) THEN
	        SET invTypeVar =2;
		END IF;
	    IF((invTypeVar = 2 AND amcVar <> 0 AND amcVar is not null) OR invTypeVar = 3) THEN 
			INSERT INTO `elixiatech`.`invoice_reminders` (`customerno`,
			`ledgerno`,
			`contract_type`,
			`remarks`,
			`productId`,
			`expectedInvDate`,
			`inv_amt`,
			`inv_desc`,
			`amount`,
			`amc_amount`,
			`cycle`,
			`approvedOn`,
			`approvedBy`,
			`timestamp`,
			`createdBy`,
			`invoice_generated`,
			`isdeleted`,
			`reminder_date`)
			SELECT `customerno`,
					`ledgerno`,
					invTypeVar,
					`remarks`,
					`productId`,
					nextInvoiceDate,
					(CASE WHEN invTypeVar=3 THEN amtVar ELSE amcVar END),
					`inv_desc`,
					amtVar,
					amcVar,
					`cycle`,
					todayParam,
					teamIdParam,
					todayParam,
					teamIdParam,
					'0',
					'0',
					nextInvoiceDate
			FROM invoice_reminders
			WHERE inv_rem_id = invoiceIdParam;
		END IF;
		call create_invoice(invoiceIdParam,DATE_FORMAT(todayParam, '%Y-%m-%d'),todayParam,DATE_ADD(DATE_FORMAT(todayParam, '%Y-%m-%d'),INTERVAL 1 MONTH),@isexecutedOut,@invIdOut);
	    SELECT @invIdOut into retInvIdOut;
	END;
	COMMIT;
END$$
DELIMITER ;
