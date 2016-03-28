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
					<?php 
				    messages(); 
					
					$act = 0;
					$sql = "SELECT active FROM members WHERE isadmin = ?";
					$con = $user->db;
					$stmt = $con->prepare($sql);
					$stmt->bind_param("i",$act);
					$stmt->execute();
					$stmt->bind_result($status);
					$stmt->store_result();
					$active = 0;
					$inactive = 0;
					$totalusers = 0; 
					while($stmt->fetch()) {
						
						if($status==1) {
							$active += 1;
						} elseif($status==2) {
							$inactive += 1;
						}
						$totalusers += 1;
					}
					$stmt->close();
					?>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                <div class="row">
                <div class="col-md-3">           
			<div class="panel panel-back noti-box bg-color-brown">
                <span class="icon-box set-icon">
                    <i class="fa fa-users"></i>
                </span>
                <div class="text-box" >
                    <p class="main-text"><?php echo $totalusers; ?></p>
					<p class="main-muted">Total Users</p>
                    
                </div>
             </div>
			  </div>
		         <div class="col-md-3">    
				<div class="panel panel-back noti-box bg-color-green">
					<span class="icon-box set-icon">
						<i class="fa fa-users"></i>
					</span>
					<div class="text-box" >
						<p class="main-text"><?php echo $active; ?></p>
						<p class="main-muted">Active</p>
					</div>
				 </div>
		             </div>
		         <div class="col-md-3">    
				<div class="panel panel-back noti-box bg-color-red">
					<span class="icon-box set-icon">
						<i class="fa fa-users"></i>
					</span>
					<div class="text-box" >
						<p class="main-text"><?php echo $inactive; ?></p>
						<p class="main-muted">Banned</p>
					</div>
				 </div>
				  </div>
		     </div>
			 <hr />
                <div class="row">
					 <div class="col-lg-8 col-md-8">
					 <?php
						$sql = "SELECT memberID, username, email, active FROM members WHERE isadmin = 0 ORDER BY memberID DESC LIMIT 5";
						$db = $user->db;
						$result = $db->query($sql);
					?>
					<div class="panel panel-default">
						  <!-- Default panel contents -->
						  <div class="panel-heading"><b>Recents Signup</b> 
						    
						    
							<a class="btn btn-default btn-sm navbar-right" href="add_user.php" ><span class="glyphicon glyphicon-plus " aria-hidden="true"></span> <i class="fa fa-user"></i></a>
							
							
						  </div>

						  <!-- Table -->
						  <div class="table-responsive">
							 <table class="table table-bordered table-condensed">
								<thead>
									<tr>
										
										<th>UserName</th>
										<th>Email</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
								<?php	
								while($row = $result->fetch_object())  { 
								$enable = $row->active;
								$username = htmlentities($row->username);
								$memberID = htmlentities($row->memberID);
								?>
								<tr>
								 
								 <td><?php echo htmlentities($username); ?></td>
								 <td><?php echo htmlentities($row->email); ?></td>
								 <?php if($enable == 1) : ?>
									<td class="center"><span class="label label-success">Active</span></td>
								 <?php  elseif($enable == 0) : ?>
									<td class="center"><span class="label label-default">Inactive</span></td>
								 <?php  else : ?>
									<td class="center"><span class="label label-danger">Banned</span></td>
								<?php endif; ?>
							    <td class="center">
											<?php echo "<a href=\"".DIRADMIN."edit_user.php?id=$memberID\"><button class='btn btn-primary btn-xs'><i class='fa fa-edit'></i></button></a>";?>
											<?php echo "<a href=\"javascript:deluser('$memberID','$username');\"><button class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></button></a>";?>
								</td>
								</tr>
								<?php } ?>
								</tbody>
							  </table>
						  </div>
						</div>
					</div>
				</div>
				
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
