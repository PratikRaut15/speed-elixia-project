INSERT INTO `dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'668', '2018-02-13 18:15:00', 'Manasvi Thakur','update trigger for invoice', '0');

ALTER TABLE `invoice`  ADD `incentiveGain` TINYINT(10) NOT NULL DEFAULT '0'  AFTER `is_baddebt`;

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
            WHERE MONTH(NEW.paymentdate) =   MONTH(target_set_on) 
            AND YEAR(NEW.paymentdate) = YEAR(target_set_on) 
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
    DECLARE leads_received INT;
    DECLARE team_incentives INT;
    DECLARE incentiveval INT;
    DECLARE incentive_amount_val INT;
    DECLARE customer_won INT;
    DECLARE customer_won_yet INT;
    DECLARE customerInPipeline INT;
    DECLARE revenue_target INT;
    DECLARE achieved_amount INT;
    DECLARE achieved_percentage INT;
    DECLARE conversion_ratio FLOAT;
    DECLARE avg_closure_day FLOAT;
    DECLARE customer_won_timestamp DATETIME;
    DECLARE customer_Added_timestamp DATETIME;
    DECLARE customerInPipeline_cold INT;
    DECLARE customerInPipeline_warm INT;
    DECLARE customerInPipeline_hot INT;
    DECLARE customerInPipelineOverall INT;
    DECLARE incentivesPercentage FLOAT;

    SET revenue_target = 0,achieved_amount=0,achieved_percentage=0,leads_given=0,conversion_ratio=0,avg_closure_day=0,incentiveval = 0,incentive_amount_val=0;
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
    SET achieved_percentage = 0;
    
 	IF(userloginIdParam IS NOT NULL)THEN
		SELECT target_amount,achieved_target into revenue_target,achieved_amount
		FROM sales_target_details 
		WHERE teamid = userloginIdParam AND MONTH(target_set_on) = currentMonthParam 
		AND YEAR(target_set_on) = YEAR(currentDateParam);

		SET achieved_percentage = (achieved_amount *100)/revenue_target ;

		SELECT COUNT(pipelineid) INTO leads_given 
		FROM sales_pipeline
		WHERE teamid_creator = userloginIdParam AND MONTH(`timestamp`) = currentMonthParam 
		AND YEAR(`timestamp`) = YEAR(currentdateParam);

		SELECT COUNT(pipelineid) INTO leads_received 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND MONTH(`timestamp`) = currentMonthParam 
		AND YEAR(`timestamp`) = YEAR(currentdateParam);

		SELECT COUNT(pipelineid) INTO customerInPipeline 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity IN(1,2,3) 
		AND MONTH(`timestamp`) = currentMonthParam AND YEAR(`timestamp`) = YEAR(currentdateParam);


		SELECT COUNT(pipelineid) INTO customerInPipelineOverall 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity IN(1,2,3) ;

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

		SELECT COUNT(pipelineid) INTO customer_won_yet 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND stageid = 8 ;

		SELECT `timestamp` INTO customer_won_timestamp
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND stageid = 8 ORDER BY `timestamp` DESC LIMIT 1;

		SELECT sales_pipeline_history.`timestamp` INTO customer_Added_timestamp
		FROM sales_pipeline_history INNER JOIN sales_pipeline ON sales_pipeline.pipelineid
		= sales_pipeline_history.pipelineid
		WHERE sales_pipeline.teamid = userloginIdParam 
		AND sales_pipeline_history.stageid = 1 ORDER BY  sales_pipeline_history.timestamp ASC LIMIT 1 ;

		SELECT incentives into incentivesPercentage 
		FROM sales_configuration WHERE companyRoleId  = companyRoleIdParam ;

		SELECT SUM(incentives_amount) into incentive_amount_val
		FROM incentives  
		WHERE teamid = userloginIdParam AND MONTH(updatedOn) =currentMonthParam 
		AND  updatedOn >= currentdateParam- INTERVAL 3 MONTH
		AND YEAR(updatedOn) = YEAR(currentdateParam) GROUP BY teamid;
        
        
        IF(incentive_amount_val != NULL OR incentive_amount_val != 0)THEN
			SET incentiveval =  incentive_amount_val * (incentivesPercentage/100);
        END IF;

		IF(customer_won_yet != 0 AND customerInPipelineOverall != 0) THEN
		SET conversion_ratio = customer_won_yet / customerInPipelineOverall;
		END IF;

		SELECT AVG(DATEDIFF(pipeline_date,DATE(timestamp))) INTO avg_closure_day 
		FROM sales_pipeline WHERE stageid = 8 AND teamid = userloginIdParam;

		SELECT  revenue_target,
				achieved_amount, 
				achieved_percentage,
				leads_given,
				leads_received,
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

UPDATE  dbpatches
SET     updatedOn = now()
        ,isapplied =1
WHERE   patchid = 667;

