INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES (578, NOW(), 'Manasvi Thakur','Update unit table for n1, n2 for cust 473');

UPDATE  unit
 SET n1=0, n2 =0 
WHERE unitno 
IN ('1826010020524' ,'1826010020482','1826010020581')  AND customerNo = '473'; 


UPDATE dbpatches SET isapplied=1 WHERE patchid = 578;

