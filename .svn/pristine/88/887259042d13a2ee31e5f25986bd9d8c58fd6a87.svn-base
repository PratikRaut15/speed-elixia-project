INSERT INTO `uat_speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'541', '2018-02-12 17:00:00', 'Kartik Joshi', 'Changes in support module in team', '0'
);

DELIMITER $$
DROP procedure IF EXISTS `get_products`$$
CREATE PROCEDURE `get_products`()
BEGIN
    SELECT prodId,prodName FROM elixiaOneProductMaster;
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `get_tickets_team`$$
CREATE PROCEDURE `get_tickets_team`(
    IN createByParam INT
)
BEGIN
    select  (CASE   WHEN stde.status=1  THEN 'Inprogress'
                                   WHEN stde.status= 2 THEN 'Closed'
                                   WHEN stde.status= 3 THEN 'Pipeline'
                                   WHEN stde.status= 4 THEN 'On Hold'
                                   WHEN stde.status= 5 THEN 'Waiting For Client'
                                   WHEN stde.status= 6 THEN 'Resolved'
                                   WHEN stde.status= 7 THEN 'Reopen'
                                   ELSE 'Open' END) as ticketstatus
                           ,stde.uid
                           ,st.ticketid
                           ,stde.ticketid
                           ,st.title
                           ,st.ticket_type
                           ,sttype.tickettype as tickettypename
                           ,st.sub_ticket_issue
                           ,st.customerid
                           ,st.eclosedate
                           ,st.prodId
                           ,sp.priority
                           ,st.create_on_date
                           ,st.create_by
                           ,stde.create_by as closeby
                           ,stde.created_type
                           ,stde.allot_to
                           ,stde.allot_from
                   from    (   SELECT  t1.*
                                       ,t1.create_by as closeby
                               FROM    sp_ticket_details t1
                               WHERE   t1.uid = (  SELECT  t2.uid
                                                   FROM    sp_ticket_details t2
                                                   WHERE t2.ticketid = t1.ticketid
                                                   ORDER BY `t2`.`uid`  DESC
                                                   LIMIT 1)
                               ORDER BY t1.uid DESC ) stde
                   left join sp_ticket as st on st.ticketid = stde.ticketid
                   left join sp_tickettype as sttype on sttype.typeid = st.ticket_type
                   left join sp_priority as sp on sp.prid= st.priority
                   where   stde.closeby = createByParam and  stde.created_type <> 1
                   group by stde.ticketid
                   order by stde.status asc;
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `update_ticket_team`$$
CREATE PROCEDURE `update_ticket_team`(
    IN customerNoParam INT
    ,IN priorityParam INT
    ,IN sendMailParam INT
    ,IN ecloseDateParam DATE
    ,IN addCountParam INT
    ,IN ticketIdParam INT
    ,IN ticketDescParam VARCHAR(255)
	,IN allotFromParam INT
    ,IN allotToParam INT
    ,IN ticketStatusParam INT
    ,IN createdByParam INT
    ,IN createdOnTimeParam DATETIME
    ,IN createdTypeParam INT
    ,OUT ticketIdOut INT   
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        /*
        GET DIAGNOSTICS CONDITION 1 @sqlstate = RETURNED_SQLSTATE,
        @errno = MYSQL_ERRNO, @text = MESSAGE_TEXT;
        SET @full_error = CONCAT("ERROR ", @errno, " (", @sqlstate, "): ", @text);
        SELECT @full_error;
        */
    END;
    START TRANSACTION;
        IF (customerNoParam = '' OR customerNoParam = 0) THEN
            SET customerNoParam = NULL;
             
        END IF;       
        IF (customerNoParam IS NOT NULL ) THEN
			update sp_ticket 
            set priority=priorityParam , send_mail_status=sendMailParam ,customerid=customerNoParam ,eclosedate=ecloseDateParam ,eclosedate_chng_count = eclosedate_chng_count + addCountParam 
            where ticketid=ticketIdParam;
			IF (createdTypeParam=0) THEN
				INSERT INTO `sp_ticket_details`(`ticketid`, `description`, `allot_from`, `allot_to`, `status`, `create_by`, `create_on_time`) 
					VALUES (ticketIdParam, ticketDescParam, allotFromParam ,allotToParam, ticketStatusParam,createdByParam,createdOnTimeParam);
			ELSE
				INSERT INTO `sp_ticket_details`(`ticketid` ,`description`,`allot_from`,`allot_to`,`status`,`create_by`,`create_on_time`,`eclosedate`,`created_type`)
					VALUES (ticketIdParam, ticketDescParam,allotFromParam ,allotToParam, ticketStatusParam,createdByParam,createdOnTimeParam,ecloseDateParam,createdTypeParam);
			END IF;
        END IF;		
        SET ticketIdOut = ticketIdParam;
    COMMIT;
END$$
DELIMITER ;


UPDATE  uat_speed.dbpatches
SET isapplied =1
WHERE   patchid = 541;