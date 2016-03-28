<?php
/**
	Made by Rizal Shahrizal
	@ Shahrizal.Net
	LAST MODIFIED: 30 APRIL 2015
	User Manager V2.1
**/

require('config.php'); 
if(isset($_POST['username']))
{
	$username = $_POST['username'];
	$username = preg_replace('/\s+/', '', $username);
	if(valid_name($username)===false)
	{
		echo "<div class='alert alert-danger' role='alert'>Only alphanumeric characters are allowed, minimum 3 characters</div>";
	} else {
		if($user->username_exists($username))
		{
			echo "<div class='alert alert-danger' role='alert'>Username exists</div>";
		} else {
			echo "<div class='alert alert-success' role='alert'>Available</div>";
		}
	}
}
?>