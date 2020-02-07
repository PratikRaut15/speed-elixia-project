INSERT INTO `speed`.`dbpatches` (
    `patchid` ,
    `patchdate` ,
    `appliedby` ,
    `patchdesc` ,
    `isapplied`)
VALUES ('519', '2017-07-03 14:04:00','Arvind Thakur','Pull My Ticket API changes', '0');


DELIMITER $$
DROP PROCEDURE IF EXISTS pullMyTicket$$
CREATE PROCEDURE pullMyTicket(
    IN teamidParam INT(11)
)

BEGIN
 
    select 	* 
    from 	(select  (CASE  WHEN stde.status=1 THEN 'Inprogress' 
                                WHEN stde.status= 2 THEN 'Closed' 
                                WHEN stde.status= 3 THEN 'Pipeline' 
                                WHEN stde.status= 4 THEN 'On Hold' 
                                WHEN stde.status= 5 THEN 'Waiting for client' 
                                WHEN stde.status= 6 THEN 'Resolved' 
                                WHEN stde.status= 7 THEN 'Reopen' 
                                ELSE 'Open' END)as ticketstatus
                            , stde.uid
                            , st.ticketid
                            , st.title
                            , st.ticket_type
                            , sttype.tickettype
                            , st.sub_ticket_issue
                            , st.customerid
                            , st.eclosedate
                            , st.priority
                            , sp.priority as prname
                            , st.create_on_date
                            , st.create_by
                            , stde.status
                            , stde.allot_to 
                            , stde.description
                    from 	sp_ticket_details stde 
            left join sp_ticket as st on st.ticketid = stde.ticketid 
            left join sp_tickettype as sttype on sttype.typeid = st.ticket_type 
            left join sp_priority as sp on sp.prid = st.priority   
            order by stde.uid desc ) as main 
    group by main.ticketid having main.allot_to = teamidParam 
    AND 	main.create_by<> -1 
    AND 	main.status <> 2 
    order by main.eclosedate asc, main.priority asc, main.ticketid asc;

END$$
DELIMITER ;

UPDATE  dbpatches
SET     patchdate = NOW()
        ,isapplied = 1
WHERE   patchid = 519;