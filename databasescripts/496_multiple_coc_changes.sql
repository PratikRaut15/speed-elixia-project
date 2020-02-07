INSERT INTO `speed`.`dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`, `isapplied`) VALUES ('496', '2017-04-21 13:07:19', 'Shrikant Suryawanshi', 'Multiple COC Changes', '0');


ALTER TABLE `tripdetails` CHANGE `triplogno` `triplogno` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;



UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 496;
