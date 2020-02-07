<style type="text/css">
    #maptoggler
{
background: none repeat scroll 0 0 #FFFFFF;
box-shadow: 7px 4px 6px #888888;
float: right;
height: 40px;
left: 17.7%;
padding: 25px 4px 4px;
position: absolute;
width: 17px;
top:225px;
z-index: 500;
border-right:#CCCCCC 1px solid;
-webkit-border-top-right-radius: 5px;
-webkit-border-bottom-right-radius: 5px;
-moz-border-radius-topright: 5px;
-moz-border-radius-bottomright: 5px;
border-top-right-radius: 5px;
border-bottom-right-radius: 5px;
border:#CCCCCC 1px solid;
}
ul, li, table, caption, tbody, tfoot, thead, tr, th, td,{
    padding-top: 10px;
    padding-bottom: 10px;
    border: none;
    text-align: left;
}
.vehName{
    font-weight: bold;
    font-color:blue;
    font-size: 20px;
    text-align: left;
}
.vehNameSmall{
    font-weight: bold;
    font-color:blue;
    font-size: 14px;
    text-align: left;
}
#chartContainer1223{
    width:300px;
}
</style>


<script type="text/javascript">
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer1223",
    {

      title:{
      text: "Speed"
      },
      axisX: {
        text: "Time"
      },
      axisY:{
        includeZero: false

      },
      data: [
      {
        type: "line",

        dataPoints: [
        { x: 6, y: 20 },
        { x: 8, y: 40},
        { x: 10, y: 90, indexLabel: "highest",markerColor: "red", markerType: "triangle"}
        ]
      }
      ]
    });

    chart.render();
  }
  </script>
 <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<li>
<?php

if ($_SESSION['switch_to'] != 3) {
	?>
        <ul id='sidepane' style="display: none;">


            <table style="width:300">
                <tr>
                    <td colspan="2" class="scrollheader vehName"></td>
                </tr>
                <tr>
                    <td colspan="2" class="scrollgroup vehName"></td>
                </tr>
                <tr>
                    <td colspan="2" class="vehName"><img  width="310"  height="200" src="../../images/vehicles/truck.png" /><br/><br/></td>
                </tr>
                <tr>
                    <td class="vehFrom vehNameSmall" style="width:150"></td>
                    <td class="vehTo vehNameSmall" style="width:150"></td>
                </tr>
                <tr>
                    <td class="schFrom vehNameSmall" style="width:150"></td>
                    <td class="schTo vehNameSmall" style="width:150"></td>
                </tr>
                <tr>
                    <td class="actFrom vehNameSmall" style="width:150"></td>
                    <td class="actTo vehNameSmall" style="width:150"></td>
                </tr>
                <tr>
                    <td colspan="2" class="rangetxt vehNameSmall">

                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="rtdloc vehNameSmall" style="text-align: left;"></td>
                </tr>

                <tr>
                    <td colspan="2" class="curspeed vehNameSmall" style="text-align: left;"></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div id="chartContainer1223" style="height: 250px; width: 300px;"></div>
                    </td>
                </tr>

            </table>


            <div class="">


            </div>

        </ul>
        <?php
}

?>


</li>