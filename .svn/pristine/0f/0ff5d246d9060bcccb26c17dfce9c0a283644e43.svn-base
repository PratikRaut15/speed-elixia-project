<?php
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/modules/team/bootstrap.css" type="text/css" />';
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
  /*Helpers*/
  .centered {
    text-align: center;
  }
  .goleft {
    text-align: left;
  }
  .goright {
    text-align: right;
  }
  .mt {
    margin-top: 25px;
  }
  .mb {
    margin-bottom: 25px;
  }
  .ml {
    margin-left: 5px;
  }
  .no-padding {
    padding: 0 !important;
  }
  .no-margin {
    margin: 0 !important;
  }
  /* BASIC THEME CONFIGURATION */
  ul li {
    list-style: none;
  }
  a, a:hover, a:focus {
    text-decoration: none;
    outline: none;
  }
  ::selection {
    background: #68dff0;
    color: #fff;
  }
  .huge {
    font-size: 25px;
  }
  .panel-green {
    border-color: #5cb85c;
  }
  .panel-green .panel-heading {
    border-color: #5cb85c;
    color: #fff;
    background-color: #5cb85c;
  }
  .panel-green a {
    color: #5cb85c;
  }
  .panel-green a:hover {
    color: #3d8b3d;
  }
  .panel-red {
    border-color: #d9534f;
  }
  .panel-red .panel-heading {
    border-color: #d9534f;
    color: #fff;
    background-color: #d9534f;
  }
  .panel-red a {
    color: #d9534f;
  }
  .panel-red a:hover {
    color: #b52b27;
  }
  .panel-yellow {
    border-color: #f0ad4e;
  }
  .panel-yellow .panel-heading {
    border-color: #f0ad4e;
    color: #fff;
    background-color: #f0ad4e;
  }
  .panel-yellow a {
    color: #f0ad4e;
  }
  .panel-yellow a:hover {
    color: #df8a13;
  }
  button {
    border: 0;
    padding: 0;
    display: inline;
    background: none;
    //text-decoration: underline;
    color: darkgray;
  }
  button:hover {
    cursor: pointer;
    color: black;
  }
  .ScrollStyle
  {
    max-height: 200px;
    overflow-y: auto;
  }
  .ScrollStyleSim
  {
    max-height: 150px;
    overflow-y: auto;
  }
</style>
<br/>
<div class="entry" id="invoice_info">
  <center>
    <form method="post" id="frmVendorEfficiency" action="<?php $_SERVER['PHP_SELF'] ?>">
      <table>
        <tr>
          <td>Start Date</td>
          <td>End Date</td>
          <td>SKU</td>
          <td>Factory</td>
          <td>Zone</td>
          <td></td>
        </tr>

        <tr>
          <td><input id="SDate" name="SDate" type="text" value="" /></td>
          <td><input id="EDate" name="EDate" type="text" value="" /></td>
          <td>
            <input type="text" style="width: 120px;" name="type_name" id="type_name" value="" maxlength="50" autocomplete="off"/>
            <input type="hidden" name="typeid" id="typeid" value="" maxlength="50"/>
          </td>
          <td><?php
            if (isset($_SESSION['factoryid']) && !empty($_SESSION['factoryid'])) {
             $objFactoty = new Factory();
             $objFactoty->customerno = $_SESSION["customerno"];
             $objFactoty->factoryid = $_SESSION['factoryid'];
             $plant = get_factory($objFactoty);
             ?>
             <input type="text"  name="factory_name" id="factory_name" value="<?php echo $plant[0][factoryname] ?>" maxlength="50" autocomplete="off" readonly=""/>
             <input type="hidden" name="factoryid" id="factoryid" value="<?php echo $_SESSION['factoryid'] ?>" maxlength="50"/>
             <?php
            } else {
             ?>
             <input type="text" name="factory_name" id="factory_name" value="" maxlength="50" autocomplete="off"/>
             <input type="hidden" name="factoryid" id="factoryid" value="" maxlength="50"/>
             <?php
            }
            ?></td>
          <td><input type="text" name="zonename" id="zonename" value="" maxlength="50" autocomplete="off"/>
            <input type="hidden" name="zoneid" id="zoneid" value="" maxlength="50"/></td>
        <input type="hidden" name="filter" id="filter" value="1"/></td>
        <td><input type="button" name="filterVendorEff" class="btn-primary" id="btn-filter-vendoreff" value="Search" onclick="ValidateVendorEfficiency();" /></td>
        </tr>
      </table>
    </form>
    <br/>
  </center>
  <div class="col-lg-12">
    <div class="col-lg-6 col-md-6 mb">
      <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px;">
        <div class="panel-heading centered">
          <div class="row">
            <div class="col-lg-12 text-center">
              <div class="huge">
                Vendor Wise Placement Efficiency
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Indented</div>
                      <div class="huge"><?php echo $transporterEffArrayTotal['totalindent'] ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Placed</div>
                      <div class="huge"><?php echo $transporterEffArrayTotal['placedindent'] ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Efficiency(%)</div>
                      <div class="huge"><?php echo $transporterEffArrayTotal['effpercent'] ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active ScrollStyle" id="type-pills">
              <div>
                <table class="table table-bordered">
                  <thead>
                  <th>Transporters</th>
                  <th>Vehicle Indented</th>
                  <th>Vehicle Placed</th>
                  <th>Placement Efficiency(%)</th>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($transporterEffArray) && !empty($transporterEffArray)) {
                     foreach ($transporterEffArray as $transporter) {
                      ?>
                      <tr>
                        <td><?php echo $transporter['transportername']; ?></td>
                        <td><?php echo $transporter['totalindent']; ?></td>
                        <td><?php echo $transporter['placed']; ?></td>
                        <td><?php echo $transporter['efficiency']; ?></td>
                      </tr>
                      <?php
                     }
                    } else {
                     ?>
                     <tr>
                       <td colspan="4"> No Data</td>
                     </tr>
                     <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!--<a href="#">-->
        <div class="panel-footer">
          <span class="pull-left">
            <form action="#" method="post">
              <input type="hidden" name="dsearch" value="Search Device" />
              <input type="hidden" name="unitstatus" value="2" />
              <button>View Detailed Report</button>
            </form>
          </span>
          <span class="pull-right"><a href="tms.php?pg=Vendor-Eff"><img src="../../img/glyphicons-circle-arrow-right.png"></a></span>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 mb">
      <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px;">
        <div class="panel-heading centered">
          <div class="row">
            <div class="col-lg-12 text-center">
              <div class="huge">
                Vendor & Zone Wise Placement Efficiency
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Indented</div>
                      <div class="huge"><?php echo $transporterzoneEffArrayTotal['totalindent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Placed</div>
                      <div class="huge"><?php echo $transporterzoneEffArrayTotal['placedindent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Efficiency(%)</div>
                      <div class="huge"><?php echo $transporterzoneEffArrayTotal['effpercent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active ScrollStyle" id="type-pills">
              <div>
                <table class="table table-bordered">
                  <thead>
                  <th>Transporter</th>
                  <th>Zone</th>
                  <th>Vehicle Indented</th>
                  <th>Vehicle Placed</th>
                  <th>Placement Efficiency(%)</th>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($transporterzoneEffArray) && !empty($transporterzoneEffArray)) {
                     foreach ($transporterzoneEffArray as $tzeff) {
                      ?>
                      <tr>
                        <td><?php echo $tzeff['transportername'] ?></td>
                        <td><?php echo $tzeff['zonename'] ?></td>
                        <td><?php echo $tzeff['totalindent'] ?></td>
                        <td><?php echo $tzeff['placed'] ?></td>
                        <td><?php echo $tzeff['efficiency']; ?></td>
                      </tr>
                      <?php
                     }
                    } else {
                     ?>
                     <tr>
                       <td colspan="4"> No Data</td>
                     </tr>
                     <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!--<a href="#">-->
        <div class="panel-footer">
          <span class="pull-left">
            <form action="#" method="post">
              <input type="hidden" name="dsearch" value="Search Device" />
              <input type="hidden" name="unitstatus" value="2" />
              <button>View Detailed Report</button>
            </form>
          </span>
          <span class="pull-right"><a href="tms.php?pg=Vendor-Zone-Eff"><img src="../../img/glyphicons-circle-arrow-right.png"></a></span>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-12">
    <div class="col-lg-6 col-md-6 mb">
      <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px;">
        <div class="panel-heading centered">
          <div class="row">
            <div class="col-lg-12 text-center">
              <div class="huge">
                Plant Vendor Zone Wise Placement Efficiency
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Indented</div>
                      <div class="huge"><?php echo $zoneEffArrayTotal['totalindent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Placed</div>
                      <div class="huge"><?php echo $zoneEffArrayTotal['placedindent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Efficiency(%)</div>
                      <div class="huge"><?php echo $zoneEffArrayTotal['effpercent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active ScrollStyle" id="type-pills">
              <div>
                <table class="table table-bordered">
                  <thead>
                  <th>Factory</th>
                  <th>Zone</th>
                  <th>Transporter</th>
                  <th>Vehicle Indented</th>
                  <th>Vehicle Placed</th>
                  <th>Placement Efficiency(%)</th>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($zoneEffArray) && !empty($zoneEffArray)) {
                     foreach ($zoneEffArray as $zone) {
                      ?>
                      <tr>
                        <td><?php echo $zone['factoryname']; ?></td>
                        <td><?php echo $zone['zonename']; ?></td>
                        <td><?php echo $zone['transportername']; ?></td>
                        <td><?php echo $zone['factoryidcount']; ?></td>
                        <td><?php echo $zone['placed']; ?></td>
                        <td><?php echo $zone['efficiency']; ?></td>
                      </tr>
                      <?php
                     }
                    } else {
                     ?>
                     <tr>
                       <td colspan="4"> No Data</td>
                     </tr>
                     <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!--<a href="#">-->
        <div class="panel-footer">
          <span class="pull-left">
            <form action="#" method="post">
              <input type="hidden" name="dsearch" value="Search Device" />
              <input type="hidden" name="unitstatus" value="2" />
              <button>View Detailed Report</button>
            </form>
          </span>
          <span class="pull-right"><a href="tms.php?pg=Plant-Vendor-Zone-Eff"><img src="../../img/glyphicons-circle-arrow-right.png"></a></span>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 mb">
      <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px;">
        <div class="panel-heading centered">
          <div class="row">
            <div class="col-lg-12 text-center">
              <div class="huge">
                Plant Wise Placement Efficiency
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Indented</div>
                      <div class="huge"><?php echo $factoryEffArrayTotal['totalindent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Placed</div>
                      <div class="huge"><?php echo $factoryEffArrayTotal['placedindent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Efficiency(%)</div>
                      <div class="huge"><?php echo $factoryEffArrayTotal['effpercent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active ScrollStyle" id="type-pills">
              <div>
                <table class="table table-bordered">
                  <thead>
                  <th>Plant</th>
                  <th>Vehicle Indented</th>
                  <th>Vehicle Placed</th>
                  <th>Placement Efficiency(%)</th>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($factoryEffArray) && !empty($factoryEffArray)) {
                     foreach ($factoryEffArray as $feff) {
                      ?>
                      <tr>
                        <td><?php echo $feff['factoryname'] ?></td>
                        <td><?php echo $feff['factoryidcount'] ?></td>
                        <td><?php echo $feff['placed'] ?></td>
                        <td><?php echo $feff['efficiency'] ?></td>
                      </tr>
                      <?php
                     }
                    } else {
                     ?>
                     <tr>
                       <td colspan="4"> No Data</td>
                     </tr>
                     <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!--<a href="#">-->
        <div class="panel-footer">
          <span class="pull-left">
            <form action="#" method="post">
              <input type="hidden" name="dsearch" value="Search Device" />
              <input type="hidden" name="unitstatus" value="2" />
              <button>View Detailed Report</button>
            </form>
          </span>
          <span class="pull-right"><a href="tms.php?pg=Plant-Eff"><img src="../../img/glyphicons-circle-arrow-right.png"></a></span>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-12">
    <div class="col-lg-6 col-md-6 mb">
      <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px;">
        <div class="panel-heading centered">
          <div class="row">
            <div class="col-lg-12 text-center">
              <div class="huge">
                Date Wise Placement Efficiency
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Indented</div>
                      <div class="huge"><?php echo $daterequiredEffArrayTotal['totalindent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Vehicle Placed</div>
                      <div class="huge"><?php echo $daterequiredEffArrayTotal['placedindent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 mb panel-green">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-lg-12 text-center">
                      <div>Efficiency(%)</div>
                      <div class="huge"><?php echo $daterequiredEffArrayTotal['effpercent']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="tab-content">
            <div class="tab-pane fade in active ScrollStyle" id="type-pills">
              <div>
                <table class="table table-bordered">
                  <thead>
                  <th>Date Required</th>
                  <th>Vehicle Indented</th>
                  <th>Vehicle Placed</th>
                  <th>Placement Efficiency(%)</th>
                  </thead>
                  <tbody>
                    <?php
                    if (isset($daterequiredEffArray) && !empty($daterequiredEffArray)) {
                     foreach ($daterequiredEffArray as $daterequired) {
                      ?>
                      <tr>
                        <td><?php echo $daterequired['requireddate']; ?></td>
                        <td><?php echo $daterequired['totalindent']; ?></td>
                        <td><?php echo $daterequired['placed']; ?></td>
                        <td><?php echo $daterequired['efficiency']; ?></td>
                      </tr>
                      <?php
                     }
                    } else {
                     ?>
                     <tr>
                       <td colspan="4"> No Data</td>
                     </tr>
                     <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!--<a href="#">-->
        <div class="panel-footer">
          <span class="pull-left">
            <form action="#" method="post">
              <input type="hidden" name="dsearch" value="Search Device" />
              <input type="hidden" name="unitstatus" value="2" />
              <button>View Detailed Report</button>
            </form>
          </span>
          <span class="pull-right"><a href="tms.php?pg=Date-Eff"><img src="../../img/glyphicons-circle-arrow-right.png"></a></span>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>

  </div>
</div>
<script>
 jQuery(document).ready(function () {
   jQuery("#page").css('overflow', 'unset');
 });
 var data = <?php echo json_encode($daterequiredEffArray); ?>;
 var tableId = 'dateeff1';
 var tableCols = [
   {"mData": "requireddate"}
   , {"mData": "totalindent"}
   , {"mData": "placed"}
   , {"mData": "efficiency"}
 ];
</script>
