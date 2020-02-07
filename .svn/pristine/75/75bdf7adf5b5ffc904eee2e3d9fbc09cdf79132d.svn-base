INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES (576, NOW(), 'Manasvi Thakur','Update unit table for n1, n2 for cust 473');

UPDATE  unit SET n1=41, n2 =42 WHERE unitno = '1826010020581' AND customerNo = '473'; 
UPDATE  unit SET n1=41, n2 =42 WHERE unitno = '1826010020524' AND customerNo = '473';
UPDATE  unit SET n1=41 WHERE unitno = '1826010020482' AND customerNo = '473';


UPDATE dbpatches SET isapplied=1 WHERE patchid = 576;
