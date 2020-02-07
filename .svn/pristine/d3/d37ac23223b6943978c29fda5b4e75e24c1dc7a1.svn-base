SET @patchId = 744;
SET @patchDate = '2019-01-03 12:10:00';
SET @patchOwner = 'Amit Tiwari';
SET @patchDescription = 'replaced invoiceid with customerno in where condition';


INSERT INTO dbpatches(patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES (@patchId, @patchDate, @patchOwner, @patchDescription, '0');

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

UPDATE dbpatches SET isapplied = 1, updatedOn =  DATE_ADD(NOW( ) , INTERVAL '05:30' HOUR_MINUTE) WHERE patchid = @patchId;