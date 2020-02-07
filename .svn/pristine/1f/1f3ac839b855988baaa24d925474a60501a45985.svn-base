<?php
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
	//														Md. Aminul Islam (aminulsumon@yahoo.com)									//
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
	//		This Page is to Generate Javascript Codes For Form Validation																//
	//				[ Limitation: ]																										//
	//==================================================================================================================================//
	//	function ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields="",$ErrorMsgForSameFields=""):- 	//
	//			This Function Takes 5 Parameter																							//
	//	$FormName = The Name of the Form Inside which the HTML Controls(Eg. Textbox, List) are.											//
	//	$ControlNames = Two dimensional Array Consists Control Name, Value for Check and Error Message(expected).						//
	//	$ValidationFunctionName = The Name of Javascript Function Which will generated.													//
	//	$SameFields = Two Control Names in One Dimensional Format which Contents needed to be Same.										//
	//	$ErrorMsgForSameFields = Expected Error Message Which Will Returned For Same Field.												//
	//----------------------------------------------------------------------------------------------------------------------------------//
	/*
		Example:- 
				require_once("ClsJSFormValidation.cls.php");
				$Validity=new ClsJSFormValidation;
				$FormName="all";
				$ControlNames=array("txtName"			=>array("txtName","''","Please Enter Your Name.","spanname"));
				$ValidationFunctionName="CheckValidity";
				$SameFields=array("txtPassword","txtConfirmPassword");
				$ErrorMsgForSameFields="Password and Confirm Password Are not Same.";
				$JsCodeForFormValidation=$Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
	*/		
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
	class ClsJSFormValidation
	{
		function ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields="",$ErrorMsgForSameFields="")
		{
			$JSValidation="<script language='javascript1.2'>
							function $ValidationFunctionName()
							{	
							
							var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i; 
							var ck_number = /^\d+$/
							var ck_password =  /^[A-Za-z0-9!@#$%^&*()_]{6,20}$/;
							var ck_alpha =  /^[A-Za-z0-9]{3,20}$/;

							var returnValue=true;\n";
			foreach($ControlNames as $SingleControlName) {
					switch($SingleControlName[1])
					{
						case "''": 		
										$JSValidation.="
											if(document.$FormName.$SingleControlName[0].value=='')
											{
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='<img src=\"images/icon_error.png\" alt=\"delete\" class=\"mid_align\"/> $SingleControlName[2]';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											 }
											 else
											 {
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }	\n";
									
										break;
						case "Number":	$JSValidation.="
											var number;
											number=document.$FormName.$SingleControlName[0].value;

											if (!ck_number.test(number))
											{
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='<img src=\"images/icon_error.png\" alt=\"delete\" class=\"mid_align\"/> Please enter number only.';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											}
											else
											 {
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
						case "ALPHANUMERIC":	$JSValidation.="
											var alpha;
											alpha=document.$FormName.$SingleControlName[0].value;

											if (!ck_alpha.test(alpha))
											{
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='<img src=\"images/icon_error.png\" alt=\"delete\" class=\"mid_align\"/> Please enter alpha numeric only .';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											}
											else
											 {
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
						case "EMail":	$JSValidation.="
											var checkEmail;
											checkEmail=document.$FormName.$SingleControlName[0].value;

											if (!ck_email.test(checkEmail))
											{
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='<img src=\"images/icon_error.png\" alt=\"delete\" class=\"mid_align\"/> Invalid Email Address.';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											}
											else
											 {
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
						case "Agree": 		
										$JSValidation.="
											if(!(document.$FormName.$SingleControlName[0].checked))
											{
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='You must accept the terms and conditions to proceed.';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											 }
											 else
											 {
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }	\n";
									
										break;
						/*case "UserName":	$JSValidation.="
											var username;
											username=document.$FormName.$SingleControlName[0].value;
											if(!ck_username.test(username))
											{
												document.getElementById('$SingleControlName[3]').className='required';
												document.getElementById('$SingleControlName[3]').innerHTML='Please Enter valid Username.';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											}
											else
											 {
												document.getElementById('$SingleControlName[3]').className='normal';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;*/
						case "Password":	$JSValidation.="
											var password;
											password=document.$FormName.$SingleControlName[0].value;
											if(!ck_password.test(password))
											{
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='<img src=\"images/icon_error.png\" alt=\"delete\" class=\"mid_align\"/> Invalid Password. Should be in range(6-20)';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											}
											else
											 {
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
						case "RePassword":	$JSValidation.="
											var repassword;
											repassword=document.$FormName.$SingleControlName[0].value;
											password=document.$FormName.$SingleControlName[4].value;
											if(!ck_password.test(repassword))
											{
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='<img src=\"images/icon_error.png\" alt=\"delete\" class=\"mid_align\"/> Invalid Password.';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											}
											else if(repassword!=password) {
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='<img src=\"images/icon_error.png\" alt=\"delete\" class=\"mid_align\"/> $SingleControlName[2]';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											}
											else {
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
						default: 			$JSValidation.="
											if(document.$FormName.$SingleControlName[0].value=='')
											{
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='<img src=\"images/icon_error.png\" alt=\"delete\" class=\"mid_align\"/> $SingleControlName[2]';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											 }
											  else
											 {
												document.getElementById('$SingleControlName[3]').className='alert_error';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }	\n";
									

					}	//End of Switch
				}		
			$JSValidation.=" return returnValue;
							 }		//End of JS Validation Function
							 </script>	\n";
			return $JSValidation;
		}
	
	
}
?>