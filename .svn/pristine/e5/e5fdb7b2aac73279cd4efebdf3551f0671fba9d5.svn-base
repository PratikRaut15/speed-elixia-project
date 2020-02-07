<!DOCTYPE html>
<html lang="en">
    <head>
		<meta name="robots" content="noindex">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Elixia speed</title>

        <!-- =========================
         FAV AND TOUCH ICONS
        ============================== -->


        <!-- =========================
             STYLESHEETS
        ============================== -->
        <link rel="stylesheet" href="css_index/bootstrap.css">
        <link rel="stylesheet" href="css_index/owl_002.css">
        <link rel="stylesheet" href="css_index/owl.css">
        <link rel="stylesheet" href="css_index/jquery.css">
        <link rel="stylesheet" href="css_index/animate.css">

        <link rel="stylesheet" href="css_index/styles.css">
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

        <link href='http://fonts.googleapis.com/css?family=Patrick+Hand' rel='stylesheet' type='text/css'>
        <!-- CUSTOM STYLES -->
        <link rel="stylesheet" href="css_index/styles_002.css">
        <link rel="stylesheet" href="css_index/responsive.css">

        <!-- WEBFONT -->
        <link href="css_index/css.css" rel="stylesheet" type="text/css">

        <link href="css_index/speed.css" rel="stylesheet" type="text/css">
        <!--[if lt IE 9]>
                    <script src="js/html5shiv.js"></script>
                    <script src="js/respond.min.js"></script>
                <![endif]-->

        <!-- JQUERY -->

    </head>


    <body>
        <!-- =========================
           PRE LOADER
        ============================== -->
        <div style="display: none;" class="preloader">
            <div style="display: none;" class="status">&nbsp;</div>
        </div>
        <!-- =========================
           HOME SECTION
        ============================== -->
        <header id="home" class="header"  >

            <!-- TOP BAR -->
            <div id="main-nav" class="navbar navbar-inverse bs-docs-nav    navbar-fixed-top" role="banner">
                <div class="container-fluid">
                    <div class="navbar-header responsive-logo">
                        <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div id="logo">
                            <a href="#" class="navbar-brand">
                                Elixia
                                <span>Speed</span>

                            </a>
                        </div>
                    </div>
                    <nav class="navbar-collapse bs-navbar-collapse " role="navigation" >
                        <ul class="nav navbar-nav navbar-right responsive-nav main-nav-list" style="margin-right:20px;">
                            <li><span  data-toggle="modal" data-target="#myModal" class="btn" id="navbtn">Login</span></li>
                        </ul>

                    </nav>
                </div>
            </div>
            <!-- / END TOP BAR -->

        </header> <!-- / END HOME SECTION  -->

        <!-- =========================
           FOOTER
        ============================== -->

        <footer>
            <div class="modal bs-example-modal-sm fade " id="myModal" >
                <div class="modal-dialog " >
                    <div class="modal-content ">

                        <div class="modal-body ">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>


                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#login" data-toggle="tab" >Login</a></li>
                                <li><a href="#forgot" data-toggle="tab">Forgot password</a></li>
                                <li><a href="#ecode_tabs" data-toggle="tab">Client Code</a></li>


                            </ul>


                            <div class="tab-content">

                                <div class="tab-pane active" id="login">


                                    <div  style="margin-top:2em;" ></div>
                                    <form   id="auth" class="form-inline"  method="POST" action="modules/user/route_secret.php" >
                                        <p id="incorrect" class="off alert alert-danger" style="display: none;">Incorrect Username/Password</p>
                                        <p id="admin" class="off alert alert-danger" style="display: none;">Your Group has been deleted</p>
                                        <p id="correct" class="off alert alert-danger" style="display: none;">Getting You Inside</p>
                                        <div style="clear:both;"></div>
                                        <div class="form-group">
                                            <label class="sr-only" for="exampleInputEmail2">Username</label>
                                            <input type="text" name="username" class="form-control" id="username" placeholder="Username" autofocus >
                                        </div>
                                        <div style="clear:both;"></div>
                                        <div class="form-group">
                                            <label class="sr-only" for="exampleInputEmail2">Password</label>
                                            <input type="password" name="password" id="password" placeholder="Password"  class="form-control">
                                        </div>
                                        <div style="clear:both;"></div>
                                        <div class="form-group" id="inputOtp" style="display: none;">
                                            <label class="sr-only" for="exampleInputOTP">OTP</label>
                                            <input type="hidden" name="isOtpSent" id="isOtpSent">
                                            <input type="hidden" name="authType" id="authType">
                                            <input type="text" name="confirmOtp" id="confirmOtp" placeholder="OTP"  class="form-control">
                                        </div>
                                        <div class=" form-group ">
                                            <button type="button" onClick="checkUserAuth();"   class="btn btn-primary btn_blue"><strong>Login</strong></button>
                                        </div>

                                    </form>

                                </div>
                                <div class="tab-pane" id="forgot">
                                    <div  style="margin-top:2em;" ></div>
                                    <form   id="forg" class="form-inline"  >
                                        <span id="wuser" name="wuser" style="display: none;color:red;font-weight:bold;"> Invalid username </span>
                                        <span id="forgot_message" style="display: none;color:green;font-weight:bold;"> New Password sent over the email </span>
                                        <span id="noemail" name="noemail" style="display: none;color:red;font-weight:bold;"> We don't have your email. Please contact an Elixir. </span>
                                        <span id="unableemail" name="unableemail" style="display: none;color:red;font-weight:bold;"> Unable to send email. Please contact an Elixir. </span>
                                        <div style="clear:both;"></div>
                                        <div class="form-group">
                                            <label class="sr-only" for="exampleInputEmail2">Username</label>
                                            <input type = "text" name = "uname" id="uname" class="form-control" placeholder = "Username" >
                                        </div>
                                        <div style="clear:both;"></div>
                                        <div class=" form-group">
                                            <button type= "button" class="btn  btn-primary btn_blue"  onclick="genNewPass();"><strong>Retrieve</strong></button>
                                        </div>

                                    </form>


                                </div>
                                <div class="tab-pane" id="ecode_tabs">
                                    <div  style="margin-top:2em;" ></div>
                                    <form   id="ecode" class="form-inline" method="POST" action="modules/user/route.php" >
                                        <span  id="eecode" name="eecode" style="display: none;" class="notok">Invalid / Expired Code</span>

                                        <div class="form-group">
                                            <label class="sr-only" for="exampleInputEmail2">Username</label>
                                            <input type="text" name="ecodeid" class="form-control" id="ecodeid" placeholder="Enter Client Code" >
                                        </div>
                                        <div style="clear:both;"></div>
                                        <div class=" form-group ">
                                            <button type= "button" class="btn btn-primary  btn_blue"   onclick="elixiacode();"><strong>Track</strong></button>

<!--<input class="btn btn-primary" type = "button"  value = "CheckOut" onclick="checkout();">-->
                                        </div>

                                    </form>


                                </div>




                            </div>


                        </div>






                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" id="myForgotPassModal">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#0679c0">Change Password</h4>
                    </div>

                    <div class="modal-body">

                        <form id="forpass" class="form-inline"  method="POST" action="modules/user/route.php">
                            <span colspan="2" id="unmatch" style="color:#FF0000;display: none">Password Does Not Match</span>
                            <span colspan="2" id="perfectpass" style="display: none">Password Changed</span>
                            <span colspan="2" id="newempty" style="color:#FF0000;display: none">Please Enter New Password</span>
                            <span colspan="2" id="confirmempty" style="color:#FF0000;display: none">Please Enter Confirm Password</span>
                            <input type="hidden" class="form-control" id="user_name" name="user_name">
                            <p style="color:#0679c0">Your password cannot be the same as your username.</p>
                            <div class="form-group">
                                <input class="input-lg form-control" id="newpasswd" name="newpasswd" type="password" placeholder="New Password" class="input-xlarge">
                            </div>
                            <br><br>
                            <div class="form-group">
                                <input class="input-lg form-control" id="confirm_newpasswd" name="confirm_newpasswd" type="password" placeholder="Confirm New Password" class="input-xlarge">
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="button" name="password"  class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Changing Password..." onclick="change_forgotpass();">Change Password</button>
                    </div>



                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->




    </div>
</div> <!-- / END CONTAINER -->
</footer> <!-- / END FOOOTER  -->

<!-- SCRIPTS -->
<script src="js_index/jquery_004.js"></script>
<script src="js_index/bootstrap.js"></script>
<script src="js_index/jquery_002.js"></script>
<script src="js_index/wow.js"></script>
<script src="js_index/jquery_003.js"></script>
<script src="js_index/jquery_005.js"></script>
<script src="js_index/owl.js"></script>
<script src="js_index/smoothscroll.js"></script>
<script src="js_index/jquery.js"></script>
<script src="scripts/crypto_js/rollups/sha1.js"></script>
<script src="scripts/crypto_js/rollups/hmac-sha1.js"></script>
<script src="js_index/elixia.js"></script>
<script src="scripts/user_secret.js"></script>
<!-- ak added, for counter -->
<link href="scripts/jquery-counter-master/jquery.counter-analog.css" media="screen" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='scripts/jquery-counter-master/jquery.counter.js'></script>
<script>jQuery('.counter').counter();</script>
<!-- counter ends-->
</body>

<script type="text/javascript">
    jQuery(document).ready(function () {
        for (var i in window.localStorage) {
            if (i.indexOf('DataTables_') >= 0 && i.indexOf('elixia') >= 0) {
                localStorage.clear(i);

            }
        }
    });
</script>
</html>
