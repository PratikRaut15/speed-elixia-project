<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

include_once("session.php");
include_once("../../lib/system/utilities.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("dash_function.php");
$_stylesheets[] = "bootstrap.css";

include("header.php");
/* $allot_count= vendor_simcount();
  echo"<pre>";
  print_r($allot_count); die(); */
?>
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
        font-size: 40px;
    }

    .panel-green {
        border-color: #5cb85c;
    }

    .panel-green .panel-heading {
        border-color: #5cb85c;
        color: #fff;
        background-color: #5cb85c;
    }

    .panel-brown {
        border: 1px solid #cf5f3b;
    }

    .panel-brown .panel-heading {
        border-color: #cf5f3b;
        color: #fff;
        background-color: #cf5f3b;
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

<div style="text-align: center"><h2>Closing Stock</h2></div>
<div class="col-lg-12">
    <!------ 1st row 1st panel------------------------------->
    <div class="row">

        <div class="col-lg-3 col-md-6 mb">
            <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px;">
                <div class="panel-heading centered">
                    <div class="row">
                        <div class="col-xs-12 text-center">    
                            <div>Total Devices In Office</div>
                            <div class="huge">
                                <?php
                                $tunit = unit_status("inoffice","1,2,3,4,17,18,20");
                                echo $tunit;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="height:200px;overflow-y: scroll;">
                    <div class="tab-content">


                        <div class="tab-pane fade in active" id="status-pills" style="font-weight: bold;">
                            <table style="font-size:20px;width:90%">
                                <tr>
                                    <td>New / Untested</td><td>:&nbsp;</td><td><?php echo unit_status("inoffice","1,4,17"); ?></td>
                                <tr>
                                <tr>
                                    <td>Ready</td><td>:</td><td><?php echo unit_status("inoffice","2"); ?></td>
                                </tr>
                                <tr>
                                    <td>Bad</td><td>:</td><td><?php echo unit_status("inoffice","3"); ?></td>
                                </tr>
                                <tr>
                                    <td>With FE (Good)</td><td>:&nbsp;</td><td><?php echo unit_status("withfe","18"); ?></td>
                                </tr>
                                <tr>
                                    <td>With FE (Bad)</td><td>:</td><td><?php echo unit_status("withfe","20"); ?></td>
                                </tr>                                
                            </table>
                        </div>
                        <div class="tab-pane fade ScrollStyle" id="type-pills">
                            <div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Types</th>
                                            <th>In Office(Not Alloted)</th>
                                            <th>view</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $type_count = unit_office_type();
                                        foreach ($type_count as $this_count) {
                                            ?>
                                            <tr>
                                                <td><?php echo $this_count['name']; ?></td>
                                                <td><?php echo $this_count['cnt']; ?></td>
                                                <!--<td><a href="dash_function.php?typeval=<?php //echo $this_count['typeval'];            ?>"><i class='icon-eye-open'></i></a></td>
                                                <td><button type="button" data-toggle="modal" data-target="#myModal"><i class='icon-eye-open'></i></button></td>-->
                                                <td><button type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                        <i class='icon-eye-open'></i>
                                                    </button></td>
                                            </tr>



                                            <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div col-xs-3 text-center>
                        <ul class="nav nav-pills">
                            <li class="active"><a href="#status-pills" data-toggle="tab">Status</a>
                            </li>
                            <li><a href="#type-pills" data-toggle="tab">Type</a>
                            </li> 
                        </ul>
                    </div>
                </div>

                <!--<a href="devices.php">-->
                <div class="panel-footer">
                    <span class="pull-left">
                        <form action="devices.php" method="post">
                            <input type="hidden" name="dsearch" value="Search Device" />
                            <input type="hidden" name="unitstatus" value="2" />
                            <button>View Details</button>
                        </form>
                    </span>
                    <span class="pull-right"><img src="../../img/glyphicons-circle-arrow-right.png"></span>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
        <!--------------------1 nd row , 2nd panel-------------------------------------------->
        <div class="col-lg-3 col-md-6 mb">
            <div class="panel-green" style="border:1px solid #3d8b3d; border-radius: 5px;">
                <div class="panel-heading centered">
                    <div class="row">
                        <div class="col-xs-12 text-center">    
                            <div>Total Devices Trashed</div>
                            <div class="huge">
                                <?php
                                $tunit = unit_status("inoffice","26");
                                echo $tunit;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="height:200px;overflow-y: scroll;">
                    <div class="tab-content">


                        <div class="tab-pane fade in active" id="fe_status-pills" style="font-weight: bold;">
                            <table style="font-size:20px;width:90%">
                                <tr>
                                    <td>Trashed</td><td>:</td><td><?php echo unit_status("inoffice","26"); ?></td>
                                </tr>                      
                            </table>
                        </div>
                        <div class="tab-pane fade ScrollStyle" id="fe_type-pills">
                            <div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Count</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $allot_count = unit_allotment_fe();

                                        foreach ($allot_count as $allot_counts) {
                                            ?>
                                            <tr>
                                                <td><?php echo $allot_counts->name; ?></td>
                                                <td><?php echo $allot_counts->count; ?></td>


                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div col-xs-3 text-center style="">
                        <ul class="nav nav-pills">
                            <li class="active"><a href="#fe_status-pills" data-toggle="tab">Status</a>
                            </li>
                            <li><a href="#fe_type-pills" data-toggle="tab">Type</a>
                            </li> 
                        </ul>
                    </div>
                </div>

                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">
                            <form action="devices.php" method="post">
                                <input type="hidden" name="dsearch" value="Search Device" />
                                <input type="hidden" name="unitstatus" value="18" />
                                <button>View Details</button>
                            </form>
                        </span>
                        <span class="pull-right"><img src="../../img/glyphicons-circle-arrow-right.png"></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        <!---------------------------------------- 1st row 3rd panel---------------------------------------------------->
        <div class="col-lg-3 col-md-6 mb">
            <div class="panel-yellow" style="border:1px solid #f0ad4e; border-radius: 5px;">
                <div class="panel-heading centered">
                    <div class="row">
                        <div class="col-xs-12 text-center">    
                            <div>Total Devices With Customer</div>
                            <div class="huge">
                                <?php
                                $tunit = unit_status("withcust","5,6,10,22,23,27,28");
                                echo $tunit;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="height:200px;overflow-y: scroll;">
                    <div class="tab-content">


                        <div class="tab-pane fade in active" id="cust_status-pills" style="font-weight: bold;">
                            <table style="font-size:20px;width:100%">

                                <tr>
                                    <td>Installed & Invoiced</td><td>:&nbsp;</td><td><?php echo unit_status("withcust","5,6",1); ?></td>
                                </tr>
                                <tr>
                                    <td>Installed & Not invoiced</td><td>:</td><td><?php echo unit_status("withcust","5,6",2); ?></td>
                                </tr>
                                <tr>
                                    <td>Invoiced and Not installed</td><td>:</td><td><?php echo unit_status("withcust","22",1); ?></td>
                                </tr>   
                                <tr>
                                    <td>Given as Spare</td><td>:</td><td><?php echo unit_status("withcust","22",2); ?></td>
                                </tr>                                
                                <tr>
                                    <td>Demo</td><td>:</td><td><?php echo unit_status("withcust","23"); ?></td>
                                </tr>
                                <tr>
                                    <td>Gifted</td><td>:</td><td><?php echo unit_status("withcust","27"); ?></td>
                                </tr>
                                <tr>
                                    <td>Fake</td><td>:</td><td><?php echo unit_status("withcust","28"); ?></td>
                                </tr>                                
                                <tr>
                                    <td>Terminated</td><td>:</td><td><?php echo unit_status("withcust","10"); ?></td>
                                </tr>                                
                            </table>
                        </div>
                        <div class="tab-pane fade ScrollStyle" id="cust_type-pills">
                            <div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer No.</th>
                                            <th>Customer Name</th>
                                            <th>Count</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cust_count = unit_cust_count();

                                        foreach ($cust_count as $cust_counts) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cust_counts->custno; ?></td>
                                                <td><?php echo $cust_counts->custcompany; ?></td>
                                                <td><?php echo $cust_counts->custcount; ?></td>

                                            </tr>
                                            <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div col-xs-3 text-center>
                        <ul class="nav nav-pills">
                            <li class="active"><a href="#cust_status-pills" data-toggle="tab">Status</a>
                            </li>
                            <li><a href="#cust_type-pills" data-toggle="tab">Type</a>
                            </li> 
                        </ul>
                    </div>
                </div>

                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">
                            <form action="devices.php" method="post">
                                <input type="hidden" name="dsearch" value="Search Device" />
                                <input type="hidden" name="unitstatus" value="5" />
                                <button>View Details</button>
                            </form>
                        </span>
                        <span class="pull-right"><img src="../../img/glyphicons-circle-arrow-right.png"></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <!--------------------------------------------------1st row 4 th panel-------------------------------------------------------------------->

        <div class="col-lg-3 col-md-6 mb">
            <div class="panel-red" style="border:1px solid #d9534f; border-radius: 5px;">
                <div class="panel-heading centered">
                    <div class="row">
                        <div class="col-xs-12 text-center">    
                            <div>Total Devices In Repair</div>
                            <div class="huge">
                                <?php
                                $tunit = unit_total("repair");
                                echo $tunit;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="height: 200px;"></div>

                <a href="">
                    <div class="panel-footer">
                        <span class="pull-left">
                            <form action="devices.php" method="post">
                                <input type="hidden" name="dsearch" value="Search Device" />
                                <input type="hidden" name="unitstatus" value="7" />
                                <button>View Details</button>
                            </form>
                        </span>
                        <span class="pull-right"><img src="../../img/glyphicons-circle-arrow-right.png"></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

        <div class="col-lg-3 col-md-6 mb">
            <div class="panel-brown" style="font-size:20px;width:600px;border-radius: 5px;">
                <div class="panel-heading centered">
                    <div class="row">
                        <div class="col-xs-12 text-center">    
                            <div>Devices in Office</div>
<!--                             <div class="huge">
                                <?php
                                $tunit = unit_status("inoffice","1,2,3,4,17,18,20");
                                echo $tunit;
                                ?>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="height:220px;">
                    <?php 
                        include('deviceLocation.php')
                    ?>
<!--                     <div class="tab-content">

                        <div class="ScrollStyle">
                        <div class="tab-pane fade in active" id="cust_status-pills" style="font-weight: bold;">
                            <table style="font-size:15px;width:100%;text-align: center;">
                                    <thead>
                                        <tr>
                                            <th style="padding-bottom:10px;border-bottom: 1px solid #000;text-align: center;">Location</th>
                                            <th style="padding-bottom:10px;border-bottom: 1px solid #000;text-align: center;">Location Name</th>
                                            <th style="padding-bottom:10px;border-bottom: 1px solid #000;text-align: center;">COUNT</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $loctn_count = unit_office_location();
                                       //print_r($loctn_count);
                                        foreach ($loctn_count as $this_count) {
                                            ?>
                                            <tr>
                                                <td style="padding-right:10px;">BOX<?php echo $this_count['location']; ?></td>
                                                <td style="padding-right:10px;"><?php echo $this_count['name']; ?></td>
                                                <td style="padding-right:10px;"><?php echo $this_count['count']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>

<div class="collapse" id="collapseExample">
    <div class="well">
        qwrwetdgdfgyrtyut76473414657u6yijyjkhgk
    </div>
</div>