<?php
include_once("session.php");
include("loginorelse.php");

include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");

class Vehlist {
    
}

$db = new DatabaseManager();
$jsondata = json_decode(($_REQUEST['vehdata']), true);

extract($jsondata);
$VehType = array_filter($vehid, function($vehElement) {
        return $vehElement['kind'] != "Warehouse";
});
$WehType = array_filter($vehid, function($vehElement) {
        return $vehElement['kind'] == "Warehouse";
});
//print_r($VehType);
//die();
////          foreach($vehid as $key=>$val){
////              echo $val['vehicleid'];
////          }
////          die();
//$vno = Array();
//$vehdata = array();
//$wehdata = array();
//if (!empty($vehid)) {
//    foreach ($vehid as $key => $val) {
//        $SQL = sprintf("SELECT vehicle.vehicleid
//                    ,vehicle.vehicleno
//                    ,vehicle.customerno
//                    ,customer.unitprice
//                    ,devices.warrantyexpiry as device_exp
//                    ,devices.expirydate as renewal_exp
//                    ,vehicle.kind
//                    FROM vehicle
//                        INNER JOIN devices ON vehicle.uid = devices.uid
//                        INNER JOIN unit ON unit.uid = vehicle.uid
//                        INNER JOIN customer ON customer.customerno=vehicle.customerno
//                        WHERE  vehicle.vehicleid=%d 
//                        AND vehicle.customerno=%d
//                        ORDER BY vehicle.vehicleid DESC", $val['vehicleid'], $cno);
//
//        $db->executeQuery($SQL);
//
//        if ($db->get_rowCount() > 0) {
//            $vcount++;
//            while ($row = $db->get_nextRow()) {
//                $veh = new Vehlist();
//                $veh->vehicleno = $row['vehicleno'];
//                $veh->vehicleid = $row['vehicleid'];
//                $veh->unitp = $row['unitprice'];
//                if ($row['device_exp'] == '0000-00-00' || $row['device_exp'] == '1970-01-01') {
//                    $veh->device_exp = '';
//                } else {
//                    $veh->device_exp = date("d M Y", strtotime($row['device_exp']));
//                }
//                if ($row['renewal_exp'] == '0000-00-00' || $row['renewal_exp'] == '1970-01-01') {
//                    $veh->renewal_exp = '';
//                } else {
//                    $veh->renewal_exp = date("d-m-Y", strtotime($row['renewal_exp']));
//                }
//                $veh->kind = $row['kind'];
//                if ($veh->kind == 'Warehouse') {
//                    $wehdata[] = $veh;
//                } else {
//                    $vehdata[] = $veh;
//                }
//            }
//        }
//    }
//}
?>
<!doctype html>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta charset="utf-8">
        <title>Invoice</title>
    </head>

    <style>

        html { overflow: auto; padding: 0.5in; }
        html { background: #999; cursor: default; }

        body { box-sizing: border-box; height: 12in; margin: 0 auto; overflow: hidden; padding: 0.2in; width: 9in; }
        body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }

        /* header */

        header { margin: 7.5em 0 1em; }
        //header:after { clear: both; content: ""; display: table; }
        table,tr,td,th{
            border:1px solid #000;
        }

        @media print {
            * { -webkit-print-color-adjust: exact; }
            html { background: none; padding: 0; }
            body { box-shadow: none; margin: 0; }
            span:empty { display: none; }
            .noprint { display: none; }
        }
        @page { margin: 0; }
    </style>


    <body>
        <header>
            <span style="text-align: center;font-weight: bold">Invoice Details</span>
        </header>
        <table style="width:825px;" cellpadding="0" cellspacing="0">
            <?php
            if ($invtype == '0') {
                $x = 0;
                ?>
                <tr>
                    <td style="width: 50%">
                        <table style="font-size:11px; width: 100%;text-align: center;"cellpadding="0" cellspacing="0">
                            <tr>
                            <th>Sr No.</th>
                            <th>Vehicle No</th>
                            <th>Warranty Expiry</th>
                            </tr>
                            <?php
                            foreach ($VehType as $key => $val) {
                                $x++;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $x; ?>
                                    </td>
                                    <td>
        <?php echo $val['vehicleno']; ?>
                                    </td>
                                    <td>
        <?php echo $val['device_exp']; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </td>
                    <?php if (!empty($WehType)) {
                        $x = 0;
                        ?>
                        <td style="width: 50%">
                            <table style="font-size:11px; width: 100%;text-align: center;"cellpadding="0" cellspacing="0">
                                <tr>
                                <th>Sr No.</th>
                                <th>WareHouse</th>
                                <th>Warranty Expiry</th>
                                </tr>
                                <?php
                                foreach ($WehType as $key => $val) {
                                    $x++;
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $x; ?>
                                        </td>
                                        <td>
            <?php echo $val['vehicleno']; ?>
                                        </td>
                                        <td>
            <?php echo $val['device_exp']; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
            <?php
            if ($invtype == '1' || $invtype == '5') {
                $x = 0;
                ?>
                <tr>
                    <td style="width: 50%">
                        <table style="font-size:11px; width: 100%;text-align: center;"cellpadding="0" cellspacing="0">
                            <tr>
                            <th>Sr No.</th>
                            <th>Vehicle No</th>
                            </tr>
                            <?php
                            foreach ($VehType as $key => $val) {
                                $x++;
                                ?>
                                <tr>
                                    <td>
                                            <?php echo $x; ?>
                                    </td>
                                    <td>
        <?php echo $val['vehicleno']; ?>
                                    </td>
                                </tr>            
                                <?php
                            }
                            ?>
                        </table>
                    </td>
                    <?php if (!empty($wehdata)) {
                        $x = 0;
                        ?>
                        <td style="width: 50%">
                            <table style="font-size:11px; width: 100%;text-align: center;"cellpadding="0" cellspacing="0">
                                <tr>
                                <th>Sr No.</th>
                                <th>WareHouse</th>
                                </tr>
                                <?php
                                foreach ($WehType as $key => $val) {
                                    $x++;
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $x; ?>
                                        </td>
                                        <td>
            <?php echo $val['vehicleno']; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>