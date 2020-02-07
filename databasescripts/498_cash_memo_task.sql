INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('498', '2017-04-19 17:29:00', 'Arvind Thakur', 'cash memo changes', '0');


CREATE TABLE IF NOT EXISTS `cash_memo_desc` (
	`id` INT(11) PRIMARY KEY AUTO_INCREMENT,
	`desc` VARCHAR(200),
	`quantity` INT(11),
	`rate` float,
	`cmid` INT(11),
	`created_by` INT(11),
	`created_on` DATETIME,
        `approved` TINYINT(1), 
	`isdeleted` TINYINT(1) DEFAULT 0
);

ALTER TABLE `cash_memo` 
ADD TABLE `approved` TINYINT(1) DEFAULT 0;


INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES9701546A', '97','2014-11-01', '151200', '2', '0', '151200', '0000-00-00', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES9703256A', '97','2015-01-13', '303200', '2', '0', '303200', '0000-00-00', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES9704', '97',2015-04-17, '96600', '2', '0', '96600', '2015-05-04', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES970161019A', '97','2015-05-01', '162750', '2', '0', '162750', '2015-05-04', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES97161490A', '97','2015-08-18', '97650', '2', '0', '97650', '2015-08-24', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES97171491', '97','2015-08-18', '69000', '2', '0', '69000', '2015-08-24', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES97182216A', '97','2016-06-01', '192200', '2', '0', '192200', '0000-00-00', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES97192217', '97','2016-06-01', '198030', '2', '0', '198030', '2016-03-10', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES97202219A', '97','2016-05-16', '117600', '2', '0', '117600', '2016-06-08', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('ES97212220A', '97','2016-05-16', '98400', '2', '0', '98400', '2016-06-08', '0', '0');



INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C17001', '170','2017-03-01', '14250', '1', '14250', '0', '', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C17002', '170','2017-03-01', '14250', '1', '14250', '0', '', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C31301', '313','2016-09-01', '1800', '2', '0', '1800', '2016-12-06', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C31302', '313','2016-10-01', '1800', '2', '0', '1800', '2016-12-06', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C31303', '313','2016-11-01', '1800', '2', '0', '1800', '2016-12-06', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C31304', '313','2016-12-01', '1800', '2', '0', '1800', '2017-01-06', '0', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C31305', '313','2017-01-01', '1800', '1', '1800', '0', '', '1', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C31306', '313','2017-02-01', '1800', '1', '1800', '0', '', '1', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C313011', '313','2017-03-01', '1800', '1', '1800', '0', '', '1', '0');

INSERT INTO `speed`.`cash_memo` (`cash_memo_no`, `customerno`, `cm_date`, `cm_amount`, `status`, `pending_amt`, `paid_amount`, `paymentdate`, `isdeleted`, `product_id`) VALUES ('C313012', '313','2017-04-01', '1800', '1', '1800', '0', '', '1', '0');


UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 498;