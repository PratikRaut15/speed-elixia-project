<?php
error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");


echo "<script src='../../scripts/tinymce/tinymce.min.js' type='text/javascript'></script>";
$today = date("Y-m-d H:i:s");
if (isset($_POST['share'])) {
    $db = new DatabaseManager();
    $from = 'sanketsheth@elixiatech.com';
    $subject = GetSafeValueString(trim($_POST['subject']), "string");
    $message = GetSafeValueString(trim($_POST['emailtemplate']), "string");
    $moduleid = GetSafeValueString($_POST['module'], "long");
    $sent = GetSafeValueString($_POST['time'], "long");
    $sentdt = '';
    if ($sent == 1) {
        $sentdt = GetSafeValueString(trim($_POST['sentdt']), "string");
        if ($sentdt == '' || $sentdt == '00-00-0000 00:00') {
            $sentdt = '';
        } else {

            $sentdt = date("Y-m-d H:i:s", strtotime($sentdt));
        }
    }
    $SQL = "INSERT INTO ".DB_PARENT.".knowledgebase_share (kbs_from,kbs_subject,kbs_message,moduleid,createdby,createdon,updatedby,updatedon)
            VALUES('%s','%s','%s',%d,%d,'%s',%d,'%s')";
    $finalSQL = sprintf($SQL, $from, $subject, $message, $moduleid, $_SESSION['sessionteamid'], $today, $_SESSION['sessionteamid'], $today);
    $db->executeQuery($finalSQL);
    $lastid = $db->get_insertedId();
    if ($lastid != 0) {
        $user_list = getAllUserdetails();
        foreach ($user_list as $user) {
            $Query = "INSERT INTO ".DB_PARENT.".knowledgebase_emaillog (kbsid,kb_to,kb_from,kb_subject,kb_message,islater,	laterdatetime,issent,customerno,createdby,createdon,updatedby,updatedon)
                      VALUES(%d,'%s','%s','%s','%s',%d,'%s',%d,%d,%d,'%s',%d,'%s')";
            $finalQuery = sprintf($Query, $lastid, $user->email, $from, $subject, $message, $sent, $sentdt, 0, $user->customerno, $_SESSION['sessionteamid'], $today, $_SESSION['sessionteamid'], $today);
            $db->executeQuery($finalQuery);
        }
    }

    header('location:knowledge_share.php');
}

function getAllUserdetails() {
    $db = new DatabaseManager();
    $data = Array();
    $SQL = "SELECT userid,customerno,realname,email,phone FROM user WHERE isdeleted =0 AND email <> '' AND realname <> 'Elixir' AND customerno NOT IN (1) ORDER BY customerno ASC";
    $db->executeQuery($SQL);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $user = new stdClass();
            $user->userid = $row['userid'];
            $user->customerno = $row['customerno'];
            $user->realname = $row['realname'];
            $user->email = $row['email'];
            $user->phone = $row['phone'];
            $data[] = $user;
        }
    }
    return $data;
}

function getModuleName() {
    $db = new DatabaseManager();
    $data = Array();
    $Query = "SELECT * FROM ".DB_PARENT.".modules";
    $db->executeQuery($Query);
    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $kbs = new stdClass();
            $kbs->moduleid = $row['moduleid'];
            $kbs->modulename = $row['modulename'];
            $data[] = $kbs;
        }
    }
    return $data;
}

include("header.php");
?>

<div class="panel">
    <div class="paneltitle" align="center">Knowledge Base Share</div>
    <div class="panelcontents">
        <form method="POST" name="bform" id="bform" onsubmit=" return Validate_test();">
            <table>
                <tr>
                    <td style="text-align: center;">
                        <span id="err_email" style="display: none;color: #FF0000;font-weight: bold;">Please enter email message</span>
                        <span id="err_sub" style="display: none;color: #FF0000;font-weight: bold;">Please enter email subject</span>
                        <span id="err_module" style="display: none;color: #FF0000;font-weight: bold;">Please Select a Module</span>
                        <span id="err_time" style="display: none;color: #FF0000;font-weight: bold;">Please Select a Time</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Write Email Subject Below :
                    </td>
                </tr>
                <tr class="emailsubject">
                    <td>
                        <textarea name="subject" id="subject" rows="3" cols="50"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        Write Email Body Below :
                    </td>
                </tr>
                <tr class="emailbody">
                    <td>
                        <textarea name="emailtemplate" id="emailtemplate" class="mceEditor"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label> Select Module To Send To :</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        $result = getModuleName();
                        foreach ($result as $this_result) {
                            ?>
                            <?php echo $this_result->modulename; ?><input type="radio" id="module" name="module" value="<?php echo $this_result->moduleid; ?>">&nbsp;&nbsp;&nbsp;&nbsp;

                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Select Send Option:</label>
                    </td>
                </tr>
                <tr>
                    <td>Now <input type="radio" id="now" name="time" value="0"/>&nbsp;&nbsp;&nbsp;&nbsp;
                        Later <input type="radio" id="later" name="time" value="1"/>
                    </td>

                </tr>
                <tr class="dt" style="display: none;">
                    <td>Select Sent Date Time:&nbsp;&nbsp;&nbsp;
                        <input type="text" name="sentdt" id="senddt" style="height: 30px;"/><button id="trigger1">...</button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name="share"  id="share" class="btn btn-primary" value="Share"/>
                    </td>
                </tr>


            </table>
        </form>
    </div>
</div>
<?php
include("footer.php");
?>
<script type="text/javascript">
    tinymce.init({
        mode: "textareas",
        menubar: false,
        editor_selector: "mceEditor",
        editor_deselector: "mceNoEditor",
        elements: "emailtemplate",
        statusbar: false,
        plugins: "autoresize",
        width: '100%',
        height: 400,
        autoresize_min_height: 400,
        autoresize_max_height: 800

    });



    jQuery('#later').click(function () {
        jQuery('.dt').show();
    });
    jQuery('#now').click(function () {
        jQuery('.dt').hide();

    });
    Calendar.setup(
            {
                inputField: "senddt", // ID of the input field
                ifFormat: "%d-%m-%Y %H:%M", // the date format
                showsTime: true,
                button: "trigger1" // ID of the button
            });

    function Validate_test() {
        var isChecked = jQuery('input[name="module"]').is(':checked');
        var isCheckedTime = jQuery('input[name="time"]').is(':checked');
        if (jQuery("#subject").val() == '') {
            jQuery("#err_sub").show();
            jQuery("#err_sub").fadeOut(6000);
            return false;
        } else if (!isChecked) {
            jQuery("#err_module").show();
            jQuery("#err_module").fadeOut(6000);
            return false;
        } else if (!isCheckedTime) {
            jQuery("#err_time").show();
            jQuery("#err_time").fadeOut(6000);
            return false;
        } else {
            jQuery("#bform").submit();
        }

    }
</script>
