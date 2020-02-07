INSERT INTO speed.dbpatches (patchid, patchdate, appliedby, patchdesc, isapplied) 
VALUES ('561', '2018-05-18 18:46:18', 'Kartik Joshi', 'Sales pipeline enhancements', '0');

UPDATE `elixiatech`.`team` SET `department_id`='8' WHERE `department_id`='7';
UPDATE `elixiatech`.`department` SET `department_id` = '8' WHERE `department`='Others';

INSERT INTO `elixiatech`.`department` (`department_id`,`department`) VALUES ('7','Management');

UPDATE `elixiatech`.`team` SET `department_id`='7' WHERE `teamid`='1';
UPDATE `elixiatech`.`team` SET `department_id`='7' WHERE `teamid`='73';
UPDATE `elixiatech`.`team` SET `department_id`='7' WHERE `teamid`='43';

DELIMITER $$
DROP procedure IF EXISTS `revive_pipeline`$$
CREATE PROCEDURE `revive_pipeline`(
	IN pipelineIdParam INT,
    IN updatePlatformParam TINYINT,
    IN todayParam DATETIME
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			/*ROLLBACK;
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
			@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
			SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
			SELECT @full_error;  
			SET isexecutedOut = 0;*/
		END;
       
		START TRANSACTION;
		BEGIN
			INSERT INTO `speed`.`sales_pipeline_history`
            (`pipelineid`,
			`pipeline_date`,
			`company_name`,
			`tepidity`,
			`sourceid`,
			`productid`,
			`industryid`,
			`modeid`,
			`teamid`,
			`location`,
			`remarks`,
			`stageid`,
			`revive_date`,
			`loss_reason`,
			`quantity`,
			`device_cost`,
			`subscription_cost`,
			`quotation_request`,
			`quotation_text`,
			`quotationDetails`,
			`create_platform`,
			`update_platform`,
			`delete_platform`,
			`timestamp`,
			`isdeleted`,
			`teamid_creator`)
			
			SELECT `pipelineid`,
			`pipeline_date`,
			`company_name`,
			`tepidity`,
			`sourceid`,
			`productid`,
			`industryid`,
			`modeid`,
			`teamid`,
			`location`,
			`remarks`,
			'11',
			`revive_date`,
			`loss_reason`,
			`quantity`,
			`device_cost`,
			`subscription_cost`,
			`quotation_request`,
			`quotation_text`,
			`quotationDetails`,
			`create_platform`,
			`update_platform`,
			`delete_platform`,
			todayParam,
			`isdeleted`,
			`teamid_creator` FROM `speed`.`sales_pipeline` WHERE pipelineid = pipelineIdParam LIMIT 1;

            SET @historyId = LAST_INSERT_ID();
            
            UPDATE `speed`.`sales_pipeline`
			SET
			`stageid` = 11,
			`update_platform` = updatePlatformParam,
			`timestamp` = todayParam
			WHERE `pipelineid` = pipelineIdParam;

		END;
		COMMIT;
END$$
DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `autoFreeze_pipeline`$$
CREATE PROCEDURE `autoFreeze_pipeline`(
	IN pipelineIdParam INT,
    IN updatePlatformParam TINYINT,
    IN todayParam DATETIME
)
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			/*ROLLBACK;
			GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
			@errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
			SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
			SELECT @full_error;  
			SET isexecutedOut = 0;*/
		END;
       
		START TRANSACTION;
		BEGIN
			INSERT INTO `speed`.`sales_pipeline_history`
            (`pipelineid`,
			`pipeline_date`,
			`company_name`,
			`tepidity`,
			`sourceid`,
			`productid`,
			`industryid`,
			`modeid`,
			`teamid`,
			`location`,
			`remarks`,
			`stageid`,
			`revive_date`,
			`loss_reason`,
			`quantity`,
			`device_cost`,
			`subscription_cost`,
			`quotation_request`,
			`quotation_text`,
			`quotationDetails`,
			`create_platform`,
			`update_platform`,
			`delete_platform`,
			`timestamp`,
			`isdeleted`,
			`teamid_creator`)
			
			SELECT `pipelineid`,
			`pipeline_date`,
			`company_name`,
			`tepidity`,
			`sourceid`,
			`productid`,
			`industryid`,
			`modeid`,
			`teamid`,
			`location`,
			`remarks`,
			'12',
			`revive_date`,
			`loss_reason`,
			`quantity`,
			`device_cost`,
			`subscription_cost`,
			`quotation_request`,
			`quotation_text`,
			`quotationDetails`,
			`create_platform`,
			updatePlatformParam,
			`delete_platform`,
			todayParam,
			`isdeleted`,
			`teamid_creator` FROM `speed`.`sales_pipeline` WHERE pipelineid = pipelineIdParam LIMIT 1;

            SET @historyId = LAST_INSERT_ID();
            
            UPDATE `speed`.`sales_pipeline`
			SET
			`stageid` = 12,
			`update_platform` = updatePlatformParam,
			`timestamp` = todayParam
			WHERE `pipelineid` = pipelineIdParam;

		END;
		COMMIT;
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetchSRStats`$$
CREATE PROCEDURE `fetchSRStats`(
	IN teamIdParam INT,
    IN weekStartParam DATETIME,
    IN monthStartParam DATETIME,
    IN todayParam DATETIME
)
BEGIN
	SELECT t.name,

	(SELECT count(*) from speed.sales_pipeline sp1 WHERE stageid  IN (9,10) AND timestamp <= todayParam AND timestamp >= weekStartParam
		AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as FrozenWeek,
	(SELECT count(*) from speed.sales_pipeline sp1 WHERE stageid IN (9,10) AND timestamp <= todayParam AND timestamp >= monthStartParam
	AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as FrozenMonth,  
    
	(SELECT count(*) from speed.sales_pipeline sp1 WHERE stageid IN (8) AND timestamp <= todayParam AND timestamp >= weekStartParam
		AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as WonWeek,
	(SELECT count(*) from speed.sales_pipeline sp1 WHERE stageid IN (8) AND timestamp <= todayParam AND timestamp >= monthStartParam
	AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as WonMonth,
    
    (SELECT count(*) from speed.sales_pipeline sp1 WHERE stageid IN (1) AND timestamp <= todayParam AND timestamp >= weekStartParam
		AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as NewWeek,
	(SELECT count(*) from speed.sales_pipeline sp1 WHERE stageid IN (1) AND timestamp <= todayParam AND timestamp >= monthStartParam
	AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as NewMonth,
    
    (SELECT count(*) from speed.sales_pipeline sp1 WHERE stageid IN (13)
		AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as Demo,
	(SELECT count(*) from speed.sales_pipeline sp1 WHERE stageid IN (12) AND timestamp <= todayParam AND timestamp >= weekStartParam
		AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as SAWeek,
	(SELECT count(*) from speed.sales_pipeline sp1 WHERE stageid IN (12) AND timestamp <= todayParam AND timestamp >= monthStartParam
	AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as SAMonth,
	(SELECT count(*) from speed.sales_pipeline sp1 WHERE tepidity = 1 AND stageid NOT IN (8,9,10,12)
	AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as HotLeads,
    (SELECT count(*) from speed.sales_pipeline sp1 WHERE tepidity = 2 AND stageid NOT IN (8,9,10,12)
	AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as WarmLeads,
    (SELECT count(*) from speed.sales_pipeline sp1 WHERE tepidity = 3 AND stageid NOT IN (8,9,10,12)
	AND sp1.teamid = outside.teamid AND sp1.isdeleted=0) as ColdLeads
    
FROM speed.sales_pipeline outside
LEFT JOIN elixiatech.team t ON t.teamid = outside.teamid
WHERE outside.teamid <>0 AND (outside.teamid = teamIdParam OR teamIdParam = 0)
GROUP BY t.teamid;
    
END$$

DELIMITER ;



ALTER TABLE speed.sales_pipeline MODIFY COLUMN create_platform TINYINT DEFAULT 1 AFTER subscription_cost ;
ALTER TABLE speed.sales_pipeline MODIFY COLUMN update_platform TINYINT DEFAULT 1 AFTER create_platform ;
ALTER TABLE speed.sales_pipeline MODIFY COLUMN delete_platform TINYINT DEFAULT 1 AFTER update_platform ;

ALTER TABLE speed.sales_pipeline_history MODIFY COLUMN create_platform TINYINT DEFAULT 1 AFTER subscription_cost ;
ALTER TABLE speed.sales_pipeline_history MODIFY COLUMN update_platform TINYINT DEFAULT 1 AFTER create_platform ;
ALTER TABLE speed.sales_pipeline_history MODIFY COLUMN delete_platform TINYINT DEFAULT 1 AFTER update_platform ;

UPDATE speed.dbpatches SET isapplied=1 WHERE patchid = 561;