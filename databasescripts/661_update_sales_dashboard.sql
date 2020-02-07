INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('661', '2019-02-06 20:00:00', 'Manasvi Thakur','Add trigger on invoice table', '0');


CREATE TABLE `sales_target_details` (
  `stdid` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `target_amount` varchar(30) NOT NULL,
  `achieved_target` varchar(11) NOT NULL,
  `target_set_by` int(11) NOT NULL,
  `target_set_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `target_achieved_percentage` int(11) NOT NULL,
  `customerNo` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `createdOn` datetime NOT NULL,
  `updatedby` int(20) NOT NULL,
  `updatedon` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `sales_target_details`
  ADD PRIMARY KEY (`stdid`);

ALTER TABLE `sales_target_details`
  MODIFY `stdid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

CREATE TABLE `sales_configuration` (
  `scid` int(11) NOT NULL,
  `incentives` float NOT NULL COMMENT 'in percentage',
  `companyRoleId` int(20) NOT NULL,
  `companyRole` varchar(50) NOT NULL,
  `isDeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `sales_configuration`
  ADD PRIMARY KEY (`scid`);

ALTER TABLE `sales_configuration`
  MODIFY `scid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


DELIMITER $$
DROP PROCEDURE IF EXISTS `get_sales_user_list`$$
CREATE  PROCEDURE `get_sales_user_list`(
        IN termParam VARCHAR(50)
        ,IN userLoginIdParam INT
)
BEGIN
 
      /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
      @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
      SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
      SELECT @full_error; */
      IF(termParam != NULL)THEN
    SELECT t.teamid,t.name FROM team t
    LEFT JOIN elixiatech.department d
    ON d.department_id = t.department_id 
    WHERE t.is_deleted = 0 AND t.name 
    LIKE CONCAT('%', termParam ,'%') AND d.department_id IN(4) 
    AND t.teamid != userLoginIdParam;
  END IF;

  IF(termParam = NULL)THEN
    SELECT t.teamid,t.name FROM team t
    LEFT JOIN elixiatech.department d
    ON d.department_id = t.department_id 
    WHERE t.is_deleted = 0 AND t.name AND d.department_id IN(4) 
    AND t.teamid != userLoginIdParam;
  END IF;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `get_team_sales_dashboard_details`$$
CREATE PROCEDURE `get_team_sales_dashboard_details`(
    IN userloginIdParam INT
    ,IN currentMonthParam INT
    ,IN currentDateParam DATE
    ,IN companyRoleIdParam INT
)
BEGIN
    DECLARE leads_given INT;
    DECLARE leades_recieved INT;
    DECLARE team_incentives INT;
    DECLARE incentiveval INT;
    DECLARE incentive_amount_val INT;
    DECLARE customer_won INT;
    DECLARE customerInPipeline INT;
    DECLARE revenue_target INT;
    DECLARE achieved_amount INT;
    DECLARE achieved_percentage INT;
    DECLARE conversion_ratio INT;
    DECLARE avg_closure_day INT;
    DECLARE customer_won_timestamp INT;
    DECLARE customer_Added_timestamp INT;
    DECLARE customerInPipeline_cold INT;
    DECLARE customerInPipeline_warm INT;
    DECLARE customerInPipeline_hot INT;
    DECLARE incentivesPercentage FLOAT;

    SET revenue_target = 0,achieved_amount=0,achieved_percentage=0,leads_given=0,conversion_ratio=0,avg_closure_day=0;
    ROLLBACK;
     /*
       GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
       @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
       SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
       SELECT @full_error;
      */
    IF userloginIdParam = 0 THEN
        SET userloginIdParam = NULL;
    END IF;
    SET team_incentives = 0;
    
  IF(userloginIdParam IS NOT NULL)THEN
    SELECT target_amount,achieved_target,target_achieved_percentage into revenue_target, 
        achieved_amount,achieved_percentage
    FROM sales_target_details 
    WHERE teamid = userloginIdParam AND MONTH(target_set_on) = currentMonthParam;

    SELECT COUNT(customerNo) INTO leads_given 
    FROM sales_pipeline
    WHERE teamid_creator = userloginIdParam AND MONTH(`timestamp`) = currentMonthParam 
    AND DATE(`timestamp`) = currentdateParam;

    SELECT COUNT(customerNo) INTO leades_recieved 
    FROM sales_pipeline
    WHERE teamid = userloginIdParam AND MONTH(`timestamp`) = currentMonthParam 
    AND DATE(`timestamp`) = currentdateParam;

    SELECT COUNT(customerNo) INTO customerInPipeline 
    FROM sales_pipeline
    WHERE teamid = userloginIdParam AND tepidity IN(1,2,3) 
    AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

    SELECT COUNT(customerNo) INTO customerInPipeline_cold
    FROM sales_pipeline
    WHERE teamid = userloginIdParam AND tepidity = 1
    AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

    SELECT COUNT(customerNo) INTO customerInPipeline_warm
    FROM sales_pipeline
    WHERE teamid = userloginIdParam AND tepidity = 2
    AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

    SELECT COUNT(customerNo) INTO customerInPipeline_hot
    FROM sales_pipeline
    WHERE teamid = userloginIdParam AND tepidity = 3
    AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

    SELECT COUNT(customerNo) INTO customer_won 
    FROM sales_pipeline
    WHERE teamid = userloginIdParam AND stageid = 8 
    AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

    SELECT `timestamp` INTO customer_won_timestamp
    FROM sales_pipeline
    WHERE teamid = userloginIdParam AND stageid = 8 ORDER BY `timestamp` DESC LIMIT 1;

    SELECT `timestamp` INTO customer_Added_timestamp
    FROM sales_pipeline
    WHERE teamid = userloginIdParam AND stageid = 7 ORDER BY `timestamp` ASC LIMIT 1 ;

    SELECT incentives into incentivesPercentage 
    FROM sales_configuration WHERE companyRoleId  = companyRoleIdParam ;

    SELECT incentives_amount into incentive_amount_val
    FROM incentives  
    WHERE teamid = userloginIdParam AND MONTH(updatedOn) =currentMonthParam ;

    SET incentiveval =  incentive_amount_val * incentivesPercentage;

    IF(customer_won != 0 AND customerInPipeline != 0) THEN
    SET conversion_ratio = (customer_won / customerInPipeline);
    END IF;

    SET avg_closure_day = (customer_won_timestamp - customer_Added_timestamp/customer_won);

    SELECT  revenue_target,
        achieved_amount, 
        achieved_percentage,
        leads_given,
        leades_recieved,
        customerInPipeline,
        customer_won,
        incentiveval,
        team_incentives,
        conversion_ratio,
        avg_closure_day,
        customerInPipeline_cold,
        customerInPipeline_warm,
        customerInPipeline_hot; 
  END IF;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `insert_sales_target_amount`$$
CREATE  PROCEDURE `insert_sales_target_amount`(
        IN teamIdParam INT
        ,IN userLoginIdParam INT
        ,IN targetAmountParam VARCHAR(30)
        ,IN targetDateParam DATETIME
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
      AND MONTH(target_set_on) = MONTH(targetAmountParam);

    SET @ifExistEntryCount = salesTargetCount;

    START TRANSACTION;
        IF (userLoginIdParam = '' OR userLoginIdParam = 0) THEN
            SET userLoginIdParam = NULL;
        END IF;
        IF(@ifExistEntryCount <= 0 )THEN
      INSERT INTO sales_target_details ( 
          teamid
          ,target_amount
          ,target_set_by
          ,target_set_on
          ,createdBy
          ,createdOn
        )
      VALUES(
          teamIdParam
          ,targetAmountParam
          ,userLoginIdParam
          ,targetDateParam
          ,createdByParam
          ,createdOnParam
        );
    END IF;
    select @ifExistEntryCount;
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `after_invoice_INSERT` $$
CREATE TRIGGER `after_invoice_INSERT`
    AFTER INSERT ON invoice
    FOR EACH ROW BEGIN
        DECLARE teamidval INT;
        DECLARE datedifference INT;
        DECLARE incentivesval FLOAT;

        SET @serverTime     = now();
        SET @istDateTime    = CONVERT_TZ(@serverTime, 'SYSTEM', '+05:30');
    
            UPDATE sales_target_details  
            INNER JOIN sales_pipeline sp ON sp.customerNo = NEW.customerno
            SET achieved_target     =   achieved_target+NEW.inv_amt
            WHERE MONTH(paymentdate) =   MONTH(target_set_on) 
            AND YEAR(paymentdate) = YEAR(target_set_on) 
            AND sp.teamid = sales_target_details.teamid;

        SET teamidval = null;

       SELECT DATEDIFF(DATE(sales_pipeline.`timestamp`),NEW.inv_date) into datedifference
       FROM sales_pipeline 
       WHERE sales_pipeline.stageid  = 8 AND sales_pipeline.customerNo = NEW.customerNo;

        IF(datedifference <= 90)THEN
           SELECT sp.teamid,sp.customerNo into teamidval
           FROM sales_pipeline sp 
           WHERE customerNo = NEW.customerno AND sp.stageid= 8;

           SELECT sc.incentives into incentivesval
           FROM sales_configuration sc 
           LEFT JOIN team ON sc.companyRoleId = team.company_roleId
           AND team.teamid = teamidval;

           INSERT INTO incentives(teamId,incentives_amount,updatedOn) 
           VALUES(teamidval,NEW.inv_amt,@istDateTime);
        END IF;
END$$
DELIMITER ;


UPDATE  dbpatches
SET     updatedOn = now()
        ,isapplied = 1
WHERE   patchid = 661;
