INSERT INTO `uat_speed`.`dbpatches` (
`patchid` ,
`patchdate` ,
`appliedby` ,
`patchdesc` ,
`isapplied`
)
VALUES (
'550', '2018-04-02 12:30:00', 'Yash Kanakia', 'Changes in docket module in team', '0'
);


DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
);
INSERT INTO `role` VALUES (1,'Head'),(2,'Admin'),(3,'Service'),(4,'Others');


DROP TABLE IF EXISTS `sp_ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sp_ticket` (
  `ticketid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `ticket_type` int(11) NOT NULL,
  `sub_ticket_issue` varchar(100) NOT NULL,
  `estimateddate` datetime DEFAULT NULL,
  `timeslot` int(11) NOT NULL,
  `vehicleid` varchar(255) NOT NULL,
  `customerid` int(11) NOT NULL,
  `eclosedate` datetime DEFAULT NULL,
  `send_mail_status` tinyint(1) NOT NULL DEFAULT '0',
  `send_mail_to` varchar(255) NOT NULL,
  `send_mail_cc` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `raised_on_date` datetime NOT NULL,
  `create_on_date` datetime NOT NULL,
  `create_by` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `created_type` tinyint(1) NOT NULL DEFAULT '0',
  `crmid` int(11) NOT NULL,
  `eclosedate_chng_count` int(11) NOT NULL,
  `create_platform` tinyint(4) DEFAULT '1',
  `prodId` int(11) NOT NULL,
  `docketid` int(11) DEFAULT NULL,
  PRIMARY KEY (`ticketid`)
) ENGINE=InnoDB AUTO_INCREMENT=1027 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



DROP TABLE IF EXISTS `sp_tickettype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sp_tickettype` (
  `typeid` int(11) NOT NULL AUTO_INCREMENT,
  `tickettype` varchar(100) NOT NULL,
  `isdeleted` tinyint(1) NOT NULL DEFAULT '0',
  `issue_type` tinyint(2) DEFAULT NULL,
  `d_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `sp_tickettype` VALUES (1,'Accounts',0,1,3),(2,'Operations - New Installation',0,1,2),(3,'Operations - Replacement',0,1,2),(4,'Operations - Reinstallation',0,1,2),(5,'Operations - Repair',0,1,2),(6,'Operations - Removal',0,1,2),(7,'Sales',0,1,4),(8,'Software - Bug',0,1,1),(9,'Software - Enhancement',0,1,1),(10,'Others',0,1,7),(11,'Investigation',0,1,7);


DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `teamid` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `member_type` tinyint(1) NOT NULL DEFAULT '0',
  `distributor_id` int(11) NOT NULL DEFAULT '0',
  `address` varchar(255) NOT NULL,
  `userkey` varchar(150) NOT NULL,
  `management_points` int(11) NOT NULL DEFAULT '0',
  `d_id` int(11) DEFAULT NULL,
  `is_deleted` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`teamid`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `team` VALUES (1,0,'Sanket Sheth','9619521206','sanketsheth@elixiatech.com','sanketsheth','bvfr456YHN','Head',1,0,'','2271',0,7,0,1),(5,0,'Dharmendra','9820445271','operations@elixiatech.com','dharmendra','dharmendra','CRM',1,0,'','',0,6,0,4),(6,0,'Dinesh Tulaskar','8976458215','operations@elixiatech.com','Dinesh','Dinesht2605','Head',1,0,'','2172',0,2,0,1),(9,0,'Alam','9004884658','operations@elixiatech.com','alam_elixia','alam_elixia','CRM',1,0,'','',0,7,1,4),(11,0,'Dinesh Joil','9619726699','operations@elixiatech.com','dineshjoil','dineshjoil','CRM',1,0,'','',0,6,0,4),(14,0,'Abhijit Thakur','8655378957','abhijitt@elixiatech.com','abhijit_elixia','abhijit_elixia','Sales',1,0,'','',0,7,1,4),(15,0,'Ashish Thakur','9820446093','ashisht@elixiatech.com','ashish_elixia','ashish_elixia','Sales',1,0,'','1234',0,7,1,4),(18,0,'Courier','','','courier_elixia','courier_elixia','CRM',2,0,'','',0,7,0,4),(22,1,'Navneet Shetty','9769387950','support@elixiatech.com','navanethshetty','navanethshetty','CRM',1,0,'','',0,5,0,3),(26,0,'Haseeb Khan','7039166961','operations@elixiatech.com','Hasid','Hasid','CRM',1,0,'','',0,6,0,4),(27,0,'Shachi Sheth','9833894843','shachibhayani@gmail.com','shachi','sovereign','Head',1,0,'','',0,7,0,1),(28,2,'Altaf Shaikh','9821982123','support@elixiatech.com','altaf','sohail','Head',1,0,'','5950',0,5,0,1),(31,3,'Parikshit Anjarlekar','9967242469','support@elixiatech.com','anjarlekar','123456','CRM',1,0,'','',0,5,0,3),(32,0,'Sachin Jangam','8692089822','operations@elixiatech.com','sachinj','rupesh9585','Head',1,0,'','6567',0,2,0,1),(37,0,'Shrikant Suryawanshi','9421377403','software@elixiatech.com','sshrikanth','sshrikanth','Serivce',1,0,'','1239',0,1,0,3),(39,0,'Ganesh Papde','9870288657','software@elixiatech.com','ganeshp','ganeshp','CRM',1,0,'','',0,7,1,4),(40,0,'Sahil Gandhi','9867093436','sahilg@elixiatech.com','sahilg','sahilg','CRM',1,0,'','',0,7,1,4),(43,0,'Mukund Sheth','9821554022','mukundsheth2@gmail.com','mukund','mukund','Head',1,0,'','',0,7,0,1),(45,0,'Nafees Khan','7039383711','operations@elixiatech.com','nafis','nafis','CRM',1,0,'','',0,6,0,4),(48,0,'Harshal Khambalkar','9225436846','accounts@elixiatech.com','harshalk','harshalk','Admin',1,0,'','',0,7,1,4),(49,0,'Mohini Karane','9967219337','accounts@elixiatech.com','mohinik','mohinik','Admin',1,0,'','',0,7,1,4),(50,0,'Mrudang Vora','9969941084','mrudang.vora@elixiatech.com','mrudang','mrudang','Head',1,0,'','9151',0,1,0,1),(51,0,'Sagar Mandavkar','9220042108','operations@elixiatech.com','sagar','sagar','CRM',1,0,'','',0,7,0,4),(55,0,'Ankush','7709031458','operations@elixiatech.com','ankush','ankush','CRM',1,0,'','',0,7,0,4),(57,0,'Uday Tambe','9321738792','operations@elixiatech.com','udayk','udayk','CRM',1,0,'','',0,7,0,4),(59,0,'Anis Kansara','8200290207','operations@elixiatech.com','anis','anis','CRM',1,0,'','8711',0,7,0,4),(60,0,'Anees Khan','7045426038','operations@elixiatech.com','hanish','hanish','CRM',1,0,'','',0,6,0,4),(62,0,'Usman ','8108606200','operations@elixiatech.com','usman','usman','CRM',1,0,'','',0,6,0,4),(66,0,'Ganesh  Jadhav','7875757937','operations@elixiatech.com','ganeshj','ganeshj','CRM',2,0,'','',0,7,0,4),(67,0,'Riyaz','8286380816','operations@elixiatech.com','riyaz','riyaz','CRM',1,0,'','',0,7,1,4),(69,0,'Jyoti Sahu','8286005026','accounts@elixiatech.com','jyotis','jyotis','Admin',1,0,'','',0,7,1,4),(71,0,'Bindi vibhakar','9619516677','bindiv@elixiatech.com','bindiv','bindiv','CRM',1,0,'','',0,7,1,4),(72,0,'Vishal Waghmare','9757187722','vishalw@elixiatech.com','vishalw','vishalw','Sales',1,0,'','',0,7,1,4),(73,0,'Mihir Ravani','9820278032','mihir@elixiatech.com','mihir','mihir23','Head',1,0,'','120',0,7,0,1),(74,0,'Swapnali Thakur','8879971946','operations@elixiatech.com','royal','royal','CRM',2,0,'','',0,7,1,4),(75,0,'Dinesh Deshmukh','9821360630','operations@elixiatech.com','dineshd','dineshd','Head',1,0,'','',0,7,1,1),(76,4,'Rinky Singh','9833783986','support@elixiatech.com','rinkys','rinkys','CRM',1,0,'','',0,5,0,3),(77,0,'Pradeep Singh','8527631620','operations@elixiatech.com','pradeep','pradeep','CRM',2,0,'','',0,7,0,4),(78,0,'Dinkar Waghmare','9860788452','operationselixia@yahoo.in','dinkarw','dinkarw','CRM',1,0,'','',0,7,1,4),(79,0,'Mukesh  Verma','8085111325','operations@elixiatech.com','mukeshv','mukeshv','CRM',2,0,'','',0,7,0,4),(80,0,'Naresh Royal Hazira','9879109519','operations@elixiatech.com','naresh','naresh','CRM',2,0,'','',0,7,0,4),(81,0,'Nikunj patel Vapi','9904133122','operations@elixiatech.com','nikunj','nikunj','CRM',2,0,'','',0,7,0,4),(82,0,'Suresh Goa','9822158407','operations@elixiatech.com','suresh','suresh','CRM',2,0,'','',0,7,0,4),(83,0,'Sohail','9900985728','operationselixia@yahoo.in','sohail','sohail','CRM',2,0,'','',0,7,1,4),(84,0,'Yathesh prewa','9071059996','operationselixia@yahoo.in','yathesh','yathesh','CRM',2,0,'','',0,7,1,4),(85,0,'Sudhakar','9912850507','operationselixia@yahoo.in','sudhakar','sudhakar','CRM',2,0,'','',0,7,1,4),(86,0,'Laxman Jadhav','9167234481','operations@elixiatech.com','Laxmanj','Laxmanj','CRM',2,0,'','',0,7,0,4),(87,0,'Satish Kumar Ranchi','9470856261','operations@elixiatech.com','satishk','satishk','CRM',2,0,'','',0,7,0,4),(88,0,'Prashant Patil','9028844385','operations@elixiatech.com','prashant','prashant','CRM',1,0,'','4351',0,7,1,4),(89,0,'Ganesh Thakur ','8425029057','operations@elixiatech.com','ganesht','ganesht','CRM',1,0,'','',0,6,0,4),(90,0,'Debashish ( Kolkatta)','7029576638','operations@elixiatech.com','debashish','debashish123','Repair',2,0,'','',0,7,0,4),(91,0,'Salim R.K.Batra','7666567733','operations@elixiatech.com','salimrk','salimrk','CRM',2,0,'','',0,7,0,4),(92,0,'Vishal Royal jasai ','9920048545','operations@elixiatech.com','vishalr','vishalr','CRM',2,0,'','',0,7,0,4),(94,0,'Pritam Kumar Bhowmick','8906691293','operations@elixiatech.com','pritam','pritam','CRM',2,0,'','',0,7,0,4),(95,0,'Ranjan Aachrya ','9874444110','operationselixia@yahoo.com','ranjan','ranjan','CRM',2,0,'','',0,7,0,4),(96,0,'Prashant Kadam','9969783639','operationselixia@yahoo.com','pkadam','pkadam','CRM',2,0,'','',0,7,0,4),(97,0,'Prafulla Kumar Odisa','9438213556','operationselixia@yahoo.com','prafullakumar','prafullak','CRM',2,0,'','',0,7,0,4),(98,0,'Ashok Fortoint Kolkatta','8017663977','operationselixia@yahoo.com','ashok','ashok','CRM',2,0,'','',0,7,0,4),(99,0,'Ganesh Delhi Woodword','9911539502','operationselixia@yahoo.com','ganeshd','ganeshd','CRM',2,0,'','',0,7,0,4),(100,0,'Vilas Patil Pune','9595639839','operationselixia@yahoo.com','vilaspatil','vilasp','CRM',2,0,'','',0,7,1,4),(101,0,'Sunil Khaire','8652093698','operations@elixiatech.com','sunil','sunil','CRM',1,0,'','',0,7,1,4),(103,0,'Sourav Paul Woodword','9804322812','ganesh@woodword.in','pauls','pauls','CRM',2,0,'','',0,7,0,4),(104,0,'Amit K Modi ','7411007702','ganesh@woodword.in','amitmodi','amitmodi','CRM',2,0,'','',0,7,0,4),(105,0,'Ganesh lakhera','9891356699','ganesh@woodword.in','ganeshl','ganeshl','CRM',2,0,'','',0,7,1,4),(106,0,'Rupali Bidvi','8983577006','software@elixiatech.com','rupalib','rupalib','Serivce',1,0,'','3586',0,1,0,3),(107,0,'Arvind Thakur','9664213727','software@elixiatech.com','arvindt','arvindt','Head',1,0,'','',0,7,1,1),(108,0,'Rupayan Choudhury','9619521206','rupayanc@elixiatech.com','rupayanc','rupayanc','Sales',1,0,'','',0,7,1,4),(109,0,'Mayur Shinde','9619521206','mayurs@elixiatech.com','mayurs','mayurs','Sales',1,0,'','',0,7,1,4),(110,0,'Sushil Sharma','8873087430','sushilks15596@gmail.com','sushil','sushil','CRM',1,0,'','',0,7,1,4),(111,0,'Pankit','9757486099','accounts@elixiatech.com','pankit','pappu','Head',1,0,'','',0,3,0,1),(112,0,'Kartik','9820847502','karik.elixiatech@gmail.com','kartik','kartik','Serivce',1,0,'','',0,1,0,3),(113,0,'Yash','9892423730','yash.elixiatech@gmail.com','yash','yash','Serivce',1,0,'','',0,1,0,3),(115,0,'Vimal Ganesan','9944220320','vimal.g@elixiatech.com','vimal.g','vimal','Sales',1,0,'','832',0,4,0,3),(116,0,'Rajendra Borse','8698324696','rajendra.elixiatech@gmail.com','rajendra','rajendra','Serivce',1,0,'','',0,1,0,3),(117,0,'Samith Safe & secure','8793846109','operations@elixiatech.com','samith','samith','CRM',2,0,'','',0,7,0,4),(118,0,'Maninder Singh Bagga','7621996780','maninder.s@elixiatech.com','maninder.s','maninder','Sales',1,0,'','143',0,4,0,3),(119,0,'Shyam Kathuria','7042588679','shyam.k@elixiatech.com','shyam.k','shyam','Sales',1,0,'','477',0,4,0,3),(120,0,'Akshay Shigwan','9702994980','akshay.s@elixiatech.com','akshay.s','akshay','Sales',1,0,'','7548',0,4,0,3),(121,5,'Rakesh Gupta','8356872455','support@elixiatech.com','rakesh','rakesh','CRM',1,0,'','',0,5,0,3),(122,0,'Shreya Aher','8828292164','shreya.a@elixiatech.com','shreya','shreya.a','Sales',1,0,'','',0,7,0,4),(123,0,'Ishwar Singh','8114460014','operations@elixiatech.com','ishwars','ishwars','CRM',2,0,'','',0,7,0,4),(124,0,'Sanjeet Shukla','9702612369','sanjeet.elixiatech@gmail.com','sanjeet','sanjeet','Serivce',1,0,'','',0,1,0,3),(125,0,'Suman Sharma','7208667770','suman.elixiatech@gmail.com','suman','suman','Serivce',1,0,'','',0,1,0,3),(126,0,'Sonali Ithape','9503733268','sonaliithape2806@gmail.com','sonali ','sonali','Serivce',1,0,'','',0,1,0,3),(127,0,'Manasvi Thakur ','8884900889','manasvi.elixiatech@gmail.com','manasvi','manasvi','Serivce',1,0,'','',0,1,0,3),(128,0,'Tanuja Patil','926062712','tanuja.v.patil92@gmail.com','tanuja','tanuja','CRM',1,0,'','',0,3,0,3),(129,0,'Kushal Doshi','9769242875','kushal.d@elixiatech.com','kushal','kushal','Head',1,0,'','',0,4,0,1);



DELIMITER $$
DROP procedure IF EXISTS `fetchOverdueTickets`$$
CREATE PROCEDURE `fetchOverdueTickets`(
    IN nowParam DATETIME
)
BEGIN
select ticket.ticketid, ticket.title, ticket.estimateddate, ticket.eclosedate, details.allot_to, team.name, team.email,team.teamid
from sp_ticket ticket
LEFT JOIN `sp_ticket_details` details ON details.ticketid = ticket.ticketid
LEFT JOIN team ON details.allot_to = team.teamid
where
    estimateddate<>'0000-00-00 00:00:00'
    AND estimateddate < nowParam
    AND (
        eclosedate = '0000-00-00 00:00:00'
        OR eclosedate= NULL
        OR eclosedate ='1970-01-01'
    )
ORDER BY team.teamid;

END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetch_team_list`;
CREATE  PROCEDURE `fetch_team_list`()
BEGIN

  SELECT teamid,name,d_id FROM team WHERE member_type = 1 and is_deleted = 0;

END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `get_ticket_types`;

CREATE PROCEDURE `get_ticket_types`(
  IN issueTypeParam TINYINT(2)
    )
BEGIN
  IF((issueTypeParam=0)OR(issueTypeParam=null)) THEN
    SELECT  d_id,typeid,tickettype from sp_tickettype;
    ELSE
    SELECT d_id,typeid,tickettype from sp_tickettype WHERE issueType = issueTypeParam;
    END IF;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_dockets`$$
CREATE PROCEDURE `fetch_dockets`(
    IN teamIdParam INT(11),
    IN docketIdParam INT(11)
)
BEGIN
IF(docketIdParam = 0) THEN
    IF(teamIdParam = 0 OR teamIdParam = NULL) THEN
        SELECT d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
        (SELECT name from team where teamid = d.team_id LIMIT 1)as name,
        (SELECT name from team where teamid = d.create_by LIMIT 1)as create_name 
        FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
    where  d.team_id = teamIdParam
        ORDER BY d.docketid DESC  ;
    ELSEIF(teamIdParam = 28) THEN
     SELECT d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
        (SELECT team.name from team where team.teamid = d.team_id LIMIT 1)as name,
        (SELECT team.name from team where team.teamid = d.create_by LIMIT 1)as create_name 
        FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
		
        ORDER BY d.docketid DESC  ;
    ELSE
    SELECT d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
        (SELECT team.name from team where team.teamid = d.team_id LIMIT 1)as name,
        (SELECT team.name from team where team.teamid = d.create_by LIMIT 1)as create_name 
        FROM docket d 
        LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
        LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
        LEFT JOIN team t ON d.team_id = t.teamid
        where  d.team_id = teamIdParam
        ORDER BY d.docketid DESC  ;
    END IF;
ELSE
    SELECT d.docketid,(SELECT customercompany from customer where customerno = d.customerno) as customername,d.raiseondate,d.create_by,d.team_id,d.purpose_id,d.interaction_id,p.purpose_type,i.interaction_type,d.timestamp,
    (SELECT name from team where teamid = d.team_id LIMIT 1)as name,
  (SELECT name from team where teamid = d.create_by LIMIT 1)as create_name 
  FROM docket d 
    LEFT JOIN docket_purpose_type p ON p.purpose_id=d.purpose_id
    LEFT JOIN docket_interaction_type i ON i.interaction_id=d.interaction_id
    LEFT JOIN team t ON d.team_id = t.teamid
    WHERE docketid = docketIdParam
    ORDER BY d.docketid DESC  ;
END IF;
END$$

DELIMITER ;

DELIMITER $$
DROP procedure IF EXISTS `fetch_buckets`$$
CREATE PROCEDURE `fetch_buckets`(
IN docketIdsParam TEXT)
BEGIN

   SELECT b.bucketid, b.apt_date,t.name,b.docketid,b.create_timestamp,
    (CASE 
		WHEN b.status=0 
        THEN 'Open'
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
    where FIND_IN_SET(docketid,docketIdsParam);
   
END$$

DELIMITER ;


DELIMITER $$
DROP procedure IF EXISTS `fetchTickets`$$
CREATE PROCEDURE `fetchTickets`(
IN docketIdsParam TEXT)
BEGIN

	SELECT 
            st.ticketid,
            st.title,
            st.docketid,
            sttype.tickettype,
            t.name AS allot_to,
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
		WHERE FIND_IN_SET(st.docketid,docketIdsParam)
        GROUP BY    std.ticketid
        order by    st.ticketid ASC;
END$$

DELIMITER ;







