DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sales_user`$$
CREATE  PROCEDURE `get_sales_user`(
       	IN termParam VARCHAR(50)
)
BEGIN
 
      /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
      @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
      SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
      SELECT @full_error; */
		SELECT t.teamid,t.name FROM team t
		LEFT JOIN elixiatech.department d
		ON d.department_id = t.department_id 
		WHERE t.is_deleted = 0 AND t.name 
		LIKE CONCAT('%', termParam ,'%') AND d.department_id IN(4);
END$$

DELIMITER ;
--CALL get_sales_user('kart');