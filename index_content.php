<!--Banner image -->
<div id="page">
    <section id="home" class="page-section">
        <div data-background="index_img/sections/slider/HeatMap_Web.jpg" style="opacity:1;" class="image-bg content-in fixed" data-stellar-background-ratio="0.5"></div>
        <div class="overlay"></div>
        <div class="row">
            <div class="col-md-4" style="margin:170px 0 0 30px;">

                <h2 style="font-size: 16px;color:#686577;text-align:center;position:relative;top:-50px;right:-100px;"></h2>

                <img alt="" src="index_img/speedicon.png" class="img-responsive" style="position:absoulte;margin-left:350px;margin-top:-300px;padding-top:220px;padding-bottom:100px;">
                <div style="top:170px;position:absolute;width:800px;left:200px;">
                    <h3 style="color:white;font-size:20px;font-weight:500;text-align:center;font-family:Arial, san-serif;">GPS BASED <br>VEHICLE TELEMATICS SOLUTION</h3>
                </div>
            </div>

            <div style="top:470px;left:440px;position:absolute;">
                <a href="https://play.google.com/store/apps/details?id=com.elixia.elixiaspeed" target="blank"><img alt="" src="index_img/icon/GooglePlay1.png" class="img-responsive" style="width:150px;height:75px;border:0;">
                </a>
            </div>
            <div style="top:470px;left:620px;position:absolute;">
                <a href="https://itunes.apple.com/in/app/elixia-speed/id889049615?mt=8" target="blank"><img alt="" src="index_img/icon/Appstore1.png" class="img-responsive" style="width:150px;height:75px;border:0;left:620px !important;">
                </a>

            </div>
            <div class="col-md-4 tb-pad-80" style="margin:310px 0 0 30px;z-index:-1;">

            </div>
            <!--Login form -->
            <div class="col-md-3 tb-pad-80">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-login">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <a href="#auth" class="active" data-toggle="tab" id="login-heading" >Login</a>
                                    </div>
                                    <div class="col-xs-4">
                                        <a href="#forg" data-toggle="tab" id="forgot-heading">Forgot password</a>
                                    </div>
                                    <div class="col-xs-5">
                                        <a href="#ecode_tabs" data-toggle="tab" id="client-heading">Client Code</a>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 ">
                                        <form id="auth" style="padding-right: 15px;"  method="POST" >
                                            <p id="incorrect" class="off alert alert-danger" style="display: none;">Incorrect Username/Password</p>
                                            <p id="admin" class="off alert alert-danger" style="display: none;">Your Group has been deleted</p>
                                            <p id="correct" class="off alert alert-danger" style="display: none;">Getting You Inside</p>
                                            <p id="otpNotSent" class="off alert alert-danger" style="display: none;">Unable To Tend OTP. Please Try Again.</p>
                                            <p id="otpSent" class="off alert alert-danger" style="display: none;">OTP Sent Successfully</p>
                                            <p id="nophone" class="off alert alert-danger" style="display: none;">Phone Number Not Available.</p>
                                            <p id="sendOtp" class="off alert alert-danger" style="display: none;">Please Enter OTP</p>
                                            <p id="loginFailed" class="off alert alert-danger" style="display: none;">Login Failed</p>
                                            <p id="userLocked" class="off alert alert-danger" style="display: none;">User Locked As It Has Exceeded Hourly SMS Cap, Please Contact The Support Team.</p>
                                            <p id="noSmsBalance" class="off alert alert-danger" style="display: none;">Insufficient Sms Balance For Your Account, Please Contact The Support Team.</p>
                                            <p id="limitExceeded" class="off alert alert-danger" style="display: none;">OTP Limit Exceeded For Today, Please Contact Support Team.</p>
                                            <div class="form-group">
                                                <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                            </div>
                                            <div class="form-group" id="inputOtp" style="display: none;">
                                                <label class="sr-only" for="exampleInputOTP">OTP</label>
                                                <input type="hidden" name="isOtpSent" id="isOtpSent">
                                                <input type="hidden" name="authType" id="authType">
                                                <input type="text" name="confirmOtp" id="confirmOtp" placeholder="OTP"  class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="button" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In" style="font-weight:600;" onclick="checkUserAuth();">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <form   id="forg" style="margin-right: 15px;display:block;display:none" method="POST">
                                            <span id="wuser" name="wuser" style="display: none;color:red;font-weight:bold;"> Invalid username </span>
                                            <span id="forgot_message" style="display: none;color:green;font-weight:bold;"> New Password sent over the email </span>
                                            <span id="noemail" name="noemail" style="display: none;color:red;font-weight:bold;"> We don't have your email. Please contact an Elixir. </span>
                                            <span id="unableemail" name="unableemail" style="display: none;color:red;font-weight:bold;"> Unable to send email. Please contact an Elixir. </span>
                                            <div class=form-group>
                                                <label class="sr-only" for="exampleInputEmail2">Username</label>
                                                <input type="hidden" name="userK" id="userK">
                                            <input type = "text" name = "uname" id="uname" class="form-control" placeholder = "Username" >
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-1" style="padding-bottom: 22px;">
                                                        <button type= "button" class="btn  btn-primary btn_blue"  onclick=" return genNewPass();"><strong>Retrieve</strong></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <form id="ecode"  method="POST" style="margin-right: 15px;display:block;display:none;" action="modules/user/route.php">
                                            <span id="eecode" name="eecode" style="display: none;" class="notok">Invalid / Expired Code</span>
                                        <div class="form-group">
                                                <label class="sr-only" for="exampleInputEmail2">Username</label>
                                                <input type="text" name="ecodeid" class="form-control" id="ecodeid" placeholder="Enter Client Code" >
                                        </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                        <button type= "button" class="btn btn-primary  btn_blue"   onclick="elixiacode();"><strong>Track</strong></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
            </div>
        </div>
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
                            <input type="hidden" class="form-control" id="forgot_userkey" name="forgot_userkey">
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

        <div class="modal fade" tabindex="-1" id="multiAuthOtp">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#0679c0">2-Way Authentication</h4>
                    </div>

                    <div class="modal-body">

                        <form id="frmMultiAuthOtp" class="form-inline"  method="POST" action="modules/user/route.php">
                            <span colspan="2" id="unmatch" style="color:#FF0000;display: none">OTP Does Not Match</span>
                            <input type="hidden" class="form-control" id="userid" name="userid">
                            <div class="form-group">
                                <input class="input-lg form-control" id="verifyOtp" name="verifyOtp" type="text" placeholder="OTP" class="input-xlarge">
                            </div>
                            <br>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="button" name="confirmOtp"  class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="OTP Confirmation..." onclick="change_forgotpass();">Confirm OTP</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </section>

    <body>
        <section class="bg00">
            <div class="container" style="padding-bottom: 100px;padding-right:100px;">
                <div class="table" style="margin-top: 0px;">
                    <h2 style="margin-top: 20px;padding-bottom: 20px;padding-left: 233px;color:white;sont-weight:600;font-size:30px;"> <b>ONE PRODUCT. TWO VERSIONS.</b></h2>
                    <div class="col-md-3 prop" style="padding-top:0px;padding-left:60px;">
                        <h2>prop</h2>
                        <div class="rec"></div>
                        <li class="hidden-xs">Real-time tracking </li>
                        <li class="hidden-xs">Reports & Analytics</li>
                        <li class="hidden-xs" style="padding-top: 0px;padding-bottom: 0px;">Software Customization</li>
                        <li class="hidden-xs">API for integration</li>
                        <li class="hidden-xs">Add-on sensors</li>
                        <li class="hidden-xs">Route Management</li>
                        <li class="hidden-xs">Trip based analytics</li>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="price active">

                            <img class="header-icon" src="index_img/icon/basic1.png">
                            <li>

                                <img class="check" src="index_img/icon/check.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/check.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/cross.png">
                            </li>
                            <li>
                                <img class="check" src="index_img/icon/cross.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/cross.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/cross.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/cross.png">
                            </li>

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="price">

                            <img alt="" src="index_img/icon/advanced1.png" class="header-icon">
                            <li>

                                <img class="check" src="index_img/icon/check.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/check.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/check.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/check.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/check.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/check.png">
                            </li>
                            <li>

                                <img class="check" src="index_img/icon/check.png">
                            </li>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </body>

    </html>

    <!--Image & Text box block -->

    <section id="benefits" class="page-section">
        <div class="overlay-strips"></div>
        <div class="container">
            <div class="section-title" data-animation="fadeInUp"
            >
                <!-- Heading -->
                <h1 class="title" style="margin: -29px 0px 80px 0;"> Benefits</h1>
            </div>

            <div class="row services icons-circle">
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInLeft visible" data-animation="fadeInLeft">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/television.png" height="30" width="28" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">LIVE MONITORING</h5>
                    <!-- Text -->
                    <div class="spw">Track all vehicles in real time with details on speed, distance covered, ignition status and much more</div>

                </div>
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInRight visible" data-animation="fadeInRight">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/india-1.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">PAN INDIA OPERATIONAL BASE</h5>
                    <!-- Text -->
                    <div class="spw">Get seamless service pan India thanks to our large operational base.</div>

                </div>

                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInRight visible" data-animation="fadeInRight">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/headphones.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">STRONG AFTER SALES SUPPORT</h5>
                    <!-- Text -->
                    <div class="spw">A dedicated relationship manager for every account to ensure 100% customer satisfaction</div>

                </div>
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInLeft visible" data-animation="fadeInLeft">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/Fuel.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">FUEL SENSOR</h5>
                    <!-- Text -->
                    <div class="spw">Get highly accurate fuel level readings and instant alerts in case of fuel theft and fuel refill</div>

                </div>

            </div>

            <div class="clearfix"></div>
        </div>
        <div class="container">
            <div class="section-title animated fadeInLeft visible" data-animation="fadeInLeft">
                <!-- Heading -->

            </div>

            <div class="row services icons-circle">
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInLeft visible" data-animation="fadeInLeft">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/app.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">ANDROID AND IOS APP</h5>
                    <!-- Text -->
                    <div class="spw">To give you total control at your fingertips at all times even when you are on the move </div>

                </div>
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInRight visible" data-animation="fadeInRight">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/compatible.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">COMPACT, RELIABLE AND ROBUST DEVICE</h5>
                    <!-- Text -->
                    <div class="spw">IP 65 compatible GPS device designed to avoid any loss of data and ensure maximum up time</div>

                </div>
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInRight visible" data-animation="fadeInRight">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/flexible.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">HARDWARE FLEXIBILITY</h5>
                    <!-- Text -->
                    <div class="spw">Compatible with multiple hardware devices so that customers can save on additional capital expenditure</div>

                </div>
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInLeft visible" data-animation="fadeInLeft">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/reports.png" height="30" width="28" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">EXHAUSTIVE ANALYTICS AND REPORTS</h5>
                    <!-- Text -->
                    <div class="spw">The best analytics and reports module in the industry enabling you to take more informed business decisions</div>

                </div>

            </div>

            <div class="clearfix"></div>
        </div>
        <div class="container">
            <div class="section-title animated fadeInLeft visible" data-animation="fadeInLeft">
                <!-- Heading -->

            </div>

            <div class="row services icons-circle">
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInLeft visible" data-animation="fadeInLeft">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/variety.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">COMPATIBLE ADD-ON SENSORS</h5>
                    <!-- Text -->
                    <div class="spw">We offer a variety of peripheral accessories to ensure you get the most out of the solution as per your need. You can opt for:

                        <div> (a) AC Sensor
                            <br>(b) Temperature Sensor
                            <br> (c) Humidity Sensor
                            <br> </div>
                        <div> (d) Gen-Set Sensor
                            <br> (d) Vehicle Immobilizer
                            <br>(e) Buzzer
                            <br> </div>
                        <div> (f) Panic Button and much more… </div>
                        <br>

                    </div>
                </div>
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInRight visible" data-animation="fadeInRight">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/email.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">SMS / EMAIL / TELEPHONIC ALERTS</h5>
                    <!-- Text -->
                    <div class="spw">Select and configure the types and modes of alerts that you want to receive</div>

                </div>

                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInRight visible" data-animation="fadeInRight">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/users.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">EASY AND FLEXIBLE MANAGEMENT OF USERS AND ROLES</h5>
                    <!-- Text -->
                    <div class="spw">Add any number of users and assign flexible customized roles to them with variable rights and responsibilities</div>

                </div>

            </div>
            <div class="row services icons-circle">
                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInLeft visible" data-animation="fadeInLeft">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/client.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">CLIENT CODE</h5>
                    <!-- Text -->
                    <div class="spw">Create a temporary code for your customers to track your vehicle(s) to provide complete transparency and customer delight</div>

                </div>

                <div class="item-box icons-color hover-black col-sm-6 col-md-6 animated fadeInRight visible" data-animation="fadeInRight">

                    <!-- Icon -->
                    <img alt="" src="index_img/icon/share.png" height="30" width="30" img style="position: absolute;top:5px; left: -30px;" class="item-box">
                    <i class="i-4x border-color"></i>
                    <!-- Title -->
                    <h5 class="title">QUICK SHARE</h5>
                    <!-- Text -->
                    <div class="spw">Share the exact location of your vehicle(s) with anyone through various ways with just one click</div>

                </div>

            </div>

        </div>

        <div class="clearfix"></div>
</div>

<div class="container">
    <div class="section-title animated fadeInLeft visible" data-animation="fadeInLeft">
        <!-- Heading -->

    </div>

</div>

</section>