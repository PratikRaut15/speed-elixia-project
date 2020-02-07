USE `speed`;
DROP procedure IF EXISTS `delete_payment_collection`;
DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `delete_payment_collection`(
  IN paymentidParam INT
    ,IN teamidParam INT
    ,IN todaysdateParam DATETIME,
    OUT isExecutedOutParam INT
)
BEGIN
SET isExecutedOutParam = 0;

  UPDATE invoice_payment_mapping
  SET isdeleted = 1
  , updated_by = teamidParam
  , updated_on = todaysdateParam
  WHERE ip_id = paymentidParam
  AND isdeleted = 0;
  
  SET isExecutedOutParam = 1;
  
END$$
DELIMITER ;