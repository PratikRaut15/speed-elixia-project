DELIMITER $$
DROP PROCEDURE IF EXISTS get_profit_loss_analysis$$
CREATE PROCEDURE `get_profit_loss_analysis`(
IN custNo INT
, IN todaysdate DATE
)
BEGIN

  DECLARE totalUnitCount DECIMAL(11,2);
  DECLARE totalUnitCost DECIMAL(11,2);
  DECLARE totalSimcardCount DECIMAL(11,2);
  DECLARE totalSimcardCost DECIMAL(11,2);
  DECLARE totalPaymentCollected DECIMAL(11,2);
  DECLARE totalPaymentDue DECIMAL(11,2);
  DECLARE totalSimcardInventory DECIMAL(11,2);
  DECLARE totalClosingStockCost DECIMAL(11,2);
  DECLARE totalMaterialCost DECIMAL(11,2);
  DECLARE totalOtherCost DECIMAL(11,2);
  DECLARE totalSalary DECIMAL(11,2);
  DECLARE totalOtherIndirectExp DECIMAL(11,2);
  DECLARE totalExpense DECIMAL(11,2);
  DECLARE plDiff DECIMAL(11,2);
  DECLARE plStatus VARCHAR(20);
  DECLARE varSimcardInventoryDBConstant DECIMAL(11,2);
  DECLARE varSimcardInventory DECIMAL(11,2);

  DECLARE varTotalUnits INT;
  DECLARE varTotalSimcards INT;

  DECLARE varTotalSimcardCost DECIMAL(11,2);

  DECLARE varSimcardTotalCost DECIMAL(11,2);
  DECLARE varPerSimcardCost DECIMAL(11,2);

  DECLARE varPerUnitCost DECIMAL(11,2);

  DECLARE varSalaryPerUnit DECIMAL(11,2);
  DECLARE varIndExpPerUnit DECIMAL(11,2);

  DECLARE varSimcardInventoryFixedConstant INT;
  DECLARE varClosingStockFixedConstant INT;
  DECLARE varSalaryConstantFixedConstant INT;
  DECLARE otherIndirectExpFixedConstant INT;

  DECLARE varCustomerNo INT;
  DECLARE varCustomerName VARCHAR(50);
  DECLARE varCustomerCompany VARCHAR(150);
  DECLARE varCustomerProfit DECIMAL(11,2);

  DECLARE varIndirectExp_13_14  DECIMAL(11,2);
  DECLARE varIndirectExp_14_15  DECIMAL(11,2);
  DECLARE varIndirectExp_15_16  DECIMAL(11,2);
  DECLARE varIndirectExp_16_17  DECIMAL(11,2);

  DECLARE varTotalIndirectExp_13_14  DECIMAL(11,2);
  DECLARE varTotalIndirectExp_14_15  DECIMAL(11,2);
  DECLARE varTotalIndirectExp_15_16  DECIMAL(11,2);
  DECLARE varTotalIndirectExp_16_17  DECIMAL(11,2);


  SET totalMaterialCost = 0;
  SET totalOtherCost = 0;



  SET varSimcardInventoryFixedConstant = 1477000;
  SET varClosingStockFixedConstant = 13364457;
  SET varSalaryConstantFixedConstant = 10203589;
  SET otherIndirectExpFixedConstant = 29877772;

  SET varIndirectExp_13_14 = 0;
  SET varIndirectExp_14_15 = 0;
  SET varIndirectExp_15_16 = 0;
  SET varIndirectExp_16_17 = 0;



  SELECT customerno,customername,customercompany
  INTO varCustomerNo,varCustomerName,varCustomerCompany
  FROM customer
  WHERE customerno = custNo;


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

  SELECT SUM(inv_amt) INTO totalPaymentDue
  FROM invoice
  WHERE customerno = custNo
  AND  LOWER(status) = 'Pending'
  AND isdeleted = 0;



  SELECT count(unit.uid)
  INTO varTotalUnits
  FROM unit
  INNER JOIN devices on devices.uid = unit.uid
  INNER JOIN customer on customer.customerno = unit.customerno
  WHERE customer.renewal NOT IN(-1,-2);


  SELECT count(simcardid) ,
  SUM((period_diff(date_format(todaysdate, '%Y%m'), date_format(installdate, '%Y%m'))) * 50) as exp
  INTO varTotalSimcards ,varTotalSimcardCost
  FROM devices
  INNER JOIN unit on unit.uid = devices.uid
  INNER JOIN customer on customer.customerno = devices.customerno
  INNER JOIN vehicle on vehicle.uid = unit.uid
  WHERE customer.renewal NOT IN(-1,-2)
  AND unit.trans_statusid NOT IN(10,22)
  AND devices.simcardid != 0
  AND vehicle.isdeleted = 0
  AND installdate!='0000-00-00'
  AND installdate !='1970-01-01';

  SET varTotalIndirectExp_13_14 = (varIndirectExp_13_14/varTotalUnits);
  SET varTotalIndirectExp_14_15 = (varIndirectExp_14_15/varTotalUnits);
  SET varTotalIndirectExp_15_16 = (varIndirectExp_15_16/varTotalUnits);
  SET varTotalIndirectExp_16_17 = (varIndirectExp_16_17/varTotalUnits);


  SET varIndExpPerUnit = (otherIndirectExpFixedConstant / varTotalUnits);
  SET totalOtherIndirectExp = (varIndExpPerUnit * totalUnitCount);

  SET totalExpense = (totalUnitCost + totalSimcardCost +  totalMaterialCost + totalOtherCost  + totalOtherIndirectExp + varTotalIndirectExp_13_14+varTotalIndirectExp_14_15+varTotalIndirectExp_15_16+varTotalIndirectExp_16_17);
  SET plDiff = (totalPaymentCollected - totalExpense);
   IF(totalPaymentDue IS NULL) THEN
  SET totalPaymentDue = 0;
   END IF;

  SET varCustomerProfit = (plDiff / (totalPaymentCollected + totalPaymentDue)) * 100;

  IF(plDiff > 0) THEN
    SET plStatus = "Profit";
  ELSE
    SET plStatus = "Loss";
  END IF;

  SELECT
  varCustomerNo
  ,varCustomerName
  ,varCustomerCompany
    ,COALESCE(totalUnitCount,0) as totalUnitCount
    ,COALESCE(totalUnitCost,0) as totalUnitCost
    ,COALESCE(totalSimcardCount,0) as totalSimcardCount
    ,COALESCE(totalSimcardCost,0) as totalSimcardCost
    ,COALESCE(totalPaymentCollected,0) as totalPaymentCollected
    ,COALESCE(totalPaymentDue,0) as totalPaymentDue
    ,COALESCE(varTotalUnits,0) as varTotalUnits
    ,COALESCE(varTotalSimcards,0) as varTotalSimcards
    ,COALESCE(totalMaterialCost,0) as totalMaterialCost
    ,COALESCE(totalOtherCost,0) as totalOtherCost
    ,COALESCE(totalOtherIndirectExp,0) as totalOtherIndirectExp
    ,COALESCE(totalExpense,0) as  totalExpense
    ,COALESCE(varTotalIndirectExp_13_14,0) as  varTotalIndirectExp_13_14
    ,COALESCE(varTotalIndirectExp_14_15,0) as  varTotalIndirectExp_14_15
    ,COALESCE(varTotalIndirectExp_15_16,0) as  varTotalIndirectExp_15_16
    ,COALESCE(varTotalIndirectExp_16_17,0) as  varTotalIndirectExp_16_17
    ,plDiff
   ,COALESCE(varCustomerProfit,0) as varCustomerProfit
    ,plStatus;

END$$
DELIMITER ;



CALL get_profit_loss_analysis('6','2017-03-21')
