SET @patchId = 742;
SET @patchDate = '2019-12-26 11:30:00';
SET @patchOwner = 'Amit Tiwari';
SET @patchDescription = 'For Credit Note & payment collection';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');


CREATE TABLE credit_note (
    credit_note_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    credit_note_no VARCHAR(50) NOT NULL,
    ledgerid INT(11) NOT NULL,
    customerno INT(11) NOT NULL,
    invoiceno INT(11) NOT NULL,
    invoice_amount float DEFAULT NULL,
    credit_amount float DEFAULT NULL,
    reason VARCHAR(255) DEFAULT NULL,
    status ENUM('requested','approved','reject') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
    invoice_date DATETIME DEFAULT NULL,
    requested_date DATETIME DEFAULT NULL,
    approved_date DATETIME DEFAULT NULL,
    created_by INT(11) DEFAULT NULL,
    updated_by INT(11) DEFAULT NULL,
    updated_on DATETIME DEFAULT NULL
);

CREATE TABLE invoice_payment_mapping (
    ip_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customerno INT(11) NOT NULL,
    invoiceid INT(11) DEFAULT NULL,
    pay_mode ENUM('cheque','cash','online') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
    paid_amt float(8,2) DEFAULT 0.00,
    paymentdate DATE DEFAULT NULL,
    tds_amt float(8,2) DEFAULT 0.00,
    cheque_no INT(6) DEFAULT NULL,
    cheque_date DATE DEFAULT NULL,
    bank_name varchar(255) NOT NULL,
    bank_branch varchar(50) NOT NULL,
    bad_debts float(8,2) DEFAULT 0.00,
    cheque_status ENUM('received','deposited','cleared') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
    status ENUM('collected','received','realized','rejected') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
    teamid INT(11) DEFAULT NULL,
    remark varchar(255) NOT NULL,
    approved_date DATETIME DEFAULT NULL,
    created_by INT(11) DEFAULT NULL,
    updated_by INT(11) DEFAULT NULL,
    created_on DATETIME DEFAULT NULL,
    updated_on DATETIME DEFAULT NULL,
    isdeleted TINYINT(4) DEFAULT 0
);


DELIMITER $$
DROP TRIGGER IF EXISTS `before_creditnote_insert`$$
CREATE TRIGGER before_creditnote_insert
BEFORE INSERT
ON credit_note FOR EACH ROW
BEGIN

 DECLARE selectid INT DEFAULT 0;
     SET selectid := (select CONVERT(SUBSTRING_INDEX(credit_note_id,'O',-1),UNSIGNED INTEGER) AS num  from credit_note order by credit_note_id desc limit 1);
     SET selectid := selectid + 1;
     SET NEW.credit_note_no = CONCAT('CRN',selectid);
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `get_collectedBy`$$
CREATE PROCEDURE `get_collectedBy`(
IN term VARCHAR(100)
)
BEGIN

  SELECT teamid,rid,name FROM team 
  WHERE (name LIKE CONCAT('%', term, '%'))
  ORDER BY teamid ASC ;

END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `get_payment_collection`$$
CREATE PROCEDURE `get_payment_collection`(
IN ip_idParam INT)
BEGIN
    SELECT ip.*,invoice.invoiceno,team.name,c.customercompany
    FROM invoice_payment_mapping ip
    LEFT JOIN invoice on ip.invoiceid=invoice.invoiceid
    LEFT JOIN team on ip.teamid=team.teamid
    LEFT JOIN customer c on c.customerno=ip.customerno
    WHERE ip.ip_id = ip_idParam AND ip.isdeleted=0;

END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `insert_payment_collection`$$
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


DELIMITER $$
DROP procedure IF EXISTS `insert_credit_note`$$
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
      customerno=ledgeridParam,
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


DELIMITER $$
DROP procedure IF EXISTS `update_payment_collection`$$
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

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_invoices`$$
CREATE PROCEDURE get_invoices(
    IN ledgerIdParam INT
    ,IN invoiceIdParam INT
)
BEGIN
    IF(ledgerIdParam = '' OR ledgerIdParam = 0) THEN
        SET ledgerIdParam = NULL;
    END IF;

    IF(invoiceIdParam = '' OR invoiceIdParam = 0) THEN
        SET invoiceIdParam = NULL;
    END IF;

    SELECT  i.invoiceid
                ,i.invoiceno
                ,DATE_FORMAT(i.inv_date,'%d-%m-%Y') AS `inv_date`
                ,i.inv_amt
                ,DATE_FORMAT(i.`timestamp`,'%d-%m-%Y %H:%i') AS `timestamp`
    FROM    invoice i
    WHERE   CASE
                WHEN ((ledgerIdParam IS NOT NULL) AND (invoiceIdParam IS NULL))
                    THEN i.ledgerid = ledgerIdParam 
                        ELSE i.invoiceid = invoiceIdParam END
            AND i.isdeleted = 0
    ORDER BY i.invoiceid DESC;

END$$
DELIMITER ;

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;