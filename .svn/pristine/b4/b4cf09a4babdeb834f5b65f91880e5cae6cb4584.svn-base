/*
    Name		-	pullMyTicket
    Description 	-	Pull all ticket assign to teamid.
    Parameters		-	
    Module		-	Team
    Sub-Modules 	- 	No
    Sample Call		-	CALL pullMyTicket(50)
    Created by		-	Arvind
    Created on		- 	06 April, 2017
    Change details 	-	
    1) 	Updated by	- 	Arvind Thakur
	Updated	on	- 	23 July, 2017
        Reason		-	
*/


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