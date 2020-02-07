<?php

include_once '../../lib/system/Validator.php';
include_once '../../lib/system/VersionedManager.php';
include_once '../../lib/system/Sanitise.php';
include_once '../../lib/model/VOSupport.php';
include_once '../../lib/system/Date.php';
include_once '../../lib/system/DatabaseManager.php';

class SupportManager extends VersionedManager {
    private $_connection = null;
    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }
  public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    protected function connect() {
        // Connection is cached between objects.
        global $connection;
        if (isset($connection)) {
            $this->_connection = $connection;
            return;
        }
        if ($this->_connection == null) {

            $this->_connection = @mysqli_connect($this->_server, $this->_username, $this->_password, $this->_database);

            if ($this->_connection == null) {
                throw new Database_Exception(@mysqli_errno(), @mysqli_error());
            } else {
                if (!@mysqli_select_db($this->_connection, $this->_database)) {
                    $connection = $this->_connection;
                    throw new Database_Exception(@mysqli_errno(), @mysqli_error());
                }
            }
        }
        return;
    }
  public function executeQuery($query) {
        if ($query == null) {
            throw new InvalidArgument_Exception('query', 'executeQuery');
        }

        $this->_affectedRows = -1;
        $this->_currentRow = null;
        $this->_insertedId = -1;

        $this->connect();
        if (($this->_queryResult = @mysqli_query($this->_connection, $query)) == null) {

            throw new Database_Exception(@mysqli_errno(), @mysqli_error());
        }
        $pos = strpos($query, "SELECT");
        $pos1 = strpos($query, "select");
        if ($pos !== 0) {
            if ($pos1 !== 0) {
                require_once RELATIVE_PATH_DOTS . 'lib/system/Log.php';
                $log = new Log();
                $custno = isset($_SESSION['customerno']) ? $_SESSION['customerno'] : '';
                $usrid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
                $log->createlog($custno, ($query), $usrid);
            }
        }

        return;
    }
    public function get_all_drivers() {
        $drivers = Array();
        $Query = "SELECT * FROM `driver` where customerno=%d AND driver.isdeleted=0";
        $driversQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($driversQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $driver->driverid = $row['driverid'];
                $driver->drivername = $row['drivername'];
                $driver->driverlicno = $row['driverlicno'];
                $driver->driverphone = $row['driverphone'];
                $drivers[] = $driver;
            }
            return $drivers;
        }
        return null;
    }

//get all data of ticket
    public function get_all_tickettype() {
        $Query = "SELECT * FROM " . DB_ELIXIATECH . ".`sp_tickettype` where isdeleted = 0";
        $timeslotQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($timeslotQuery);
        $typedata = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $typedata[] = array(
                    'typeid' => $row['typeid'],
                    'tickettype' => $row['tickettype']
                );
            }
            return $typedata;
        }
        return NULL;
    }

    public function get_all_priority() {
        $Query = "SELECT * FROM " . DB_ELIXIATECH . ".`sp_priority` where isdeleted = 0";
        $timeslotQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($timeslotQuery);
        $prioritydata = array();
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $prioritydata[] = array(
                    'prid' => $row['prid'],
                    'priority' => $row['priority']
                );
            }
            return $prioritydata;
        }
        return NULL;
    }

///get value by id////////////////////////////////

    public function gettimeslotvalue($id) {
        $Query = "SELECT * FROM " . DB_ELIXIATECH . ".`sp_timeslot` where tsid='" . $id . "' AND isdeleted = 0";
        $timeslotQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($timeslotQuery);
        $timeslot = "";
        if ($this->_databaseManager->get_rowCount() > 0) {

            while ($row = $this->_databaseManager->get_nextRow()) {
                $timeslot = $row['timeslot'];
            }
            return $timeslot;
        }
        return $timeslot;
    }

    public function gettickettypevalue($id) {
        $Query = "SELECT * FROM " . DB_ELIXIATECH . ".`sp_tickettype` where typeid = '" . $id . "' AND isdeleted = 0";
        $timeslotQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($timeslotQuery);
        $tickettype = "";
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $tickettype = $row['tickettype'];
            }
            return $tickettype;
        }
        return $tickettype;
    }

    public function getpriorityvalue($id) {
        $Query = "SELECT * FROM " . DB_ELIXIATECH . ".`sp_priority` where prid='" . $id . "' AND isdeleted = 0";
        $timeslotQuery = sprintf($Query);
        $this->_databaseManager->executeQuery($timeslotQuery);
        $priority = "";
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $priority = $row['priority'];
            }
            return $priority;
        }
        return $priority;
    }

////////////////////////
    #Usage For History Module -- Vehicle Data

    public function GetAllDrivers_SQLite() {
        $DRIVERS = Array();
        $Query = "SELECT drivername,driverphone,driverid FROM `driver` where customerno=%d AND isdeleted = 0";
        $driversQuery = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($driversQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $id = $row['driverid'];
                $DRIVERS[$id]['id'] = $row['driverid'];
                $DRIVERS[$id]['drivername'] = $row['drivername'];
                $DRIVERS[$id]['driverphone'] = $row['driverphone'];
            }
            return $DRIVERS;
        }
        return null;
    }

    public function getusername($uid) {
        $query = sprintf("select * from user where userid=" . $uid);
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $realname = $row['realname'];
            }
            return $realname;
        }
        return null;
    }

    public function getclosedesc($tid) {
        $query = sprintf("select description from " . DB_ELIXIATECH . ".sp_ticket_details where status = 2 AND ticketid=" . $tid . " ORDER BY `uid` DESC  limit 1");
        $this->_databaseManager->executeQuery($query);
        $decs = "";
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $decs = $row['description'];
            }
            return $decs;
        }
        return null;
    }

    public function getclosedate($tid) {
        $query = sprintf("select create_on_time from " . DB_ELIXIATECH . ".sp_ticket_details where status = 2 AND ticketid=" . $tid . " ORDER BY `uid` DESC  limit 1");
        $this->_databaseManager->executeQuery($query);
        $close_date = "";
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $close_date = $row['create_on_time'];
            }
            return $close_date;
        }
        return null;
    }

    public function get_all_issues() {
        $ticketcustid = $_SESSION["customerno"];
        $uid = $_SESSION["userid"];
        $issues = Array();
        $query = sprintf("select    st.ticketid
                                    ,st.title
                                    ,st.ticket_type
                                    ,sttype.tickettype
                                    ,sp.priority as prname
                                    ,st.sub_ticket_issue
                                    ,st.customerid
                                    ,st.priority
                                    ,st.create_on_date
                                    ,st.create_by
                                    ,st.created_type
                                    ,st.uid
                                    ,st.create_on_date 
                                    ,st.create_platform 
                                    ,t.name AS allot_to
                                    ,std.status
                                    ,user.realname
                        from        " . DB_ELIXIATECH . ".sp_ticket as st 
                        INNER JOIN  " . DB_ELIXIATECH . ".sp_ticket_details std ON std.ticketid = st.ticketid AND std.uid = (SELECT MAX(uid) from " . DB_ELIXIATECH . ".sp_ticket_details sds where sds.ticketid=st.ticketid)
                        LEFT JOIN  " . DB_ELIXIATECH . ".team t ON t.teamid = std.allot_to
                        left join " . DB_ELIXIATECH . ".sp_tickettype as sttype on sttype.typeid = st.ticket_type 
                        left join " . DB_ELIXIATECH . ".sp_priority as sp on sp.prid = st.priority 
                        LEFT JOIN " . DB_ELIXIATECH . ".user ON user.userid = st.uid
                        WHERE       st.customerid= %d 
                        AND         st.uid IN ( %d ,0)
                        GROUP BY    std.ticketid
                        order by    st.ticketid DESC", Sanitise::Long($ticketcustid), Sanitise::Long($uid));
        $this->_databaseManager->executeQuery($query);
        $this->_databaseManager->get_rowCount();
        if ($this->_databaseManager->get_rowCount() > 0) {

            $count = $this->_databaseManager->get_rowCount();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $support1 = new VOSupport();
                $ticketid = $row['ticketid'];
                $support1->ticketid = $row['ticketid'];
                $support1->title = $row['title'];
                $support1->ticket_type = $row['tickettype'];
                $support1->sub_ticket_issue = $row['sub_ticket_issue'];
                $support1->customerid = $row['customerid'];
                $support1->priority = $row['prname'];
                $support1->create_on_date = $row['create_on_date'];
                $support1->create_by = $row['create_by'];
                $support1->created_type = $row['created_type'];
                $support1->uid = $row['uid'];
                $support1->allot_to = $row['allot_to'];
                $support1->status = $row['status'];
                $support1->realname = $row['realname'];
                $support1->create_platform = $row['create_platform'];
                $support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $issues[] = $support1;
                
            }
            return $issues;
        }
        return null;
    }

    //get ticket data for modify ticket
    public function get_issue($id) {
        $ticketcustid = $_SESSION["customerno"];
        $uid = $_SESSION["userid"];
        $issues = Array();
        $query = sprintf("  SELECT  st.ticketid
                                    ,st.title
                                    ,st.ticket_type
                                    ,st.title
                                    ,st.ticket_type
                                    ,st.sub_ticket_issue
                                    ,st.customerid
                                    ,st.priority
                                    ,st.create_on_date
                                    ,st.create_by
                                    ,st.created_type
                                    ,st.uid
                                    ,st.create_on_date
                                    ,st.send_mail_to
                                    ,std.description
                                    ,std.status
                                    ,user.realname
                            FROM    " . DB_ELIXIATECH . ".sp_ticket as st 
                            LEFT JOIN sp_ticket_details std ON std.ticketid = st.ticketid AND std.uid = (SELECT MAX(sds.uid) from " . DB_ELIXIATECH . ".sp_ticket_details sds where sds.ticketid = st.ticketid)
                            LEFT JOIN " . DB_ELIXIATECH . ".user ON user.userid = st.uid
                            WHERE   st.ticketid = %d 
                            AND     st.customerid = %d 
                            ORDER BY std.uid DESC
                            LIMIT   1", Sanitise::Long($id), Sanitise::Long($ticketcustid), Sanitise::Long($uid));
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $count = $this->_databaseManager->get_rowCount();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $support1 = new VOSupport();
                $ticketid = $row['ticketid'];
                $support1->ticketid = $row['ticketid'];
                $support1->title = $row['title'];
                $support1->ticket_type = $row['ticket_type'];
                $support1->sub_ticket_issue = $row['sub_ticket_issue'];
                $support1->customerid = $row['customerid'];
                $support1->priority = $row['priority'];
                $support1->create_on_date = $row['create_on_date'];
                $support1->send_mail_to = $row['send_mail_to'];
                $support1->create_by = $row['create_by'];
                $support1->created_type = $row['created_type'];
                $support1->uid = $row['uid'];
                $support1->description = $row['description'];
                $support1->status = $row['status'];
                $support1->realname = $row['realname'];
                $support1->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $issues[] = $support1;
            }

            return $issues;
        }
        return null;
    }

    function get_issue_desc($tid) {
        $ticketcustid = $_SESSION["customerno"];
        $uid = $_SESSION["userid"];
        $issuesdesc = Array();
        $query = sprintf("  SELECT      std.ticketid
                                        ,sn.note 
                                        ,std.create_on_time
                            FROM        " . DB_ELIXIATECH . ".sp_ticket_details std
                            INNER JOIN   sp_note sn ON sn.noteid = std.noteid
                            WHERE       std.ticketid = '" . $tid . "' 
                            AND         std.is_custupdated = 1 
                            ORDER BY    std.uid DESC");
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $support2 = new VOSupport();
                $ticketid = $row['ticketid'];
                $support2->note = $row['note'];
                $support2->timestamp = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_time']));
                $issuesdesc[] = $support2;
            }

            return $issuesdesc;
        }
        return null;
    }

    public function add_support($adddata) {
        $message = "";
        $ticketcustid = $_SESSION["customerno"];
        $uid = $_SESSION["userid"];
        $today1 = date("Y-m-d H:i:s");
        $today = date("Y-m-d");
        $ticket_title = $adddata["issuetitle"];
        $tickettype = $adddata["tickettype"];
        $typeName = $adddata["typeName"];
        $ticket_details = $adddata["notes_support"];
        $priority = $adddata["priority"];
        $priorityName = $adddata["priorityName"];
        $mailid = $adddata["mailid"];
        $callback = isset($adddata["callback"]) ? $adddata["callback"] : '';
        $phone = $adddata["phone"];
        $showtouser = $adddata["showtouser"];
        $mailids = explode(',', $mailid);
        $showtousers = explode(',', $showtouser);
        $showuserIds =  $adddata['showtouserids'];
        $newUserArray = array_combine($showuserIds,$showtousers);
      
        $mailid1='';
        if (is_array($mailids)) {
            foreach ($mailids AS $mailid) {
                if (preg_match("/[a-z]/i", $mailid)) {
                    $str = "%" . $mailid . "%";
                    $sql = sprintf("select eid from " . DB_ELIXIATECH . ".report_email_list where email_id LIKE '%s' and isdeleted=0 LIMIT 1;"  ,$str);
                    
                    $this->_databaseManager->executeQuery($sql);
                    if ($this->_databaseManager->get_rowCount() > 0) {
                        while ($row = $this->_databaseManager->get_nextRow()) {
                           $mailid1 .= $row["eid"] . ',';
                        }
                    } else {
                        $sql = sprintf("INSERT INTO " . DB_ELIXIATECH . ".report_email_list`(
                                            `customerno` ,
                                            `email_id` ,
                                            `created_on`,
                                            `created_by`)
                                        VALUES (%d, '%s', '%s',%d);", Sanitise::Long($ticketcustid)
                                , Sanitise::String($mailid)
                                , Sanitise::DateTime($today1)
                                , Sanitise::Long($uid));
                        $this->_databaseManager->executeQuery($sql);
                        $mailid1 = $this->_databaseManager->get_insertedId();
                        $mailid .=$mailid1 . ',';
                    }
                }
            }
        }

        $sql = sprintf("select rel_manager from " . DB_ELIXIATECH . ".customer where customerno=" . $ticketcustid);

        $this->_databaseManager->executeQuery($sql);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $rel_manager = $row["rel_manager"];
        }
       $sql = sprintf("INSERT INTO " . DB_ELIXIATECH . ".sp_ticket(
                        `title`,
                        `ticket_type` ,
                        `sub_ticket_issue`,
                        `customerid` ,
                        `priority`,
                        `raised_on_date`,
                        `create_on_date`,
                        `create_by`,
                        `created_type`,
                        `uid`,
                        `crmid`,
                        `send_mail_to`,
                        `send_mail_cc`,
                        `create_platform`)
                        VALUES ('%s', %d,'',%d,%d,'%s','%s',%d,%d,%d,%d,'%s','',%d);", Sanitise::String($ticket_title)
                , Sanitise::Long($tickettype)
                , Sanitise::Long($ticketcustid)
                , Sanitise::Long($priority)
                , Sanitise::DateTime($today1)
                , Sanitise::DateTime($today1)
                , -1
                , 1
                , Sanitise::Long($uid)
                , Sanitise::Long($rel_manager)
                , Sanitise::String($mailid1)
                , 1);

        $this->_databaseManager->executeQuery($sql);
        $ticketid1 = $this->_databaseManager->get_insertedId();

        $sql = sprintf("SELECT   teamid
                                ,email
                                ,phone 
                        FROM    " . DB_ELIXIATECH . ".team 
                        WHERE   rid = %d", Sanitise::Long($rel_manager));
        $this->_databaseManager->executeQuery($sql);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $allot_to = $row["teamid"];
            $crmemail = $row["email"];
            $rel_phone = $row["phone"];
        }

        $sql = sprintf("INSERT INTO " . DB_ELIXIATECH . ".`sp_ticket_details`(
                            `ticketid` ,
                            `description`,
                            `allot_to`,
                            `status`,
                            `create_by`,
                            `create_on_time`,
                            `created_type`,
                            `userid`,
                            `is_custupdated`)
                        VALUES (%d,'%s',%d,%d,%d,'%s',%d,%d,%d);", Sanitise::Long($ticketid1)
                , Sanitise::String($ticket_details)
                , Sanitise::Long($allot_to)
                , 0
                , 0
                , Sanitise::DateTime($today1)
                , 1
                , Sanitise::Long($uid)
                , 1);

        $this->_databaseManager->executeQuery($sql);
        $uid = $this->_databaseManager->get_insertedId();
if (is_array($newUserArray)) {
            foreach ($newUserArray as $userid=>$username)
            {
                $sql1 = sprintf("INSERT INTO ".DB_PARENT.".`ticket_user_mapping`(
                            `ticketid` ,
                            `userid`,
                            `username`,
                            `updatedOn`)VALUES (%d,%d,'%s','%s');", Sanitise::Long($ticketid1)
                , Sanitise::Long($userid),Sanitise::String($username), Sanitise::DateTime($today1));
  
                $this->_databaseManager->executeQuery($sql1);
            }   
}
        $messageCust = "<table>
                            <tr><td>Ticket No : </td><td>ET00" . $ticketid1 . "</td></tr>
                            <tr><td>Created By : </td><td>" . $_SESSION['realname'] . "</td></tr>
                            <tr><td>Title : </td><td>" . $ticket_title . "</td></tr>
                            <tr><td>Customer : </td><td>" . $_SESSION['customercompany'] . "</td></tr>    
                            <tr><td>Ticket Type : </td><td>" . $typeName . "</td></tr> 
                            <tr><td>Priority : </td><td>" . $priorityName . "</td></tr>              
                            <tr><td>Description :</td><td>" . $ticket_details . "</td></tr>   
                            <tr><td>Status :</td><td>Open</td></tr>   
                        </table>";

        //send Mail to customer
        if (!empty($mailids) && is_array($mailids)) {
            $sendmailidtoteam = $mailids;
            $to = $mailids;
            $strCCMailIds = "";
            $strBCCMailIds = "";
            $attachmentFilePath = "";
            $attachmentFileName = "";
            $subject = "Elixia Speed - Support Ticket No: ET00" . $ticketid1 . " (Customer No: " . $ticketcustid . ")";
            $message .= "<h4> A new support ticket has been created for you. Kindly interact with Elixia team providing the ticket number for further assistance. </h4>";
            $message .= $messageCust;

            sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
        }
        
        if ($callback == 'on') {
            $response = '';
            $userid = $vehicleid = 0;
            $message = "Quick Ticket" . "\r\n" .
                    "Company Name:" . $_SESSION['customercompany'] . "(" . $ticketcustid . ")" . "\r\n" .
                    "Username:" . $_SESSION['realname'] . "\r\n" .
                    "Issue:" . $ticket_details . "\r\n" .
                    "Contact No:" . $phone;
            $isSMSSent = sendSMSUtil($rel_phone, $message, $response);
            $moduleid = 9;
            if ($isSMSSent == 1) {
                $SQL = sprintf("INSERT INTO " . DB_ELIXIATECH . ".  smslog (`mobileno`,`message`,`response`,`vehicleid`,`userid`,`moduleid`,`customerno`,`issmssent`, `inserted_datetime`, `cqid`) 
                                VALUES (%d,'%s','%s',%d,%d,%d,%d,%d,'%s',0);", Sanitise::Long($rel_phone), Sanitise::String($message), $response, $vehicleid, $userid, $moduleid, $_SESSION['customerno'], $isSMSSent, Sanitise::DateTime($today1));
                $this->_databaseManager->executeQuery($SQL);
            }
        }
        
        //Send mail to CRM
        $content = "";
        $to = $crmemail;
        $subject = "Ticket Raised By Customer -(" . $ticketcustid . "-" . $_SESSION['customercompany'] . ")";
        $content .= $ticket_details;
        $content .= "<b>Ticket Id : </b>" . $ticketid1 . "\n";
        $content .= "<b>" . $ticket_title . "</b>\n";
        if (!empty($tickettype)) {
            $content .= "<b>Ticket Type :</b>" . $this->gettickettypevalue($tickettype) . "\n";
        }

        if (!empty($priority)) {
            $content .="<b>Priority :</b>" . $this->getpriorityvalue($priority) . "\n";
        }
        $strCCMailIds = "sanketsheth@elixiatech.com,mihir@elixiatech.com";
        $strBCCMailIds = "";
        $attachmentFilePath = "";
        $attachmentFileName = "";
        sendMailUtil(array($to), $strCCMailIds, $strBCCMailIds, $subject, $content, $attachmentFilePath, $attachmentFileName);

        $error = false;
        $files = array();
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/support/";
        $supportfolder = "../../customer/" . $_SESSION['customerno'] . "/support/";
        if (!file_exists($supportfolder)) {
            mkdir("../../customer/" . $_SESSION['customerno'] . "/support/", 0777);
        }
        foreach ($_FILES as $file) {
            $filename = $uploaddir . basename($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if ($ext == "zip" || $ext == "rar") {
                if (move_uploaded_file($file['tmp_name'], $uploaddir . $ticketid1 . '_support.' . $ext)) {
                    $files[] = $uploaddir . $file['name'];
                } else {
                    $error = true;
                }
            }
        }
        $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
    }

    public function modissue($modifydata) {
        $ticketcustid = $_SESSION["customerno"];
        $uid = $_SESSION["userid"];
        $today1 = date("Y-m-d H:i:s");
        $today = date("Y-m-d");
        $ticket_title = $modifydata["issuetitle"];
        $tickettype = $modifydata["tickettype"];
        $typeName = $modifydata["typeName"];
        $ticket_details = $modifydata["notes_support"];
        $priority = $modifydata["priority"];
        $ticketid = $modifydata['ticketid'];
        $sentoEmail = $modifydata['sentoEmail'];
        $showtoUser = $modifydata['showtouser'];
        $showtousers = explode(',', $showtoUser);
        $sentoEmail = array_filter(explode(',', $sentoEmail));
        $mailid = '';
        foreach ($sentoEmail AS $mail) {
            $mail1 = '%' . $mail . '%';
            $sql = sprintf("select  eid 
                            FROM    " . DB_ELIXIATECH . ".report_email_list 
                            WHERE   email_id LIKE '%s' 
                            LIMIT   1", Sanitise::String($mail1));
            $this->_databaseManager->executeQuery($sql);
            while ($row = $this->_databaseManager->get_nextRow()) {
                $mailid .= $row["eid"] . ',';
            }
        }
        rtrim($mailid,',');
        $query = sprintf("  UPDATE  " . DB_ELIXIATECH . ".sp_ticket 
                            SET     title = '%s'
                                    ,ticket_type = %d
                                    ,priority = %d 
                                    ,send_mail_to = '%s'
                            WHERE   ticketid = %d
                            AND     customerid= %d", Sanitise::String($ticket_title)
                , Sanitise::Long($tickettype)
                , Sanitise::Long($priority)
                , Sanitise::String($mailid)
                , Sanitise::Long($ticketid)
                , Sanitise::Long($ticketcustid));
        $this->_databaseManager->executeQuery($query);


/**SP To Delete all the entries of ticket id which has been updated***/

        $sp_params = "'". $ticketid ."'";
        $pdo = $this->_databaseManager->CreatePDOConn();
         $sp_query = $this->PrepareSP(speedConstants::SP_DELETE_FROM_TICKETUSERMAPPING, $sp_params);
         $pdo->query($sp_query); 

        /**********To make new entries for users that are mapped to the ticket***//// 

        if (is_array($showtousers)) {
            foreach ($showtousers AS $username) {
                if (preg_match("/[a-z]/i", $username)) {
                    $userstr =  $username ;
                    $selectSql = "SELECT userid FROM user WHERE realname= '$userstr'";
                    $this->_databaseManager->executeQuery($selectSql);
                     while ($row = $this->_databaseManager->get_nextRow()) {
                        $userID =$row['userid'];
                     }
                     $sp_params = "'" . $ticketid . "'"
                                    . ",'" . $userID . "'"
                                    . ",'" . $username . "'";
                  $sp_query       = $this->PrepareSP(speedConstants::SP_INSERT_INTO_TICKETUSERMAPPING, $sp_params); 
                    $pdo->query($sp_query); 
                   
            }
        }
    }

/************************/
 
        //crm allot - start
        $sql = sprintf("select  `rel_manager` from " . DB_ELIXIATECH . ".customer where customerno= %d", Sanitise::Long($ticketcustid));

        $this->_databaseManager->executeQuery($sql);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $rel_manager = $row["rel_manager"];
        }
        $sql = sprintf("select teamid,email from " . DB_ELIXIATECH . ".team where rid = %d", Sanitise::Long($rel_manager));
        $this->_databaseManager->executeQuery($sql);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $allot_to = $row["teamid"];
            $team_email = $row["email"];
        }

        //crm allot - end
        //checking ticket assign to another person
        $sql = sprintf("select  std.allot_to
                                ,std.status
                                ,std.create_by
                                ,std.created_type
                                ,std.userid
                                ,ts.`status` AS tsstatus
                        from    " . DB_ELIXIATECH . ".sp_ticket_details std 
                        INNER JOIN " . DB_ELIXIATECH . ".`ticket_status` ts ON ts.id =  std.`status`
                        where   std.ticketid = %d
                        order by uid desc 
                        limit 1", Sanitise::Long($modifydata['ticketid']));

        $this->_databaseManager->executeQuery($sql);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $allot_to1 = $row["allot_to"];
            $status = $row["status"];
            $statusName = $row["tsstatus"];
            $create_by = $row["create_by"];
            $createdtype = $row["created_type"];
            $userid = $row["userid"];
        }
        if ($create_by != 0) {
            $sql = sprintf("INSERT INTO " . DB_ELIXIATECH . ".`sp_ticket_details`(
                                    `ticketid` ,
                                    `description`,
                                    `allot_to`,
                                    `status`,
                                    `create_by`,
                                    `create_on_time`,
                                    `created_type`,
                                    `userid`,
                                    `is_custupdated`)
                            VALUES (%d,'%s',%d,%d,%d,'%s',%d,%d,%d);", Sanitise::Long($modifydata['ticketid'])
                    , Sanitise::String($modifydata['notes_support'])
                    , Sanitise::Long($allot_to1)
                    , Sanitise::Long($status)
                    , Sanitise::Long($create_by)
                    , Sanitise::DateTime($today1)
                    , 1
                    , Sanitise::Long($userid)
                    , 1);
            $this->_databaseManager->executeQuery($sql);
        } else {
            $sql = sprintf("INSERT INTO " . DB_ELIXIATECH . ".`sp_ticket_details`(`ticketid` 
                                    ,`description`
                                    ,`allot_to`
                                    ,`status`
                                    ,`create_by`
                                    ,`create_on_time`
                                    ,`created_type`
                                    ,`userid`
                                    ,`is_custupdated`)
                            VALUES (%d,'%s',%d,%d,%d,'%s',%d,%d,%d);", Sanitise::Long($modifydata['ticketid'])
                    , Sanitise::String($modifydata['notes_support'])
                    , Sanitise::Long($allot_to)
                    , 0
                    , 0
                    , Sanitise::DateTime($today1)
                    , 1
                    , Sanitise::Long($uid)
                    , 1);
            $this->_databaseManager->executeQuery($sql);
        }

        $sql = sprintf("select  sn.`note`
                                ,sn.`create_on_date`
                                ,t.`name`
                                ,u.`realname`
                        FROM    " . DB_ELIXIATECH . ".`sp_note` sn
                        LEFT JOIN " . DB_ELIXIATECH . ".`team` t ON t.`teamid` = sn.`create_by` AND `is_customer` = 0
                        LEFT JOIN " . DB_ELIXIATECH . ".`user` u ON u.`userid` = sn.`create_by` AND `is_customer` = 1
                        WHERE   sn.ticketid = %d 
                        ORDER BY sn.`noteid` DESC", Sanitise::Long($ticketid));

        $this->_databaseManager->executeQuery($sql);

        if ($this->_databaseManager->get_rowCount() > 0) {
            $srno = 1;
            $note = "<table>";
            $note.="<tr><th>Sr No</th><th>Note</th><th>Added By</th><th>Time</th></tr>";
            while ($row = $this->_databaseManager->get_nextRow()) {
                $name = isset($row['realname']) ? $row['realname'] : $row['name'];
                $note.="<tr><td>" . $srno . "</td><td>" . $row['note'] . "</td><td>" . $name . "</td><td>" . date('d-m-Y H:i:s', strtotime($row['create_on_date'])) . "</td></tr>";
                $srno++;
            }
            $note.="</table>";
        }

        //mail to customer
        $note="";
        if (!empty($sentoEmail) && is_array($sentoEmail)) {
            $to = $sentoEmail;
            $strCCMailIds = "";

            $strBCCMailIds = "";
            $attachmentFilePath = "";
            $attachmentFileName = "";
            $ticketid1=$ticketid;
            $ticketid = "ET00" . $ticketid;
            $subject = "Elixia Speed - Ticket No: " . $ticketid . " - Ticket For: " . ucfirst($typeName) . " (Customer No: " . $ticketcustid . ")";
            $message = file_get_contents('../emailtemplates/ticketMailTemplate.html');
            $message = str_replace("{{SUBJECT}}", $subject, $message);
            $message = str_replace("{{TICKETID}}", $ticketid, $message);
            $message = str_replace("{{TICKETTITLE}}", $ticket_title, $message);
            $message = str_replace("{{TICKETSTATUS}}", $statusName, $message);
            $message = str_replace("{{TICKETDESC}}", $ticket_details, $message);
            $message = str_replace("{{NOTE}}", $note, $message);
            $message = str_replace("{{CLOSEDATA}}", '', $message);
            $subject = "Elixia Speed - Support Ticket No: " . $ticketid;
            sendMailUtil($to, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);
        }

        //mail to CRM
        sendMailUtil(array($team_email), $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName);

         $error = false;
        $files = array();
        $uploaddir = "../../customer/" . $_SESSION['customerno'] . "/support/";
        $supportfolder = "../../customer/" . $_SESSION['customerno'] . "/support/";
        if (!file_exists($supportfolder)) {
            mkdir("../../customer/" . $_SESSION['customerno'] . "/support/", 0777);
        }
        foreach ($_FILES as $file) {
            $filename = $uploaddir . basename($file['name']);
            $path_parts = pathinfo($filename);
            $ext = $path_parts['extension'];
            if ($ext == "zip" || $ext == "rar") {
                if (move_uploaded_file($file['tmp_name'], $uploaddir . $ticketid1 . '_support.' . $ext)) {
                    $files[] = $uploaddir . $file['name'];

                } else {
                    $error = true;
                }
            }
        }
        $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
    }

    public function deldriver($driverid, $userid) {
        $Query = "UPDATE driver SET isdeleted=1,userid=%d WHERE driverid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "UPDATE vehicle SET driverid=0,userid=%d WHERE driverid=%d AND customerno=%d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function mapdrivertovehicle($vehicleid, $driverid, $userid) {
        $Query = "Update vehicle Set `driverid`=%d, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($driverid), Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        $Query = "Update driver Set `vehicleid`=%d, userid=%d WHERE driverid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($vehicleid), Sanitise::Long($userid), Sanitise::Long($driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function demapdriver($vehicleid, $userid) {
        $Query = "Update driver Set `vehicleid`= 0, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        $Query = "Update vehicle Set `driverid`= 0, userid=%d WHERE vehicleid = %d AND customerno = %d";
        $SQL = sprintf($Query, Sanitise::Long($userid), Sanitise::Long($vehicleid), $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function getdriverfromvehicles($driverid) {
        $Query = "SELECT * FROM `vehicle` where vehicle.driverid = %d AND vehicle.customerno=%d and vehicle.isdeleted=0";
        $driverQuery = sprintf($Query, Sanitise::String($driverid), $this->_Customerno);
        $this->_databaseManager->executeQuery($driverQuery);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $driver = new VODriver();
                $driver->vehicleid = $row['vehicleid'];
                $driver->driverid = $row['driverid'];
            }
            return $driver;
        }
        return null;
    }

    public function add_supportapi($adddata) {  //add ticket support api
        $ticketcustid = $adddata["customerno"];
        $uid = $adddata["userid"];
        $today1 = date("Y-m-d H:i:s");
        $today = date("Y-m-d");
        $ticket_title = $adddata["issuetitle"];
        $tickettype = $adddata["tickettype"];
        $ticket_details = $adddata["notes_support"];
        $priority = $adddata["priority"];
        $customerno = $adddata["customerno"];
        $companyname = $adddata["companyname"];

        $sql = sprintf("select rel_manager from " . DB_ELIXIATECH . ".customer where customerno=" . $ticketcustid);

        $this->_databaseManager->executeQuery($sql);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $rel_manager = $row["rel_manager"];
        }

        $sql = sprintf("INSERT INTO " . DB_ELIXIATECH . ".`sp_ticket`(
       `title` ,
       `ticket_type` ,
       `customerid` ,
       `eclosedate`,
        `priority`,
       `create_on_date`,
       `create_by`,
       `created_type`,
       `uid`,
       `crmid`
       )
       VALUES (
        '%s', '%s','%d','%s','%s','%s','%d','%d','%d','%d'
       );", $ticket_title, $tickettype, $ticketcustid, $today, $priority, Sanitise::DateTime($today1), -1, 1, $uid, $rel_manager);
        $this->_databaseManager->executeQuery($sql);


        $SQL = sprintf("SELECT max(ticketid)as ticketid from " . DB_ELIXIATECH . ".sp_ticket");
        $this->_databaseManager->executeQuery($SQL);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $ticketid1 = $row["ticketid"];
        }

        $sql = sprintf("select teamid,email from " . DB_ELIXIATECH . ".team where rid=" . $rel_manager);
        $this->_databaseManager->executeQuery($sql);
        while ($row = $this->_databaseManager->get_nextRow()) {
            $allot_to = $row["teamid"];
            $crmemail = $row["email"];
        }

        $sql = sprintf("INSERT INTO " . DB_ELIXIATECH . ".`sp_ticket_details`(
    `ticketid` ,
    `description`,
    `allot_to`,
    `status`,
    `create_by`,
    `create_on_time`,
    `created_type`,
    `userid`,
    `is_custupdated`
    )
    VALUES (
     '%d', '%s', '%d', '%d','%d','%s','%d','%d','%d'
    );", $ticketid1, $ticket_details, $allot_to, 0, 0, Sanitise::DateTime($today1), 1, $uid, '1');

        $this->_databaseManager->executeQuery($sql);
        $uid = $this->_databaseManager->get_insertedId();

        //send Mail to CRM
        $content = "";
        $to = $crmemail;
        $subject = "Ticket Raised By Customer -(" . $_SESSION['customerno'] . "-" . $_SESSION['customercompany'] . ")";
        $content .= $ticket_details;
        $content .= "<b>Ticket Id : </b>" . $ticketid1 . "\n";
        $content .= "<b>" . $ticket_title . "</b>\n";
        if (!empty($tickettype)) {
            $content .= "<b>Ticket Type :</b>" . $this->gettickettypevalue($tickettype) . "\n";
        }
        if (!empty($priority)) {
            $content .="<b>Priority :</b>" . $this->getpriorityvalue($priority) . "\n";
        }

        $ticketid = "SUP00" . $ticketid1;
        $data = array(
            'ticketid' => $ticketid
        );
        return $data;
    }

    public function getteamdata() {
        $users = Array();
        $Query = "select * from " . DB_ELIXIATECH . ".team where role IN ('Head','Sales','Service','Admin','Data','CRM')";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $users[] = array(
                    'teamid' => $row['teamid'],
                    'rid' => $row['rid'],
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'email' => $row['email'],
                    'username' => $row['username'],
                    'password' => $row['password'],
                    'role' => $row['role'],
                    'member_type' => $row['member_type'],
                    'distributor_id' => $row['distributor_id'],
                    'address' => $row['address']
                );
            }
        }
        return $users;
    }

    public function getcrmdata() {
        $users = Array();
        $Query = "select * from " . DB_ELIXIATECH . ".team where role IN ('CRM')";
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $users[] = array(
                    'teamid' => $row['teamid'],
                    'rid' => $row['rid'],
                    'name' => $row['name'],
                    'phone' => $row['phone'],
                    'email' => $row['email'],
                    'username' => $row['username'],
                    'password' => $row['password'],
                    'role' => $row['role'],
                    'member_type' => $row['member_type'],
                    'distributor_id' => $row['distributor_id'],
                    'address' => $row['address']
                );
            }
        }
        return $users;
    }

    public function get_created_count($teamid, $fromdate) {
        $sql = sprintf("select * from " . DB_ELIXIATECH . ".sp_ticket where create_by=" . $teamid . " AND create_on_date < '" . $fromdate . "'");
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $status = $this->_databaseManager->get_rowCount();
        } else {
            $status = "";
        }
        return $status;
    }

    public function get_crm_ticket_details($teamid, $today) {
        $timeslot = 0;
        $ticket_type = "";
        $vehicleno = "0";
        $today = date('y-m-d', strtotime($today));
        $data = array();
        $sql = sprintf("select  (CASE WHEN stde.status=1 THEN 'Inprogress' WHEN stde.status= 2 THEN 'Closed' WHEN stde.status= 3 THEN 'Reopen' ELSE 'Open' END)as ticketstatus
                                , stde.uid
                                ,st.ticketid
                                , st.title
                                ,st.ticket_type
                                ,sttype.tickettype as tickettypename
                                ,st.sub_ticket_issue
                                ,st.customerid
                                ,st.eclosedate
                                ,sp.priority
                                ,st.create_on_date
                                ,st.create_by
                                ,stde.create_by as closeby
                                ,stde.created_type
                                ,stde.allot_to
                                ,stde.create_on_time
                                ,stde.description
    from ( SELECT t1.*,t1.create_by as closeby FROM " . DB_ELIXIATECH . ".sp_ticket_details t1
    WHERE
    t1.uid = (SELECT t2.uid FROM " . DB_ELIXIATECH . ".sp_ticket_details t2 WHERE t2.ticketid = t1.ticketid ORDER BY `t2`.`uid` DESC LIMIT 1 ) ORDER BY t1.uid DESC ) stde
    left join " . DB_ELIXIATECH . ".sp_ticket as st on st.ticketid = stde.ticketid
    left join " . DB_ELIXIATECH . ".sp_tickettype as sttype on sttype.typeid = st.ticket_type
    left join " . DB_ELIXIATECH . ".sp_priority as sp on sp.prid= st.priority
    where date(st.create_on_date) = '" . $today . "' and stde.allot_to =" . $teamid . " group by stde.ticketid order by stde.ticketid desc");
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $allot_to_name = $this->get_name($row['allot_to']);
                $timeslot = $this->gettimeslot($row['timeslot']);
                $get_cust = $this->get_customerno($row['customerid']);
                if ($row['ticket_type'] == 0) {
                    $ticket_type = "Elixir";
                } else {
                    $ticket_type = "Customer";
                }
                $data[] = array(
                    'ticketid' => $row['ticketid'],
                    'description' => $row['description'],
                    'allot_to' => $row['allot_to'],
                    'allot_to_name' => $allot_to_name,
                    'ticketstatus' => $row['ticketstatus'],
                    'create_by' => $row['create_by'],
                    'create_on_time' => $row['create_on_time'],
                    'title' => $row['title'],
                    'ticket_type' => $row['ticket_type'],
                    'tickettypename' => $row['tickettypename'],
                    'sub_ticket_issue' => $row['sub_ticket_issue'],
                    'customerid' => $row['customerid'],
                    'priority' => $row['priority'],
                    'created_type' => $ticket_type,
                    'eclosedate' => $row['eclosedate'],
                    'customername' => $get_cust,
                );
            }
        }
        return $data;
    }

    public function get_vehicleno($vehicleid) {
        $vehicleno = 0;
        $sql = sprintf("select vehicleno,vehicleid from vehicle where vehicleid =" . $vehicleid);
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $vehicleno = $row['vehicleno'];
            }
        }
        return $vehicleno;
    }

    public function gettimeslot($timeslot) {
        if ($timeslot != 0) {
            $sql = sprintf("select tsid,timeslot from " . DB_ELIXIATECH . ".sp_timeslot where tsid =" . $timeslot);
            $this->_databaseManager->executeQuery($sql);
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $timeslot = $row['timeslot'];
                }
            }
        }
        return $timeslot;
    }

    public function get_name($allot_to) {
        $sql = sprintf("select * from " . DB_ELIXIATECH . ".team where teamid =" . $allot_to);
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $name = $row['name'];
            }
        }
        return $name;
    }

    public function get_count($teamid, $from_date, $status) {
        $sql = sprintf("select * from (select * from " . DB_ELIXIATECH . ".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.create_by = " . $teamid . " AND main.create_on_time < '" . $from_date . "' AND main.status=" . $status);
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $status = $this->_databaseManager->get_rowCount();
        } else {
            $status = "";
        }
        return $status;
    }

    public function get_open_count($teamid, $from_date, $status) {
        $sql = sprintf("select * from (select * from " . DB_ELIXIATECH . ".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.allot_to = " . $teamid . " AND main.create_on_time < '" . $from_date . "' AND main.status=" . $status);
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $status = $this->_databaseManager->get_rowCount();
        } else {
            $status = "";
        }
        return $status;
    }

    public function get_expiry_count($teamid, $from_date, $status) {
        $sql = sprintf("select * from (select * from " . DB_ELIXIATECH . ".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.allot_to = " . $teamid . " AND main.create_on_time < '" . $from_date . "' AND main.status=" . $status);
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $status = $this->_databaseManager->get_rowCount();
        } else {
            $status = "";
        }
        return $status;
    }

    public function get_inprogress_count($teamid, $from_date, $status) {
        $sql = sprintf("select * from (select * from " . DB_ELIXIATECH . ".sp_ticket_details order by uid desc ) as main group by main.ticketid having main.allot_to = " . $teamid . " AND main.create_on_time < '" . $from_date . "' AND main.status=" . $status);
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $status = $this->_databaseManager->get_rowCount();
        } else {
            $status = "";
        }
        return $status;
    }

    public function get_customerno($customerno) {
        $sql = sprintf("select customername,customercompany from " . DB_ELIXIATECH . ".customer where customerno =" . $customerno);
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $customername = $row['customercompany'];
            }
        }
        return $customername;
    }

    public function view_notes($id) {
        $sql = sprintf("SELECT  sn.note
                                ,t.name
                                ,u.realname
                                ,sn.create_on_date
                        FROM    " . DB_ELIXIATECH . ".sp_note sn
                        LEFT OUTER JOIN " . DB_ELIXIATECH . ".team t ON t.teamid = sn.create_by AND sn.is_customer = 0
                        LEFT OUTER JOIN " . DB_ELIXIATECH . ".user u ON u.userid = sn.create_by AND sn.is_customer = 1
                        WHERE   sn.ticketid = %d 
                        ORDER BY sn.noteid DESC;", Sanitise::Long($id));
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $notes = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $note['note'] = $row['note'];
                $note['name'] = isset($row['name']) ? $row['name'] : $row['realname'];
                $note['time'] = date('d-m-Y H:i:s', strtotime($row['create_on_date']));
                $notes[] = $note;
            }
            echo json_encode($notes);
        }
    }

    public function add_note($id, $note) {
        $todaysdate = date('Y-m-d H:i:s');
        $sql = sprintf("INSERT INTO `sp_note`(`note`
                        ,`ticketid`
                        ,`create_by`
                        ,`is_customer`
                        ,`create_on_date`)
                        VALUES ('%s',%d,%d,1,'%s');", Sanitise::String($note)
                , Sanitise::Long($id)
                , Sanitise::Long($_SESSION["userid"])
                , Sanitise::DateTime($todaysdate));
        $this->_databaseManager->executeQuery($sql);

            $sql1 = sprintf("SELECT sn.note
                                ,t.name
                                ,u.realname
                                ,sn.create_on_date
                        FROM    " . DB_PARENT . ".sp_note sn
                        LEFT OUTER JOIN " . DB_PARENT . ".team t ON t.teamid = sn.create_by AND sn.is_customer = 0
                        LEFT OUTER JOIN " . DB_PARENT . ".user u ON u.userid = sn.create_by AND sn.is_customer = 1
                        WHERE   sn.ticketid = %d 
                        ORDER BY sn.noteid DESC;", Sanitise::Long($id));

        $this->_databaseManager->executeQuery($sql1);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $notes = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $note = new stdClass();
                $note->note = isset($row['note'])?$row['note']:'';
                $note->name = isset($row['name']) ? $row['name'] : $row['realname'];
                $note->time = date('d-m-Y H:i:s', strtotime(isset($row['create_on_date']) ? $row['create_on_date'] :date('d-m-Y H:i:s')));
                $notes[]      = $note;
            }
            echo json_encode($notes);
        }
    }

    public function getmailid($id) {
        $sql = sprintf("SELECT  email_id     
                        FROM    " . DB_ELIXIATECH . ".report_email_list
                        WHERE   eid = %d 
                        LIMIT   1", Sanitise::Long($id));
        $this->_databaseManager->executeQuery($sql);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $notes = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $email_id = $row['email_id'];
            }
            return $email_id;
        }
    }

    public function get_all_other_issues() {
        $ticketcustid   = $_SESSION["customerno"];
        $uid            = $_SESSION["userid"];
        $issues         = Array();
        $query          = sprintf("SELECT  st.ticketid
                                    ,st.title
                                    ,st.ticket_type
                                    ,sttype.tickettype
                                    ,sp.priority as prname
                                    ,st.sub_ticket_issue
                                    ,st.customerid
                                    ,st.priority
                                    ,st.create_on_date
                                    ,st.create_by
                                    ,st.created_type
                                    ,st.uid
                                    ,st.create_on_date 
                                    ,st.create_platform 
                                    ,t.name AS allot_to
                                    ,std.status
                                    ,user.realname
                        FROM        " . DB_PARENT . ".sp_ticket as st 
                        INNER JOIN  " . DB_PARENT . ".sp_ticket_details std ON std.ticketid = st.ticketid AND std.uid = (SELECT MAX(uid) from sp_ticket_details sds where sds.ticketid=st.ticketid)
                        LEFT JOIN  " . DB_PARENT . ".team t ON t.teamid = std.allot_to
                        LEFT JOIN " . DB_PARENT . ".sp_tickettype as sttype on sttype.typeid = st.ticket_type 
                        LEFT JOIN " . DB_PARENT . ".sp_priority as sp on sp.prid = st.priority 
                        LEFT JOIN " . DB_PARENT . ".user ON user.userid = st.uid
                        LEFT JOIN " .DB_PARENT. ".ticket_user_mapping as tup ON st.ticketid =tup.ticketid 
                        WHERE       st.customerid= %d 
                        AND         tup.userid = %d
                        GROUP BY    std.ticketid
                        order by    st.ticketid DESC",Sanitise::Long($ticketcustid), Sanitise::Long($uid),Sanitise::Long($ticketcustid));
     
   $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $count = $this->_databaseManager->get_rowCount();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $support1 = new VOSupport();
                $ticketid = $row['ticketid'];
                $support1->ticketid         = $row['ticketid'];
                $support1->title            = $row['title'];
                $support1->ticket_type      = $row['tickettype'];
                $support1->sub_ticket_issue = $row['sub_ticket_issue'];
                $support1->customerid       = $row['customerid'];
                $support1->priority         = $row['prname'];
                $support1->create_on_date   = $row['create_on_date'];
                $support1->create_by        = $row['create_by'];
                $support1->created_type     = $row['created_type'];
                $support1->uid              = $row['uid'];
                $support1->allot_to         = $row['allot_to'];
                $support1->status           = $row['status'];
                $support1->realname         = $row['realname'];
                $support1->create_platform  = $row['create_platform'];
                $support1->timestamp        = date(speedConstants::DEFAULT_DATETIME, strtotime($row['create_on_date']));
                $issues[]                   = $support1;
            }
            return $issues;
        }
        return null;
    }
    function getShowToUserList($ticketid){
    $query = sprintf("SELECT userid,username FROM ticket_user_mapping WHERE ticketid = %d",Sanitise::Long($ticketid));
    $this->_databaseManager->executeQuery($query);
       if ($this->_databaseManager->get_rowCount() > 0) {
            $userids = array();
            while ($row = $this->_databaseManager->get_nextRow()) {
                $users['userid'] = $row['userid'];
                $users['username'] = $row['username'];
                $userids[]=$users;
            }
            return $userids;
        }
    }
}