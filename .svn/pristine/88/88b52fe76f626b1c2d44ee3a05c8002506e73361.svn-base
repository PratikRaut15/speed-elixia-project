DELIMITER $$
DROP PROCEDURE IF EXISTS `get_shipment_details`$$
CREATE  PROCEDURE `get_shipment_details`(
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
