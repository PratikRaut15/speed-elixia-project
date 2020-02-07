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
<div style="text-align: center"><h2>Simcard</h2></div>
<div class="col-lg-12">
    <div class="row">
        <!------------------------------2 nd row 1st panel-------------------------------------------->
        <div class="col-lg-4 col-md-6 mb">
            <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px;">
                <div class="panel-heading centered">
                    <div class="row">
                        <div class="col-xs-12 text-center">    
                            <div>Total Simcard In Office</div>
                            <div class="huge">
                                <?php $tsim = sim_total("inoffice");
                                echo $tsim;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="height: 210px;">
                    <div class="tab-content">


                        <div class="tab-pane fade in active" id="simstatus-pills" style="font-weight: bold;">
                            <table style="font-size:20px;">
                                <tr>
                                    <td>Activated: </td><td><?php echo sim_status("inoffice", 11); ?></td>
                                <tr>
                                <tr>
                                    <td>Bad: </td><td><?php echo sim_status("inoffice", 12); ?></td>
                                </tr>
                                <tr>
                                    <td>Allotted to Team: </td><td><?php echo sim_status("inoffice", 19, 21); ?></td>
                                </tr>

                            </table>
                        </div>
                        <div class="tab-pane fade ScrollStyleSim" id="vendor-pills">
                            <div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Vendors</th>
                                            <th>In Office(Not Alloted)</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ven_count = vendor_simcount();
                                        foreach ($ven_count as $ven_counts) {
                                            ?>
                                            <tr>
                                                <td><?php echo $ven_counts->vname; ?></td>
                                                <td><?php echo $ven_counts->vcount; ?></td>


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
                            <li class="active"><a href="#simstatus-pills" data-toggle="tab">Status</a>
                            </li>
                            <li><a href="#vendor-pills" data-toggle="tab">Vendor</a>
                            </li> 
                        </ul>
                    </div>
                </div>

                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">
                            <form action="devices.php" method="post">
                                <input type="hidden" name="ssearch" value="Search SIM" />
                                <input type="hidden" name="simcardstatus" value="11" />
                                <button>View Details</button>
                            </form>
                        </span>
                        <span class="pull-right"><img src="../../img/glyphicons-circle-arrow-right.png"></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <!------------------------2nd row 2nd panel--------------------------------------->
        <div class="col-lg-4 col-md-6 mb">
            <div class="panel-green" style="border:1px solid #3d8b3d; border-radius: 5px;">
                <div class="panel-heading centered">
                    <div class="row">
                        <div class="col-xs-12 text-center">    
                            <div>Total Simcards With Field Engineer</div>
                            <div class="huge">
                                <?php $tunit = sim_total("withfe");
                                echo $tunit;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="height: 210px;">
                    <div class="tab-content">


                        <div class="tab-pane fade in active" id="fe_sim_status-pills" style="font-weight: bold;">
                            <table style="font-size:20px;">

                                <tr>
                                    <td>Fresh Alloted:</td><td><?php echo sim_status("withfe", 19); ?></td>
                                </tr>
                                <tr>
                                    <td>Bad Alloted:</td><td><?php echo sim_status("withfe", 21); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane fade ScrollStyleSim" id="fe_vendor-pills">
                            <div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Sim alloted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ven_count = sim_withfe();
                                        foreach ($ven_count as $ven_counts) {
                                            ?>
                                            <tr>
                                                <td><?php echo $ven_counts->tname; ?></td>
                                                <td><?php echo $ven_counts->simcnt; ?></td>


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
                            <li class="active"><a href="#fe_sim_status-pills" data-toggle="tab">Status</a>
                            </li>
                            <li><a href="#fe_vendor-pills" data-toggle="tab">Vendor</a>
                            </li> 
                        </ul>
                    </div>
                </div>

                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">
                            <form action="devices.php" method="post">
                                <input type="hidden" name="ssearch" value="Search Sim" />
                                <input type="hidden" name="simcardstatus" value="19" />
                                <button>View Details</button>
                            </form>
                        </span>
                        <span class="pull-right"><img src="../../img/glyphicons-circle-arrow-right.png"></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <!-------------------2 nd row 3rd panel---------------------------->
        <div class="col-lg-4 col-md-6 mb">
            <div class="panel-yellow" style="border:1px solid #f0ad4e; border-radius: 5px;">
                <div class="panel-heading centered">
                    <div class="row">
                        <div class="col-xs-12 text-center">    
                            <div>Total Simcard With Customer</div>
                            <div class="huge">
                                <?php $tunit = unit_total("withcust");
                                echo $tunit;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="tab-content">


                        <div class="tab-pane fade in active" id="cust_sim_status-pills" style="font-weight: bold;">
                            <table style="font-size:20px;">

                                <tr>
                                    <td>Installed:</td><td><?php echo sim_status("withcust", 13); ?></td>
                                </tr>
                                <tr>
                                    <td>Demo:</td><td><?php echo sim_status("withcust", 24); ?></td>
                                </tr>
                                <tr>
                                    <td>Suspected With Customer:</td><td><?php echo sim_status("withcust", 14); ?></td>
                                </tr>
                                <tr>
                                    <td>Bad</td><td><?php echo sim_status("withcust", 12); ?></td>
                                </tr>
                                <tr>
                                    <td>With Customer-not installed:</td><td><?php echo sim_status("withcust", 25); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane fade ScrollStyleSim" id="cust_sim_vendor-pills">
                            <div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer No.</th>
                                            <th>Customer Name</th>
                                            <th>Vendor</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sim_count = sim_cust_count();
                                        //print_r($sim_count);

                                        foreach ($sim_count as $cust_counts) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cust_counts->custno; ?></td>
                                                <td><?php echo $cust_counts->custcompany; ?></td>
                                                <td><?php echo $cust_counts->vname; ?></td>
                                                <td><?php echo $cust_counts->simcount; ?></td>

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
                            <li class="active"><a href="#cust_sim_status-pills" data-toggle="tab">Status</a>
                            </li>
                            <li><a href="#cust_sim_vendor-pills" data-toggle="tab">Vendor</a>
                            </li> 
                        </ul>
                    </div>
                </div>

                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">
                            <form action="devices.php" method="post">
                                <input type="hidden" name="ssearch" value="Search Device" />
                                <input type="hidden" name="simcardstatus" value="13" />
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
