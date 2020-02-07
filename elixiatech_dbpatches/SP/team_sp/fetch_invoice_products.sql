USE `elixiatech`;
DROP procedure IF EXISTS `fetch_invoice_products`;
DELIMITER $$
USE `elixiatech`$$
CREATE PROCEDURE `fetch_invoice_products`()
BEGIN

	SELECT prod_id,prod_name FROM  invoice_products;
END$$
DELIMITER ;