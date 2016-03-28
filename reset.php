<?php 
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/

require('includes/config.php'); 

	$id=""; 
	$stmt= $user->db->prepare("SELECT token FROM tokens WHERE token =? AND used='0' LIMIT 1"); 
	$stmt->bind_param('s', $_GET['token']); 
	$stmt->execute(); 
	$stmt->bind_result($id); 
	$stmt->store_result(); 
	$stmt->fetch(); 
	$stmt->close(); 
	if($id == '') { 
		$_SESSION['error'] = 'Invalid link, please retry to get new reset link'; 
		header('Location: '.DIR.'login.php'); 
		exit(); 
	} 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        <?php echo SITETITLE;?>
    </title>
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
                <br />
                <br />
                <h2> <?php echo SITETITLE;?> : Reset Password</h2>

                <h5></h5>
                <br />
            </div>
        </div>
        <div class="row ">

            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php ?>
                        <strong>   Enter New Password </strong>

                        <?php messages(); if(isset($_POST[ 'password1'],$_POST[ 'password2'],$_POST[ 'submitPassword'])) { if(strlen($_POST[ 'password1']) < 4) { $_SESSION[ 'error']='Password too short! minimum 4 alphanumeric' ; header( 'Location: '.DIR. 'reset.php?token='.htmlentities($_GET[ 'token'])); exit(); } if($_POST[ 'password1'] !==$ _POST[ 'password2']) { $_SESSION[ 'error']='Both password is different' ; header( 'Location: '.DIR. 'reset.php?token='.htmlentities($_GET[ 'token'])); exit(); } if($_POST[ 'password1']==$ _POST[ 'password2']) { $sql="SELECT email FROM tokens WHERE token =? AND used='0' LIMIT 1" ; $con=$ user->db; if($stmt = $con->prepare($sql)) { $stmt->bind_param("s",$_GET['token']); $stmt->execute(); $stmt->bind_result($email); $stmt->store_result(); $stmt->fetch(); $stmt->close(); $query = "UPDATE members SET password=?, salt=? WHERE email=? LIMIT 1"; $statement = $con->prepare($query); $salt = randomSalt(); $hash = hash('sha256', $salt.$_POST['password1'].PEPPER); $statement->bind_param('sss', $hash,$salt,$email); if ($statement->execute()) { $query1 = "UPDATE tokens SET used = '1' WHERE token=? LIMIT 1"; $statement1 = $con->prepare($query1); $statement1->bind_param("s",$_GET['token']); if($statement1->execute()) { $statement->close(); $statement1->close(); $_SESSION['success'] = 'Password is changed, you can relogin using your new password'; header('Location: '.DIR.'login.php'); exit(); } else { $statement->close(); $statement1->close(); $_SESSION['error'] = 'Password fail to change, please get new reset link'; header('Location: '.DIR.'reset.php?token='.htmlentities($_GET['token'])); exit(); } } else { $statement->close(); $_SESSION['error'] = 'Password fail to change, please retry again'; header('Location: '.DIR.'reset.php?token='.htmlentities($_GET['token'])); exit(); } } } } ?>

                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="">
                            <br />
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                                <input type="password" class="form-control" placeholder="Enter password" name="password1" required />
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                                <input type="password" class="form-control" placeholder="Retype password" name="password2" required />
                            </div>
                            <input type="submit" name="submitPassword" value="reset" class="btn btn-primary " />
                            <hr /> Login? <a href="login.php">click here </a>
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