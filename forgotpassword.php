<?php
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/


require('includes/config.php'); 

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
                <h2> <?php echo SITETITLE;?> : Send Verification link</h2>
               
                <h5></h5>
                 <br />
            </div>
        </div>
         <div class="row ">
               	
                  <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
<?php 
if(isset($_POST['email'],$_POST['submitEmail']))
{

function mailreset($token, $email, $username) {
	//send email
	$subject = 'Forgot Password on '.SITETITLE;
	$url = DIR;
	$message = '
	<html>
	<head>
	<title>Forgot Password For '.SITETITLE.'</title>
	</head>
	<body>
	<p>Dear '.htmlentities($username).',</p>
	<p>You or someone else have requested a password reset, ignore this email if you have not requested so.</p>
	<p>Click on the given link to reset your password <a href="'.$url.'reset.php?token='.$token.'" target="_blank">Reset Password</a></p>

	</body>
	</html>
	';
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= 'From: Admin<webmaster@example.com>' . "\r\n";
	$headers .= 'Cc: Admin@example.com' . "\r\n";
	
	if(mail($email,$subject,$message,$headers)){
		$_SESSION['success'] = "We have sent the reset link to your email address: <b>".$email."</b>"; 
	}
}
function token_exists($email,$con) {
	
	
	$sql = "SELECT token FROM tokens WHERE email=? AND used='0' LIMIT 1";
	
	$stmt = $con->prepare($sql);
	$stmt->bind_param("s",$email);
	$stmt->execute();
	$stmt->bind_result($token_);
	$stmt->store_result();
	$stmt->fetch(); 
	$stmt->close(); 
	if($stmt->num_rows!==0){
		return $token_;
	} else {
		return "";
	}
	return "";
}
function randomString($length=25) {
    $validCharacters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $validCharNumber = strlen($validCharacters);
    $result = "";

    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }
	return $result;
}
	include_once 'securimage/securimage.php';
	$securimage = new Securimage();
	if (isset($_POST['captcha_code']) && $securimage->check($_POST['captcha_code']) == false && using_captcha == 1) {
	  // the code was incorrect
	  // you should handle the error so that the form processor doesn't continue
		$_SESSION['error'] = 'The security code entered was incorrect.';
		header('Location: '.DIR.'forgotpassword.php');
		exit();
	} else {
		$email = $_POST['email'];
		if($email == "")
		{
			$_SESSION['error'] =  'Email address is empty';
			header('Location: '.DIR.'forgotpassword.php');
			return;
			
		}
		if($user->email_valid($email) == false)
		{
			$_SESSION['error'] = 'Email address is invalid';
			header('Location: '.DIR.'forgotpassword.php');
			exit();
		}
		if($user->email_exists($email))
		{
			$con = $user->db;
			$username = $user->getUserInfo($email,'username');
			// check if email reset link exists if yes return that token
			$check_token = token_exists($email,$con); 
			if($check_token == "") {
				$token = randomString(25);
			} else {
				$token = $check_token;
				mailreset($token,$email,$username);
				header('Location: '.DIR.'forgotpassword.php');
				exit();
			}
			$sql = "INSERT INTO tokens (token,email,date) VALUES (?,?,NOW())";
			
			if($stmt = $con->prepare($sql)) 
			{
				$stmt->bind_param("ss",$token,$email);
				if ($stmt->execute()) { 
					mailreset($token,$email,$username);
					header('Location: '.DIR.'forgotpassword.php');
					exit();
				} else {
					$_SESSION['error'] = 'Process failed, try again';
					header('Location: '.DIR.'forgotpassword.php');
					exit();
				}
			}
			
			
		} else {
			$_SESSION['error'] = 'Email is not registered';
			header('Location: '.DIR.'forgotpassword.php');
			exit();
		}	
	}
}
				
?>
                        <strong>   Enter Email Address </strong>  
						
						<?php  messages();?>
						
						</div>
                            <div class="panel-body">
                                <form role="form" method="post" action="">
                                       <br />
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-envelope"  ></i></span>
                                            <input type="email" class="form-control" placeholder="Your Email" name="email" required />
                                     </div>
                                     <?php if (using_captcha == 1) : ?>
										 <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /> 
										 <div class="form-group input-group">
										 <br />
										 <input type="text" placeholder="Solve the image above" class="form-control" name="captcha_code" size="8" maxlength="6" required />
										 <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
										 </div>
									 <?php endif; ?>
									 <input type="submit" name="submitEmail" value="Send email" class="btn btn-primary "/> 
                                    <hr />
                                    Login? <a href="login.php" >click here </a> 
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
