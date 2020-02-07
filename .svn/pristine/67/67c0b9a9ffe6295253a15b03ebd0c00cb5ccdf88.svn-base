INSERT INTO `dbpatches` (`patchid`, `patchdate`, `appliedby`, `patchdesc`) 
VALUES (655, '2019-01-24 19:15:00', 'Manasvi Thakur','Team Sales dashboard changes');

CREATE TABLE `sales_configuration_settings` (
  `scsid` int(11) NOT NULL,
  `target_achived_threshold` int(11) NOT NULL COMMENT 'default 70%',
  `incentives` int(11) NOT NULL COMMENT 'default 1%',
  `incentives_split_ratio` varchar(20) NOT NULL COMMENT 'default 80-20',
  `added_by` int(10) NOT NULL COMMENT 'loginid(teamid)',
  `added_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(10) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(10) NOT NULL COMMENT 'optional'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `sales_configuration_settings` (`scsid`, `target_achived_threshold`, `incentives`, `incentives_split_ratio`, `added_by`, `added_on`, `updated_by`, `updated_on`, `status`) VALUES
(1, 70, 1, '80-20', 129, '2019-01-24 06:49:56', 129, '0000-00-00 00:00:00', 0);

ALTER TABLE `sales_configuration_settings`
  ADD PRIMARY KEY (`scsid`);

ALTER TABLE `sales_configuration_settings`
  MODIFY `scsid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

CREATE TABLE `sales_target_details` (
  `stdid` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `target_amount` varchar(30) NOT NULL,
  `achieved_target` varchar(11) NOT NULL,
  `target_set_by` int(11) NOT NULL,
  `target_set_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `target_achieved_percentage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `sales_target_details` (`stdid`, `teamid`, `target_amount`, `achieved_target`, `target_set_by`, `target_set_on`, `target_achieved_percentage`) VALUES
(1, 129, '250000', '150000', 1, '2019-01-24 09:18:50', 60);


ALTER TABLE `sales_target_details`
  ADD PRIMARY KEY (`stdid`);

ALTER TABLE `sales_target_details`
  MODIFY `stdid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;



DELIMITER $$
DROP PROCEDURE IF EXISTS `get_team_sales_dashboard_details`$$
CREATE PROCEDURE `get_team_sales_dashboard_details`(
    IN userloginIdParam INT,
    IN currentMonthParam INT,
    IN currentDateParam DATE
)
BEGIN
    DECLARE leads_given INT;
    DECLARE leades_recieved INT;
    DECLARE team_incentives INT;
    DECLARE incentiveval INT;
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

		SELECT COUNT(DISTINCT('company_name')) INTO leads_given 
		FROM sales_pipeline
		WHERE teamid_creator = userloginIdParam  AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

		SELECT COUNT(DISTINCT('company_name')) INTO leades_recieved 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

		SELECT COUNT(DISTINCT('company_name')) INTO customerInPipeline 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity IN(1,2,3) 
		AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

		SELECT COUNT(DISTINCT('company_name')) INTO customerInPipeline_cold
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity = 1
		AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

		SELECT COUNT(DISTINCT('company_name')) INTO customerInPipeline_warm
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity = 2
		AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

		SELECT COUNT(DISTINCT('company_name')) INTO customerInPipeline_hot
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND tepidity = 3
		AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

		SELECT COUNT(DISTINCT('company_name')) INTO customer_won 
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND stageid = 8 AND MONTH(`timestamp`) = currentMonthParam AND DATE(`timestamp`) = currentdateParam;

		SELECT `timestamp` INTO customer_won_timestamp
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND stageid = 8 ORDER BY `timestamp` DESC LIMIT 1;

		SELECT `timestamp` INTO customer_Added_timestamp
		FROM sales_pipeline
		WHERE teamid = userloginIdParam AND stageid = 7 ORDER BY `timestamp` ASC LIMIT 1 ;

		SELECT incentives into incentiveval
		FROM sales_configuration_settings; 

		IF(achieved_amount != 0) THEN
		SET team_incentives = achieved_amount*(incentiveval/100);
		END IF;

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

UPDATE dbpatches SET 
updatedOn = NOW()
,isapplied = 1 WHERE patchid = 654;