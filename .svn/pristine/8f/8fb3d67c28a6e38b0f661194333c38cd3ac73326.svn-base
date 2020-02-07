DROP procedure IF EXISTS `get_collectedBy`;

DELIMITER $$
CREATE PROCEDURE `get_collectedBy`(
IN term VARCHAR(100)
)
BEGIN

  SELECT teamid,rid,name FROM team 
  WHERE (name LIKE CONCAT('%', term, '%'))
  ORDER BY teamid ASC ;

END$$

DELIMITER ;