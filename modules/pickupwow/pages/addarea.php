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
        if (isset($_POST['areaname'])) {
            $zoneid = $_POST['zoneid'];
            $zonename = $_POST['zonename'];
            $areaid = $_POST['areaid'];
            $areaname = $_POST['areaname'];
            
            $location = get_google_location($areaname);
            $lat = $location['lat'];
            $lng = $location['lng'];
            $result = add_area($zoneid, $areaid, $areaname, $lat, $lng);
    
            if ($result == 'not ok') {
                echo "Area Not Added, Please Try Again";
            } else {
                header("Location: pick.php?id=7");       
            }
        }
        ?>
        <form id="addorders" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" enctype='application/json'>
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Add Area</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <tr>
                        <td class='frmlblTd'>Zone Name <span class="mandatory">*</span></td>
                        <td><input type="text" name="zonename" id="zonename" required></td>
                        <input type='hidden' id='zoneid' name='zoneid' >
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Area ID <span class="mandatory">*</span></td>
                        <td><input type="text" name="areaid" id="areaid" required></td>
                        
                    </tr>
                    
                    <tr>
                         <td class='frmlblTd'>Area Name <span class="mandatory">*</span></td>
                        <td><input type="text" name="areaname" id="areaname" required></td>
                    </tr>


                    <tr>
                        <td colspan="100%" class='frmlblTd'>
                            <input type="submit" value="Add" name="addarea" class='btn btn-primary'>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </center>
</div>
