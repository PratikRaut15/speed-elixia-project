INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('599', '2018-08-22 15:00:00', 'Sanjeet Shukla', 'Username update query', '0');

UPDATE `user` SET `username`='durgesh2@derbyindia.in' WHERE `userid`='4377';

UPDATE dbpatches SET isapplied = 1, patchdate = '2018-08-22 15:00:00' where patchid = 599;
