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

//run if a user deletion has been requested
if(isset($_GET['deluser'])){
		
	$db = $user->db;
	$stmt = $db->prepare("DELETE FROM members WHERE memberID = ?");
	$stmt->bind_param('i', $_GET['deluser']);
	$stmt->execute();
	$stmt->close();
    $_SESSION['success'] = "User Deleted"; 
    header('Location: ' .DIRADMIN. 'index.php');
   	exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SITETITLE;?> : Dashboard</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="<?php echo DIR;?>assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="<?php echo DIR;?>assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
    <link href="<?php echo DIR;?>assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="<?php echo DIR;?>assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<script language="JavaScript" type="text/javascript">
			function deluser(id, username)
			{
			   if (confirm("Are you sure you want to delete '" + username + "'"))
			   {
				  window.location.href = '<?php echo DIRADMIN. 'index.php';?>?deluser=' + id;
			   }
			}
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
                        <a class="active-menu"  href="index.php"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
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
                        <a href="#"><i class="fa fa-desktop fa-3x"></i>Settings<span class="fa arrow"></span></a>
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
                     <h2>Dashboard</h2>   
                        <h5>Welcome back <b><?php echo	htmlentities($_SESSION['username']); ?></b>.</h5>
						<p><strong>Why is it secure?</strong></p>
						<ul>
						<li>Uses prepared statements so no SQL Injection!</li>
						<li>Protects against CSRF attacks!</li>
						<li>Captcha enabled to prevent spam!</li>
						<li>BruteForce prevention</li>
						</ul>
					<?php 
						messages(); 
						
						if($user->admin_logged_in())
						{
							// redirect to admin dashboard
							header('Location: '.DIRADMIN.'dashboard.php');
							exit();
						}
					?>
                    </div>
                </div>              
                
                 
			 <hr />
             <div class="alert alert-info" role="alert">You can add anything here...</div>
				
			</div>
                   
    </div>
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
