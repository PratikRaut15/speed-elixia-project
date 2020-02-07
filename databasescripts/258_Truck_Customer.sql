-- Insert SQL here.

update vehicle SET kind="Truck" where customerno IN(8,10,12,14,15,17,18,19,20,21,14,25,28,30,31,35,36,37,39,41,42,48,49,50,52,55,56,57,58,59,65,66,68,69,70,71,72,73,74,76,80,81,82,87,88,90,91,92,93,94,95,96,97,100,101,103,104,105,106,108,110,111,114,115,119,121,122,123,129,132,133,135,136,138,139,141,142,146,147,148,149,154,155,156,158,159,166,167);


-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 258, NOW(), 'Shrikanth Suryawanshi','Customer with Trucks');
