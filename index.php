<!DOCTYPE html>
<html>

<head>
    <title>Speed</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Elixia">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- css group start -->
   <?php
        include 'index_files/include_css.php';
    ?>
    <!-- css group end -->
    
</head>

<body class="pg-home">
    <h1 class="cm-not-in-page">Elixia</h1>
    <header>
        <!-- css group start -->
       <?php
        include 'index_files/header.php';
        ?>
        <!-- css group end -->
        
    </header>
    <main>
        <div class="bs-banner js-bg-img typ-form wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s">
            <div class="img-wrap addto">
                <img src="index_img/product/banner/speed-banner.jpg" alt="banner-img" class="img">
            </div>
            <div class="banner-cont">
                <div class="text-wrap">
                    <div class="prod-logo">
                        <div class="img-wrap">
                            <img src="index_img/product/logo-speed.png" alt="product-logo">
                        </div>
                        <h2 class="banner-title cm-font-thin">Elixia speed</h2>
                    </div>
                     <!-- <h2 class="banner-title">Elixia<span class="cm-font-thin"> speed</span> -->
                     </h2> 
                    <div class="banner-desc">
                        <p><span class="cm-line-break">Elixia Speed is the best-in-class vehicle</span> <span class="cm-line-break">telematics solution with comprehensive </span>reports and real time analytics</p>
                    </div>
                    <a href="#prodquery" class="btn btn-default btn-inverse scrollbtn">Request a demo </a>
                </div>
                <div class="cont-img-wrap">
                    <!-- <img src="index_img/banner-icon1.svg" alt="infography" class="img-responsive"> -->
                </div> 

            </div>
            <div class="lyt-login wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-login">

                            <div class="panel-heading">
                                <div class="row">
                                        <div class="col-xs-3">
                                            <a href="#auth" class="active" data-toggle="tab" id="login-heading" >Login</a>
                                        </div>
                                        <div class="col-xs-5">
                                            <a href="#forg" data-toggle="tab" id="forgot-heading">Forgot Password</a>
                                        </div>
                                        <div class="col-xs-4">
                                            <a href="#ecode_tabs" data-toggle="tab" id="client-heading">Client Code</a>
                                        </div>
                                </div>
                            </div>
                            <hr>         
                            <div class="panel-body">
                                <div class="row">
                                    
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
                                                <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value  style="font-size: 15px;margin-left: 10px;">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" style="font-size: 15px;margin-left: 10px;">
                                            </div>
                                            
                                            <div class="form-group" id="inputOtp" style="display: none;">
                                                <label class="sr-only" for="exampleInputOTP">OTP</label>
                                                <input type="hidden" name="isOtpSent" id="isOtpSent">
                                                <input type="hidden" name="authType" id="authType">
                                                <input type="text" name="confirmOtp" id="confirmOtp" placeholder="OTP"  class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                    </div>    
                                                    <div class="col-xs-4">
                                                    <button type="button" name="login-submit" id="login-submit" tabindex="4" class="btns" style="font-weight:600;" onclick="checkUserAuth();"><strong>Login</strong>
                                                    </button>
                                                    </div>
                                                    <div class="col-xs-4">
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
                                                    <input type = "text" name = "uname" id="uname" class="form-control" placeholder = "Username" style="font-size: 15px;margin-left: 10px;">
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <button type= "button" class="btns"  onclick=" return genNewPass();" style="font-weight:600;"><strong>Retrieve</strong></button>
                                                    <div class="col-xs-3">
                                                    </div>   
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <form id="ecode"  method="POST" style="margin-right: 15px;display:block;display:none;" action="modules/user/route.php">
                                            <span id="eecode" name="eecode" style="display: none;" class="notok">Invalid / Expired Code</span>
                                            <div class="form-group">
                                                    <label class="sr-only" for="exampleInputEmail2">Username</label>
                                                    <input type="text" name="ecodeid" class="form-control" id="ecodeid" placeholder="Enter Client Code" style="font-size: 15px;margin-left: 10px;">
                                            </div>
                                            <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-xs-3">
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <button type= "button" class="btns"   onclick="elixiacode();" style="font-weight:600;" ><strong>Track</strong></button>
                                                        </div>
                                                        <div class="col-xs-3">
                                                        </div>
                                                    </div>
                                            </div>
                                        </form>       
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn-flyout visible-xs wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s"></button>
        </div>
                        <div class="modal fade" tabindex="-1" id="myForgotPassModal">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" style="color:#0679c0">Change Password</h4>
                                </div>
                                <div class="modal-body">

                                    <form id="forpass" class="form-inline"  method="POST" >
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
            </div>
        <div class="lyt-content" id="products">
            <section>
                <div class="bs-sec typ-bg typ-features">
                    <div class="sec-head">
                        <div class="sec-title wow fadeIn" data-wow-delay="0.8s" data-wow-duration="1.5s"><span class="cm-font-thin">top</span> Benefits</div>
                    </div>
                    <div class="sec-cont wow fadeInUp" data-wow-delay="0.8s" data-wow-duration="1.5s">
                        <div class="cm-container">
                            <div class="bs-benefit">
                                <div class="button-wrap">
                                   <div class="desc">
                                                <!-- <p>
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus in feugiat mi. Phasellus vulputate pellentesque ante, sed tincidunt velit vo
                                                </p> -->
                                            </div> 
                                    <div class="btn-group">
                                        <div class="btn-list">
                                            <ul class="row">
                                                <li class="item col-md-6">
                                                    <div class="benefits-btn">
                                                        <label for="radio1" data-target="#vehstatus">Real Time Vehicle Status</label>
                                                    </div>
                                                </li>
                                                <li class="item  col-md-6">
                                                    <div class="benefits-btn">
                                                        <label for="radio2" data-target="#curloc" class="">Add On Sensors</label>
                                                    </div>
                                                </li>
                                                <li class="item  col-md-6">
                                                    <div class="benefits-btn">
                                                        <label for="radio3" data-target="#tracing" class="">Dashboard &amp; Analytics</label>
                                                    </div>
                                                </li>
                                                <li class="item col-md-6">
                                                    <div class="benefits-btn">
                                                        <label for="radio4" data-target="#control" class="">Alerts &amp; Notifications</label>
                                                    </div>
                                                </li>
                                                <li class="item col-md-6">
                                                    <div class="benefits-btn">
                                                        <label for="radio5" data-target="#contract" class="">Route &amp; Checkpoints</label>
                                                    </div>
                                                </li>
                                                <li class="item col-md-6">
                                                    <div class="benefits-btn">
                                                        <label for="radio6" data-target="#billing" class="">Client Code & Trips</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="feature-infographic">
                                    <div class="img-wrap">
                                        <img src="index_img/status.png" alt="" />
                                    </div>
                                    <div class="rel-info">
                                        <div class="img-wrap">
                                            <img src="index_img/product/speed/speed-logo.jpg" alt="">
                                        </div> 
                                        <ul class="list">
                                            <li class="item active" id="vehstatus">
                                                <div class="pointer-list">
                                                    <ul class="row">
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-idle"></span>
                                                                <p class="text">Idle/Running</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-ignition"></span>
                                                                <p class="text">Ignition On/Off</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-trip"></span>
                                                                <p class="text">On Trip/Available</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="item" id="curloc">
                                                <div class="pointer-list">
                                                    <ul class="row">
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-ac-temp-monitor"></span>
                                                                <p class="text">AC &amp; Temperature Monitoring</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-immobilizer"></span>
                                                                <p class="text">Vehicle Immobilizer</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-buzzer"></span>
                                                                <p class="text">Buzzer/Hooter &amp; Door Sensor</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="item" id="tracing">
                                                <div class="pointer-list">
                                                    <ul class="row">
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-rt1-dashboard"></span>
                                                                <p class="text">Real time Dashboard</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-comprehensive-analytics1"></span>
                                                                <p class="text">Comprehensive Analytics</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-rt-dashboard"></span>
                                                                <p class="text">Vehicle wise detailed analysis</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="item" id="control">
                                                <div class="pointer-list">
                                                    <ul class="row">
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-instant-alerts"></span>
                                                                <p class="text">Instant Alerts</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-email-alerts"></span>
                                                                <p class="text">Telephonic, SMS, Email Alerts</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-rt-notification"></span>
                                                                <p class="text">Real time Notifications</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="item" id="contract">
                                                <div class="pointer-list">
                                                    <ul class="row">
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-route-violation"></span>
                                                                <p class="text">Route violation alerts</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-route-mapping"></span>
                                                                <p class="text">Intelligent route mapping</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-checkpoints"></span>
                                                                <p class="text">Enhanced Checkpoints</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="item" id="billing">
                                                <div class="pointer-list">
                                                    <ul class="row">
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-module-management"></span>
                                                                <p class="text">Trip Module and Management</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-trip-report-customer"></span>
                                                                <p class="text">Trip Wise reports to Customers</p>
                                                            </div>
                                                        </li>
                                                        <li class="col-md-4 col-xs-4">
                                                            <div class="mod-icon-text typ-pointer">
                                                                <span class="icon icon-client-code"></span>
                                                                <p class="text">Client Code for Complete Transparency</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="bs-sec typ-no-pad">
                    <div class="sec-head cm-not-in-page ">
                        <h2 class="sec-title">
                            <span class="cm-font-thin cm-line-break">what </span>makes us different
                        </h2>
                    </div>
                    <div class="sec-cont ">
                        <div class="bs-tile js-bg-img typ-primary wow fadeInUp" data-wow-delay="0.8s" data-wow-duration="1.5s">
                            <ul class="tile-list row">
                                <li class="item typ-counter col-md-4">
                                    <div class="tile addto">
                                        <div class="img-wrap ">
                                            <img src="index_img/product/speed/speed-pan.png" alt="" class="img" />
                                        </div>
                                        <div class="tile-info">

                                            <div class="desc">
                                                <p>
                                                    Pan India operational services and centralized after-sales customer support
                                                </p>
                                            </div>
                                        </div>
                                        <span class="cm-overlay"></span>
                                    </div>
                                </li>
                                <li class="item typ-counter col-md-4">
                                    <div class="tile addto">
                                        <div class="img-wrap ">
                                            <img src="index_img/product/speed/speed-timeupdate.png" alt="" class="img" />
                                        </div>
                                        <div class="tile-info">

                                            <div class="desc">
                                                <p>
                                                    Get real time vehicle status with current location, speed and distance travelled
                                                </p>
                                            </div>
                                        </div>
                                        <span class="cm-overlay"></span>
                                    </div>
                                </li>
                                <li class="item typ-counter col-md-4">
                                    <div class="tile addto">
                                        <div class="img-wrap ">
                                            <img src="index_img/product/speed/speed-unlimited.png" alt="" class="img" />
                                        </div>
                                        <div class="tile-info">

                                            <div class="desc">
                                                <p>
                                                    Add unlimited users with specific roles and responsibilities for efficient vehicle management
                                                </p>
                                            </div>
                                        </div>
                                        <span class="cm-overlay"></span>
                                    </div>
                                </li>
                                <li class="item typ-counter col-md-4">
                                    <div class="tile addto">
                                        <div class="img-wrap ">
                                            <img src="index_img/product/speed/speed-go.png" alt="" class="img" />
                                        </div>
                                        <div class="tile-info">

                                            <div class="desc">
                                                <p>
                                                    Free android and iOS app to give you complete access even on the go
                                                </p>
                                            </div>
                                        </div>
                                        <span class="cm-overlay"></span>
                                    </div>
                                </li>
                                <li class="item typ-counter col-md-4">
                                    <div class="tile addto">
                                        <div class="img-wrap ">
                                            <img src="index_img/product/speed/notification.png" alt="" class="img" />
                                        </div>
                                        <div class="tile-info">

                                            <div class="desc">
                                                <p>
                                                    Instant alerts &amp; aotifications for over-speeding, power cut, device tampering and much more
                                                </p>
                                            </div>
                                        </div>
                                        <span class="cm-overlay"></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="bs-sec typ-gray">
                    <div class="sec-head">
                        <h2 class="sec-title wow fadeIn" data-wow-delay="0.8s" data-wow-duration="1.5s">
                            <span class="cm-font-thin">our </span>clientele
                        </h2>
                    </div>
                    <div class="sec-cont">
                        <div class="container">
                            <div class="bs-logo wow fadeInUp" data-wow-delay="0.9s" data-wow-duration="1.5s">
                                <div class="swiper-container" id="clientslider">
                                    <ul class="logo-list swiper-wrapper">
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/mondelez.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/nestle.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/monginis.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/mahindra.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/times.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/sanfoi.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide last">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/faasos.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/allcargo.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/fortpoint.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/cold-star.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/delex.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/vibgyor.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/pastonji.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide last">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/jhonson.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/foodland.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/technova.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/icelings.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/jags.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/indian.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/wp-group.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide last">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/prekin.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/esimal.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/suprume.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/gurukrupa.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/symbol-trus.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/sm-express.png" alt="logo" />
                                            </div>
                                        </li>

                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/hybro-fresh.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide last">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/algor.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/chheda.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/slwillbord.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/shree.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/supreme.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/western.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/korea.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide last">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/rkgroup.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/coldcare.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/derby.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/gargi.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/vinti.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/aptinfra.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/bdp.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide last">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/fast-track.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/gamzen.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/kool.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/licious.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/liladhar.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/parazelsus.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/reema.png" alt="logo" />
                                            </div>
                                        </li>
                                        <li class="item swiper-slide last">
                                            <div class="logo-wrap">
                                                <img src="index_img/client/tl.png" alt="logo" />
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <section>
            <!-- productqueries starts -->
            <?php
                include 'index_files/productqueries.php';
            ?>
            <!-- productqueries end -->
        </section>
    </div>
    <footer>
        <?php
        include 'index_files/footer.php';
        ?>
    </footer>
        
    <!-- js group start -->
        <?php
            include 'index_files/include_js.php';
        ?>
    <!-- js group end -->
</body>
</html>