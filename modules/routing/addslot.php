<?php
/**
 * City master form
 */
?>
<style>
    #ajaxstatus{text-align:center;font-weight:bold;display:none}
    .mandatory{color:red;font-weight:bold;}
    #addorders table{width:50%;}
    #addorders .frmlblTd{text-align:center}    
</style>
<br/>

<div class='container' >
    <center>
        <?php
        if (isset($_POST['addslot'])) {
            $slotid = $_POST['slotid'];
            $starttime = $_POST['starttime'];
            $endtime = $_POST['endtime'];
            
            $result = add_slot($slotid, $starttime, $endtime);
            if ($result == 'not ok') {
                echo "Zone Not Added, Please Try Again";
            } else {
                header("Location: assign.php?id=17");       
            }
        }
        ?>
        <form id="addorders" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" enctype='application/json'>
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Add Slot</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <tr>
                        <td class='frmlblTd'>Slot ID <span class="mandatory">*</span></td>
                        <td><input type="text" name="slotid" required></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Start Time <span class="mandatory">*</span></td>
                        <td><input type="text" name="starttime" id="STime" required></td>
                    </tr>
                    
                     <tr>
                        <td class='frmlblTd'>End Time <span class="mandatory">*</span></td>
                        <td><input type="text" name="endtime" id="ETime" required></td>
                    </tr>


                    <tr>
                        <td colspan="100%" class='frmlblTd'>
                            <input type="submit" value="Add" name="addslot" class='btn btn-primary'>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </center>
</div>
