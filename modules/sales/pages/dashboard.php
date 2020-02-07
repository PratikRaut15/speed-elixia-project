<?php
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/modules/team/bootstrap.css" type="text/css" />';
?>
<style>
    #page {
        margin: 0 auto;
        overflow: unset;
        width: auto;
    }

    .panel{width: auto;}
    .panel-green {
        border-color: #5cb85c;
    }
    .panel-green .panel-heading {
        background-color: #5cb85c;
        border-color: #5cb85c;
        color: #fff;
    }
    .panel-green a {
        color: #5cb85c;
    }
    .panel-green a:hover {
        color: #3d8b3d;
    }

    .panel-green a:hover {
        color: #3d8b3d;
    }
    .panel-red {
        border-color: #d9534f;
    }
    .panel-red .panel-heading {
        background-color: #d9534f;
        border-color: #d9534f;
        color: #fff;
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
        background-color: #f0ad4e;
        border-color: #f0ad4e;
        color: #fff;
    }
    .panel-yellow a {
        color: #f0ad4e;
    }
    .panel-yellow a:hover {
        color: #df8a13;
    }


    .panel-perple {
        border-color: #7266BA;
    }
    .panel-perple .panel-heading {
        background-color: #7266BA;
        border-color: #7266BA;
        color: #fff;
    }
    .panel-perple a {
        color: #7266BA;
    }
    .panel-perple a:hover {
        color: #7266BA;
    }
    .panel-greend {
        border-color: #5F9EA0;
    }
    .panel-greend .panel-heading {
        background-color: #5F9EA0;
        border-color: #5F9EA0;
        color: #fff;
    }
    .panel-greend a {
        color: #5F9EA0;
    }
    .panel-greend a:hover {
        color: #5F9EA0;
    }

    .panel-redd {
        border-color: #8B0000;
    }
    .panel-redd .panel-heading {
        background-color: #8B0000;
        border-color: #8B0000;
        color: #fff;
    }
    .panel-redd a {
        color: #8B0000;
    }
    .panel-redd a:hover {
        color: #8B0000;
    }

    .panel-blued {
        border-color: #00BFFF;
    }
    .panel-blued .panel-heading {
        background-color: #00BFFF;
        border-color: #00BFFF;
        color: #fff;
    }
    .panel-blued a {
        color: #00BFFF;
    }
    .panel-blued a:hover {
        color: #00BFFF;
    }
    .panel-red1 {
        border-color: #FF6347;
    }
    .panel-red1 .panel-heading {
        background-color: #FF6347;
        border-color: #FF6347;
        color: #fff;
    }
    .panel-red1 a {
        color: #FF6347;
    }
    .panel-red1 a:hover {
        color: #FF6347;
    }

    #footer {
        background: none repeat scroll 0 0 #e9e9e9;
        border-top: 3px solid #d8d8d8;
        font-family: Arial,Helvetica,sans-serif;
        height: 20px;
        margin: 60px auto 0;
        padding: 0 0 15px;
    }

    #footer p {
        color: #a0a0a0;
        font-size: 12px;
        letter-spacing: 2px;
        line-height: 1px;
        margin: 10px 0 0;
        text-align: center;
        text-transform: uppercase;
    }

    #footer a {
        color: #8a8a8a;
    }

</style>
<?php
$mob = new Sales($customerno, $userid);

$prisalescount = $mob->getprimary();
$counto = $mob->getsecondaryorders();
$countdstock = $mob->getdeadstock();
$countshop = $mob->get_shopcount();
$countdist = $mob->get_distview();

$count_category = $mob->get_category();
$countcat = count($count_category);
$count_sku = $mob->get_styleview();
$countsku = count($count_sku);
$user_count = $mob->getallusers();
$countusers = count($user_count);
 
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">  

        <!-- /.row -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-lg-3  col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-reorder fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if (isset($prisalescount)) {
    echo $prisalescount;
} ?></div>
                                    <div>Total Primary Sales</div>
                                </div>
                            </div>
                        </div>
                        <a href="sales.php?pg=prisalesview">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if (isset($counto)) {
    echo $counto;
} ?></div>
                                    <div>Total Orders</div>
                                </div>
                            </div>
                        </div>
                        <a href="sales.php?pg=orderview">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-ban fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if(isset($countdstock)){echo $countdstock;} ?></div>
                                    <div>Dead Stocks </div>
                                </div>
                            </div>
                        </div>
                        <a href="sales.php?pg=stock">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-perple">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if (isset($countshop)) {
    echo $countshop;
} ?></div>
                                    <div> Shops </div>
                                </div>
                            </div>
                        </div>
                        <a href="sales.php?pg=shopview">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3  col-md-6">
                    <div class="panel panel-red1">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-sitemap fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if (isset($countdist)) {
    echo $countdist;
} ?></div>
                                    <div>Distributors</div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0)">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-greend">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-reorder fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if (isset($countcat)) {
    echo $countcat;
} ?></div>
                                    <div>Category</div>
                                </div>
                            </div>
                        </div>
                        <a href="sales.php?pg=catview">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-redd">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-square fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if (isset($countsku)) {
    echo $countsku;
} ?></div>
                                    <div>SKU </div>
                                </div>
                            </div>
                        </div>
                        <a href="sales.php?pg=styleview">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-blued">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-users fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php if (isset($countusers)) {
    echo $countusers;
} ?></div>
                                    <div>Users </div>
                                </div>
                            </div>
                        </div>
                        <a href="../account/users.php?id=2">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

