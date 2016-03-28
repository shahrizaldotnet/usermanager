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
    header('Location: ' .DIRADMIN. 'view_user.php');
   	exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo SITETITLE;?> : View User</title>
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
        <script language="JavaScript" type="text/javascript">
			function deluser(id, username)
			{
			   if (confirm("Are you sure you want to delete '" + username + "'"))
			   {
				  window.location.href = '<?php echo DIRADMIN. 'view_user.php';?>?deluser=' + id;
			   }
			}
		</script>
		<style>
		</style>
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
                        <a class="active-menu" href="#"><i class="fa fa-sitemap fa-3x"></i>Users<span class="fa arrow"></span></a>
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
                     <h2>User Management</h2>   
                        <h5></h5>
                       
                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
               
            <div class="row">
                <div class="col-md-12">
				<?php
						$sql = "SELECT memberID, name, username, email, lastlogin, ipadd, active FROM members WHERE isadmin = 0 ORDER BY memberID ASC";
						$db = $user->db;
						$result = $db->query($sql);
						
							
							
						
				?>
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading ">
                            <i class="fa fa-table"></i>
								&nbsp; View Users
								<?php  messages(); ?>
                        </div>
						
                        <div class="panel-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed" id="dataTables-example">
                                  
								  <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>UserName</th>
                                            <th>Email</th>
                                            <th>Last Login</th>
											<th>IP Address</th>
											<th>Status</th>
											<th>Actions</th>
                                        </tr>
                                    </thead>
								
                                    <tbody>
									<?php	while($row = $result->fetch_object())  { 
										$enable = $row->active;
										$username = htmlentities($row->username);
										$memberID = htmlentities($row->memberID);
									?>
                                        <tr>
										
                                            <td><?php echo $row->name; ?></td>
                                            <td><?php echo $username; ?></td>
                                            <td class="center"><?php echo $row->email; ?></td>
                                            <td class="center"><?php echo $row->lastlogin; ?></td>
											<td class="center"><?php echo long2ip($row->ipadd); ?></td>
											<?php if($enable==1) : ?>
											<td class="center"><span class="label label-success">Active</span></td>
											<?php elseif($enable==0) : ?>
											<td class="center"><span class="label label-default">Inactive</span></td>
											<?php else : ?>
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
                    <!--End Advanced Tables -->
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
     <!-- DATA TABLE SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo DIR;?>assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
		</script>
		
         <!-- CUSTOM SCRIPTS -->
    <script src="<?php echo DIR;?>assets/js/custom.js"></script>
    
   
</body>
</html>
