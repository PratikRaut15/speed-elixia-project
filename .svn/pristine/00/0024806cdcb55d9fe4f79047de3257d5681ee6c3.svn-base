DROP procedure IF EXISTS `create_CreditNote_AND_PaymentCollection_table`;

DELIMITER $$
CREATE PROCEDURE `create_CreditNote_AND_PaymentCollection_table`()
BEGIN

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
)

CREATE TABLE invoice_payment_mapping (
	ip_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
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
)


END$$

DELIMITER ;