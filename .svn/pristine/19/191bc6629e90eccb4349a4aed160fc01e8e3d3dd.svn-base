INSERT INTO dbpatches (
patchid,
patchdate ,
appliedby ,
patchdesc ,
isapplied
)
VALUES (
443, NOW(), 'Mrudang Vora', 'Alter User - Add 2 mobile no fields', '0'
);

ALTER TABLE `user` ADD `mobile1` VARCHAR(15) NOT NULL DEFAULT '' AFTER `phone`, ADD `mobile2` VARCHAR(15) NOT NULL DEFAULT '' AFTER `mobile1`;

UPDATE  dbpatches
SET     patchdate = NOW()
    , isapplied =1
WHERE   patchid = 443;
