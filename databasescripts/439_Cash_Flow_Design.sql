
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'439', '2016-12-22 17:28:54', 'Shrikant Suryawanshi', 'Cash Flow Design', '0'
);



/*Cash Flow Design */

CREATE TABLE category(
categoryid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
category VARCHAR(20),
created_by int NOT NULL,
created_on datetime NOT NULL,
updated_by int NOT NULL,
updated_on datetime NOT NULL,
isdeleted tinyint(1) DEFAULT 0
);

CREATE TABLE bank_statement(
statementid INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
transaction_datetime DATETIME NOT NULL,
details VARCHAR(50),
remarks VARCHAR(50),
transaction_type TINYINT(1) NOT NULL,
categoryid INT NOT NULL,
amount DECIMAL(10,2),
created_by int NOT NULL,
created_on datetime NOT NULL,
updated_by int NOT NULL,
updated_on datetime NOT NULL,
isdeleted tinyint(1) DEFAULT 0
);

ALTER TABLE `bank_statement` ADD `enteredInTally` TINYINT(1) NOT NULL DEFAULT '0' AFTER `amount`;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_category`$$
CREATE PROCEDURE `insert_category`(
    IN category VARCHAR(50)
    ,IN teamid INT
    ,IN todaydate DATETIME
    ,OUT categoryid INT
)

BEGIN
  INSERT INTO category
  (
    category
    ,created_by
    ,created_on
  )VALUES(
    category
    ,teamid
    ,todaydate
  );
SET categoryid = LAST_INSERT_ID();

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_category`$$
CREATE PROCEDURE `update_category`(
  IN categoryidParam INT
    ,IN categoryParam VARCHAR(50)
    ,IN teamid INT
    ,IN todaysdate DATETIME
)

BEGIN
  UPDATE category
  SET category = categoryParam
  , updated_by = teamid
  , updated_on = todaysdate
  WHERE categoryid = categoryidParam
  AND isdeleted = 0;


END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_category`$$
CREATE PROCEDURE `delete_category`(
  IN categoryidParam INT
    ,IN teamid INT
    ,IN todaysdate DATETIME
)

BEGIN
  UPDATE category
  SET isdeleted = 1
  , updated_by = teamid
  , updated_on = todaysdate
  WHERE categoryid = categoryidParam
  AND isdeleted = 0;


END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_bank_statement`$$
CREATE PROCEDURE `insert_bank_statement`(
    IN transaction_datetime DATETIME
    ,IN details VARCHAR(50)
  , IN remarks VARCHAR(50)
  , IN transaction_type TINYINT
  , IN categoryid INT
  , IN amount DECIMAL(10,2)
  , IN teamid INT
    ,IN todaysdate DATETIME
    ,OUT statementid INT
)

BEGIN
  INSERT INTO bank_statement
  (
    transaction_datetime
    ,details
    ,remarks
    ,transaction_type
    ,categoryid
    ,amount
    ,created_by
    ,created_on
  )VALUES(
    transaction_datetime
    ,details
    ,remarks
    ,transaction_type
    ,categoryid
    ,amount
    ,teamid
    ,todaysdate
  );
SET statementid = LAST_INSERT_ID();

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `update_bank_statement`$$
CREATE PROCEDURE `update_bank_statement`(
  IN statementidParam INT
    , IN transaction_datetimeParam DATETIME
    , IN detailsParam VARCHAR(50)
  , IN remarksParam VARCHAR(50)
  , IN transaction_typeParam TINYINT
  , IN categoryidParam INT
  , IN amountParam DECIMAL(10,2)
  , IN teamid INT
    , IN todaysdate DATETIME
)

BEGIN

  UPDATE bank_statement

  SET transaction_datetime = transaction_datetimeParam
    ,details = detailsParam
    ,remarks = remarksParam
    ,transaction_type = transaction_typeParam
    ,categoryid = categoryidParam
    ,amount = amountParam
    ,updated_by = teamid
    ,updated_on= todaysdate
  WHERE statementid = statementidParam
  AND isdeleted = 0;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_bank_statement`$$
CREATE PROCEDURE `delete_bank_statement`(
  IN statementidParam INT
    , IN teamid INT
    , IN todaysdate DATETIME
)

BEGIN

  UPDATE bank_statement

  SET isdeleted = 1
    ,updated_by = teamid
    ,updated_on= todaysdate
  WHERE statementid = statementidParam
  AND isdeleted = 0;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_category`$$
CREATE PROCEDURE `get_category`(
  IN categoryidParam INT
)

BEGIN

  IF(categoryidParam = '' OR categoryidParam = 0) THEN
    SET categoryidParam = categoryidParam = NULL;
  END IF;

  SELECT categoryid
  ,category
  ,created_by
  ,created_on
  ,updated_by
  ,updated_on
  FROM category
  WHERE (categoryid = categoryidParam OR categoryidParam IS NULL)
  AND isdeleted = 0
  ORDER BY categoryid ASC;


END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE  IF EXISTS`get_bank_statement`$$
CREATE PROCEDURE `get_bank_statement`(
  IN statementidParam INT
  , IN transaction_datetime_from date
  , IN transaction_datetime_to date
  , IN transaction_typeParam INT
  , IN categoryidParam INT
)

BEGIN

  IF(statementidParam = '' OR statementidParam = 0) THEN
    SET statementidParam =  NULL;
  END IF;
  IF(transaction_typeParam = '' OR transaction_typeParam = 0) THEN
    SET transaction_typeParam  = NULL;
  END IF;
  IF(categoryidParam = '' OR categoryidParam = 0) THEN
    SET categoryidParam =  NULL;
  END IF;
  IF(transaction_datetime_from = '' OR transaction_datetime_from = '0000-00-00') THEN
    SET transaction_datetime_from =  NULL;
  END IF;
  IF(transaction_datetime_to = '' OR transaction_datetime_to = '0000-00-00') THEN
    SET transaction_datetime_to =  NULL;
  END IF;

  SELECT statementid
  ,transaction_datetime
  ,details
  ,remarks
  ,transaction_type
  ,bank_statement.categoryid
  ,category
  ,amount
  ,enteredInTally
  ,bank_statement.created_by
  ,bank_statement.created_on
  ,bank_statement.updated_by
  ,bank_statement.updated_on
  FROM bank_statement
  INNER JOIN category on category.categoryid = bank_statement.categoryid
  WHERE (statementid = statementidParam OR statementidParam IS NULL)
  AND (transaction_type = transaction_typeParam OR transaction_typeParam IS NULL)
  AND (bank_statement.categoryid = categoryidParam OR categoryidParam IS NULL)
  AND (DATE(transaction_datetime) BETWEEN transaction_datetime_from AND transaction_datetime_to OR transaction_datetime_from IS NULL AND transaction_datetime_to IS NULL )
  AND bank_statement.isdeleted = 0
  AND category.isdeleted = 0
  ORDER BY statementid DESC;


END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE  IF EXISTS`get_bank_statement_summary`$$
CREATE PROCEDURE `get_bank_statement_summary`(
  IN transaction_datetime_from date
  , IN transaction_datetime_to date
  , IN categoryidParam INT
)

BEGIN


  IF(categoryidParam = '' OR categoryidParam = 0) THEN
    SET categoryidParam =  NULL;
  END IF;
  IF(transaction_datetime_from = '' OR transaction_datetime_from = '0000-00-00') THEN
    SET transaction_datetime_from =  NULL;
  END IF;
  IF(transaction_datetime_to = '' OR transaction_datetime_to = '0000-00-00') THEN
    SET transaction_datetime_to =  NULL;
  END IF;

  SELECT
  MONTH(transaction_datetime) as transaction_month

  ,sum(amount) as transaction_amount
  FROM bank_statement bs
  INNER JOIN category c on c.categoryid = bs.categoryid

  AND (bs.categoryid = categoryidParam OR categoryidParam IS NULL)
  AND (DATE(transaction_datetime) BETWEEN transaction_datetime_from AND transaction_datetime_to OR transaction_datetime_from IS NULL AND transaction_datetime_to IS NULL )
  AND bs.isdeleted = 0
  AND c.isdeleted = 0
  GROUP BY  MONTH(transaction_datetime) ;


END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `add_bank_statement_to_tally`$$
CREATE PROCEDURE `add_bank_statement_to_tally`(
  IN statementidParam INT
    , IN teamid INT
    , IN todaysdate DATETIME
)

BEGIN

  UPDATE bank_statement

  SET enteredInTally = 1
    ,updated_by = teamid
    ,updated_on= todaysdate
  WHERE statementid = statementidParam
  AND isdeleted = 0;

END$$
DELIMITER ;



UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 439;
