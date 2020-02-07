
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'448', '2017-01-14 19:09:40', 'Shrikant Suryawanshi', 'Team Changes - Cash Flow', '0'
);


DELIMITER $$
DROP PROCEDURE IF EXISTS get_profit_loss_analysis$$
CREATE PROCEDURE `get_profit_loss_analysis`(
IN custNo INT
, IN todaysdate DATE
)
BEGIN

  DECLARE totalUnitCount INT;
  DECLARE totalUnitCost INT;
  DECLARE totalSimcardCount INT;
  DECLARE totalSimcardCost INT;
  DECLARE totalPaymentCollected INT;
  DECLARE totalExpense INT;
  DECLARE plDiff INT;
  DECLARE plStatus VARCHAR(20);

  SELECT SUM(unitcost), count(unit.uid)
  INTO totalUnitCost, totalUnitCount
  FROM unit
  INNER JOIN devices on devices.uid = unit.uid
  WHERE unit.customerno = custNo
  AND devices.simcardid != 0
  AND unit.trans_statusid NOT IN(10,22);

  select count(simcardid) as simcard,
  SUM((period_diff(date_format(todaysdate, '%Y%m'), date_format(installdate, '%Y%m'))) * 50) as exp
  INTO totalSimcardCount, totalSimcardCost
  FROM devices
  INNER JOIN unit on unit.uid = devices.uid
  WHERE devices.customerno = custNo
  AND devices.simcardid != 0
  AND unit.trans_statusid NOT IN(10,22);


  SELECT SUM(inv_amt) INTO totalPaymentCollected
  FROM invoice
  WHERE customerno = custNo
  AND  LOWER(status) = 'paid'
  AND isdeleted = 0;

  SET totalExpense = (totalUnitCost + totalSimcardCost);
  SET plDiff = (totalPaymentCollected - totalExpense);

  IF(plDiff > 0) THEN
    SET plStatus = "Profit";
  ELSE
    SET plStatus = "Loss";
  END IF;

  SELECT totalUnitCount, totalUnitCost, totalSimcardCount, totalSimcardCost, totalPaymentCollected, totalExpense, plDiff, plStatus;

END$$
DELIMITER ;




UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 448;
