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
        if (isset($_POST['addzone'])) {
            $zoneid = $_POST['zoneid'];
            $zonename = $_POST['zonename'];
            $result = add_zone($zoneid, $zonename);
            if ($result == 'not ok') {
                echo "Zone Not Added, Please Try Again";
            } else {
                header("Location: assign.php?id=6");       
            }
        }
        ?>
        <form id="addorders" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" enctype='application/json'>
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Add Zone</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <tr>
                        <td class='frmlblTd'>Zone ID <span class="mandatory">*</span></td>
                        <td><input type="text" name="zoneid" required></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Zone Name <span class="mandatory">*</span></td>
                        <td><input type="text" name="zonename" id="areaname" required></td>
                    </tr>


                    <tr>
                        <td colspan="100%" class='frmlblTd'>
                            <input type="submit" value="Add" name="addzone" class='btn btn-primary'>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </center>
</div>
