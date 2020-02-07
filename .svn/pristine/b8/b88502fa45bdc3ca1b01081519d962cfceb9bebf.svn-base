
INSERT INTO `speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'454', '2017-02-03 11:09:00', 'Arvind Thakur', 'Add is_toggle_panic in unit table', '0'
);


ALTER TABLE unit ADD is_toggle_panic TINYINT NOT NULL DEFAULT '0' AFTER unitcost;


UPDATE  dbpatches
SET     patchdate = '2017-02-03 11:09:00'
    , isapplied =1
WHERE   patchid = 454;





