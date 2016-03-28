<?php
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/

require('../includes/config.php'); 

//make sure user is logged in, function will redirect use if not logged in
$user->login_required();
$user->check_if_time_is_expired();

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SITETITLE;?> : Page Settings</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="<?php echo DIR;?>assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="<?php echo DIR;?>assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
    <link href=".<?php echo DIR;?>assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
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
	url: "../includes/user_check.php",
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
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?php echo SITETITLE;?></a> 
            </div>
  <div style="color: white;
padding: 15px 50px 5px 50px;
float: right;
font-size: 16px;"> Last access : <?php echo $_SESSION['lastlogin'];?> &nbsp; <a href="<?php echo DIRADMIN;?>logout.php" class="btn btn-danger square-btn-adjust"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a> </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				<li class="text-center">
                    <img src="<?php echo DIR;?>assets/img/find_user.png" class="user-image img-responsive"/>
					</li>
				
					
                    <li>
                        <a  href="index.php"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
                    </li>  
				    <?php if($user->admin_logged_in()) : ?>
                    <li>
                        <a href="#"><i class="fa fa-sitemap fa-3x"></i>Users<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="add_user.php">Add Users</a>
                            </li>
                            <li>
                                <a href="view_user.php">View Users</a>
                            </li>
                        </ul>
                      </li> 
					  <?php endif; ?>
					  <li>
                        <a class="active-menu" href="#"><i class="fa fa-desktop fa-3x"></i>Settings<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="page_settings.php">Profile Settings</a>
                            </li>
                        </ul>
                      </li>  
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Settings</h2> 
                    </div>
                </div>              
                           <!-- /. ROW  -->
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php 
							if(isset($_POST['submitpassword'])) {
								if(isset($_POST['csrf']) && $_SESSION['csrfkey'] == $_POST['csrf'])
								{
									$user->changepassword($_POST['password1'], $_POST['password2'],$_POST['ID']);
								} else {
									$user->logout();
								}
								
							}
							if(isset($_POST['submitprofile'])) {
								if(isset($_POST['csrf']) && $_SESSION['csrfkey'] == $_POST['csrf'])
								{
									$user->changeprofile($_POST['ID'], $_POST['name'], $_POST['username'], $_POST['email']);
								} else {
									$user->logout();
								}
								
							}
							?>
                        </div>
                        <div class="panel-body">
						<?php  
						messages(); 
						
						$csrfkey = $_SESSION['csrfkey'] = bin2hex(openssl_random_pseudo_bytes(16));
						?>
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#home-pills" data-toggle="tab">Profile Settings</a>
                                </li>
                                <li class=""><a href="#pass-pills" data-toggle="tab">Change Password</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home-pills">
								
                                    <h4>Edit Profile Settings</h4>
									<?php 
										$username = $_SESSION['username'];
										$sql = "SELECT memberID, name, username, email FROM members WHERE username =? LIMIT 1";
										$con = $user->db;

										$stmt = $con->prepare($sql);
										$stmt->bind_param("s",$username);
										$stmt->execute();
										$stmt->bind_result($ID,$name,$username,$email);
										$stmt->store_result();
										$stmt->fetch();
										$stmt->close();
										
									?>
									 <form role="form" method="post" action="">
									 <input type="hidden" name="csrf" class="form-control" value="<?php echo htmlspecialchars($csrfkey); ?>" readonly />
									 <input type="hidden" name="ID" class="form-control" value="<?php echo $ID; ?>" readonly />
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Name</span>
                                            <input class="form-control" value = "<?php echo htmlentities($name); ?>" type="text" name="name" required >
                                        </div>
                                     <div class="form-group input-group">
                                            <span class="input-group-addon">Username</span>
                                            <input class="form-control" value = "<?php echo htmlentities($username); ?>" type="text" name="username" id="username" required >
                                        </div>
										<div id="disp"></div>
                                         <div class="form-group input-group">
                                            <span class="input-group-addon">Email</span>
                                            <input class="form-control" value = "<?php echo htmlentities($email); ?>" type="email" name="email" required >
                                        </div>
                                     <p><input type="submit" name="submitprofile" value="Save Changes" class="btn btn-success "/></p>
                                    </form>
									
                                </div>
                                <div class="tab-pane fade" id="pass-pills">
                                    <h4>Change Your Password</h4>
									
									<form role="form" method="post" action="">
									<input type="hidden" name="csrf" class="form-control" value="<?php echo htmlspecialchars($csrfkey); ?>" readonly />
									 <input type="hidden" name="ID" class="form-control" value="<?php echo $ID; ?>" readonly />
                                      <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                            <input class="form-control" placeholder="Enter Password" type="password" name="password1" required >
                                        </div>
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                            <input class="form-control" placeholder="Retype Password" type="password" name="password2" required >
                                        </div>
										<p><input type="submit" name="submitpassword" value="Save Changes" class="btn btn-success "/></p> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    <!-- /. ROW  -->
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/jquery.metisMenu.js"></script>
     <!-- MORRIS CHART SCRIPTS -->
     <script src="<?php echo DIR;?>assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="<?php echo DIR;?>assets/js/morris/morris.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/custom.js"></script>
    
   
</body>
</html>
