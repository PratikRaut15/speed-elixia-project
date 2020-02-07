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
