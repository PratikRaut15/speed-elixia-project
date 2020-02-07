DELIMITER $$
DROP PROCEDURE IF EXISTS `get_distinct_transporters`$$
CREATE PROCEDURE `get_distinct_transporters`( 
	IN custno int
)
BEGIN
	IF (custno = '' OR custno = 0) THEN
		SET custno = NULL;
    END IF;
    
	select 
    distinct(proposed_transporterid) 
    from proposed_indent_transporter_mapping as pit
	INNER JOIN proposed_indent as pi on  pi.proposedindentid = pit.proposedindentid
	WHERE (pit.customerno = custno OR custno IS NULL)
    AND pit.isdeleted = 0 
    AND pi.isdeleted = 0;

END$$
DELIMITER ;
