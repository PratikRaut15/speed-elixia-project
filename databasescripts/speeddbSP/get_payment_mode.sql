USE `speed`;
DROP procedure IF EXISTS `get_payment_mode`;

DELIMITER $$
USE `speed`$$
CREATE  PROCEDURE `get_payment_mode`()
BEGIN
SELECT * from payment_mode;

END$$

DELIMITER ;