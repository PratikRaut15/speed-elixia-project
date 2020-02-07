<div style="width:1120px;">
    <table style="width: 1120px;  border:1px solid;background-color:#CCCCCC;border-collapse:collapse;">
        <tr>
            <td colspan="5" style="width:1120px; text-align: center; text-transform:uppercase;">
                <h4 style="text-transform:uppercase;"><?php echo $report_name; ?></h4>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:left;font-weight:bold;">
                Start Date:  <?php echo date(speedConstants::DEFAULT_DATE, strtotime($STdate)); ?><br/>
                End Date: <?php echo date(speedConstants::DEFAULT_DATE, strtotime($EDdate)); ?> <br/>
                <?php if (!is_null($vgroupname)) { ?>
                    Group-name: <?php echo $vgroupname; ?><br/>
                <?php } ?>
            </td>
            <td colspan="2" style="text-align:right;font-weight:bold;">
                Generated By: <?php echo $_SESSION['username'] ?><br/>
                Generated On: <?php echo date('Y-m-d'); ?>
            </td>
        </tr>
    </table>
</div>

<hr/>
<style type='text/css'>
    table, td { border: solid 1px  #999999; color:#000000; }
    hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
</style>

<table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
    <tbody>
        <tr style='background-color:#CCCCCC;'>
            <td>Vehicle No</td>
            <td colspan='<?php echo count($totaldays); ?>'> <?php echo $table_header; ?></td>
            <td>Total</td>
        </tr>
        <?php echo $table_format; ?>
    </tbody>
</table>