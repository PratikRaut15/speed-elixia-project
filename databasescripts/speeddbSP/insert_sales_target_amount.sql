DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_sales_target_amount`$$
CREATE  PROCEDURE `insert_sales_target_amount`(
        IN teamIdParam INT
        ,IN userLoginIdParam INT
        ,IN targetAmountParam VARCHAR(30)
        ,IN targetMonthParam TINYINT(2)
        ,IN targetYearParam TINYINT(4)
        ,IN createdByParam INT
        ,IN createdOnParam DATETIME
)
BEGIN
    DECLARE ifExistEntryCount INT;
        DECLARE salesTargetCount INT;
        BEGIN
        ROLLBACK;
/*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
      */
      END;

      SELECT count(*) INTO salesTargetCount 
      FROM sales_target_details 
      WHERE teamid = teamIdParam 
      AND target_set_for_month = targetMonthParam 
      AND target_set_for_year =  targetYearParam;

    SET @ifExistEntryCount = salesTargetCount;

    START TRANSACTION;
        IF (userLoginIdParam = '' OR userLoginIdParam = 0) THEN
            SET userLoginIdParam = NULL;
        END IF;
        IF(@ifExistEntryCount < 1  )THEN
      INSERT INTO sales_target_details ( 
          teamid
          ,target_amount
          ,target_set_by
          ,target_set_for_month
          ,target_set_for_year
          ,createdBy
          ,createdOn
        )
      VALUES(
          teamIdParam
          ,targetAmountParam
          ,userLoginIdParam
          ,targetMonthParam
          ,targetYearParam
          ,createdByParam
          ,createdOnParam
        );
    END IF;
    COMMIT;
    select @ifExistEntryCount;
END$$
DELIMITER ;
-- CALL insert_sales_target_amount('118','129','5000','2019-02-22 12:00:00','129','2019-02-06 07:47:23')