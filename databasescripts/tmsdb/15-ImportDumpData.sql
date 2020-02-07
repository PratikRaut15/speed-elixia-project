# 
# Create Table Statement For bill_type
#

CREATE TABLE IF NOT EXISTS `bill_type` (
`btypeid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`type` varchar(50) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);
# 
# Create Table Statement For vehicle_type_master
#

CREATE TABLE IF NOT EXISTS `vehicle_type_master` (
`vtypeid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
`type` varchar(50) NOT NULL,
  `customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);

#
# Create Table Statement For Shipment Dump
#

CREATE TABLE IF NOT EXISTS shipment_dump(
sdid BIGINT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
delivery_no int(11) NOT NULL,
lr_no varchar(30) NOT NULL,
shipment_no varchar(30) NOT NULL,
cost_doc_no varchar(30) NOT NULL,
truck_type varchar(30) NOT NULL,
route varchar(150) NOT NULL,
vehicle_no varchar(20) NOT NULL,
`customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);

# 
# Create Table Statement For Payment Dump 
#

CREATE TABLE IF NOT EXISTS payment_dump(
pdid int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
vendor_code int(11) NOT NULL,
bill_no bigint(11) NOT NULL,
clearing_doc_no bigint(11) NOT NULL,
clearing_date date,
ref_no varchar(30) NOT NULL,
payment_status varchar(30) NOT NULL,
`customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'

);

# 
# Create table Statement For billpayable_draft
#

CREATE TABLE IF NOT EXISTS billpayable_draft(
billid int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
billtypeid int(11) NOT NULL,
invoice_location_id int(11) NOT NULL,
depot_location_id int(11) NOT NULL,
vendor_id int(11) NOT NULL,
bill_no varchar(100) NOT NULL,
bill_date date,
description text(500),
final_bill_amount decimal(11,2) NOT NULL,
bill_received_date date,
bill_processed_date date,
bill_sent_date date,
grn_no varchar(30) NOT NULL,
po_no varchar(30) NOT NULL,
bill_remarks text(500),
settelement_remarks text(500),
due_days tinyint NOT NULL,
billing_status varchar(30) NOT NULL,
due_status varchar(30) NOT NULL,
days_for_receiving_bills tinyint NOT NULL,
process_days tinyint NOT NULL,
custody tinyint NOT NULL,
total_custody tinyint NOT NULL,
payment_done tinyint NOT NULL,
month_sent varchar(10) NOT NULL,
year_sent int NOt NULL,
payment_bucket varchar(50) NOT NULL,
payment_status varchar(50) NOT NULL, 
`customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);



create table lrDetails_draft(
lrid int(11) PRIMARY KEY AUTO_INCREMENT,
bill_id int NOT NULL,
delivery_no bigint(11) NOT NULL,
lr_no varchar(100),
shipment_no varchar(30) NOT NULL,
cost_document_no varchar(30) NOT NULL,
truck_type varchar(30) NOT NULL,
route varchar(150) NOT NULL,
vehicle_no varchar(20) NOT NULL,
vehicle_type tinyint NOT NULL,
movement_type tinyint NOT NULL,
cfa_cost decimal(10,2) DEFAULT '0.0',
shipment_freight_bill decimal(10,2) DEFAULT '0.0',
loading_charges decimal(10,2) DEFAULT '0.0',
unloading_charges decimal(10,2) DEFAULT '0.0',
other_charges decimal(10,2) DEFAULT '0.0',
multidrop_charges decimal(10,2) DEFAULT '0.0',
toll_charges decimal(10,2) DEFAULT '0.0',
permit_charges decimal(10,2) DEFAULT '0.0',
charges_outword decimal(10,2) DEFAULT '0.0',
gprs decimal(10,2) DEFAULT '0.0',
noentry_charges decimal(10,2) DEFAULT '0.0',
auto_charges decimal(10,2) DEFAULT '0.0',
lr_charges decimal(10,2) DEFAULT '0.0',
tt_penalty decimal(10,2) DEFAULT '0.0',
any_deduction decimal(10,2) DEFAULT '0.0',
total_delivery_amount decimal(10,2) DEFAULT '0.0',
customerno int NOT NULL,
created_on DATETIME,
updated_on DATETIME,
created_by int NOT NULL,
updated_by int NOT NULL,
isdeleted tinyint(1) DEFAULT 0 
);


CREATE TABLE IF NOT EXISTS billpayable(
billid int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
billtypeid int(11) NOT NULL,
invoice_location_id int(11) NOT NULL,
depot_location_id int(11) NOT NULL,
vendor_id int(11) NOT NULL,
bill_no varchar(100) NOT NULL,
bill_date date,
description text(500),
final_bill_amount decimal(11,2) NOT NULL,
bill_received_date date,
bill_processed_date date,
bill_sent_date date,
grn_no varchar(30) NOT NULL,
po_no varchar(30) NOT NULL,
bill_remarks text(500),
settelement_remarks text(500),
due_days tinyint NOT NULL,
billing_status varchar(30) NOT NULL,
due_status varchar(30) NOT NULL,
days_for_receiving_bills tinyint NOT NULL,
process_days tinyint NOT NULL,
custody tinyint NOT NULL,
total_custody tinyint NOT NULL,
payment_done tinyint NOT NULL,
month_sent varchar(10) NOT NULL,
year_sent int NOt NULL,
payment_bucket varchar(50) NOT NULL,
payment_status varchar(50) NOT NULL, 
`customerno` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `isdeleted` tinyint(1) DEFAULT '0'
);


create table lrDetails(
lrid int(11) PRIMARY KEY AUTO_INCREMENT,
bill_id int NOT NULL,
delivery_no bigint(11) NOT NULL,
lr_no varchar(100),
shipment_no varchar(30) NOT NULL,
cost_document_no varchar(30) NOT NULL,
truck_type varchar(30) NOT NULL,
route varchar(150) NOT NULL,
vehicle_no varchar(20) NOT NULL,
vehicle_type tinyint NOT NULL,
movement_type tinyint NOT NULL,
cfa_cost decimal(10,2) DEFAULT '0.0',
shipment_freight_bill decimal(10,2) DEFAULT '0.0',
loading_charges decimal(10,2) DEFAULT '0.0',
unloading_charges decimal(10,2) DEFAULT '0.0',
other_charges decimal(10,2) DEFAULT '0.0',
multidrop_charges decimal(10,2) DEFAULT '0.0',
toll_charges decimal(10,2) DEFAULT '0.0',
permit_charges decimal(10,2) DEFAULT '0.0',
charges_outword decimal(10,2) DEFAULT '0.0',
gprs decimal(10,2) DEFAULT '0.0',
noentry_charges decimal(10,2) DEFAULT '0.0',
auto_charges decimal(10,2) DEFAULT '0.0',
lr_charges decimal(10,2) DEFAULT '0.0',
tt_penalty decimal(10,2) DEFAULT '0.0',
any_deduction decimal(10,2) DEFAULT '0.0',
total_delivery_amount decimal(10,2) DEFAULT '0.0',
customerno int NOT NULL,
created_on DATETIME,
updated_on DATETIME,
created_by int NOT NULL,
updated_by int NOT NULL,
isdeleted tinyint(1) DEFAULT 0 
);



create table payment_bucket(
pbid tinyint NOT NULL PRIMARY KEY AUTO_INCREMENT,
minrange tinyint NOT NULL,
maxrange tinyint NOT NULL,
customerno int NOT NULL,
created_on DATETIME,
updated_on DATETIME,
created_by int NOT NULL,
updated_by int NOT NULL,
isdeleted tinyint(1) DEFAULT 0
);

alter table lrDetails_draft ADD indentid int after vehicle_no;
alter table lrDetails ADD indentid int after vehicle_no;


INSERT INTO `bill_type` (`btypeid`, `type`, `customerno`, `created_on`, `updated_on`, `created_by`, `updated_by`, `isdeleted`) VALUES
(1, 'Primary Transportation', 116, '2016-01-11 12:10:12', '2016-01-11 12:10:12', 1074, 1074, 0),
(2, 'Secondary Transportation', 116, '2016-01-11 12:10:12', '2016-01-11 12:10:12', 1074, 1074, 0),
(3, 'Warehousing', 116, '2016-01-11 12:10:12', '2016-01-11 12:10:12', 1074, 1074, 0),
(4, 'Miscellaneous', 116, '2016-01-11 12:10:12', '2016-01-11 12:10:12', 1074, 1074, 0);


INSERT INTO `vehicle_type_master` (`vtypeid`, `type`, `customerno`, `created_on`, `updated_on`, `created_by`, `updated_by`, `isdeleted`) VALUES
(1, 'Dedicated Vehicle', 116, '2016-01-13 14:36:36', '2016-01-13 14:36:36', 1074, 1074, 0),
(2, 'Non Dedicated Vehicle', 116, '2016-01-13 14:36:36', '2016-01-13 14:36:36', 1074, 1074, 0),
(3, 'Market Vehicle', 116, '2016-01-13 14:36:36', '2016-01-13 14:36:36', 1074, 1074, 0);






DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_shipment_dump`$$
CREATE PROCEDURE `insert_shipment_dump`( 
	IN deliveryno bigint(11) 
	, IN lrno varchar(30)
	, IN shipmentno varchar(30)
    , IN costdocumentno varchar(30)
	, IN trucktype varchar(30)
    , IN route varchar(150)
    , IN vehicleno varchar(20)
	, IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	INSERT INTO `shipment_dump`(
						`delivery_no`
						, `lr_no`
						, `shipment_no`
                        , `cost_doc_no`
						, `truck_type`
                        , `route`
                        , `vehicle_no`
						, `customerno`
						, `created_on`
						, `updated_on`
						, `created_by`
						, `updated_by`) 
				VALUES (deliveryno
					, lrno
					, shipmentno
                    , costdocumentno
                    , trucktype
                    , route
                    , vehicleno
					, customerno
					, todaysdate
					, todaysdate
					, userid
					, userid
					);
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_payment_dump`$$
CREATE PROCEDURE `insert_payment_dump`( 
	IN vendorcode bigint(11) 
	, IN billno bigint(11)
	, IN clearingdocno varchar(30)
    , IN clearingdate DATE
	, IN refno varchar(30)
    , IN paymentstatus varchar(30)
    , IN customerno INT
    , IN todaysdate DATETIME
    , IN userid INT
	)
BEGIN
	INSERT INTO `payment_dump`(
						`vendor_code`
						, `bill_no`
						, `clearing_doc_no`
                        , `clearing_date`
						, `ref_no`
                        , `payment_status`
                        , `customerno`
						, `created_on`
						, `updated_on`
						, `created_by`
						, `updated_by`) 
				VALUES (vendorcode
					, billno
					, clearingdocno
                    , clearingdate
                    , refno
                    , paymentstatus
                    , customerno
					, todaysdate
					, todaysdate
					, userid
					, userid
					);

END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_shipment_details`$$
CREATE PROCEDURE `get_shipment_details`(
        IN custno INT
        ,IN deliveryno bigint
        ,IN lrno varchar(100)
)
BEGIN
	DECLARE deliverynoparam bigint;
    DECLARE lrnoparam varchar(100);
    DECLARE shipmentnoparam varchar(30);
    DECLARE costdocnoparam varchar(30);
    DECLARE trucktypeparam varchar(150);
    DECLARE routeparam varchar(150);
    DECLARE vehicleparam varchar(30);
    DECLARE indentidparam int;
    

    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(deliveryno = '' OR deliveryno = 0) THEN
	SET deliveryno = NULL;
    END IF;
    IF(lrno = '') THEN
	SET lrno = NULL;
    END IF;
    
    SELECT 	
            delivery_no, lr_no,shipment_no,cost_doc_no,truck_type,route,vehicle_no
            INTO deliverynoparam,lrnoparam,shipmentnoparam,costdocnoparam,trucktypeparam,routeparam,vehicleparam
	FROM    shipment_dump sd
    WHERE (sd.customerno = custno OR custno IS NULL)
    AND (sd.delivery_no = deliveryno OR deliveryno IS NULL)
    AND (sd.lr_no = lrno OR lrno IS NULL)    
	AND sd.isdeleted = 0;
    
    IF(shipmentnoparam IS NOT NULL)THEN
		SELECT 
		indentid  INTO indentidparam
		FROM indent 
		WHERE (shipmentno = shipmentnoparam OR shipmentnoparam IS NULL)
		AND isdeleted = 0;
    END IF;
    
    IF(deliverynoparam is NOT NULL)THEN
    SELECT 
    deliverynoparam,
    lrnoparam,
    shipmentnoparam,
    costdocnoparam,
    trucktypeparam,
    routeparam,
    vehicleparam,
    indentidparam;
    END IF;
    
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_bill_details_draft`$$
CREATE PROCEDURE `insert_bill_details_draft`(
         IN billtypeid INT
        , IN invoice_location_id INT
        , IN depot_id INT
        , IN vendor_id INT
        , IN bill_no VARCHAR(100)
        , IN bill_date date
        , IN description text
        , IN final_bill_amount int
        , IN bill_received_date date
        , IN bill_processed_date date
        , IN bill_sent_date date
        , IN grn_no int
        , IN po_no int
        , IN remarks_regarding_bill text
        , IN remarks_regarding_settlement text
        , IN due_days int 
        , IN billing_status varchar(30) 
        , IN due_status varchar(30) 
        , IN days_for_receiving_bills tinyint
        , IN process_days tinyint
        , IN custody tinyint
        , IN total_custody tinyint 
        , IN payment_done tinyint
        , IN month_sent varchar(10) 
        , IN year_sent int
        , IN payment_bucket varchar(50) 
        , IN payment_status varchar(50)
        , IN customerno INT
		, IN todaysdate DATETIME
		, IN userid INT
        , OUT current_billid INT
        
)
BEGIN
    INSERT INTO 
		billpayable_draft
        (
        billtypeid
        ,invoice_location_id
        ,depot_location_id
        ,vendor_id
        ,bill_no
        ,bill_date
        ,description
        ,final_bill_amount
        ,bill_received_date
        ,bill_processed_date
        ,bill_sent_date
        ,grn_no
        ,po_no
        ,bill_remarks
        ,settelement_remarks
        ,due_days 
        , billing_status 
        , due_status 
        , days_for_receiving_bills 
        , process_days 
        , custody 
        , total_custody 
        , payment_done 
        , month_sent 
        , year_sent 
        , payment_bucket 
        , payment_status 
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        )
        values
        (
        billtypeid
        ,invoice_location_id
        ,depot_id
        ,vendor_id
        ,bill_no
        ,bill_date
        ,description
        ,final_bill_amount
        ,bill_received_date
        ,bill_processed_date
        ,bill_sent_date
        ,grn_no
        ,po_no
        ,remarks_regarding_bill
        ,remarks_regarding_settlement
        ,due_days 
        , billing_status 
        , due_status 
        , days_for_receiving_bills 
        , process_days 
        , custody 
        , total_custody 
        , payment_done 
        , month_sent 
        , year_sent 
        , payment_bucket 
        , payment_status 
        ,customerno
		,todaysdate
		,todaysdate
		,userid
		,userid
        );
        
        SET current_billid = LAST_INSERT_ID();
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `update_bill_details_draft`$$
CREATE PROCEDURE `update_bill_details_draft`(
		 IN billidparam INT
        , IN billtypeidparam INT
        , IN invoice_location_idparam INT
        , IN depot_idparam INT
        , IN vendor_idparam INT
        , IN bill_noparam VARCHAR(100)
        , IN bill_dateparam date
        , IN descriptionparam text
        , IN final_bill_amountparam int
        , IN bill_received_dateparam date
        , IN bill_processed_dateparam date
        , IN bill_sent_dateparam date
        , IN grn_noparam int
        , IN po_noparam int
        , IN remarks_regarding_billparam text
        , IN remarks_regarding_settlementparam text
        , IN due_daysparam int 
        , IN billing_statusparam varchar(30) 
        , IN due_statusparam varchar(30) 
        , IN days_for_receiving_billsparam tinyint
        , IN process_daysparam tinyint
        , IN custodyparam tinyint
        , IN total_custodyparam tinyint 
        , IN payment_doneparam tinyint
        , IN month_sentparam varchar(10) 
        , IN year_sentparam int
        , IN payment_bucketparam varchar(50) 
        , IN payment_statusparam varchar(50)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update billpayable_draft SET
			billtypeid = billtypeidparam
            , invoice_location_id = invoice_location_idparam
            , depot_location_id = depot_idparam
            , vendor_id = vendor_idparam
            , bill_no = bill_noparam 
            , bill_date = bill_dateparam
            , description = descriptionparam
            , final_bill_amount = final_bill_amountparam
            , bill_received_date = bill_received_dateparam
            , bill_processed_date = bill_processed_dateparam
            , bill_sent_date = bill_sent_dateparam
            , grn_no = grn_noparam
            , po_no = po_noparam
            , bill_remarks = remarks_regarding_billparam
            , settelement_remarks = remarks_regarding_settlementparam
            , due_days = due_daysparam
			, billing_status = billing_statusparam
			, due_status = due_statusparam
			, days_for_receiving_bills = days_for_receiving_billsparam
			, process_days = process_daysparam
			, custody = custodyparam
			, total_custody = total_custodyparam
			, payment_done = payment_doneparam
			, month_sent = month_sentparam
			, year_sent = year_sentparam
			, payment_bucket = payment_bucketparam
			, payment_status = payment_statusparam
            , updated_on = todaysdate
			, updated_by = userid
	WHERE billid =  billidparam;
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_lr_details_draft`$$
CREATE PROCEDURE `insert_lr_details_draft`(
		bill_id int
        , IN delivery_no bigint
        , IN lr_no varchar(100)
        , IN shipment_no varchar(30)
        , IN cost_document_no varchar(30)
        , IN truck_type varchar(30)
        , IN route varchar(150)
        , IN vehicle_no varchar(20)
        , IN indentid int
        , IN vehicle_type tinyint
        , IN movement_type tinyint
        , IN cfa_cost decimal(10,2)
        , IN shipment_freight_bill decimal(10,2)
        , IN loading_charges decimal(10,2)
        , IN unloading_charges decimal(10,2)
        , IN other_charges decimal(10,2)
        , IN multidrop_charges decimal(10,2)
        , IN toll_charges decimal(10,2)
        , IN permit_charges decimal(10,2)
        , IN charges_outword decimal(10,2)
        , IN gprs decimal(10,2)
        , IN noentry_charges decimal(10,2)
        , IN auto_charges decimal(10,2)
        , IN lr_charges decimal(10,2)
        , IN tt_penalty decimal(10,2)
        , IN any_deduction decimal(10,2)
        , In total_delivery_amount decimal(10,2)
        , IN customerno INT
		, IN todaysdate DATETIME
		, IN userid INT
        , OUT current_lrid INT
        
)
BEGIN
    INSERT INTO 
		lrDetails_draft
        (
        bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        )
        values
        (
        bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,todaysdate
		,todaysdate
		,userid
		,userid
        );
        
        SET current_lrid = LAST_INSERT_ID();
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_lr_details_draft`$$
CREATE PROCEDURE `delete_lr_details_draft`(
		 IN lridparam INT
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update lrDetails_draft SET
			isdeleted = 1
	WHERE lrid =  lridparam
    AND customerno = custno;
    
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_lr_details_draft`$$
CREATE PROCEDURE `get_lr_details_draft`(
        IN lridparam INT
        ,IN custno int
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(lridparam = '' OR lridparam = 0) THEN
	SET lridparam = NULL;
    END IF;
    
    SELECT 	
		lrid
		,bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
    FROM    lrDetails_draft 
    WHERE (customerno = custno OR custno IS NULL)
    AND (lrid = lridparam OR lridparam IS NULL)
	AND   isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `update_lr_details_draft`$$
CREATE PROCEDURE `update_lr_details_draft`(
		lridparam int
        , IN delivery_noparam bigint
        , IN lr_noparam varchar(100)
        , IN shipment_noparam varchar(30)
        , IN cost_document_noparam varchar(30)
        , IN truck_typeparam varchar(30)
        , IN routeparam varchar(150)
        , IN vehicle_noparam varchar(20)
        , IN indentidparam int
        , IN vehicle_typeparam tinyint
        , IN movement_typeparam tinyint
        , IN cfa_costparam decimal(10,2)
        , IN shipment_freight_billparam decimal(10,2)
        , IN loading_chargesparam decimal(10,2)
        , IN unloading_chargesparam decimal(10,2)
        , IN other_chargesparam decimal(10,2)
        , IN multidrop_chargesparam decimal(10,2)
        , IN toll_chargesparam decimal(10,2)
        , IN permit_chargesparam decimal(10,2)
        , IN charges_outwordparam decimal(10,2)
        , IN gprsparam decimal(10,2)
        , IN noentry_chargesparam decimal(10,2)
        , IN auto_chargesparam decimal(10,2)
        , IN lr_chargesparam decimal(10,2)
        , IN tt_penaltyparam decimal(10,2)
        , IN any_deductionparam decimal(10,2)
        , In total_delivery_amountparam decimal(10,2)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    UPDATE lrDetails_draft
    SET 
    delivery_no = delivery_noparam
    , lr_no =lr_noparam
    , shipment_no= shipment_noparam
    , cost_document_no = cost_document_noparam
    , truck_type= truck_typeparam
    , route = routeparam
    , vehicle_no = vehicle_noparam
    , indentid = indentidparam
    , vehicle_type = vehicle_typeparam
    , movement_type = movement_typeparam
    , cfa_cost = cfa_costparam
    , shipment_freight_bill = shipment_freight_billparam
    , loading_charges = loading_chargesparam
    , unloading_charges = unloading_chargesparam
    , other_charges  = other_chargesparam
    , multidrop_charges = multidrop_chargesparam
    , toll_charges = toll_chargesparam
    , permit_charges = permit_chargesparam
    , charges_outword = charges_outwordparam
    , gprs = gprsparam
    , noentry_charges = noentry_chargesparam
    , auto_charges = auto_chargesparam
    , lr_charges = lr_chargesparam
    , tt_penalty = tt_penaltyparam
    , any_deduction = any_deductionparam
    , total_delivery_amount = total_delivery_amountparam
    , updated_on = todaysdate
	, updated_by = userid
    WHERE lrid = lridparam
    AND isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_bill_details_draft`$$
CREATE PROCEDURE `get_bill_details_draft`(
		 IN billidparam INT 
         ,IN custno INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(billidparam = '' OR billidparam = 0) THEN
	SET billidparam = NULL;
    END IF;	

    SELECT 
		billid
        ,billtypeid
        ,invoice_location_id
        ,factory.factoryname as invoice_location
        ,depot.depotname as depotname
        ,depot_location_id
        ,vendor_id
        ,transporter.transportername as vendorname
        ,bill_no
        ,bill_date
        ,description
        ,final_bill_amount
        ,bill_received_date
        ,bill_processed_date
        ,bill_sent_date
        ,grn_no
        ,po_no
        ,bill_remarks
        ,settelement_remarks
        ,due_days 
        , billing_status 
        , due_status 
        , days_for_receiving_bills 
        , process_days 
        , custody 
        , total_custody 
        , payment_done 
        , month_sent 
        , year_sent 
        , payment_bucket 
        , payment_status 
		, billpayable_draft.customerno
		,billpayable_draft.created_on
		,billpayable_draft.updated_on
		,billpayable_draft.created_by
		,billpayable_draft.updated_by
        FROM billpayable_draft 
    LEFT JOIN factory on factory.factoryid =  billpayable_draft.invoice_location_id
    LEFT JOIN  depot on depot.depotid = billpayable_draft.depot_location_id
    LEFT JOIN transporter on transporter.transporterid = billpayable_draft.vendor_id
	WHERE (billpayable_draft.customerno = custno OR custno IS NULL)
    AND (billid = billidparam OR billidparam IS NULL)
	AND   billpayable_draft.isdeleted = 0;
        
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_vendor_payable`$$
CREATE PROCEDURE `insert_vendor_payable`(
        IN billidparam INT
        , IN custno INT
)
BEGIN

DECLARE current_billid int;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
	END;
    
    START TRANSACTION;
    
    IF(custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    IF(billidparam = '' OR billidparam = 0) THEN
		SET billidparam = NULL;
    END IF;
    

    INSERT INTO billpayable(
		billtypeid
        ,invoice_location_id
        ,depot_location_id
        ,vendor_id
        ,bill_no
        ,bill_date
        ,description
        ,final_bill_amount
        ,bill_received_date
        ,bill_processed_date
        ,bill_sent_date
        ,grn_no
        ,po_no
        ,bill_remarks
        ,settelement_remarks
        ,due_days 
        , billing_status 
        , due_status 
        , days_for_receiving_bills 
        , process_days 
        , custody 
        , total_custody 
        , payment_done 
        , month_sent 
        , year_sent 
        , payment_bucket 
        , payment_status 
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        ,isdeleted
    )
     SELECT 	
		billtypeid
        ,invoice_location_id
        ,depot_location_id
        ,vendor_id
        ,bill_no
        ,bill_date
        ,description
        ,final_bill_amount
        ,bill_received_date
        ,bill_processed_date
        ,bill_sent_date
        ,grn_no
        ,po_no
        ,bill_remarks
        ,settelement_remarks
        ,due_days 
        , billing_status 
        , due_status 
        , days_for_receiving_bills 
        , process_days 
        , custody 
        , total_custody 
        , payment_done 
        , month_sent 
        , year_sent 
        , payment_bucket 
        , payment_status 
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        , isdeleted
	FROM    billpayable_draft bd
    WHERE (bd.customerno = custno OR custno IS NULL)
    AND   (bd.billid = billidparam OR billidparam IS NULL)
    AND   bd.isdeleted = 0;

	SET current_billid = LAST_INSERT_ID();

	IF(current_billid IS NOT NULL OR current_billid > 0) THEN
    
    INSERT INTO lrDetails(
		lrid
		,bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        ,isdeleted
    )
    SELECT
		lrid
		,current_billid
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        ,isdeleted
	FROM lrDetails_draft ld
	WHERE (ld.customerno = custno OR custno IS NULL)
    AND   (ld.bill_id = billidparam OR billidparam IS NULL)
    AND   ld.isdeleted = 0;
    
    END IF;
        
	

	COMMIT;
    
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_bill_details`$$
CREATE PROCEDURE `get_bill_details`(
		 IN billidparam INT 
         ,IN custno INT
)
BEGIN
	
	IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(billidparam = '' OR billidparam = 0) THEN
	SET billidparam = NULL;
    END IF;	

    SELECT 
		billid
        ,billtypeid
        ,invoice_location_id
        ,factory.factoryname as invoice_location
        ,depot.depotname as depotname
        ,depot_location_id
        ,vendor_id
        ,transporter.transportername as vendorname
        ,bill_no
        ,bill_date
        ,description
        ,final_bill_amount
        ,bill_received_date
        ,bill_processed_date
        ,bill_sent_date
        ,grn_no
        ,po_no
        ,bill_remarks
        ,settelement_remarks
        ,due_days 
        , billing_status 
        , due_status 
        , days_for_receiving_bills 
        , process_days 
        , custody 
        , total_custody 
        , payment_done 
        , month_sent 
        , year_sent 
        , payment_bucket 
        , payment_status 
		, billpayable.customerno
		,billpayable.created_on
		,billpayable.updated_on
		,billpayable.created_by
		,billpayable.updated_by
        FROM billpayable
    LEFT JOIN factory on factory.factoryid =  billpayable.invoice_location_id
    LEFT JOIN  depot on depot.depotid = billpayable.depot_location_id
    LEFT JOIN transporter on transporter.transporterid = billpayable.vendor_id
	WHERE (billpayable.customerno = custno OR custno IS NULL)
    AND (billid = billidparam OR billidparam IS NULL)
	AND   billpayable.isdeleted = 0;
        
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_lr_details`$$
CREATE PROCEDURE `get_lr_details`(
        IN lridparam INT
        ,IN billid INT
        ,IN custno INT
)
BEGIN
    IF(custno = '' OR custno = 0) THEN
	SET custno = NULL;
    END IF;
    IF(billid = '' OR billid = 0) THEN
	SET billid = NULL;
    END IF;
    IF(lridparam = '' OR lridparam = 0) THEN
	SET lridparam = NULL;
    END IF;
    
    SELECT 	
		lrid
		,bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
    FROM  lrDetails 
    WHERE (customerno = custno OR custno IS NULL)
    AND (lrid = lridparam OR lridparam IS NULL)
    AND (bill_id = billid OR billid IS NULL)
	AND   isdeleted = 0;
END$$
DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `delete_lr_details`$$
CREATE PROCEDURE `delete_lr_details`(
		 IN lridparam INT
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update lrDetails SET
			isdeleted = 1
	WHERE lrid =  lridparam
    AND customerno = custno;
    
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `update_lr_details`$$
CREATE PROCEDURE `update_lr_details`(
		lridparam int
        , IN delivery_noparam bigint
        , IN lr_noparam varchar(100)
        , IN shipment_noparam varchar(30)
        , IN cost_document_noparam varchar(30)
        , IN truck_typeparam varchar(30)
        , IN routeparam varchar(150)
        , IN vehicle_noparam varchar(20)
        , IN indentidparam int
        , IN vehicle_typeparam tinyint
        , IN movement_typeparam tinyint
        , IN cfa_costparam decimal(10,2)
        , IN shipment_freight_billparam decimal(10,2)
        , IN loading_chargesparam decimal(10,2)
        , IN unloading_chargesparam decimal(10,2)
        , IN other_chargesparam decimal(10,2)
        , IN multidrop_chargesparam decimal(10,2)
        , IN toll_chargesparam decimal(10,2)
        , IN permit_chargesparam decimal(10,2)
        , IN charges_outwordparam decimal(10,2)
        , IN gprsparam decimal(10,2)
        , IN noentry_chargesparam decimal(10,2)
        , IN auto_chargesparam decimal(10,2)
        , IN lr_chargesparam decimal(10,2)
        , IN tt_penaltyparam decimal(10,2)
        , IN any_deductionparam decimal(10,2)
        , In total_delivery_amountparam decimal(10,2)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    UPDATE lrDetails
    SET 
    delivery_no = delivery_noparam
    , lr_no =lr_noparam
    , shipment_no= shipment_noparam
    , cost_document_no = cost_document_noparam
    , truck_type= truck_typeparam
    , route = routeparam
    , vehicle_no = vehicle_noparam
    , indentid = indentidparam
    , vehicle_type = vehicle_typeparam
    , movement_type = movement_typeparam
    , cfa_cost = cfa_costparam
    , shipment_freight_bill = shipment_freight_billparam
    , loading_charges = loading_chargesparam
    , unloading_charges = unloading_chargesparam
    , other_charges  = other_chargesparam
    , multidrop_charges = multidrop_chargesparam
    , toll_charges = toll_chargesparam
    , permit_charges = permit_chargesparam
    , charges_outword = charges_outwordparam
    , gprs = gprsparam
    , noentry_charges = noentry_chargesparam
    , auto_charges = auto_chargesparam
    , lr_charges = lr_chargesparam
    , tt_penalty = tt_penaltyparam
    , any_deduction = any_deductionparam
    , total_delivery_amount = total_delivery_amountparam
    , updated_on = todaysdate
	, updated_by = userid
    WHERE lrid = lridparam
    AND isdeleted = 0;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `update_bill_details`$$
CREATE PROCEDURE `update_bill_details`(
		 IN billidparam INT
        , IN billtypeidparam INT
        , IN invoice_location_idparam INT
        , IN depot_idparam INT
        , IN vendor_idparam INT
        , IN bill_noparam VARCHAR(100)
        , IN bill_dateparam date
        , IN descriptionparam text
        , IN final_bill_amountparam int
        , IN bill_received_dateparam date
        , IN bill_processed_dateparam date
        , IN bill_sent_dateparam date
        , IN grn_noparam int
        , IN po_noparam int
        , IN remarks_regarding_billparam text
        , IN remarks_regarding_settlementparam text
        , IN due_daysparam int 
        , IN billing_statusparam varchar(30) 
        , IN due_statusparam varchar(30) 
        , IN days_for_receiving_billsparam tinyint
        , IN process_daysparam tinyint
        , IN custodyparam tinyint
        , IN total_custodyparam tinyint 
        , IN payment_doneparam tinyint
        , IN month_sentparam varchar(10) 
        , IN year_sentparam int
        , IN payment_bucketparam varchar(50) 
        , IN payment_statusparam varchar(50)
        , IN custno INT
		, IN todaysdate DATETIME
		, IN userid INT
        
)
BEGIN
    update billpayable SET
			billtypeid = billtypeidparam
            , invoice_location_id = invoice_location_idparam
            , depot_location_id = depot_idparam
            , vendor_id = vendor_idparam
            , bill_no = bill_noparam 
            , bill_date = bill_dateparam
            , description = descriptionparam
            , final_bill_amount = final_bill_amountparam
            , bill_received_date = bill_received_dateparam
            , bill_processed_date = bill_processed_dateparam
            , bill_sent_date = bill_sent_dateparam
            , grn_no = grn_noparam
            , po_no = po_noparam
            , bill_remarks = remarks_regarding_billparam
            , settelement_remarks = remarks_regarding_settlementparam
            , due_days = due_daysparam
			, billing_status = billing_statusparam
			, due_status = due_statusparam
			, days_for_receiving_bills = days_for_receiving_billsparam
			, process_days = process_daysparam
			, custody = custodyparam
			, total_custody = total_custodyparam
			, payment_done = payment_doneparam
			, month_sent = month_sentparam
			, year_sent = year_sentparam
			, payment_bucket = payment_bucketparam
			, payment_status = payment_statusparam
            , updated_on = todaysdate
			, updated_by = userid
	WHERE billid =  billidparam;
    
END$$
DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_lr_details`$$
CREATE PROCEDURE `insert_lr_details`(
		bill_id int
        , IN delivery_no bigint
        , IN lr_no varchar(100)
        , IN shipment_no varchar(30)
        , IN cost_document_no varchar(30)
        , IN truck_type varchar(30)
        , IN route varchar(150)
        , IN vehicle_no varchar(20)
        , IN indentid int
        , IN vehicle_type tinyint
        , IN movement_type tinyint
        , IN cfa_cost decimal(10,2)
        , IN shipment_freight_bill decimal(10,2)
        , IN loading_charges decimal(10,2)
        , IN unloading_charges decimal(10,2)
        , IN other_charges decimal(10,2)
        , IN multidrop_charges decimal(10,2)
        , IN toll_charges decimal(10,2)
        , IN permit_charges decimal(10,2)
        , IN charges_outword decimal(10,2)
        , IN gprs decimal(10,2)
        , IN noentry_charges decimal(10,2)
        , IN auto_charges decimal(10,2)
        , IN lr_charges decimal(10,2)
        , IN tt_penalty decimal(10,2)
        , IN any_deduction decimal(10,2)
        , In total_delivery_amount decimal(10,2)
        , IN customerno INT
		, IN todaysdate DATETIME
		, IN userid INT
        , OUT current_lrid INT
        
)
BEGIN
    INSERT INTO 
		lrDetails
        (
        bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,created_on
		,updated_on
		,created_by
		,updated_by
        )
        values
        (
        bill_id
        ,delivery_no
        ,lr_no
        ,shipment_no
        ,cost_document_no
        ,truck_type
        ,route
        ,vehicle_no
        ,indentid
        ,vehicle_type
        ,movement_type
        ,cfa_cost
        ,shipment_freight_bill
        ,loading_charges
        ,unloading_charges
        ,other_charges
        ,multidrop_charges
        ,toll_charges
        ,permit_charges
        ,charges_outword
        ,gprs
        ,noentry_charges
        ,auto_charges
        ,lr_charges
        ,tt_penalty
        ,any_deduction
        ,total_delivery_amount
		,customerno
		,todaysdate
		,todaysdate
		,userid
		,userid
        );
        
        SET current_lrid = LAST_INSERT_ID();
END$$
DELIMITER ;






-- Successful. Add the Patch to the Applied Patches table.

 INSERT INTO `dbpatches` ( `patchid`, `patchdate`, `appliedby`, `patchdesc` ) 
 VALUES ( 15, NOW(), 'Shrikant Suryawanshi','Import Dump Data And Vendor Payable Process');
 
 
