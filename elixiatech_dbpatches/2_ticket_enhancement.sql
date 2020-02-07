INSERT INTO `elixiatech`.`dbpatches` VALUES(2,'2018-08-03 12:16:00','Kartik','Recommitting fetch_ticket SP due to overwriting',0);

DELIMITER $$
DROP procedure IF EXISTS `fetch_tickets`$$
CREATE  PROCEDURE `fetch_tickets`(
  IN docketIdParam INT,
  IN ticketIdParam INT
)
BEGIN
  if(docketIdParam=0) THEN
    select st.ticketid
          ,st.title
          ,st.ticket_type
          ,sttype.tickettype
          ,sp.priority as prname
          ,st.sub_ticket_issue
          ,st.docketid
          ,st.customerid
          ,(SELECT customercompany from customer where customerno=st.customerid) as customercompany
          ,st.priority
          ,DATE_FORMAT(st.create_on_date ,'%d-%m-%Y') AS create_on_date
          ,st.create_by
          ,st.created_type
          ,st.uid
          ,st.send_mail_to
          ,st.create_platform
          ,t.name AS allot_to
          ,std.description
          ,std.status
          ,(SELECT team.name from team where team.teamid = std.allot_to) AS allot_to
          ,ts.status as ticketStatus
          ,DATE_FORMAT(st.eclosedate,'%d-%m-%Y') AS eclosedate
          ,DATE_FORMAT(st.estimateddate,'%d-%m-%Y') AS estimateddate
          ,std.uid
          ,(SELECT e.prodName from elixiaOneProductMaster e where e.prodId = st.prodId) AS prod_id
          ,st.send_mail_cc
          ,std.add_charge
          ,c.charge_id
          ,c.description AS chargeDescription
          ,c.amount AS chargeAmount
        FROM sp_ticket as st
        INNER JOIN  sp_ticket_details std ON std.ticketid = st.ticketid
		AND std.uid = (SELECT MAX(uid) from sp_ticket_details sds where sds.ticketid=st.ticketid)
        LEFT JOIN   team t ON t.teamid = std.allot_to
        LEFT JOIN   sp_tickettype as sttype on sttype.typeid = st.ticket_type
        LEFT JOIN   sp_priority as sp on sp.prid = st.priority
        LEFT JOIN ticket_status ts ON ts.id = std.status
        LEFT JOIN additional_charges c ON c.ticketid = ticketIdParam
        WHERE (st.ticketid = ticketIdParam OR ticketIdParam IS NULL)
        GROUP BY    std.ticketid
        order by    st.ticketid ASC;
  ELSE
    select st.ticketid
          ,st.title
          ,st.ticket_type
		  ,st.docketid
          ,sttype.tickettype
          ,sp.priority as prname
          ,st.sub_ticket_issue
          ,st.customerid
          ,(SELECT customercompany from customer where customerno=st.customerid) as customercompany
          ,st.priority
          ,DATE_FORMAT(st.create_on_date ,'%d-%m-%Y') AS create_on_date
          ,DATE_FORMAT(st.estimateddate,'%d-%m-%Y') AS estimateddate
          ,st.send_mail_cc
          ,st.create_by
          ,st.created_type
          ,st.uid
		  ,st.send_mail_to
          ,st.create_platform
          ,t.name AS allot_to
		  ,std.description
          ,std.status
          ,(SELECT team.name from team where team.teamid = std.allot_to) AS allot_to
          ,ts.status as ticketStatus
          ,DATE_FORMAT(st.eclosedate,'%d-%m-%Y') AS eclosedate
		  ,std.uid
          ,(SELECT e.prodName from elixiaOneProductMaster e where e.prodId = st.prodId) AS prod_id
          ,DATE_FORMAT(st.estimateddate,'%d-%m-%Y') AS estimateddate
          ,std.add_charge
          ,std.add_charge
          ,c.charge_id
          ,c.description AS chargeDescription
          ,c.amount AS chargeAmount
        FROM sp_ticket as st
        INNER JOIN  sp_ticket_details std ON std.ticketid = st.ticketid
		AND std.uid = (SELECT MAX(uid) from sp_ticket_details sds where sds.ticketid=st.ticketid)
        LEFT JOIN   team t ON t.teamid = std.allot_to
        LEFT JOIN   sp_tickettype as sttype on sttype.typeid = st.ticket_type
        LEFT JOIN   sp_priority as sp on sp.prid = st.priority
        LEFT JOIN ticket_status ts ON ts.id = std.status
        LEFT JOIN additional_charges c ON c.ticketid = st.ticketid
        WHERE (st.docketid = docketIdParam OR docketIdParam IS NULL)
        GROUP BY    std.ticketid
        order by    st.ticketid ASC;
    END IF;
END$$
DELIMITER ;

UPDATE `elixiatech`.`dbpatches` SET isapplied = 1 WHERE patchid = 2;
