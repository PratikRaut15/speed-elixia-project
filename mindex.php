<!DOCTYPE html>
<html lang="en"><head>
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
<link rel="stylesheet" href="css_index/jquery.css">
<link rel="stylesheet" href="css_index/animate.css">

<link rel="stylesheet" href="css_index/styles.css"> 

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
<br/><br/>
<div id="logo"><a>Elixia  <span>Speed</span></a></div>
<div>
    
  <div class="modal-dialog " >
      
	<div class="modal-content ">
                            
				<div class="modal-body ">
				
				
                                
                                    
						<ul class="nav nav-tabs">
						<li class="active"><a href="#login" data-toggle="tab" >Login</a></li>
						<!--<li><a href="#forgot" data-toggle="tab">Forgot password</a></li>-->
						<li><a href="#ecode_tabs" data-toggle="tab">Client Code</a></li>
						
						
						</ul>
				
						
						<div class="tab-content">
						
								<div class="tab-pane active" id="login">
								
											
											<div  style="margin-top:2em;" ></div>
											<form   id="auth" class="form-inline"  method="POST" action="modules/user/route.php" >
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
											<div class=" form-group ">
												<button type="button" onClick="login();"   class="btn btn-primary btn_blue"><strong>Login</strong></button>	
											</div>
											
											</form>
											
								</div>
								<div class="tab-pane" id="forgot">
								<div  style="margin-top:2em;" ></div>
											<form   id="forg" class="form-inline" >
											<span id="wuser" name="wuser" style="display: none;"> Invalid username </span>
											<span id="message" name="message" style="display: none;"> You will receive a new password </span>
											<span id="noemail" name="noemail" style="display: none;"> We don't have your email please contact an elixir </span>
											<div class="form-group">
											<label class="sr-only" for="exampleInputEmail2">Username</label>
											<input type = "text" name = "uname" id="uname" class="form-control" placeholder = "Username" >
											</div>
											<div style="clear:both;"></div>
											<div class=" form-group col-md-offset-1">
											<button type= "button" class="btn  btn-primary btn_blue"  onclick="genNewPass();">Retrieve</button>
										</div>	
											
										</form>
										
								
								</div>
								<div class="tab-pane" id="ecode_tabs">
								<div  style="margin-top:2em;" ></div>
											<form   id="forg" class="form-inline" >
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
  </div>



<!-- SCRIPTS -->
<script src="js_index/jquery_004.js"></script>
<script src="js_index/bootstrap.js"></script>
<script src="js_index/jquery_002.js"></script>
<script src="js_index/jquery.js"></script>
<script src="js_index/elixia.js"></script>
<script src="scripts/user.js"></script>

</body></html>