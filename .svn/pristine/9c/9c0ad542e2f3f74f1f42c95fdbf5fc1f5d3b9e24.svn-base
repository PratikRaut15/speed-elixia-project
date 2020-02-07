<?php
include_once("session.php");
include_once("../../lib/system/DatabaseManager.php");
$db = new DatabaseManager();

$SQL = sprintf("SELECT teamid,name FROM team");
$db->executeQuery($SQL);

$team_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $team['id'] = $row['teamid'];
        $team['name'] = $row['name'];
        $team_array[] = $team;
    }
}
$month = isset($_REQUEST['monthPicker']) ? $_REQUEST['monthPicker'] : date('M Y');

if (isset($_REQUEST['teamid'])) {
    $teamid = $_REQUEST['teamid'];
} else {
    $teamid = GetLoggedInUserId();
}

$SQL = sprintf("SELECT `role` FROM team WHERE teamid=%d", $teamid);

$db->executeQuery($SQL);

if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $role = $row['role'];
    }
}

$SQL1 = sprintf("SELECT value, base_points, point_desc,id,name,target,`constraint`,type,role FROM perfomance_parameter WHERE role='%s' OR `role`='All'", $role);
$db->executeQuery($SQL1);

$performance_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $performance['id'] = $row['id'];
        $performance['point_desc'] = $row['point_desc'];        
        $performance['role'] = $row['role'];
        $performance['type'] = $row['type'];
        $performance['target'] = $row['target'];
        $performance['constraint'] = $row['constraint'];
        $performance['name'] = $row['name'];
        $performance['base_points'] = $row['base_points'];        
        $performance['value'] = $row['value'];        
        $performance_array[] = $performance;
    }
}

$SQL2 = sprintf("SELECT product_name, parameter, license_weightage FROM sales_product;");
$db->executeQuery($SQL2);

$product_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $product['product_name'] = $row['product_name'];
        $product['parameter'] = $row['parameter'];
        $product['license_weightage'] = $row['license_weightage'];        
        $product_array[] = $product;
    }
}

$SQL3 = sprintf("SELECT management_points.description, management_points.timestamp, management_points.points, team.name FROM management_points INNER JOIN team ON management_points.team_creator_id = team.teamid WHERE management_points.team_id =".$teamid);
$db->executeQuery($SQL3);

$m_array = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $m['description'] = $row['description'];
        $m['timestamp'] = date("d-m-Y", strtotime($row['timestamp']));
        $m['points'] = $row['points'];        
        $m['name'] = $row['name'];                
        $m_array[] = $m;
    }
}


function getAchievedValue($id, $teamid, $date) {
    $db = new DatabaseManager();
    $today = date('Y-m-d');
    $date_array = explode(' ', $date);
    $count = 0;
    if ($id == 1) {
        $SQL1 = "   SELECT	count(customerno) AS count1
                    FROM 	customer 
                    WHERE       salesid='" . $teamid . "' 
                    AND 	year(createdtime) LIKE '%" . $date_array['1'] . "%' 
                    AND  	monthname(createdtime) LIKE '%" . $date_array['0'] . "%'";
    } elseif ($id == 2) {
        $SQL1 = "   SELECT	count(uid) AS count1
                    FROM 	unit
                    INNER JOIN  customer ON customer.customerno = unit.customerno
                    WHERE       customer.salesid='" . $teamid . "' 
                    AND 	year(customer.createdtime) LIKE '%" . $date_array['1'] . "%' 
                    AND  	monthname(customer.createdtime) LIKE '%" . $date_array['0'] . "%'";
    } elseif ($id == 3) {
        $SQL1 = "   SELECT	count(*) AS count1
                    FROM 	sales_pipeline_history
                    WHERE       stageid = 5 AND teamid = '" . $teamid . "' 
                    AND 	year(timestamp) LIKE '%" . $date_array['1'] . "%' 
                    AND  	monthname(timestamp) NOT LIKE '%" . $date_array['0'] . "%'";
    } elseif ($id == 5) {
        $SQL = "   SELECT	count(pipelineid) AS count1
                    FROM 	sales_pipeline
                    WHERE       teamid='" . $teamid . "' 
                    AND 	year(timestamp) LIKE '%" . $date_array['1'] . "%' 
                    AND  	monthname(timestamp) LIKE '%" . $date_array['0'] . "%' AND stageid = 5";
        $db->executeQuery($SQL);

        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $win = $row['count1'];
            }
        }
        $SQL2 = "   SELECT	count(pipelineid) AS count1
                    FROM 	sales_pipeline
                    WHERE       teamid='" . $teamid . "' 
                    AND 	year(timestamp) LIKE '%" . $date_array['1'] . "%' 
                    AND  	monthname(timestamp) LIKE '%" . $date_array['0'] . "%'";
        $db->executeQuery($SQL2);

        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $other = $row['count1'];
            }
        }

        $SQL1 = "SELECT FLOOR((" . $win . " * 100)/" . $other . ") AS count1";
    } elseif ($id == 6) {
        $SQL1 = "   SELECT	count(pipelineid) AS count1
                    FROM 	sales_pipeline
                    WHERE       teamid='" . $teamid . "' 
                    AND 	year(timestamp) LIKE '%" . $date_array['1'] . "%' 
                    AND  	monthname(timestamp) LIKE '%" . $date_array['0'] . "%' AND stageid IN (9,10)";
    } elseif ($id == 7) {
        $SQL1 = "   SELECT (management_points + (SELECT points FROM management_points WHERE team_id='" . $teamid . "' 
                    AND 	year(timestamp) LIKE '%" . $date_array['1'] . "%' 
                    AND  	monthname(timestamp) LIKE '%" . $date_array['0'] . "%' )) as count1"
                . " FROM    team"
                . " WHERE teamid = '".$teamid."'";
    } elseif ($id == 8) {
        $SQL1 = "   SELECT  count(unit.unitno) AS count1 
                    FROM    customer c
                    LEFT OUTER JOIN unit ON c.customerno = unit.customerno AND unit.trans_statusid not in (10) 
                    WHERE   c.renewal = -1 AND c.customerno <> 1
                    AND     c.salesid = " . $teamid . "
                    GROUP BY c.customerno";
    } elseif ($id == 9) {
        $SQL1 = "   SELECT  count(unit.unitno) AS count1 
                    FROM    customer c
                    LEFT OUTER JOIN unit ON c.customerno = unit.customerno AND unit.trans_statusid not in (10) 
                    WHERE   c.renewal = -1 AND c.customerno <> 1
                    AND     c.salesid = " . $teamid . "
                    AND     unit.uid IN (SELECT newunitid 
                                            FROM    trans_history_new 
                                            WHERE   transtypeid = 1 
                                            AND     bucketstatusid = 1
                                            AND     createdon < DATE_SUB('" . $today . "', INTERVAL 15 DAY)
                                            AND     newunitid = unit.uid)    
                    GROUP BY c.customerno";
    } elseif ($id == 10) {
        $SQL1 = "   SELECT 	count(customerno) AS count1
                    FROM 	customer 
                    WHERE 	salesid=" . $teamid . " 
                    AND         year(createdtime) LIKE '%" . $date_array['1'] . "%' 
                    AND 	monthname(createdtime) LIKE '%" . $date_array['0'] . "%'
                    AND 	customerno IN (	select d.customerno 
                                                FROM 	devices d 
                                                INNER JOIN unit ON unit.uid=d.uid 
                                                group by d.customerno having count(d.customerno) > 50);";
    } elseif ($id == 11) {

        $SQL1 = "   SELECT      count(d.device_invoiceno) AS count1
                    FROM        `devices` as d
                    INNER JOIN  unit ON unit.uid = d.uid
                    INNER JOIN  simcard ON simcard.id = d.simcardid
                    INNER JOIN  customer ON customer.customerno = d.customerno
                    WHERE       d.device_invoiceno = '' 
                    AND         d.customerno NOT IN (-1,1)";
    } elseif ($id == 12) {

        $SQL1 = "   SELECT      count(d.device_invoiceno) AS count1
                    FROM        `devices` as d
                    INNER JOIN  unit ON unit.uid = d.uid
                    INNER JOIN  simcard ON simcard.id = d.simcardid
                    INNER JOIN  customer ON customer.customerno = d.customerno
                    WHERE       d.device_invoiceno <> '' 
                    AND         d.expirydate BETWEEN '" . $today . "' AND DATE_ADD('" . $today . "', INTERVAL 7 DAY)
                    AND         d.customerno NOT IN (-1,1)";
    } elseif ($id == 13) {
        $SQL1 = "SELECT count(unit.unitno) AS count1
                FROM    vehicle 
                INNER JOIN devices ON devices.uid = vehicle.uid 
                INNER JOIN unit ON devices.uid = unit.uid 
                LEFT OUTER JOIN simcard ON simcard.id = devices.simcardid 
                WHERE   vehicle.isdeleted= 0 
                AND     devices.expirydate < '" . $today . "' 
                AND     unit.customerno NOT IN (-1,1) 
                AND     devices.expirydate !='1970-01-01' 
                AND     devices.expirydate!='0000-00-00' 
                AND     unit.trans_statusid NOT IN(22,23,24,10)";
    } elseif ($id == 15) {
        $SQL1 = "   SELECT  count(id) AS count1 
                    FROM    otherinvoices 
                    WHERE   pay_expected_date < '" . $today . "'";
    } elseif ($id == 16) {
        $SQL1 = "   SELECT  count(invoiceid) AS count1 
                    FROM    invoice 
                    WHERE   inv_date < DATE_SUB('" . $today . "', INTERVAL 60 DAY)";
    } elseif ($id == 17) {
        $SQL = "   SELECT  count(customerno) AS count1 
                    FROM    customer 
                    WHERE   renewal IN (-1,-2,-3)";
        $db->executeQuery($SQL);

        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $reten = $row['count1'];
            }
        }

        $SQL2 = "    SELECT  count(customerno) AS count1 
                    FROM    customer";
        $db->executeQuery($SQL2);

        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $total = $row['count1'];
            }
        }
        $SQL1 = "SELECT FLOOR((" . $reten . " * 100)/" . $total . ") AS count1";
    } elseif ($id == 18) {
        $SQL1 = "  SELECT  count(ticketid) AS count1 
                    FROM    sp_ticket 
                    WHERE   create_by=" . $teamid . " 
                    AND     year(create_on_date) LIKE '%" . $date_array['1'] . "%' 
                    AND     monthname(create_on_date) LIKE '%" . $date_array['0'] . "%'";
    } elseif ($id == 19) {
        $SQL1 = " SELECT  count(t.ticketid) AS count1
                FROM    sp_ticket t
                INNER JOIN sp_ticket_details td ON td.ticketid = t.ticketid
                WHERE   td.allot_from =" . $teamid . "
                AND     td.allot_from <> td.allot_to
                AND     year(td.create_on_time) LIKE '%" . $date_array['1'] . "%' 
                AND     monthname(td.create_on_time) LIKE '%" . $date_array['0'] . "%'
                GROUP BY t.ticketid";
    } elseif ($id == 20) {
        $SQL1 = "   SELECT count(*) AS count1 FROM (SELECT  count(t.ticketid) AS count
                FROM    sp_ticket t
                INNER JOIN sp_ticket_details td1 ON td1.ticketid = t.ticketid
                INNER JOIN sp_ticket_details td2 ON td2.ticketid = t.ticketid
                WHERE   td1.allot_from =" . $teamid . "
                AND     td1.eclosedate <> td2.eclosedate    
                AND     td1.allot_from = td1.allot_to
                AND     year(t.create_on_date) LIKE '%" . $date_array['1'] . "%' 
                AND     monthname(t.create_on_date) LIKE '%" . $date_array['0'] . "%'
                GROUP BY t.ticketid) AS A";
    } elseif ($id == 21) {
        $SQL1 = " SELECT  count(uid) AS count1
                FROM    sp_ticket_details
                WHERE   allot_to=" . $teamid . "
                AND     year(create_on_time) LIKE '%" . $date_array['1'] . "%' 
                AND     monthname(create_on_time) LIKE '%" . $date_array['0'] . "%'
                AND     eclosedate < '" . $today . "'
                AND     status <> 2
                GROUP BY ticketid";
    } elseif ($id == 22) {
        $SQL1 = "SELECT  count(ticketid) AS count1
                FROM    sp_ticket
                WHERE   create_by =" . $teamid . "
                AND     year(create_on_date) LIKE '%" . $date_array['1'] . "%' 
                AND     monthname(create_on_date) LIKE '%" . $date_array['0'] . "%'
                AND     TIMESTAMPDIFF(HOUR, `raised_on_date`, `create_on_date`) > 3";
    } else {
        $SQL1 = "SELECT 0";
    }
    $db->executeQuery($SQL1);

    if ($db->get_rowCount() > 0) {
        while ($row = $db->get_nextRow()) {
            $count += $row['count1'];
        }
    }
    return $count;
}

include("header.php");
?>
<div id="container">
    <div class="panel">
        <form id="myScoreForm" name="myScoreForm" action="scorecard.php">
            <div class="paneltitle" align="center">Scorecard</div>
            <div class="panelcontents">
                <table width="60%">
                    <?php if (IsHead()) { ?>
                        <tr>
                            <td>Select Team member :<?php echo $_POST['teamid']; ?></td>
                            <td>
                                <select id="teamid" name="teamid">
                                    <option value="-1">Select team member</option>
                                    <?php
                                    foreach ($team_array as $data) {
                                        if ($teamid == $data['id']) {
                                            echo '<option value="' . $data['id'] . '" selected>' . $data['name'] . '</option>';
                                        } else {
                                            echo '<option value="' . $data['id'] . '" >' . $data['name'] . '</option>';
                                        }
                                    }
                                    ?>         
                                </select> 
                            </td> 
                        </tr>
                    <?php } else { ?>
                        <tr><td></td> <td><input id="teamid" type="hidden" value="<?php echo GetLoggedInUserId(); ?>"/> </td> </tr>
                    <?php }
                    ?>
                    <tr><td>Month :</td><td> <input name="monthPicker" id="monthPicker" class="date-picker" value="<?php echo $month; ?>"/></td></tr>
                </table>
            </div>
        </form>
    </div>

                    <?php
                    $elixia_score = 0;
                    if (!empty($performance_array)) {
                        foreach ($performance_array as $perf) {
                            $value = getAchievedValue($perf['id'], $teamid, $month);
                            $base_points = $perf['base_points'];
                            $points_collected = $base_points + ($value*$perf['value']);
                            $elixia_score+=$points_collected;
                        }
                    }
                             ?>   
    
    <div id="cardTemplate">
        <div id="header" style="text-align: center;">Your Score: <b> <?php echo ($elixia_score); ?> </b></div>
        <div id="content">
            <div style="padding:10px;text-align: center;">
                <table id="dataTable" border="1" cellpadding="0" cellspacing="0">
                    <tr><th class="center">Performance Indicator</th><th class="center">Constraint</th><th class="center">Target</th><th class="center">Score Description</th><th class="center">Achieved</th><th class="center">Points Collected</th></tr>
                    <?php
                    if (!empty($performance_array)) {
                        foreach ($performance_array as $perf) { 
                            $value = getAchievedValue($perf['id'], $teamid, $month);
                            $rowClass = 'notok';
                            if ($perf['constraint'] == 1) {
                                $constraint = '>';
                                if ($value > $perf['target']) {
                                    $rowClass = 'ok';
                                }
                            } elseif ($perf['constraint'] == 2) {
                                $constraint = '<';
                                if ($value < $perf['target']) {
                                    $rowClass = 'ok';
                                }
                            } elseif ($perf['constraint'] == 3) {
                                $constraint = '=';
                                if ($value == $perf['target']) {
                                    $rowClass = 'ok';
                                }
                            } else {
                                $constraint = '';
                                $rowClass = 'ok';
                            }
                            switch ($perf['type']) {
                                case 1:
                                    $symbol = '';
                                    break;
                                case 2:
                                    $symbol = '%';
                                    break;
                                case 3:
                                    $symbol = '';
                                    break;
                                case 4:
                                    $symbol = 'times';
                                    break;
                                case 5:
                                    $symbol = 'Rs';
                                    break;
                                default:
                                    break;
                            }
                            $base_points = $perf['base_points'];
                            $points_collected = $base_points + ($value*$perf['value']);
                            echo '<tr class="' . $rowClass . '"><td class="center">' . $perf['name'] . '</td><td class="center">' . $constraint . '</td><td class="center">' . $perf['target'] . ' ' . $symbol . '</td><td class="center">'.$perf['point_desc'].'</td><td class="center">' . $value . ' ' . $symbol . '</td><td class="center">'.$points_collected.'</td></tr>';
                        }
                    } else {
                        echo '<tr><td class="center" colspan="4">Data not available</td></tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <br/>
    <div id="cardTemplate">
        <div id="header" style="text-align: center;">License Weight Chart (LCW)</div>
        <div id="content">
            <div style="padding:10px;text-align: center;">
                <table id="dataTable" border="1" cellpadding="0" cellspacing="0">
                    <tr><th class="center">Product</th><th class="center">Parameters</th><th class="center">Points</th></tr>
                    <?php
                    if (!empty($product_array)) {
                        foreach ($product_array as $perf) { 
                            echo '<tr><td class="center">' . $perf['product_name'] . '</td><td class="center">' . $perf['parameter'] . '</td><td class="center">' . $perf['license_weightage'] . '</td></tr>';
                            }
                    
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>    
    
    <br/>
    <div id="cardTemplate">
        <div id="header" style="text-align: center;">Management Points</div>
        <div id="content">
            <div style="padding:10px;text-align: center;">
                <table id="dataTable" border="1" cellpadding="0" cellspacing="0">
                    <tr><th class="center">Action Taken By</th><th class="center">Action Taken For</th><th class="center">Action Taken On</th><th class="center">Management Points</th></tr>
                    <?php
                    if (!empty($m_array)) {
                        foreach ($m_array as $perf) { 
                            echo '<tr><td class="center">' . $perf['name'] . '</td><td class="center">' . $perf['description'] . '</td><td class="center">' . $perf['timestamp'] . '</td><td class="center">' . $perf['points'] . '</td></tr>';
                            }
                    
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>    

    <br/>
    <div id="cardTemplate">
        <div id="header" style="text-align: center;">Incentive Mechanism</div>
        <div id="content">
            <div style="padding:10px;text-align: center;">
                <table id="dataTable" border="1" cellpadding="0" cellspacing="0">
                    <tr><th class="center">10 points</th><th class="center">INR 1000 /-</th></tr>
                </table>
            </div>
        </div>
    </div>    

    <br/>
    <div id="cardTemplate">
        <div id="header" style="text-align: center;">Threshold Level</div>
        <div id="content">
            <div style="padding:10px;text-align: center;">
                <table id="dataTable" border="1" cellpadding="0" cellspacing="0">
                    <tr><th class="center">Minimum Points to be collected for incentives</th><th class="center">70</th></tr>
                </table>
            </div>
        </div>
    </div>    
    
</div>
<script>
    $(document).ready(function () {
        $('#monthPicker').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function (dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
                $('#myScoreForm').submit();
            }
        });


    });
</script>

<style>

    .ui-datepicker-calendar {
        display: none;
    }
    #container div{
        position: relative;
    }
    .center{
        text-align: center;
        padding: 3px;
    }
    .left{
        text-align: left;
        padding: 3px;
    }
    #header{
        border-top-right-radius: 10px;
        display: block;
        font-size: large;
        background: lightgray;
        padding: 10px;
        border-bottom: 1px solid black;
    }
    #content{
        display: block;
        width: 100%;
        background: #8FC4E8;
    }
    #dataTable{
        border-collapse:collapse;
        background: lightyellow;
        width:90%;
        margin-left: auto;
        margin-right: auto;
        font-size: 110%;

    }
    #cardTemplate{
        width: 700px;
        height: auto;
        border: 1px solid black;
        top: 80px;
        left: 20%;
        border-top-right-radius: 10px;

        animation-name: popupDiv;
        animation-iteration-count: 1;
        animation-timing-function: ease-out;
        animation-duration: 0.3s;
    }

    @keyframes popupDiv {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateY(0);
        }
    }
    .ok{
        background: lightgreen;
    }
    .notok{
        background: #D50000;
        color:white;
    }
</style>