INSERT INTO dbpatches (patchid, patchdate, appliedby, patchdesc, isapplied)
VALUES ('572', '2018-07-10 11:00:00', 'Sanjeet Shukla', 'Assigned Heirarchy Id to Sr for 193', '0');


UPDATE `user` SET `heirarchy_id` = '8150' WHERE `user`.`userid` = '8019';

UPDATE `user` SET `heirarchy_id` = '8153' WHERE `user`.`userid` = '8020';

UPDATE `user` SET `heirarchy_id` = '8151' WHERE `user`.`userid` = '8021';

UPDATE `user` SET `heirarchy_id` = '8150' WHERE `user`.`userid` = '8022';

UPDATE `user` SET `heirarchy_id` = '8152' WHERE `user`.`userid` = '8085';

UPDATE `user` SET `heirarchy_id` = '8154' WHERE `user`.`userid` = '8002';

UPDATE `user` SET `heirarchy_id` = '8155' WHERE `user`.`userid` = '8003';


UPDATE dbpatches SET isapplied=1 WHERE patchid = 572;

