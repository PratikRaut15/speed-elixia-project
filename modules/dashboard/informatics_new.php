<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//date_default_timezone_set("Asia/Calcutta");
include '../panels/header.php';
?>
<style type="text/css">
    #pageloaddiv {
        position: fixed;
        left: 0px;
        top: -80px;
        width: 100%;
        height: 100%;
        z-index: 1000;
        background: url('../../images/progressbar.gif') no-repeat center center;
    }
</style>
<div id="pageloaddiv" style='display:none;'></div>
<div class="entry" style='min-height:400px;'>
    <center>
        <br/>
        <!-- starts, input table -->
        <?php
        $month_start = strtotime('first day of last month', time());
        $month_end = strtotime('last day of last month', time());
        $dmonthstart = date("d-m-Y", $month_start);
        $dmonthend = date("d-m-Y", $month_end);
        $s_date = $dmonthstart;
        $e_date = $dmonthend;
        ?>
        <form method="post" action="informatics_new.php" onsubmit="getInformatics();return false;" id="infoForm">
            <table>
                <thead>
                    <tr><th colspan="100%" id="formheader">Informatics Report - New Format</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="100%">
                            <span style="display: none;" name="error1" id="error1">Already in progress. Please Refresh to start again</span>
                            <span style="display: none;" name="error2" id="error2">Data Not Available</span>
                            <span style="display: none;" name="error3" id="error3">Please Check The Dates</span>
                            <span style="display: none;" name="error4" id="error4">Please Select Dates With Difference Of Not More Than 1 Month</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Start Date</td>
                        <td>End Date</td>
                        <td><input type='hidden' value='get_informatics_report' name='to_get'/></td>
                        <td colspan="2">Detailed Report</td>
                        <td colspan="2">Summarized Report</td>
                    </tr>
                    <tr>
                        <td><input type="text" required="" value="<?php echo $s_date; ?>" name="SDate" id="SDate"></td>
                        <td><input type="text" required="" value="<?php echo $e_date; ?>" name="EDate" id="EDate"></td>
                        <td><input type="submit" name="GetReport" value="Get Report" class="g-button g-button-submit" id='informaticsSubmit'></td>
                        <td>
                            <a href='javascript:void(0)' onclick="get_InformaticsDownload(<?php echo $_SESSION['customerno'] ?>, 'pdf', 1);">
                                <img  title="Export Detailed Report to PDF" class="exportIcons" alt="Export Detailed Report to PDF" src="../../images/pdf_icon.png">
                            </a>
                        </td>
                        <td>
                            <a href='javascript:void(0)' onclick="get_InformaticsDownload(<?php echo $_SESSION['customerno'] ?>, 'xls', 1);">
                                <img title="Export Detailed Report to Excel" class="exportIcons" alt="Export Detailed Report to Excel" src="../../images/xls.gif">
                            </a>
                        </td>
                        <td>
                            <a href='javascript:void(0)' onclick="get_InformaticsDownload(<?php echo $_SESSION['customerno'] ?>, 'pdf', 2);">
                                <img  title="Export Summarized Report to PDF" class="exportIcons" alt="Export Summarized Report to PDF" src="../../images/pdf_icon.png">
                            </a>
                        </td>
                        <td>
                            <a href='javascript:void(0)' onclick="get_InformaticsDownload(<?php echo $_SESSION['customerno'] ?>, 'xls', 2);">
                                <img title="Export Summarized Report to Excel" class="exportIcons" alt="Export Summarized Report to Excel" src="../../images/xls.gif">
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <br/>
    </center>
    <!-- ends, input table -->
    <center><div id="centerDiv"></div></center>
</div>
<!-- footer starts here -->
</div>
</div>
<div style="clear: both;">&nbsp;</div>
</div>
</div>
</div>
</div>
<?php include '../panels/footer.php'; ?>
<!-- end #footer -->

<script src="../../scripts/highcharts/js/highcharts.js" type='text/javascript'></script>
<script src="../../scripts/highcharts/js/modules/exporting.js"></script>
<script src="../../scripts/informatics/informatics_new.js" type="text/javascript"></script>
</html>
