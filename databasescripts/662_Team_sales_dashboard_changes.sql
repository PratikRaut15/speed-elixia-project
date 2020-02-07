INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES ('662', '2019-02-08 13:10:00', 'Manasvi Thakur','changes to sales dashboard functionality and SP', '0');

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
		WHERE teamid = userloginIdParam AND MONTH(target_set_on) = currentMonthParam 
		AND YEAR(target_set_on) = YEAR(currentDateParam);

		SELECT COUNT(pipelineid) INTO leads_given 
		FROM sales_pipeline
		WHERE teamid_creator = userloginIdParam AND MONTH(`timestamp`) = currentMonthParam 
		AND YEAR(`timestamp`) = YEAR(currentdateParam);

		SELECT COUNT(pipelineid) INTO leades_recieved 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND MONTH(`timestamp`) = currentMonthParam 
		AND YEAR(`timestamp`) = YEAR(currentdateParam);

		SELECT COUNT(pipelineid) INTO customerInPipeline 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity IN(1,2,3) 
		AND MONTH(`timestamp`) = currentMonthParam AND YEAR(`timestamp`) = YEAR(currentdateParam);

		SELECT COUNT(pipelineid) INTO customerInPipeline_cold
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity = 1
		AND MONTH(`timestamp`) = currentMonthParam AND YEAR(`timestamp`) = YEAR(currentdateParam);

		SELECT COUNT(pipelineid) INTO customerInPipeline_warm
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity = 2
		AND MONTH(`timestamp`) = currentMonthParam AND YEAR(`timestamp`) = YEAR(currentdateParam);

		SELECT COUNT(pipelineid) INTO customerInPipeline_hot
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity = 3
		AND MONTH(`timestamp`) = currentMonthParam AND YEAR(`timestamp`) = YEAR(currentdateParam);

		SELECT COUNT(pipelineid) INTO customer_won 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND stageid = 8 
		AND MONTH(`timestamp`) = currentMonthParam AND YEAR(`timestamp`) = YEAR(currentdateParam);

		SELECT `timestamp` INTO customer_won_timestamp
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND stageid = 8 ORDER BY `timestamp` DESC LIMIT 1;

		SELECT sales_pipeline_history.`timestamp` INTO customer_Added_timestamp
		FROM sales_pipeline_history INNER JOIN sales_pipeline ON sales_pipeline.pipelineid
		= sales_pipeline_history.pipelineid
		WHERE sales_pipeline.teamid = userloginIdParam 
		AND sales_pipeline_history.stageid = 1 ORDER BY timestamp ASC LIMIT 1 ;

		SELECT incentives into incentivesPercentage 
		FROM sales_configuration WHERE companyRoleId  = companyRoleIdParam ;

		SELECT SUM(incentives_amount) into incentive_amount_val
		FROM incentives  
		WHERE teamid = userloginIdParam AND MONTH(updatedOn) =currentMonthParam 
		AND  updatedOn >= currentdateParam- INTERVAL 3 MONTH
		AND YEAR(updatedOn) = YEAR(currentdateParam) GROUP BY teamid;

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
      IF(termParam != '' && termParam != NULL)THEN
    SELECT t.teamid,t.name FROM team t
    LEFT JOIN elixiatech.department d
    ON d.department_id = t.department_id 
    WHERE t.is_deleted = 0 AND t.name 
    LIKE CONCAT('%', termParam ,'%') AND d.department_id IN(4) 
    AND t.teamid != userLoginIdParam;
  END IF;

  IF(termParam = '')THEN
    SELECT t.teamid,t.name FROM team t
    LEFT JOIN elixiatech.department d
    ON d.department_id = t.department_id 
    WHERE t.is_deleted = 0 AND d.department_id IN(4) 
    AND t.teamid != userLoginIdParam;
  END IF;
END$$
DELIMITER ;

CREATE TABLE `incentives` (
  `incentiveId` int(11) NOT NULL,
  `teamId` int(20) NOT NULL,
  `incentives_amount` varchar(50) NOT NULL,
  `incentive_reason` varchar(50) NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `updatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isDeleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `incentives`
  ADD PRIMARY KEY (`incentiveId`);

ALTER TABLE `incentives`
  MODIFY `incentiveId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

UPDATE  dbpatches
SET     updatedOn = now()
        ,isapplied = 1
WHERE   patchid = 662;



