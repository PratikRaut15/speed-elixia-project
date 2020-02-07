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