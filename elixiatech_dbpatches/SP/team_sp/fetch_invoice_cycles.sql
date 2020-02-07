USE `elixiatech`;
DROP procedure IF EXISTS `fetch_invoice_cycles`;
DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `fetch_invoice_cycles`()
BEGIN

	select cycle_id, cycle_name from invoice_cycles WHERE isdeleted = 0;
END$$
DELIMITER ;