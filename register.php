<?php
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/

require('includes/config.php'); 
if (can_register == 0){
$_SESSION['error'] = 'Registration is not allowed!.';
header('Location: '.'login.php');
exit();
}


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SITETITLE;?></title>
	<!-- BOOTSTRAP STYLES-->
    <link href="<?php echo DIR;?>assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="<?php echo DIR;?>assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="<?php echo DIR;?>assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#username").keyup(function() {
var username = $('#username').val();
if(username=="")
{
$("#disp").html("");
}
else
{
$.ajax({
type: "POST",
url: "includes/user_check.php",
data: "username="+ username ,
success: function(html){
$("#disp").html(html);
}
});
return false;
}
});
});
</script>
</head>
<body>
    <div class="container">
        <div class="row text-center  ">
            <div class="col-md-12">
                <br /><br />
                <h2> <?php echo SITETITLE;?> : Register</h2>
               
                <h5></h5>
                 <br />
            </div>
        </div>
         <div class="row">
               
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
							<?php 
						if(isset($_POST['submit'])) {
							include_once 'securimage/securimage.php';
							$securimage = new Securimage();
							if (isset($_POST['captcha_code']) && $securimage->check($_POST['captcha_code']) == false && using_captcha == 1) {
								  // the code was incorrect
								  // you should handle the error so that the form processor doesn't continue
									$_SESSION['error'] = 'The security code entered was incorrect.';
									header('Location: '.DIR.'register.php');
									exit();
								} else {
									$user->register($_POST['name'],$_POST['username'],$_POST['email'], $_POST['password']);
								}
						}
						?>
                        <strong>  New User? Register now </strong> 
						<?php  messages();?>
                            </div>
                            <div class="panel-body">
                                <form role="form" method="post" action="" autocomplete="off" >
									<br/>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-circle-o-notch"  ></i></span>
                                            <input type="text" class="form-control" required placeholder="Your Name" name="name"/>
                                        </div>
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"  ></i></span>
                                            <input type="text" class="form-control" required placeholder="Desired Username" name="username" id="username"/>
                                        </div>
										<div id="disp"></div>
                                         <div class="form-group input-group">
                                            <span class="input-group-addon">@</span>
                                            <input type="email" class="form-control" required placeholder="Your Email" name="email"/>
                                        </div>
                                      <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                                            <input type="password" class="form-control" required placeholder="Enter Password" name="password"/>
                                      </div>
                                     <?php if (using_captcha == 1) : ?>
										 <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /> 
										 <div class="form-group input-group">
										 <br />
										 <input type="text" placeholder="Solve the image above" class="form-control" name="captcha_code" size="8" maxlength="6" required />
										 <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
										 </div>
									 <?php endif; ?>
									  <p><input type="submit" name="submit" value="Register Me" class="btn btn-success "/></p>
                                    <hr />
                                    Already Registered?  <a href="login.php" >Login here</a>
                                    </form>
                            </div>
                           
                        </div>
                    </div>
                
                
        </div>
    </div>


     <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/custom.js"></script>
   
</body>
</html>
