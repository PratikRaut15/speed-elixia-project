USE `elixiatech`;
CREATE TABLE `dbpatches` (
  `patchid` int(11) NOT NULL,
  `patchdate` datetime NOT NULL,
  `appliedby` varchar(20) NOT NULL,
  `patchdesc` varchar(255) NOT NULL,
  `isapplied` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`patchid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `elixiatech`.`dbpatches` VALUES(1,'2018-06-11 18:41:47','Kartik','Scheduling invoices and dbpatches table added to elixiatech',0)

DELIMITER $$
DROP procedure IF EXISTS `scheduleInvoice`$$
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

DELIMITER $$
DROP procedure IF EXISTS `fetch_invoice_cycles`$$
CREATE PROCEDURE `fetch_invoice_cycles`()
BEGIN

	select cycle_id, cycle_name from invoice_cycles WHERE isdeleted = 0;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_invoice_products`$$
CREATE PROCEDURE `fetch_invoice_products`()
BEGIN

	SELECT prod_id,prod_name FROM  invoice_products;
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetchInvoiceReminders`$$

CREATE PROCEDURE `fetchInvoiceReminders`(
	IN invIdParam INT
)
BEGIN
	SELECT ir.inv_rem_id,c.customercompany,it.inv_type_name,
		ir.remarks,ip.prod_name,ir.expectedInvDate,ir.inv_amt as invoiceAmount,
		ir.inv_desc,ir.invoiceid,
        (CASE WHEN ir.contract_type=3
			THEN 'N.A.'
		ELSE ir.amount
		END
        )as amount,
        (CASE WHEN ir.contract_type=3
			THEN 'N.A.'
		ELSE
			ir.amc_amount
        END)as amc_amount,ir.reminder_date,ir.start_date,ir.end_date,ic.cycle_name,ir.invoice_generated,
        l.ledgername
    
    FROM invoice_reminders ir
    
    LEFT JOIN customer c ON c.customerno = ir.customerno
    LEFT JOIN invoice_type it ON it.inv_type_id=ir.contract_type
    LEFT JOIN invoice_products ip ON ip.prod_id = ir.productId
    LEFT JOIN invoice_cycles ic ON ic.cycle_id = ir.cycle 
    LEFT JOIN speed.ledger l ON l.ledgerid = ir.ledgerno
    WHERE inv_rem_id = invIdParam OR invIdParam = 0 AND ir.isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `rescheduleInvoice`$$
CREATE PROCEDURE `rescheduleInvoice`(
	IN rescheduleDateParam DATETIME,
    IN invoiceIdParam INT
)
BEGIN
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
            SET reminder_date = rescheduleDateParam
            WHERE inv_rem_id = invoiceIdParam;
		END;
		COMMIT;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `approveInvoice`$$
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

DELIMITER $$
DROP procedure IF EXISTS `create_invoice`$$

CREATE PROCEDURE `create_invoice`(
	IN inv_rem_idParam INT,
    IN todayDateParam DATE,
    IN todayDateTimeParam DATETIME,
    IN invExpiryParam DATE,
    Out isexecutedOut INT,
    OUT invIdOut INT
)
BEGIN
	DECLARE ledgerNoVar INT;
	DECLARE customerNoVar INT;
	DECLARE invIdVar INT;
	DECLARE invNoVar VARCHAR(50);
	DECLARE cgstVar, sgstVar, igstVar,invAmtVar,taxAmtVar,totalAmtVar FLOAT;
	DECLARE customerNameVar,productNameVar VARCHAR(100);
	DECLARE prodIdVar, invTypeVar INT;
	DECLARE descriptionVar,descVar VARCHAR (350);
	DECLARE prodChar,subChar VARCHAR (3);
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
		SELECT max(invoiceid) into invIdVar from speed.invoice;
        SET invIdVar = invIdVar + 1;
        
        SELECT customerno,ledgerno INTO customerNoVar,ledgerNoVar from invoice_reminders WHERE inv_rem_id = inv_rem_idParam;
        
        SELECT customerCompany into customerNameVar FROM customer where customerno = customerNoVar;

		SELECT i.productId,i.contract_type,p.name,i.inv_amt Into prodIdVar,invTypeVar,productNameVar,invAmtVar
			FROM invoice_reminders i
            LEFT JOIN speed.product p ON p.id = i.productId 
            WHERE inv_rem_id = inv_rem_idParam;
            
        SET descriptionVar = productNameVar;
        
        CASE WHEN prodIdVar = 3
			THEN SET prodChar = 'EE';
		WHEN prodIdVar = 11
			THEN SET prodChar = 'EC';
		END CASE;
        
        CASE WHEN invTypeVar = 1
			THEN 
				SET subChar = 'O';
				SET descriptionVar = CONCAT(descriptionVar,' Development charges');
		WHEN invTypeVar = 2
			THEN SET subChar = 'A';
            SET descriptionVar = CONCAT(descriptionVar,' AMC');
		WHEN invTypeVar = 3
			THEN SET subChar = 'S';
            SET descriptionVar = CONCAT(descriptionVar,' Subscription charges');
        END CASE;
        
        SET invNoVar = CONCAT(prodChar,subChar,customerNoVar,ledgerNoVar,invIdVar);
        
        CALL calculate_taxes(ledgerNoVar,invAmtVar,@cgstOut,@sgstOut,@igstOut);
        SELECT @cgstOut,@sgstOut,@igstOut into cgstVar, sgstVar, igstVar;
        
        SET taxAmtVar = cgstVar + sgstVar + igstVar;
		SET totalAmtVar = invAmtVar + taxAmtVar;
        
        INSERT INTO `speed`.`invoice`
			(`invoiceno`,`customerno`,`ledgerid`,`clientname`,`inv_date`,`inv_amt`,
			`status`,`pending_amt`,`tax`,`tax_amt`,`paid_amt`,`tds_amt`,`unpaid_amt`,`inv_expiry`,
			`comment`,`isdeleted`,`product_id`,`customername`
            ,`start_date`,`end_date`,
			`timestamp`,`is_mail_sent`,`quantity`,`cgst`,`sgst`,`igst`)
			
			SELECT invNoVar,`customerno`,`ledgerno`,customerNameVar,`todayDateParam`,totalAmtVar,
			'Pending',totalAmtVar,4,0,0,0,0,invExpiryParam,
			descriptionVar,0,`productId`,customerNameVar
            ,`start_date`,`end_date`,
			todayDateTimeParam,0,1,cgstVar,sgstVar,igstVar
			FROM elixiatech.invoice_reminders WHERE inv_rem_id = inv_rem_idParam;
        
		SET  isexecutedOut = 1;
        UPDATE `elixiatech`.`invoice_reminders` 
			SET invoiceid = invIdVar
		WHERE inv_rem_id = inv_rem_idParam;
        SET invIdOut = invIdVar;
        select invIdOut;
		END;
		COMMIT;
END$$

DELIMITER ;



DELIMITER $$
DROP procedure IF EXISTS `calculate_taxes`$$
CREATE PROCEDURE `calculate_taxes`(
	In ledgerNoParam Int,
    In amtParam Float,
    Out cgstOut Float,
    Out sgstOut Float,
    Out igstOut Float
)
BEGIN
	DECLARE stateCodeVar INT;
    
    Select state_code into stateCodeVar From speed.ledger Where ledgerid = ledgerNoParam;
    
    CASE WHEN stateCodeVar = 27 THEN
		SET cgstOut = round(amtParam*0.09);
        SET sgstOut = round(amtParam*0.09);
        SET igstOut = 0;
	ELSE
		SET cgstOut = 0;
        SET sgstOut = 0;
        SET igstOut = round(amtParam*0.18);
    END CASE;
END$$
DELIMITER ;

ALTER TABLE `speed`.`invoice` MODIFY COLUMN `comment` VARCHAR(255);

CREATE TABLE `invoice_reminders` (
  `inv_rem_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` int(11) DEFAULT NULL,
  `customerno` int(11) DEFAULT NULL,
  `ledgerno` int(11) DEFAULT NULL,
  `contract_type` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `amc_amount` float DEFAULT NULL,
  `expectedInvDate` date DEFAULT NULL,
  `reminder_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `cycle` float DEFAULT NULL,
  `inv_amt` float DEFAULT NULL,
  `inv_desc` varchar(255) DEFAULT NULL,
  `approvedOn` datetime DEFAULT NULL,
  `approvedBy` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `invoice_generated` tinyint(4) DEFAULT '0',
  `isdeleted` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`inv_rem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `invoice_products` (
  `prod_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_name` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `invoice_cycles` (
  `cycle_id` int(11) NOT NULL AUTO_INCREMENT,
  `cycle_name` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `isdeleted` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`cycle_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `invoice_type` (
  `inv_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `inv_type_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`inv_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


INSERT INTO elixiatech.invoice_products VALUES (3,'Elixia Enterprise',112,now()),(11,'Elixia Control Tower',112,now());
INSERT INTO elixiatech.invoice_cycles VALUES (1,'Monthly',112,NOW(),0),(2,'Quarterly',112,NOW(),0),(3,'Half Yearly',112,NOW(),0),(4,'Yearly',112,NOW(),0);
INSERT INTO elixiatech.invoice_type VALUES (1,'One Time',112,NOW()),(2,'AMC',112,NOW()),(3,'SAAS',112,NOW());

USE `elixiatech`;
DROP procedure IF EXISTS `getInvoiceReminders`;

DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `getInvoiceReminders`(
	IN todayParam DATE
)
BEGIN
	SELECT ir.inv_rem_id,c.customercompany,it.inv_type_name,
		ir.remarks,ip.prod_name,ir.expectedInvDate,ir.inv_amt as invoiceAmount,
		ir.inv_desc,ir.invoiceid,
        (CASE WHEN ir.contract_type=3
			THEN 'N.A.'
		ELSE ir.amount
		END
        )as amount,
        (CASE WHEN ir.contract_type=3
			THEN 'N.A.'
		ELSE
			ir.amc_amount
        END)as amc_amount,ir.reminder_date,ir.start_date,ir.end_date,ic.cycle_name,ir.invoice_generated,
        l.ledgername
    
    FROM invoice_reminders ir
    
    LEFT JOIN customer c ON c.customerno = ir.customerno
    LEFT JOIN invoice_type it ON it.inv_type_id=ir.contract_type
    LEFT JOIN invoice_products ip ON ip.prod_id = ir.productId
    LEFT JOIN invoice_cycles ic ON ic.cycle_id = ir.cycle 
    LEFT JOIN speed.ledger l ON l.ledgerid = ir.ledgerno
    WHERE ir.reminder_date <= todayParam AND ir.invoice_generated = 0 AND ir.isdeleted = 0; 
END$$

DELIMITER ;

DELIMITER $$
		DROP PROCEDURE IF EXISTS `editInvoiceReminder`$$
		CREATE PROCEDURE `editInvoiceReminder`(
			IN invIdParam INT,
		    IN customerNoParam INT,
		    IN ledgerNoParam INT,
		    IN productParam INT,
		    IN invAmtParam FLOAT,
		    IN invDescParam VARCHAR(255),
		    OUT isexecutedOut INT
		)
		BEGIN
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
			    SET 
						customerno = customerNoParam,
						ledgerno = ledgerNoParam,
						productId = productParam,
				inv_amt = invAmtParam,
				inv_desc = invDescParam
					WHERE inv_rem_id = invIdParam;

				END;
				COMMIT;
			SET isexecutedOut = 1;
		END

DELIMITER $$
		DROP PROCEDURE IF EXISTS `deleteInvoiceReminder`$$
		CREATE PROCEDURE `deleteInvoiceReminder`(
			IN invRemIdParam INT,
		    OUT isexecutedOut INT
		)
		BEGIN
			BEGIN
			DECLARE EXIT HANDLER FOR SQLEXCEPTION
				BEGIN
					ROLLBACK;
					GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
					@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
					SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
					SELECT @full_error; 
					SET isexecutedOut = 0;
				END;
		       
				START TRANSACTION;
				BEGIN
					UPDATE invoice_reminders SET isdeleted = 1 WHERE inv_rem_id = invRemIdParam;
				END;
				COMMIT;
			SET isexecutedOut = 1;
			END;
		END;
UPDATE `elixiatech`.`dbpatches` SET isapplied = 1 WHERE patchid = 1;
