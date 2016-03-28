<?php
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/


require('includes/config.php'); 
if($user->logged_in()) {header('Location: '.DIRADMIN); exit();}
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

</head>
<body>
    <div class="container">
        <div class="row text-center ">
            <div class="col-md-12">
                <br /><br />
                <h2> <?php echo SITETITLE;?> : Login</h2>
               
                <h5></h5>
                 <br />
            </div>
        </div>
         <div class="row ">
               	
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
									header('Location: '.DIR.'login.php');
									exit();
								} else {
									$user->login($_POST['username'], $_POST['password']);
								}
							}
						?>
                        <strong>   Enter Details To Login </strong>  
						
						<?php  messages();?>
						
						</div>
                            <div class="panel-body">
                                <form role="form" method="post" action="" autocomplete="off" >
                                       <br />
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"  ></i></span>
                                            <input type="text" class="form-control" placeholder="Your Username" name="username" required />
                                     </div>
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                                            <input type="password" class="form-control"  placeholder="Your Password" name="password" required />
                                     </div>
									 <?php if (using_captcha == 1) : ?>
									 <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /> 
                                     <div class="form-group input-group">
									 <br />
									 <input type="text" placeholder="Solve the image above" class="form-control" name="captcha_code" size="8" maxlength="6" required />
									 <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
									 </div>
									 <?php endif; ?>
                                    <div class="form-group">
                                            <span class="pull-right">
                                                   <a href="forgotpassword.php" >Forgot password ? </a> 
                                            </span>
                                    </div>
                                    
									 <p><input type="submit" name="submit" value="Login" class="btn btn-primary "/></p>  
                                    <?php if(can_register == 1) : ?>
									<hr />
                                    Not register? <a href="register.php" >click here </a>
									<?php endif; ?> 
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
