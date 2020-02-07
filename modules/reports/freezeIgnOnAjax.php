<?php
include 'freeze_ignitionOn_function.php';
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    $today = date('d-m-Y');
    $reportDate = date("d-m-Y",strtotime('-1 day'));
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(5000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(5000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(5000)</script>";
    $error3 = "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(5000)</script>";
    $error4 = "<script>jQuery('#error4').show();jQuery('#error4').fadeOut(5000)</script>";
    $error5 = "<script>jQuery('#error5').show();jQuery('#error5').fadeOut(5000)</script>";
    $STdate = $_POST['STdate'];
    $EDdate = $_POST['EDdate'];
    if (isset($_SESSION['ecodeid'])) {
        /*Client Code Validation */
        $validation = clientCodeValidation($_POST['s_start'], $_POST['e_end'], $_POST['days'], $STdate, $EDdate);
        if (isset($validation) && !empty($validation)) {
            if ($validation['isError'] == 1) {
                echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
                die();
            } else {
                $STdate = date('d-m-Y', strtotime($validation['reportStartDate']));
                $EDdate = date('d-m-Y', strtotime($validation['reportEndDate']));
                echo "<script>jQuery('#SDate').val('" . $STdate . "');</script>";
                echo "<script>jQuery('#EDate').val('" . $EDdate . "');</script>";
            }
        }
    }
    $datecheck = datediff($STdate, $EDdate);
    $datediffcheck = date_SDiff($STdate, $EDdate);

    if(strtotime($EDdate)>=strtotime($STdate)){
        if ($datediffcheck <= 30) {
            if(strtotime($EDdate)<=strtotime($today)){
                $vehicleid = 0;
                $vehicleid = GetSafeValueString($_POST['vehicleid'], 'long');
                if ($vehicleid != 0) {
                    $data = getVehicleFreezeIgnOn($STdate, $EDdate, $vehicleid, $_POST['vehicleno'], $_SESSION['customerno'], speedConstants::REPORT_HTML);
                    if (isset($data) && !empty($data)) {
                        $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
                        $placehoders['{{CONDITIONAL_HEADER}}'] = $data['conditionalHeader'];
                        $placehoders['{{CONDITIONAL_ABBREVIATIONS}}'] = $data['conditional_abbreviations'];
                        $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
                        $html = file_get_contents('pages/panels/freezeIgnOnReportTemplate.php');
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        echo $html;
                    }
                } 
                else {
                    echo $error3;
                }
            }
            else{
                echo $error4;
            }
        } 
        else {
            echo $error2;
        }
    }
    else{
         echo $error5;
    }
}
?>
