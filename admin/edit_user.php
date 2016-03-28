<?php
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/

require('../includes/config.php'); 

//make sure user is logged in, function will redirect use if not logged in
$user->admin_login_required();
$user->check_if_time_is_expired();

$stmt = $user->db->prepare("SELECT memberID FROM members WHERE memberID =? AND isadmin='0' LIMIT 1");
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$stmt->bind_result($id);
$stmt->store_result();
$stmt->fetch();
$stmt->close();

if($id == '')
{
	header('Location: '.DIRADMIN); 
	exit();
}


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SITETITLE;?> : Edit User</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="<?php echo DIR;?>assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="<?php echo DIR;?>assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
   
        <!-- CUSTOM STYLES-->
    <link href="<?php echo DIR;?>assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
     <!-- TABLE STYLES-->
    <link href="<?php echo DIR;?>assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
                        <a href="index.php"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
                    </li>               
                    <li>
                        <a  class="active-menu" href="#"><i class="fa fa-sitemap fa-3x"></i>Users<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="add_user.php">Add Users</a>
                            </li>
                            <li>
                                <a href="view_user.php">View Users</a>
                            </li>
                        </ul>
                      </li> 
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
                     <h2>Edit User</h2>   
                        <h5></h5>
                       
                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
               
            <div class="row">
                <div class="col-md-6">
                    <?php 
						if(isset($_POST['submit'])) {
							if(isset($_POST['csrf']) && $_SESSION['csrfkey'] == $_POST['csrf'])
							{
								$user->edit_user($_POST['ID'],$_POST['name'],$_POST['username'],$_POST['email'], $_POST['password'], $_POST['active']);
						
							} else {
								$user->logout();
							}
						}
					?>
                    <div class="panel panel-default">
                        <div class="panel-heading ">
                            <i class="fa fa-plus"></i>
								&nbsp; Edit Users
								<?php  
								messages(); 
								$csrfkey = $_SESSION['csrfkey'] = bin2hex(openssl_random_pseudo_bytes(16));
									 
									$id = $_GET['id'];
									$sql = "SELECT memberID, name, username, email, active FROM members WHERE memberID =? LIMIT 1";
									$con = $user->db;

									$stmt = $con->prepare($sql);
									
									$stmt->bind_param("i",$id);
									$stmt->execute();
									$stmt->bind_result($ID,$name,$username,$email,$status);
									$stmt->store_result();
									$stmt->fetch();
									$stmt->close();
									$enable = "";
									$disable = "";
									$banned = "";
									if( $status == 1 ) {
										$enable = "selected";
									} elseif( $status == 2 ) {
										$banned = "selected";
									} else {
										$disable = "selected";
									}
							
								?>
                        </div>
                        <div class="panel-body">
                                    <form role="form" method="post" action="">
                                        <div class="form-group">
										<input type="hidden" name="csrf" class="form-control" value="<?php echo htmlspecialchars($csrfkey); ?>" readonly />
										<input type="hidden" name="ID" class="form-control" value="<?php echo $ID; ?>" readonly />
                                            <label>Name</label>
											  <input type="text" value = "<?php echo htmlentities($name); ?>" name="name" data-required="true" class="form-control" />
										</div>
										<div class="form-group">
											  <label>Username</label>
											  <input type="text"  value = "<?php echo htmlentities($username); ?>" name="username" data-required="true"  class="form-control" id="username" />
										</div>
										<div id="disp"></div>
										<div class="form-group">
											  <label>Email</label>
											  <input type="email" value = "<?php echo htmlentities($email); ?>"  name="email"  class="form-control" />
										</div>
										<div class="form-group">
											  <label>Password</label>
											  <input type="password" placeholder="Leave blank if you don't want to change the password" name="password" data-required="true"  class="form-control" />
										</div>
										<div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="active">
                                                <option <?php echo $enable ?> value="enable">Enable</option>
                                                <option <?php echo $disable ?> value="disable">Disable</option>
												<option <?php echo $banned ?> value="banned">Banned</option>
                                            </select>
                                        </div>
										<button type="submit" name="submit" class="btn btn-default">Submit Button</button>
									 </form>
								
                        </div>
                    </div>
                    
                </div>
            </div>
       
        </div>
               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
     <!-- /. WRAPPER  -->
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
