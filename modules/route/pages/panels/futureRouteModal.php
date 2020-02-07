 <style>
.column{
    width:49%;
    margin-right:.5%;
    height:500px;
    background:#fff;
    float:left;
        overflow-y: scroll;
}
#column2{
    background-image: url(../../images/drop.png);
    background-position: center;
    background-repeat: no-repeat;
    background-size: 200px 50px;
}
.heading{
    width:49%;
    margin-right:.5%;
    min-height:21px;
    background:#cfc;
    float:left;
}
.column .dragbox{
    margin:5px 2px  20px;
    background:#fff;
    position:"relative";
    border:1px solid #946553;
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
        width: inherit;
}
.column .dragbox h2{
    margin:0;
    font-size:12px;
    background:#946553;
    color:#fff;
    border-bottom:1px solid #946553;
    font-family:Verdana;
    cursor:move;
    padding:5px;
}
.dragbox-content{
    background:#fff;
    min-height:100px; margin:5px;
    font-family:'Lucida Grande', Verdana; font-size:0.8em; line-height:1.5em;
}
.column  .placeholder{
    background: #EED5B7;
    border:1px dashed #946553;
}
.alert-info {
background-color: #d9edf7;
border-color: #bce8f1;
color: #3a87ad;
cursor:move;
}
/*.clor{
border: 1px solid #d3d3d3;
background: #e6e6e6 url(http://code.jquery.com/ui/1.10.4/themes/smoothness/images/ui-bg_glass_75_e6e6e6_1x400.png) 50% 50% repeat-x;
font-weight: normal;
color: #555555;
    cursor:move;
}*/
</style>
<script type="text/javascript">

</script>
<!-- vehicle temp range modal starts-->
<div class="modal hide" id="futureRouteModal" style="width:60%;left:30%; right: 10%" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >

<div style="width: 50%">
    <?php echo "Create Future Route For ";?> <label id="vehicleno"></label>
</div>
<div style="width: 67%;">
    <div class="heading" id="head1">Routes List</div>
    <div class="heading" id="head1">Future Route List</div>
    <div id="column1" class="column">
    <?php
        $routes = getAllRoutesByCustomer($_SESSION['customerno']);
        // print("<pre>"); print_r($routes);
        if (isset($routes)) {
            foreach ($routes as $route) {
                echo '<div class="alert-info" id=' . $route->routeid . ' rel="' . $route->routename . '">
                        <h2 style="font-size: 14px;font-weight: normal; margin: 2px;"><span>::</span>   ' . $route->routename . '</h2>
                    </div>';
                //echo "<li class='dragbox' id='recordsArray_$checkpoint->checkpointid'><span>::</span>   $checkpoint->cname</li>";
            }
        }
    ?>
    <input type="hidden" name="vehicleid" id="vehicleid" value="">
        <input type="hidden" name="routeid" id="routeid" value="">
    </div>
    <div class="column" id="column2" >
        
    </div>
</div>
<table>    
<tr>
    <td align="center"><input type="button" value="Save" onclick="submitFutureRoute();"></td>
</tr>
</table>

</div>
