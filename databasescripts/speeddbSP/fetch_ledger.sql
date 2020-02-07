USE `speed`;
DROP procedure IF EXISTS `fetch_ledger`;

DELIMITER $$
USE `speed`$$
CREATE PROCEDURE `fetch_ledger`(
    IN customernoParam VARCHAR(20)
)
BEGIN
SELECT DISTINCT l.ledgerid,l.ledgername
from ledger l
LEFT JOIN ledger_cust_mapping lcm ON lcm.ledgerid = l.ledgerid
LEFT JOIN customer c ON  lcm.customerno = c.customerno 
where c.customerno = customernoParam and l.ledgerid <> 0 AND l.isdeleted = 0
ORDER BY l.ledgerid; 
END$$

DELIMITER ;