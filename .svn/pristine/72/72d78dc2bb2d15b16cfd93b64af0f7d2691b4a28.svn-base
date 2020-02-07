DELIMITER $$
DROP PROCEDURE IF EXISTS `get_invoices`$$
CREATE PROCEDURE get_invoices(
	IN ledgerIdParam INT
	,IN customernoParam INT
)
BEGIN
	IF(ledgerIdParam = '' OR ledgerIdParam = 0) THEN
		SET ledgerIdParam = NULL;
	END IF;

	IF(customernoParam = '' OR customernoParam = 0) THEN
		SET customernoParam = NULL;
	END IF;

	SELECT  i.invoiceid
                ,i.invoiceno
                ,DATE_FORMAT(i.inv_date,'%d-%m-%Y') AS `inv_date`
                ,i.inv_amt
                ,DATE_FORMAT(i.`timestamp`,'%d-%m-%Y %H:%i') AS `timestamp`
	FROM    invoice i
	WHERE   CASE
            	WHEN ((ledgerIdParam IS NOT NULL) AND (customernoParam IS NULL))
                	THEN i.ledgerid = ledgerIdParam 
                        ELSE i.customerno = customernoParam END
            AND i.isdeleted = 0
	ORDER BY i.invoiceid DESC;

END$$
DELIMITER ;

-- CALL get_clients('','483');