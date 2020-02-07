<?php
$vehicleid = $_GET['vid'];
$unitno = $_GET['unitno'];
//echo $vehicleid.$_SESSION['customerno'];
if(!isset($_POST['SDate'])) { $Sdate = getdate_IST(); } else $Sdate = strtotime ($_POST['SDate']);
?>
<form action="vehicle.php?id=5&unitno=<?php echo $unitno; ?>" method="POST">
    <input type="hidden" value="<?php echo $unitno; ?>" name="unitno">
    <table>
        <tr>
            <td>Please select date</td>
            <td><input id="SDate" name="SDate" type="text" value="<?php echo date('d-m-Y',$Sdate);?>" required/></td>
            <td colspan="100%"><input type="submit" data="g-button g-button-submit" class="btn  btn-primary" value="Submit" name="submit"></td>
        </tr>
    </table>
</form>
<?php if(isset($_POST['SDate']))
{ 
$date = GetSafeValueString($_POST['SDate'], 'string');
$dt = date("Y-m-d", strtotime($date));
$unit = GetSafeValueString($_POST['unitno'], 'string');
$location = "../../customer/".$_SESSION['customerno']."/unitno/$unit/sqlite/$dt.sqlite";   ?>
<div class="tabbable" style="width: 61%;">
        <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab1">Device History</a></li>
                <li class=""><a data-toggle="tab" href="#tab2">Unit History</a></li>
                <li class=""><a data-toggle="tab" href="#tab3">Vehicle History</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab1" class="tab-pane active">
                <table class="table  table-bordered table-striped dTableR dataTable">
                  <thead>
                    <tr>
                        <th>Lat</th>
                        <th>Long</th>
                        <th>Last Updated</th>
                        <th>Int Batt (V)</th>
                        <th>Status</th>
                        <th>Ignition</th>
                        <th>Power Cut</th>
                        <th>Tamper</th>
                        <th>Gps fixed</th>
                        <th>Online/Offline</th>
                        <th>Gsm Strength</th>
                        <th>Gsm Register</th>
                        <th>Gprs Register</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $devhists = getdevicehist_sqlite($location);
                      foreach($devhists as $devhist){
                          echo "<tr><td>$devhist->devicelat</td>
                        <td>$devhist->devicelong</td>
                        <td>$devhist->lastupdated</td>
                        <td>$devhist->inbatt</td>
                        <td>$devhist->status</td>
                        <td>$devhist->ignition</td>
                        <td>$devhist->powercut</td>
                        <td>$devhist->tamper</td>
                        <td>$devhist->gpsfixed</td>
                        <td>$devhist->onoff</td>
                        <td>$devhist->gsmstrength</td>
                        <td>$devhist->gsmregister</td>
                        <td>$devhist->gprsregister</td></tr>";
                      }
                      ?>
                  </tbody>
                </table>
            </div>
            <div id="tab2" class="tab-pane">
                <table class="table  table-bordered table-striped dTableR dataTable">
                  <thead>
                    <tr>
                        <th>analog 1</th>
                        <th>analog 2</th>
                        <th>analog 3</th>
                        <th>analog 4</th>
                        <th>Digital I/O</th>
                        <th>Last Updated</th>
                        <th>Command Key</th>
                        <th>Command Key Value</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $unithists = getunithist_sqlite($location);
                      foreach($unithists as $unithist){
                          echo "<tr><td>$unithist->analog1</td>
                        <td>$unithist->analog2</td>
                        <td>$unithist->analog3</td>
                        <td>$unithist->analog4</td>
                        <td>$unithist->digitalio</td>
                        <td>$unithist->lastupdated</td>
                        <td>$unithist->commandkey</td>
                        <td>$unithist->commandkeyval</td></tr>";
                      }
                      ?>
                  </tbody>
                </table>
            </div>
            <div id="tab3" class="tab-pane">
                <table class="table  table-bordered table-striped dTableR dataTable">
                  <thead>
                    <tr>
                        <th>Vehicle No</th>
                        <th>Ext Batt</th>
                        <th>Odometer</th>
                        <th>Last Updated</th>
                        <th>Current Speed</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      $vehhists = getvehhist_sqlite($location);
                      foreach($vehhists as $vehhist){
                          echo "<tr><td>$vehhist->vehicleno</td>
                        <td>$vehhist->extbatt</td>
                        <td>$vehhist->odometer</td>
                        <td>$vehhist->lastupdated</td>
                        <td>$vehhist->curspeed</td></tr>";
                      }
                      ?>
                  </tbody>
                </table>
            </div>
        </div>
</div>
<?php } ?>