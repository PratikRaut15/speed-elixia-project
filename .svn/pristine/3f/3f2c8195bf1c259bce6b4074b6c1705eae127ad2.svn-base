INSERT INTO `uat_speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'549', '2018-03-20 12:30:00', 'Yash Kanakia', 'Changes in docket module in team', '0'
);


CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
INSERT INTO `role` VALUES (1,'Head'),(2,'Admin'),(3,'Service'),(4,'Others');


CREATE TABLE `department` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT,
  `department` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
INSERT INTO `department` VALUES (1,'Software'),(2,'Operations'),(3,'Accounts'),(4,'Sales'),(5,'CRM'),(6,'Field Engineers'),(7,'Others');


DELIMITER $$
DROP procedure IF EXISTS `fetchBucketsForDocket`$$
CREATE PROCEDURE `fetchBucketsForDocket`(
IN  docketIdParam INT
)
BEGIN
    SELECT b.bucketid, b.apt_date, b.created_by, b.purposeid as purpose_id, b.status as statusid, t.teamid, t.name,b.priority,
    (CASE
        WHEN b.status=1
        THEN 'Rescheduled'
        WHEN b.status=2
        THEN 'Successful'
        WHEN b.status=3
        THEN 'Unsuccessful'
        WHEN b.status=4
        THEN 'FE Assigned'
        WHEN b.status=5
        THEN 'Cancelled'
        WHEN b.status=6
        THEN 'Incomplete'
    END) as status,
    (CASE
        WHEN b.purposeid=1
        THEN 'Installation'
        WHEN b.purposeid=2
        THEN 'Repair'
        WHEN b.purposeid=3
        THEN 'Removal'
        WHEN b.purposeid=4
        THEN 'Replacement'
        WHEN b.purposeid=5
        THEN 'Reinstall'
    END) as purposeid,
    (CASE
        WHEN b.priority=1
        THEN 'High'
        WHEN b.priority=2
        THEN 'Medium'
        WHEN b.priority=1
        THEN 'Low'
    END)as priority
    from speed.bucket b
    LEFT JOIN team t on b.created_by = t.teamid
    where docketid = docketIdParam;
END$$
DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetchTicketsForDocket`$$
CREATE PROCEDURE `fetchTicketsForDocket`(
IN  docketIdParam INT
)
BEGIN
    SELECT
            st.ticketid,
            st.title,st.ticket_type,
            st.docketid,
            sttype.tickettype,sp.priority as prname,
            st.sub_ticket_issue,
            st.priority as priorityid,
            t.name AS allot_to,
            std.status,
            std.allot_to,
            st.create_on_date,
            sp.priority,
            ts.status as ticketStatus,
            st.eclosedate
        FROM sp_ticket as st
        INNER JOIN  sp_ticket_details std ON std.ticketid = st.ticketid
        AND std.uid = (SELECT MAX(uid) from sp_ticket_details sds where sds.ticketid=st.ticketid)
        LEFT JOIN   team t ON t.teamid = std.allot_to
        LEFT JOIN   sp_tickettype as sttype on sttype.typeid = st.ticket_type
        LEFT JOIN   sp_priority as sp on sp.prid = st.priority
        LEFT JOIN ticket_status ts ON ts.id = std.status
        WHERE (st.docketid = docketIdParam OR docketIdParam IS NULL)
        GROUP BY    std.ticketid
        order by    st.ticketid ASC;
END$$
DELIMITER ;



