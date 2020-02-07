INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'485', '2017-03-21 16:45:00', 'Ganesh Papde', 'alter consinee or consignor table or add data', '0'
);


ALTER TABLE `tripconsignee`  ADD `checkpointid` INT(11) NOT NULL  AFTER `phone`;
ALTER TABLE `tripconsignor`  ADD `checkpointid` INT(11) NOT NULL  AFTER `phone`;

INSERT INTO tripconsignor (consignorname,email,phone,checkpointid,customerno)
SELECT cname,email,phoneno,checkpointid,customerno from checkpoint where customerno=328 AND isdeleted=0; 

INSERT INTO tripconsignee (consigneename,email,phone,checkpointid,customerno)
SELECT cname,email,phoneno,checkpointid,customerno from checkpoint where customerno=328 AND isdeleted=0; 






UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied =1
WHERE   patchid = 485;
