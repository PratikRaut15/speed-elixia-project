
    INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`
    )
    VALUES (
    '432', NOW(), 'Shrikant Surywanshi', 'Add Extra Columns For Trigon Transit InDealer Table ', '0'
    );


    ALTER TABLE `dealer` ADD `contact_person` VARCHAR(50) NOT NULL DEFAULT '0' AFTER `name`;
    ALTER TABLE `dealer` ADD `location` VARCHAR(150) NOT NULL DEFAULT '0' AFTER `address`;
    ALTER TABLE `dealer` ADD `pan_no` VARCHAR(25) NOT NULL DEFAULT '0' AFTER `cellphone`;
    ALTER TABLE `dealer` ADD `cst_tin_no` VARCHAR(25) NOT NULL DEFAULT '0' AFTER `pan_no`;
    ALTER TABLE `dealer` ADD `vat_tin_no` VARCHAR(25) NOT NULL DEFAULT '0' AFTER `cst_tin_no`;
    ALTER TABLE `dealer` ADD `email_id` VARCHAR(25) NOT NULL DEFAULT '0' AFTER `vat_tin_no`;


    ALTER TABLE `dealer` CHANGE `location` `location` VARCHAR( 150 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;

    ALTER TABLE `dealer` CHANGE `email_id` `email_id` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ;




    UPDATE 	dbpatches 
    SET 	patchdate = NOW()
            , isapplied =1 
    WHERE 	patchid = 432;
