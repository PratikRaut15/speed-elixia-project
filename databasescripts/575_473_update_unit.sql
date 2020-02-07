INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES (575, NOW(), 'Manasvi Thakur','Update unit table for n1, n2 for cust 473');

UPDATE  unit SET n1=41, n2 =42 WHERE unitno = '1826010020490' AND customerNo = '473'; 
UPDATE  unit SET n1=41 WHERE unitno = '1827010020605' AND customerNo = '473';


UPDATE dbpatches SET isapplied=1 WHERE patchid = 575;
