   
DELIMITER $$
DROP PROCEDURE IF EXISTS `get_daily_sales_report_data`$$
CREATE  PROCEDURE `get_daily_sales_report_data`(
        IN useridParam INT(20),
        IN datefromparam VARCHAR(50),
        IN datetoparam VARCHAR(50)
)
BEGIN
 
      /* GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
      @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
      SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
      SELECT @full_error; */

            SELECT 
                sp.pipelineid,
                sp.pipeline_history_id ,
                sp.company_name ,
                `sales_product`.product_name ,
            COALESCE(t.name,'N.A.') as name ,
            s.stage_name AS newstage ,
            COALESCE(sh.stage_name,'New Pipeline') AS oldstage ,
            sp.timestamp ,COALESCE(t.name,'N.A.') as name ,
            sp.remarks ,sph.stageid ,COALESCE(tc.name,'N.A.') AS tcreator 
            FROM `sales_pipeline_history` sp 
            LEFT JOIN `sales_pipeline_history` sph 
            ON sph.pipeline_history_id = (SELECT max(pipeline_history_id) FROM sales_pipeline_history 
            WHERE pipeline_history_id < sp.pipeline_history_id AND pipelineid = sp.pipelineid) 
            LEFT JOIN `sales_stage` s ON s.stageid = sp.stageid 
            LEFT JOIN `sales_stage` sh ON sh.stageid = sph.stageid 
            LEFT JOIN `sales_product` ON `sales_product`.productid = sp.productid 
            LEFT JOIN elixiatech.`team` t ON t.teamid = sp.teamid 
            LEFT JOIN elixiatech.`team` tc ON tc.teamid = sp.teamid_creator 
            WHERE DATE(sp.timestamp) BETWEEN datefromparam AND datetoparam
                AND t.teamid= useridParam
            ORDER BY sp.teamid ASC, sp.timestamp desc;

END$$

DELIMITER ;

--CALL get_daily_sales_report_data('2018-12-01,2018-12-30');
