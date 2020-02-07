UPDATE `maintenance` SET roleid =18 WHERE customerno = 118 AND category IN (0,1) AND amount_quote > 2500; 
UPDATE `maintenance_history` SET roleid =18 WHERE customerno = 118 AND category IN (0,1) AND amount_quote > 2500;    
UPDATE `maintenance` SET roleid =19 WHERE customerno = 118 AND category IN (0,1) AND amount_quote <= 2500; 
UPDATE `maintenance_history` SET roleid =19 WHERE customerno = 118 AND category IN (0,1) AND amount_quote <= 2500; 

INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
VALUES (357, NOW(), 'Sahil','change in role id for trigon based on quotation amt for tyre and battery transactions');
