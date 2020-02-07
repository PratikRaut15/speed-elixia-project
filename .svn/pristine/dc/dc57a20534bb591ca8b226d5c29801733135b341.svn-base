/* Alter tables */
ALTER TABLE `insurance`  ADD `policy_no` VARCHAR(30) NOT NULL  AFTER `ncb`;

ALTER TABLE `insurance`  ADD `ins_dealerid` INT(11) NOT NULL  AFTER `policy_no`;

ALTER TABLE `loan`  ADD `emidate` DATE NOT NULL  AFTER `emiamt`;

ALTER TABLE `loan`  ADD `loan_accountno` VARCHAR(30) NOT NULL  AFTER `loanamt`;

/* New table */

DROP TABLE IF EXISTS `insurance_dealer`;
CREATE TABLE IF NOT EXISTS `insurance_dealer` (
`ins_dealerid` int(11) NOT NULL,
  `ins_dealername` varchar(50) NOT NULL,
  `customerno` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createdon` datetime NOT NULL,
  `updatedby` int(11) NOT NULL,
  `updatedon` datetime NOT NULL,
  `isdeleted` tinyint(1) NOT NULL
);

ALTER TABLE `insurance_dealer`
 ADD PRIMARY KEY (`ins_dealerid`);

ALTER TABLE `insurance_dealer`
MODIFY `ins_dealerid` int(11) NOT NULL AUTO_INCREMENT;

-- Successful. Add the Patch to the Applied Patches table.

UPDATE     dbpatches 
SET     patchdate = NOW()
        , isapplied =1 
WHERE     patchid = 373;
